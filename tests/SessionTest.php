<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Authentication;
use Bybit\Configuration;
use Bybit\Session;
use Bybit\Exception\{
    AuthException,
    RateLimitException,
    ServerException,
    ClientException as BybitClientException,
    ConfigurationException,
    NetworkException,
    TimeoutException,
    TransportException,
};
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Session-level regressions: signature payload branch by HTTP verb, header
 * presence, error-class mapping, transport exception mapping, redirect safety,
 * and the load-bearing signing invariant (payload signed == wire query).
 */
final class SessionTest extends TestCase
{
    /**
     * @param list<Response|ConnectException|TransferException> $responses
     * @param list<array<string,mixed>>                          $history
     */
    private function makeSession(array $responses, array &$history): Session
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $config = new Configuration();
        $config->apiKey    = 'test-key';
        $config->apiSecret = 'test-secret';
        $config->baseUrl   = 'https://api-testnet.bybit.com';
        $config->recvWindow = '5000';
        $config->httpClient = new GuzzleClient([
            'handler' => $stack,
            'base_uri' => 'https://api-testnet.bybit.com',
            'http_errors' => false,
        ]);

        return new Session($config);
    }

    private function okBody(int $retCode = 0, string $retMsg = 'OK'): string
    {
        return json_encode([
            'retCode' => $retCode,
            'retMsg'  => $retMsg,
            'result'  => new \stdClass(),
            'retExtInfo' => new \stdClass(),
            'time'    => 0,
        ]) ?: '';
    }

    public function testSignsGetWithBapiHeaders(): void
    {
        $history = [];
        $session = $this->makeSession([new Response(200, ['Content-Type' => 'application/json'], $this->okBody())], $history);
        $session->signRequest('GET', '/v5/account/wallet-balance', ['accountType' => 'UNIFIED']);

        $req = $history[0]['request'];
        foreach (['X-BAPI-API-KEY', 'X-BAPI-TIMESTAMP', 'X-BAPI-RECV-WINDOW', 'X-BAPI-SIGN', 'X-BAPI-SIGN-TYPE'] as $h) {
            $this->assertTrue($req->hasHeader($h), "missing header: $h");
        }
        $this->assertStringContainsString('accountType=UNIFIED', (string) $req->getUri());
    }

    public function testSignsPostWithJsonBody(): void
    {
        $history = [];
        $session = $this->makeSession([new Response(200, ['Content-Type' => 'application/json'], $this->okBody())], $history);
        $session->signRequest('POST', '/v5/order/create', ['category' => 'linear', 'symbol' => 'BTCUSDT']);

        $req = $history[0]['request'];
        $this->assertSame('POST', $req->getMethod());
        $this->assertStringNotContainsString('?', (string) $req->getUri());
        $this->assertSame('application/json', $req->getHeaderLine('Content-Type'));
    }

    public function testSignsDeleteWithParams(): void
    {
        $history = [];
        $session = $this->makeSession([new Response(200, ['Content-Type' => 'application/json'], $this->okBody())], $history);
        $session->signRequest('DELETE', '/v5/order/cancel', ['orderId' => 'abc123']);

        $req = $history[0]['request'];
        $this->assertSame('DELETE', $req->getMethod());
        $this->assertStringContainsString('orderId=abc123', (string) $req->getUri());
    }

    public function testMissingApiKeyThrows(): void
    {
        $config = new Configuration();
        $config->apiKey = null;
        $config->apiSecret = null;
        $session = new Session($config);
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessageMatches('/apiKey/');
        $session->signRequest('GET', '/v5/account/wallet-balance');
    }

    public function testAuthExceptionOnRetCode10004(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody(10004, 'error sign')),
        ], $history);
        $this->expectException(AuthException::class);
        $session->signRequest('GET', '/v5/account/wallet-balance');
    }

    public function testRateLimitExceptionOnRetCode10006(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody(10006, 'too many')),
        ], $history);
        $this->expectException(RateLimitException::class);
        $session->signRequest('GET', '/v5/account/wallet-balance');
    }

    public function testServerExceptionOn5xxNonJson(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(502, [], '<html>Bad Gateway</html>'),
        ], $history);
        $this->expectException(ServerException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    public function testClientExceptionOn4xxNonJson(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(400, [], 'bad request'),
        ], $history);
        $this->expectException(BybitClientException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    // ── P1: transport-level exception mapping ────────────────────────────────
    // MockHandler accepts Exceptions in the queue — those bubble as if the
    // underlying handler failed. This exercises the catch blocks that
    // integration tests would otherwise never hit.

    public function testTimeoutExceptionOnCurlTimeout(): void
    {
        $history = [];
        // cURL surfaces read/connect timeouts as ConnectException on Guzzle 7.
        // The exact message string is what our dispatch() classifier greps.
        $session = $this->makeSession([
            new ConnectException(
                'cURL error 28: Operation timed out after 5000 milliseconds',
                new Request('GET', '/v5/market/time')
            ),
        ], $history);
        $this->expectException(TimeoutException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    public function testNetworkExceptionOnConnectRefused(): void
    {
        $history = [];
        $session = $this->makeSession([
            new ConnectException(
                'cURL error 7: Failed to connect to api-testnet.bybit.com port 443: Connection refused',
                new Request('GET', '/v5/market/time')
            ),
        ], $history);
        $this->expectException(NetworkException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    public function testTransportExceptionOnGenericTransferError(): void
    {
        $history = [];
        // Non-timeout, non-connect TransferException — a lower-level Guzzle
        // fault (e.g. cURL error 60 SSL) that isn't a Connect problem per se.
        $session = $this->makeSession([
            new TransferException('cURL error 60: SSL certificate problem'),
        ], $history);
        $this->expectException(TransportException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    // ── P1: signing-invariant test ───────────────────────────────────────────
    // The single most important property of Session: whatever query string
    // shows up on the wire must be the EXACT string that got fed into signV5.
    // If sortAndEncode ever drifts from what Guzzle emits, every signed call
    // goes 401.

    public function testSignedGetPayloadEqualsWireQuery(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        // Deliberately non-alphabetical input to lock the sort behavior.
        $session->signRequest('GET', '/v5/account/wallet-balance', [
            'symbol' => 'BTCUSDT',
            'accountType' => 'UNIFIED',
            'coin' => 'BTC',
        ]);

        $req = $history[0]['request'];
        $wireQuery = $req->getUri()->getQuery();
        $signOnWire = $req->getHeaderLine('X-BAPI-SIGN');
        $ts = $req->getHeaderLine('X-BAPI-TIMESTAMP');
        $rw = $req->getHeaderLine('X-BAPI-RECV-WINDOW');
        $apiKey = $req->getHeaderLine('X-BAPI-API-KEY');

        // Recompute independently using the SAME query bytes the server will see.
        $recomputed = Authentication::signV5('test-secret', $ts, $apiKey, $rw, $wireQuery);
        $this->assertSame($signOnWire, $recomputed, 'X-BAPI-SIGN diverges from wire-query re-derivation');

        // Lock the sort: alphabetical by key.
        $this->assertSame('accountType=UNIFIED&coin=BTC&symbol=BTCUSDT', $wireQuery);
    }

    public function testSignedPostBodyEqualsSignaturePayload(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $session->signRequest('POST', '/v5/order/create', [
            'category' => 'linear',
            'symbol' => 'BTCUSDT',
            'side' => 'Buy',
        ]);

        $req = $history[0]['request'];
        $bodyOnWire = (string) $req->getBody();
        $signOnWire = $req->getHeaderLine('X-BAPI-SIGN');
        $ts = $req->getHeaderLine('X-BAPI-TIMESTAMP');
        $rw = $req->getHeaderLine('X-BAPI-RECV-WINDOW');
        $apiKey = $req->getHeaderLine('X-BAPI-API-KEY');

        $recomputed = Authentication::signV5('test-secret', $ts, $apiKey, $rw, $bodyOnWire);
        $this->assertSame($signOnWire, $recomputed, 'POST body diverges from signature payload');
        $this->assertStringNotContainsString('?', (string) $req->getUri(), 'POST must not leak params into query');
    }

    // ── P1: scalar-normalization on the query string ─────────────────────────

    public function testBooleanQueryValueSerializesAsWord(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        // signRequest is fine — we only care about the wire query encoding.
        $session->signRequest('GET', '/v5/foo', [
            'onlyOrderbook' => true,
            'includeArchived' => false,
        ]);

        $wireQuery = $history[0]['request']->getUri()->getQuery();
        // 'true' / 'false' string form — Bybit rejects '1' / '' for boolean params.
        $this->assertStringContainsString('includeArchived=false', $wireQuery);
        $this->assertStringContainsString('onlyOrderbook=true', $wireQuery);
        // Guard against a regression to PHP's '(string) false === ""' cast.
        $this->assertStringNotContainsString('includeArchived=&', $wireQuery);
        $this->assertStringNotContainsString('=1&onlyOrderbook', $wireQuery);
    }

    public function testFloatQueryValueAvoidsScientificNotation(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $session->signRequest('GET', '/v5/foo', [
            'tiny'  => 1.0e-9,
            'big'   => 12345.6789,
            'zero'  => 0.0,
        ]);

        $wireQuery = $history[0]['request']->getUri()->getQuery();
        // Must not emit 'E-' / 'e-' in numeric values — Bybit rejects.
        $this->assertStringNotContainsString('E-', $wireQuery);
        $this->assertStringNotContainsString('e-', $wireQuery);
        $this->assertStringContainsString('big=12345.6789', $wireQuery);
        $this->assertStringContainsString('tiny=0.00000000', $wireQuery); // scaled-out prefix
        $this->assertStringContainsString('zero=0', $wireQuery);
    }

    // ── P2P legacy envelope normalization ────────────────────────────────
    // P2P endpoints (/v5/p2p/*) return snake_case fields (ret_code / ret_msg /
    // time_now / ext_info) instead of the V5 standard camelCase. Session must
    // recognize both shapes and normalize to the canonical form so downstream
    // consumers can rely on $r['retCode'].

    public function testP2pSnakeCaseEnvelopeNormalizedOnSuccess(): void
    {
        $history = [];
        $legacyBody = json_encode([
            'ret_code' => 0,
            'ret_msg' => 'SUCCESS',
            'result' => ['nickName' => 'Test'],
            'ext_code' => '',
            'ext_info' => new \stdClass(),
            'time_now' => '1700000000.123456',
        ]) ?: '';
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $legacyBody),
        ], $history);

        $r = $session->signRequest('GET', '/v5/p2p/user/personal/info');
        $this->assertSame(0, $r['retCode']);
        $this->assertSame('SUCCESS', $r['retMsg']);
        // time_now is float-seconds; time is int-milliseconds — 1700000000.123 * 1000 ≈ 1700000000123.
        $this->assertSame(1700000000123, $r['time']);
        // Original keys preserved for callers who want them.
        $this->assertSame(0, $r['ret_code']);
        $this->assertSame('SUCCESS', $r['ret_msg']);
    }

    public function testP2pSnakeCaseEnvelopeRoutesToApiExceptionOnError(): void
    {
        $history = [];
        $legacyBody = json_encode([
            'ret_code' => 912000004,
            'ret_msg' => '[912000004] Parameter exception',
            'result' => new \stdClass(),
            'ext_code' => '',
            'ext_info' => new \stdClass(),
            'time_now' => '1700000000.123456',
        ]) ?: '';
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $legacyBody),
        ], $history);

        $this->expectException(\Bybit\Exception\ApiException::class);
        try {
            $session->signRequest('GET', '/v5/p2p/item/online');
        } catch (\Bybit\Exception\ApiException $e) {
            $this->assertSame(912000004, $e->getRetCode());
            throw $e;
        }
    }

    // ── P1: redirect-safety guard ────────────────────────────────────────────

    public function testAutoRedirectsDisabledForSignedCalls(): void
    {
        $history = [];
        $session = $this->makeSession([
            // 302 → Location. Even though target host is HTTPS-legit, we must
            // NOT follow it and re-send X-BAPI-SIGN. Session should surface the
            // 3xx as a ParseException (or similar transport-shaped error).
            new Response(302, ['Location' => 'https://evil.example.com/leak'], ''),
        ], $history);
        try {
            $session->signRequest('GET', '/v5/account/wallet-balance');
            $this->fail('expected an exception for a 3xx response');
        } catch (\Bybit\Exception\BybitException $e) {
            // OK — any Bybit-family exception is acceptable here.
        }
        // Critical assertion: only ONE request was issued (the redirect wasn't
        // followed). If Guzzle had followed, MockHandler would have gotten a
        // second dequeue and thrown "queue is empty" — but that would raise a
        // Guzzle exception, not a bybit-family one.
        $this->assertCount(1, $history, 'redirect was followed — X-BAPI-SIGN would leak');
    }
}

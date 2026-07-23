<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Authentication;
use Bybit\Configuration;
use Bybit\Exception\{
    AuthException,
    ClientException as BybitClientException,
    ConfigurationException,
    NetworkException,
    RateLimitException,
    ServerException,
    TimeoutException,
    TransportException,
};
use Bybit\Session;
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

        $config = new Configuration(
            apiKey: 'test-key',
            apiSecret: 'test-secret',
            recvWindow: '5000',
            baseUrl: 'https://api-testnet.bybit.com',
            httpClient: new GuzzleClient([
                'handler' => $stack,
                'base_uri' => 'https://api-testnet.bybit.com',
                'http_errors' => false,
            ]),
        );

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
        // Lock the trailing significant digit — 'tiny=0.00000000' alone would
        // pass under a precision regression that drops the final '1'.
        $this->assertStringContainsString('tiny=0.000000001', $wireQuery);
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

    // ── ApiException dispatch table regression lock ──────────────────────────
    // ApiException::fromResponse routes 10 retCodes to typed subclasses. If a
    // future edit removes / reshuffles a code, downstream retry/backoff logic
    // silently misclassifies — one of the most damaging regressions a signing
    // SDK can ship. Iterate every code.

    /**
     * @return list<array{int,class-string<\Bybit\Exception\ApiException>}>
     */
    public static function apiExceptionDispatchProvider(): array
    {
        return [
            [10002, AuthException::class],
            [10003, AuthException::class],
            [10004, AuthException::class],
            [10005, AuthException::class],
            [10007, AuthException::class],
            [10008, AuthException::class],
            [10009, AuthException::class],
            [10010, AuthException::class],
            [10017, AuthException::class],
            [10022, AuthException::class],
            [10024, AuthException::class],
            [10028, AuthException::class],
            [10029, AuthException::class],
            [10006, RateLimitException::class],
            [10018, RateLimitException::class],
            // Unmapped code stays as the base class.
            [12345, \Bybit\Exception\ApiException::class],
        ];
    }

    /**
     * @dataProvider apiExceptionDispatchProvider
     * @param class-string<\Bybit\Exception\ApiException> $expected
     */
    public function testApiExceptionDispatchTable(int $retCode, string $expected): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody($retCode, 'nope')),
        ], $history);
        try {
            $session->signRequest('GET', '/v5/foo');
            $this->fail("expected {$expected} for retCode {$retCode}");
        } catch (\Bybit\Exception\ApiException $e) {
            $this->assertInstanceOf($expected, $e);
            $this->assertSame($retCode, $e->getRetCode());
        }
    }

    // ── Header-shape locks: format of X-BAPI-TIMESTAMP + X-BAPI-RECV-WINDOW ──
    // testSignedGetPayloadEqualsWireQuery re-derives the signature FROM the wire
    // headers, so a regression to microtime()/ISO-8601 timestamps would still be
    // internally consistent and pass. Assert the SHAPE explicitly.

    public function testTimestampHeaderIsThirteenDigitMsEpoch(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $session->signRequest('GET', '/v5/foo');

        $req = $history[0]['request'];
        $ts = $req->getHeaderLine('X-BAPI-TIMESTAMP');
        $this->assertMatchesRegularExpression('/^\d{13}$/', $ts, 'X-BAPI-TIMESTAMP must be a 13-digit ms-epoch');
        $nowMs = (int) floor(microtime(true) * 1000);
        $this->assertLessThan(5000, abs($nowMs - (int) $ts), 'X-BAPI-TIMESTAMP drifted from now by >5s');

        $this->assertSame('5000', $req->getHeaderLine('X-BAPI-RECV-WINDOW'));
    }

    public function testRecvWindowDefaultAppliesWhenNotOverridden(): void
    {
        // Reach past makeSession() — that helper hardcodes recvWindow='5000',
        // which would mask a default-value regression. Build Session directly.
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ]);
        $history = [];
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $config = new Configuration(
            apiKey: 'k',
            apiSecret: 's',
            baseUrl: 'https://api-testnet.bybit.com',
            httpClient: new GuzzleClient([
                'handler' => $stack,
                'base_uri' => 'https://api-testnet.bybit.com',
                'http_errors' => false,
            ]),
        );
        $session = new Session($config);
        $session->signRequest('GET', '/v5/foo');

        $this->assertSame(\Bybit\Bybit::DEFAULT_RECV_WINDOW, $history[0]['request']->getHeaderLine('X-BAPI-RECV-WINDOW'));
    }

    // ── WireKeys wire-in ─────────────────────────────────────────────────────
    // Session::dispatch calls WireKeys::camelize on caller params before
    // signing / serializing. A caller passing snake_case must see camelCase on
    // the wire — else the Bybit V5 server 10001-rejects.

    public function testSnakeCaseCallerKeysAreCamelizedOnWire(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $session->signRequest('POST', '/v5/order/create', [
            'order_type' => 'Limit',
            'time_in_force' => 'GTC',
            'symbol' => 'BTCUSDT',
        ]);

        $req = $history[0]['request'];
        $bodyOnWire = (string) $req->getBody();
        $this->assertStringContainsString('"orderType":"Limit"', $bodyOnWire);
        $this->assertStringContainsString('"timeInForce":"GTC"', $bodyOnWire);
        // Original snake_case must NOT survive to the wire.
        $this->assertStringNotContainsString('order_type', $bodyOnWire);
        $this->assertStringNotContainsString('time_in_force', $bodyOnWire);
    }

    public function testSnakeCaseCallerKeysAreCamelizedOnQueryString(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $session->publicRequest('GET', '/v5/market/kline', [
            'category' => 'spot',
            'symbol' => 'BTCUSDT',
            'start_time' => 1700000000,
        ]);

        $wireQuery = $history[0]['request']->getUri()->getQuery();
        $this->assertStringContainsString('startTime=1700000000', $wireQuery);
        $this->assertStringNotContainsString('start_time', $wireQuery);
    }

    // ── HTTP-status → exception mapping for non-envelope 4xx ─────────────────
    // README documents 401/403 → AuthException, 429 → RateLimitException even
    // when the response body is not a Bybit envelope (WAF / CDN / edge auth).

    public function testHttp401NonEnvelopeMapsToAuthException(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(401, [], 'Unauthorized'),
        ], $history);
        $this->expectException(AuthException::class);
        $session->signRequest('GET', '/v5/account/wallet-balance');
    }

    public function testHttp403NonEnvelopeMapsToAuthException(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(403, [], 'Forbidden'),
        ], $history);
        $this->expectException(AuthException::class);
        $session->signRequest('GET', '/v5/account/wallet-balance');
    }

    public function testHttp429NonEnvelopeMapsToRateLimitException(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(429, [], 'Too Many Requests'),
        ], $history);
        $this->expectException(RateLimitException::class);
        $session->publicRequest('GET', '/v5/market/time');
    }

    // ── P2P legacy envelope → AuthException routing ──────────────────────────
    // If normalizeLegacyEnvelope / fromResponse ordering regresses, a P2P
    // ret_code=10004 would silently downgrade to base ApiException. Lock it.

    // ── sortAndEncode nested-assoc guard ─────────────────────────────────────
    // GET/DELETE support only flat scalar + list<scalar> params. A nested
    // associative array or list-of-arrays would previously silently mis-encode
    // (dropping inner keys, or emitting literal 'Array'). Session now rejects
    // at the source so latent regressions surface immediately.

    public function testNestedAssocArrayOnQueryRejected(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/nested/i');
        $session->publicRequest('GET', '/v5/foo', ['filter' => ['side' => 'Buy']]);
    }

    public function testListOfArraysOnQueryRejected(): void
    {
        $history = [];
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $this->okBody()),
        ], $history);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/list-of-arrays/i');
        $session->publicRequest('GET', '/v5/foo', ['batch' => [['a' => 1], ['a' => 2]]]);
    }

    public function testP2pLegacyEnvelopeRoutesRetCode10004ToAuthException(): void
    {
        $history = [];
        $legacyBody = json_encode([
            'ret_code' => 10004,
            'ret_msg' => 'error sign',
            'result' => new \stdClass(),
            'ext_info' => new \stdClass(),
            'time_now' => '1700000000.000000',
        ]) ?: '';
        $session = $this->makeSession([
            new Response(200, ['Content-Type' => 'application/json'], $legacyBody),
        ], $history);
        $this->expectException(AuthException::class);
        $session->signRequest('POST', '/v5/p2p/order/simplifyList');
    }
}

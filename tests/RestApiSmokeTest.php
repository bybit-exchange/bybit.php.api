<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Client;
use Bybit\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Reflection-driven smoke test for every public method on every RestApi
 * service class. Each method is invoked with type-appropriate dummy arguments
 * and asserted to:
 *   1. Dispatch exactly one HTTP request via Session (proving the wiring is
 *      intact — no missing session call, no exception before dispatch).
 *   2. Send a URL under `/v5/`.
 *   3. Use a Bybit V5 verb (GET, POST, PUT, or DELETE).
 *
 * This is the ~240-endpoint safety net the initial commit lacked — a wrong
 * HTTP verb, a broken PSR-4 mapping, or a method that forgets to call session
 * would previously ship green. It doesn't validate the exact path shape (that
 * would duplicate source); it locks the load-bearing invariant that every
 * public service method reaches the wire at all.
 */
final class RestApiSmokeTest extends TestCase
{
    private const OK_ENVELOPE = '{"retCode":0,"retMsg":"OK","result":{},"retExtInfo":{},"time":0}';

    private const V5_VERBS = ['GET', 'POST', 'PUT', 'DELETE'];

    /** @return list<class-string> */
    private static function serviceClasses(): array
    {
        return [
            \Bybit\RestApi\AccountService::class,
            \Bybit\RestApi\AffiliateService::class,
            \Bybit\RestApi\AssetService::class,
            \Bybit\RestApi\BotService::class,
            \Bybit\RestApi\BrokerService::class,
            \Bybit\RestApi\CryptoLoanService::class,
            \Bybit\RestApi\EarnService::class,
            \Bybit\RestApi\MarketService::class,
            \Bybit\RestApi\P2pService::class,
            \Bybit\RestApi\PositionService::class,
            \Bybit\RestApi\RfqService::class,
            \Bybit\RestApi\SpotMarginService::class,
            \Bybit\RestApi\TradeService::class,
            \Bybit\RestApi\UserService::class,
        ];
    }

    /**
     * @return iterable<string,array{class-string,string}>
     */
    public static function serviceMethodProvider(): iterable
    {
        foreach (self::serviceClasses() as $class) {
            $ref = new \ReflectionClass($class);
            foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $class) {
                    continue; // skip inherited (BaseService constructor, etc.)
                }
                if ($method->isConstructor() || $method->isStatic()) {
                    continue;
                }
                $shortClass = $ref->getShortName();
                yield "{$shortClass}::{$method->getName()}" => [$class, $method->getName()];
            }
        }
    }

    /**
     * @dataProvider serviceMethodProvider
     * @param class-string $serviceClass
     */
    public function testEveryServiceMethodDispatchesAv5Request(string $serviceClass, string $methodName): void
    {
        // 240+ service methods — one shared 200-OK mock queue per test call.
        $history = [];
        $client = $this->makeClient(new Response(200, ['Content-Type' => 'application/json'], self::OK_ENVELOPE), $history);

        $service = $this->serviceProperty($client, $serviceClass);
        $method = new \ReflectionMethod($serviceClass, $methodName);
        $args = $this->dummyArgs($method);

        // Some methods might complete via publicRequest without needing keys —
        // Configuration below has apiKey/apiSecret set so signed calls also
        // succeed. Any exception here is a real bug.
        $method->invokeArgs($service, $args);

        $this->assertCount(1, $history, "{$serviceClass}::{$methodName} did not dispatch exactly one request");
        $req = $history[0]['request'];
        $verb = $req->getMethod();
        $this->assertContains(
            $verb,
            self::V5_VERBS,
            "{$serviceClass}::{$methodName} used non-V5 verb: {$verb}"
        );
        $path = $req->getUri()->getPath();
        $this->assertStringStartsWith(
            '/v5/',
            $path,
            "{$serviceClass}::{$methodName} dispatched to non-/v5/ path: {$path}"
        );
    }

    private function makeClient(Response $response, array &$history): Client
    {
        $mock = new MockHandler([$response]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $config = new Configuration(
            apiKey: 'test-key',
            apiSecret: 'test-secret',
            baseUrl: 'https://api-testnet.bybit.com',
            httpClient: new GuzzleClient([
                'handler' => $stack,
                'base_uri' => 'https://api-testnet.bybit.com',
                'http_errors' => false,
            ]),
        );

        return new Client($config);
    }

    /**
     * Map a service class name to its property on Client (e.g.
     * MarketService → $client->market, CryptoLoanService → $client->cryptoLoan).
     */
    private function serviceProperty(Client $client, string $serviceClass): object
    {
        $short = (new \ReflectionClass($serviceClass))->getShortName(); // e.g. "CryptoLoanService"
        $trimmed = substr($short, 0, -strlen('Service'));                // "CryptoLoan"
        $prop = lcfirst($trimmed);                                       // "cryptoLoan"
        /** @var object $svc */
        $svc = $client->{$prop};
        return $svc;
    }

    /**
     * Generate a dummy argument for every declared parameter based on type.
     * Bybit service methods use string / int / bool / array — no unions or
     * complex objects.
     *
     * @return list<mixed>
     */
    private function dummyArgs(\ReflectionMethod $method): array
    {
        $args = [];
        foreach ($method->getParameters() as $p) {
            if ($p->isOptional()) {
                // Fall through to defaults — usually `[] $options`.
                break;
            }
            $type = $p->getType();
            $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : 'string';
            $args[] = match ($typeName) {
                'int'   => 1,
                'bool'  => false,
                'array' => [],
                'float' => 1.0,
                default => 'dummy',
            };
        }
        return $args;
    }
}

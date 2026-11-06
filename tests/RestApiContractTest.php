<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Authentication;
use Bybit\Client;
use Bybit\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class RestApiContractTest extends TestCase
{
    /** @return iterable<string,array{string}> */
    public static function removedAssetMethodProvider(): iterable
    {
        foreach ([
            'queryOrderFromOpen',
            'coinConvertLimitQuery',
            'limitOrderCallback',
            'assetInfoQuery',
            'transferSubMemberSave',
        ] as $method) {
            yield $method => [$method];
        }
    }

    public function testTradeInfoForAnalysisRequiresAndSendsSymbol(): void
    {
        $history = [];
        $client = $this->makeClient($history);

        $client->account->getTradeInfoForAnalysis('BTCUSDT', ['startTime' => 1000]);

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/v5/account/trade-info-for-analysis', $request->getUri()->getPath());
        $this->assertSame('startTime=1000&symbol=BTCUSDT', $request->getUri()->getQuery());
    }

    public function testFixedBorrowMarketKeepsOrderByOptional(): void
    {
        $history = [];
        $client = $this->makeClient($history);

        $client->spotMargin->queryFixedBorrowMarket('USDT', ['orderBy' => 'apy', 'limit' => 1]);

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/v5/spot-margin-trade/fixedborrow-order-quote', $request->getUri()->getPath());
        $this->assertSame('limit=1&orderBy=apy&orderCurrency=USDT', $request->getUri()->getQuery());
    }

    public function testPredictionSideMarketSendsEventId(): void
    {
        $history = [];
        $client = $this->makeClient($history);

        $client->alpha->getPredictionSideMarketList('event-123');

        $body = json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['eventId' => 'event-123'], $body);
    }

    public function testPredictionGroupStageUsesDocumentedContract(): void
    {
        $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, 'getPredictionGroupStageDetail');
        $params = $method->getParameters();

        $this->assertSame(['eventType', 'options'], array_map(static fn (\ReflectionParameter $p): string => $p->getName(), $params));
        $this->assertSame('int', (string) $params[0]->getType());

        $history = [];
        $client = $this->makeClient($history);
        $client->alpha->getPredictionGroupStageDetail(1, ['groupName' => 'A']);

        $body = json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['groupName' => 'A', 'eventType' => 1], $body);
    }

    public function testPredictionMatchListUsesIntegerEventType(): void
    {
        $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, 'getPredictionMatchList');
        $this->assertSame('int', (string) $method->getParameters()[0]->getType());
    }

    public function testPredictionTimelineStagesRequiresEventType(): void
    {
        $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, 'getPredictionTimelineStages');
        $params = $method->getParameters();

        $this->assertSame(['eventType', 'options'], array_map(static fn (\ReflectionParameter $p): string => $p->getName(), $params));
        $this->assertSame('int', (string) $params[0]->getType());

        $history = [];
        $client = $this->makeClient($history);
        $client->alpha->getPredictionTimelineStages(1);

        $this->assertSame('eventType=1', $history[0]['request']->getUri()->getQuery());
    }

    public function testPredictionPriceHistoryRequiresTokenIdAndInterval(): void
    {
        $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, 'getPredictionPriceHistory');
        $params = $method->getParameters();

        $this->assertSame(['tokenId', 'interval', 'options'], array_map(static fn (\ReflectionParameter $p): string => $p->getName(), $params));

        $history = [];
        $client = $this->makeClient($history);
        $client->alpha->getPredictionPriceHistory('token-123', '1h');

        $body = json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['tokenId' => 'token-123', 'interval' => '1h'], $body);
    }

    public function testAlphaTradeOrderListRequiresPagination(): void
    {
        $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, 'getOrderList');
        $params = $method->getParameters();

        $this->assertSame(['limit', 'pageIndex', 'options'], array_map(static fn (\ReflectionParameter $p): string => $p->getName(), $params));
        $this->assertSame('int', (string) $params[0]->getType());
        $this->assertSame('int', (string) $params[1]->getType());

        $history = [];
        $client = $this->makeClient($history);
        $client->alpha->getOrderList(20, 1);

        $body = json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['limit' => 20, 'pageIndex' => 1], $body);
    }

    public function testPredictionEnumParametersUseIntegerTypes(): void
    {
        foreach ([
            'executePredictionBuy' => ['orderType'],
            'executePredictionSell' => ['orderType'],
            'getPredictionOrderEstimate' => ['side', 'orderType'],
        ] as $methodName => $integerParams) {
            $method = new \ReflectionMethod(\Bybit\RestApi\AlphaService::class, $methodName);
            $params = [];
            foreach ($method->getParameters() as $param) {
                $params[$param->getName()] = (string) $param->getType();
            }
            foreach ($integerParams as $paramName) {
                $this->assertSame('int', $params[$paramName], "{$methodName}::\${$paramName} must be int");
            }
        }
    }

    public function testInstitutionalLoanProductInfoIsPublic(): void
    {
        $history = [];
        $client = $this->makeClient($history, false);

        try {
            $client->lending->insLoanProductInfos();
        } catch (\Bybit\Exception\ConfigurationException $e) {
            $this->fail('Public product info endpoint unexpectedly required credentials: ' . $e->getMessage());
        }

        $request = $history[0]['request'];
        $this->assertFalse($request->hasHeader('X-BAPI-API-KEY'));
        $this->assertFalse($request->hasHeader('X-BAPI-SIGN'));
    }

    public function testMarginCoinConversionInfoIsPublicWithoutCredentials(): void
    {
        $history = [];
        $client = $this->makeClient($history, false);

        $client->lending->insLoanEnsureTokensConvert(['productId' => '81']);

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/v5/ins-loan/ensure-tokens-convert', $request->getUri()->getPath());
        $this->assertSame('productId=81', $request->getUri()->getQuery());
        $this->assertFalse($request->hasHeader('X-BAPI-API-KEY'));
        $this->assertFalse($request->hasHeader('X-BAPI-SIGN'));
    }

    public function testMarginCoinConversionInfoRetainsAuthenticatedOtcAccess(): void
    {
        $history = [];
        $client = $this->makeClient($history);

        $client->lending->insLoanEnsureTokensConvert(['productId' => '81']);

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $this->assertSame('productId=81', $request->getUri()->getQuery());
        $this->assertSame('test-key', $request->getHeaderLine('X-BAPI-API-KEY'));
        $this->assertSame(
            Authentication::signV5(
                'test-secret',
                $request->getHeaderLine('X-BAPI-TIMESTAMP'),
                'test-key',
                $request->getHeaderLine('X-BAPI-RECV-WINDOW'),
                $request->getUri()->getQuery()
            ),
            $request->getHeaderLine('X-BAPI-SIGN')
        );
    }

    public function testMarginCoinConversionInfoTracksCredentialUpdates(): void
    {
        $history = [];
        $config = $this->makeConfiguration($history, false, 3);
        $client = new Client($config);

        $client->lending->insLoanEnsureTokensConvert();
        $config->apiKey = 'rotated-key';
        $config->apiSecret = 'rotated-secret';
        $client->lending->insLoanEnsureTokensConvert();
        $config->apiKey = null;
        $config->apiSecret = null;
        $client->lending->insLoanEnsureTokensConvert();

        $this->assertCount(3, $history);
        $this->assertFalse($history[0]['request']->hasHeader('X-BAPI-API-KEY'));
        $this->assertSame('rotated-key', $history[1]['request']->getHeaderLine('X-BAPI-API-KEY'));
        $this->assertNotSame('', $history[1]['request']->getHeaderLine('X-BAPI-SIGN'));
        $this->assertFalse($history[2]['request']->hasHeader('X-BAPI-API-KEY'));
        $this->assertFalse($history[2]['request']->hasHeader('X-BAPI-SIGN'));
    }

    public function testSmokeProviderCoversEveryClientService(): void
    {
        $covered = [];
        foreach (RestApiSmokeTest::serviceMethodProvider() as $case) {
            $covered[$case[0]] = true;
        }

        $expected = [];
        $client = new \ReflectionClass(Client::class);
        foreach ($client->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $type = $property->getType();
            if ($type instanceof \ReflectionNamedType && is_subclass_of($type->getName(), \Bybit\RestApi\BaseService::class)) {
                $expected[$type->getName()] = true;
            }
        }

        $missing = array_diff_key($expected, $covered);
        $this->assertSame([], array_keys($missing), 'Smoke provider is missing Client services');
    }

    public function testStocksServiceIsNotPublished(): void
    {
        $this->assertFalse(property_exists(Client::class, 'stocks'));
        $this->assertFalse(class_exists(\Bybit\RestApi\StocksService::class));
    }

    /** @dataProvider removedAssetMethodProvider */
    public function testUnsupportedOrAbandonedAssetMethodIsNotPublished(string $method): void
    {
        $this->assertFalse(method_exists(\Bybit\RestApi\AssetService::class, $method));
    }

    /** @param array<int,array<string,mixed>> $history */
    private function makeClient(array &$history, bool $authenticated = true): Client
    {
        return new Client($this->makeConfiguration($history, $authenticated));
    }

    /** @param array<int,array<string,mixed>> $history */
    private function makeConfiguration(array &$history, bool $authenticated = true, int $responseCount = 1): Configuration
    {
        $responses = [];
        for ($i = 0; $i < $responseCount; $i++) {
            $responses[] = new Response(200, ['Content-Type' => 'application/json'], '{"retCode":0,"retMsg":"OK","result":{},"retExtInfo":{},"time":0}');
        }
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        return new Configuration(
            apiKey: $authenticated ? 'test-key' : null,
            apiSecret: $authenticated ? 'test-secret' : null,
            httpClient: new GuzzleClient([
                'handler' => $stack,
                'base_uri' => 'https://api-testnet.bybit.com',
                'http_errors' => false,
            ]),
        );
    }
}

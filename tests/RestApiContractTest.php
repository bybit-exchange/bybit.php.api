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

    /** @dataProvider removedAssetMethodProvider */
    public function testUnsupportedOrAbandonedAssetMethodIsNotPublished(string $method): void
    {
        $this->assertFalse(method_exists(\Bybit\RestApi\AssetService::class, $method));
    }

    /** @param array<int,array<string,mixed>> $history */
    private function makeClient(array &$history): Client
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"retCode":0,"retMsg":"OK","result":{},"retExtInfo":{},"time":0}'),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        return new Client(new Configuration(
            apiKey: 'test-key',
            apiSecret: 'test-secret',
            httpClient: new GuzzleClient([
                'handler' => $stack,
                'base_uri' => 'https://api-testnet.bybit.com',
                'http_errors' => false,
            ]),
        ));
    }
}

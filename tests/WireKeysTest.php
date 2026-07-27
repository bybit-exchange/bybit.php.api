<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Util\WireKeys;
use PHPUnit\Framework\TestCase;

final class WireKeysTest extends TestCase
{
    public function testConvertsTopLevelSnakeToCamel(): void
    {
        $this->assertSame(
            ['orderType' => 'Limit', 'symbol' => 'BTCUSDT'],
            WireKeys::camelize(['order_type' => 'Limit', 'symbol' => 'BTCUSDT'])
        );
    }

    public function testRecursesIntoNestedArray(): void
    {
        $input  = ['extra' => ['stake_amount' => '1', 'account_type' => 'UNIFIED']];
        $output = ['extra' => ['stakeAmount' => '1', 'accountType' => 'UNIFIED']];
        $this->assertSame($output, WireKeys::camelize($input));
    }

    public function testRecursesIntoListOfArrays(): void
    {
        $input  = [
            'request' => [
                ['order_type' => 'Limit', 'symbol' => 'BTCUSDT'],
                ['order_type' => 'Market', 'symbol' => 'ETHUSDT'],
            ],
        ];
        $output = [
            'request' => [
                ['orderType' => 'Limit', 'symbol' => 'BTCUSDT'],
                ['orderType' => 'Market', 'symbol' => 'ETHUSDT'],
            ],
        ];
        $this->assertSame($output, WireKeys::camelize($input));
    }

    public function testPassesThroughNonHashAndAlreadyCamel(): void
    {
        $input = ['symbols' => ['BTCUSDT', 'ETHUSDT'], 'count' => 5];
        $this->assertSame($input, WireKeys::camelize($input));
    }

    public function testRewritesReservedAliases(): void
    {
        $this->assertSame(['end' => 123], WireKeys::camelize(['end_' => 123]));
        $this->assertSame(['begin' => 1, 'end' => 2], WireKeys::camelize(['begin_' => 1, 'end_' => 2]));
    }
}

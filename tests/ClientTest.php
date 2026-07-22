<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Bybit;
use Bybit\Client;
use Bybit\Configuration;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testHasVersion(): void
    {
        $this->assertNotEmpty(Bybit::VERSION);
    }

    public function testBuildsWithDefaults(): void
    {
        $config = new Configuration();
        $config->testnet = true;
        $client = new Client($config);
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testToStringRedacts(): void
    {
        $config = new Configuration();
        $config->apiKey = 'super-secret-key';
        $client = new Client($config);
        $s = (string) $client;
        $this->assertStringNotContainsString('super-secret-key', $s);
    }

    public function testConfigurationRedactsInDebugInfo(): void
    {
        $config = new Configuration();
        $config->apiKey = 'super-secret-key';
        $config->apiSecret = 'super-secret-value';
        $dump = print_r($config, true);
        $this->assertStringNotContainsString('super-secret-key', $dump);
        $this->assertStringNotContainsString('super-secret-value', $dump);
    }
}

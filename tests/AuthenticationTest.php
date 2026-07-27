<?php

declare(strict_types=1);

namespace Bybit\Tests;

use Bybit\Authentication;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests around Authentication::signV5. The known-vector assertions
 * LOCK a hardcoded HMAC hex for a fixed input tuple — if anyone rearranges the
 * timestamp + apiKey + recvWindow + payload concatenation, or swaps the HMAC
 * key/message argument order, these tests fail even though a self-referential
 * `hash_hmac()` comparison would still agree.
 *
 * Fixtures precomputed with `openssl dgst -sha256 -hmac 'test-secret'` for:
 *   msg = '1700000000000' . 'test-key' . '5000' . <payload>
 */
final class AuthenticationTest extends TestCase
{
    private string $apiSecret = 'test-secret';
    private string $apiKey = 'test-key';
    private string $timestamp = '1700000000000';
    private string $recvWindow = '5000';

    public function testKnownVectorGetQueryString(): void
    {
        $payload = 'category=spot&symbol=BTCUSDT';
        // sha256_hmac(key='test-secret',
        //             msg='1700000000000test-key5000category=spot&symbol=BTCUSDT')
        $expected = '0048edf42c4979197cec265d4f090ffe6c30d7dec8782e4e6a26b51c2703cbf9';
        $this->assertSame(
            $expected,
            Authentication::signV5($this->apiSecret, $this->timestamp, $this->apiKey, $this->recvWindow, $payload)
        );
    }

    public function testKnownVectorPostBody(): void
    {
        $payload = '{"category":"linear","symbol":"BTCUSDT"}';
        // sha256_hmac(key='test-secret',
        //             msg='1700000000000test-key5000{"category":"linear","symbol":"BTCUSDT"}')
        $expected = '16378a8ca3caa3c068e2e74ef209dad5c036fec4047c7582ddcfcf13323a8275';
        $this->assertSame(
            $expected,
            Authentication::signV5($this->apiSecret, $this->timestamp, $this->apiKey, $this->recvWindow, $payload)
        );
    }

    public function testKnownVectorEmptyPayload(): void
    {
        // sha256_hmac(key='test-secret', msg='1700000000000test-key5000')
        $expected = 'd8d5e71d8f986368aa5c13405f059ab6adb4f41df59d2f11bb056226b63457d6';
        $this->assertSame(
            $expected,
            Authentication::signV5($this->apiSecret, $this->timestamp, $this->apiKey, $this->recvWindow, '')
        );
    }

    public function testProduces64CharHex(): void
    {
        $sig = Authentication::signV5($this->apiSecret, $this->timestamp, $this->apiKey, $this->recvWindow, '');
        $this->assertMatchesRegularExpression('/\A[0-9a-f]{64}\z/', $sig);
    }

    /**
     * Belt-and-suspenders: also verify signV5 agrees with a fresh
     * `hash_hmac` call — this catches accidental double-hashing or
     * post-processing regressions that the known-vector tests would miss.
     */
    public function testMatchesFreshHashHmac(): void
    {
        $payload = 'a=1&b=2';
        $msg = $this->timestamp . $this->apiKey . $this->recvWindow . $payload;
        $this->assertSame(
            hash_hmac('sha256', $msg, $this->apiSecret),
            Authentication::signV5($this->apiSecret, $this->timestamp, $this->apiKey, $this->recvWindow, $payload)
        );
    }
}

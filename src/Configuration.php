<?php

declare(strict_types=1);

namespace Bybit;

use GuzzleHttp\ClientInterface;

/**
 * Runtime configuration for a Client. Instantiate, set fields, pass to Client.
 * Credentials are redacted from every default PHP serialization pathway
 * (__debugInfo / __toString / jsonSerialize) so a stray dump/log doesn't leak.
 */
final class Configuration implements \JsonSerializable
{
    public ?string $apiKey = null;
    public ?string $apiSecret = null;
    public bool $testnet = false;
    public ?string $baseUrl = null;
    public string $recvWindow = Bybit::DEFAULT_RECV_WINDOW;
    public int $timeout = Bybit::DEFAULT_TIMEOUT;
    public ?ClientInterface $httpClient = null;

    public function resolvedBaseUrl(): string
    {
        if ($this->baseUrl !== null) {
            return $this->baseUrl;
        }
        return $this->testnet ? Bybit::BASE_URL_TESTNET : Bybit::BASE_URL_MAINNET;
    }

    /**
     * Serialization-safe snapshot used by __debugInfo / jsonSerialize / __toString.
     *
     * @return array<string,mixed>
     */
    private function toSafeArray(): array
    {
        return [
            'apiKey'     => $this->redact($this->apiKey),
            'apiSecret'  => $this->redact($this->apiSecret),
            'testnet'    => $this->testnet,
            'baseUrl'    => $this->resolvedBaseUrl(),
            'recvWindow' => $this->recvWindow,
            'timeout'    => $this->timeout,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return $this->toSafeArray();
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toSafeArray();
    }

    public function __toString(): string
    {
        return sprintf(
            '<Bybit\\Configuration apiKey=%s apiSecret=%s testnet=%s baseUrl=%s>',
            $this->redact($this->apiKey),
            $this->redact($this->apiSecret),
            $this->testnet ? 'true' : 'false',
            $this->resolvedBaseUrl()
        );
    }

    private function redact(?string $v): string
    {
        return ($v === null || $v === '') ? '(unset)' : '[REDACTED]';
    }
}

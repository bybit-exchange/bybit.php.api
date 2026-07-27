<?php

declare(strict_types=1);

namespace Bybit;

use GuzzleHttp\ClientInterface;

/**
 * Runtime configuration for a Client. Instantiate with named arguments, pass to
 * Client. Credentials are redacted from every default PHP serialization pathway
 * (__debugInfo / __toString / jsonSerialize) so a stray dump/log doesn't leak.
 *
 * Fields fall into two groups:
 *  - Live (public, writable): apiKey, apiSecret, recvWindow — Session reads
 *    these on every request, so rotating credentials mid-run works.
 *  - Snapshot (readonly): testnet, baseUrl, timeout, httpClient — captured by
 *    Session at construction time. Set them via constructor args; post-hoc
 *    assignment is a PHP Error.
 */
final class Configuration implements \JsonSerializable
{
    public function __construct(
        public ?string $apiKey = null,
        public ?string $apiSecret = null,
        public string $recvWindow = Bybit::DEFAULT_RECV_WINDOW,
        public readonly bool $testnet = false,
        public readonly ?string $baseUrl = null,
        public readonly int $timeout = Bybit::DEFAULT_TIMEOUT,
        public readonly ?ClientInterface $httpClient = null,
    ) {
    }

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

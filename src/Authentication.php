<?php

declare(strict_types=1);

namespace Bybit;

/**
 * HMAC-SHA256 signer for the Bybit V5 REST API.
 * Payload format: timestamp + apiKey + recvWindow + (queryString OR requestBody).
 */
final class Authentication
{
    private function __construct()
    {
    }

    public static function signV5(
        string $apiSecret,
        string $timestamp,
        string $apiKey,
        string $recvWindow,
        string $payload
    ): string {
        $msg = $timestamp . $apiKey . $recvWindow . $payload;
        return hash_hmac('sha256', $msg, $apiSecret);
    }
}

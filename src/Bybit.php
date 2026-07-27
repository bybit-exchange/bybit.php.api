<?php

declare(strict_types=1);

namespace Bybit;

/**
 * Package-level constants for the Bybit V5 connector.
 */
final class Bybit
{
    public const VERSION = '0.1.0';

    public const BASE_URL_MAINNET    = 'https://api.bybit.com';
    public const BASE_URL_TESTNET    = 'https://api-testnet.bybit.com';
    public const DEFAULT_RECV_WINDOW = '5000';
    public const DEFAULT_TIMEOUT     = 10;

    private function __construct()
    {
    }
}

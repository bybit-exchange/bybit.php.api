<?php

declare(strict_types=1);

namespace Bybit\Exception;

/**
 * Transport-level errors that don't fit Timeout / Network / a Bybit body.
 * Guzzle RequestException / ConnectException / TransferException land here.
 */
class TransportException extends BybitException
{
}

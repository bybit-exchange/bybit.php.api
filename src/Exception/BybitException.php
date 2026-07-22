<?php

declare(strict_types=1);

namespace Bybit\Exception;

/**
 * Root class for every failure the SDK raises. `catch (BybitException $e)`
 * catches everything — auth, rate-limit, timeout, network, parse, misconfig.
 */
class BybitException extends \RuntimeException
{
}

<?php

declare(strict_types=1);

namespace Bybit\Exception;

/**
 * Configuration mistake caught before the network call (missing apiKey,
 * invalid combination of options). Distinct from AuthException which is a
 * server-side rejection.
 */
class ConfigurationException extends BybitException
{
}

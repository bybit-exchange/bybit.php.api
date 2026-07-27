<?php

declare(strict_types=1);

namespace Bybit\Exception;

/** 5xx HTTP without a decodable Bybit body — infra outage / gateway error. */
class ServerException extends TransportException
{
}

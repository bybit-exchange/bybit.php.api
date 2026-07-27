<?php

declare(strict_types=1);

namespace Bybit\Exception;

/** Non-auth 4xx HTTP without a decodable Bybit body — usually WAF / CDN. */
class ClientException extends TransportException
{
}

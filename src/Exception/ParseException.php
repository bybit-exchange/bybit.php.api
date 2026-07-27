<?php

declare(strict_types=1);

namespace Bybit\Exception;

/**
 * Body did not parse or didn't match Bybit V5 ApiResponse shape. The full
 * raw payload is available via getBody() so consumers can log CDN /
 * maintenance-page HTML for post-mortem.
 */
class ParseException extends TransportException
{
    private ?string $rawBody;
    private ?int $httpStatus;

    public function __construct(string $message, ?string $body = null, ?int $httpStatus = null)
    {
        parent::__construct($message);
        $this->rawBody = $body;
        $this->httpStatus = $httpStatus;
    }

    public function getBody(): ?string
    {
        return $this->rawBody;
    }

    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }
}

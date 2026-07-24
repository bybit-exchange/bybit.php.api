<?php

declare(strict_types=1);

namespace Bybit\Exception;

/**
 * Raised when the server returns HTTP 200 + retCode != 0 (the V5 norm), or
 * when a transport-level error is enriched with a Bybit body payload.
 */
class ApiException extends BybitException
{
    private ?int $retCode;
    private ?string $retMsg;
    /** @var mixed */
    private $result;
    private ?int $time;
    private ?int $httpStatus;

    /**
     * @param array<string,mixed> $response Bybit V5 ApiResponse envelope.
     */
    public function __construct(array $response, ?int $httpStatus = null)
    {
        $this->retCode    = isset($response['retCode']) ? (int) $response['retCode'] : null;
        $this->retMsg     = isset($response['retMsg']) ? (string) $response['retMsg'] : null;
        $this->result     = $response['result'] ?? null;
        $this->time       = isset($response['time']) ? (int) $response['time'] : null;
        $this->httpStatus = $httpStatus;
        parent::__construct(sprintf('[%s] %s', (string) $this->retCode, (string) $this->retMsg));
    }

    public function getRetCode(): ?int
    {
        return $this->retCode;
    }

    public function getRetMsg(): ?string
    {
        return $this->retMsg;
    }

    /** @return mixed */
    public function getResult()
    {
        return $this->result;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * Route a V5 response with retCode != 0 to the most specific subclass.
     *
     * @param array<string,mixed> $response
     */
    public static function fromResponse(array $response, ?int $httpStatus = null): ApiException
    {
        $code = isset($response['retCode']) ? (int) $response['retCode'] : 0;
        // Auth/permission family per https://bybit-exchange.github.io/docs/v5/error :
        //   10002 sync-window, 10003 invalid-key, 10004 sign-mismatch,
        //   10005 permission-denied, 10007 user-auth-failed, 10008 expired-token,
        //   10009 ip-blocked, 10010 unmatched-ip, 10017 gateway-401/403,
        //   10022 blocked-jurisdiction, 10024 compliance-check-failed,
        //   10028 forbidden, 10029 access-denied.
        //   -2015 legacy account-service auth failure (observed on
        //   /v5/account/query-instruments — Bybit's account-service pathway
        //   still surfaces code alongside 10005).
        if (in_array($code, [-2015, 10002, 10003, 10004, 10005, 10007, 10008, 10009, 10010, 10017, 10022, 10024, 10028, 10029], true)) {
            return new AuthException($response, $httpStatus);
        }
        if (in_array($code, [10006, 10018], true)) {
            return new RateLimitException($response, $httpStatus);
        }
        return new ApiException($response, $httpStatus);
    }
}

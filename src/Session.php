<?php

declare(strict_types=1);

namespace Bybit;

use Bybit\Exception\ApiException;
use Bybit\Exception\ClientException as BybitClientException;
use Bybit\Exception\ConfigurationException;
use Bybit\Exception\NetworkException;
use Bybit\Exception\ParseException;
use Bybit\Exception\ServerException;
use Bybit\Exception\TimeoutException;
use Bybit\Exception\TransportException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;

/**
 * Session owns the Guzzle client + auth-header assembly. Every service class
 * receives a Session instance and dispatches through publicRequest() /
 * signRequest().
 *
 * Signing invariant: the payload signed and the query string sent on the wire
 * MUST be byte-identical. We build ONE query_str (sortAndEncode after key
 * sort) and use it verbatim for BOTH the signature payload AND the request
 * URL — never letting Guzzle's query serializer re-encode.
 */
final class Session
{
    /** @var array<int,string> HTTP verbs that carry params on the query string. */
    private const PAYLOAD_QUERY_METHODS = ['GET', 'DELETE'];

    private Configuration $config;
    private ClientInterface $http;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->http = $config->httpClient ?? new GuzzleClient([
            'base_uri' => $config->resolvedBaseUrl(),
            'timeout'  => $config->timeout,
            'http_errors' => false,
        ]);
    }

    /**
     * Public unsigned endpoint — no X-BAPI-* headers attached.
     *
     * @param array<mixed,mixed>|null $params Query params for GET/DELETE, body for POST/PUT/PATCH.
     * @return array<string,mixed>
     */
    public function publicRequest(string $method, string $path, ?array $params = null): array
    {
        return $this->dispatch($method, $path, $params, false);
    }

    /**
     * Signed endpoint — X-BAPI-* headers computed via Authentication.
     *
     * @param array<mixed,mixed>|null $params Query params for GET/DELETE, body for POST/PUT/PATCH.
     * @return array<string,mixed>
     */
    public function signRequest(string $method, string $path, ?array $params = null): array
    {
        return $this->dispatch($method, $path, $params, true);
    }

    /**
     * @param array<mixed,mixed>|null $params
     * @return array<string,mixed>
     */
    private function dispatch(string $method, string $path, ?array $params, bool $signed): array
    {
        $method = strtoupper($method);
        $clean = $this->compact($params);
        $usesQuery = in_array($method, self::PAYLOAD_QUERY_METHODS, true);

        $queryStr = ($clean && $usesQuery) ? $this->sortAndEncode($clean) : '';
        $bodyStr  = ($clean && !$usesQuery) ? (json_encode($clean, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '') : '';

        $headers = $this->buildHeaders($signed, $usesQuery, $queryStr, $bodyStr);

        $url = $queryStr !== '' ? ($path . '?' . $queryStr) : $path;

        try {
            // allow_redirects: false — Bybit V5 never legitimately 3xx-redirects
            // an API call. A 302 from a proxy / WAF / maintenance page would
            // otherwise cause Guzzle to replay X-BAPI-SIGN + X-BAPI-API-KEY to
            // an unintended host. Better to surface the 3xx as a
            // ParseException / ClientException in parseResponse.
            $options = [
                'headers' => $headers,
                'http_errors' => false,
                'allow_redirects' => false,
            ];
            if ($bodyStr !== '') {
                $options['body'] = $bodyStr;
            }
            $response = $this->http->request($method, $url, $options);
        } catch (ConnectException $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'timeout') !== false || stripos($msg, 'timed out') !== false) {
                throw new TimeoutException($msg, 0, $e);
            }
            throw new NetworkException($msg, 0, $e);
        } catch (RequestException $e) {
            $msg = $e->getMessage();
            if (stripos($msg, 'timeout') !== false || stripos($msg, 'timed out') !== false) {
                throw new TimeoutException($msg, 0, $e);
            }
            throw new TransportException($msg, 0, $e);
        } catch (TransferException $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        } catch (GuzzleException $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $this->parseResponse($response);
    }

    /**
     * Deterministic `&`-joined encoding, keys sorted, values URL-escaped.
     * Arrays become repeated keys (symbol=BTCUSDT&symbol=ETHUSDT) — this
     * matches Bybit V5's flat-list expectation.
     *
     * @param array<mixed,mixed> $params
     */
    private function sortAndEncode(array $params): string
    {
        $flat = [];
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $inner) {
                    $flat[] = [(string) $k, self::scalarToWire($inner)];
                }
            } else {
                $flat[] = [(string) $k, self::scalarToWire($v)];
            }
        }
        usort($flat, static function ($a, $b) {
            return strcmp($a[0], $b[0]);
        });
        $parts = [];
        foreach ($flat as [$k, $v]) {
            $parts[] = rawurlencode($k) . '=' . rawurlencode($v);
        }
        return implode('&', $parts);
    }

    /**
     * Normalize a scalar to its wire-format string:
     *  - bool → 'true' / 'false' (PHP's default cast gives '1' / '' which
     *    Bybit either rejects or misinterprets).
     *  - float → non-scientific decimal, trailing zeros stripped ('1.0e-9'
     *    becomes '0.000000001'). Bybit rejects E-notation on numeric params.
     *  - int / string / stringable object → default `(string)` cast.
     *  - null → empty string (callers strip nulls before this, so unreachable
     *    in practice, but defensive).
     *
     * @param mixed $v
     */
    private static function scalarToWire($v): string
    {
        if ($v === null) {
            return '';
        }
        if (is_bool($v)) {
            return $v ? 'true' : 'false';
        }
        if (is_float($v)) {
            if (!is_finite($v)) {
                return (string) $v; // 'INF' / 'NAN' — Bybit will reject; surfaces the bug.
            }
            // %.14F gives up to 14 fractional digits (double precision) with no E.
            $s = rtrim(rtrim(sprintf('%.14F', $v), '0'), '.');
            return $s === '' || $s === '-' ? '0' : $s;
        }
        return (string) $v;
    }

    /**
     * @return array<string,string>
     */
    private function buildHeaders(bool $signed, bool $usesQuery, string $queryStr, string $bodyStr): array
    {
        $h = [];
        if ($bodyStr !== '') {
            $h['Content-Type'] = 'application/json';
        }
        if (!$signed) {
            return $h;
        }
        if ($this->config->apiKey === null || $this->config->apiSecret === null) {
            throw new ConfigurationException('signed endpoint requires apiKey + apiSecret');
        }
        $ts = (string) (int) floor(microtime(true) * 1000);
        $payload = $usesQuery ? $queryStr : $bodyStr;

        $h['X-BAPI-API-KEY']     = $this->config->apiKey;
        $h['X-BAPI-TIMESTAMP']   = $ts;
        $h['X-BAPI-RECV-WINDOW'] = (string) $this->config->recvWindow;
        $h['X-BAPI-SIGN']        = Authentication::signV5(
            $this->config->apiSecret,
            $ts,
            $this->config->apiKey,
            (string) $this->config->recvWindow,
            $payload
        );
        $h['X-BAPI-SIGN-TYPE']   = '2';
        return $h;
    }

    /**
     * @return array<string,mixed>
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();
        $body = null;
        if ($raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $body = $decoded;
            }
        }

        // P2P endpoints (/v5/p2p/*) return legacy snake_case envelope keys
        // (ret_code / ret_msg / time_now / ext_info) — a real spec drift
        // vs the V5 standard camelCase (retCode / retMsg / time / retExtInfo).
        // Alias into the canonical shape so downstream consumers see the SDK
        // contract uniformly.
        if (is_array($body) && !isset($body['retCode']) && isset($body['ret_code'])) {
            $body = self::normalizeLegacyEnvelope($body);
        }

        if (!is_array($body) || !isset($body['retCode']) || !is_int($body['retCode'])) {
            $preview = $this->truncateForError($raw);
            if ($status >= 500) {
                throw new ServerException(sprintf('Bybit server error (status=%d): %s', $status, $preview));
            }
            if ($status >= 400) {
                throw new BybitClientException(sprintf('Bybit client error (status=%d): %s', $status, $preview));
            }
            throw new ParseException(
                sprintf('Response is not a valid Bybit V5 ApiResponse (status=%d): %s', $status, $preview),
                $raw,
                $status
            );
        }
        if ((int) $body['retCode'] === 0) {
            /** @var array<string,mixed> $body */
            return $body;
        }
        throw ApiException::fromResponse($body, $status);
    }

    /**
     * Convert a legacy snake_case Bybit envelope (P2P and a handful of other
     * older endpoints still emit this shape) to the V5 camelCase standard.
     * Preserves original keys so raw callers who care about the wire shape can
     * still read them; adds canonical aliases for the SDK contract.
     *
     * @param  array<string,mixed> $body
     * @return array<string,mixed>
     */
    private static function normalizeLegacyEnvelope(array $body): array
    {
        // ret_code / ret_msg → retCode / retMsg (int).
        $body['retCode'] = (int) $body['ret_code'];
        $body['retMsg']  = (string) ($body['ret_msg'] ?? '');

        // ext_info → retExtInfo (V5 standard puts extension info here).
        if (!isset($body['retExtInfo'])) {
            $body['retExtInfo'] = $body['ext_info'] ?? new \stdClass();
        }

        // time_now (seconds w/ microseconds, string) → time (milliseconds int).
        if (!isset($body['time']) && isset($body['time_now'])) {
            $seconds = (float) $body['time_now'];
            $body['time'] = (int) round($seconds * 1000);
        }

        return $body;
    }

    private function truncateForError(string $raw): string
    {
        if ($raw === '') {
            return '(empty body)';
        }
        return strlen($raw) > 2048 ? (substr($raw, 0, 2048) . '…(truncated)') : $raw;
    }

    /**
     * @param array<mixed,mixed>|null $params
     * @return array<mixed,mixed>|null
     */
    private function compact(?array $params): ?array
    {
        if ($params === null) {
            return null;
        }
        $out = [];
        foreach ($params as $k => $v) {
            if ($v !== null) {
                $out[$k] = $v;
            }
        }
        return $out === [] ? null : $out;
    }
}

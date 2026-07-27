# bybit-connector-php

[![Packagist version](https://img.shields.io/packagist/v/bybit-exchange/bybit-connector-php.svg)](https://packagist.org/packages/bybit-exchange/bybit-connector-php)
[![PHP ≥ 8.1](https://img.shields.io/badge/php-%E2%89%A58.1-brightgreen.svg)](https://www.php.net)
[![PHPStan level 6](https://img.shields.io/badge/PHPStan-level%206-777bb4.svg)](https://phpstan.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](./LICENSE)
[![CI](https://github.com/bybit-exchange/bybit.php.api/actions/workflows/ci.yml/badge.svg)](https://github.com/bybit-exchange/bybit.php.api/actions/workflows/ci.yml)

Official lightweight PHP connector for the [Bybit V5 REST API](https://bybit-exchange.github.io/docs/v5/intro).

`bybit-connector-php` wraps the Bybit V5 HTTP endpoints as a set of typed PHP methods with explicit required arguments plus an `array $options = []` catch-all for optional parameters. Its goal is the same as [`pybit`](https://github.com/bybit-exchange/pybit) on the Python side and [`bybit-connector-ruby`](https://github.com/bybit-exchange/bybit.ruby.api) on the Ruby side: an easy-to-use, high-performance connector with a small dependency footprint.

## Prerequisites

Before you write any code you need a Bybit API key. Two accounts to know
about:

- **Testnet** — [testnet.bybit.com](https://testnet.bybit.com) → sign up →
  API Management → *Create New Key*. This is where you should point every
  new integration until you're confident about behavior. Testnet balances
  are virtual; nothing you do here touches real funds.
- **Mainnet** — [bybit.com](https://www.bybit.com) → API Management. Real
  money. Enable only the permissions you actually need (spot / derivatives /
  withdrawals) and prefer IP-restricted keys.

Testnet and mainnet keys are **separate** — an API key issued on one won't
work against the other. The SDK selects the environment via the
`testnet: true|false` constructor argument on `Configuration` (or an explicit
`baseUrl:` override).

## Installation

PHP >= 8.1 is required (PHP 8.3.x recommended). Composer 2.x.

```
composer require bybit-exchange/bybit-connector-php
```

## Quick Start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Bybit\Client;
use Bybit\Configuration;

// All calls below run against testnet — flip testnet: false for mainnet
// once you're happy with the behavior.
$config = new Configuration(
    apiKey: getenv('BYBIT_TESTNET_KEY') ?: null,
    apiSecret: getenv('BYBIT_TESTNET_SECRET') ?: null,
    testnet: true,
);

$client = new Client($config);

// 1. Public endpoint — no auth needed.
print_r($client->market->getServerTime());

// 2. Signed endpoint — apiKey + apiSecret required.
$wallet = $client->account->getWalletBalance('UNIFIED');
print_r($wallet['result']['list']);

// 3. Place a LIMIT order well below market so it sits on the book and does
//    NOT fill (safe to run repeatedly). Adjust `price` if BTC ever trades
//    at $10k again — otherwise this stays a resting order you can cancel.
$order = $client->trade->createOrder(
    'linear', 'BTCUSDT', 'Buy', 'Limit', '0.01',
    ['price' => '10000', 'timeInForce' => 'GTC']
);
$orderId = $order['result']['orderId'];
echo "orderId: {$orderId}" . PHP_EOL;

// 4. Cancel it before moving on.
$client->trade->cancelOrder('linear', 'BTCUSDT', ['orderId' => $orderId]);
```

> ⚠️ **Before switching `testnet = false`**: verify the `price` / `qty` in
> `createOrder` won't cross the top of the book — a Limit Buy at $10k on
> mainnet becomes a market fill instantly (if BTC ever drops that low),
> and a Limit Sell at $1M does the reverse.

See `examples/quickstart.php` for a runnable script.

## Configuration

All options live on `Bybit\Configuration`. Instantiate with named arguments
and pass to `Client`:

```php
use Bybit\Configuration;

$config = new Configuration(
    apiKey:     getenv('BYBIT_TESTNET_KEY') ?: null,
    apiSecret:  getenv('BYBIT_TESTNET_SECRET') ?: null,
    recvWindow: '5000',   // milliseconds — Bybit rejects requests whose signed
                          // timestamp is older than this window. Bump to
                          // 10000+ if your clock drifts or the network is noisy.
    testnet:    true,     // false selects mainnet (default)
    timeout:    10,       // Guzzle timeout, seconds
);
```

`testnet`, `baseUrl`, `timeout`, and `httpClient` are **readonly** — set them
via the constructor and Configuration is a snapshot the Session captures at
Client construction. `apiKey`, `apiSecret`, and `recvWindow` remain writable
on the object so callers can rotate credentials mid-run without rebuilding
the Client.

Bring your own Guzzle client to inject retries / logging / middleware:

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

$stack = HandlerStack::create();
$stack->push(Middleware::retry(
    fn($retries, $req, $resp, $err) => $retries < 3 && ($err !== null || ($resp && $resp->getStatusCode() >= 500)),
    fn($retries) => (int) pow(2, $retries) * 1000  // 1s, 2s, 4s (ms)
));

$config = new Configuration(
    apiKey:    getenv('BYBIT_TESTNET_KEY') ?: null,
    apiSecret: getenv('BYBIT_TESTNET_SECRET') ?: null,
    httpClient: new GuzzleClient([
        'base_uri' => 'https://api-testnet.bybit.com',
        'handler'  => $stack,
        'timeout'  => 5,
    ]),
);
```

> ⚠️ When you inject `httpClient`, the SDK does **not** re-apply
> `Configuration::$baseUrl` / `$testnet` / `$timeout` onto your client — the
> injected Guzzle instance is used as-is. Set `base_uri` and `timeout` on the
> Guzzle client yourself (as shown above), and pick `api.bybit.com` vs
> `api-testnet.bybit.com` explicitly.

Base URLs (exported constants):

- `Bybit\Bybit::BASE_URL_MAINNET` — `https://api.bybit.com`
- `Bybit\Bybit::BASE_URL_TESTNET` — `https://api-testnet.bybit.com`

## Services

Each API group is a readonly property on `Bybit\Client`:

- `$client->market` — public market data (kline, tickers, orderbook, instruments-info, ...)
- `$client->trade` — orders (create / amend / cancel / batch / history)
- `$client->position` — positions, leverage, TP/SL, move-position
- `$client->account` — wallet, margin, collateral, fee-rate, transaction log
- `$client->asset` — coin balance, funding history
- `$client->user` — sub-accounts, API-key management
- `$client->affiliate` — sub-affiliate lists
- `$client->broker` — broker earnings, distributions
- `$client->cryptoLoan` — flexible / fixed crypto loans
- `$client->rfq` — request-for-quote (block trades)
- `$client->spotMargin` — UTA spot margin
- `$client->earn` — earn, liquidity mining, RWA, PWM, hold-to-earn
- `$client->p2p` — P2P advertise / order / chat
- `$client->bot` — DCA / grid / futures-combo / futures-grid / martingale

## Error Handling

Every failure is a subclass of `Bybit\Exception\BybitException`:

```php
use Bybit\Exception\{
    AuthException, RateLimitException, TimeoutException, NetworkException,
    ServerException, ClientException, ParseException, ApiException,
};

try {
    $client->trade->createOrder('linear', 'BTCUSDT', 'Buy', 'Limit', '0.01', ['price' => '10000']);
} catch (AuthException $e) {          // retCode 10002/10003/10004/10005/10007/10009/10010/10029, or HTTP 401/403
    // bad key / bad sign / permission
} catch (RateLimitException $e) {     // retCode 10006/10018, or HTTP 429
    sleep(1);
} catch (TimeoutException $e) {       // Guzzle ConnectException / RequestException(timeout)
} catch (NetworkException $e) {       // Guzzle ConnectException (connection refused / DNS / SSL)
} catch (ServerException $e) {        // HTTP 5xx w/ non-JSON body
} catch (ClientException $e) {        // non-auth 4xx w/ non-JSON body (WAF, CDN, etc.)
} catch (ParseException $e) {         // unrecognized body shape; $e->getBody() holds raw payload
} catch (ApiException $e) {           // any other retCode != 0 — catch-all API error
}
```

Full hierarchy:

- `Bybit\Exception\BybitException` (`\RuntimeException`)
  - `Bybit\Exception\ConfigurationException` — missing apiKey / conflicting options
  - `Bybit\Exception\TransportException`
    - `Bybit\Exception\TimeoutException`
    - `Bybit\Exception\NetworkException`
    - `Bybit\Exception\ServerException` (5xx w/o body)
    - `Bybit\Exception\ClientException` (non-auth 4xx w/o body)
    - `Bybit\Exception\ParseException` — body did not parse or shape mismatch (has `getBody()`, `getHttpStatus()`)
  - `Bybit\Exception\ApiException` — Bybit V5 body with retCode != 0
    - `Bybit\Exception\AuthException`
    - `Bybit\Exception\RateLimitException`

Every `ApiException` exposes `getRetCode()`, `getRetMsg()`, `getResult()`, `getTime()`, `getHttpStatus()`. See the [Bybit V5 error-code list](https://bybit-exchange.github.io/docs/v5/error) for meanings.

> The `sleep(1)` on rate-limit shown above is fine for exploration, **not**
> for production — Bybit will escalate throttling on tight retry loops. For
> real workloads, wire a Guzzle `Middleware::retry` with exponential backoff
> via the "bring your own Guzzle client" hook in [Configuration](#configuration)
> above.

## Return Value

Every service method returns the raw parsed JSON as an associative `array`:

```php
$response = $client->market->getKline('spot', 'BTCUSDT', '1');
$response['retCode'];   // => 0
$response['retMsg'];    // => 'OK'
$response['result'];    // => ['category' => 'spot', 'symbol' => 'BTCUSDT', 'list' => [...]]
$response['time'];      // => 1234567890000
```

> P2P endpoints return a legacy snake_case envelope (`ret_code` / `ret_msg` /
> `time_now` / `ext_info`) instead of the V5 standard. The SDK normalizes
> these into `retCode` / `retMsg` / `time` / `retExtInfo` automatically —
> `$response['retCode']` works uniformly across every service. The original
> snake_case keys are preserved on the array for callers that want the wire
> shape verbatim.

## Method Reference

Method names follow the Bybit V5 endpoint slug in camelCase, grouped by
domain. Required path/body params are explicit typed arguments; every optional
parameter goes into the final `array $options = []`. A few illustrative
mappings:

| Bybit V5 path                          | HTTP  | SDK method                                                          |
| -------------------------------------- | ----- | ------------------------------------------------------------------- |
| `/v5/market/kline`                     | GET   | `$client->market->getKline($category, $symbol, $interval, $options)` |
| `/v5/market/tickers`                   | GET   | `$client->market->getTickers($category, $options)`                  |
| `/v5/order/create`                     | POST  | `$client->trade->createOrder($category, $symbol, $side, $orderType, $qty, $options)` |
| `/v5/order/cancel`                     | POST  | `$client->trade->cancelOrder($category, $symbol, $options)`         |
| `/v5/position/list`                    | GET   | `$client->position->getInfo($category, $options)`                   |
| `/v5/account/wallet-balance`           | GET   | `$client->account->getWalletBalance($accountType, $options)`        |

For the full list of 240+ endpoints, browse the service class source under
`src/RestApi/` — every method carries a PHPDoc block naming its HTTP verb,
path, params, and a `@see` link to the Bybit docs page.

## Pagination

Bybit V5 uses **opaque cursor pagination** — the response's
`result.nextPageCursor` (empty string when the page is the last one) feeds
back in as the `cursor` option on the next call:

```php
$cursor = null;
do {
    $opts = ['limit' => 50];
    if ($cursor !== null && $cursor !== '') {
        $opts['cursor'] = $cursor;
    }
    $resp = $client->trade->getOrderHistory('linear', $opts);
    foreach ($resp['result']['list'] as $order) {
        // process($order);
    }
    $cursor = $resp['result']['nextPageCursor'] ?? null;
} while ($cursor !== null && $cursor !== '');
```

The same pattern works for `getClosedPnl`, `getTransactionLog`,
`$client->trade->getHistory()` (execution list), and every other paginated
endpoint.

## Signing Invariant (for the curious)

`Session` guarantees that the query string signed matches the query string
sent on the wire byte-for-byte. It does this by:

1. Sorting `params` keys and encoding via `rawurlencode` into one canonical
   string (`sortAndEncode()`).
2. Feeding that exact string into `Authentication::signV5()`.
3. Building the request URL manually as `$path . '?' . $queryStr` rather
   than letting Guzzle re-serialize `params` — Guzzle's default query
   handler can reorder keys and array-bracket lists differently, which
   would break the HMAC.

Booleans are wire-serialized as literal `'true'` / `'false'`, floats are
formatted non-scientifically (so `1.0e-9` becomes `0.000000001`) — both
avoid Bybit's parameter validators rejecting the PHP default casts.

## Development

```
composer install
composer test           # PHPUnit
composer stan           # PHPStan level 6
composer cs-check       # PHP-CS-Fixer dry-run
composer cs-fix         # PHP-CS-Fixer auto-fix
```

## License

MIT — see [LICENSE](LICENSE).

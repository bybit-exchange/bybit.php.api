# bybit/bybit-connector-php

Official lightweight PHP connector for the [Bybit V5 REST API](https://bybit-exchange.github.io/docs/v5/intro).

`bybit/bybit-connector-php` wraps the Bybit V5 HTTP endpoints as a set of typed PHP methods. Its goal is the same as [`pybit`](https://github.com/bybit-exchange/pybit) on the Python side and [`bybit-connector-ruby`](https://github.com/bybit-exchange/bybit.ruby.api) on the Ruby side: an easy-to-use, high-performance connector with a small dependency footprint.

## Installation

PHP >= 8.1 is required (PHP 8.3.x recommended).

```
composer require bybit/bybit-connector-php
```

## Quick Start

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Bybit\Client;
use Bybit\Configuration;

$config = new Configuration();
$config->apiKey    = getenv('BYBIT_KEY') ?: null;
$config->apiSecret = getenv('BYBIT_SECRET') ?: null;
$config->testnet   = true;   // omit / false for mainnet

$client = new Client($config);

// Public endpoint — no auth needed. Required args first, optional in $options.
print_r($client->market->getServerTime());

$kline = $client->market->getKline('BTCUSDT', '1', ['category' => 'spot', 'limit' => 5]);
print_r($kline['result']['list']);

// Signed endpoint — apiKey + apiSecret required. Fee rate for linear futures.
$fee = $client->account->getFeeRate('linear', ['symbol' => 'BTCUSDT']);
print_r($fee['result']['list']);

// Place an order (Trade). Required: category, symbol, side, orderType, qty.
$order = $client->trade->createOrder(
    'linear', 'BTCUSDT', 'Buy', 'Limit', '0.01',
    ['price' => '30000', 'timeInForce' => 'GTC']
);
echo $order['result']['orderId'] . PHP_EOL;
```

See `examples/quickstart.php` for a runnable script.

**Signature convention**: every service method takes the endpoint's REQUIRED
parameters as explicit typed arguments, and all OPTIONAL parameters bundled
into a final `array $options = []`. IDE autocomplete lists the required args;
consult the linked `@see` docs URL on each method for the full option list.

## Configuration

All options live on `Bybit\Configuration`:

```php
$config = new Configuration();
$config->apiKey     = getenv('BYBIT_KEY');
$config->apiSecret  = getenv('BYBIT_SECRET');
$config->testnet    = false;             // default false
$config->recvWindow = '5000';            // ms, X-BAPI-RECV-WINDOW header
$config->timeout    = 10;                // Guzzle timeout, seconds
```

Bring your own Guzzle client to inject retries / logging / middleware:

```php
use GuzzleHttp\Client as GuzzleClient;

$http = new GuzzleClient([
    'base_uri' => 'https://api-testnet.bybit.com',
    'timeout'  => 5,
]);
$config->httpClient = $http;
```

Base URLs (exported constants):

- `Bybit\Bybit::BASE_URL_MAINNET` — `https://api.bybit.com`
- `Bybit\Bybit::BASE_URL_TESTNET` — `https://api-testnet.bybit.com`

## Services

Each API group is a readonly property on `Bybit\Client`:

- `$client->market` — public market data (kline, tickers, orderbook, instruments-info, ...)
- `$client->trade` — orders (create / amend / cancel / batch / history)
- `$client->position` — positions, leverage, TP/SL, move-position
- `$client->account` — wallet, margin, collateral, fee-rate, transaction log
- `$client->asset` — coin balance, coin greeks, funding history
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
    $client->trade->createOrder('linear', 'BTCUSDT', 'Buy', 'Limit', '0.01', ['price' => '30000']);
} catch (AuthException $e) {         // retCode 10003/10004/10005/... or HTTP 401/403
    // bad key / bad sign / permission
} catch (RateLimitException $e) {    // retCode 10006 / 10018 or HTTP 429
    sleep(1);
} catch (TimeoutException $e) {      // Guzzle ConnectException / RequestException(timeout)
} catch (NetworkException $e) {      // Guzzle ConnectException
} catch (ServerException $e) {       // HTTP 5xx w/ non-JSON body
} catch (ClientException $e) {       // HTTP 4xx w/ non-JSON body (WAF, CDN, etc.)
} catch (ParseException $e) {        // unrecognized body shape; $e->getBody() holds raw payload
} catch (ApiException $e) {          // any other retCode != 0 — catch-all API error
}
```

Full hierarchy:

- `Bybit\Exception\BybitException` (RuntimeException)
  - `Bybit\Exception\ConfigurationException` — missing api_key / conflicting options
  - `Bybit\Exception\TransportException`
    - `Bybit\Exception\TimeoutException`
    - `Bybit\Exception\NetworkException`
    - `Bybit\Exception\ServerException` (5xx w/o body)
    - `Bybit\Exception\ClientException` (non-auth 4xx w/o body)
    - `Bybit\Exception\ParseException` — body did not parse or shape mismatch (has `getBody()`, `getHttpStatus()`)
  - `Bybit\Exception\ApiException` — Bybit V5 body with retCode != 0
    - `Bybit\Exception\AuthException`
    - `Bybit\Exception\RateLimitException`

Every `ApiException` exposes `getRetCode()`, `getRetMsg()`, `getResult()`, `getTime()`, `getHttpStatus()`. See the [Bybit V5 error-code list](https://bybit-exchange.github.io/docs/v5/error).

## Return Value

Every service method returns the raw parsed JSON as an associative `array`:

```php
$response = $client->market->getKline('BTCUSDT', '1', ['category' => 'spot']);
$response['retCode'];  // => 0
$response['retMsg'];   // => 'OK'
$response['result'];   // => ['category' => 'spot', 'symbol' => 'BTCUSDT', 'list' => [...]]
$response['time'];     // => 1234567890000
```

## Testnet

Toggle `testnet = true` for [https://testnet.bybit.com](https://testnet.bybit.com):

```php
$config = new Configuration();
$config->apiKey    = getenv('BYBIT_TESTNET_KEY');
$config->apiSecret = getenv('BYBIT_TESTNET_SECRET');
$config->testnet   = true;
$client = new Client($config);
```

## Development

```
composer install
composer test           # PHPUnit
composer stan           # PHPStan level 6
composer cs-check       # PHP-CS-Fixer dry-run
```

## License

MIT — see [LICENSE](LICENSE).

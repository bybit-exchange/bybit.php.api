<?php

declare(strict_types=1);

// End-to-end quickstart — public + signed + full exception matrix.
// Run with: BYBIT_TESTNET_KEY=... BYBIT_TESTNET_SECRET=... php examples/quickstart.php

require __DIR__ . '/../vendor/autoload.php';

use Bybit\Client;
use Bybit\Configuration;
use Bybit\Exception\{
    ApiException,
    AuthException,
    ClientException,
    NetworkException,
    ParseException,
    RateLimitException,
    ServerException,
    TimeoutException,
};

$config = new Configuration(
    apiKey: getenv('BYBIT_TESTNET_KEY') ?: null,
    apiSecret: getenv('BYBIT_TESTNET_SECRET') ?: null,
    testnet: true,
);

$client = new Client($config);

echo '--- server time ---' . PHP_EOL;
print_r($client->market->getServerTime());

echo '--- kline (public) ---' . PHP_EOL;
print_r($client->market->getKline('spot', 'BTCUSDT', '1', ['limit' => 5]));

echo '--- fee rate (signed) ---' . PHP_EOL;
try {
    $fee = $client->account->getFeeRate('linear', ['symbol' => 'BTCUSDT']);
    print_r($fee['result']['list']);
} catch (AuthException $e) {
    fwrite(STDERR, sprintf("auth failed: [%s] %s\n", (string) $e->getRetCode(), (string) $e->getRetMsg()));
} catch (RateLimitException $e) {
    fwrite(STDERR, sprintf("rate-limited: %s\n", (string) $e->getRetMsg()));
} catch (TimeoutException $e) {
    fwrite(STDERR, sprintf("timeout: %s\n", $e->getMessage()));
} catch (NetworkException $e) {
    fwrite(STDERR, sprintf("network: %s\n", $e->getMessage()));
} catch (ServerException $e) {
    fwrite(STDERR, sprintf("server: %s\n", $e->getMessage()));
} catch (ClientException $e) {
    fwrite(STDERR, sprintf("client: %s\n", $e->getMessage()));
} catch (ParseException $e) {
    fwrite(STDERR, sprintf("parse: %s\n", $e->getMessage()));
} catch (ApiException $e) {
    fwrite(STDERR, sprintf("api error [%s]: %s\n", (string) $e->getRetCode(), (string) $e->getRetMsg()));
}

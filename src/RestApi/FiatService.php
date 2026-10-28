<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class FiatService extends BaseService
{
    /**
     * Query Account Balance
     *
     * GET /v5/fiat/balance-query
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/balance-query
     */
    public function queryBalance(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/fiat/balance-query', $options);
    }

    /**
     * Get Trading Pairs
     *
     * GET /v5/fiat/query-coin-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/query-coin-list
     */
    public function queryCoinList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/fiat/query-coin-list', $options);
    }

    /**
     * Query Trade History
     *
     * GET /v5/fiat/query-trade-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/query-trade-history
     */
    public function queryTradeHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/fiat/query-trade-history', $options);
    }

    /**
     * Request a Quote
     *
     * POST /v5/fiat/quote-apply
     *
     * @param string $fromCoin Convert from coin (coin to sell)
     * @param string $fromCoinType From coin type
     * @param string $toCoin Convert to coin (coin to buy)
     * @param string $toCoinType To coin type
     * @param string $requestAmount Request coin amount (the amount you want to sell)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/quote-apply
     */
    public function applyQuote(string $fromCoin, string $fromCoinType, string $toCoin, string $toCoinType, string $requestAmount, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fiat/quote-apply',
            array_merge($options, ['fromCoin' => $fromCoin, 'fromCoinType' => $fromCoinType, 'toCoin' => $toCoin, 'toCoinType' => $toCoinType, 'requestAmount' => $requestAmount])
        );
    }

    /**
     * Get Reference Price
     *
     * GET /v5/fiat/reference-price
     *
     * @param string $symbol Coin pair, format: "FIAT-CRYPTO", e.g., "EUR-USDT"
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/reference-price
     */
    public function getReferencePrice(string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/fiat/reference-price',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Confirm Quote and Execute Trade
     *
     * POST /v5/fiat/trade-execute
     *
     * @param string $quoteTxId Quote transaction ID from the quote request
     * @param string $subUserId The user's sub userId in Bybit
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/confirm-quote
     */
    public function confirmQuote(string $quoteTxId, string $subUserId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fiat/trade-execute',
            array_merge($options, ['quoteTxId' => $quoteTxId, 'subUserId' => $subUserId])
        );
    }

    /**
     * Query Trade Status
     *
     * GET /v5/fiat/trade-query
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/fiat-convert/query-trade
     */
    public function queryTrade(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/fiat/trade-query', $options);
    }

}

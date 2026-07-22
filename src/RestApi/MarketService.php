<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class MarketService extends BaseService
{
    /**
     * Get ADL Alert status for a given symbol.
     *
     * GET /v5/market/adlAlert
     *
     * @param array{symbol?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/adl-alert
     */
    public function getAdlAlert(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/market/adlAlert', $options);
    }

    /**
     * Get the delivery price for delivery contracts.
     *
     * GET /v5/market/delivery-price
     *
     * @param string $category
     * @param array{symbol?:string, baseCoin?:string, settleCoin?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/delivery-price
     */
    public function getDeliveryPrice(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/delivery-price',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get the fee group structure for a given product type.
     *
     * GET /v5/market/fee-group-info
     *
     * @param string $productType
     * @param array{groupId?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/fee-group-info
     */
    public function getFeeGroupInfo(string $productType, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/fee-group-info',
            array_merge($options, ['productType' => $productType])
        );
    }

    /**
     * Get the historical funding rate for a symbol.
     *
     * GET /v5/market/funding/history
     *
     * @param string $category
     * @param string $symbol
     * @param array{startTime?:int, endTime?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/history-fund-rate
     */
    public function getFundingRateHistory(string $category, string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/funding/history',
            array_merge($options, ['category' => $category, 'symbol' => $symbol])
        );
    }

    /**
     * Get historical volatility data for options.
     *
     * GET /v5/market/historical-volatility
     *
     * @param string $category
     * @param array{baseCoin?:string, quoteCoin?:string, period?:int, startTime?:int, endTime?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/iv
     */
    public function getHistoricalVolatility(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/historical-volatility',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get index price components for a given index name.
     *
     * GET /v5/market/index-price-components
     *
     * @param string $indexName
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getIndexPriceComponents(string $indexName, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/index-price-components',
            array_merge($options, ['indexName' => $indexName])
        );
    }

    /**
     * Get the index price kline for a given symbol.
     *
     * GET /v5/market/index-price-kline
     *
     * @param string $symbol
     * @param string $interval
     * @param array{category?:string, start?:int, end?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/index-kline
     */
    public function getIndexPriceKline(string $symbol, string $interval, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/index-price-kline',
            array_merge($options, ['symbol' => $symbol, 'interval' => $interval])
        );
    }

    /**
     * Get instruments information for the given category.
     *
     * GET /v5/market/instruments-info
     *
     * @param string $category
     * @param array{symbol?:string, status?:string, baseCoin?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/instrument
     */
    public function getInstrumentsInfo(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/instruments-info',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get the insurance pool balance data.
     *
     * GET /v5/market/insurance
     *
     * @param array{coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/insurance
     */
    public function getInsurancePool(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/market/insurance', $options);
    }

    /**
     * Get the long/short ratio for a symbol.
     *
     * GET /v5/market/account-ratio
     *
     * @param string $category
     * @param string $symbol
     * @param string $period
     * @param array{startTime?:string, endTime?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/long-short-ratio
     */
    public function getLongShortRatio(string $category, string $symbol, string $period, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/account-ratio',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'period' => $period])
        );
    }

    /**
     * Get the kline (candlestick) data for a symbol.
     *
     * GET /v5/market/kline
     *
     * @param string $symbol
     * @param string $interval
     * @param array{category?:string, start?:int, end?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/kline
     */
    public function getKline(string $symbol, string $interval, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/kline',
            array_merge($options, ['symbol' => $symbol, 'interval' => $interval])
        );
    }

    /**
     * Get the mark price kline for a given symbol.
     *
     * GET /v5/market/mark-price-kline
     *
     * @param string $symbol
     * @param string $interval
     * @param array{category?:string, start?:int, end?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/mark-kline
     */
    public function getMarkPriceKline(string $symbol, string $interval, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/mark-price-kline',
            array_merge($options, ['symbol' => $symbol, 'interval' => $interval])
        );
    }

    /**
     * Get the latest delivery price for a base coin.
     *
     * GET /v5/market/new-delivery-price
     *
     * @param string $category
     * @param string $baseCoin
     * @param array{settleCoin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/new-delivery-price
     */
    public function getNewDeliveryPrice(string $category, string $baseCoin, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/new-delivery-price',
            array_merge($options, ['category' => $category, 'baseCoin' => $baseCoin])
        );
    }

    /**
     * Get the open interest history of a symbol.
     *
     * GET /v5/market/open-interest
     *
     * @param string $category
     * @param string $symbol
     * @param string $intervalTime
     * @param array{startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/open-interest
     */
    public function getOpenInterest(string $category, string $symbol, string $intervalTime, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/open-interest',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'intervalTime' => $intervalTime])
        );
    }

    /**
     * Get orderbook data for a given symbol and category.
     *
     * GET /v5/market/orderbook
     *
     * @param string $category
     * @param string $symbol
     * @param array{limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/orderbook
     */
    public function getOrderbook(string $category, string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/orderbook',
            array_merge($options, ['category' => $category, 'symbol' => $symbol])
        );
    }

    /**
     * Get the order price limit for a symbol.
     *
     * GET /v5/market/price-limit
     *
     * @param string $symbol
     * @param array{category?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getOrderPriceLimit(string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/price-limit',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Get premium index price kline for a given symbol and interval.
     *
     * GET /v5/market/premium-index-price-kline
     *
     * @param string $symbol
     * @param string $interval
     * @param array{category?:string, start?:int, end?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/premium-index-kline
     */
    public function getPremiumIndexPriceKline(string $symbol, string $interval, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/premium-index-price-kline',
            array_merge($options, ['symbol' => $symbol, 'interval' => $interval])
        );
    }

    /**
     * Get recent public trades for a given symbol or option base coin.
     *
     * GET /v5/market/recent-trade
     *
     * @param string $category
     * @param array{symbol?:string, baseCoin?:string, optionType?:string, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/recent-trade
     */
    public function getRecentPublicTrades(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/recent-trade',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get risk limit for linear and inverse contracts.
     *
     * GET /v5/market/risk-limit
     *
     * @param string $category
     * @param array{symbol?:string, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/risk-limit
     */
    public function getRiskLimit(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/risk-limit',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get RPI orderbook data for a given symbol.
     *
     * GET /v5/market/rpi_orderbook
     *
     * @param string $symbol
     * @param int $limit
     * @param array{category?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRpiOrderbook(string $symbol, int $limit, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/rpi_orderbook',
            array_merge($options, ['symbol' => $symbol, 'limit' => $limit])
        );
    }

    /**
     * Get Bybit server time.
     *
     * GET /v5/market/time
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/time
     */
    public function getServerTime(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/market/time', $options);
    }

    /**
     * Get latest ticker information for symbols in a given category.
     *
     * GET /v5/market/tickers
     *
     * @param string $category
     * @param array{symbol?:string, baseCoin?:string, expDate?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/market/tickers
     */
    public function getTickers(string $category, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/market/tickers',
            array_merge($options, ['category' => $category])
        );
    }
}

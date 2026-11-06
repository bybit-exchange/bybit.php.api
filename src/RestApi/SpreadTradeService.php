<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class SpreadTradeService extends BaseService
{
    /**
     * Get Trade History
     *
     * GET /v5/spread/execution/list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/trade-history
     */
    public function getHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spread/execution/list', $options);
    }

    /**
     * Get Spread Instruments Info
     *
     * GET /v5/spread/instrument
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/market/instrument
     */
    public function getSpreadInstrumentsInfo(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/spread/instrument', $options);
    }

    /**
     * Get Spread Wallet Balance
     *
     * GET /v5/spread/max-qty
     *
     * @param string $symbol Spread trading pair.
     * @param string $side Order side:
- `1`: Buy
- `2`: Sell

     * @param string $orderPrice Order price.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/max-qty
     */
    public function getSpreadMaxQty(string $symbol, string $side, string $orderPrice, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spread/max-qty',
            array_merge($options, ['symbol' => $symbol, 'side' => $side, 'orderPrice' => $orderPrice])
        );
    }

    /**
     * Amend Order
     *
     * POST /v5/spread/order/amend
     *
     * @param string $symbol Spread combination symbol name.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/amend-order
     */
    public function amendSpreadOrder(string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spread/order/amend',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Cancel Order
     *
     * POST /v5/spread/order/cancel
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/cancel-order
     */
    public function cancelSpreadOrder(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/spread/order/cancel', $options);
    }

    /**
     * Cancel All Orders
     *
     * POST /v5/spread/order/cancel-all
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/cancel-all
     */
    public function cancelAllSpreadOrders(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/spread/order/cancel-all', $options);
    }

    /**
     * Create Order
     *
     * POST /v5/spread/order/create
     *
     * @param string $symbol Spread combination symbol name.
     * @param string $side Order direction.
     * @param string $orderType Order type.
     * @param string $qty Order quantity (string-formatted number).
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/create-order
     */
    public function createSpreadOrder(string $symbol, string $side, string $orderType, string $qty, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spread/order/create',
            array_merge($options, ['symbol' => $symbol, 'side' => $side, 'orderType' => $orderType, 'qty' => $qty])
        );
    }

    /**
     * Get Order History
     *
     * GET /v5/spread/order/history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/order-history
     */
    public function getSpreadOrderHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spread/order/history', $options);
    }

    /**
     * Get Open Orders
     *
     * GET /v5/spread/order/realtime
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/trade/open-order
     */
    public function getSpreadOpenOrders(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spread/order/realtime', $options);
    }

    /**
     * Get Spread Orderbook
     *
     * GET /v5/spread/orderbook
     *
     * @param string $symbol Spread combination symbol name, e.g. `SOLUSDT_SOL/USDT`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/market/orderbook
     */
    public function getSpreadOrderbook(string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spread/orderbook',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Get Spread Recent Public Trades
     *
     * GET /v5/spread/recent-trade
     *
     * @param string $symbol Spread combination symbol name, e.g. `SOLUSDT_SOL/USDT`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/market/recent-trade
     */
    public function getSpreadRecentTrades(string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spread/recent-trade',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Get Spread Tickers
     *
     * GET /v5/spread/tickers
     *
     * @param string $symbol Spread combination symbol name, e.g. `SOLUSDT_SOL/USDT`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spread/market/tickers
     */
    public function getSpreadTickers(string $symbol, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spread/tickers',
            array_merge($options, ['symbol' => $symbol])
        );
    }

}

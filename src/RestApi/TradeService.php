<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class TradeService extends BaseService
{
    /**
     * Get Trade History.
     *
     * GET /v5/execution/list
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/execution
     */
    public function getHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/execution/list',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Amend Order.
     *
     * POST /v5/order/amend
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/amend-order
     */
    public function amendOrder(string $category, string $symbol, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/amend',
            array_merge($options, ['category' => $category, 'symbol' => $symbol]));
    }

    /**
     * Batch Amend Orders.
     *
     * POST /v5/order/amend-batch
     *
     * @param string $category Product type
     * @param array $request Array of order amend requests
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/batch-amend
     */
    public function batchAmendOrders(string $category, array $request, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/amend-batch',
            array_merge($options, ['category' => $category, 'request' => $request]));
    }

    /**
     * Batch Cancel Orders.
     *
     * POST /v5/order/cancel-batch
     *
     * @param string $category Product type
     * @param array $request Array of order cancel requests
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/batch-cancel
     */
    public function batchCancelOrders(string $category, array $request, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/cancel-batch',
            array_merge($options, ['category' => $category, 'request' => $request]));
    }

    /**
     * Batch Create Orders.
     *
     * POST /v5/order/create-batch
     *
     * @param string $category Product type
     * @param array $request Array of order create requests
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/batch-place
     */
    public function batchCreateOrders(string $category, array $request, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/create-batch',
            array_merge($options, ['category' => $category, 'request' => $request]));
    }

    /**
     * Cancel All Orders.
     *
     * POST /v5/order/cancel-all
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/cancel-all
     */
    public function cancelAllOrders(string $category, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/cancel-all',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Cancel Order.
     *
     * POST /v5/order/cancel
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/cancel-order
     */
    public function cancelOrder(string $category, string $symbol, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/cancel',
            array_merge($options, ['category' => $category, 'symbol' => $symbol]));
    }

    /**
     * Create Order.
     *
     * POST /v5/order/create
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $side Buy or Sell
     * @param string $orderType Market or Limit
     * @param string $qty Order quantity
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/create-order
     */
    public function createOrder(string $category, string $symbol, string $side, string $orderType, string $qty, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/create',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'side' => $side, 'orderType' => $orderType, 'qty' => $qty]));
    }

    /**
     * Set DCP Time Window.
     *
     * POST /v5/order/disconnected-cancel-all
     *
     * @param int $timeWindow DCP trigger time window in seconds
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/dcp
     */
    public function dcpSetTimewindow(int $timeWindow, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/disconnected-cancel-all',
            array_merge($options, ['timeWindow' => $timeWindow]));
    }

    /**
     * Get Open Orders.
     *
     * GET /v5/order/realtime
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/open-order
     */
    public function getOpenOrders(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/order/realtime',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get Order History.
     *
     * GET /v5/order/history
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/order-list
     */
    public function getOrderHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/order/history',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get Spot Borrow Quota.
     *
     * GET /v5/order/spot-borrow-check
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $side Buy or Sell
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/spot-borrow-quota
     */
    public function getSpotBorrowQuota(string $category, string $symbol, string $side, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/order/spot-borrow-check',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'side' => $side]));
    }

    /**
     * Pre-check Order.
     *
     * POST /v5/order/pre-check
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $side Buy or Sell
     * @param string $orderType Market or Limit
     * @param string $qty Order quantity
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/order/pre-check-order
     */
    public function preCheckOrder(string $category, string $symbol, string $side, string $orderType, string $qty, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/order/pre-check',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'side' => $side, 'orderType' => $orderType, 'qty' => $qty]));
    }
}

<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class EventService extends BaseService
{
    /**
     * Get Event Contract Order History
     *
     * GET /v5/event/order-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/event/trade/order-list
     */
    public function getEcOrderHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/event/order-list', $options);
    }

    /**
     * Get Event Contract Active Orders
     *
     * GET /v5/event/order-realtime
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/event/trade/open-order
     */
    public function getEcActiveOrders(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/event/order-realtime', $options);
    }

    /**
     * Get Event Contract Position Info
     *
     * GET /v5/event/positions
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/event/trade/position
     */
    public function getEcPositionInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/event/positions', $options);
    }

    /**
     * Get Event Contract Settlement Records
     *
     * GET /v5/event/settlements
     *
     * @param string $symbol Symbol name, e.g. `BTCUSDT-EC`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/event/trade/settlement
     */
    public function getEcSettlements(string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/event/settlements',
            array_merge($options, ['symbol' => $symbol])
        );
    }

    /**
     * Get Event Contract Trade History
     *
     * GET /v5/event/trades
     *
     * @param string $symbol Symbol name, e.g. `BTCUSDT-EC`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/event/trade/execution
     */
    public function getEcTradeHistory(string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/event/trades',
            array_merge($options, ['symbol' => $symbol])
        );
    }

}

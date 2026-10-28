<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class StrategyService extends BaseService
{
    /**
     * Create a strategy order
     *
     * POST /v5/strategy/create
     *
     * @param array $params Request payload. strategyType: chaseOrder | iceberg | pov | twap.
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/strategy/create-strategy
     */
    public function createStrategy(array $params): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/strategy/create',
            $params
        );
    }

    /**
     * Query trading strategy list with filters
     *
     * GET /v5/strategy/list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/strategy/strategy-list
     */
    public function queryList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/strategy/list', $options);
    }

    /**
     * Query orders created by a specific strategy
     *
     * GET /v5/strategy/order-list
     *
     * @param string $strategyId Strategy ID to query orders for (UUID format).
This is the parent strategy whose child orders you want to retrieve.
Obtain from strategy creation response or strategy list query.

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/strategy/order-list
     */
    public function queryOrderList(string $strategyId, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/strategy/order-list',
            array_merge($options, ['strategyId' => $strategyId])
        );
    }

    /**
     * Stop a running strategy
     *
     * POST /v5/strategy/stop
     *
     * @param string $strategyId Strategy ID to stop (UUID format).
Obtained from strategy creation response or strategy list query.
Only Running or Untriggered strategies can be stopped.

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/strategy/stop-strategy
     */
    public function stopStrategy(string $strategyId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/strategy/stop',
            array_merge($options, ['strategyId' => $strategyId])
        );
    }

}

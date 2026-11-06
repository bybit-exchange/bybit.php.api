<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class PreUpgradeService extends BaseService
{
    /**
     * Get Pre-upgrade Transaction Log
     *
     * GET /v5/pre-upgrade/account/transaction-log
     *
     * @param string $category Product type:
- `linear`: USDC perpetual
- `option`: Options

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/transaction-log
     */
    public function getTransactionLog(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/account/transaction-log',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Pre-upgrade Delivery Record
     *
     * GET /v5/pre-upgrade/asset/delivery-record
     *
     * @param string $category Product type:
- `option`: Options

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/delivery
     */
    public function getDeliveryRecord(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/asset/delivery-record',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Pre-upgrade Settlement Record
     *
     * GET /v5/pre-upgrade/asset/settlement-record
     *
     * @param string $category Product type:
- `linear`: USDC perpetual

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/settlement
     */
    public function getSettlementRecord(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/asset/settlement-record',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Pre-upgrade Execution List
     *
     * GET /v5/pre-upgrade/execution/list
     *
     * @param string $category Product type:
- `linear`: USDT perpetual, USDC contract
- `inverse`: Inverse perpetual, Inverse futures
- `option`: Options
- `spot`: Spot trading

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/execution
     */
    public function getExecutionList(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/execution/list',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Pre-upgrade Order History
     *
     * GET /v5/pre-upgrade/order/history
     *
     * @param string $category Product type:
- `linear`: USDT perpetual, USDC contract
- `inverse`: Inverse perpetual, Inverse futures
- `option`: Options
- `spot`: Spot trading

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/order-list
     */
    public function getOrderHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/order/history',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Pre-Upgrade Closed PnL
     *
     * GET /v5/pre-upgrade/position/closed-pnl
     *
     * @param string $category Product type.
     * @param string $symbol Contract name.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/pre-upgrade/close-pnl
     */
    public function getClosedPnl(string $category, string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/pre-upgrade/position/closed-pnl',
            array_merge($options, ['category' => $category, 'symbol' => $symbol])
        );
    }

}

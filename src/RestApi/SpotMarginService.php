<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class SpotMarginService extends BaseService
{
    /**
     * Get Historical Interest Rate.
     *
     * GET /v5/spot-margin-trade/interest-rate-history
     *
     * @param string $currency Currency
     * @param array{vipLevel?:string, startTime?:int, endTime?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getHistoricalInterestRate(string $currency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/interest-rate-history',
            array_merge($options, ['currency' => $currency])
        );
    }

    /**
     * Get Position Tiers.
     *
     * GET /v5/spot-margin-trade/position-tiers
     *
     * @param array{currency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getPositionTiers(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/position-tiers', $options);
    }

    /**
     * Get Tiered Collateral Ratio.
     *
     * GET /v5/spot-margin-trade/collateral
     *
     * @param array{currency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTieredCollateralRatio(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/spot-margin-trade/collateral', $options);
    }

    /**
     * Get VIP Margin Data.
     *
     * GET /v5/spot-margin-trade/data
     *
     * @param array{vipLevel?:string, currency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getVipMarginData(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/spot-margin-trade/data', $options);
    }

    /**
     * Get Spot Margin Coin State
     *
     * GET /v5/spot-margin-trade/coinstate
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/coinstate
     */
    public function getTradeCoinState(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/coinstate', $options);
    }

    /**
     * Query Fixed-Rate Available Inventory
     *
     * GET /v5/spot-margin-trade/fixed-available-inventory
     *
     * @param string $currency Borrow coin name, uppercase only. e.g. `USDT`, `BTC`. **Required.**
     * @param string $term Loan term in days. e.g. `7`, `14`, `30`, `90`, `180`. **Required.**
     * @param string $annualRate Annual interest rate. e.g. `0.02` means 2%. **Required.**
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixed-available-inventory
     */
    public function queryFixedAvailableInventory(string $currency, string $term, string $annualRate, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/fixed-available-inventory',
            array_merge($options, ['currency' => $currency, 'term' => $term, 'annualRate' => $annualRate])
        );
    }

    /**
     * Fixed-Rate Borrow
     *
     * POST /v5/spot-margin-trade/fixedborrow
     *
     * @param string $orderCurrency Borrow coin name, uppercase only. e.g. `USDT`, `BTC`.
     * @param string $orderAmount Borrow amount.
     * @param string $annualRate Annual interest rate. e.g. `0.02` means 2%.
     * @param string $term Loan term in days. Supported values: `7`, `14`, `30`, `90`, `180`.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixedborrow
     */
    public function accountFixedBorrow(string $orderCurrency, string $orderAmount, string $annualRate, string $term, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spot-margin-trade/fixedborrow',
            array_merge($options, ['orderCurrency' => $orderCurrency, 'orderAmount' => $orderAmount, 'annualRate' => $annualRate, 'term' => $term])
        );
    }

    /**
     * Query Fixed-Rate Borrow Contracts
     *
     * GET /v5/spot-margin-trade/fixedborrow-contract-info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixedborrow-contract-info
     */
    public function queryFixedBorrowContracts(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/fixedborrow-contract-info', $options);
    }

    /**
     * Query Fixed-Rate Borrow Orders
     *
     * GET /v5/spot-margin-trade/fixedborrow-order-info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixedborrow-order-info
     */
    public function queryFixedBorrowOrders(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/fixedborrow-order-info', $options);
    }

    /**
     * Query Fixed-Rate Borrow Market
     *
     * GET /v5/spot-margin-trade/fixedborrow-order-quote
     *
     * @param string $orderCurrency Borrow coin name, uppercase only. e.g. `USDT`, `BTC`.
     * @param array{term?:string, orderBy?:string, sort?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixedborrow-order-quote
     */
    public function queryFixedBorrowMarket(string $orderCurrency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/fixedborrow-order-quote',
            array_merge($options, ['orderCurrency' => $orderCurrency])
        );
    }

    /**
     * Renew Fixed-Rate Borrow
     *
     * POST /v5/spot-margin-trade/fixedborrow-renew
     *
     * @param string $loanId The loan contract ID (corresponds to match order ID). Required.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/fixedborrow-renew
     */
    public function renewFixedBorrow(string $loanId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spot-margin-trade/fixedborrow-renew',
            array_merge($options, ['loanId' => $loanId])
        );
    }

    /**
     * Get Flexible Available Inventory
     *
     * GET /v5/spot-margin-trade/flexible-available-inventory
     *
     * @param string $currency Coin name, uppercase only. e.g. `BTC`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/flexible-available-inventory
     */
    public function getTradeFlexibleAvailableInventory(string $currency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/flexible-available-inventory',
            array_merge($options, ['currency' => $currency])
        );
    }

    /**
     * Get Auto Repay Mode
     *
     * GET /v5/spot-margin-trade/get-auto-repay-mode
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/get-auto-repay-mode
     */
    public function getTradeAutoRepayMode(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/get-auto-repay-mode', $options);
    }

    /**
     * Query Borrow Liability
     *
     * GET /v5/spot-margin-trade/liability
     *
     * @param string $currency Coin name, uppercase only. e.g. `USDT`, `BTC`. **Required.**
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/liability
     */
    public function queryBorrowLiability(string $currency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/liability',
            array_merge($options, ['currency' => $currency])
        );
    }

    /**
     * Get Max Borrowable Amount
     *
     * GET /v5/spot-margin-trade/max-borrowable
     *
     * @param string $currency Coin name, uppercase only. e.g. `BTC`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/max-borrowable
     */
    public function getTradeMaxBorrowable(string $currency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/max-borrowable',
            array_merge($options, ['currency' => $currency])
        );
    }

    /**
     * Get Repayment Available Amount
     *
     * GET /v5/spot-margin-trade/repayment-available-amount
     *
     * @param string $currency Coin name, uppercase only. e.g. `BTC`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/repayment-available-amount
     */
    public function getTradeRepaymentAvailableAmount(string $currency, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/spot-margin-trade/repayment-available-amount',
            array_merge($options, ['currency' => $currency])
        );
    }

    /**
     * Set Auto Repay Mode
     *
     * POST /v5/spot-margin-trade/set-auto-repay-mode
     *
     * @param string $autoRepayMode Auto repay mode switch:
- `1`: Enable auto repay — Enable auto repay
- `0`: Disable auto repay — Disable auto repay

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/set-auto-repay-mode
     */
    public function setAutoRepayMode(string $autoRepayMode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spot-margin-trade/set-auto-repay-mode',
            array_merge($options, ['autoRepayMode' => $autoRepayMode])
        );
    }

    /**
     * Set spot cross margin leverage
     *
     * POST /v5/spot-margin-trade/set-leverage
     *
     * @param string $leverage Leverage amount, valid range is 2 to 10
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/set-leverage
     */
    public function spotMarginSetLeverage(string $leverage, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spot-margin-trade/set-leverage',
            array_merge($options, ['leverage' => $leverage])
        );
    }

    /**
     * Get Spot Margin Trade Status
     *
     * GET /v5/spot-margin-trade/state
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/status
     */
    public function getTradeState(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/state', $options);
    }

    /**
     * Toggle spot cross margin mode
     *
     * POST /v5/spot-margin-trade/switch-mode
     *
     * @param string $spotMarginMode Spot margin mode: 1=on, 0=off
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/spot-margin-uta/switch-mode
     */
    public function spotMarginSwitchMode(string $spotMarginMode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/spot-margin-trade/switch-mode',
            array_merge($options, ['spotMarginMode' => $spotMarginMode])
        );
    }

}

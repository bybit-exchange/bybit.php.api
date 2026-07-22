<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class AccountService extends BaseService
{
    /**
     * Batch Set Collateral.
     *
     * POST /v5/account/set-collateral-switch-batch
     *
     * @param array $request List of collateral switch items
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/batch-set-collateral
     */
    public function batchSetCollateral(array $request, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/set-collateral-switch-batch',
            array_merge($options, ['request' => $request]));
    }

    /**
     * Get Account Info.
     *
     * GET /v5/account/info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/account-info
     */
    public function getInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/info', $options);
    }

    /**
     * Get Account Instruments.
     *
     * GET /v5/account/instruments-info
     *
     * @param string $category Product type
     * @param array{symbol?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getInstruments(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/instruments-info',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get Borrow History.
     *
     * GET /v5/account/borrow-history
     *
     * @param array{currency?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/borrow-history
     */
    public function getBorrowHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/borrow-history', $options);
    }

    /**
     * Get Collateral Info.
     *
     * GET /v5/account/collateral-info
     *
     * @param array{currency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/collateral-info
     */
    public function getCollateralInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/collateral-info', $options);
    }

    /**
     * Get DCP Info.
     *
     * GET /v5/account/query-dcp-info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/dcp-info
     */
    public function getDcpInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/query-dcp-info', $options);
    }

    /**
     * Get Fee Rate.
     *
     * GET /v5/account/fee-rate
     *
     * @param string $category Product type
     * @param array{symbol?:string, baseCoin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/fee-rate
     */
    public function getFeeRate(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/fee-rate',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get MMP State.
     *
     * GET /v5/account/mmp-state
     *
     * @param string $baseCoin Base coin
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/get-mmp-state
     */
    public function getMmpState(string $baseCoin, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/mmp-state',
            array_merge($options, ['baseCoin' => $baseCoin]));
    }

    /**
     * Get SMP Group.
     *
     * GET /v5/account/smp-group
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/smp-group
     */
    public function getSmpGroup(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/smp-group', $options);
    }

    /**
     * Get Transaction Log.
     *
     * GET /v5/account/transaction-log
     *
     * @param array{accountType?:string, category?:string, currency?:string, baseCoin?:string, type?:string, transSubType?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/transaction-log
     */
    public function getTransactionLog(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/transaction-log', $options);
    }

    /**
     * Get Wallet Balance for the given account type. Returns coin balances,
     * total equity, and available margin figures.
     *
     * GET /v5/account/wallet-balance
     *
     * NOTE: This endpoint is not currently exposed in the local OpenAPI spec —
     * it lives in the docs (https://bybit-exchange.github.io/docs/v5/account/wallet-balance)
     * and is hand-registered via the workflow's EXTRA_ENDPOINTS table so it
     * survives regeneration.
     *
     * @param string $accountType UNIFIED | CONTRACT | SPOT
     * @param array{coin?:string} $options coin — comma-separated coin filter (e.g. 'BTC,ETH,USDT').
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/wallet-balance
     */
    public function getWalletBalance(string $accountType, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/wallet-balance',
            array_merge($options, ['accountType' => $accountType]));
    }

    /**
     * Get Transferable Amount.
     *
     * GET /v5/account/withdrawal
     *
     * @param string $coinName Coin name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTransferableAmount(string $coinName, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/withdrawal',
            array_merge($options, ['coinName' => $coinName]));
    }

    /**
     * Get User Settings.
     *
     * GET /v5/account/user-setting-config
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getUserSettings(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/account/user-setting-config', $options);
    }

    /**
     * Manual Borrow.
     *
     * POST /v5/account/borrow
     *
     * @param string $coin Coin name
     * @param string $amount Amount to borrow
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function manualBorrow(string $coin, string $amount, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/borrow',
            array_merge($options, ['coin' => $coin, 'amount' => $amount]));
    }

    /**
     * Manual Repay.
     *
     * POST /v5/account/repay
     *
     * @param array{coin?:string, amount?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function manualRepay(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/repay', $options);
    }

    /**
     * No-Convert Repay.
     *
     * POST /v5/account/no-convert-repay
     *
     * @param string $coin Coin name
     * @param array{amount?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/no-convert-repay
     */
    public function noConvertRepay(string $coin, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/no-convert-repay',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * One-Click Repay: repay outstanding liabilities in one click for UTA accounts.
     *
     * POST /v5/account/quick-repayment
     *
     * @param array{coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/repay-liability
     */
    public function oneClickRepay(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/quick-repayment', $options);
    }

    /**
     * Reset MMP: reset the market maker protection state for the specified base coin.
     *
     * POST /v5/account/mmp-reset
     *
     * @param string $baseCoin Base coin
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/reset-mmp
     */
    public function resetMmp(string $baseCoin, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/mmp-reset',
            array_merge($options, ['baseCoin' => $baseCoin]));
    }

    /**
     * Set Collateral Coin: enable or disable a coin as collateral for USDC contract trading.
     *
     * POST /v5/account/set-collateral-switch
     *
     * @param string $coin Coin name
     * @param string $collateralSwitch ON or OFF collateral switch
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/set-collateral
     */
    public function setCollateralCoin(string $coin, string $collateralSwitch, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/set-collateral-switch',
            array_merge($options, ['coin' => $coin, 'collateralSwitch' => $collateralSwitch]));
    }

    /**
     * Set Margin Mode: switch margin mode between ISOLATED_MARGIN, REGULAR_MARGIN and PORTFOLIO_MARGIN.
     *
     * POST /v5/account/set-margin-mode
     *
     * @param string $setMarginMode Target margin mode: ISOLATED_MARGIN, REGULAR_MARGIN or PORTFOLIO_MARGIN
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/set-margin-mode
     */
    public function setMarginMode(string $setMarginMode, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/set-margin-mode',
            array_merge($options, ['setMarginMode' => $setMarginMode]));
    }

    /**
     * Set MMP: configure the market maker protection parameters for options trading.
     *
     * POST /v5/account/mmp-modify
     *
     * @param string $baseCoin Base coin
     * @param string $window Time window in milliseconds
     * @param string $frozenPeriod MMP frozen period in milliseconds
     * @param string $qtyLimit Quantity limit
     * @param string $deltaLimit Delta limit
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/set-mmp
     */
    public function setMmp(string $baseCoin, string $window, string $frozenPeriod, string $qtyLimit, string $deltaLimit, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/mmp-modify',
            array_merge($options, ['baseCoin' => $baseCoin, 'window' => $window, 'frozenPeriod' => $frozenPeriod, 'qtyLimit' => $qtyLimit, 'deltaLimit' => $deltaLimit]));
    }

    /**
     * Set Price Limit: enable or disable the modify-order price limit action for a category.
     *
     * POST /v5/account/set-limit-px-action
     *
     * @param string $category Product type
     * @param bool $modifyEnable Whether to enable modify price limit action
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function setPriceLimit(string $category, bool $modifyEnable, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/set-limit-px-action',
            array_merge($options, ['category' => $category, 'modifyEnable' => $modifyEnable]));
    }

    /**
     * Set Spot Hedging: enable or disable spot hedging feature for the portfolio margin account.
     *
     * POST /v5/account/set-hedging-mode
     *
     * @param string $setHedgingMode ON or OFF spot hedging mode
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/set-spot-hedge
     */
    public function setSpotHedging(string $setHedgingMode, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/set-hedging-mode',
            array_merge($options, ['setHedgingMode' => $setHedgingMode]));
    }

    /**
     * Upgrade to UTA Pro: upgrade the current account to Unified Trading Account Pro.
     *
     * POST /v5/account/upgrade-to-uta
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/upgrade-unified-account
     */
    public function upgradeToUtaPro(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/account/upgrade-to-uta', $options);
    }
}

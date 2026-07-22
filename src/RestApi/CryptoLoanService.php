<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class CryptoLoanService extends BaseService
{
    /**
     * Adjust Collateral (Add or Remove).
     *
     * POST /v5/crypto-loan-common/adjust-ltv
     *
     * @param string $currency Collateral currency
     * @param string $amount Adjustment amount
     * @param int $direction 1: add collateral, 2: remove collateral
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function adjustLtv(string $currency, string $amount, int $direction, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-common/adjust-ltv',
            array_merge($options, ['currency' => $currency, 'amount' => $amount, 'direction' => $direction]));
    }

    /**
     * Get Collateral Adjustment History.
     *
     * GET /v5/crypto-loan-common/adjustment-history
     *
     * @param array{adjustId?:int, collateralCurrency?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAdjustmentHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-common/adjustment-history', $options);
    }

    /**
     * Get Collateral Currency Data.
     *
     * GET /v5/crypto-loan-common/collateral-data
     *
     * @param array{currency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getCollateralData(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/crypto-loan-common/collateral-data', $options);
    }

    /**
     * Get Loanable Currency Data.
     *
     * GET /v5/crypto-loan-common/loanable-data
     *
     * @param array{currency?:string, vipLevel?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLoanableData(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-common/loanable-data', $options);
    }

    /**
     * Get Max Collateral Redeem Amount.
     *
     * GET /v5/crypto-loan-common/max-collateral-amount
     *
     * @param string $currency Collateral currency
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getMaxCollateralAmount(string $currency, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-common/max-collateral-amount',
            array_merge($options, ['currency' => $currency]));
    }

    /**
     * Get Crypto Loan Position.
     *
     * GET /v5/crypto-loan-common/position
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getPosition(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-common/position', $options);
    }

    /**
     * Calculate Max Borrowable Amount.
     *
     * POST /v5/crypto-loan-common/max-loan
     *
     * @param string $currency Loan currency
     * @param array $collateralList List of collateral items
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function maxLoan(string $currency, array $collateralList, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-common/max-loan',
            array_merge($options, ['currency' => $currency, 'collateralList' => $collateralList]));
    }

    /**
     * Cancel Borrow Order.
     *
     * POST /v5/crypto-loan-fixed/borrow-order-cancel
     *
     * @param string $orderId Order ID to cancel
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelFixedBorrowOrder(string $orderId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/borrow-order-cancel',
            array_merge($options, ['orderId' => $orderId]));
    }

    /**
     * Cancel Supply Order.
     *
     * POST /v5/crypto-loan-fixed/supply-order-cancel
     *
     * @param string $orderId Order ID to cancel
     * @param array{refundedAccount?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelFixedSupplyOrder(string $orderId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/supply-order-cancel',
            array_merge($options, ['orderId' => $orderId]));
    }

    /**
     * Create Fixed-Term Borrow Order.
     *
     * POST /v5/crypto-loan-fixed/borrow
     *
     * @param string $orderCurrency Loan currency
     * @param string $orderAmount Loan amount
     * @param string $annualRate Annual interest rate
     * @param string $term Loan term
     * @param array $collateralList List of collateral assets
     * @param array{autoRepay?:string, repayType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createFixedBorrow(string $orderCurrency, string $orderAmount, string $annualRate, string $term, array $collateralList, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/borrow',
            array_merge($options, ['orderCurrency' => $orderCurrency, 'orderAmount' => $orderAmount, 'annualRate' => $annualRate, 'term' => $term, 'collateralList' => $collateralList]));
    }

    /**
     * Fully Repay Loan.
     *
     * POST /v5/crypto-loan-fixed/fully-repay
     *
     * @param string $loanId Loan ID to repay
     * @param string $loanCurrency Loan currency
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function fullyRepayCryptoLoanFixed(string $loanId, string $loanCurrency, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/fully-repay',
            array_merge($options, ['loanId' => $loanId, 'loanCurrency' => $loanCurrency]));
    }

    /**
     * Get Borrow Contract Info.
     *
     * GET /v5/crypto-loan-fixed/borrow-contract-info
     *
     * @param array{orderId?:string, loanId?:string, orderCurrency?:string, term?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedBorrowContractInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-fixed/borrow-contract-info', $options);
    }

    /**
     * Get Borrow Order Info.
     *
     * GET /v5/crypto-loan-fixed/borrow-order-info
     *
     * @param array{orderId?:string, orderCurrency?:string, state?:string, term?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedBorrowOrderInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-fixed/borrow-order-info', $options);
    }

    /**
     * Get Borrow Market Quotes.
     *
     * GET /v5/crypto-loan-fixed/borrow-order-quote
     *
     * @param array{orderCurrency?:string, term?:string, orderBy?:string, sort?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedBorrowOrderQuote(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/crypto-loan-fixed/borrow-order-quote', $options);
    }

    /**
     * Get Renewal Information.
     *
     * GET /v5/crypto-loan-fixed/renew-info
     *
     * @param array{orderId?:string, orderCurrency?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedRenewInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-fixed/renew-info', $options);
    }

    /**
     * Get Supply Contract Info.
     *
     * GET /v5/crypto-loan-fixed/supply-contract-info
     *
     * @param array{orderId?:string, supplyId?:string, supplyCurrency?:string, term?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedSupplyContractInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-fixed/supply-contract-info', $options);
    }

    /**
     * Get Supply Order Info.
     *
     * GET /v5/crypto-loan-fixed/supply-order-info
     *
     * @param array{orderId?:string, orderCurrency?:string, state?:string, term?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedSupplyOrderInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-fixed/supply-order-info', $options);
    }

    /**
     * Get Supply Market Quotes.
     *
     * GET /v5/crypto-loan-fixed/supply-order-quote
     *
     * @param array{orderCurrency?:string, term?:string, orderBy?:string, sort?:int, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedSupplyOrderQuote(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/crypto-loan-fixed/supply-order-quote', $options);
    }

    /**
     * Renew Loan.
     *
     * POST /v5/crypto-loan-fixed/renew
     *
     * @param string $loanId Loan ID to renew
     * @param array $collateralList List of collateral assets
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function renewFixed(string $loanId, array $collateralList, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/renew',
            array_merge($options, ['loanId' => $loanId, 'collateralList' => $collateralList]));
    }

    /**
     * Repay with Collateral.
     *
     * POST /v5/crypto-loan-fixed/repay-collateral
     *
     * @param int $loanId Loan ID
     * @param string $loanCurrency Loan currency
     * @param string $collateralCoin Collateral coin
     * @param string $amount Amount to repay with collateral
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function repayFixedCollateral(int $loanId, string $loanCurrency, string $collateralCoin, string $amount, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-fixed/repay-collateral',
            array_merge($options, ['loanId' => $loanId, 'loanCurrency' => $loanCurrency, 'collateralCoin' => $collateralCoin, 'amount' => $amount]));
    }

    /**
     * Get Flexible Borrow History.
     *
     * GET /v5/crypto-loan-flexible/borrow-history
     *
     * @param array{orderId?:string, loanCurrency?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFlexibleBorrowHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-flexible/borrow-history', $options);
    }

    /**
     * Get Ongoing Flexible Borrow Info.
     *
     * GET /v5/crypto-loan-flexible/ongoing-coin
     *
     * @param array{loanCurrency?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFlexibleOngoingCoin(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-flexible/ongoing-coin', $options);
    }

    /**
     * Get Flexible Repayment History.
     *
     * GET /v5/crypto-loan-flexible/repayment-history
     *
     * @param array{repayId?:string, loanCurrency?:string, limit?:int, cursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFlexibleRepaymentHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/crypto-loan-flexible/repayment-history', $options);
    }

    /**
     * Create Flexible Borrow Order.
     *
     * POST /v5/crypto-loan-flexible/borrow
     *
     * @param string $loanCurrency Loan currency
     * @param string $loanAmount Loan amount
     * @param array $collateralList Collateral list
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function borrowFlexible(string $loanCurrency, string $loanAmount, array $collateralList, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-flexible/borrow',
            array_merge($options, ['loanCurrency' => $loanCurrency, 'loanAmount' => $loanAmount, 'collateralList' => $collateralList]));
    }

    /**
     * Repay Flexible Loan.
     *
     * POST /v5/crypto-loan-flexible/repay
     *
     * @param string $loanCurrency Loan currency
     * @param string $amount Repay amount
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function repayFlexible(string $loanCurrency, string $amount, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-flexible/repay',
            array_merge($options, ['loanCurrency' => $loanCurrency, 'amount' => $amount]));
    }

    /**
     * Repay with Collateral.
     *
     * POST /v5/crypto-loan-flexible/repay-collateral
     *
     * @param string $loanCurrency Loan currency
     * @param string $collateralCoin Collateral coin
     * @param string $amount Repay amount
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function repayFlexibleWithCollateral(string $loanCurrency, string $collateralCoin, string $amount, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/crypto-loan-flexible/repay-collateral',
            array_merge($options, ['loanCurrency' => $loanCurrency, 'collateralCoin' => $collateralCoin, 'amount' => $amount]));
    }
}

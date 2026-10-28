<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class LendingService extends BaseService
{
    /**
     * Bind/Unbind UID
     *
     * POST /v5/ins-loan/association-uid
     *
     * @param string $uid The UID to bind or unbind.
     * @param string $operate Operation type. `0`: Bind, `1`: Unbind.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanAssociationUid(string $uid, string $operate, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/ins-loan/association-uid',
            array_merge($options, ['uid' => $uid, 'operate' => $operate])
        );
    }

    /**
     * Get Coin Delta Amount
     *
     * GET /v5/ins-loan/coin-delta-amount
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanCoinDeltaAmount(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/coin-delta-amount', $options);
    }

    /**
     * Get Delay Liquidation Status
     *
     * GET /v5/ins-loan/delay-liq-status
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanDelayLiqStatus(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/delay-liq-status', $options);
    }

    /**
     * Get Margin Token Conversion Details
     *
     * GET /v5/ins-loan/ensure-tokens-convert
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanEnsureTokensConvert(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/ensure-tokens-convert', $options);
    }

    /**
     * Get Loan Orders
     *
     * GET /v5/ins-loan/loan-order
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanGetLoanOrder(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/loan-order', $options);
    }

    /**
     * Get LTV with Conversion Details
     *
     * GET /v5/ins-loan/ltv-convert
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanLtvConvert(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/ltv-convert', $options);
    }

    /**
     * Get Product Infos
     *
     * GET /v5/ins-loan/product-infos
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanProductInfos(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/ins-loan/product-infos', $options);
    }

    /**
     * Get Repaid History
     *
     * GET /v5/ins-loan/repaid-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanRepaidHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/ins-loan/repaid-history', $options);
    }

    /**
     * Repay Loan
     *
     * POST /v5/ins-loan/repay-loan
     *
     * @param string $token Repayment coin name, uppercase only. e.g. `USDT`, `BTC`.
     * @param string $quantity Repayment quantity.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     */
    public function insLoanRepayLoan(string $token, string $quantity, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/ins-loan/repay-loan',
            array_merge($options, ['token' => $token, 'quantity' => $quantity])
        );
    }

}

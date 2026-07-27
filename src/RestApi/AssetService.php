<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class AssetService extends BaseService
{
    /**
     * Get Coin Balance - Query the balance of all coins under a specified account type.
     *
     * GET /v5/asset/transfer/query-account-coins-balance
     *
     * @param string $accountType Account type
     * @param array{memberId?:string, coin?:string, withBonus?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/balance/all-balance
     */
    public function getCoinBalance(string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/transfer/query-account-coins-balance',
            array_merge($options, ['accountType' => $accountType])
        );
    }

    /**
     * Get Funding History - Query the funding fee history record of a specified account.
     *
     * GET /v5/asset/fundinghistory
     *
     * @param array{createTimeFrom?:string, createTimeTo?:string, limit?:string, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryFundingDetail(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/fundinghistory', $options);
    }
}

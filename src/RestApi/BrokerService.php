<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class BrokerService extends BaseService
{
    /**
     * Distribute voucher.
     *
     * POST /v5/broker/award/distribute-award
     *
     * @param string $accountId The recipient sub account UID
     * @param string $awardId Voucher spec ID
     * @param string $specCode Voucher spec code
     * @param string $amount Voucher distribution amount
     * @param string $brokerId Broker ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function distributeAward(string $accountId, string $awardId, string $specCode, string $amount, string $brokerId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/broker/award/distribute-award',
            array_merge($options, ['accountId' => $accountId, 'awardId' => $awardId, 'specCode' => $specCode, 'amount' => $amount, 'brokerId' => $brokerId]));
    }

    /**
     * Get voucher details.
     *
     * POST /v5/broker/award/info
     *
     * @param string $id Voucher spec ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAwardInfo(string $id, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/broker/award/info',
            array_merge($options, ['id' => $id]));
    }

    /**
     * Query voucher distribution record.
     *
     * POST /v5/broker/award/distribution-record
     *
     * @param string $accountId Sub account UID
     * @param string $awardId Voucher spec ID
     * @param string $specCode Voucher spec code
     * @param array{withUsedAmount?:bool} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getDistributionRecord(string $accountId, string $awardId, string $specCode, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/broker/award/distribution-record',
            array_merge($options, ['accountId' => $accountId, 'awardId' => $awardId, 'specCode' => $specCode]));
    }

    /**
     * Get Broker Account Info.
     *
     * GET /v5/broker/account-info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryAccountInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/broker/account-info', $options);
    }

    /**
     * Query Broker All UID Rate Limits.
     *
     * GET /v5/broker/apilimit/query-all
     *
     * @param array{uids?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryAllUidDetails(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/broker/apilimit/query-all', $options);
    }

    /**
     * Query Broker Rate Limit Cap.
     *
     * GET /v5/broker/apilimit/query-cap
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryCap(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/broker/apilimit/query-cap', $options);
    }

    /**
     * Get Broker Earnings Info.
     *
     * GET /v5/broker/earnings-info
     *
     * @param array{bizType?:string, begin?:string, end?:string, uid?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryEarning(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/broker/earnings-info', $options);
    }

    /**
     * Set Broker API Rate Limit.
     *
     * POST /v5/broker/apilimit/set
     *
     * @param array{list?:array} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function setApiLimit(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/broker/apilimit/set', $options);
    }
}

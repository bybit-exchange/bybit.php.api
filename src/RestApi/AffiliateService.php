<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class AffiliateService extends BaseService
{
    /**
     * Get affiliate sub-affiliate list.
     *
     * GET /v5/affiliate/affiliate-sub-list
     *
     * @param array{cursor?:string, size?:int, startDate?:string, endDate?:string, subAffId?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/affiliate/affiliate-sub-list
     */
    public function getSubList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/affiliate/affiliate-sub-list', $options);
    }

    /**
     * Get affiliate user list.
     *
     * GET /v5/affiliate/aff-user-list
     *
     * @param array{cursor?:string, size?:int, needDeposit?:bool, need30?:bool, need365?:bool, startDate?:string, endDate?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getUserList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/affiliate/aff-user-list', $options);
    }
}

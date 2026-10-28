<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class TaxService extends BaseService
{
    /**
     * Batch create tax report files
     *
     * POST /v5/fht/compliance/tax/private/batch_create
     *
     * @param int $startTime Tax report start time (Unix timestamp, seconds)
     * @param int $endTime Tax report end time (Unix timestamp, seconds); interval must not exceed 12 months
     * @param array $items Non-empty list of tax report export items
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/tax/batch-create
     */
    public function batchCreateTaxReports(int $startTime, int $endTime, array $items, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fht/compliance/tax/private/batch_create',
            array_merge($options, ['startTime' => $startTime, 'endTime' => $endTime, 'items' => $items])
        );
    }

    /**
     * Query batch tax report status
     *
     * GET /v5/fht/compliance/tax/private/batch_query
     *
     * @param string $batchId Batch ID returned by `batch_create`
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/tax/batch-query
     */
    public function batchQueryTaxReports(string $batchId, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/fht/compliance/tax/private/batch_query',
            array_merge($options, ['batchId' => $batchId])
        );
    }

}

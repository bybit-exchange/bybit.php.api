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
        return $this->session->signRequest('GET', '/v5/spot-margin-trade/interest-rate-history',
            array_merge($options, ['currency' => $currency]));
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
}

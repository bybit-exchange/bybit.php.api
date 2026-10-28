<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class SpotXService extends BaseService
{
    /**
     * Get Launchpool project (activity) list
     *
     * GET /v5/spot-x/launchpool/project/list
     *
     * @param int $status Activity phase filter.
- `0` — Upcoming
- `1` — Ongoing
- `2` — Ended

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/launchpool/launchpool-project-list
     */
    public function getLaunchpoolProjectList(int $status, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spot-x/launchpool/project/list',
            array_merge($options, ['status' => $status])
        );
    }

    /**
     * Get user's staking operation records
     *
     * POST /v5/spot-x/launchpool/user/activity-log
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/launchpool/launchpool-activity-log
     */
    public function getLaunchpoolUserActivityLog(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/spot-x/launchpool/user/activity-log', $options);
    }

    /**
     * Get user's active staking positions
     *
     * GET /v5/spot-x/launchpool/user/current-staking
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/launchpool/launchpool-current-staking
     */
    public function getLaunchpoolUserCurrentStaking(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-x/launchpool/user/current-staking', $options);
    }

    /**
     * Get user's completed staking position history
     *
     * POST /v5/spot-x/launchpool/user/history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/launchpool/launchpool-user-history
     */
    public function getLaunchpoolUserHistory(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/spot-x/launchpool/user/history', $options);
    }

    /**
     * Get Puzzle project (activity) list
     *
     * GET /v5/spot-x/puzzle/project/list
     *
     * @param int $status Activity phase filter.
- `0` — Upcoming (not yet started)
- `1` — Ongoing (in progress)
- `2` — Ended

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/puzzle/puzzle-project-list
     */
    public function getPuzzleProjectList(int $status, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spot-x/puzzle/project/list',
            array_merge($options, ['status' => $status])
        );
    }

    /**
     * Get Token Splash project (activity) list
     *
     * GET /v5/spot-x/token-splash/project/list
     *
     * @param int $status Activity phase filter.
- `0` — Upcoming (registration not yet started)
- `1` — Ongoing (activity in progress)
- `2` — Ended (announcement time has passed)

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/token-splash/token-splash-project-list
     */
    public function getTokenSplashProjectList(int $status, array $options = []): array
    {
        return $this->session->publicRequest(
            'GET',
            '/v5/spot-x/token-splash/project/list',
            array_merge($options, ['status' => $status])
        );
    }

    /**
     * Get user's Token Splash trade-task progress
     *
     * GET /v5/spot-x/token-splash/user/activity-params
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/finance/spot-x/token-splash/token-splash-user-activity-params
     */
    public function getTokenSplashUserActivityParams(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/spot-x/token-splash/user/activity-params', $options);
    }

}

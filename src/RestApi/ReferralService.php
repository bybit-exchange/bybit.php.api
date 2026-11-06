<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class ReferralService extends BaseService
{
    /**
     * Get referral rules and campaign task rewards for the current site
     *
     * POST /v5/referral/campaign/rewards
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/referral/referral-campaign-rewards
     */
    public function getCampaignRewards(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/referral/campaign/rewards', $options);
    }

    /**
     * Get Referral Commission Records
     *
     * POST /v5/referral/commission/records
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/referral/referral-commission-records
     */
    public function getCommissionRecords(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/referral/commission/records', $options);
    }

    /**
     * Get Referral User Identity Profile
     *
     * POST /v5/referral/user/identity-profile
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/referral/referral-user-identity-profile
     */
    public function getUserIdentityProfile(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/referral/user/identity-profile', $options);
    }

    /**
     * Get referral user invitations
     *
     * POST /v5/referral/user/invitations
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/referral/referral-user-invitations
     */
    public function getUserInvitations(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/referral/user/invitations', $options);
    }

}

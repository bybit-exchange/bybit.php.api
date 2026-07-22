<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class UserService extends BaseService
{
    /**
     * Create a new API key for a sub-account.
     *
     * POST /v5/user/create-sub-api
     *
     * @param int $subuid Sub-account user id
     * @param int $readOnly_ 0: read-write, 1: read-only
     * @param array $permissions Permissions granted to the API key
     * @param array{ips?:string, note?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/create-subuid-apikey
     */
    public function createSubApiKey(int $subuid, int $readOnly_, array $permissions, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/create-sub-api',
            array_merge($options, ['subuid' => $subuid, 'readOnly' => $readOnly_, 'permissions' => $permissions])
        );
    }

    /**
     * Create a new sub-account (Sub UID).
     *
     * POST /v5/user/create-sub-member
     *
     * @param string $username Sub-account username
     * @param int $memberType 1: normal sub-account, 6: custodial sub-account
     * @param array{password?:string, switch?:int, isUta?:bool, note?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/create-subuid
     */
    public function createSubMember(string $username, int $memberType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/create-sub-member',
            array_merge($options, ['username' => $username, 'memberType' => $memberType])
        );
    }

    /**
     * Delete the master account API key.
     *
     * POST /v5/user/delete-api
     *
     * @param array{apikey?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/rm-master-apikey
     */
    public function deleteApiKey(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/user/delete-api', $options);
    }

    /**
     * Delete an API key of a sub-account.
     *
     * POST /v5/user/delete-sub-api
     *
     * @param int $subuid Sub-account user id
     * @param array{apikey?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function deleteSubApiKey(int $subuid, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/delete-sub-api',
            array_merge($options, ['subuid' => $subuid])
        );
    }

    /**
     * Delete a sub-account.
     *
     * POST /v5/user/del-submember
     *
     * @param int $subuid Sub-account user id to be deleted
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function deleteSubMember(int $subuid, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/del-submember',
            array_merge($options, ['subuid' => $subuid])
        );
    }

    /**
     * Freeze or unfreeze a Sub UID.
     *
     * POST /v5/user/frozen-sub-member
     *
     * @param int $subuid Sub-account user id
     * @param int $frozen 0: unfreeze, 1: freeze
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/froze-subuid
     */
    public function frozenSubMember(int $subuid, int $frozen, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/frozen-sub-member',
            array_merge($options, ['subuid' => $subuid, 'frozen' => $frozen])
        );
    }

    /**
     * Get Affiliate User Info (extended with coin/business filters).
     *
     * GET /v5/user/aff-customer-info
     *
     * @param string $uid The user id of the direct affiliate client
     * @param array{coin?:string, business?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/apikey-info
     */
    public function getAffiliateCustomOpenInfo(string $uid, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/user/aff-customer-info',
            array_merge($options, ['uid' => $uid])
        );
    }

    /**
     * Get the account type of the master or specified member accounts.
     *
     * GET /v5/user/get-member-type
     *
     * @param array{memberIds?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getMemberAccountType(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/get-member-type', $options);
    }

    /**
     * List all API keys of a sub-account.
     *
     * GET /v5/user/sub-apikeys
     *
     * @param int $subuid Sub-account user id
     * @param array{limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/list-sub-apikeys
     */
    public function listSubApiKeys(int $subuid, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/user/sub-apikeys',
            array_merge($options, ['subuid' => $subuid])
        );
    }

    /**
     * Get information about the current API key in use.
     *
     * GET /v5/user/query-api
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/apikey-info
     */
    public function queryApiKey(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/query-api', $options);
    }

    /**
     * Query the escrow (fund management) sub-accounts.
     *
     * GET /v5/user/escrow_sub_members
     *
     * @param array{nextCursor?:int, pageSize?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryEscrowSubMembers(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/escrow_sub_members', $options);
    }

    /**
     * Query referrals invited by the current affiliate account.
     *
     * GET /v5/user/invitation/referrals
     *
     * @param array{cursor?:string, size?:int, status?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryReferrals(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/invitation/referrals', $options);
    }

    /**
     * Query the Sub UID list of the master account.
     *
     * GET /v5/user/query-sub-members
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/subuid-list
     */
    public function querySubMembers(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/query-sub-members', $options);
    }

    /**
     * Query the list of sub-accounts under the master account.
     *
     * GET /v5/user/submembers
     *
     * @param array{pageSize?:int, nextCursor?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function querySubMembersGet(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/user/submembers', $options);
    }

    /**
     * Sign Agreement — accept the Bybit user agreement for a given category.
     *
     * POST /v5/user/agreement
     *
     * @param int $category Agreement category identifier
     * @param bool $agree Whether the user agrees to the agreement
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/sign-agreement
     */
    public function signAgreement(int $category, bool $agree, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/agreement',
            array_merge($options, ['category' => $category, 'agree' => $agree])
        );
    }

    /**
     * Modify Master API Key — update permissions, IP whitelist, or read-only flag of the master API key.
     *
     * POST /v5/user/update-api
     *
     * @param array{readOnly?:int, ips?:string, permissions?:array} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/modify-master-apikey
     */
    public function updateApiKey(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/user/update-api', $options);
    }

    /**
     * Modify Sub-account API Key — update permissions, IP whitelist, or read-only flag of a sub-account API key.
     *
     * POST /v5/user/update-sub-api
     *
     * @param int $subuid Sub-account UID
     * @param int $readOnly_ 0: read-write, 1: read-only
     * @param array{apikey?:string, ips?:string, permissions?:array, note?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/user/modify-sub-apikey
     */
    public function updateSubApiKey(int $subuid, int $readOnly_, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/user/update-sub-api',
            array_merge($options, ['subuid' => $subuid, 'readOnly' => $readOnly_])
        );
    }
}

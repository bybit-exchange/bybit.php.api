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

    /**
     * Get Asset Overview
     *
     * GET /v5/asset/asset-overview
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/balance/asset-overview
     */
    public function getOverview(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/asset-overview', $options);
    }

    /**
     * Get Coin Greeks
     *
     * GET /v5/asset/coin-greeks
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/account/coin-greeks
     */
    public function getCoinGreeks(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/coin-greeks', $options);
    }

    /**
     * Get Coin Info
     *
     * GET /v5/asset/coin/query-info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/coin-info
     */
    public function queryCoinChainInfo(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/coin/query-info', $options);
    }

    /**
     * Small asset get quote
     *
     * POST /v5/asset/covert/get-quote
     *
     * @param string $accountType Wallet type. Only supports eb_convert_uta (Unified wallet)
     * @param string $toCoin Target currency. Each request supports one of: MNT, USDT, or USDC
     * @param array $fromCoinList Source currency list, e.g. ["BTC","XRP","ETH"]. Up to 20 coins per transaction
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert-small-balance/request-quote
     */
    public function smallAssetQuote(string $accountType, string $toCoin, array $fromCoinList, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/covert/get-quote',
            array_merge($options, ['accountType' => $accountType, 'toCoin' => $toCoin, 'fromCoinList' => $fromCoinList])
        );
    }

    /**
     * Small asset confirm conversion
     *
     * POST /v5/asset/covert/small-balance-execute
     *
     * @param string $quoteId Quote ID from the get-quote interface (/v5/asset/covert/get-quote)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert-small-balance/confirm-quote
     */
    public function smallAssetConvert(string $quoteId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/covert/small-balance-execute',
            array_merge($options, ['quoteId' => $quoteId])
        );
    }

    /**
     * Small asset conversion history query
     *
     * GET /v5/asset/covert/small-balance-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert-small-balance/exchange-history
     */
    public function querySmallAssetConvertOrder(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/covert/small-balance-history', $options);
    }

    /**
     * Small asset conversion list query
     *
     * GET /v5/asset/covert/small-balance-list
     *
     * @param string $accountType Wallet type. Only supports eb_convert_uta (Unified wallet)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert-small-balance/small-balanc-coins
     */
    public function querySmallAssetList(string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/covert/small-balance-list',
            array_merge($options, ['accountType' => $accountType])
        );
    }

    /**
     * Get Delivery Record
     *
     * GET /v5/asset/delivery-record
     *
     * @param string $category Product type:
- `linear`: USDT / USDC futures
- `inverse`: Inverse futures
- `option`: Options

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/delivery
     */
    public function getDeliveryRecord(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/delivery-record',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Set Deposit Account
     *
     * POST /v5/asset/deposit/deposit-to-account
     *
     * @param string $accountType Deposit target account type:
- `UNIFIED`: Unified Trading Account
- `FUND`: Fund Account (default)

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/set-deposit-acct
     */
    public function setDefaultDepositToAccount(string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/deposit/deposit-to-account',
            array_merge($options, ['accountType' => $accountType])
        );
    }

    /**
     * Get Master Deposit Address
     *
     * GET /v5/asset/deposit/query-address
     *
     * @param string $coin Coin symbol (uppercase only), e.g. `USDT`.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/master-deposit-addr
     */
    public function queryDepositAddress(string $coin, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/deposit/query-address',
            array_merge($options, ['coin' => $coin])
        );
    }

    /**
     * Get Internal Deposit Records (off-chain)
     *
     * GET /v5/asset/deposit/query-internal-record
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/internal-deposit-record
     */
    public function queryInternalDepositRecords(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/deposit/query-internal-record', $options);
    }

    /**
     * Get Deposit Records (on-chain)
     *
     * GET /v5/asset/deposit/query-record
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/deposit-record
     */
    public function queryDepositRecords(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/deposit/query-record', $options);
    }

    /**
     * Get Sub Deposit Address
     *
     * GET /v5/asset/deposit/query-sub-member-address
     *
     * @param string $coin Coin symbol (uppercase only).
     * @param string $chainType Chain type. Use `chain` value from the coin-info endpoint.
     * @param string $subMemberId Sub-account user ID.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/sub-deposit-addr
     */
    public function querySubMemberDepositAddress(string $coin, string $chainType, string $subMemberId, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/deposit/query-sub-member-address',
            array_merge($options, ['coin' => $coin, 'chainType' => $chainType, 'subMemberId' => $subMemberId])
        );
    }

    /**
     * Get Sub Deposit Records (on-chain)
     *
     * GET /v5/asset/deposit/query-sub-member-record
     *
     * @param string $subMemberId Sub-account UID.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/deposit/sub-deposit-record
     */
    public function querySubMemberDepositRecords(string $subMemberId, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/deposit/query-sub-member-record',
            array_merge($options, ['subMemberId' => $subMemberId])
        );
    }

    /**
     * Execute conversion
     *
     * POST /v5/asset/exchange/convert-execute
     *
     * @param string $quoteTxId Quote transaction ID returned by the QuoteApply interface
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert/confirm-quote
     */
    public function convertExecute(string $quoteTxId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/exchange/convert-execute',
            array_merge($options, ['quoteTxId' => $quoteTxId])
        );
    }

    /**
     * Query conversion result
     *
     * GET /v5/asset/exchange/convert-result-query
     *
     * @param string $quoteTxId Quote transaction ID
     * @param string $accountType Wallet type (scene code)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert/get-convert-result
     */
    public function queryResult(string $quoteTxId, string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/exchange/convert-result-query',
            array_merge($options, ['quoteTxId' => $quoteTxId, 'accountType' => $accountType])
        );
    }

    /**
     * Paginated conversion order query
     *
     * GET /v5/asset/exchange/order-record
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/exchange
     */
    public function queryOrderByPage(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/exchange/order-record', $options);
    }

    /**
     * Coin list query
     *
     * GET /v5/asset/exchange/query-coin-list
     *
     * @param string $accountType Wallet type. Supported values: eb_convert_funding, eb_convert_uta, eb_convert_spot, eb_convert_contract, eb_convert_inverse
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert/convert-coin-list
     */
    public function coinListQuery(string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/exchange/query-coin-list',
            array_merge($options, ['accountType' => $accountType])
        );
    }

    /**
     * Conversion history query
     *
     * GET /v5/asset/exchange/query-convert-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert/get-convert-history
     */
    public function convertHistoryQuery(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/exchange/query-convert-history', $options);
    }

    /**
     * Apply quote
     *
     * POST /v5/asset/exchange/quote-apply
     *
     * @param string $accountType Wallet type (required)
     * @param string $fromCoin Convert from coin (coin to sell), required
     * @param string $toCoin Convert to coin (coin to buy), required
     * @param string $requestAmount Request coin amount (the amount you want to sell), required
     * @param string $requestCoin Request coin, same as fromCoin, required
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/convert/apply-quote
     */
    public function quoteApply(string $accountType, string $fromCoin, string $toCoin, string $requestAmount, string $requestCoin, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/exchange/quote-apply',
            array_merge($options, ['accountType' => $accountType, 'fromCoin' => $fromCoin, 'toCoin' => $toCoin, 'requestAmount' => $requestAmount, 'requestCoin' => $requestCoin])
        );
    }

    /**
     * Get Portfolio Margin Info
     *
     * GET /v5/asset/portfolio-margin
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/portfolio-margin
     */
    public function getPortfolioMargin(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/portfolio-margin', $options);
    }

    /**
     * Get USDC Session Settlement
     *
     * GET /v5/asset/settlement-record
     *
     * @param string $category Product type:
- `linear`: USDC contract

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/settlement
     */
    public function getSettlementRecord(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/settlement-record',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get Total Members Assets
     *
     * GET /v5/asset/total-members-assets
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/total-members-assets
     */
    public function getTotalMembersAssets(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/total-members-assets', $options);
    }

    /**
     * Create Internal Transfer
     *
     * POST /v5/asset/transfer/inter-transfer
     *
     * @param string $transferId UUID for the transfer. Must be unique and manually generated by client.
     * @param string $coin Coin name, uppercase (e.g. BTC, USDT)
     * @param string $amount Transfer amount. Must be greater than zero. String format for precision.
     * @param string $fromAccountType Source account type
     * @param string $toAccountType Destination account type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/transfer/create-inter-transfer
     */
    public function interTransfer(string $transferId, string $coin, string $amount, string $fromAccountType, string $toAccountType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/transfer/inter-transfer',
            array_merge($options, ['transferId' => $transferId, 'coin' => $coin, 'amount' => $amount, 'fromAccountType' => $fromAccountType, 'toAccountType' => $toAccountType])
        );
    }

    /**
     * Get Single Coin Balance
     *
     * GET /v5/asset/transfer/query-account-coin-balance
     *
     * @param string $accountType Account type
     * @param string $coin Coin name, uppercase (e.g. USDT, BTC). Required.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/balance/account-coin-balance
     */
    public function accountCoinBalanceQuery(string $accountType, string $coin, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/transfer/query-account-coin-balance',
            array_merge($options, ['accountType' => $accountType, 'coin' => $coin])
        );
    }

    /**
     * Get Internal Transfer Records
     *
     * GET /v5/asset/transfer/query-inter-transfer-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/transfer/inter-transfer-list
     */
    public function interTransferListQuery(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/transfer/query-inter-transfer-list', $options);
    }

    /**
     * Get Sub UID List
     *
     * GET /v5/asset/transfer/query-sub-member-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/sub-uid-list
     */
    public function subMemberListQuery(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/transfer/query-sub-member-list', $options);
    }

    /**
     * Get Transferable Coin List
     *
     * GET /v5/asset/transfer/query-transfer-coin-list
     *
     * @param string $fromAccountType Source account type
     * @param string $toAccountType Destination account type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/transfer/transferable-coin
     */
    public function transferCoinListQuery(string $fromAccountType, string $toAccountType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/transfer/query-transfer-coin-list',
            array_merge($options, ['fromAccountType' => $fromAccountType, 'toAccountType' => $toAccountType])
        );
    }

    /**
     * Get Universal Transfer Records
     *
     * GET /v5/asset/transfer/query-universal-transfer-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/transfer/unitransfer-list
     */
    public function universalTransferListQuery(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/transfer/query-universal-transfer-list', $options);
    }

    /**
     * Create Universal Transfer
     *
     * POST /v5/asset/transfer/universal-transfer
     *
     * @param string $transferId UUID for the transfer
     * @param string $coin Coin name, uppercase
     * @param string $amount Transfer amount, must be greater than zero
     * @param int $fromMemberId Source UID
     * @param int $toMemberId Destination UID
     * @param string $fromAccountType Source account type
     * @param string $toAccountType Destination account type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/transfer/unitransfer
     */
    public function universalTransfer(string $transferId, string $coin, string $amount, int $fromMemberId, int $toMemberId, string $fromAccountType, string $toAccountType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/transfer/universal-transfer',
            array_merge($options, ['transferId' => $transferId, 'coin' => $coin, 'amount' => $amount, 'fromMemberId' => $fromMemberId, 'toMemberId' => $toMemberId, 'fromAccountType' => $fromAccountType, 'toAccountType' => $toAccountType])
        );
    }

    /**
     * Cancel Withdrawal
     *
     * POST /v5/asset/withdraw/cancel
     *
     * @param string $id Withdrawal ID to cancel
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/withdraw/cancel-withdraw
     */
    public function cancelWithdraw(string $id, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/withdraw/cancel',
            array_merge($options, ['id' => $id])
        );
    }

    /**
     * Withdraw
     *
     * POST /v5/asset/withdraw/create
     *
     * @param string $coin Coin name, uppercase
     * @param string $address Wallet address (forceChain=0/1) or Bybit main account UID (forceChain=2)
     * @param string $amount Withdrawal amount (string). Must be greater than 0 and meet coin precision requirements
     * @param int $timestamp Current timestamp in milliseconds. Used for replay attack prevention
     * @param string $accountType Account type to deduct from. Supports combo:
- `FUND`: Funding account
- `UTA`: Unified Trading Account
- `FUND,UTA`: Deduct from FUND first, then UTA for remainder

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/withdraw/withdraw
     */
    public function sendWithdraw(string $coin, string $address, string $amount, int $timestamp, string $accountType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/asset/withdraw/create',
            array_merge($options, ['coin' => $coin, 'address' => $address, 'amount' => $amount, 'timestamp' => $timestamp, 'accountType' => $accountType])
        );
    }

    /**
     * Get Withdrawal Address List
     *
     * GET /v5/asset/withdraw/query-address
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/withdraw/withdraw-address
     */
    public function queryWithdrawAddresses(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/withdraw/query-address', $options);
    }

    /**
     * Get Withdrawal Records
     *
     * GET /v5/asset/withdraw/query-record
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/withdraw/withdraw-record
     */
    public function queryWithdrawRecords(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/withdraw/query-record', $options);
    }

    /**
     * Get Available VASPs
     *
     * GET /v5/asset/withdraw/vasp/list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/withdraw/vasp-list
     */
    public function getVaspList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/asset/withdraw/vasp/list', $options);
    }

    /**
     * Get Withdrawable Amount
     *
     * GET /v5/asset/withdraw/withdrawable-amount
     *
     * @param string $coin Coin name, uppercase, e.g. USDT
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/asset/balance/delay-amount
     */
    public function getWithdrawableAmountByCoin(string $coin, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/asset/withdraw/withdrawable-amount',
            array_merge($options, ['coin' => $coin])
        );
    }

}

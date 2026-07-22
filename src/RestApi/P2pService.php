<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class P2pService extends BaseService
{
    /**
     * Get Account Information.
     *
     * POST /v5/p2p/user/personal/info
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAccountInfo(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/user/personal/info', $options);
    }

    /**
     * Get Ads.
     *
     * POST /v5/p2p/item/online
     *
     * @param string $tokenId Token ID
     * @param string $currencyId Currency ID
     * @param string $side Side
     * @param array{page?:string, size?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAds(string $tokenId, string $currencyId, string $side, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/online',
            array_merge($options, ['tokenId' => $tokenId, 'currencyId' => $currencyId, 'side' => $side]));
    }

    /**
     * Get All Orders.
     *
     * POST /v5/p2p/order/simplifyList
     *
     * @param int $page Page number
     * @param int $size Page size
     * @param array{status?:int, beginTime?:string, endTime?:string, tokenId?:string, side?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAllOrders(int $page, int $size, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/simplifyList',
            array_merge($options, ['page' => $page, 'size' => $size]));
    }

    /**
     * Get Chat Message.
     *
     * POST /v5/p2p/order/message/listpage
     *
     * @param string $orderId Order ID
     * @param string $size Page size
     * @param array{currentPage?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getChatMessages(string $orderId, string $size, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/message/listpage',
            array_merge($options, ['orderId' => $orderId, 'size' => $size]));
    }

    /**
     * Get Counterparty User Info.
     *
     * POST /v5/p2p/user/order/personal/info
     *
     * @param array{originalUid?:string, orderId?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getCounterpartyUserInfo(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/user/order/personal/info', $options);
    }

    /**
     * Get My Ad Details.
     *
     * POST /v5/p2p/item/info
     *
     * @param string $itemId Item ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getMyAdDetails(string $itemId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/info',
            array_merge($options, ['itemId' => $itemId]));
    }

    /**
     * Get My Ads.
     *
     * POST /v5/p2p/item/personal/list
     *
     * @param array{itemId?:string, status?:string, side?:string, tokenId?:string, page?:string, size?:string, currencyId?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getMyAds(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/personal/list', $options);
    }

    /**
     * Get Order Detail.
     *
     * POST /v5/p2p/order/info
     *
     * @param string $orderId Order ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getOrderDetail(string $orderId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/info',
            array_merge($options, ['orderId' => $orderId]));
    }

    /**
     * Get Pending Orders.
     *
     * POST /v5/p2p/order/pending/simplifyList
     *
     * @param int $page Page number
     * @param int $size Page size
     * @param array{status?:int, beginTime?:string, endTime?:string, tokenId?:string, side?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getPendingOrders(int $page, int $size, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/pending/simplifyList',
            array_merge($options, ['page' => $page, 'size' => $size]));
    }

    /**
     * Get User Payment.
     *
     * POST /v5/p2p/user/payment/list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getUserPayment(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/user/payment/list', $options);
    }

    /**
     * Mark Order as Paid.
     *
     * POST /v5/p2p/order/pay
     *
     * @param string $orderId Order ID
     * @param string $paymentType Payment type
     * @param string $paymentId Payment ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function markOrderAsPaid(string $orderId, string $paymentType, string $paymentId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/pay',
            array_merge($options, ['orderId' => $orderId, 'paymentType' => $paymentType, 'paymentId' => $paymentId]));
    }

    /**
     * Post Ad.
     *
     * POST /v5/p2p/item/create
     *
     * @param string $tokenId Token ID
     * @param string $currencyId Currency ID
     * @param string $side Side
     * @param string $priceType Price type
     * @param string $premium Premium
     * @param string $price Price
     * @param string $minAmount Minimum amount
     * @param string $maxAmount Maximum amount
     * @param string $remark Remark
     * @param array $tradingPreferenceSet Trading preference set
     * @param array $paymentIds Payment IDs
     * @param string $quantity Quantity
     * @param string $paymentPeriod Payment period
     * @param string $itemType Item type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createAd(string $tokenId, string $currencyId, string $side, string $priceType, string $premium, string $price, string $minAmount, string $maxAmount, string $remark, array $tradingPreferenceSet, array $paymentIds, string $quantity, string $paymentPeriod, string $itemType, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/create',
            array_merge($options, ['tokenId' => $tokenId, 'currencyId' => $currencyId, 'side' => $side, 'priceType' => $priceType, 'premium' => $premium, 'price' => $price, 'minAmount' => $minAmount, 'maxAmount' => $maxAmount, 'remark' => $remark, 'tradingPreferenceSet' => $tradingPreferenceSet, 'paymentIds' => $paymentIds, 'quantity' => $quantity, 'paymentPeriod' => $paymentPeriod, 'itemType' => $itemType]));
    }

    /**
     * Release Assets.
     *
     * POST /v5/p2p/order/finish
     *
     * @param string $orderId Order ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function releaseAssets(string $orderId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/finish',
            array_merge($options, ['orderId' => $orderId]));
    }

    /**
     * Remove Ad.
     *
     * POST /v5/p2p/item/cancel
     *
     * @param string $itemId Item ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function removeAd(string $itemId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/cancel',
            array_merge($options, ['itemId' => $itemId]));
    }

    /**
     * Send Chat Message.
     *
     * POST /v5/p2p/order/message/send
     *
     * @param string $message Message content
     * @param string $contentType Content type
     * @param string $orderId Order ID
     * @param string $msgUuid Message UUID
     * @param array{fileName?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function sendChatMessage(string $message, string $contentType, string $orderId, string $msgUuid, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/order/message/send',
            array_merge($options, ['message' => $message, 'contentType' => $contentType, 'orderId' => $orderId, 'msgUuid' => $msgUuid]));
    }

    /**
     * Update / Relist Ad.
     *
     * POST /v5/p2p/item/update
     *
     * @param string $id Ad ID
     * @param string $priceType Price type
     * @param string $premium Premium
     * @param string $price Price
     * @param string $minAmount Minimum amount
     * @param string $maxAmount Maximum amount
     * @param string $remark Remark
     * @param array $tradingPreferenceSet Trading preference set
     * @param array $paymentIds Payment IDs
     * @param string $actionType Action type
     * @param string $quantity Quantity
     * @param string $paymentPeriod Payment period
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function updateAd(string $id, string $priceType, string $premium, string $price, string $minAmount, string $maxAmount, string $remark, array $tradingPreferenceSet, array $paymentIds, string $actionType, string $quantity, string $paymentPeriod, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/item/update',
            array_merge($options, ['id' => $id, 'priceType' => $priceType, 'premium' => $premium, 'price' => $price, 'minAmount' => $minAmount, 'maxAmount' => $maxAmount, 'remark' => $remark, 'tradingPreferenceSet' => $tradingPreferenceSet, 'paymentIds' => $paymentIds, 'actionType' => $actionType, 'quantity' => $quantity, 'paymentPeriod' => $paymentPeriod]));
    }

    /**
     * Upload Chat File.
     *
     * POST /v5/p2p/oss/upload_file
     *
     * @param string $upload_file File to upload
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function uploadChatFile(string $upload_file, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/p2p/oss/upload_file',
            array_merge($options, ['upload_file' => $upload_file]));
    }
}

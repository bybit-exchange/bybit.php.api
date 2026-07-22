<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class RfqService extends BaseService
{
    /**
     * Accept Non-LP Quote.
     *
     * POST /v5/rfq/accept-other-quote
     *
     * @param string $rfqId RFQ ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function acceptNonLpQuote(string $rfqId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/accept-other-quote',
            array_merge($options, ['rfqId' => $rfqId]));
    }

    /**
     * Cancel All Quotes.
     *
     * POST /v5/rfq/cancel-all-quotes
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelAllQuotes(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/cancel-all-quotes', $options);
    }

    /**
     * Cancel All RFQs.
     *
     * POST /v5/rfq/cancel-all-rfq
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelAllRfqs(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/cancel-all-rfq', $options);
    }

    /**
     * Cancel Quote.
     *
     * POST /v5/rfq/cancel-quote
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelQuote(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/cancel-quote', $options);
    }

    /**
     * Cancel RFQ.
     *
     * POST /v5/rfq/cancel-rfq
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function cancelRfq(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/cancel-rfq', $options);
    }

    /**
     * Create Quote.
     *
     * POST /v5/rfq/create-quote
     *
     * @param string $rfqId RFQ ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createQuote(string $rfqId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/create-quote',
            array_merge($options, ['rfqId' => $rfqId]));
    }

    /**
     * Create RFQ.
     *
     * POST /v5/rfq/create-rfq
     *
     * @param array $counterparties List of counterparties
     * @param array $list_ RFQ leg list
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createRfq(array $counterparties, array $list_, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/create-rfq',
            array_merge($options, ['counterparties' => $counterparties, 'list' => $list_]));
    }

    /**
     * Execute Quote.
     *
     * POST /v5/rfq/execute-quote
     *
     * @param string $rfqId RFQ ID
     * @param string $quoteId Quote ID
     * @param string $quoteSide Quote side
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function executeQuote(string $rfqId, string $quoteId, string $quoteSide, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/rfq/execute-quote',
            array_merge($options, ['rfqId' => $rfqId, 'quoteId' => $quoteId, 'quoteSide' => $quoteSide]));
    }

    /**
     * Get Public Trades.
     *
     * GET /v5/rfq/public-trades
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getPublicTrades(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/public-trades', $options);
    }

    /**
     * Get Quotes.
     *
     * GET /v5/rfq/quote-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getQuotes(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/quote-list', $options);
    }

    /**
     * Get Quotes Realtime.
     *
     * GET /v5/rfq/quote-realtime
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getQuotesRealtime(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/quote-realtime', $options);
    }

    /**
     * Get RFQ Config.
     *
     * GET /v5/rfq/config
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getConfig(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/config', $options);
    }

    /**
     * Get RFQs.
     *
     * GET /v5/rfq/rfq-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRfqs(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/rfq-list', $options);
    }

    /**
     * Get RFQs Realtime.
     *
     * GET /v5/rfq/rfq-realtime
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRfqsRealtime(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/rfq-realtime', $options);
    }

    /**
     * Get Trade History.
     *
     * GET /v5/rfq/trade-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTradeHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/rfq/trade-list', $options);
    }
}

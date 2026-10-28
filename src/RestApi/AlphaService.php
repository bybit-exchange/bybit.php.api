<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class AlphaService extends BaseService
{
    /**
     * Get LP order history with status and execution details
     *
     * POST /v5/alpha/lp/order-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/order-list
     */
    public function getLpOrderList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/lp/order-list', $options);
    }

    /**
     * Get list of supported payment tokens
     *
     * POST /v5/alpha/lp/pay-token-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/pay-token-list
     */
    public function getLpPayTokenList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/lp/pay-token-list', $options);
    }

    /**
     * Get current prices for payment tokens
     *
     * POST /v5/alpha/lp/pay-token-price
     *
     * @param array $tokenCode Array of token codes to query (e.g., ["CEX_1", "CEX_2"]).
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/pay-token-price
     */
    public function getLpPayTokenPrice(array $tokenCode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/lp/pay-token-price',
            array_merge($options, ['tokenCode' => $tokenCode])
        );
    }

    /**
     * Get detailed information for a specific LP pool
     *
     * POST /v5/alpha/lp/pool-info
     *
     * @param string $poolAddress Pool contract address (from getLPPoolList).
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/pool-info
     */
    public function getLpPoolInfo(string $poolAddress, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/lp/pool-info',
            array_merge($options, ['poolAddress' => $poolAddress])
        );
    }

    /**
     * Get LP pool list with optional filters
     *
     * POST /v5/alpha/lp/pool-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/pool-list
     */
    public function getLpPoolList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/lp/pool-list', $options);
    }

    /**
     * Get user's LP positions with rewards and current value
     *
     * POST /v5/alpha/lp/position-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/position-list
     */
    public function getLpPositionList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/lp/position-list', $options);
    }

    /**
     * Redeem (withdraw) liquidity from a position
     *
     * POST /v5/alpha/lp/redeem
     *
     * @param int $positionId Position ID (from getLPPositionList).
     * @param string $poolAddress Pool contract address.
     * @param string $dercRatio Reduction ratio (0-1).
- "0.25" = redeem 25%
- "0.5" = redeem 50%
- "1" = close entire position (redeem 100%)

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/redeem
     */
    public function executeLpRedeem(int $positionId, string $poolAddress, string $dercRatio, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/lp/redeem',
            array_merge($options, ['positionId' => $positionId, 'poolAddress' => $poolAddress, 'dercRatio' => $dercRatio])
        );
    }

    /**
     * Stake tokens into an LP pool
     *
     * POST /v5/alpha/lp/stake
     *
     * @param int $positionId Position ID. Use `0` to create a new position, or provide an existing position ID to add liquidity.

     * @param string $poolAddress Pool contract address (from getLPPoolInfo).
     * @param string $payTokenAmount Payment token amount (positive decimal as string).
     * @param string $payTokenCode Payment token code (e.g., "CEX_1" for USDT).
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/lp/stake
     */
    public function executeLpStake(int $positionId, string $poolAddress, string $payTokenAmount, string $payTokenCode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/lp/stake',
            array_merge($options, ['positionId' => $positionId, 'poolAddress' => $poolAddress, 'payTokenAmount' => $payTokenAmount, 'payTokenCode' => $payTokenCode])
        );
    }

    /**
     * Buy prediction outcome tokens with USDC
     *
     * POST /v5/alpha/prediction/buy
     *
     * @param string $tokenId Outcome token ID to buy (from getPredictionEventDetail).
     * @param string $amount USDC amount to invest (positive decimal as string).
     * @param string $payTokenCode Payment token code. Phase 1 fixed to "USDC".
     * @param int $orderType
     * @param string $slippage Maximum acceptable price slippage as decimal (e.g., "0.05" = 5% tolerance).
     * @param string $eventId Event ID associated with the token.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/buy
     */
    public function executePredictionBuy(string $tokenId, string $amount, string $payTokenCode, int $orderType, string $slippage, string $eventId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/buy',
            array_merge($options, ['tokenId' => $tokenId, 'amount' => $amount, 'payTokenCode' => $payTokenCode, 'orderType' => $orderType, 'slippage' => $slippage, 'eventId' => $eventId])
        );
    }

    /**
     * Get prediction market matching engine availability status
     *
     * GET /v5/alpha/prediction/engine-status
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/engine-status
     */
    public function getPredictionEngineStatus(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/alpha/prediction/engine-status', $options);
    }

    /**
     * Get prediction event detail with all markets and outcomes
     *
     * POST /v5/alpha/prediction/event-detail
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/event-detail
     */
    public function getPredictionEventDetail(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/prediction/event-detail', $options);
    }

    /**
     * Get full order book snapshot for prediction tokens
     *
     * POST /v5/alpha/prediction/order-book
     *
     * @param array $tokenIds List of outcome token IDs. Maximum 20.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/order-book
     */
    public function getPredictionOrderBook(array $tokenIds, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/order-book',
            array_merge($options, ['tokenIds' => $tokenIds])
        );
    }

    /**
     * Estimate buy or sell order fill amount and fees before execution
     *
     * POST /v5/alpha/prediction/order-estimate
     *
     * @param string $tokenId Outcome token ID (from getPredictionEventDetail or getPredictionMarketList).
     * @param int $side
     * @param string $eventId Event ID associated with this token.
     * @param string $amount For BUY: USDC amount to invest (e.g., "100").
For SELL: number of shares to sell (e.g., "50").

     * @param int $orderType
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/order-estimate
     */
    public function getPredictionOrderEstimate(string $tokenId, int $side, string $eventId, string $amount, int $orderType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/order-estimate',
            array_merge($options, ['tokenId' => $tokenId, 'side' => $side, 'eventId' => $eventId, 'amount' => $amount, 'orderType' => $orderType])
        );
    }

    /**
     * Get user's prediction market order history
     *
     * POST /v5/alpha/prediction/order-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/order-list
     */
    public function getPredictionOrderList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/prediction/order-list', $options);
    }

    /**
     * Get available payment tokens (USDC) for prediction market
     *
     * GET /v5/alpha/prediction/pay-token-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/pay-token-list
     */
    public function getPredictionPayTokenList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/alpha/prediction/pay-token-list', $options);
    }

    /**
     * Get user's prediction market portfolio overview
     *
     * POST /v5/alpha/prediction/portfolio-summary
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/portfolio-summary
     */
    public function getPredictionPortfolioSummary(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/prediction/portfolio-summary', $options);
    }

    /**
     * Get user's historical closed prediction market positions
     *
     * POST /v5/alpha/prediction/position-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/position-history
     */
    public function getPredictionPositionHistory(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/prediction/position-history', $options);
    }

    /**
     * Get user's current open prediction market positions
     *
     * POST /v5/alpha/prediction/position-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/position-list
     */
    public function getPredictionPositionList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/prediction/position-list', $options);
    }

    /**
     * Get historical price chart data for prediction tokens
     *
     * POST /v5/alpha/prediction/price-history
     *
     * @param string $tokenId Outcome token ID.
     * @param string $interval
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/price-history
     */
    public function getPredictionPriceHistory(string $tokenId, string $interval, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/price-history',
            array_merge($options, ['tokenId' => $tokenId, 'interval' => $interval])
        );
    }

    /**
     * Sell prediction outcome token shares for USDC
     *
     * POST /v5/alpha/prediction/sell
     *
     * @param string $tokenId Outcome token ID to sell (from getPredictionPositionList).
     * @param string $size Number of shares to sell (positive decimal as string).
     * @param int $orderType
     * @param string $slippage Maximum acceptable price slippage as decimal (e.g., "0.05" = 5% tolerance).
     * @param string $eventId Event ID associated with the token.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/sell
     */
    public function executePredictionSell(string $tokenId, string $size, int $orderType, string $slippage, string $eventId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/sell',
            array_merge($options, ['tokenId' => $tokenId, 'size' => $size, 'orderType' => $orderType, 'slippage' => $slippage, 'eventId' => $eventId])
        );
    }

    /**
     * Get related side markets for a sports event
     *
     * POST /v5/alpha/prediction/side-market-list
     *
     * @param string $eventId Event ID to query markets for.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/side-market-list
     */
    public function getPredictionSideMarketList(string $eventId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/side-market-list',
            array_merge($options, ['eventId' => $eventId])
        );
    }

    /**
     * Get group standings and results for a tournament stage
     *
     * POST /v5/alpha/prediction/sports/group-stage-detail
     *
     * @param int $eventType Sports event type.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/sports-group-stage-detail
     */
    public function getPredictionGroupStageDetail(int $eventType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/sports/group-stage-detail',
            array_merge($options, ['eventType' => $eventType])
        );
    }

    /**
     * Get match list for a sports prediction event
     *
     * POST /v5/alpha/prediction/sports/match-list
     *
     * @param int $eventType Sports event type.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/sports-match-list
     */
    public function getPredictionMatchList(int $eventType, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/sports/match-list',
            array_merge($options, ['eventType' => $eventType])
        );
    }

    /**
     * Get tournament timeline stages for a sports event
     *
     * GET /v5/alpha/prediction/sports/timeline-stages
     *
     * @param int $eventType Sports event type.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/sports-timeline-stages
     */
    public function getPredictionTimelineStages(int $eventType, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/alpha/prediction/sports/timeline-stages',
            array_merge($options, ['eventType' => $eventType])
        );
    }

    /**
     * Get real-time prices for prediction outcome tokens
     *
     * POST /v5/alpha/prediction/token-price
     *
     * @param array $tokenIds List of outcome token IDs to query. Maximum 20.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/prediction/token-price
     */
    public function getPredictionTokenPrice(array $tokenIds, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/prediction/token-price',
            array_merge($options, ['tokenIds' => $tokenIds])
        );
    }

    /**
     * Get holding details for a specific token including quantity, PnL, and cost basis
     *
     * POST /v5/alpha/trade/asset-detail
     *
     * @param string $chainCode Blockchain code (ETH, SOL, BSC, etc.).
     * @param string $tokenAddress Token contract address on chain.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/asset-detail
     */
    public function getAssetDetail(string $chainCode, string $tokenAddress, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/asset-detail',
            array_merge($options, ['chainCode' => $chainCode, 'tokenAddress' => $tokenAddress])
        );
    }

    /**
     * Get user's on-chain token portfolio with holdings, PnL, and market value
     *
     * POST /v5/alpha/trade/asset-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/asset-list
     */
    public function getAssetList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/trade/asset-list', $options);
    }

    /**
     * Get token project details including description, social links, risk flag, and order limits
     *
     * POST /v5/alpha/trade/biz-token-details
     *
     * @param string $chainCode Blockchain code (ETH, SOL, BSC, etc.).
     * @param string $tokenAddress Token contract address on chain.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/biz-token-details
     */
    public function getBizTokenDetails(string $chainCode, string $tokenAddress, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/biz-token-details',
            array_merge($options, ['chainCode' => $chainCode, 'tokenAddress' => $tokenAddress])
        );
    }

    /**
     * Get on-chain tradable tokens with DEX token codes, risk flags, and order limits
     *
     * POST /v5/alpha/trade/biz-token-list
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/biz-token-list
     */
    public function getBizTokenList(array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/alpha/trade/biz-token-list', $options);
    }

    /**
     * Batch query token prices, 24h change, volume, market cap, liquidity, and holders by chain+address
     *
     * POST /v5/alpha/trade/biz-token-price-list
     *
     * @param array $tokenAddressInfo List of token identifiers to query. Maximum 20 items per request.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/biz-token-price-list
     */
    public function getBizTokenPriceList(array $tokenAddressInfo, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/biz-token-price-list',
            array_merge($options, ['tokenAddressInfo' => $tokenAddressInfo])
        );
    }

    /**
     * Get trade order history with status, fees, and execution details
     *
     * POST /v5/alpha/trade/order-list
     *
     * @param int $limit Results per page.
     * @param int $pageIndex Page number, starting from 1.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/order-list
     */
    public function getOrderList(int $limit, int $pageIndex, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/order-list',
            array_merge($options, ['limit' => $limit, 'pageIndex' => $pageIndex])
        );
    }

    /**
     * Get available payment tokens (USDT, USDC, etc.) with CEX token codes and supported chains
     *
     * POST /v5/alpha/trade/pay-token-list
     *
     * @param string $chainCode Blockchain code (e.g. SOL, MANTLE).
     * @param string $tokenAddress Token contract address on chain.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/pay-token-list
     */
    public function getPayTokenList(string $chainCode, string $tokenAddress, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/pay-token-list',
            array_merge($options, ['chainCode' => $chainCode, 'tokenAddress' => $tokenAddress])
        );
    }

    /**
     * Execute a buy order to purchase on-chain tokens with USDT/USDC, returns order number
     *
     * POST /v5/alpha/trade/purchase
     *
     * @param string $fromTokenCode Payment token code (`CEX_<id>`, e.g. `CEX_1` for USDT). Must match the quote request.
     * @param string $fromTokenAmount Payment amount (positive decimal as string). Must match the quote request.
     * @param string $toTokenCode Target on-chain token code (`DEX_<id>`). Must match the quote request.
     * @param string $slippage Slippage tolerance as decimal.
- `0.005` = 0.5%
- `0.01` = 1%
- `0.05` = 5%

     * @param string $quoteData Base64-encoded quote data. **Must pass as-is from `/quote` response.**
     * @param string $gas Estimated gas fee. **Must pass as-is from `/quote` response.**
     * @param int $quoteMode Quote mode. Must be consistent with the `/quote` request.
- `0`: Auto
- `1`: Price Priority
- `2`: Success Rate Priority

     * @param string $correctingCode MD5 checksum for data integrity. **Must pass as-is from `/quote` response.**
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/trade-purchase
     */
    public function executePurchase(string $fromTokenCode, string $fromTokenAmount, string $toTokenCode, string $slippage, string $quoteData, string $gas, int $quoteMode, string $correctingCode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/purchase',
            array_merge($options, ['fromTokenCode' => $fromTokenCode, 'fromTokenAmount' => $fromTokenAmount, 'toTokenCode' => $toTokenCode, 'slippage' => $slippage, 'quoteData' => $quoteData, 'gas' => $gas, 'quoteMode' => $quoteMode, 'correctingCode' => $correctingCode])
        );
    }

    /**
     * Get estimated price, fees, slippage, and gas for an on-chain token trade
     *
     * POST /v5/alpha/trade/quote
     *
     * @param int $tradeType Trade type.
- `1`: Purchase (buy on-chain token with payment token)
- `2`: Redeem (sell on-chain token for payment token)

     * @param string $fromTokenCode Source token code. Format: `CEX_<id>` or `DEX_<id>`.
- Purchase: CEX token code (e.g. `CEX_1` for USDT). Get from `getPayTokenList`.
- Redeem: DEX token code (e.g. `DEX_123`). Get from `getBizTokenList` or `getAssetList`.

     * @param string $fromTokenAmount Amount to pay (string-formatted positive decimal for precision). Must be greater than 0.
     * @param string $toTokenCode Target token code. Format: `CEX_<id>` or `DEX_<id>`.
- Purchase: DEX token code (e.g. `DEX_123`). Get from `getBizTokenList`.
- Redeem: CEX token code (e.g. `CEX_1` for USDT). Get from `getPayTokenList`.

     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/trade-quote
     */
    public function getTradeQuote(int $tradeType, string $fromTokenCode, string $fromTokenAmount, string $toTokenCode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/quote',
            array_merge($options, ['tradeType' => $tradeType, 'fromTokenCode' => $fromTokenCode, 'fromTokenAmount' => $fromTokenAmount, 'toTokenCode' => $toTokenCode])
        );
    }

    /**
     * Execute a sell order to redeem on-chain tokens for USDT/USDC, returns order number
     *
     * POST /v5/alpha/trade/redeem
     *
     * @param string $fromTokenCode On-chain token to sell (`DEX_<id>`). Must match the quote request.
     * @param string $fromTokenAmount Amount to sell (positive decimal as string). Must match the quote request.
     * @param string $toTokenCode Payment token to receive (`CEX_<id>`, e.g. `CEX_1` for USDT). Must match the quote request.
     * @param string $slippage Slippage tolerance as decimal.
- `0.005` = 0.5%
- `0.01` = 1%
- `0.05` = 5%

     * @param string $quoteData Base64-encoded quote data. **Must pass as-is from `/quote` response.**
     * @param string $gas Estimated gas fee. **Must pass as-is from `/quote` response.**
     * @param int $quoteMode Quote mode. Must be consistent with the `/quote` request.
- `0`: Auto
- `1`: Price Priority
- `2`: Success Rate Priority

     * @param string $correctingCode MD5 checksum for data integrity. **Must pass as-is from `/quote` response.**
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/alpha/trade/trade-redeem
     */
    public function executeRedeem(string $fromTokenCode, string $fromTokenAmount, string $toTokenCode, string $slippage, string $quoteData, string $gas, int $quoteMode, string $correctingCode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/alpha/trade/redeem',
            array_merge($options, ['fromTokenCode' => $fromTokenCode, 'fromTokenAmount' => $fromTokenAmount, 'toTokenCode' => $toTokenCode, 'slippage' => $slippage, 'quoteData' => $quoteData, 'gas' => $gas, 'quoteMode' => $quoteMode, 'correctingCode' => $correctingCode])
        );
    }

}

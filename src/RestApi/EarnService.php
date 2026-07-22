<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class EarnService extends BaseService
{
    /**
     * Add liquidity to a liquidity mining product.
     *
     * POST /v5/earn/liquidity-mining/add-liquidity
     *
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order link ID
     * @param array{quoteAccountType?:string, baseAccountType?:string, quoteAmount?:string, baseAmount?:string, leverage?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function addLiquidity(string $productId, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/liquidity-mining/add-liquidity',
            array_merge($options, ['productId' => $productId, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Add margin to a liquidity mining position.
     *
     * POST /v5/earn/liquidity-mining/add-margin
     *
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order link ID
     * @param string $positionId Position ID to add margin to
     * @param string $amount Margin amount to add
     * @param string $quoteAccountType Account type used for the quote coin
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function addMargin(string $productId, string $orderLinkId, string $positionId, string $amount, string $quoteAccountType, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/liquidity-mining/add-margin',
            array_merge($options, ['productId' => $productId, 'orderLinkId' => $orderLinkId, 'positionId' => $positionId, 'amount' => $amount, 'quoteAccountType' => $quoteAccountType]));
    }

    /**
     * Claim accrued interest from a liquidity mining product.
     *
     * POST /v5/earn/liquidity-mining/claim-interest
     *
     * @param string $productId Product ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function claimLiquidityInterest(string $productId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/liquidity-mining/claim-interest',
            array_merge($options, ['productId' => $productId]));
    }

    /**
     * Get advance earn order.
     *
     * GET /v5/earn/advance/order
     *
     * @param string $category Product category
     * @param array{productId?:int, orderId?:string, orderLinkId?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAdvanceEarnOrder(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/advance/order',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get advance earn position.
     *
     * GET /v5/earn/advance/position
     *
     * @param string $category Product category
     * @param array{productId?:int, coin?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAdvanceEarnPosition(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/advance/position',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get advance earn product info.
     *
     * GET /v5/earn/advance/product
     *
     * @param string $category Product category
     * @param array{coin?:string, duration?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAdvanceEarnProduct(string $category, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/advance/product',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get advance earn product extra info.
     *
     * GET /v5/earn/advance/product-extra-info
     *
     * @param string $category Product category
     * @param array{productId?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAdvanceEarnProductExtraInfo(string $category, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/advance/product-extra-info',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get double win leverage info.
     *
     * GET /v5/earn/advance/double-win-leverage
     *
     * @param int $productId Product ID
     * @param string $initialPrice Initial price
     * @param string $lowerPrice Lower price bound
     * @param string $upperPrice Upper price bound
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getDoubleWinLeverage(int $productId, string $initialPrice, string $lowerPrice, string $upperPrice, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/advance/double-win-leverage',
            array_merge($options, ['productId' => $productId, 'initialPrice' => $initialPrice, 'lowerPrice' => $lowerPrice, 'upperPrice' => $upperPrice]));
    }

    /**
     * Get earn product APR history.
     *
     * GET /v5/earn/apr-history
     *
     * @param string $category Product category
     * @param string $productId Product ID
     * @param int $startTime Start timestamp (ms)
     * @param int $endTime End timestamp (ms)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getAprHistory(string $category, string $productId, int $startTime, int $endTime, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/apr-history',
            array_merge($options, ['category' => $category, 'productId' => $productId, 'startTime' => $startTime, 'endTime' => $endTime]));
    }

    /**
     * Get earn hourly yield history.
     *
     * GET /v5/earn/hourly-yield
     *
     * @param string $category Product category
     * @param array{productId?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getHourlyYieldHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/hourly-yield',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get stake or redeem order history.
     *
     * GET /v5/earn/order
     *
     * @param string $category Product category
     * @param array{orderId?:string, orderLinkId?:string, productId?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getOrderHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/order',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get staked position.
     *
     * GET /v5/earn/position
     *
     * @param string $category Product category
     * @param array{productId?:string, coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getPosition(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/position',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get earn product info.
     *
     * GET /v5/earn/product
     *
     * @param string $category Product category
     * @param array{coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getProduct(string $category, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/product',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get earn yield history.
     *
     * GET /v5/earn/yield
     *
     * @param string $category Product category
     * @param array{productId?:int, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getYieldHistory(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/yield',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Get fixed term earn order history.
     *
     * GET /v5/earn/fixed-term/order
     *
     * @param array{orderType?:string, productId?:string, category?:string, orderId?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedTermOrder(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/fixed-term/order', $options);
    }

    /**
     * Query fixed term earn positions.
     *
     * GET /v5/earn/fixed-term/position
     *
     * @param array{productId?:string, category?:string, coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedTermPosition(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/fixed-term/position', $options);
    }

    /**
     * Get the list of fixed term earn products.
     *
     * GET /v5/earn/fixed-term/product
     *
     * @param array{coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFixedTermProduct(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/fixed-term/product', $options);
    }

    /**
     * Get the list of Hold-to-Earn products.
     *
     * GET /v5/earn/hold-to-earn/product
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getHoldToEarnProduct(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/hold-to-earn/product', $options);
    }

    /**
     * Get the yield history for Hold-to-Earn products.
     *
     * GET /v5/earn/hold-to-earn/yield-history
     *
     * @param int $limit Number of records to return
     * @param array{timeStart?:int, timeEnd?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getHoldToEarnYieldHistory(int $limit, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/hold-to-earn/yield-history',
            array_merge($options, ['limit' => $limit]));
    }

    /**
     * Get liquidation records for Liquidity Mining positions.
     *
     * GET /v5/earn/liquidity-mining/liquidation-records
     *
     * @param array{baseCoin?:string, quoteCoin?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLiquidityMiningLiquidationRecords(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/liquidity-mining/liquidation-records', $options);
    }

    /**
     * Get the liquidity mining order history.
     *
     * GET /v5/earn/liquidity-mining/order
     *
     * @param array{orderId?:string, orderLinkId?:string, productId?:string, orderType?:string, status?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLiquidityMiningOrders(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/liquidity-mining/order', $options);
    }

    /**
     * Get the list of active liquidity mining positions.
     *
     * GET /v5/earn/liquidity-mining/position
     *
     * @param array{productId?:string, baseCoin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLiquidityMiningPositions(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/liquidity-mining/position', $options);
    }

    /**
     * Get the list of liquidity mining products.
     *
     * GET /v5/earn/liquidity-mining/product
     *
     * @param array{baseCoin?:string, quoteCoin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLiquidityMiningProducts(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/liquidity-mining/product', $options);
    }

    /**
     * Get the liquidity mining yield claim records.
     *
     * GET /v5/earn/liquidity-mining/yield-records
     *
     * @param array{baseCoin?:string, quoteCoin?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getLiquidityMiningYieldRecords(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/liquidity-mining/yield-records', $options);
    }

    /**
     * Get the NAV chart data for an RWA product.
     *
     * GET /v5/earn/rwa/nav-chart
     *
     * @param int $productId Product ID
     * @param array{startTime?:int, endTime?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRwaNavChart(int $productId, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/rwa/nav-chart',
            array_merge($options, ['productId' => $productId]));
    }

    /**
     * Get the list of RWA orders.
     *
     * GET /v5/earn/rwa/order
     *
     * @param array{orderId?:string, orderLinkId?:string, orderType?:string, productId?:int, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRwaOrderList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/rwa/order', $options);
    }

    /**
     * Get the list of RWA positions.
     *
     * GET /v5/earn/rwa/position
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRwaPositionList(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/rwa/position', $options);
    }

    /**
     * Get the list of RWA earn products.
     *
     * GET /v5/earn/rwa/product
     *
     * @param array{coin?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getRwaProductList(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/rwa/product', $options);
    }

    /**
     * Get smart leverage redeem estimation amount list.
     *
     * GET /v5/earn/advance/get-redeem-est-amount-list
     *
     * @param string $category Product category
     * @param string $positionIds List of position IDs
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getSmartLeverageRedeemEstAmountList(string $category, string $positionIds, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/advance/get-redeem-est-amount-list',
            array_merge($options, ['category' => $category, 'positionIds' => $positionIds]));
    }

    /**
     * Get daily yield records for a token earn position.
     *
     * GET /v5/earn/token/yield
     *
     * @param string $coin Coin name
     * @param array{startTime?:int, endTime?:int, cursor?:string, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenDailyYield(string $coin, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/token/yield',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * Get historical APR data for a token earn product.
     *
     * GET /v5/earn/token/history-apr
     *
     * @param string $coin Coin name
     * @param int $range Time range
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenHistoricalApr(string $coin, int $range, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/token/history-apr',
            array_merge($options, ['coin' => $coin, 'range' => $range]));
    }

    /**
     * Get hourly yield records for a token earn position.
     *
     * GET /v5/earn/token/hourly-yield
     *
     * @param string $coin Coin name
     * @param array{startTime?:int, endTime?:int, cursor?:string, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenHourlyYield(string $coin, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/token/hourly-yield',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * Query the list of token earn orders.
     *
     * GET /v5/earn/token/order
     *
     * @param string $coin Coin name
     * @param array{orderLinkId?:string, orderId?:string, orderType?:string, startTime?:int, endTime?:int, cursor?:string, limit?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenOrderList(string $coin, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/token/order',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * Query the current token earn position for a coin.
     *
     * GET /v5/earn/token/position
     *
     * @param string $coin Coin name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenPosition(string $coin, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/token/position',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * Get token earn product information by coin.
     *
     * GET /v5/earn/token/product
     *
     * @param string $coin Coin name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getTokenProduct(string $coin, array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/token/product',
            array_merge($options, ['coin' => $coin]));
    }

    /**
     * List coupons for the specified category.
     *
     * GET /v5/earn/coupons
     *
     * @param string $category Product category
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function listCoupons(string $category, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/coupons',
            array_merge($options, ['category' => $category]));
    }

    /**
     * Modify an earn position (e.g. toggle auto reinvest).
     *
     * POST /v5/earn/position/modify
     *
     * @param string $category Product category
     * @param int $productId Product ID
     * @param int $positionId Position ID
     * @param int $autoReinvest Auto reinvest flag
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function modifyEarnPosition(string $category, int $productId, int $positionId, int $autoReinvest, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/position/modify',
            array_merge($options, ['category' => $category, 'productId' => $productId, 'positionId' => $positionId, 'autoReinvest' => $autoReinvest]));
    }

    /**
     * Place an advance earn order.
     *
     * POST /v5/earn/advance/place-order
     *
     * @param string $category Product category
     * @param int $productId Product ID
     * @param string $orderType Order type
     * @param string $amount Order amount
     * @param string $accountType Account type
     * @param string $coin Coin name
     * @param string $orderLinkId User-defined order ID
     * @param array{dualAssetsExtra?:array, interestCard?:array, smartLeverageStakeExtra?:array, smartLeverageRedeemExtra?:array, doubleWinStakeExtra?:array, doubleWinRedeemExtra?:array, discountBuyExtra?:array} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function placeAdvanceEarnOrder(string $category, int $productId, string $orderType, string $amount, string $accountType, string $coin, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/advance/place-order',
            array_merge($options, ['category' => $category, 'productId' => $productId, 'orderType' => $orderType, 'amount' => $amount, 'accountType' => $accountType, 'coin' => $coin, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Stake or redeem an earn product.
     *
     * POST /v5/earn/place-order
     *
     * @param string $category Product category
     * @param string $orderType Order type: Stake or Redeem
     * @param string $accountType Account type
     * @param string $amount Order amount
     * @param string $coin Coin name
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order ID
     * @param array{redeemPositionId?:string, toAccountType?:string, interestCard?:array} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function placeOrder(string $category, string $orderType, string $accountType, string $amount, string $coin, string $productId, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/place-order',
            array_merge($options, ['category' => $category, 'orderType' => $orderType, 'accountType' => $accountType, 'amount' => $amount, 'coin' => $coin, 'productId' => $productId, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Place a fixed term earn order.
     *
     * POST /v5/earn/fixed-term/place-order
     *
     * @param string $productId Product ID
     * @param string $category Product category
     * @param string $coin Coin name
     * @param string $amount Order amount
     * @param string $accountType Account type
     * @param string $orderLinkId User-customized order ID
     * @param array{autoInvest?:bool} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function placeFixedTermOrder(string $productId, string $category, string $coin, string $amount, string $accountType, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/fixed-term/place-order',
            array_merge($options, ['productId' => $productId, 'category' => $category, 'coin' => $coin, 'amount' => $amount, 'accountType' => $accountType, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Place a stake or redeem order for an RWA product.
     *
     * POST /v5/earn/rwa/place-order
     *
     * @param int $productId Product ID
     * @param string $orderType Order type (Stake or Redeem)
     * @param string $coin Coin name
     * @param string $orderLinkId User-defined order link ID
     * @param array{stakeAmount?:string, redeemShares?:string, accountType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function placeRwaOrder(int $productId, string $orderType, string $coin, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/rwa/place-order',
            array_merge($options, ['productId' => $productId, 'orderType' => $orderType, 'coin' => $coin, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Place a mint or redeem order for token products.
     *
     * POST /v5/earn/token/place-order
     *
     * @param string $coin Coin name
     * @param string $orderLinkId User-customized order ID
     * @param string $orderType Order type (Mint/Redeem)
     * @param string $amount Order amount
     * @param string $accountType Account type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function placeTokenOrder(string $coin, string $orderLinkId, string $orderType, string $amount, string $accountType, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/token/place-order',
            array_merge($options, ['coin' => $coin, 'orderLinkId' => $orderLinkId, 'orderType' => $orderType, 'amount' => $amount, 'accountType' => $accountType]));
    }

    /**
     * Get Plan Asset Trend for a PWM investment plan.
     *
     * GET /v5/earn/pwm/investment-plan/asset-trend
     *
     * @param string $planId Plan ID
     * @param array{startTime?:int, endTime?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmAssetTrend(string $planId, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/asset-trend',
            array_merge($options, ['planId' => $planId]));
    }

    /**
     * Claim Available Funds from a PWM investment plan.
     *
     * POST /v5/earn/pwm/investment-plan/claim
     *
     * @param string $planId Plan ID
     * @param string $orderLinkId User-defined order link ID
     * @param array{toAccountType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmClaim(string $planId, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/investment-plan/claim',
            array_merge($options, ['planId' => $planId, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Create Custom Investment Plan (Direct Mode) for PWM.
     *
     * POST /v5/earn/pwm/customize-plan/create
     *
     * @param array $products List of products to include in the plan
     * @param string $orderLinkId User-defined order link ID
     * @param array{accountType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmCreateCustomPlan(array $products, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/customize-plan/create',
            array_merge($options, ['products' => $products, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Get Fund Historical NAV for a PWM investment plan fund.
     *
     * GET /v5/earn/pwm/investment-plan/fund-nav
     *
     * @param string $fundId Fund ID
     * @param array{startTime?:int, endTime?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmFundNav(string $fundId, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/fund-nav',
            array_merge($options, ['fundId' => $fundId]));
    }

    /**
     * Transfer funds between PWM custody sub-accounts.
     *
     * POST /v5/earn/pwm/fund-transfer
     *
     * @param string $transferId Client-supplied transfer id
     * @param int $fromUserId Source user id
     * @param int $toUserId Destination user id
     * @param string $amount Transfer amount
     * @param string $coin Coin to transfer
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmFundTransfer(string $transferId, int $fromUserId, int $toUserId, string $amount, string $coin, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/fund-transfer',
            array_merge($options, ['transferId' => $transferId, 'fromUserId' => $fromUserId, 'toUserId' => $toUserId, 'amount' => $amount, 'coin' => $coin]));
    }

    /**
     * Get pending-subscription PWM investment plan detail.
     *
     * GET /v5/earn/pwm/investment-plan/new-plan
     *
     * @param string $planId Investment plan id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmGetNewPlanDetail(string $planId, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/new-plan',
            array_merge($options, ['planId' => $planId]));
    }

    /**
     * Get PWM investment plan detail (active or closed).
     *
     * GET /v5/earn/pwm/investment-plan/detail
     *
     * @param string $planId Investment plan id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmGetPlanDetail(string $planId, array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/detail',
            array_merge($options, ['planId' => $planId]));
    }

    /**
     * Create a pending-subscription fund under the PWM asset manager.
     *
     * POST /v5/earn/pwm/asset-manager/create-fund
     *
     * @param string $fundName Fund name
     * @param string $coin Underlying coin
     * @param string $profitShareRate Profit-share rate
     * @param string $managementFeeRate Management fee rate
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array{fundIntroduction?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstCreateFund(string $fundName, string $coin, string $profitShareRate, string $managementFeeRate, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/create-fund',
            array_merge($options, ['fundName' => $fundName, 'coin' => $coin, 'profitShareRate' => $profitShareRate, 'managementFeeRate' => $managementFeeRate, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Create an investment plan for a PWM client.
     *
     * POST /v5/earn/pwm/asset-manager/create-investment-plan
     *
     * @param string $accountUid Client account UID
     * @param string $planName Investment plan name
     * @param string $planType Investment plan type
     * @param array $investmentDistribution Fund distribution allocations for the plan
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstCreateInvestmentPlan(string $accountUid, string $planName, string $planType, array $investmentDistribution, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/create-investment-plan',
            array_merge($options, ['accountUid' => $accountUid, 'planName' => $planName, 'planType' => $planType, 'investmentDistribution' => $investmentDistribution, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Create a fund sub-account under the PWM asset manager.
     *
     * POST /v5/earn/pwm/asset-manager/create-sub-account
     *
     * @param string $fundId Fund identifier
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstCreateSubAccount(string $fundId, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/create-sub-account',
            array_merge($options, ['fundId' => $fundId, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Query the institution's investment plans under PWM asset manager.
     *
     * GET /v5/earn/pwm/asset-manager/get-investment-plan
     *
     * @param array{planId?:string, status?:string, subscriptionUid?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstGetInvestmentPlans(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/asset-manager/get-investment-plan', $options);
    }

    /**
     * Query the institution's managed funds under PWM asset manager.
     *
     * GET /v5/earn/pwm/asset-manager/all-funds
     *
     * @param array{fundId?:string, coin?:string, status?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstListFunds(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/asset-manager/all-funds', $options);
    }

    /**
     * Query fund subscription and redemption orders under PWM asset manager.
     *
     * GET /v5/earn/pwm/asset-manager/all-order
     *
     * @param array{fundId?:string, orderType?:string, status?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstListOrders(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/asset-manager/all-order', $options);
    }

    /**
     * Update investment plan status and/or its constituent funds.
     *
     * POST /v5/earn/pwm/asset-manager/manage-investment-plan
     *
     * @param string $planId Investment plan id
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array{updateStatus?:string, updateFunds?:array} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstManageInvestmentPlan(string $planId, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/manage-investment-plan',
            array_merge($options, ['planId' => $planId, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Approve or reject a PWM fund subscription/redemption order.
     *
     * POST /v5/earn/pwm/asset-manager/manage-order
     *
     * @param string $orderId Order id to manage
     * @param string $action Action to perform (approve/reject)
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstManageOrder(string $orderId, string $action, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/manage-order',
            array_merge($options, ['orderId' => $orderId, 'action' => $action, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Execute profit settlement for a PWM asset-manager fund.
     *
     * POST /v5/earn/pwm/asset-manager/settle-profit
     *
     * @param string $fundId Fund identifier
     * @param string $reqLinkId Client-supplied idempotency/request link id
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInstSettleProfit(string $fundId, string $reqLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/asset-manager/settle-profit',
            array_merge($options, ['fundId' => $fundId, 'reqLinkId' => $reqLinkId]));
    }

    /**
     * Invest More in an Active PWM investment plan.
     *
     * POST /v5/earn/pwm/investment-plan/invest-more
     *
     * @param string $planId Plan ID
     * @param string $category Product category
     * @param string $productId Product ID
     * @param string $amount Amount to invest
     * @param string $orderLinkId User-defined order link ID
     * @param array{accountType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmInvestMore(string $planId, string $category, string $productId, string $amount, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/investment-plan/invest-more',
            array_merge($options, ['planId' => $planId, 'category' => $category, 'productId' => $productId, 'amount' => $amount, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * List PWM investment plans.
     *
     * GET /v5/earn/pwm/investment-plan/all
     *
     * @param array{planId?:string, status?:string, limit?:int, cursor?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmListInvestmentPlans(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/all', $options);
    }

    /**
     * List Investment Plan Orders for PWM.
     *
     * GET /v5/earn/pwm/investment-plan/order
     *
     * @param array{planId?:string, category?:string, type?:string, status?:string, startTime?:int, endTime?:int, limit?:int, cursor?:string, orderLinkId?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmListOrder(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/investment-plan/order', $options);
    }

    /**
     * List Available Product Cards (Direct Mode) for PWM customize plan.
     *
     * GET /v5/earn/pwm/customize-plan/product
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmListProductCards(array $options = []): array
    {
        return $this->session->publicRequest('GET', '/v5/earn/pwm/customize-plan/product', $options);
    }

    /**
     * Query PWM fund transfer records.
     *
     * GET /v5/earn/pwm/query-fund-transfer-result
     *
     * @param array{transferId?:string, fromUserId?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmQueryFundTransferResult(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/earn/pwm/query-fund-transfer-result', $options);
    }

    /**
     * Redeem from a PWM Investment Plan.
     *
     * POST /v5/earn/pwm/investment-plan/redeem
     *
     * @param string $planId Plan ID
     * @param string $category Product category
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order link ID
     * @param array{shares?:string, amount?:string, positionId?:int} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmRedeem(string $planId, string $category, string $productId, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/investment-plan/redeem',
            array_merge($options, ['planId' => $planId, 'category' => $category, 'productId' => $productId, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * One-Click Subscribe to Pending Plan for PWM investment.
     *
     * POST /v5/earn/pwm/investment-plan/subscribe
     *
     * @param string $planId Plan ID
     * @param string $orderLinkId User-defined order link ID
     * @param array{accountType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function pwmSubscribe(string $planId, string $orderLinkId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/pwm/investment-plan/subscribe',
            array_merge($options, ['planId' => $planId, 'orderLinkId' => $orderLinkId]));
    }

    /**
     * Redeem a fixed term earn position.
     *
     * POST /v5/earn/fixed-term/redeem
     *
     * @param string $productId Product ID
     * @param string $category Product category
     * @param string $positionId Position ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function redeemFixedTerm(string $productId, string $category, string $positionId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/fixed-term/redeem',
            array_merge($options, ['productId' => $productId, 'category' => $category, 'positionId' => $positionId]));
    }

    /**
     * Reinvest accrued interest into a liquidity mining position.
     *
     * POST /v5/earn/liquidity-mining/reinvest
     *
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order link ID
     * @param string $positionId Position ID to reinvest into
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function reinvestLiquidity(string $productId, string $orderLinkId, string $positionId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/liquidity-mining/reinvest',
            array_merge($options, ['productId' => $productId, 'orderLinkId' => $orderLinkId, 'positionId' => $positionId]));
    }

    /**
     * Remove liquidity from a liquidity mining position.
     *
     * POST /v5/earn/liquidity-mining/remove-liquidity
     *
     * @param string $productId Product ID
     * @param string $orderLinkId User-defined order link ID
     * @param string $positionId Position ID to remove liquidity from
     * @param array{removeRate?:int, removeType?:string} $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function removeLiquidity(string $productId, string $orderLinkId, string $positionId, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/liquidity-mining/remove-liquidity',
            array_merge($options, ['productId' => $productId, 'orderLinkId' => $orderLinkId, 'positionId' => $positionId]));
    }

    /**
     * Enable or disable auto-invest on a fixed term earn position.
     *
     * POST /v5/earn/fixed-term/position/auto-invest
     *
     * @param string $productId Product ID
     * @param string $category Product category
     * @param string $positionId Position ID
     * @param string $status Auto-invest status
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function setFixedTermAutoInvest(string $productId, string $category, string $positionId, string $status, array $options = []): array
    {
        return $this->session->signRequest('POST', '/v5/earn/fixed-term/position/auto-invest',
            array_merge($options, ['productId' => $productId, 'category' => $category, 'positionId' => $positionId, 'status' => $status]));
    }
}

<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class PositionService extends BaseService
{
    /**
     * Manually add or reduce margin for an isolated margin position.
     *
     * POST /v5/position/add-margin
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $margin Add or reduce margin amount; positive to add, negative to reduce
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/manual-add-margin
     */
    public function addReduceMargin(string $category, string $symbol, string $margin, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/add-margin',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'margin' => $margin])
        );
    }

    /**
     * Confirm new risk limit to remove the reduce-only restriction.
     *
     * POST /v5/position/confirm-pending-mmr
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/confirm-mmr
     */
    public function confirmNewRiskLimit(string $category, string $symbol, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/confirm-pending-mmr',
            array_merge($options, ['category' => $category, 'symbol' => $symbol])
        );
    }

    /**
     * Get closed profit and loss records.
     *
     * GET /v5/position/closed-pnl
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/close-pnl
     */
    public function getClosedPnl(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/position/closed-pnl',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get closed option position records.
     *
     * GET /v5/position/get-closed-positions
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getClosePosition(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/position/get-closed-positions',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Get move position (block trade) history.
     *
     * GET /v5/position/move-history
     *
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/move-position-history
     */
    public function getMovePositionHistory(array $options = []): array
    {
        return $this->session->signRequest('GET', '/v5/position/move-history', $options);
    }

    /**
     * Get position info (real-time).
     *
     * GET /v5/position/list
     *
     * @param string $category Product type
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position
     */
    public function getInfo(string $category, array $options = []): array
    {
        return $this->session->signRequest(
            'GET',
            '/v5/position/list',
            array_merge($options, ['category' => $category])
        );
    }

    /**
     * Move positions between UIDs via block trade.
     *
     * POST /v5/position/move-positions
     *
     * @param string $fromUid Source UID
     * @param string $toUid Destination UID
     * @param array $list_ List of positions to move
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/move-position
     */
    public function movePosition(string $fromUid, string $toUid, array $list_, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/move-positions',
            array_merge($options, ['fromUid' => $fromUid, 'toUid' => $toUid, 'list' => $list_])
        );
    }

    /**
     * Enable or disable auto-add-margin for a position.
     *
     * POST /v5/position/set-auto-add-margin
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param int $autoAddMargin 0: off, 1: on
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/auto-add-margin
     */
    public function setAutoAddMargin(string $category, string $symbol, int $autoAddMargin, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/set-auto-add-margin',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'autoAddMargin' => $autoAddMargin])
        );
    }

    /**
     * Set leverage for a position.
     *
     * POST /v5/position/set-leverage
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $buyLeverage Buy leverage
     * @param string $sellLeverage Sell leverage
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/leverage
     */
    public function setLeverage(string $category, string $symbol, string $buyLeverage, string $sellLeverage, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/set-leverage',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'buyLeverage' => $buyLeverage, 'sellLeverage' => $sellLeverage])
        );
    }

    /**
     * Set take profit, stop loss, and trailing stop for a position.
     *
     * POST /v5/position/trading-stop
     *
     * @param string $category Product type
     * @param string $symbol Symbol name
     * @param string $tpslMode TP/SL mode: Full or Partial
     * @param int $positionIdx Position index (used in hedge mode)
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/trading-stop
     */
    public function setTradingStop(string $category, string $symbol, string $tpslMode, int $positionIdx, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/trading-stop',
            array_merge($options, ['category' => $category, 'symbol' => $symbol, 'tpslMode' => $tpslMode, 'positionIdx' => $positionIdx])
        );
    }

    /**
     * Switch position mode between one-way and hedge mode.
     *
     * POST /v5/position/switch-mode
     *
     * @param string $category Product type
     * @param int $mode 0: one-way, 3: hedge
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/position/position-mode
     */
    public function switchPositionMode(string $category, int $mode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/position/switch-mode',
            array_merge($options, ['category' => $category, 'mode' => $mode])
        );
    }
}

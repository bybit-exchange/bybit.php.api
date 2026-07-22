<?php

declare(strict_types=1);

namespace Bybit\RestApi;

final class BotService extends BaseService
{
    /**
     * Close a running DCA bot with a specified settlement mode.
     *
     * POST /v5/dca/close-bot
     *
     * @param int $botId DCA bot identifier
     * @param int $closeMode Settlement mode for closing the bot
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function closeDcaBot(int $botId, int $closeMode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/dca/close-bot',
            array_merge($options, ['bot_id' => $botId, 'close_mode' => $closeMode])
        );
    }

    /**
     * Create a new DCA (Dollar-Cost Averaging) bot with custom parameters.
     *
     * POST /v5/dca/create-bot
     *
     * @param array $parameters DCA bot configuration parameters
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createDcaBot(array $parameters, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/dca/create-bot',
            array_merge($options, ['parameters' => $parameters])
        );
    }

    /**
     * Close a running futures combo bot by bot ID.
     *
     * POST /v5/fcombobot/close
     *
     * @param int $botId Bot ID of the futures combo bot to close.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function closeComboBot(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fcombobot/close',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Create a new futures combo bot with multi-symbol portfolio and rebalancing.
     *
     * POST /v5/fcombobot/create
     *
     * @param string $leverage Leverage value for the combo bot.
     * @param string $initMargin Initial margin allocated to the bot.
     * @param int $adjustPositionMode Position adjustment mode for rebalancing.
     * @param array $symbolSettings List of symbol-level settings for the multi-symbol portfolio.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createComboBot(string $leverage, string $initMargin, int $adjustPositionMode, array $symbolSettings, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fcombobot/create',
            array_merge($options, ['leverage' => $leverage, 'init_margin' => $initMargin, 'adjust_position_mode' => $adjustPositionMode, 'symbol_settings' => $symbolSettings])
        );
    }

    /**
     * Get full details of a futures combo bot including PnL, positions, and status.
     *
     * POST /v5/fcombobot/detail
     *
     * @param int $botId Bot ID of the futures combo bot to query.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getComboDetail(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fcombobot/detail',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Validate combo bot input parameters and return allowable ranges.
     *
     * POST /v5/fcombobot/getlimit
     *
     * @param string $leverage Leverage value for the combo bot.
     * @param string $initMargin Initial margin allocated to the bot.
     * @param int $adjustPositionMode Position adjustment mode for rebalancing.
     * @param array $symbolSettings List of symbol-level settings for the multi-symbol portfolio.
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getComboLimit(string $leverage, string $initMargin, int $adjustPositionMode, array $symbolSettings, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fcombobot/getlimit',
            array_merge($options, ['leverage' => $leverage, 'init_margin' => $initMargin, 'adjust_position_mode' => $adjustPositionMode, 'symbol_settings' => $symbolSettings])
        );
    }

    /**
     * Close a running futures grid bot by bot ID.
     *
     * POST /v5/fgridbot/close
     *
     * @param int $botId Bot ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function closeFuturesGridBot(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fgridbot/close',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Create a new futures grid trading bot with specified parameters.
     *
     * POST /v5/fgridbot/create
     *
     * @param string $symbol Trading symbol
     * @param int $gridMode Grid mode
     * @param string $minPrice Minimum price of grid range
     * @param string $maxPrice Maximum price of grid range
     * @param int $cellNumber Number of grid cells
     * @param string $leverage Leverage
     * @param int $gridType Grid type
     * @param string $totalInvestment Total investment amount
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createFuturesGridBot(string $symbol, int $gridMode, string $minPrice, string $maxPrice, int $cellNumber, string $leverage, int $gridType, string $totalInvestment, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fgridbot/create',
            array_merge($options, ['symbol' => $symbol, 'grid_mode' => $gridMode, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'cell_number' => $cellNumber, 'leverage' => $leverage, 'grid_type' => $gridType, 'total_investment' => $totalInvestment])
        );
    }

    /**
     * Get full details of a futures grid bot including PnL, positions, and status.
     *
     * POST /v5/fgridbot/detail
     *
     * @param int $botId Bot ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFuturesGridDetail(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fgridbot/detail',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Validate futures grid bot input parameters and return allowable ranges.
     *
     * POST /v5/fgridbot/validate
     *
     * @param string $symbol Trading symbol
     * @param int $cellNumber Number of grid cells
     * @param string $minPrice Minimum price of grid range
     * @param string $maxPrice Maximum price of grid range
     * @param string $leverage Leverage
     * @param int $gridType Grid type
     * @param int $gridMode Grid mode
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function validateFuturesGridInput(string $symbol, int $cellNumber, string $minPrice, string $maxPrice, string $leverage, int $gridType, int $gridMode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fgridbot/validate',
            array_merge($options, ['symbol' => $symbol, 'cell_number' => $cellNumber, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'leverage' => $leverage, 'grid_type' => $gridType, 'grid_mode' => $gridMode])
        );
    }

    /**
     * Close a running futures Martingale bot by bot ID.
     *
     * POST /v5/fmartingalebot/close
     *
     * @param int $botId Bot ID to close
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function closeFuturesMartingaleBot(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fmartingalebot/close',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Create a new futures Martingale bot with DCA averaging strategy.
     *
     * POST /v5/fmartingalebot/create
     *
     * @param string $symbol Symbol
     * @param string $martingaleMode Martingale mode
     * @param string $leverage Leverage
     * @param string $priceFloatPercent Price float percent
     * @param string $addPositionPercent Add position percent
     * @param int $addPositionNum Number of add position rounds
     * @param string $initMargin Initial margin
     * @param string $roundTpPercent Round take-profit percent
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createFuturesMartingaleBot(string $symbol, string $martingaleMode, string $leverage, string $priceFloatPercent, string $addPositionPercent, int $addPositionNum, string $initMargin, string $roundTpPercent, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fmartingalebot/create',
            array_merge($options, ['symbol' => $symbol, 'martingale_mode' => $martingaleMode, 'leverage' => $leverage, 'price_float_percent' => $priceFloatPercent, 'add_position_percent' => $addPositionPercent, 'add_position_num' => $addPositionNum, 'init_margin' => $initMargin, 'round_tp_percent' => $roundTpPercent])
        );
    }

    /**
     * Get full details of a futures Martingale bot including PnL, positions, and round progress.
     *
     * POST /v5/fmartingalebot/detail
     *
     * @param int $botId Bot ID
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFuturesMartingaleDetail(int $botId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fmartingalebot/detail',
            array_merge($options, ['bot_id' => $botId])
        );
    }

    /**
     * Validate Martingale bot input parameters and return allowable ranges.
     *
     * POST /v5/fmartingalebot/getlimit
     *
     * @param string $symbol Symbol
     * @param string $martingaleMode Martingale mode
     * @param string $leverage Leverage
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function getFuturesMartingaleLimit(string $symbol, string $martingaleMode, string $leverage, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/fmartingalebot/getlimit',
            array_merge($options, ['symbol' => $symbol, 'martingale_mode' => $martingaleMode, 'leverage' => $leverage])
        );
    }

    /**
     * Close a running spot grid bot with a specified settlement mode.
     *
     * POST /v5/grid/close-grid
     *
     * @param int $gridId Grid bot identifier
     * @param int $closeMode Settlement mode used to close the grid bot
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function closeGridBot(int $gridId, int $closeMode, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/grid/close-grid',
            array_merge($options, ['grid_id' => $gridId, 'close_mode' => $closeMode])
        );
    }

    /**
     * Create a new spot grid trading bot.
     *
     * POST /v5/grid/create-grid
     *
     * @param string $symbol Trading pair symbol
     * @param string $maxPrice Upper bound price of the grid range
     * @param string $minPrice Lower bound price of the grid range
     * @param string $totalInvestment Total investment amount
     * @param int $cellNumber Number of grid cells
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function createGridBot(string $symbol, string $maxPrice, string $minPrice, string $totalInvestment, int $cellNumber, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/grid/create-grid',
            array_merge($options, ['symbol' => $symbol, 'max_price' => $maxPrice, 'min_price' => $minPrice, 'total_investment' => $totalInvestment, 'cell_number' => $cellNumber])
        );
    }

    /**
     * Query full details of a specific grid bot by grid_id.
     *
     * POST /v5/grid/query-grid-detail
     *
     * @param int $gridId Grid bot identifier
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function queryGridDetail(int $gridId, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/grid/query-grid-detail',
            array_merge($options, ['grid_id' => $gridId])
        );
    }

    /**
     * Validate spot grid bot parameters before creation.
     *
     * POST /v5/grid/validate-input
     *
     * @param string $symbol Trading pair symbol
     * @param int $cellNumber Number of grid cells
     * @param string $minPrice Lower bound price of the grid range
     * @param string $maxPrice Upper bound price of the grid range
     * @param string $totalInvestment Total investment amount
     * @param array $options
     * @return array Bybit V5 ApiResponse envelope (retCode / retMsg / result / retExtInfo / time).
     * @see https://bybit-exchange.github.io/docs/v5/intro
     */
    public function validateGridInput(string $symbol, int $cellNumber, string $minPrice, string $maxPrice, string $totalInvestment, array $options = []): array
    {
        return $this->session->signRequest(
            'POST',
            '/v5/grid/validate-input',
            array_merge($options, ['symbol' => $symbol, 'cell_number' => $cellNumber, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'total_investment' => $totalInvestment])
        );
    }
}

<?php

declare(strict_types=1);

namespace Bybit;

// gen-sdk-php:service-uses:start
use Bybit\RestApi\AccountService;
use Bybit\RestApi\AffiliateService;
use Bybit\RestApi\AssetService;
use Bybit\RestApi\BotService;
use Bybit\RestApi\BrokerService;
use Bybit\RestApi\CryptoLoanService;
use Bybit\RestApi\EarnService;
use Bybit\RestApi\MarketService;
use Bybit\RestApi\P2pService;
use Bybit\RestApi\PositionService;
use Bybit\RestApi\RfqService;
use Bybit\RestApi\SpotMarginService;
use Bybit\RestApi\TradeService;
use Bybit\RestApi\UserService;

// gen-sdk-php:service-uses:end

final class Client
{
    private Configuration $config;
    private Session $session;

    // gen-sdk-php:client-properties:start
    public readonly AccountService $account;
    public readonly AffiliateService $affiliate;
    public readonly AssetService $asset;
    public readonly BotService $bot;
    public readonly BrokerService $broker;
    public readonly CryptoLoanService $cryptoLoan;
    public readonly EarnService $earn;
    public readonly MarketService $market;
    public readonly P2pService $p2p;
    public readonly PositionService $position;
    public readonly RfqService $rfq;
    public readonly SpotMarginService $spotMargin;
    public readonly TradeService $trade;
    public readonly UserService $user;
    // gen-sdk-php:client-properties:end

    public function __construct(?Configuration $config = null)
    {
        $this->config = $config ?? new Configuration();
        $this->session = new Session($this->config);

        // gen-sdk-php:client-inits:start
        $this->account = new AccountService($this->session);
        $this->affiliate = new AffiliateService($this->session);
        $this->asset = new AssetService($this->session);
        $this->bot = new BotService($this->session);
        $this->broker = new BrokerService($this->session);
        $this->cryptoLoan = new CryptoLoanService($this->session);
        $this->earn = new EarnService($this->session);
        $this->market = new MarketService($this->session);
        $this->p2p = new P2pService($this->session);
        $this->position = new PositionService($this->session);
        $this->rfq = new RfqService($this->session);
        $this->spotMargin = new SpotMarginService($this->session);
        $this->trade = new TradeService($this->session);
        $this->user = new UserService($this->session);
        // gen-sdk-php:client-inits:end
    }

    public function __toString(): string
    {
        return sprintf(
            '<Bybit\\Client testnet=%s baseUrl=%s>',
            $this->config->testnet ? 'true' : 'false',
            $this->config->resolvedBaseUrl()
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'testnet' => $this->config->testnet,
            'baseUrl' => $this->config->resolvedBaseUrl(),
        ];
    }
}

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 0.1.0

Initial public release of the official Bybit V5 REST connector for PHP.

### Added
- Full Bybit V5 REST surface across 14 service classes (market, trade, position, account, asset, user, affiliate, broker, cryptoLoan, rfq, spotMargin, earn, p2p, bot) with ~240 typed endpoint methods.
- HMAC-SHA256 request signing (`X-BAPI-SIGN` / `X-BAPI-TIMESTAMP` / `X-BAPI-API-KEY` / `X-BAPI-RECV-WINDOW`) with a byte-identity invariant between the payload signed and the query string on the wire.
- Typed exception hierarchy rooted at `Bybit\Exception\BybitException`: transport-layer (`Timeout`, `Network`, `Server`, `Client`, `Parse`, `Transport`) and API-body (`Api`, `Auth`, `RateLimit`) branches. HTTP `401/403` route to `AuthException`, `429` to `RateLimitException`.
- `Bybit\Configuration` snapshot with credential redaction on every default PHP serialization pathway (`__toString`, `__debugInfo`, `jsonSerialize`); `baseUrl` / `testnet` / `timeout` / `httpClient` are readonly and set via constructor.
- Optional snake_case → camelCase key translation for callers via `Bybit\Util\WireKeys`, wired into every signed and public request.
- `Bybit\Client::raw()` escape hatch for endpoints not yet wrapped by a service class.
- Legacy P2P snake_case envelope (`ret_code` / `ret_msg` / `time_now` / `ext_info`) normalized to the V5 camelCase shape at the response boundary.
- Guzzle-based HTTP transport with `allow_redirects: false` to prevent `X-BAPI-*` header replay via WAF/proxy 302s.
- PHP 8.1 / 8.2 / 8.3 / 8.4 CI matrix, PHPStan level 6, PHP-CS-Fixer, PHPUnit strict mode (`failOnRisky` + `failOnWarning`), Composer archive dry-run, `gitleaks` secret scan.
- Runnable end-to-end example at `examples/quickstart.php`; full pagination + error-handling docs in `README.md`.

# API Review Fixes Design

## Goal

Make the unreleased endpoint additions match the public Bybit V5 contract and avoid publishing wrappers that are undocumented, obsolete, or unusable.

## Public API changes

- Remove `AssetService::queryOrderFromOpen()` because `/v5/asset/exchange/query-order-list` is absent from the public documentation and failed every testnet request attempted during review.
- Remove `AssetService::coinConvertLimitQuery()` and `AssetService::limitOrderCallback()` because their endpoints are absent from the public documentation. Although the convert-limit endpoint currently responds on testnet, an official SDK should not promise an unsupported public contract.
- Remove `AssetService::assetInfoQuery()` and `AssetService::transferSubMemberSave()` because their documentation lives under the official `abandon` section. They are new, unreleased additions here, so removal does not break a released PHP API.
- Change `AccountService::getTradeInfoForAnalysis()` to require `string $symbol` and merge it into the signed query.
- Change `SpotMarginService::queryFixedBorrowMarket()` to require only `string $orderCurrency`; callers may pass optional `orderBy` through `$options`.

## Documentation

- Remove deleted methods from the `0.1.1` changelog entry.
- Update every newly added `@see` URL to the canonical public documentation route rather than assuming the endpoint path is also the documentation path.
- Keep the version bump and all other endpoint additions unchanged.

## Tests

- Add focused request-contract tests that assert the corrected method signatures, HTTP methods, paths, and serialized parameters.
- Add an inventory regression test asserting that the five removed methods are not part of the public service API.
- Run the full PHPUnit suite, PHPStan, PHP CS Fixer dry-run, syntax checks, and `git diff --check`.
- Re-run the corrected read-only methods against testnet. Do not run asset-moving or account-setting POST endpoints.

## Compatibility and error handling

All affected methods are unreleased working-tree additions, so correcting or removing them before release is preferable to carrying compatibility shims. Existing exception and response behavior remains unchanged.

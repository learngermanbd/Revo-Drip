# Changelog

All notable changes to REVO DRIP Store Manager are documented here.
This project follows [Semantic Versioning](https://semver.org/).

## [1.0.0] — 2026-11-01

### Added

- Four field storefront checkout (Full Name, Phone Number, Email Address, Complete Delivery
  Address) with folded Inside/Outside Dhaka delivery area selection.
- Bangladesh mobile number validation rule accepting `01XXXXXXXXX`, `+8801XXXXXXXXX` and
  `8801XXXXXXXXX` formats, with formatting stripped before storage.
- Address mapping onto Bagisto's `addresses` structure without inventing city, state, postcode or
  district values.
- `revodrip` shipping carrier exposing one rate per delivery zone, priced server side from the
  extension settings.
- Idempotent order delivery records (`revo_drip_order_deliveries`) written from the verified
  `checkout.order.save.after` event.
- Admin menu, ACL entries, Dashboard, Checkout Manager, Delivery Manager and General Settings.
- Delivery panel on the admin order page.
- English and Bangla translations.
- `phpunit.revodrip.xml` standalone test configuration and unit tests for name splitting, phone
  validation, zone pricing and settings fallbacks.
- GitHub Actions release workflow that lints, packages and publishes the installable ZIP.

### Notes

- Checkout feature tests require a migrated store database and are gated behind
  `REVODRIP_FEATURE_TESTS=1`.
- No Bagisto core file is modified.

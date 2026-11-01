# REVO DRIP Store Manager

A standalone Bagisto 2.5 extension that replaces the storefront checkout address step with a
four field, mobile first form for Bangladesh, and charges delivery per zone (Inside Dhaka /
Outside Dhaka) through Bagisto's own shipping layer.

Nothing in Bagisto core is modified: the extension is a package, its storefront overrides are
resolved through the view namespace, and its admin screens are registered through the menu,
ACL and route extension points Bagisto already merges from packages.

## Features (v1.0.0)

- **Checkout Manager** — Full Name, Phone Number, Email Address and Complete Delivery Address,
  with Bangla aware validation and a fully server validated submission.
- **Delivery Manager** — Inside Dhaka / Outside Dhaka charges, editable in the admin, applied
  as real Bagisto shipping rates so cart totals, orders, invoices and emails stay consistent.
- **Order delivery record** — zone, charge, address, phone and customer name stored once per
  order (idempotent, safe against Bagisto's order creation retry loop).
- **Admin** — REVO DRIP Store Manager menu with Dashboard, Checkout Manager, Delivery Manager
  and General Settings, protected by ACL permissions.
- **Translations** — English and Bangla.

## Compatibility

| Requirement | Version |
|---|---|
| Bagisto | 2.5.x |
| Laravel | 13.x |
| PHP | 8.4 |
| Database | MySQL 8 / MariaDB 10.11 (PostgreSQL supported by Bagisto) |

Verified against Bagisto `2.5.x-dev` with `laravel/framework v13.36.0`.

## Installation

See [INSTALL.md](INSTALL.md). Upgrade steps live in [UPDATING.md](UPDATING.md).

## How it works

1. The storefront address step is replaced by `Resources/views/shop/checkout/onepage/address.blade.php`,
   resolved through `View::prependNamespace('shop', ...)`.
2. The form posts to `revodrip/checkout/address`. The controller validates the four fields,
   maps them onto Bagisto's address structure and calls Bagisto's own `Cart::saveAddresses()`.
3. The chosen zone is stored with `Cart::saveShippingMethod('revodrip_<zone>')`. Bagisto
   re-collects rates and refuses any method whose price it did not compute, so the browser can
   never set the delivery price.
4. Bagisto's own order endpoint creates the order. The `checkout.order.save.after` listener
   writes the delivery snapshot into `revo_drip_order_deliveries`.
5. Returning shoppers get the cart's own zone back (`shipping_method = revodrip_*`) rather than
   the package default, and the address is restored from `billing_address` with its line breaks
   intact.

### Address mapping

Bagisto stores `first_name`, `last_name` and `city` as non nullable columns. The extension:

- splits Full Name at the last space (`last_name` stays empty for single word names),
- keeps the complete address verbatim in the multiline `address` column,
- sets `city` to an empty string, `state`/`postcode` to `null` and `country` to `BD`,
- never invents a district, city or postcode.

### Email behaviour

Guests use the email they type. For signed in customers the field is filled from the account and
then read only, with a short note explaining that order updates go to the account email. The
sender is the account, never the typed value, so order, invoice and account stay consistent.
Bagisto keeps the **account** email as the order contact (`Cart::setCustomerPersonnelDetails()`);
the extension defends this server side as well. Account email, verification, password recovery and
Social Login are never written to.

## Settings

Stored in `revo_drip_settings` and read through `SettingsService` with package defaults as a
fallback, so the storefront keeps working if the table or the admin settings are unavailable.

## Tests

```bash
/opt/alt/php84/usr/bin/php vendor/bin/pest -c phpunit.revodrip.xml
```

Unit tests cover name splitting, phone validation, zone pricing and settings fallbacks. Checkout
feature tests are gated behind `REVODRIP_FEATURE_TESTS=1` because they need a migrated store
database.

## Uninstall

Remove the package folder, its registration lines and the two tables (or keep them for history):

```sql
DROP TABLE IF EXISTS revo_drip_order_deliveries;
DROP TABLE IF EXISTS revo_drip_settings;
```

## Licence

MIT. See [LICENSE](LICENSE).

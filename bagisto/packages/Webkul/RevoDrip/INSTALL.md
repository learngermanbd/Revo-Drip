# Installation — REVO DRIP Store Manager v1.0.0

Target: cPanel shared hosting, Bagisto 2.5, PHP 8.4 (CLI path `/opt/alt/php84/usr/bin/php`).

> The storefront changed. Take a database and file backup before starting.

## 1. Download the release

Download `revo-drip-store-manager-1.0.0.zip` from the GitHub Release page.

## 2. Upload and unpack

Using cPanel File Manager or SSH:

```bash
cd ~/public_html
unzip revo-drip-store-manager-1.0.0.zip -d bagisto/packages/Webkul
mv bagisto/packages/Webkul/revo-drip-store-manager bagisto/packages/Webkul/RevoDrip
```

The folder must end up as `bagisto/packages/Webkul/RevoDrip/composer.json`.

## 3. Register the package

Three small registrations — these are Bagisto's documented app level extension points, not core
edits:

1. `bagisto/composer.json` → add to `autoload.psr-4`:

   ```json
   "Webkul\\RevoDrip\\": "packages/Webkul/RevoDrip/src",
   ```

2. `bagisto/bootstrap/providers.php` → add the import and the main provider:

   ```php
   use Webkul\RevoDrip\Providers\RevoDripServiceProvider;
   ```

   ```php
   RevoDripServiceProvider::class,
   ```

3. `bagisto/config/concord.php` → add the module provider under `modules` (this is what
   registers the two models; do not also add it to `bootstrap/providers.php`):

   ```php
   Webkul\RevoDrip\Providers\ModuleServiceProvider::class,
   ```

## 4. Install the package

```bash
PHP=/opt/alt/php84/usr/bin/php
cd ~/public_html/bagisto

$PHP -d memory_limit=512M /usr/local/bin/composer dump-autoload
$PHP artisan vendor:publish --tag=revodrip-config --force   # optional
$PHP artisan migrate --force
$PHP artisan optimize:clear
```

`migrate` creates:

- `revo_drip_settings` (seeded with the default labels and the ৳60 / ৳120 placeholders),
- `revo_drip_order_deliveries` (one row per order, unique `order_id`).

## 5. Verify

1. Log in to the admin → the sidebar shows **REVO DRIP Store Manager**.
2. Dashboard → Checkout Manager → Delivery Manager → General Settings all load.
3. Give the new permissions to the roles that need them (Settings → Roles).
4. Storefront: add a product, open checkout, confirm the four fields + delivery area appear.
5. Switch between Inside/Outside Dhaka — the charge changes.
6. Place a test order (Cash on Delivery is fine) and confirm:
   - the order is created in Sales → Orders,
   - the delivery charge equals the zone charge,
   - the order page shows the delivery panel with zone, charge, phone and address.
7. Confirm the checkout works as a guest **and** as a registered customer.

If anything fails, see [UPDATING.md](UPDATING.md) for the rollback steps.

## 6. Notes for the hosting environment

- Always call PHP 8.4 explicitly: `/opt/alt/php84/usr/bin/php`. The default `php` binary on the
  server may be a different version.
- If `composer` is not on the server, run `composer dump-autoload` locally and upload
  `vendor/composer/autoload_*.php`.
- Blade overrides are cached: after replacing package files always run `artisan optimize:clear`
  (or delete the contents of `bagisto/storage/framework/views`).
- The extension never writes to Bagisto core tables. Both of its tables can be dropped to
  uninstall without touching orders.

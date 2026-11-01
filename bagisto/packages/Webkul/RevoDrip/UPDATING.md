# Updating — REVO DRIP Store Manager

Settings live in `revo_drip_settings` and delivery records in `revo_drip_order_deliveries`;
neither is touched by an update, so admin configuration and historical order data survive.

## Standard update

```bash
PHP=/opt/alt/php84/usr/bin/php
cd ~/public_html

cp -r bagisto/packages/Webkul/RevoDrip bagisto/packages/Webkul/RevoDrip.bak
unzip revo-drip-store-manager-X.Y.Z.zip -d /tmp/revodrip
rsync -a --delete /tmp/revodrip/revo-drip-store-manager/ bagisto/packages/Webkul/RevoDrip/

cd bagisto
$PHP -d memory_limit=512M /usr/local/bin/composer dump-autoload
$PHP artisan migrate --force
$PHP artisan optimize:clear
```

Then re-run the verification list in [INSTALL.md](INSTALL.md) step 5.

## Version notes

- **1.0.x** — bug fixes only. No database changes expected; `migrate` is a no-op.
- **1.1.x** — new settings arrive as new rows with defaults, existing values are kept.
- **2.x** — may rename settings keys; read the release notes before updating.

## Rollback

```bash
cd ~/public_html
rm -rf bagisto/packages/Webkul/RevoDrip
mv bagisto/packages/Webkul/RevoDrip.bak bagisto/packages/Webkul/RevoDrip
```

Remove the three registration lines only if you are uninstalling completely, then:

```bash
cd bagisto
$PHP -d memory_limit=512M /usr/local/bin/composer dump-autoload
$PHP artisan optimize:clear
```

Neither the settings table nor the delivery records need to be dropped for a rollback. To delete
them deliberately:

```sql
DROP TABLE IF EXISTS revo_drip_order_deliveries;
DROP TABLE IF EXISTS revo_drip_settings;
```

## After every update

1. `artisan optimize:clear` (Blade override cache).
2. Confirm the admin menu and the four screens load.
3. Place one test order to confirm the delivery charge and the admin delivery panel.

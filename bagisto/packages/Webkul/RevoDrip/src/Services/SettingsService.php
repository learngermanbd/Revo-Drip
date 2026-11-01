<?php

namespace Webkul\RevoDrip\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SettingsService
{
    /**
     * Cache key holding the stored settings map.
     */
    protected const CACHE_KEY = 'revodrip.settings';

    /**
     * Cache key holding only the simplified checkout flag, read during boot without a query.
     */
    public const FLAG_CHECKOUT = 'revodrip.flag.checkout';

    /**
     * Seconds the stored settings stay cached.
     */
    protected const CACHE_TTL = 600;

    /**
     * Settings resolved for the current request.
     *
     * @var array|null
     */
    protected ?array $resolved = null;

    /**
     * Whether the simplified checkout should replace the storefront address step.
     *
     * Read from the cache so the service provider does not touch the database on boot; the
     * flag is refreshed whenever the settings are loaded or saved.
     */
    public static function checkoutEnabled(): bool
    {
        try {
            return (bool) Cache::get(self::FLAG_CHECKOUT, config('revodrip.checkout.enabled', true));
        } catch (Throwable $e) {
            return (bool) config('revodrip.checkout.enabled', true);
        }
    }

    /**
     * Every setting, with package defaults filling the gaps.
     */
    public function all(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $this->resolved = array_replace_recursive($this->defaults(), Arr::undot($this->stored()));

        return $this->resolved;
    }

    /**
     * Read one setting using dot notation.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $value = Arr::get($this->all(), $key);

        if ($value === null) {
            return $default ?? Arr::get($this->defaults(), $key);
        }

        return $value;
    }

    /**
     * Persist a flat map of settings.
     */
    public function setMany(array $values): void
    {
        if (empty($values)) {
            return;
        }

        $now = now();

        $rows = [];

        foreach ($values as $key => $value) {
            $rows[] = [
                'key'        => (string) $key,
                'value'      => is_array($value) ? json_encode($value) : (string) $value,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::transaction(function () use ($rows) {
            DB::table('revo_drip_settings')->upsert($rows, ['key'], ['value', 'updated_at']);
        });

        $this->forget();

        /**
         * Re-read once so the boot flag reflects what was just saved.
         */
        $this->get('checkout.enabled', true);
    }

    /**
     * Drop the cached settings.
     */
    public function forget(): void
    {
        $this->resolved = null;

        try {
            Cache::forget(self::CACHE_KEY);
        } catch (Throwable $e) {
            Log::warning('RevoDrip: unable to clear the settings cache. '.$e->getMessage());
        }
    }

    /**
     * Package defaults for every known setting.
     */
    public function defaults(): array
    {
        return (array) config('revodrip', []);
    }

    /**
     * Stored settings, or nothing when the table is not available yet.
     */
    protected function stored(): array
    {
        try {
            return Cache::remember(
                self::CACHE_KEY,
                self::CACHE_TTL,
                function () {
                    $stored = DB::table('revo_drip_settings')->pluck('value', 'key')->all();

                    Cache::forever(
                        self::FLAG_CHECKOUT,
                        (bool) Arr::get(Arr::undot($stored), 'checkout.enabled', config('revodrip.checkout.enabled', true))
                    );

                    return $stored;
                }
            );
        } catch (Throwable $e) {
            Log::warning('RevoDrip: settings table unavailable, using defaults. '.$e->getMessage());

            return [];
        }
    }
}

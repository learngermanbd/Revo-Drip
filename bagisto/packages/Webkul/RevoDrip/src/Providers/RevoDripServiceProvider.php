<?php

namespace Webkul\RevoDrip\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Throwable;
use Webkul\RevoDrip\Listeners\RecordOrderDelivery;
use Webkul\RevoDrip\Services\SettingsService;
use Webkul\Theme\ViewRenderEventManager;

class RevoDripServiceProvider extends ServiceProvider
{
    /**
     * Register the package configuration and admin extension points.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/Config/revodrip.php', 'revodrip');

        $this->mergeAdminConfig('menu.admin', 'menu.php');

        $this->mergeAdminConfig('acl', 'acl.php');
    }

    /**
     * Bootstrap the package services.
     */
    public function boot(): void
    {
        $this->registerCarrier();

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'revodrip');

        $this->loadViewsFrom(__DIR__.'/../Resources/views/admin', 'revodrip-admin');

        $this->prependStorefrontViews();

        $this->registerRoutes();

        Event::listen('checkout.order.save.after', RecordOrderDelivery::class);

        $this->registerAdminOrderPanel();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Config/revodrip.php' => config_path('revodrip.php'),
            ], 'revodrip-config');
        }
    }

    /**
     * Register the delivery zone carrier at runtime so it survives `config:cache`.
     */
    protected function registerCarrier(): void
    {
        $carriers = config('carriers', []);

        $carriers['revodrip'] = require __DIR__.'/../Config/carriers.php';

        config(['carriers' => $carriers]);
    }

    /**
     * Merge package entries into an existing Bagisto configuration array.
     */
    protected function mergeAdminConfig(string $configKey, string $file): void
    {
        $existing = config($configKey, []);

        $package = require __DIR__.'/../Config/'.$file;

        config([$configKey => array_merge($existing, $package)]);
    }

    /**
     * Let the package override selected storefront views without touching the Shop package.
     */
    protected function prependStorefrontViews(): void
    {
        try {
            $enabled = (bool) $this->app->make(SettingsService::class)->get('checkout.enabled', true);
        } catch (Throwable $e) {
            $enabled = true;
        }

        if (! $enabled) {
            return;
        }

        $this->app['view']->prependNamespace('shop', __DIR__.'/../Resources/views/shop');
    }

    /**
     * Add the delivery panel to the admin order view.
     */
    protected function registerAdminOrderPanel(): void
    {
        Event::listen('bagisto.admin.sales.order.left_component.before', static function (ViewRenderEventManager $manager) {
            $manager->addTemplate('revodrip-admin::partials.order-delivery');
        });
    }

    /**
     * Register the storefront and admin routes.
     */
    protected function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['web', 'shop'])
            ->prefix('revodrip')
            ->group(__DIR__.'/../Routes/shop.php');

        Route::middleware(['admin'])
            ->prefix(config('app.admin_url'))
            ->group(__DIR__.'/../Routes/admin.php');
    }
}

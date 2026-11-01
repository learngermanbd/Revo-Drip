<?php

namespace Webkul\RevoDrip\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\RevoDrip\Models\OrderDelivery;
use Webkul\RevoDrip\Models\Setting;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Models registered with Concord.
     *
     * @var array
     */
    protected $models = [
        Setting::class,
        OrderDelivery::class,
    ];
}

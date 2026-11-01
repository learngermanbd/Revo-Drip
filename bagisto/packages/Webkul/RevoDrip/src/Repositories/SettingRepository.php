<?php

namespace Webkul\RevoDrip\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\RevoDrip\Contracts\Setting as SettingContract;

class SettingRepository extends Repository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return SettingContract::class;
    }
}

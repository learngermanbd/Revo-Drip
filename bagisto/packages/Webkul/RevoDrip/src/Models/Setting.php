<?php

namespace Webkul\RevoDrip\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RevoDrip\Contracts\Setting as SettingContract;

class Setting extends Model implements SettingContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'revo_drip_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}

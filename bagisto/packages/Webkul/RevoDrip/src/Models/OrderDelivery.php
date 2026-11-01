<?php

namespace Webkul\RevoDrip\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RevoDrip\Contracts\OrderDelivery as OrderDeliveryContract;

class OrderDelivery extends Model implements OrderDeliveryContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'revo_drip_order_deliveries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'delivery_charge' => 'float',
    ];
}

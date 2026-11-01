<?php

use Webkul\RevoDrip\Shipping\Carriers\DeliveryZone;

return [
    'revodrip' => [
        'code'        => 'revodrip',
        'title'       => 'revodrip::app.delivery.carrier-title',
        'description' => 'revodrip::app.delivery.carrier-description',
        'active'      => true,
        'class'       => DeliveryZone::class,
    ],
];

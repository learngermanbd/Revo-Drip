<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Checkout Manager
    |--------------------------------------------------------------------------
    |
    | Labels, placeholders and helper text of the four customer fields. The
    | four fields are always required: order creation and delivery depend on
    | them, so they are not switchable off.
    |
    */
    'checkout' => [
        'enabled'              => true,
        'show_delivery_zone'   => true,
        'require_address_note' => false,

        'fields' => [
            'name' => [
                'label'       => 'revodrip::app.checkout.fields.name',
                'placeholder' => 'revodrip::app.checkout.placeholders.name',
                'help'        => '',
            ],

            'phone' => [
                'label'       => 'revodrip::app.checkout.fields.phone',
                'placeholder' => 'revodrip::app.checkout.placeholders.phone',
                'help'        => 'revodrip::app.checkout.help.phone',
            ],

            'email' => [
                'label'       => 'revodrip::app.checkout.fields.email',
                'placeholder' => 'revodrip::app.checkout.placeholders.email',
                'help'        => '',
            ],

            'address' => [
                'label'       => 'revodrip::app.checkout.fields.address',
                'placeholder' => 'revodrip::app.checkout.placeholders.address',
                'help'        => 'revodrip::app.checkout.help.address',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Delivery Manager
    |--------------------------------------------------------------------------
    |
    | Delivery charges are stored in the extension settings table. These values
    | are only the defaults seeded on install.
    |
    */
    'delivery' => [
        'enabled'      => true,
        'default_zone' => 'inside_dhaka',

        'zones' => [
            'inside_dhaka' => [
                'label'  => 'revodrip::app.delivery.zones.inside_dhaka',
                'charge' => 60,
            ],

            'outside_dhaka' => [
                'label'  => 'revodrip::app.delivery.zones.outside_dhaka',
                'charge' => 120,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */
    'general' => [
        'store_phone'    => '',
        'delivery_notes' => 'revodrip::app.general.default-notes',
    ],
];

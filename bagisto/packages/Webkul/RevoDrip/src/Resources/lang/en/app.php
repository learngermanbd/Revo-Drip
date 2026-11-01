<?php

return [
    'checkout' => [
        'continue'         => 'Continue to payment',
        'processing'       => 'Please wait...',
        'error'            => 'Something went wrong. Please review your details and try again.',
        'delivery-area'    => 'Delivery Area',
        'delivery-charge'  => 'Delivery Charge',

        'fields' => [
            'name'    => 'Full Name',
            'phone'   => 'Phone Number',
            'email'   => 'Email Address',
            'address' => 'Complete Delivery Address',
        ],

        'placeholders' => [
            'name'    => 'e.g. Md. Rakib Hasan',
            'phone'   => 'e.g. 01712345678',
            'email'   => 'e.g. you@example.com',
            'address' => 'House, road, area, district and any delivery instruction',
        ],

        'help' => [
            'phone'         => 'Bangladeshi mobile number, for example 01712345678.',
            'address'       => 'Write the complete address in one place so the courier can find you.',
            'account-email' => 'Order updates will be sent to your account email.',
        ],

        'validation' => [
            'phone' => 'Please enter a valid Bangladeshi mobile number.',
            'zone'  => 'Please select a delivery area.',
        ],
    ],

    'delivery' => [
        'carrier-title'       => 'Delivery Area',
        'carrier-description' => 'Delivery charge for your area',
        'selected-at-address' => 'Selected in the delivery address step',

        'zones' => [
            'inside_dhaka'  => 'Inside Dhaka',
            'outside_dhaka' => 'Outside Dhaka',
        ],
    ],

    'general' => [
        'default-notes' => 'Please keep your phone reachable for the delivery call.',
    ],

    'admin' => [
        'menu' => [
            'title'     => 'REVO DRIP Store Manager',
            'dashboard' => 'Dashboard',
            'checkout'  => 'Checkout Manager',
            'delivery'  => 'Delivery Manager',
            'general'   => 'General Settings',
        ],

        'acl' => [
            'title'     => 'REVO DRIP Store Manager',
            'dashboard' => 'Dashboard',
            'checkout'  => 'Checkout Manager',
            'delivery'  => 'Delivery Manager',
            'general'   => 'General Settings',
        ],

        'common' => [
            'save'     => 'Save Changes',
            'enabled'  => 'Enabled',
            'disabled' => 'Disabled',
        ],

        'dashboard' => [
            'title'        => 'REVO DRIP Store Manager',
            'subtitle'     => 'Delivery overview for your store.',
            'checkout-on'  => 'Simplified checkout on',
            'checkout-off' => 'Simplified checkout off',
            'total-orders' => 'Delivery records',
            'inside-dhaka' => 'Inside Dhaka',
            'outside-dhaka' => 'Outside Dhaka',
            'collected'    => 'Delivery charges',
            'zones'        => 'Delivery zones',
            'zones-help'   => 'Charges are calculated on the server for every order. Edit them in Delivery Manager.',
        ],

        'checkout' => [
            'title'            => 'Checkout Manager',
            'subtitle'         => 'Control the four field checkout your customers see.',
            'behaviour'        => 'Checkout behaviour',
            'enable'           => 'Simplified checkout',
            'enable-info'      => 'Hide the default address form and use the four field checkout.',
            'show-zone'        => 'Delivery area selection',
            'show-zone-info'   => 'Show the Inside Dhaka and Outside Dhaka options under the address fields.',
            'fields'           => 'Customer fields',
            'fields-info'      => 'Rename the fields and their placeholders. The four fields are always required.',
            'field-label'      => ':field label',
            'field-placeholder' => 'Placeholder',
            'field-name'       => 'Full Name',
            'field-phone'      => 'Phone Number',
            'field-email'      => 'Email Address',
            'field-address'    => 'Complete Delivery Address',
            'required-note'    => 'Full Name, Phone Number, Email Address and Complete Delivery Address are required for every order and cannot be removed.',
            'saved'            => 'Checkout settings saved.',
        ],

        'delivery' => [
            'title'        => 'Delivery Manager',
            'subtitle'     => 'Delivery charges for Inside Dhaka and Outside Dhaka.',
            'enable'       => 'Delivery zones',
            'enable-info'  => 'Switch delivery zone selection on or off.',
            'default'      => 'Default delivery area',
            'zone'         => 'Delivery area',
            'charge'       => 'Charge',
            'method'       => 'Shipping method',
            'default'      => 'Default',
            'zone-label'   => 'Label shown to customers',
            'charge-bdt'   => 'Delivery charge (BDT)',
            'charge-info'  => 'Calculated on the server. Customers cannot change this amount.',
            'saved'        => 'Delivery settings saved.',
            'invalid-zone'   => 'Please choose a valid delivery area.',
            'disable-blocked' => 'Delivery zones cannot be switched off while no other shipping method is available for stockable products.',
        ],

        'general' => [
            'title'              => 'General Settings',
            'subtitle'           => 'Store level details used by the extension.',
            'store-phone'        => 'Store phone number',
            'store-phone-info'   => 'Shown to customers when they need to contact you.',
            'delivery-notes'     => 'Delivery note',
            'delivery-notes-info' => 'Shown under the delivery area selection at checkout.',
            'saved'              => 'General settings saved.',
        ],

        'order' => [
            'title'   => 'Delivery details',
            'zone'    => 'Delivery area',
            'charge'  => 'Delivery charge',
            'phone'   => 'Phone',
            'name'    => 'Customer',
            'address' => 'Address',
            'empty'   => 'No delivery details stored for this order.',
        ],
    ],
];

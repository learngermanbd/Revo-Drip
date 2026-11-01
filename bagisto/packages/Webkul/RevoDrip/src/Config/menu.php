<?php

return [
    [
        'key'  => 'revodrip',
        'name' => 'revodrip::app.admin.menu.title',
        'route' => 'revodrip.admin.dashboard.index',
        'sort' => 9,
        'icon' => 'settings/store.svg',
    ], [
        'key'  => 'revodrip.dashboard',
        'name' => 'revodrip::app.admin.menu.dashboard',
        'route' => 'revodrip.admin.dashboard.index',
        'sort' => 1,
        'icon' => '',
    ], [
        'key'  => 'revodrip.checkout',
        'name' => 'revodrip::app.admin.menu.checkout',
        'route' => 'revodrip.admin.checkout.edit',
        'sort' => 2,
        'icon' => '',
    ], [
        'key'  => 'revodrip.delivery',
        'name' => 'revodrip::app.admin.menu.delivery',
        'route' => 'revodrip.admin.delivery.edit',
        'sort' => 3,
        'icon' => '',
    ], [
        'key'  => 'revodrip.general',
        'name' => 'revodrip::app.admin.menu.general',
        'route' => 'revodrip.admin.general.edit',
        'sort' => 4,
        'icon' => '',
    ],
];

<?php

return [
    [
        'key'   => 'revodrip',
        'name'  => 'revodrip::app.admin.acl.title',
        'route' => 'revodrip.admin.dashboard.index',
        'sort'  => 9,
    ], [
        'key'   => 'revodrip.dashboard',
        'name'  => 'revodrip::app.admin.acl.dashboard',
        'route' => 'revodrip.admin.dashboard.index',
        'sort'  => 1,
    ], [
        'key'   => 'revodrip.checkout',
        'name'  => 'revodrip::app.admin.acl.checkout',
        'route' => [
            'revodrip.admin.checkout.edit',
            'revodrip.admin.checkout.update',
        ],
        'sort' => 2,
    ], [
        'key'   => 'revodrip.delivery',
        'name'  => 'revodrip::app.admin.acl.delivery',
        'route' => [
            'revodrip.admin.delivery.edit',
            'revodrip.admin.delivery.update',
        ],
        'sort' => 3,
    ], [
        'key'   => 'revodrip.general',
        'name'  => 'revodrip::app.admin.acl.general',
        'route' => [
            'revodrip.admin.general.edit',
            'revodrip.admin.general.update',
        ],
        'sort' => 4,
    ],
];

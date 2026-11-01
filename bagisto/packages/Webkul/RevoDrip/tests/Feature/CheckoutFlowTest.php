<?php

/**
 * Checkout flow feature tests need a migrated Bagisto database and the storefront
 * channels seeded. They run only when REVODRIP_FEATURE_TESTS=1 so the package test
 * suite stays usable on a machine without the application database.
 */
beforeEach(function () {
    if (! env('REVODRIP_FEATURE_TESTS')) {
        $this->markTestSkipped('Set REVODRIP_FEATURE_TESTS=1 with a prepared Bagisto test database to run checkout feature tests.');
    }
});

test('the package routes are registered', function () {
    expect(route('revodrip.checkout.address'))->toContain('revodrip/checkout/address')
        ->and(route('revodrip.checkout.delivery-zone'))->toContain('revodrip/checkout/delivery-zone');
});

test('the delivery carrier exposes one rate per zone', function () {
    $carrier = app(\Webkul\RevoDrip\Shipping\Carriers\DeliveryZone::class);

    $rates = $carrier->calculate();

    expect($rates)->toBeArray()->not->toBeEmpty();

    $methods = array_map(fn ($rate) => $rate->method, $rates);

    expect($methods)->toContain('revodrip_inside_dhaka')
        ->and($methods)->toContain('revodrip_outside_dhaka');
});

test('the order delivery listener is attached to the order creation event', function () {
    $listeners = app('events')->getListeners('checkout.order.save.after');

    expect($listeners)->not->toBeEmpty();

    $attached = collect($listeners)->contains(function ($listener) {
        return is_string($listener)
            ? str_contains($listener, 'RecordOrderDelivery')
            : (is_object($listener) && str_contains($listener::class, 'RecordOrderDelivery'));
    });

    expect($attached)->toBeTrue();
});

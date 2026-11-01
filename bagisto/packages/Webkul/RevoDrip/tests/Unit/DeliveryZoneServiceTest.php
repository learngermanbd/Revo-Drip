<?php

use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\RevoDrip\Services\SettingsService;

/**
 * Settings service reading from an in memory map instead of the database.
 */
function revoDripSettings(array $values): SettingsService
{
    return new class($values) extends SettingsService
    {
        public function __construct(protected array $values) {}

        public function all(): array
        {
            return $this->values;
        }

        public function get(string $key, mixed $default = null)
        {
            return data_get($this->values, $key, $default);
        }

        public function defaults(): array
        {
            return $this->values;
        }

        protected function stored(): array
        {
            return [];
        }
    };
}

test('zone charges come from settings and never from the request', function () {
    $zones = new DeliveryZoneService(revoDripSettings([
        'delivery' => [
            'enabled'      => '1',
            'default_zone' => 'inside_dhaka',
            'zones'        => [
                'inside_dhaka'  => ['label' => 'Inside Dhaka', 'charge' => '60'],
                'outside_dhaka' => ['label' => 'Outside Dhaka', 'charge' => '150'],
            ],
        ],
    ]));

    expect($zones->charge('inside_dhaka'))->toBe(60.0)
        ->and($zones->charge('outside_dhaka'))->toBe(150.0)
        ->and($zones->charge('unknown_zone'))->toBe(0.0);
});

test('negative charges fall back to zero', function () {
    $zones = new DeliveryZoneService(revoDripSettings([
        'delivery' => [
            'zones' => [
                'inside_dhaka' => ['label' => 'Inside Dhaka', 'charge' => '-40'],
            ],
        ],
    ]));

    expect($zones->charge('inside_dhaka'))->toBe(0.0);
});

test('zone codes and methods are mapped both ways', function () {
    $zones = new DeliveryZoneService(revoDripSettings([
        'delivery' => [
            'zones' => [
                'inside_dhaka'  => ['label' => 'Inside Dhaka', 'charge' => '60'],
                'outside_dhaka' => ['label' => 'Outside Dhaka', 'charge' => '120'],
            ],
        ],
    ]));

    expect($zones->method('inside_dhaka'))->toBe('revodrip_inside_dhaka')
        ->and($zones->zoneForMethod('revodrip_outside_dhaka'))->toBe('outside_dhaka')
        ->and($zones->zoneForMethod('flatrate_flatrate'))->toBeNull()
        ->and($zones->zoneForMethod(null))->toBeNull()
        ->and($zones->exists('inside_dhaka'))->toBeTrue()
        ->and($zones->exists('nowhere'))->toBeFalse();
});

test('an unknown default zone falls back to the first configured zone', function () {
    $zones = new DeliveryZoneService(revoDripSettings([
        'delivery' => [
            'default_zone' => 'deleted_zone',
            'zones'        => [
                'outside_dhaka' => ['label' => 'Outside Dhaka', 'charge' => '120'],
            ],
        ],
    ]));

    expect($zones->defaultZone())->toBe('outside_dhaka');
});

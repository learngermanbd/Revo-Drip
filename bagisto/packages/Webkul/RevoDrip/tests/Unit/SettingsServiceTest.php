<?php

use Webkul\RevoDrip\Services\SettingsService;

test('package defaults are used when nothing is stored', function () {
    $settings = new class extends SettingsService
    {
        public function defaults(): array
        {
            return [
                'delivery' => [
                    'enabled' => true,
                    'zones'   => [
                        'inside_dhaka' => ['label' => 'Inside Dhaka', 'charge' => 60],
                    ],
                ],
            ];
        }

        protected function stored(): array
        {
            return [];
        }
    };

    expect($settings->get('delivery.enabled'))->toBeTrue()
        ->and($settings->get('delivery.zones.inside_dhaka.charge'))->toBe(60)
        ->and($settings->get('delivery.zones.missing.charge', 7))->toBe(7);
});

test('stored values override the package defaults', function () {
    $settings = new class extends SettingsService
    {
        public function defaults(): array
        {
            return ['delivery' => ['zones' => ['inside_dhaka' => ['charge' => 60]]]];
        }

        protected function stored(): array
        {
            return ['delivery.zones.inside_dhaka.charge' => '95'];
        }
    };

    expect($settings->get('delivery.zones.inside_dhaka.charge'))->toBe('95');
});

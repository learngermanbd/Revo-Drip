<?php

namespace Webkul\RevoDrip\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RevoDripSettingsSeeder extends Seeder
{
    /**
     * Default settings written when the seeder runs.
     *
     * @var array
     */
    protected array $defaults = [
        'delivery.enabled'                    => '1',
        'delivery.default_zone'               => 'inside_dhaka',
        'delivery.zones.inside_dhaka.label'   => 'Inside Dhaka',
        'delivery.zones.inside_dhaka.charge'  => '60',
        'delivery.zones.outside_dhaka.label'  => 'Outside Dhaka',
        'delivery.zones.outside_dhaka.charge' => '120',
        'checkout.enabled'                    => '1',
        'checkout.show_delivery_zone'         => '1',
        'general.delivery_notes'              => 'Please keep your phone reachable for the delivery call.',
    ];

    /**
     * Run the seeder.
     */
    public function run(): void
    {
        if (! Schema::hasTable('revo_drip_settings')) {
            return;
        }

        $now = now();

        $rows = [];

        foreach ($this->defaults as $key => $value) {
            $rows[] = [
                'key'        => $key,
                'value'      => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('revo_drip_settings')->insertOrIgnore($rows);
    }
}

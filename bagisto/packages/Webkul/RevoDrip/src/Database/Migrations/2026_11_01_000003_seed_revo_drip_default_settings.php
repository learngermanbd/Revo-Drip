<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Default settings written on install. Existing values are kept.
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
        'checkout.fields.name.label'          => 'Full Name',
        'checkout.fields.name.placeholder'    => 'e.g. Md. Rakib Hasan',
        'checkout.fields.phone.label'         => 'Phone Number',
        'checkout.fields.phone.placeholder'   => 'e.g. 01712345678',
        'checkout.fields.email.label'         => 'Email Address',
        'checkout.fields.email.placeholder'   => 'e.g. you@example.com',
        'checkout.fields.address.label'       => 'Complete Delivery Address',
        'checkout.fields.address.placeholder' => 'House, road, area, district and any delivery instruction',
        'general.delivery_notes'              => 'Please keep your phone reachable for the delivery call.',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_keys($this->defaults) as $key) {
            DB::table('revo_drip_settings')->where('key', $key)->delete();
        }
    }
};

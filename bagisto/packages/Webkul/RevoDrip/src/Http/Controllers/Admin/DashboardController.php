<?php

namespace Webkul\RevoDrip\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;
use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\RevoDrip\Services\SettingsService;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected DeliveryZoneService $zones,
        protected SettingsService $settings
    ) {}

    /**
     * Delivery overview of the store.
     */
    public function index()
    {
        $stats = [
            'total'         => 0,
            'inside'        => 0,
            'outside'       => 0,
            'charges_total' => 0.0,
        ];

        try {
            $stats = [
                'total'         => DB::table('revo_drip_order_deliveries')->count(),
                'inside'        => DB::table('revo_drip_order_deliveries')->where('zone_code', DeliveryZoneService::INSIDE)->count(),
                'outside'       => DB::table('revo_drip_order_deliveries')->where('zone_code', DeliveryZoneService::OUTSIDE)->count(),
                'charges_total' => (float) DB::table('revo_drip_order_deliveries')->sum('delivery_charge'),
            ];
        } catch (Throwable $e) {
            report($e);
        }

        return view('revodrip-admin::dashboard', [
            'stats'        => $stats,
            'zones'        => $this->zones->zones(),
            'deliveryOn'   => $this->zones->isEnabled(),
            'checkoutOn'   => (bool) $this->settings->get('checkout.enabled', true),
            'formattedTotal' => core()->currency($stats['charges_total']),
        ]);
    }
}

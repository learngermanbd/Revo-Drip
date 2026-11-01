<?php

namespace Webkul\RevoDrip\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Webkul\RevoDrip\Http\Requests\DeliverySettingsRequest;
use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\RevoDrip\Services\SettingsService;

class DeliverySettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected SettingsService $settings,
        protected DeliveryZoneService $zones
    ) {}

    /**
     * Show the delivery settings.
     */
    public function edit()
    {
        return view('revodrip-admin::delivery', [
            'settings' => $this->settings->all(),
            'zones'    => $this->zones->zones(),
        ]);
    }

    /**
     * Persist the delivery settings.
     */
    public function update(DeliverySettingsRequest $request): RedirectResponse
    {
        $values = Arr::dot($request->validated());

        $values['delivery.enabled'] = isset($values['delivery.enabled']) && (string) $values['delivery.enabled'] === '1' ? '1' : '0';

        $this->settings->setMany($values);

        $this->settings->forget();

        session()->flash('success', trans('revodrip::app.admin.delivery.saved'));

        return redirect()->route('revodrip.admin.delivery.edit');
    }
}

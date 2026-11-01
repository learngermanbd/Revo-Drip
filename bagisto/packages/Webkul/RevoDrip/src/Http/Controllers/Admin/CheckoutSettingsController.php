<?php

namespace Webkul\RevoDrip\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Webkul\RevoDrip\Http\Requests\CheckoutSettingsRequest;
use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\RevoDrip\Services\SettingsService;

class CheckoutSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected SettingsService $settings,
        protected DeliveryZoneService $zones
    ) {}

    /**
     * Show the checkout settings.
     */
    public function edit()
    {
        return view('revodrip-admin::checkout', [
            'settings' => $this->settings->all(),
            'zones'    => $this->zones->zones(),
        ]);
    }

    /**
     * Persist the checkout settings.
     */
    public function update(CheckoutSettingsRequest $request): RedirectResponse
    {
        $values = Arr::dot($request->validated());

        foreach (['checkout.enabled', 'checkout.show_delivery_zone'] as $flag) {
            $values[$flag] = isset($values[$flag]) && (string) $values[$flag] === '1' ? '1' : '0';
        }

        $this->settings->setMany($values);

        session()->flash('success', trans('revodrip::app.admin.checkout.saved'));

        return redirect()->route('revodrip.admin.checkout.edit');
    }
}

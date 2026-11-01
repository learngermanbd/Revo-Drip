<?php

namespace Webkul\RevoDrip\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\RevoDrip\Services\SettingsService;

class GeneralSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected SettingsService $settings) {}

    /**
     * Show the general settings.
     */
    public function edit()
    {
        return view('revodrip-admin::general', [
            'settings' => $this->settings->all(),
        ]);
    }

    /**
     * Persist the general settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $values = $request->validate([
            'general.store_phone'    => ['nullable', 'string', 'max:30'],
            'general.delivery_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $this->settings->setMany([
            'general.store_phone'    => (string) ($values['general']['store_phone'] ?? ''),
            'general.delivery_notes' => (string) ($values['general']['delivery_notes'] ?? ''),
        ]);

        session()->flash('success', trans('revodrip::app.admin.general.saved'));

        return redirect()->route('revodrip.admin.general.edit');
    }
}

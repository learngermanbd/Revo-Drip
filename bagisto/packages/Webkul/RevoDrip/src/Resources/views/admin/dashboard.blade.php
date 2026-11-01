<x-admin::layouts>
    <x-slot:title>
        @lang('revodrip::app.admin.dashboard.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('revodrip::app.admin.dashboard.title')
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                    @lang('revodrip::app.admin.dashboard.subtitle')
                </p>
            </div>

            <div class="flex items-center gap-2 text-xs">
                <span class="rounded-full px-3 py-1 font-medium {{ $deliveryOn ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $deliveryOn ? trans('revodrip::app.admin.common.enabled') : trans('revodrip::app.admin.common.disabled') }}
                </span>

                <span class="rounded-full bg-gray-100 px-3 py-1 font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    {{ $checkoutOn ? trans('revodrip::app.admin.dashboard.checkout-on') : trans('revodrip::app.admin.dashboard.checkout-off') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 max-1180:grid-cols-2 max-md:grid-cols-1">
            @foreach ([
                ['label' => trans('revodrip::app.admin.dashboard.total-orders'), 'value' => $stats['total']],
                ['label' => trans('revodrip::app.admin.dashboard.inside-dhaka'), 'value' => $stats['inside']],
                ['label' => trans('revodrip::app.admin.dashboard.outside-dhaka'), 'value' => $stats['outside']],
                ['label' => trans('revodrip::app.admin.dashboard.collected'), 'value' => $formattedTotal],
            ] as $card)
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ $card['label'] }}
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $card['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('revodrip::app.admin.dashboard.zones')
                </p>
            </div>

            <div class="p-4">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="pb-2">@lang('revodrip::app.admin.delivery.zone')</th>
                            <th class="pb-2">@lang('revodrip::app.admin.delivery.charge')</th>
                            <th class="pb-2">@lang('revodrip::app.admin.delivery.method')</th>
                            <th class="pb-2">@lang('revodrip::app.admin.delivery.default')</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 dark:text-gray-200">
                        @foreach ($zones as $zone)
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="py-2">{{ $zone['label'] }}</td>
                                <td class="py-2">{{ $zone['formatted_charge'] }}</td>
                                <td class="py-2 font-mono text-xs">{{ $zone['method'] }}</td>
                                <td class="py-2">{{ $zone['code'] === app(\Webkul\RevoDrip\Services\DeliveryZoneService::class)->defaultZone() ? '✓' : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    @lang('revodrip::app.admin.dashboard.zones-help')
                </p>
            </div>
        </div>
    </div>
</x-admin::layouts>

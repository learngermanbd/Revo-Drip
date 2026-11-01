@php
    $revoDripDelivery = app(\Webkul\RevoDrip\Services\OrderDeliveryService::class)->forOrder($order->id);
@endphp

<div class="mt-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center justify-between gap-2">
        <p class="text-sm font-semibold text-gray-800 dark:text-white">
            @lang('revodrip::app.admin.order.title')
        </p>

        @if ($revoDripDelivery && $revoDripDelivery->zone_label)
            <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                {{ $revoDripDelivery->zone_label }}
            </span>
        @endif
    </div>

    @if ($revoDripDelivery)
        <dl class="mt-3 grid grid-cols-2 gap-2 text-xs max-md:grid-cols-1">
            <div>
                <dt class="text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.order.zone')</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-200">{{ $revoDripDelivery->zone_label ?: '—' }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.order.charge')</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-200">{{ core()->currency($revoDripDelivery->delivery_charge) }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.order.phone')</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-200">{{ $revoDripDelivery->phone ?: '—' }}</dd>
            </div>

            <div>
                <dt class="text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.order.name')</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-200">{{ $revoDripDelivery->customer_name ?: '—' }}</dd>
            </div>

            <div class="col-span-2 max-md:col-span-1">
                <dt class="text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.order.address')</dt>
                <dd class="whitespace-pre-line font-medium text-gray-800 dark:text-gray-200">{{ $revoDripDelivery->full_address ?: '—' }}</dd>
            </div>
        </dl>
    @else
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            @lang('revodrip::app.admin.order.empty')
        </p>
    @endif
</div>

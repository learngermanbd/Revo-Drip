{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}

<div class="mb-7 max-md:mb-0">
    <div class="flex items-center justify-between gap-3 rounded-xl border border-zinc-200 px-4 py-3 max-md:p-3">
        <div>
            <p class="text-sm font-medium">
                @lang('revodrip::app.delivery.carrier-title')
            </p>

            <p class="mt-0.5 text-xs text-zinc-500">
                @lang('revodrip::app.delivery.selected-at-address')
            </p>
        </div>

        <p class="text-sm font-semibold">
            @{{ cart.formatted_shipping_amount }}
        </p>
    </div>
</div>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.after') !!}

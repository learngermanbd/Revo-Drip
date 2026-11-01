@php
    $revoDripZones = app(\Webkul\RevoDrip\Services\DeliveryZoneService::class);

    $revoDripSettings = app(\Webkul\RevoDrip\Services\SettingsService::class);

    $revoDripCustomer = auth()->guard('customer')->user();

    $revoDripZoneSelection = $revoDripZones->selectionVisible();

    $revoDripText = fn (string $key): string => (string) trans((string) $revoDripSettings->get($key));
@endphp

{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<div class="mb-7 mt-8 rounded-xl border border-zinc-200 p-5 max-md:mt-0 max-md:rounded-lg max-md:p-4">
    <h2 class="text-2xl font-medium max-md:text-base">
        @lang('shop::app.checkout.onepage.address.title')
    </h2>

    <v-revodrip-checkout-address
        :cart="cart"
        :zones='{!! json_encode($revoDripZones->zones()) !!}'
        :default-zone='{!! json_encode($revoDripZones->defaultZone()) !!}'
        :show-zone='{!! json_encode($revoDripZoneSelection) !!}'
        :is-guest='{!! json_encode(is_null($revoDripCustomer)) !!}'
        :account-email='{!! json_encode($revoDripCustomer?->email ?? '') !!}'
        :delivery-notes='{!! json_encode($revoDripText('general.delivery_notes')) !!}'
        address-route='{!! json_encode(route('revodrip.checkout.address')) !!}'
        zone-route='{!! json_encode(route('revodrip.checkout.delivery-zone')) !!}'
        @processing="stepForward"
        @processed="stepProcessed"
    ></v-revodrip-checkout-address>
</div>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-revodrip-checkout-address-template"
    >
        <form
            class="mt-5 grid gap-4"
            @submit.prevent="submit"
        >
            <!-- Full Name -->
            <div>
                <label class="mb-1.5 block text-sm font-medium" for="revodrip_name">
                    {{ $revoDripText('checkout.fields.name.label') }} <span class="text-red-600">*</span>
                </label>

                <input
                    id="revodrip_name"
                    type="text"
                    class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                    v-model="form.name"
                    placeholder="{{ $revoDripText('checkout.fields.name.placeholder') }}"
                    autocomplete="name"
                    required
                >

                <p class="mt-1 text-xs text-red-600" v-if="errors.name">@{{ errors.name[0] }}</p>
            </div>

            <!-- Phone and Email -->
            <div class="grid grid-cols-2 gap-4 max-sm:grid-cols-1">
                <div>
                    <label class="mb-1.5 block text-sm font-medium" for="revodrip_phone">
                        {{ $revoDripText('checkout.fields.phone.label') }} <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="revodrip_phone"
                        type="tel"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                        v-model="form.phone"
                        placeholder="{{ $revoDripText('checkout.fields.phone.placeholder') }}"
                        autocomplete="tel"
                        inputmode="tel"
                        required
                    >

                    <p class="mt-1 text-xs text-zinc-500">@lang('revodrip::app.checkout.help.phone')</p>

                    <p class="mt-1 text-xs text-red-600" v-if="errors.phone">@{{ errors.phone[0] }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium" for="revodrip_email">
                        {{ $revoDripText('checkout.fields.email.label') }} <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="revodrip_email"
                        type="email"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                        :class="! isGuest ? 'bg-gray-100 text-gray-500' : ''"
                        v-model="form.email"
                        placeholder="{{ $revoDripText('checkout.fields.email.placeholder') }}"
                        autocomplete="email"
                        :readonly="! isGuest"
                        required
                    >

                    <p class="mt-1 text-xs text-zinc-500" v-if="! isGuest">
                        @lang('revodrip::app.checkout.help.account-email')
                    </p>

                    <p class="mt-1 text-xs text-red-600" v-if="errors.email">@{{ errors.email[0] }}</p>
                </div>
            </div>

            <!-- Complete Delivery Address -->
            <div>
                <label class="mb-1.5 block text-sm font-medium" for="revodrip_address">
                    {{ $revoDripText('checkout.fields.address.label') }} <span class="text-red-600">*</span>
                </label>

                <textarea
                    id="revodrip_address"
                    class="min-h-28 w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                    v-model="form.address"
                    placeholder="{{ $revoDripText('checkout.fields.address.placeholder') }}"
                    autocomplete="street-address"
                    required
                ></textarea>

                <p class="mt-1 text-xs text-zinc-500">@lang('revodrip::app.checkout.help.address')</p>

                <p class="mt-1 text-xs text-red-600" v-if="errors.address">@{{ errors.address[0] }}</p>
            </div>

            <!-- Delivery Area -->
            <div v-if="showZone">
                <p class="mb-2 block text-sm font-medium">
                    @lang('revodrip::app.checkout.delivery-area') <span class="text-red-600">*</span>
                </p>

                <div class="grid gap-2">
                    <label
                        class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border px-4 py-3"
                        :class="form.delivery_zone === zone.code ? 'border-navyBlue bg-blue-50' : 'border-zinc-200'"
                        v-for="zone in zoneList"
                        :key="zone.code"
                    >
                        <span class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="delivery_zone"
                                class="h-4 w-4"
                                :value="zone.code"
                                v-model="form.delivery_zone"
                                :disabled="isUpdatingZone"
                                @change="selectZone(zone.code)"
                            >

                            <span class="text-sm font-medium">@{{ zone.label }}</span>
                        </span>

                        <span class="text-sm font-semibold">@{{ zone.formatted_charge }}</span>
                    </label>
                </div>

                <p class="mt-1 text-xs text-red-600" v-if="errors.delivery_zone">@{{ errors.delivery_zone[0] }}</p>

                <p class="mt-2 text-xs text-zinc-500" v-if="deliveryNotes">@{{ deliveryNotes }}</p>
            </div>

            <!-- Delivery area when the selector is switched off by the store -->
            <div
                class="flex items-center justify-between rounded-xl border border-zinc-200 px-4 py-3 text-sm"
                v-else
            >
                <span>@lang('revodrip::app.checkout.delivery-area')</span>

                <span class="font-semibold">@{{ selectedZone ? selectedZone.label : '' }}</span>
            </div>

            <p class="text-xs text-red-600" v-if="zoneError">@{{ zoneError }}</p>

            <!-- Delivery total preview -->
            <div class="flex items-center justify-between rounded-xl bg-gray-100 px-4 py-3 text-sm" v-if="selectedZone">
                <span>@lang('revodrip::app.checkout.delivery-charge')</span>

                <span class="font-semibold">@{{ selectedZone.formatted_charge }}</span>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="primary-button rounded-2xl px-11 py-3 max-md:w-full max-md:max-w-full max-md:rounded-lg"
                    :disabled="isStoring || isUpdatingZone"
                >
                    <span v-if="! isStoring">@lang('revodrip::app.checkout.continue')</span>
                    <span v-else>@lang('revodrip::app.checkout.processing')</span>
                </button>
            </div>
        </form>
    </script>

    <script type="module">
        app.component('v-revodrip-checkout-address', {
            template: '#v-revodrip-checkout-address-template',

            props: {
                cart: { type: Object, default: () => ({}) },

                zones: { type: Object, default: () => ({}) },

                defaultZone: { type: String, default: '' },

                deliveryNotes: { type: String, default: '' },

                showZone: { type: Boolean, default: true },

                isGuest: { type: Boolean, default: true },

                accountEmail: { type: String, default: '' },

                addressRoute: { type: String, required: true },

                zoneRoute: { type: String, required: true },
            },

            emits: ['processing', 'processed'],

            data() {
                return {
                    form: {
                        name: '',
                        phone: '',
                        email: '',
                        address: '',
                        delivery_zone: '',
                    },

                    errors: {},

                    zoneError: '',

                    isStoring: false,

                    isUpdatingZone: false,

                    userPickedZone: false,
                };
            },

            computed: {
                zoneList() {
                    return Object.values(this.zones);
                },

                selectedZone() {
                    return this.zones[this.form.delivery_zone] || null;
                },

                /**
                 * Delivery area the cart is already charged for, so a returning shopper sees
                 * the same area and the same charge instead of the package default.
                 */
                cartZone() {
                    const method = this.cart ? this.cart.shipping_method : null;

                    if (typeof method !== 'string' || method.indexOf('revodrip_') !== 0) {
                        return '';
                    }

                    const code = method.substring('revodrip_'.length);

                    return this.zones[code] ? code : '';
                },
            },

            watch: {
                cartZone(code) {
                    if (! this.userPickedZone && code) {
                        this.form.delivery_zone = code;
                    }
                },
            },

            mounted() {
                this.prefill();
            },

            methods: {
                prefill() {
                    const address = this.cart ? this.cart.billing_address : null;

                    if (address) {
                        this.form.name = [address.first_name, address.last_name].filter(Boolean).join(' ').trim();

                        this.form.phone = address.phone || '';

                        /**
                         * The cart address is serialised as an array of lines, so restore the
                         * textarea with newlines instead of letting it collapse into one line.
                         */
                        this.form.address = Array.isArray(address.address)
                            ? address.address.join('\n')
                            : (address.address || '');
                    }

                    this.form.email = this.isGuest
                        ? ((address && address.email) || '')
                        : (this.accountEmail || (address && address.email) || '');

                    this.form.delivery_zone = this.cartZone || this.defaultZone || Object.keys(this.zones)[0] || '';
                },

                selectZone(code) {
                    const previous = this.form.delivery_zone;

                    this.form.delivery_zone = code;

                    this.userPickedZone = true;

                    this.zoneError = '';

                    if (! this.cart || ! this.cart.billing_address) {
                        return;
                    }

                    this.isUpdatingZone = true;

                    this.$axios.post(this.zoneRoute, { delivery_zone: code })
                        .then(() => {
                            this.refreshCart();
                        })
                        .catch((error) => {
                            this.form.delivery_zone = previous;

                            this.zoneError = (error.response && error.response.data && error.response.data.message)
                                || '@lang('revodrip::app.checkout.error')';
                        })
                        .finally(() => {
                            this.isUpdatingZone = false;
                        });
                },

                refreshCart() {
                    const parent = this.$parent;

                    if (parent && typeof parent.getCart === 'function') {
                        parent.getCart();
                    }
                },

                submit() {
                    this.errors = {};

                    this.zoneError = '';

                    this.isStoring = true;

                    if (! this.isGuest) {
                        this.form.email = this.accountEmail;
                    }

                    this.$emit('processing', 'payment');

                    this.$axios.post(this.addressRoute, this.form)
                        .then((response) => {
                            this.isStoring = false;

                            if (response.data.redirect) {
                                window.location.href = response.data.redirect_url;

                                return;
                            }

                            this.$emit('processed', response.data.data ? response.data.data.payment_methods : null);
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            this.$emit('processing', 'address');

                            if (error.response && error.response.status === 422) {
                                this.errors = error.response.data.errors || {};

                                return;
                            }

                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: (error.response && error.response.data && error.response.data.message) || '@lang('revodrip::app.checkout.error')',
                            });
                        });
                },
            },
        });
    </script>
@endPushOnce

@php
    $revoDripZones = app(\Webkul\RevoDrip\Services\DeliveryZoneService::class);

    $revoDripSettings = app(\Webkul\RevoDrip\Services\SettingsService::class);
@endphp

{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<div class="mb-7 mt-8 rounded-xl border border-zinc-200 p-5 max-md:mt-0 max-md:rounded-lg max-md:p-4">
    <h2 class="text-2xl font-medium max-md:text-base">
        @lang('shop::app.checkout.onepage.address.title')
    </h2>

    <v-revodrip-checkout-address
        :cart="cart"
        :zones="{{ json_encode($revoDripZones->zones()) }}"
        default-zone="{{ $revoDripZones->defaultZone() }}"
        :show-zone="{{ $revoDripZones->isEnabled() ? 'true' : 'false' }}"
        delivery-notes="{{ $revoDripSettings->get('general.delivery_notes') }}"
        address-route="{{ route('revodrip.checkout.address') }}"
        zone-route="{{ route('revodrip.checkout.delivery-zone') }}"
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
                    {{ $revoDripSettings->get('checkout.fields.name.label') }} <span class="text-red-600">*</span>
                </label>

                <input
                    id="revodrip_name"
                    type="text"
                    class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                    v-model="form.name"
                    placeholder="{{ $revoDripSettings->get('checkout.fields.name.placeholder') }}"
                    autocomplete="name"
                    required
                >

                <p class="mt-1 text-xs text-red-600" v-if="errors.name">@{{ errors.name[0] }}</p>
            </div>

            <!-- Phone and Email -->
            <div class="grid grid-cols-2 gap-4 max-sm:grid-cols-1">
                <div>
                    <label class="mb-1.5 block text-sm font-medium" for="revodrip_phone">
                        {{ $revoDripSettings->get('checkout.fields.phone.label') }} <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="revodrip_phone"
                        type="tel"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                        v-model="form.phone"
                        placeholder="{{ $revoDripSettings->get('checkout.fields.phone.placeholder') }}"
                        autocomplete="tel"
                        inputmode="tel"
                        required
                    >

                    <p class="mt-1 text-xs text-zinc-500">@lang('revodrip::app.checkout.help.phone')</p>

                    <p class="mt-1 text-xs text-red-600" v-if="errors.phone">@{{ errors.phone[0] }}</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium" for="revodrip_email">
                        {{ $revoDripSettings->get('checkout.fields.email.label') }} <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="revodrip_email"
                        type="email"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                        v-model="form.email"
                        placeholder="{{ $revoDripSettings->get('checkout.fields.email.placeholder') }}"
                        autocomplete="email"
                        required
                    >

                    <p class="mt-1 text-xs text-red-600" v-if="errors.email">@{{ errors.email[0] }}</p>
                </div>
            </div>

            <!-- Complete Delivery Address -->
            <div>
                <label class="mb-1.5 block text-sm font-medium" for="revodrip_address">
                    {{ $revoDripSettings->get('checkout.fields.address.label') }} <span class="text-red-600">*</span>
                </label>

                <textarea
                    id="revodrip_address"
                    class="min-h-28 w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-hidden"
                    v-model="form.address"
                    placeholder="{{ $revoDripSettings->get('checkout.fields.address.placeholder') }}"
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
                                :checked="form.delivery_zone === zone.code"
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

            <!-- Delivery total preview -->
            <div class="flex items-center justify-between rounded-xl bg-gray-100 px-4 py-3 text-sm" v-if="selectedZone">
                <span>@lang('revodrip::app.checkout.delivery-charge')</span>

                <span class="font-semibold">@{{ selectedZone.formatted_charge }}</span>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="primary-button rounded-2xl px-11 py-3 max-md:w-full max-md:max-w-full max-md:rounded-lg"
                    :disabled="isStoring"
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
                        delivery_zone: this.defaultZone || Object.keys(this.zones)[0] || '',
                    },

                    errors: {},

                    isStoring: false,
                };
            },

            computed: {
                zoneList() {
                    return Object.values(this.zones);
                },

                selectedZone() {
                    return this.zones[this.form.delivery_zone] || null;
                },
            },

            mounted() {
                this.prefill();
            },

            methods: {
                prefill() {
                    const address = this.cart ? this.cart.billing_address : null;

                    if (! address) {
                        return;
                    }

                    this.form.name = [address.first_name, address.last_name].filter(Boolean).join(' ').trim();

                    this.form.phone = address.phone || '';

                    this.form.email = address.email || '';

                    this.form.address = address.address || '';
                },

                selectZone(code) {
                    this.form.delivery_zone = code;

                    if (! this.cart || ! this.cart.billing_address) {
                        return;
                    }

                    this.$axios.post(this.zoneRoute, { delivery_zone: code })
                        .then((response) => {
                            this.$emit('zone-updated', response.data);
                        })
                        .catch(() => {});
                },

                submit() {
                    this.errors = {};

                    this.isStoring = true;

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

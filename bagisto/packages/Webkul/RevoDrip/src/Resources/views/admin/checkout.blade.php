<x-admin::layouts>
    <x-slot:title>
        @lang('revodrip::app.admin.checkout.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('revodrip::app.admin.checkout.title')
            </p>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                @lang('revodrip::app.admin.checkout.subtitle')
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('revodrip.admin.checkout.update') }}"
            class="flex flex-col gap-4"
        >
            @csrf

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('revodrip::app.admin.checkout.behaviour')
                    </p>
                </div>

                <div class="grid gap-4 p-4 max-md:grid-cols-1 md:grid-cols-2">
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.checkout.enable')</span>

                        <input type="hidden" name="checkout[enabled]" value="0">

                        <input
                            type="checkbox"
                            name="checkout[enabled]"
                            value="1"
                            {{ (int) ($settings['checkout']['enabled'] ?? 0) === 1 ? 'checked' : '' }}
                        >

                        <span class="text-xs text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.checkout.enable-info')</span>
                    </label>

                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.checkout.show-zone')</span>

                        <input type="hidden" name="checkout[show_delivery_zone]" value="0">

                        <input
                            type="checkbox"
                            name="checkout[show_delivery_zone]"
                            value="1"
                            {{ (int) ($settings['checkout']['show_delivery_zone'] ?? 0) === 1 ? 'checked' : '' }}
                        >

                        <span class="text-xs text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.checkout.show-zone-info')</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('revodrip::app.admin.checkout.fields')
                    </p>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        @lang('revodrip::app.admin.checkout.fields-info')
                    </p>
                </div>

                <div class="grid gap-4 p-4">
                    @foreach (['name', 'phone', 'email', 'address'] as $field)
                        <div class="grid gap-3 md:grid-cols-2">
                            <label class="flex flex-col gap-1 text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    @lang('revodrip::app.admin.checkout.field-label', ['field' => trans('revodrip::app.admin.checkout.field-'.$field)])
                                </span>

                                <input
                                    type="text"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                                    name="checkout[fields][{{ $field }}][label]"
                                    value="{{ trans((string) ($settings['checkout']['fields'][$field]['label'] ?? '')) }}"
                                    required
                                >
                            </label>

                            <label class="flex flex-col gap-1 text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    @lang('revodrip::app.admin.checkout.field-placeholder')
                                </span>

                                <input
                                    type="text"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                                    name="checkout[fields][{{ $field }}][placeholder]"
                                    value="{{ trans((string) ($settings['checkout']['fields'][$field]['placeholder'] ?? '')) }}"
                                >
                            </label>
                        </div>
                    @endforeach

                    <p class="rounded-md bg-gray-50 px-3 py-2 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        @lang('revodrip::app.admin.checkout.required-note')
                    </p>
                </div>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="primary-button rounded-md px-5 py-2 text-sm"
                >
                    @lang('revodrip::app.admin.common.save')
                </button>
            </div>
        </form>
    </div>
</x-admin::layouts>

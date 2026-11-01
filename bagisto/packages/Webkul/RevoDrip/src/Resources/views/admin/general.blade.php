<x-admin::layouts>
    <x-slot:title>
        @lang('revodrip::app.admin.general.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('revodrip::app.admin.general.title')
            </p>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                @lang('revodrip::app.admin.general.subtitle')
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
            action="{{ route('revodrip.admin.general.update') }}"
            class="flex flex-col gap-4"
        >
            @csrf

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="grid gap-4 p-4">
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.general.store-phone')</span>

                        <input
                            type="text"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                            name="general[store_phone]"
                            value="{{ $settings['general']['store_phone'] ?? '' }}"
                        >

                        <span class="text-xs text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.general.store-phone-info')</span>
                    </label>

                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.general.delivery-notes')</span>

                        <textarea
                            class="min-h-24 w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                            name="general[delivery_notes]"
                        >{{ trans((string) ($settings['general']['delivery_notes'] ?? '')) }}</textarea>

                        <span class="text-xs text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.general.delivery-notes-info')</span>
                    </label>
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

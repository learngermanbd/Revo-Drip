<x-admin::layouts>
    <x-slot:title>
        @lang('revodrip::app.admin.delivery.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('revodrip::app.admin.delivery.title')
            </p>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                @lang('revodrip::app.admin.delivery.subtitle')
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
            action="{{ route('revodrip.admin.delivery.update') }}"
            class="flex flex-col gap-4"
        >
            @csrf

            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="grid gap-4 p-4 md:grid-cols-2">
                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.delivery.enable')</span>

                        <input type="hidden" name="delivery[enabled]" value="0">

                        <input
                            type="checkbox"
                            name="delivery[enabled]"
                            value="1"
                            {{ (int) ($settings['delivery']['enabled'] ?? 0) === 1 ? 'checked' : '' }}
                        >

                        <span class="text-xs text-gray-500 dark:text-gray-400">@lang('revodrip::app.admin.delivery.enable-info')</span>
                    </label>

                    <label class="flex flex-col gap-1 text-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.delivery.default')</span>

                        <select
                            name="delivery[default_zone]"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                        >
                            @foreach ($zones as $zone)
                                <option
                                    value="{{ $zone['code'] }}"
                                    {{ ($settings['delivery']['default_zone'] ?? '') === $zone['code'] ? 'selected' : '' }}
                                >
                                    {{ $zone['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($zones as $zone)
                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                {{ $zone['label'] }}
                            </p>

                            <p class="mt-1 font-mono text-xs text-gray-400">{{ $zone['method'] }}</p>
                        </div>

                        <div class="grid gap-3 p-4">
                            <label class="flex flex-col gap-1 text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-200">@lang('revodrip::app.admin.delivery.zone-label')</span>

                                <input
                                    type="text"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                                    name="delivery[zones][{{ $zone['code'] }}][label]"
                                    value="{{ $zone['raw_label'] }}"
                                    required
                                >
                            </label>

                            <label class="flex flex-col gap-1 text-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    @lang('revodrip::app.admin.delivery.charge-bdt')
                                </span>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
                                    name="delivery[zones][{{ $zone['code'] }}][charge]"
                                    value="{{ $zone['charge'] }}"
                                    required
                                >

                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    @lang('revodrip::app.admin.delivery.charge-info')
                                </span>
                            </label>
                        </div>
                    </div>
                @endforeach
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

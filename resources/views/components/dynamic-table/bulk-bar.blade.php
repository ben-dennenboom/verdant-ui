@props(['vm', 'bulkStoreKey'])

@php
    $bulkFields    = $vm->bulkFields ?? [];
    $bulkActionUrl = $vm->bulkActionUrl ?? request()->url();
    $bsk           = $bulkStoreKey;
@endphp

<div
    x-show="$store[@js($bsk)].selected.length > 0"
    x-cloak
    x-transition:enter="v-transition v-ease-out v-duration-200"
    x-transition:enter-start="v-opacity-0 v-translate-y-3"
    x-transition:enter-end="v-opacity-100 v-translate-y-0"
    x-transition:leave="v-transition v-ease-in v-duration-150"
    x-transition:leave-start="v-opacity-100 v-translate-y-0"
    x-transition:leave-end="v-opacity-0 v-translate-y-3"
    class="v-fixed v-bottom-6 v-left-1/2 -v-translate-x-1/2 v-z-50"
>
    <form method="POST" action="{{ $bulkActionUrl }}" x-ref="bulkForm">
        @csrf

        {{-- Selected IDs rendered by Alpine --}}
        <template x-for="id in $store[@js($bsk)].selected" :key="id">
            <input type="hidden" name="_ids[]" :value="id">
        </template>

        <div class="v-flex v-items-center v-bg-white dark:v-bg-gray-800 v-rounded-full v-shadow-xl v-border v-border-gray-200 dark:v-border-gray-700 v-px-5 v-py-2.5 v-gap-0 v-whitespace-nowrap">
            {{-- Count --}}
            <span class="v-text-sm v-font-semibold v-text-gray-800 dark:v-text-gray-100 v-pr-4">
                <span x-text="$store[@js($bsk)].selected.length"></span> selected
            </span>

            <div class="v-w-px v-self-stretch v-bg-gray-200 dark:v-bg-gray-600"></div>

            {{-- Bulk fields --}}
            @foreach ($bulkFields as $field)
                @php
                    $key         = $field['key'];
                    $label       = $field['label'];
                    $type        = $field['type'] ?? 'text';
                    $options     = $field['options'] ?? [];
                    $placeholder = $field['placeholder'] ?? ('Select ' . strtolower($label) . '…');
                    $multiple    = !empty($field['multiple']);
                    $noneOpt     = collect($options)->firstWhere('value', '_none');
                    $mainOptions = collect($options)->filter(fn($o) => $o['value'] !== '_none')->values()->all();
                    $selectClass = 'v-text-sm v-border-0 v-bg-transparent v-text-gray-700 dark:v-text-gray-200 focus:v-ring-0 focus:v-outline-none v-cursor-pointer v-py-0 v-pl-1 v-pr-6';
                    $inputClass  = 'v-text-sm v-border v-border-gray-300 dark:v-border-gray-600 v-rounded-lg v-bg-white dark:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-200 v-px-2 v-py-1 focus:v-ring-1 focus:v-ring-primary-500 focus:v-border-primary-500 focus:v-outline-none';
                @endphp

                <div class="v-flex v-items-center v-gap-1.5 v-pl-4 v-pr-3">
                    @if ($type === 'select' && $multiple)
                        {{-- Custom Alpine dropdown — native <select multiple> renders as a listbox, not a dropdown --}}
                        <span class="v-text-sm v-text-gray-600">{{ $label }}</span>
                        <div
                            x-data="{ open: false, selected: [] }"
                            @click.away="open = false"
                            class="v-relative"
                        >
                            <template x-for="val in selected" :key="val">
                                <input type="hidden" name="{{ $key }}[]" :value="val">
                            </template>

                            <button
                                type="button"
                                @click="open = !open"
                                class="v-flex v-items-center v-gap-1 v-text-sm v-text-gray-700 v-cursor-pointer v-py-0 v-pl-1 v-pr-1 focus:v-outline-none"
                            >
                                <span x-text="selected.includes('_none') ? '{{ addslashes($noneOpt ? $noneOpt['label'] : 'None') }}' : (selected.length ? selected.length + ' {{ addslashes(strtolower($label)) }}' : '{{ addslashes($placeholder) }}')"></span>
                                <svg class="v-w-3 v-h-3 v-text-gray-500 v-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="v-transition v-ease-out v-duration-100"
                                x-transition:enter-start="v-opacity-0 v-scale-95"
                                x-transition:enter-end="v-opacity-100 v-scale-100"
                                x-transition:leave="v-transition v-ease-in v-duration-75"
                                x-transition:leave-start="v-opacity-100 v-scale-100"
                                x-transition:leave-end="v-opacity-0 v-scale-95"
                                class="v-absolute v-bottom-full v-left-1/2 -v-translate-x-1/2 v-mb-3 v-min-w-40 v-bg-white v-rounded-lg v-shadow-lg v-border v-border-gray-200 v-py-1 v-z-50 v-max-h-56 v-overflow-y-auto"
                            >
                                @if ($noneOpt)
                                    <label class="v-flex v-items-center v-gap-2 v-px-3 v-py-1.5 v-text-sm v-text-gray-400 v-italic hover:v-bg-gray-50 v-cursor-pointer v-select-none">
                                        <input
                                            type="checkbox"
                                            value="_none"
                                            @change="if ($event.target.checked) { selected = ['_none'] }"
                                            :checked="selected.includes('_none')"
                                            class="v-rounded v-border-gray-300 v-text-primary-600 focus:v-ring-primary-500 v-bg-white"
                                        >
                                        {{ $noneOpt['label'] }}
                                    </label>
                                    <div class="v-border-t v-border-gray-100 v-mx-2 v-my-1"></div>
                                @endif
                                @foreach ($mainOptions as $opt)
                                    <label class="v-flex v-items-center v-gap-2 v-px-3 v-py-1.5 v-text-sm v-text-gray-700 hover:v-bg-gray-50 v-cursor-pointer v-select-none">
                                        <input
                                            type="checkbox"
                                            value="{{ $opt['value'] }}"
                                            @change="if ($event.target.checked) { selected = selected.filter(v => v !== '_none') }"
                                            x-model="selected"
                                            class="v-rounded v-border-gray-300 v-text-primary-600 focus:v-ring-primary-500 v-bg-white"
                                        >
                                        {{ $opt['label'] }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @elseif ($type === 'select')
                        <span class="v-text-sm v-text-gray-600 dark:v-text-gray-400">{{ $label }}</span>
                        <select
                            name="{{ $key }}"
                            class="{{ $selectClass }}"
                        >
                            <option value="">{{ $placeholder }}</option>
                            @foreach ($options as $opt)
                                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                            @endforeach
                        </select>
                    @elseif ($type === 'checkbox')
                        <label class="v-flex v-items-center v-gap-2 v-text-sm v-text-gray-600 dark:v-text-gray-400 v-cursor-pointer v-select-none">
                            <input
                                type="checkbox"
                                name="{{ $key }}"
                                value="1"
                                class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 v-bg-white dark:v-bg-gray-700"
                            >
                            {{ $label }}
                        </label>
                    @else
                        <span class="v-text-sm v-text-gray-600 dark:v-text-gray-400">{{ $label }}</span>
                        <input
                            type="{{ $type === 'number' ? 'number' : ($type === 'date' ? 'date' : 'text') }}"
                            name="{{ $key }}"
                            placeholder="{{ $placeholder }}"
                            class="{{ $inputClass }} v-w-32"
                        >
                    @endif
                </div>

                @if (!$loop->last)
                    <div class="v-w-px v-self-stretch v-bg-gray-200 dark:v-bg-gray-600"></div>
                @endif
            @endforeach

            <div class="v-w-px v-self-stretch v-bg-gray-200 dark:v-bg-gray-600 v-ml-1"></div>

            {{-- Apply --}}
            <button
                type="submit"
                class="v-ml-3 v-text-sm v-font-medium v-text-primary-600 dark:v-text-primary-400 hover:v-text-primary-800 dark:hover:v-text-primary-200 v-transition-colors"
            >
                Apply
            </button>

            {{-- Clear --}}
            <button
                type="button"
                @click="$store[@js($bsk)].clear(); $refs.bulkForm.reset()"
                class="v-ml-3 v-text-sm v-text-gray-500 dark:v-text-gray-400 hover:v-text-gray-700 dark:hover:v-text-gray-200 v-transition-colors"
            >
                Clear
            </button>
        </div>
    </form>
</div>

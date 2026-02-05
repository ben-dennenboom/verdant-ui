@props(['vm', 'modalId'])

@php
    $filterColumns = $vm->filterColumns ?? [];
    $filterValues = $vm->filterValues ?? [];
    $filterKeys = collect($filterColumns)->pluck('key')->all();
    $currentQuery = request()->except($filterKeys);
    $activeCount = collect($filterValues)->filter(fn ($v) => $v !== null && $v !== '' && $v !== false)->count();
    $clearUrl = $activeCount > 0 ? request()->url() . (count($currentQuery) ? '?' . http_build_query($currentQuery) : '') : null;
    $formId = $modalId . '-form';
@endphp

<button
    type="button"
    class="v-inline-flex v-items-center v-gap-2 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-300"
    aria-label="Filter table"
    aria-haspopup="dialog"
    aria-controls="{{ $modalId }}"
    @click="$dispatch('open-modal', '{{ $modalId }}')"
>
    <i class="fa fa-filter"></i>
    Filter
    @if($activeCount > 0)
        <span class="v-text-primary-800 dark:v-text-primary-200 v-text-xs">
            {{ $activeCount }}
        </span>
    @endif
</button>

@if($clearUrl)
    <a
        href="{{ $clearUrl }}"
        class="v-text-sm v-text-gray-500 hover:v-text-gray-700 dark:hover:v-text-gray-400 hover:v-underline"
        title="Clear all filters"
    >
        Clear
    </a>
@endif

<template x-teleport="body">
    <x-v-modal :id="$modalId" maxWidth="lg">
        <form
            id="{{ $formId }}"
            method="GET"
            action="{{ request()->url() }}"
            class="v-form v-auto-filter"
        >
            @foreach ($currentQuery as $name => $value)
                @if (is_array($value))
                    @foreach ($value as $v)
                        <input type="hidden" name="{{ $name }}[]" value="{{ $v }}" />
                    @endforeach
                @else
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
                @endif
            @endforeach

            <h3 class="v-text-lg v-font-medium v-leading-6 v-text-gray-900 dark:v-text-gray-100" id="{{ $modalId }}-title">
                Filters
            </h3>

            <div class="v-mt-4 v-mb-4 v-space-y-4">
                @foreach ($filterColumns as $col)
                    @php
                        $key = $col['key'];
                        $label = $col['label'];
                        $type = $col['type'] ?? 'text';
                        $value = $filterValues[$key] ?? $col['default'] ?? '';
                        $placeholder = $col['placeholder'] ?? null;
                        $options = $col['options'] ?? null;
                    @endphp

                    @if ($type === 'checkbox')
                        <div class="v-flex v-items-center v-gap-2">
                            <input
                                type="checkbox"
                                name="{{ $key }}"
                                id="{{ $modalId }}-{{ $key }}"
                                value="1"
                                {{ $value ? 'checked' : '' }}
                                class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500"
                            />
                            <label for="{{ $modalId }}-{{ $key }}" class="v-text-sm v-font-medium v-text-gray-700 dark:v-text-gray-300">
                                {{ $label }}
                            </label>
                        </div>
                    @elseif ($type === 'select')
                        <div class="v-w-full">
                            <label for="{{ $modalId }}-{{ $key }}" class="v-block v-text-sm v-font-medium v-text-gray-700 dark:v-text-gray-300">
                                {{ $label }}
                            </label>
                            <select
                                name="{{ $key }}"
                                id="{{ $modalId }}-{{ $key }}"
                                class="v-mt-1 v-block v-w-full v-rounded-md v-border v-border-gray-300
                                    dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm
                                    v-text-gray-900 dark:v-text-gray-100 focus:v-ring-primary-500 focus:v-border-primary-500"
                            >
                                @if ($options)
                                    @foreach ($options as $opt)
                                        <option value="{{ $opt['value'] ?? '' }}" {{ (string)($opt['value'] ?? '') === (string)$value ? 'selected' : '' }}>
                                            {{ $opt['label'] ?? $opt['value'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @else
                        <div class="v-w-full">
                            <label for="{{ $modalId }}-{{ $key }}" class="v-block v-text-sm v-font-medium v-text-gray-700 dark:v-text-gray-300">
                                {{ $label }}
                            </label>
                            <input
                                type="{{ $type === 'date' ? 'date' : ($type === 'number' ? 'number' : 'text') }}"
                                name="{{ $key }}"
                                id="{{ $modalId }}-{{ $key }}"
                                value="{{ $type === 'checkbox' ? '' : e($value) }}"
                                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                                class="v-mt-1 v-block v-w-full v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm v-text-gray-900 dark:v-text-gray-100 focus:v-ring-primary-500 focus:v-border-primary-500"
                            />
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="v-mt-5 sm:v-mt-4 v-flex v-justify-between">
                <x-verdant::button.secondary
                    type="button"
                    @click="$dispatch('close-modal')"
                >
                    Cancel
                </x-verdant::button.secondary>
                <div class="v-flex v-gap-2">
                    @if($clearUrl)
                        <x-verdant::button.danger
                            href="{{ $clearUrl }}"
                        >
                            Reset
                        </x-verdant::button.danger>
                    @endif
                    <x-verdant::button.primary type="submit">
                        Apply
                    </x-verdant::button.primary>
                </div>
            </div>
        </form>
    </x-v-modal>
</template>

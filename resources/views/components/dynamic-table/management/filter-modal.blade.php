@props(['vm', 'modalId'])

@php
    $filterColumns = $vm->filterColumns ?? [];
    $filterValues = $vm->filterValues ?? [];
    $filterKeys = collect($filterColumns)->pluck('key')->all();
    $currentQuery = request()->except($filterKeys);
    $activeCount = collect($filterValues)->filter(function ($v) {
        if (is_array($v)) {
            return count($v) > 0;
        }
        return $v !== null && $v !== '' && $v !== false;
    })->count();
    $clearUrl = $activeCount > 0 ? request()->url() . (count($currentQuery) ? '?' . http_build_query($currentQuery) : '') : null;
    $formId = $modalId . '-form';
@endphp

<x-v-button.light
    type="button"
    icon="filter"
    aria-label="Filter table"
    aria-haspopup="dialog"
    aria-controls="{{ $modalId }}"
    @click="$dispatch('open-modal', '{{ $modalId }}')"
    class="v-text-sm v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500 v-whitespace-nowrap"
>
    Filter
    @if($activeCount > 0)
        <span class="v-ml-1 v-inline-flex v-items-center v-justify-center v-min-w-5 v-h-5 v-rounded-full v-bg-primary-100 dark:v-bg-primary-900 v-text-primary-800 dark:v-text-primary-200 v-text-xs">
            {{ $activeCount }}
        </span>
    @endif
</x-v-button.light>

@if($clearUrl)
    <x-v-button.light
        :href="$clearUrl"
        outline
        class="v-text-sm"
    >
        Clear
    </x-v-button.light>
@endif

<template x-teleport="body">
    <x-v-modal :id="$modalId" maxWidth="2xl">
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
                        $multiple = !empty($col['multiple']);
                        $valueArray = $multiple ? (array) $value : null;
                    @endphp

                    @if ($type === 'checkbox')
                        <x-v-form.checkbox
                            :name="$key"
                            :label="$label"
                            value="1"
                            :checked="!!$value"
                            :id="$modalId . '-' . $key"
                            class="v-mb-0"
                        />
                    @elseif ($type === 'select')
                        <x-v-form.select
                            :name="$multiple ? $key . '[]' : $key"
                            :label="$label"
                            :options="$options ?? []"
                            :selected="$multiple ? $valueArray : $value"
                            :multiple="$multiple"
                            :first_empty="false"
                        />
                    @else
                        <x-v-form.input
                            :name="$key"
                            :label="$label"
                            :type="$type === 'date' ? 'date' : ($type === 'number' ? 'number' : 'text')"
                            :value="$value"
                            :placeholder="$placeholder"
                            :id="$modalId . '-' . $key"
                            class="v-mb-0"
                        />
                    @endif
                @endforeach
            </div>

            <div class="v-mt-5 sm:v-mt-4 v-flex v-justify-between">
                <x-v-button.light
                    type="button"
                    @click="$dispatch('close-modal')"
                    class="v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500"
                >
                    Cancel
                </x-v-button.light>
                <div class="v-flex v-gap-2">
                    @if($clearUrl)
                        <x-v-button.danger
                            :href="$clearUrl"
                            outline
                        >
                            Reset
                        </x-v-button.danger>
                    @endif
                    <x-v-button.primary type="submit">
                        Apply
                    </x-v-button.primary>
                </div>
            </div>
        </form>
    </x-v-modal>
</template>

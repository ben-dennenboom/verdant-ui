@props([
    'text' => '',
    'position' => 'top', // top, bottom, left, right
])

@php
    $positionClasses = [
        'top' => 'v-bottom-full v-left-1/2 v-translate-x-1/2 mb-2',
        'bottom' => 'v-top-full v-left-1/2 v-translate-x-1/2 mt-2',
        'left' => 'v-right-full v-top-1/2 v-translate-y-1/2 mr-2',
        'right' => 'v-left-full v-top-1/2 v-translate-y-1/2 ml-2',
    ];

    $arrowClasses = [
        'top' => 'v-top-full v-left-1/2 v-translate-x-1/2 v-mt-1 v-border-t-gray-800',
        'bottom' => 'v-bottom-full v-left-1/2 v-translate-x-1/2 v-mb-1 v-border-b-gray-800',
        'left' => 'v-left-full v-top-1/2 v-translate-y-1/2 v-ml-1 v-border-l-gray-800',
        'right' => 'v-right-full v-top-1/2 v-translate-y-1/2 v-mr-1 v-border-r-gray-800',
    ];
@endphp

<div x-data="{ show: false }" @mouseleave="show = false" class="v-relative v-inline-flex">
    <div @mouseenter="show = true" class="v-inline-flex">
        {{ $slot }}
    </div>

    <template x-if="show && '{{ $text }}'">
        <div class="v-absolute v-z-50 {{ $positionClasses[$position] }}">
            <div class="v-relative">
                <div class="v-px-3 v-py-2 v-text-sm v-text-white v-bg-gray-800 v-whitespace-nowrap">
                    {{ $text }}
                </div>
                <div class="v-absolute v-w-2 v-h-2 v-rotate-45 v-bg-gray-800 {{ $arrowClasses[$position] }}"></div>
            </div>
        </div>
    </template>
</div>

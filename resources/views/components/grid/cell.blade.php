@props([
    'class' => '',
    'span' => 1,
    'nowrap' => false,
    'label' => null,
    'hideable' => false,
    'id' => null,
])

@php
    /**
     * COLUMN IDENTIFICATION
     * We prioritize the ID, fallback to the label for visibility state tracking.
     */
    $columnId = $id ?? $label;

    /**
     * GRID SPANNING LOGIC
     * Maps the numeric span prop to the utility classes defined in grid-utils.css.
     */
    $spanClasses = [
        1 => 'v-col-span-1',
        2 => 'v-col-span-2',
        3 => 'v-col-span-3',
        4 => 'v-col-span-4',
        5 => 'v-col-span-5',
        6 => 'v-col-span-6',
        7 => 'v-col-span-7',
        8 => 'v-col-span-8',
        'full' => 'v-col-span-full',
    ];

    $spanClass = $spanClasses[$span] ?? null;
    $wrapClass = $nowrap ? 'v-whitespace-nowrap' : 'v-text-wrap';
@endphp

<div @if ($hideable) x-show="isColumnVisible('{{ $columnId }}')"
        x-cloak @endif
    data-column-id="{{ $columnId }}" data-span="{{ $span }}"
    {{ $attributes->merge([
        'class' => "v-grid-cell v-group v-hideable-column v-px-6 v-py-4 v-font-medium v-text-gray-900 dark:v-text-gray-100 $wrapClass $spanClass $class",
    ]) }}
    @if (!$spanClass && $span > 1) style="grid-column: span {{ $span }} / span {{ $span }};" @endif>
    <div class="v-cell-wrapper">
        @if ($label)
            <span class="v-cell-tileview-label">
                {{ $label }}
            </span>
        @endif

        <div class="v-cell-content">
            {{ $slot }}
        </div>
    </div>
</div>

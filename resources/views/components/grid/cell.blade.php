@props(['class' => '', 'span' => 1, 'nowrap' => false, 'label' => null])

@php
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

<div {{ $attributes->merge(['class' => "v-grid-cell v-px-6 v-py-4 v-font-medium v-text-gray-900 dark:v-text-gray-100 v-border-b v-border-gray-200 dark:v-border-gray-700 group-hover:v-bg-gray-50 dark:group-hover:v-bg-[#2d3441] $wrapClass $spanClass $class"]) }}
     @if(!$spanClass && $span > 1) style="grid-column: span {{ $span }} / span {{ $span }};" @endif>
    
  <div class="v-cell-wrapper">
    @if($label)
        <span class="v-cell-tileview-label">
            {{ $label }}
        </span>
    @endif

    <div class="v-cell-content">
        {{ $slot }}
    </div>
  </div>
</div>

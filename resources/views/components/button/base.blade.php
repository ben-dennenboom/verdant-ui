@props([
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconRight' => null,
    'outline' => true,
    'tooltip' => null,
    'tooltipPosition' => 'top',
    'newWindow' => false,
])

@php
    $baseClasses = 'v-rounded inline-flex v-items-center v-justify-center v-px-4 v-py-2 v-border v-text-base v-font-medium focus:v-outline-none v-transition v-ease-in-out v-duration-150';
    $tag = $href ? 'a' : 'button';
    $attributes = $attributes->merge([
        'type' => $tag === 'button' ? $type : null,
        'href' => htmlspecialchars_decode($href),
        'disabled' => $disabled,
        'target' => $newWindow && $href ? '_blank' : null,
        'rel' => $newWindow && $href ? 'noopener noreferrer' : null,
    ])->class([$baseClasses]);

    $hasText = trim($slot) !== '';
@endphp

@if($tooltip)
    <x-v-tooltip :text="$tooltip" :position="$tooltipPosition">
        <{{ $tag }} {{ $attributes }}>
        @if($icon)
            <i class="fas fa-{{ $icon }} {{ $hasText ? 'v-mr-2' : '' }}"></i>
        @endif
        {{ $slot }}
        @if($iconRight)
            <i class="fas fa-{{ $iconRight }} {{ $hasText ? 'v-ml-2' : '' }}"></i>
        @endif
    </{{ $tag }}>
    </x-v-tooltip>
@else
    <{{ $tag }} {{ $attributes }}>
    @if($icon)
        <i class="fas fa-{{ $icon }} {{ $hasText ? 'v-mr-2' : '' }}"></i>
    @endif
    {{ $slot }}
    @if($iconRight)
        <i class="fas fa-{{ $iconRight }} {{ $hasText ? 'v-ml-2' : '' }}"></i>
    @endif
    </{{ $tag }}>
@endif

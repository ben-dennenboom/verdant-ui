@props([
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconRight' => null,
    'outline' => true,
    'tooltip' => null,
    'tooltipPosition' => 'top',
    'newWindow' => false,
])

<x-v-button.base
    {{ $attributes->merge([
        'class' => $outline
            ? 'v-bg-transparent v-border-red-700 v-text-red-700 hover:v-bg-red-700 hover:v-text-white focus:v-ring-red-500' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-red-700 v-border-transparent v-text-white hover:v-bg-red-800 focus:v-ring-red-500' . ($disabled ? ' v-cursor-not-allowed' : '')
    ]) }}
    :href="$href"
    :disabled="$disabled"
    :icon="$icon"
    :icon-right="$iconRight"
    :outline="$outline"
    :tooltip="$tooltip"
    :tooltip-position="$tooltipPosition"
    :new-window="$newWindow"
>
    {{ $slot }}
</x-v-button.base>

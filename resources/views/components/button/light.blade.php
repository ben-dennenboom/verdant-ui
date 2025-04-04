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
            ? 'v-bg-transparent v-border-secondary-500 v-text-secondary-800 hover:v-bg-secondary-700 hover:v-text-white focus:v-border-secondary-800' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-white v-border-secondary-500 v-text-secondary-800 hover:v-bg-secondary-200 focus:v-border-secondary-800' . ($disabled ? ' v-cursor-not-allowed' : '')
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

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
            ? 'v-bg-transparent v-border-secondary-700 dark:v-border-secondary-600 v-text-secondary-700 dark:v-text-secondary-400 hover:v-bg-secondary-700 dark:hover:v-bg-secondary-500 hover:v-text-white focus:v-ring-secondary-500' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-secondary-700 dark:v-bg-secondary-600 v-border-transparent v-text-white hover:v-bg-secondary-800 dark:hover:v-bg-secondary-500 focus:v-ring-secondary-500' . ($disabled ? ' v-cursor-not-allowed' : '')
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

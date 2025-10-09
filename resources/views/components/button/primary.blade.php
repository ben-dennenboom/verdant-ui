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
            ? 'v-bg-transparent v-border-primary-700 dark:v-border-primary-600 v-text-primary-700 dark:v-text-primary-400 hover:v-bg-primary-700 dark:hover:v-bg-primary-500 hover:v-text-white focus:v-ring-primary-500' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-primary-700 dark:v-bg-primary-600 v-border-transparent v-text-white dark:v-text-white hover:v-bg-primary-800 dark:hover:v-text-black dark:hover:v-bg-primary-500 focus:v-ring-primary-500' . ($disabled ? ' v-cursor-not-allowed' : '')
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

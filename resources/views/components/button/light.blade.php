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
            ? 'v-bg-transparent v-border-secondary-500 dark:v-border-secondary-600 v-text-secondary-800 dark:v-text-secondary-300 hover:v-bg-secondary-700 dark:hover:v-bg-secondary-500 hover:v-text-white focus:v-border-secondary-800' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-white dark:v-bg-gray-700 v-border-secondary-500 dark:v-border-secondary-600 v-text-secondary-800 dark:v-text-secondary-200 hover:v-bg-secondary-200 dark:hover:v-text-black dark:hover:v-bg-gray-600 focus:v-border-secondary-800' . ($disabled ? ' v-cursor-not-allowed' : '')
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

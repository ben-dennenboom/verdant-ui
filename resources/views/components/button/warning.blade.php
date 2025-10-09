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
            ? 'v-bg-transparent v-border-yellow-600 dark:v-border-yellow-500 v-text-yellow-600 dark:v-text-yellow-400 hover:v-bg-yellow-600 dark:hover:v-bg-yellow-500 hover:v-text-white dark:hover:v-text-white focus:v-ring-yellow-400' . ($disabled ? ' v-cursor-not-allowed' : '')
            : 'v-bg-yellow-600 dark:v-bg-yellow-500 v-border-transparent v-text-white dark:v-text-white hover:v-bg-yellow-700 dark:hover:v-bg-yellow-400 focus:v-ring-yellow-400' . ($disabled ? ' v-cursor-not-allowed' : '')
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

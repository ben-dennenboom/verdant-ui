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
        'class' => 'v-bg-transparent v-border-transparent v-text-secondary-700 dark:v-text-secondary-300 hover:v-text-secondary-900 dark:hover:v-text-black focus:v-ring-0 focus:v-ring-offset-0 v-px-0 v-py-0 v-m-0 focus:v-border-transparent' . ($disabled ? 'v-cursor-not-allowed' : '')
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

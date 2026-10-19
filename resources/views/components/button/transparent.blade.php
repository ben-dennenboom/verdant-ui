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
        'class' => 'v:bg-transparent v:border-transparent v:text-secondary-700 v:dark:text-secondary-300 v:hover:text-secondary-900 v:dark:hover:text-white v:focus:ring-0 v:focus:ring-offset-0 v:px-0 v:py-0 v:m-0 v:focus:border-transparent' . ($disabled ? 'v:cursor-not-allowed' : '')
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

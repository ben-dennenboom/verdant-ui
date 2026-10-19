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
            ? 'v:bg-transparent v:border-secondary-700 v:dark:border-secondary-600 v:text-secondary-700 v:dark:text-secondary-400 v:dark:hover:text-black v:hover:bg-secondary-700 v:dark:hover:bg-secondary-500 v:hover:text-white v:focus:ring-secondary-500' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-secondary-700 v:dark:bg-secondary-600 v:border-transparent v:text-white v:dark:text-white v:hover:bg-secondary-800 v:dark:hover:bg-secondary-500 v:focus:ring-secondary-500' . ($disabled ? ' v:cursor-not-allowed' : '')
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

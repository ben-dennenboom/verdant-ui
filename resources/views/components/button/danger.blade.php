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
            ? 'v:bg-transparent v:border-red-700 v:dark:border-red-600 v:text-red-700 v:dark:text-red-400 v:dark:hover:text-black v:hover:bg-red-700 v:dark:hover:bg-red-500 v:hover:text-white v:focus:ring-red-500' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-red-700 v:dark:bg-red-600 v:border-transparent v:text-white v:hover:bg-red-800 v:dark:hover:bg-red-500 v:focus:ring-red-500' . ($disabled ? ' v:cursor-not-allowed' : '')
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

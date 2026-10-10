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
            ? 'v:bg-transparent v:border-secondary-500 v:dark:border-secondary-600 v:text-secondary-800 v:dark:text-secondary-300 v:hover:bg-secondary-700 v:dark:hover:bg-secondary-500 v:dark:hover:text-black v:hover:text-white v:focus:border-secondary-800' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-white v:dark:bg-gray-700 v:border-secondary-500 v:dark:border-secondary-600 v:text-secondary-800 v:dark:text-secondary-200 v:hover:bg-secondary-200 v:dark:hover:bg-gray-600 v:focus:border-secondary-800' . ($disabled ? ' v:cursor-not-allowed' : '')
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

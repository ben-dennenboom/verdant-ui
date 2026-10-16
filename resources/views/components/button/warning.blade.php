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
            ? 'v:bg-transparent v:border-yellow-600 v:dark:border-yellow-500 v:text-yellow-600 v:dark:text-yellow-400 v:hover:bg-yellow-600 v:dark:hover:text-black v:dark:hover:bg-yellow-500 v:hover:text-white v:focus:ring-yellow-400' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-yellow-600 v:dark:bg-yellow-500 v:border-transparent v:text-white v:dark:text-white v:hover:bg-yellow-700 v:dark:hover:bg-yellow-400 v:focus:ring-yellow-400' . ($disabled ? ' v:cursor-not-allowed' : '')
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

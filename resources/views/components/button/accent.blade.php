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

<x-verdant::button.base
    {{ $attributes->merge([
        'class' => $outline
            ? 'v:bg-transparent v:border-accent-700 v:dark:border-accent-600 v:text-accent-700 v:dark:text-accent-400 v:dark:hover:text-black v:hover:bg-accent-700 v:dark:hover:bg-accent-500 v:hover:text-white v:focus:ring-accent-500' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-accent-700 v:dark:bg-accent-600 v:border-transparent v:text-white v:hover:bg-accent-800 v:dark:hover:bg-accent-500 v:focus:ring-accent-500' . ($disabled ? ' v:cursor-not-allowed' : '')
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
</x-verdant::button.base>

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
            ? 'v:bg-transparent v:border-primary-700 v:dark:border-primary-600 v:text-primary-700 v:dark:text-primary-400 v:hover:bg-primary-700 v:dark:hover:text-black v:dark:hover:bg-primary-500 v:hover:text-white v:focus:ring-primary-500' . ($disabled ? ' v:cursor-not-allowed' : '')
            : 'v:bg-primary-700 v:dark:bg-primary-600 v:border-transparent v:text-white v:dark:text-white v:hover:bg-primary-800 v:dark:hover:bg-primary-500 v:focus:ring-primary-500' . ($disabled ? ' v:cursor-not-allowed' : '')
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

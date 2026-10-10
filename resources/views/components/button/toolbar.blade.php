@props([
    'tooltip' => null,
    'tooltipPosition' => 'top',
])

<div {{ $attributes->merge(['class' => 'v:inline-block v:cursor-pointer']) }}>
    @if ($tooltip)
        <x-v-tooltip :text="$tooltip" :position="$tooltipPosition">
            <button type="button"
                class="v:px-6 v:py-5 v:rounded-lg v:border v:border-gray-200 v:bg-white v:hover:bg-gray-100 v:transition-colors v:duration-150 v:dark:bg-gray-700 v:dark:border-gray-600 v:dark:hover:bg-gray-600 v:w-full">
                {{ $slot }}
            </button>
        </x-v-tooltip>
    @else
        <button type="button"
            class="v:px-6 v:py-5 v:rounded-lg v:border v:border-gray-200 v:bg-white v:hover:bg-gray-100 v:transition-colors v:duration-150 v:dark:bg-gray-700 v:dark:border-gray-600 v:dark:hover:bg-gray-600">
            {{ $slot }}
        </button>
    @endif
</div>

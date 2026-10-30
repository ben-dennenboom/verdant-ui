@props(['id', 'maxWidth' => '2xl'])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'v:sm:max-w-sm',
        'md' => 'v:sm:max-w-md',
        'lg' => 'v:sm:max-w-lg',
        'xl' => 'v:sm:max-w-xl',
        '2xl' => 'v:sm:max-w-2xl',
        '3xl' => 'v:sm:max-w-3xl',
        '4xl' => 'v:sm:max-w-4xl',
        '5xl' => 'v:sm:max-w-5xl',

    ][$maxWidth];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $id }}') show = true"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="v:fixed v:inset-0 v:z-50"
    style="display: none;"
>
    <div class="v:absolute v:inset-0 v:bg-gray-700/50 v:dark:bg-gray-700/75 v:transition-opacity"
         x-show="show"
         x-transition:enter="v:ease-out v:duration-300"
         x-transition:enter-start="v:opacity-0"
         x-transition:enter-end="v:opacity-100"
         x-transition:leave="v:ease-in v:duration-200"
         x-transition:leave-start="v:opacity-100"
         x-transition:leave-end="v:opacity-0"
         @click="show = false">
    </div>

    <div class="v:fixed v:inset-0 v:z-50 v:overflow-y-auto">
        <div class="v:flex v:min-h-full v:items-center v:justify-center v:p-4 v:text-center v:sm:p-0">
            <div
                x-show="show"
                x-transition:enter="v:ease-out v:duration-300"
                x-transition:enter-start="v:opacity-0 v:translate-y-4 v:sm:translate-y-0 v:sm:scale-95"
                x-transition:enter-end="v:opacity-100 v:translate-y-0 v:sm:scale-100"
                x-transition:leave="v:ease-in v:duration-200"
                x-transition:leave-start="v:opacity-100 v:translate-y-0 v:sm:scale-100"
                x-transition:leave-end="v:opacity-0 v:translate-y-4 v:sm:translate-y-0 v:sm:scale-95"
                class="v:relative v:overflow-visible v:bg-floating v:text-left v:shadow-xl v:transition-all v:sm:my-8 v:w-full {{ $maxWidth }} v:sm:w-full v:rounded"
            >
                <div class="v:bg-floating v:p-4 v:sm:p-6 v:text-wrap v:rounded">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

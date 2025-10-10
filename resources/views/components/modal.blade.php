@props(['id', 'maxWidth' => '2xl'])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'sm:v-max-w-sm',
        'md' => 'sm:v-max-w-md',
        'lg' => 'sm:v-max-w-lg',
        'xl' => 'sm:v-max-w-xl',
        '2xl' => 'sm:v-max-w-2xl',
    ][$maxWidth];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $id }}') show = true"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="v-fixed v-inset-0 v-z-50"
    style="display: none;"
>
    <div class="v-absolute v-inset-0 v-bg-gray-700 v-bg-opacity-75 dark:v-bg-opacity-80 v-transition-opacity"
         x-show="show"
         x-transition:enter="v-ease-out v-duration-300"
         x-transition:enter-start="v-opacity-0"
         x-transition:enter-end="v-opacity-100"
         x-transition:leave="v-ease-in v-duration-200"
         x-transition:leave-start="v-opacity-100"
         x-transition:leave-end="v-opacity-0"
         @click="show = false">
    </div>

    <div class="v-fixed v-inset-0 v-z-50 v-overflow-y-auto">
        <div class="v-flex v-min-h-full v-items-center v-justify-center v-p-4 v-text-center sm:v-p-0">
            <div
                x-show="show"
                x-transition:enter="v-ease-out v-duration-300"
                x-transition:enter-start="v-opacity-0 v-translate-y-4 sm:v-translate-y-0 sm:v-scale-95"
                x-transition:enter-end="v-opacity-100 v-translate-y-0 sm:v-scale-100"
                x-transition:leave="v-ease-in v-duration-200"
                x-transition:leave-start="v-opacity-100 v-translate-y-0 sm:v-scale-100"
                x-transition:leave-end="v-opacity-0 v-translate-y-4 sm:v-translate-y-0 sm:v-scale-95"
                class="v-relative v-transform v-overflow-visible v-bg-white dark:v-bg-gray-800 v-text-left v-shadow-xl v-transition-all sm:v-my-8 v-w-full {{ $maxWidth }} sm:v-w-full v-rounded"
            >
                <div class="v-bg-white dark:v-bg-gray-800 v-p-4 sm:v-p-6 v-text-wrap v-rounded">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

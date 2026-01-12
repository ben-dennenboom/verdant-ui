@props([
    /**
     * @var array $columns - Kolommen voor de Column Manager
     */
    'columns' => [],
])

<div class="v-toolbar-spacer v-w-full v-mb-6">
    <div
        class="v-toolbar-fixed v-flex v-items-center v-justify-end v-gap-3 v-bg-white v-p-4 v-rounded-xl v-shadow-xl v-ring-1 v-ring-black/10 dark:v-bg-gray-800 v-z-30">

        <x-v-grid-toolbar.view-toggle />

        @if (count($columns) > 0)
            <div class="v-flex v-items-center v-border-l v-border-gray-200 v-pl-3 dark:v-border-gray-700">
                <x-v-grid-toolbar.column-manager :columns="$columns" />
            </div>
        @endif

        @if (isset($slot) && !empty(trim($slot)))
            <div class="v-flex v-items-center v-border-l v-border-gray-200 v-pl-3 dark:v-border-gray-700">
                {{ $slot }}
            </div>
        @endif

        <div x-show="hasScroll" x-transition:enter="v-transition v-ease-out v-duration-200"
            x-transition:enter-start="v-opacity-0 v-scale-95" x-transition:enter-end="v-opacity-100 v-scale-100"
            class="v-flex v-items-center v-border-l v-border-gray-200 v-pl-3 dark:v-border-gray-700">
            <x-v-grid-toolbar.scroll-to-end />
        </div>
    </div>
</div>

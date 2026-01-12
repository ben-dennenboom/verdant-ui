@props([
    'columns' => [],
    'gridId' => 'grid', // De ID van het grid waarmee deze toolbar verbonden is
])
<div class="v-toolbar-spacer v-w-full v-mb-6" x-data="{
    gridId: '{{ str_replace(' ', '_', strtolower($gridId)) }}',
    get gridState() {
        return this.$store.grids.get(this.gridId);
    }
}">
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

        <!-- Scroll buttons - gebruikt nu $store.grids -->
        <div x-show="gridState.hasScroll" x-transition:enter="v-transition-all v-duration-200"
            x-transition:leave="v-transition-all v-duration-200"
            class="v-flex v-flex-col v-gap-2 v-border-l v-border-gray-200 v-pl-3 dark:v-border-gray-700">
            <div class="v-flex v-flex-row v-gap-1">
                <!-- Scroll left button -->
                <div x-show="!gridState.isAtStart" x-transition:enter="v-transition-all v-duration-150"
                    x-transition:leave="v-transition-all v-duration-150">
                    <x-v-grid-toolbar.scroll-to direction="start" tooltip="Scroll to start" />
                </div>
                <!-- Scroll right button -->
                <div x-show="!gridState.isAtEnd" x-transition:enter="v-transition-all v-duration-150"
                    x-transition:leave="v-transition-all v-duration-150">
                    <x-v-grid-toolbar.scroll-to direction="end" tooltip="Scroll to end" />
                </div>
            </div>
        </div>
    </div>
</div>

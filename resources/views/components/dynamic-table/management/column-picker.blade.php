@props(['vm', 'columnVisibilityConfig', 'hasFilter' => true])

@php
    $storageKey = $columnVisibilityConfig['storageKey'] ?? 'verdant.table.columns.default';
    $storeKey = $columnVisibilityConfig['storeKey'] ?? 'vtd_default';
    $allKeys = $columnVisibilityConfig['allKeys'] ?? [];
    $pinned = $columnVisibilityConfig['pinned'] ?? ['actions'];
    $defaultVisible = $columnVisibilityConfig['defaultVisible'] ?? null;
    $columnsPanelId = 'columns-panel-' . $storeKey;
    $columns = collect($vm->headers)->map(function ($header, $key) use ($vm, $pinned) {
        $label = is_array($header) ? ($header['label'] ?? $key) : $header;
        $columnKey = $vm->columnKeyForIndex($key);
        $isPinned = is_array($header) && array_key_exists('pinned', $header)
            ? (bool) $header['pinned']
            : in_array($columnKey, $pinned, true);

        return ['key' => $columnKey, 'label' => $label, 'pinned' => $isPinned];
    })->values()->all();
    $popupPosition = $hasFilter ? 'v-left-1/2 -v-translate-x-1/2' : '';
@endphp

<div class="v-shrink-0 v-flex v-items-center v-justify-end">
    <div
        class="v-relative"
        x-data="verdantColumnPicker({
            storeKey: @js($storeKey),
            columns: @js($columns),
            panelId: @js($columnsPanelId)
        })"
        @click.outside="open = false"
    >
        <x-v-button.light
            type="button"
            icon-right="angle-down"
            aria-label="Choose visible columns"
            aria-haspopup="true"
            x-bind:aria-expanded="'open'"
            aria-controls="{{ $columnsPanelId }}"
            @click="open = !open"
            class="v-text-sm v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500 v-whitespace-nowrap"
        >
            Columns
        </x-v-button.light>

        <template x-if="open">
            <div
                :id="panelId"
                role="region"
                aria-label="Visible columns"
                class="v-absolute {{ $popupPosition }} md:v-left-auto md:v-right-0 md:v-translate-x-0 v-z-20 v-mt-2 v-w-60 v-rounded-md v-border v-border-gray-200 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-p-3 v-shadow-lg"
            >
                <div class="v-flex v-items-center v-justify-between v-pb-2">
                    <div class="v-text-sm v-font-semibold v-text-gray-900 dark:v-text-gray-100">Visible columns</div>
                    <x-v-button.transparent
                        type="button"
                        class="v-text-xs v-text-gray-600 dark:v-text-gray-400 hover:v-underline"
                        @click="resetAndClose()"
                    >
                        Reset
                    </x-v-button.transparent>
                </div>

                <div class="v-max-h-64 v-space-y-2 v-overflow-auto v-pl-1 v-pt-1">
                    <template x-for="col in columns" :key="col.key">
                        <label class="v-flex v-items-center v-gap-2 v-text-sm v-cursor-pointer">
                            <input
                                type="checkbox"
                                class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 focus:v-border-primary-500 v-shadow-sm sm:v-text-sm v-bg-white dark:v-bg-gray-800"
                                :checked="isColumnVisible(col.key)"
                                @change="setColumnVisible(col.key, $event.target.checked)"
                                :disabled="col.pinned"
                            />
                            <span :class="col.pinned ? 'v-text-gray-400 dark:v-text-gray-500' : 'v-text-gray-700 dark:v-text-gray-300'" x-text="col.label"></span>
                        </label>
                    </template>
                </div>

                <div class="v-mt-3 v-flex v-gap-2">
                    <x-v-button.light
                        type="button"
                        outline
                        class="v-flex-1 v-text-sm v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500"
                        @click="showAll()"
                    >
                        Show all
                    </x-v-button.light>

                    <x-v-button.light
                        type="button"
                        outline
                        class="v-flex-1 v-text-sm v-border-gray-500 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-200 dark:hover:v-bg-gray-600 focus:v-ring-gray-500"
                        @click="hideAll()"
                    >
                        Hide all
                    </x-v-button.light>
                </div>
            </div>
        </template>
    </div>
</div>

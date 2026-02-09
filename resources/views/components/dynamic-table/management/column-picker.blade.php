@props(['vm', 'columnVisibilityConfig'])

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
@endphp

<div class="v-flex v-items-center v-justify-end">
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
            class="v-text-sm"
        >
            Columns
        </x-v-button.light>

        <template x-if="open">
            <div
                :id="panelId"
                role="region"
                aria-label="Visible columns"
                class="v-absolute v-right-0 v-z-20 v-mt-2 v-w-72 v-rounded-md v-border v-border-gray-200 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-p-3 v-shadow-lg"
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
                                class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 focus:v-border-primary-500 v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 dark:v-border-gray-600"
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
                        class="v-flex-1 v-text-sm"
                        @click="showAll()"
                    >
                        Show all
                    </x-v-button.light>

                    <x-v-button.light
                        type="button"
                        outline
                        class="v-flex-1 v-text-sm"
                        @click="hideAll()"
                    >
                        Hide all
                    </x-v-button.light>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    if (!Alpine.data('verdantColumnPicker')) {
        Alpine.data('verdantColumnPicker', (config) => ({
            open: false,
            storeKey: config.storeKey ?? 'vtd_default',
            columns: config.columns ?? [],
            panelId: config.panelId ?? 'columns-panel-default',

            resetAndClose() {
                const store = Alpine.store(this.storeKey);
                if (store?.reset) store.reset();
                this.open = false;
            },
            isColumnVisible(key) {
                const store = Alpine.store(this.storeKey);

                return store?.isVisible ? store.isVisible(key) : false;
            },
            setColumnVisible(key, checked) {
                const store = Alpine.store(this.storeKey);
                if (store?.setVisible) store.setVisible(key, checked);
            },
            showAll() {
                const store = Alpine.store(this.storeKey);
                if (store?.showAll) store.showAll();
            },
            hideAll() {
                const store = Alpine.store(this.storeKey);
                if (store?.hideAll) store.hideAll();
            },
        }));
    }

    if (Alpine.data('verdantTableColumns')) return;

    Alpine.data('verdantTableColumns', (config) => ({
        storageKey: config.storageKey ?? 'verdant.table.columns.default',
        storeKey: config.storeKey ?? 'vtd_default',
        allKeys: config.allKeys ?? [],
        pinned: config.pinned ?? ['actions'],
        defaultVisible: config.defaultVisible ?? null,
        visible: {},

        init() {
            try {
                const stored = localStorage.getItem(this.storageKey);
                if (stored) {
                    const parsed = JSON.parse(stored);
                    this.visible = { ...parsed };
                    this.allKeys.forEach((key) => {
                        if (!(key in this.visible)) {
                            this.visible[key] = this.visibilityDefaultFor(key);
                        }
                    });
                } else {
                    this.applyDefaults();
                }
            } catch (e) {
                this.applyDefaults();
            }

            Alpine.store(this.storeKey, {
                visibleCount: this.allKeys.filter((k) => this.visible[k] !== false).length,
                isVisible: (k) => this.isVisible(k),
                setVisible: (k, v) => this.setVisible(k, v),
                showAll: () => this.showAll(),
                hideAll: () => this.hideAll(),
                reset: () => this.reset(),
            });
        },

        visibilityDefaultFor(key) {
            const isPinned = this.pinned.includes(key);
            const fromDefault = this.defaultVisible === null
                ? true
                : this.defaultVisible.includes(key);

            return isPinned ? true : fromDefault;
        },

        applyDefaults() {
            this.visible = {};
            this.allKeys.forEach((key) => {
                this.visible[key] = this.visibilityDefaultFor(key);
            });
        },

        syncVisibleCount() {
            if (!Alpine.store || !this.storeKey) return;
            const store = Alpine.store(this.storeKey);
            if (!store) return;
            store.visibleCount = this.allKeys.filter((k) => this.visible[k] !== false).length;
        },

        isVisible(key) {
            return this.visible[key] !== false;
        },

        setVisible(key, bool) {
            this.visible[key] = bool;
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        showAll() {
            this.allKeys.forEach((k) => { this.visible[k] = true; });
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        hideAll() {
            this.allKeys.forEach((k) => {
                if (!this.pinned.includes(k)) this.visible[k] = false;
            });

            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        reset() {
            this.applyDefaults();

            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },
    }));
});
</script>

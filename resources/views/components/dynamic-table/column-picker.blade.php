@props(['vm', 'columnVisibilityConfig'])

@php
    $storageKey = $columnVisibilityConfig['storageKey'] ?? 'verdant.table.columns.default';
    $storeKey = $columnVisibilityConfig['storeKey'] ?? 'vtd_default';
    $allKeys = $columnVisibilityConfig['allKeys'] ?? [];
    $pinned = $columnVisibilityConfig['pinned'] ?? ['actions'];
    $defaultVisible = $columnVisibilityConfig['defaultVisible'] ?? null;
    $columnsPanelId = 'columns-panel-' . $storeKey;
@endphp

<div class="v-flex v-items-center v-justify-end">
    <div class="v-relative" x-data="{ open: false, storeKey: @js($storeKey) }">
        <button
            type="button"
            class="v-inline-flex v-items-center v-gap-2 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-300"
            aria-label="Choose visible columns"
            aria-haspopup="true"
            :aria-expanded="open"
            aria-controls="{{ $columnsPanelId }}"
            @click="open = !open"
        >
            Columns <i class="fa fa-angle-down"></i>
        </button>

        <div
            id="{{ $columnsPanelId }}"
            role="region"
            aria-label="Visible columns"
            x-show="open"
            x-cloak
            @click.outside="open = false"
            x-transition
            class="v-absolute v-right-0 v-z-20 v-mt-2 v-w-72 v-rounded-md v-border v-border-gray-200 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-p-3 v-shadow-lg"
        >
            <div class="v-flex v-items-center v-justify-between v-pb-2">
                <div class="v-text-sm v-font-semibold v-text-gray-900 dark:v-text-gray-100">Visible columns</div>
                <button
                    type="button"
                    class="v-text-xs v-text-gray-600 dark:v-text-gray-400 hover:v-underline"
                    @click="Alpine.store(storeKey).reset(); open = false"
                >
                    Reset
                </button>
            </div>

            <div class="v-max-h-64 v-space-y-2 v-overflow-auto v-pl-1 v-pt-1">
                @foreach($vm->headers as $key => $header)
                    @php
                        $label = is_array($header) ? ($header['label'] ?? $key) : $header;
                        $columnKey = $vm->columnKeyForIndex($key);
                        $isPinned = in_array($columnKey, $pinned, true);
                    @endphp
                    <label class="v-flex v-items-center v-gap-2 v-text-sm v-cursor-pointer">
                        <input
                            type="checkbox"
                            class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600"
                            :checked="Alpine.store(storeKey).isVisible('{{ $columnKey }}')"
                            @change="Alpine.store(storeKey).setVisible('{{ $columnKey }}', $event.target.checked)"
                            {{ $isPinned ? 'disabled' : '' }}
                        />
                        <span class="{{ $isPinned ? 'v-text-gray-400 dark:v-text-gray-500' : 'v-text-gray-700 dark:v-text-gray-300' }}">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>

            <div class="v-mt-3 v-flex v-gap-2">
                <button
                    type="button"
                    class="v-w-1/2 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-px-3 v-py-2 v-text-sm hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-300"
                    @click="Alpine.store(storeKey).showAll()"
                >
                    Show all
                </button>
                <button
                    type="button"
                    class="v-w-1/2 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-px-3 v-py-2 v-text-sm hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-300"
                    @click="Alpine.store(storeKey).hideAll()"
                >
                    Hide all
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
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

            // Expose for nested components (Alpine v3 has no $parent)
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

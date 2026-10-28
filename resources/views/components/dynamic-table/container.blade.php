@props([
    'data' => null,
    'headers' => [],
    'rows' => [],
    'class' => '',
    'emptyText' => null,
    'columnVisibilityKey' => null,
])

@php
    $emptyText ??= __('verdant::table.empty');
    $vm = \Dennenboom\VerdantUI\Tables\DynamicTableViewModel::from(
        $data,
        $headers,
        $rows
    );
    $visibilityKey = $columnVisibilityKey ?? $vm->columnVisibilityKey;
    $allKeys = collect($vm->columnKeys)
        ->map(fn ($key, $i) => $key ?? 'col-' . $i)
        ->values()
        ->all();

    $pinnedFromHeaders = collect($vm->headers)
        ->filter(fn ($h) => !empty($h['pinned']) && $h['pinned'])
        ->map(fn ($h, $i) => $h['key'] ?? $vm->columnKeyForIndex($i))
        ->values()
        ->all();

    $pinnedColumns = $pinnedFromHeaders !== [] ? $pinnedFromHeaders : ['actions'];
    $storeKey = $visibilityKey ? 'vtd_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $visibilityKey) : null;
    $columnOrderEnabled = $vm->columnOrderEnabled && $visibilityKey !== null;
    $columnVisibility = $visibilityKey ? [
        'enabled' => true,
        'storeKey' => $storeKey,
        'orderEnabled' => $columnOrderEnabled,
        'initial' => $vm->initialColumnState($pinnedColumns, $columnOrderEnabled),
    ] : null;
    $columnMeta = collect($vm->headers)->map(function ($header, $key) use ($vm, $pinnedColumns) {
        $label = is_array($header) ? ($header['label'] ?? $key) : $header;
        $columnKey = $vm->columnKeyForIndex($key);
        $isPinned = is_array($header) && array_key_exists('pinned', $header)
            ? (bool) $header['pinned']
            : in_array($columnKey, $pinnedColumns, true);

        return ['key' => $columnKey, 'label' => $label, 'pinned' => $isPinned];
    })->values()->all();
    $columnVisibilityConfig = $visibilityKey ? [
        'storageKey' => 'verdant.table.columns.' . $visibilityKey,
        'orderStorageKey' => 'verdant.table.order.' . $visibilityKey,
        'storeKey' => $storeKey,
        'allKeys' => $allKeys,
        'pinned' => $pinnedColumns,
        'defaultVisible' => $vm->defaultVisibleColumns,
        'initialVisible' => (object) $columnVisibility['initial']['visible'],
        'initialOrder' => $columnVisibility['initial']['order'],
        'columns' => $columnMeta,
        'orderEnabled' => $columnOrderEnabled,
        'saveUrl' => $vm->preferencesSaveUrl,
    ] : null;
    $localColumnConfig = $visibilityKey && !$vm->preferencesSaveUrl ? [
        'storageKey' => $columnVisibilityConfig['storageKey'],
        'orderStorageKey' => $columnOrderEnabled ? $columnVisibilityConfig['orderStorageKey'] : null,
        'allKeys' => $allKeys,
        'pinned' => $pinnedColumns,
        'orderEnabled' => $columnOrderEnabled,
        'initialVisible' => $columnVisibilityConfig['initialVisible'],
        'initialOrder' => $columnVisibilityConfig['initialOrder'],
        'widths' => (object) array_combine($allKeys, $vm->columnGridWidths),
    ] : null;
    $showSearch = !empty($vm->searchableColumns);
    $showFilter = !empty($vm->filterColumns);
    $hasToolbarActions = isset($toolbar) && trim((string) $toolbar) !== '';
    $showToolbar = $showSearch || $visibilityKey || $showFilter || $hasToolbarActions;
    $hasBulkEdit = $vm->hasBulkEdit;
    $bulkStoreKey = $hasBulkEdit ? ('vtbulk_' . ($storeKey ?? uniqid('tbl_'))) : null;

    $stateKey = $visibilityKey ?? (trim(request()->path(), '/') ?: 'root');
    $stateConfig = [
        'storageKey' => 'verdant.table.state.' . $stateKey,
        'filters' => collect($vm->filterColumns ?? [])
            ->map(fn ($f) => ['key' => $f['key'], 'multiple' => !empty($f['multiple'])])
            ->values()
            ->all(),
    ];
@endphp

<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        if (window.verdantTableState) window.verdantTableState(@js($stateConfig));
    });
</script>

@once
    <script>
        window.verdantColumnLayout = {
            reconcileOrder(candidate, fallback) {
                const valid = candidate.filter((k) => fallback.includes(k));

                return [...valid, ...fallback.filter((k) => !valid.includes(k))];
            },
            effectiveOrder(allKeys, pinned, order, orderEnabled) {
                if (!orderEnabled) return allKeys;
                const queue = [...order];

                return allKeys.map((k) => (pinned.includes(k) ? k : queue.shift()));
            },
            gridTemplate(effectiveOrder, visible, widths) {
                return effectiveOrder
                    .filter((k) => visible[k] !== false)
                    .map((k) => widths[k] || 'minmax(0, 1fr)')
                    .join(' ');
            },
            readStored({ storageKey, orderStorageKey, initialVisible, initialOrder }) {
                try {
                    const visible = JSON.parse(localStorage.getItem(storageKey));
                    const order = orderStorageKey ? JSON.parse(localStorage.getItem(orderStorageKey)) : null;
                    if (!visible && !order) return null;

                    return {
                        visible: { ...initialVisible, ...visible },
                        order: order ? this.reconcileOrder(order, initialOrder) : [...initialOrder],
                    };
                } catch {
                    return null;
                }
            },
        };
    </script>
@endonce

@if($hasBulkEdit)
<div x-data="verdantTableBulk({ allRowKeys: @js($vm->allRowKeys), storeKey: @js($bulkStoreKey) })">
@endif

<div
    class="v:rounded v:bg-surface v:border v:border-gray-200 v:dark:border-gray-700 {{ $class }}"
    @if($visibilityKey)
        data-columns-store="{{ $storeKey }}"
        x-data="verdantTableColumns({
            storageKey: @js($columnVisibilityConfig['storageKey']),
            orderStorageKey: @js($columnVisibilityConfig['orderStorageKey']),
            storeKey: @js($columnVisibilityConfig['storeKey']),
            allKeys: @js($columnVisibilityConfig['allKeys']),
            columnWidths: @js($vm->columnGridWidths),
            pinned: @js($columnVisibilityConfig['pinned']),
            defaultVisible: @js($columnVisibilityConfig['defaultVisible']),
            initialVisible: @js($columnVisibilityConfig['initialVisible']),
            initialOrder: @js($columnVisibilityConfig['initialOrder']),
            columns: @js($columnVisibilityConfig['columns']),
            orderEnabled: @js($columnVisibilityConfig['orderEnabled']),
            saveUrl: @js($columnVisibilityConfig['saveUrl']),
            csrfToken: @js(csrf_token()),
        })"
    @else
        x-data
    @endif
>
    @if($showToolbar)
        <div class="v:flex v:flex-col v:md:flex-row v:items-stretch v:md:items-center v:justify-end v:gap-3 v:px-4 v:py-3 v:border-b v:border-gray-200 v:dark:border-gray-700">
            @if($showSearch)
                <div class="v:w-full v:md:flex-1 v:md:min-w-0">
                    @include('verdant::components.dynamic-table.management.search-bar', [
                        'searchTerm' => $vm->searchTerm,
                        'paramName' => 'search',
                        'placeholder' => __('verdant::table.search_placeholder'),
                        'searchApiUrl' => $vm->searchApiUrl,
                    ])
                </div>
            @endif

            @if($showFilter || $visibilityKey)
                <div class="v:flex v:items-center v:gap-2 v:shrink-0">
                    @if($hasToolbarActions)
                        {{ $toolbar }}
                    @endif
                    @if($showFilter)
                        @include('verdant::components.dynamic-table.management.filter-modal', [
                            'vm' => $vm,
                            'modalId' => 'v:dynamic-table-filter-' . ($storeKey ?? 'default'),
                        ])
                    @endif

                    @if($visibilityKey)
                        @include('verdant::components.dynamic-table.management.column-picker', [
                            'vm' => $vm,
                            'columnVisibilityConfig' => $columnVisibilityConfig,
                            'hasFilter' => $showFilter,
                        ])
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="v:hidden v:lg:block">
        <div class="v:min-w-full">
            @include('verdant::components.dynamic-table.header', ['columnVisibility' => $columnVisibility, 'bulkStoreKey' => $bulkStoreKey])
            @include('verdant::components.dynamic-table.body-table', [
                'columnVisibility' => $columnVisibility,
                'emptyText' => $emptyText,
                'bulkStoreKey' => $bulkStoreKey,
            ])
        </div>
    </div>

    <div class="v:block v:lg:hidden v:p-4">
        @include('verdant::components.dynamic-table.body-cards', [
            'columnVisibility' => $columnVisibility,
            'emptyText' => $emptyText,
            'bulkStoreKey' => $bulkStoreKey,
        ])
    </div>

    @include('verdant::components.dynamic-table.pagination')
</div>

@if($localColumnConfig)
    <script>
        (() => {
            const config = @js($localColumnConfig);
            const layout = window.verdantColumnLayout;
            const stored = layout.readStored(config);
            if (!stored) return;

            const table = document.currentScript.previousElementSibling;
            const order = layout.effectiveOrder(config.allKeys, config.pinned, stored.order, config.orderEnabled);
            const grid = layout.gridTemplate(order, stored.visible, config.widths);

            table.querySelectorAll('[data-grid-prefix]').forEach((el) => {
                el.style.gridTemplateColumns = el.dataset.gridPrefix + grid;
            });

            table.querySelectorAll('[data-column-key]').forEach((el) => {
                el.style.display = stored.visible[el.dataset.columnKey] === false ? 'none' : '';
                if (config.orderEnabled) el.style.order = order.indexOf(el.dataset.columnKey);
            });
        })();
    </script>
@endif

@if($hasBulkEdit)
    @include('verdant::components.dynamic-table.bulk-bar', ['vm' => $vm, 'bulkStoreKey' => $bulkStoreKey])
</div>
@endif

@once
    <script src="{{ asset('vendor/verdant/js/dynamic-table.js') }}" type="module"></script>

@endonce

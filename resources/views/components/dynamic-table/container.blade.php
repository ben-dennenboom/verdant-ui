@props([
    'data' => null,
    'headers' => [],
    'rows' => [],
    'class' => '',
    'emptyText' => 'No data available.',
    'columnVisibilityKey' => null,
])

@php
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
    $columnVisibility = $visibilityKey ? ['enabled' => true, 'storeKey' => $storeKey, 'orderEnabled' => $columnOrderEnabled] : null;
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
        'storedVisible' => $vm->storedVisibleColumns,
        'columns' => $columnMeta,
        'orderEnabled' => $columnOrderEnabled,
        'defaultOrder' => $vm->defaultColumnOrder,
        'saveUrl' => $vm->preferencesSaveUrl,
    ] : null;
    $showSearch = !empty($vm->searchableColumns);
    $showFilter = !empty($vm->filterColumns);
    $showToolbar = $showSearch || $visibilityKey || $showFilter;
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.verdantTableState) window.verdantTableState(@js($stateConfig));
    });
</script>

@if($hasBulkEdit)
<div x-data="verdantTableBulk({ allRowKeys: @js($vm->allRowKeys), storeKey: @js($bulkStoreKey) })">
@endif

<div
    class="v-rounded v-bg-surface v-border v-border-gray-200 dark:v-border-gray-700 {{ $class }}"
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
            storedVisible: @js($columnVisibilityConfig['storedVisible']),
            columns: @js($columnVisibilityConfig['columns']),
            orderEnabled: @js($columnVisibilityConfig['orderEnabled']),
            defaultOrder: @js($columnVisibilityConfig['defaultOrder']),
            saveUrl: @js($columnVisibilityConfig['saveUrl']),
            csrfToken: @js(csrf_token()),
        })"
    @endif
>
    @if($showToolbar)
        <div class="v-flex v-flex-col md:v-flex-row v-items-stretch md:v-items-center v-justify-between v-gap-3 v-px-4 v-py-3 v-border-b v-border-gray-200 dark:v-border-gray-700">
            @if($showSearch)
                <div class="v-w-full md:v-flex-1 md:v-min-w-0">
                    @include('verdant::components.dynamic-table.management.search-bar', [
                        'searchTerm' => $vm->searchTerm,
                        'paramName' => 'search',
                        'placeholder' => 'Search…',
                        'searchApiUrl' => $vm->searchApiUrl,
                    ])
                </div>
            @endif

            @if($showFilter || $visibilityKey)
                <div class="v-flex v-items-center v-gap-2 v-shrink-0">
                    @if($showFilter)
                        @include('verdant::components.dynamic-table.management.filter-modal', [
                            'vm' => $vm,
                            'modalId' => 'v-dynamic-table-filter-' . ($storeKey ?? 'default'),
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

    <div class="v-hidden lg:v-block">
        <div class="v-min-w-full">
            @include('verdant::components.dynamic-table.header', ['columnVisibility' => $columnVisibility, 'bulkStoreKey' => $bulkStoreKey])
            @include('verdant::components.dynamic-table.body-table', [
                'columnVisibility' => $columnVisibility,
                'emptyText' => $emptyText,
                'bulkStoreKey' => $bulkStoreKey,
            ])
        </div>
    </div>

    <div class="v-block lg:v-hidden v-p-4">
        @include('verdant::components.dynamic-table.body-cards', [
            'columnVisibility' => $columnVisibility,
            'emptyText' => $emptyText,
            'bulkStoreKey' => $bulkStoreKey,
        ])
    </div>

    @include('verdant::components.dynamic-table.pagination')
</div>

@if($hasBulkEdit)
    @include('verdant::components.dynamic-table.bulk-bar', ['vm' => $vm, 'bulkStoreKey' => $bulkStoreKey])
</div>
@endif

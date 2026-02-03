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
    $pinnedColumns = ['actions'];
    $allKeys = collect($vm->columnKeys)->map(fn ($key, $i) => $key ?? 'col-' . $i)->values()->all();
    $storeKey = $visibilityKey ? 'vtd_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $visibilityKey) : null;
    $columnVisibility = $visibilityKey ? ['enabled' => true, 'storeKey' => $storeKey] : null;
    $columnVisibilityConfig = $visibilityKey ? [
        'storageKey' => 'verdant.table.columns.' . $visibilityKey,
        'storeKey' => $storeKey,
        'allKeys' => $allKeys,
        'pinned' => $pinnedColumns,
        'defaultVisible' => $vm->defaultVisibleColumns,
    ] : null;
    $showSearch = !empty($vm->searchableColumns);
    $showToolbar = $showSearch || $visibilityKey;
@endphp

<div
    class="v-rounded v-bg-white dark:v-bg-gray-800 v-border {{ $class }}"
    @if($visibilityKey)
        data-columns-store="{{ $storeKey }}"
        x-data="verdantTableColumns({
            storageKey: @js($columnVisibilityConfig['storageKey']),
            storeKey: @js($columnVisibilityConfig['storeKey']),
            allKeys: @js($columnVisibilityConfig['allKeys']),
            pinned: @js($columnVisibilityConfig['pinned']),
            defaultVisible: @js($columnVisibilityConfig['defaultVisible']),
        })"
    @endif
>
    @if($showToolbar)
        <div class="v-flex v-items-center v-justify-between v-gap-4 v-px-4 v-py-3 v-border-b v-border-gray-200 dark:v-border-gray-700">
            @if($showSearch)
                @include('verdant::components.dynamic-table.search-bar', [
                    'searchTerm' => $vm->searchTerm,
                    'paramName' => 'search',
                    'placeholder' => 'Search…',
                    'searchApiUrl' => $vm->searchApiUrl,
                ])
            @endif

            @if($visibilityKey)
                @include('verdant::components.dynamic-table.column-picker', [
                    'vm' => $vm,
                    'columnVisibilityConfig' => $columnVisibilityConfig,
                ])
            @endif
        </div>
    @endif

    <div class="v-hidden lg:v-block v-overflow-x-auto">
        <div class="v-min-w-full">
            @include('verdant::components.dynamic-table.header', ['columnVisibility' => $columnVisibility])
            @include('verdant::components.dynamic-table.body-table', ['columnVisibility' => $columnVisibility])
        </div>
    </div>

    <div class="v-block lg:v-hidden v-p-4">
        @include('verdant::components.dynamic-table.body-cards', [
            'columnVisibility' => $columnVisibility,
            'emptyText' => $emptyText,
        ])
    </div>

    @include('verdant::components.dynamic-table.pagination')
</div>

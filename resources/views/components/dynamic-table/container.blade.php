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
    $vm->columnVisibilityKey = $columnVisibilityKey ?? $vm->columnVisibilityKey;
    $showSearch = !empty($vm->searchableColumns);
    $showFilter = !empty($vm->filterColumns);
    $hasToolbarActions = isset($toolbar) && trim((string) $toolbar) !== '';
    $showColumnPicker = !is_null($vm->columnVisibilityKey);
    $showToolbar = $showSearch || $showFilter || $hasToolbarActions || $showColumnPicker;
    $bulkFormId = $vm->hasBulkEdit ? 'v-bulk-' . ($vm->columnVisibilityKey ? preg_replace('/[^a-zA-Z0-9_-]/', '-', $vm->columnVisibilityKey) : uniqid()) : null;
    $persistKey = $vm->columnVisibilityKey ?? (trim(request()->path(), '/') ?: 'root');
    $persistFilters = collect($vm->filterColumns ?? [])
        ->map(fn ($f) => ['key' => $f['key'], 'multiple' => !empty($f['multiple'])])
        ->values()
        ->all();
    $persistColumns = $showColumnPicker && $vm->preferencesSaveUrl === null;
    $bulkPrefix = $vm->hasBulkEdit ? '3rem ' : '';
@endphp

@once
    <script>{!! \Dennenboom\VerdantUI\VerdantUI::inlineAsset('js/dynamic-table-persist.js') !!}</script>
@endonce

<div
    class="v:rounded v:bg-surface v:border v:border-gray-200 v:dark:border-gray-700 {{ $class }}"
    data-v-table
    data-v-persist="{{ $persistKey }}"
    data-v-persist-filters="{{ json_encode($persistFilters) }}"
    @if($showColumnPicker) data-v-columns="{{ json_encode($vm->columnPickerColumns()) }}" @endif
    @if($persistColumns) data-v-persist-columns @endif
    @if($persistColumns && $vm->columnOrderEnabled) data-v-persist-order @endif
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

            @if($showFilter || $hasToolbarActions || $showColumnPicker)
                <div class="v:flex v:items-center v:gap-2 v:shrink-0">
                    @if($hasToolbarActions)
                        {{ $toolbar }}
                    @endif
                    @if($showFilter)
                        @include('verdant::components.dynamic-table.management.filter-modal', [
                            'vm' => $vm,
                            'modalId' => 'v:dynamic-table-filter-' . ($vm->columnVisibilityKey ?? 'default'),
                        ])
                    @endif
                    @if($showColumnPicker)
                        @include('verdant::components.dynamic-table.management.column-picker', [
                            'vm' => $vm,
                            'hasFilter' => $showFilter,
                        ])
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="v:hidden v:lg:block">
        <div
            class="v:grid v:min-w-full"
            data-v-grid="{{ $bulkPrefix }}"
            data-v-default-track="{{ \Dennenboom\VerdantUI\Tables\DynamicTableViewModel::DEFAULT_TRACK }}"
            style="grid-template-columns: {{ $bulkPrefix }}{{ $vm->visibleGridTemplateColumns() }};"
        >
            @include('verdant::components.dynamic-table.header')
            @include('verdant::components.dynamic-table.body-table', [
                'emptyText' => $emptyText,
                'bulkFormId' => $bulkFormId,
            ])
        </div>
    </div>

    <div class="v:block v:lg:hidden v:p-4">
        @include('verdant::components.dynamic-table.body-cards', [
            'emptyText' => $emptyText,
            'bulkFormId' => $bulkFormId,
        ])
    </div>

    @include('verdant::components.dynamic-table.pagination')
</div>

<script>window.verdantTablePersist?.(document.currentScript.previousElementSibling)</script>

@if($vm->hasBulkEdit)
    @include('verdant::components.dynamic-table.bulk-bar', ['vm' => $vm, 'bulkFormId' => $bulkFormId])
@endif

@if($showColumnPicker || $vm->hasBulkEdit || $vm->rowOpenTrigger() || ($showSearch && $vm->searchApiUrl))
    @once
        <script src="{{ asset('vendor/verdant/js/dynamic-table.js') }}" type="module"></script>
    @endonce
@endif

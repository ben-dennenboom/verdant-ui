@php
    $bulkPrefix = $vm->hasBulkEdit ? '3rem ' : '';
    $rowIx = !$vm->hasBulkEdit && ($vm->rowInteractionEnabled ?? false);
    $bsk = $bulkStoreKey ?? null; // shorthand for use inside Alpine expressions
@endphp

@if($rowIx)
    <div
        x-data="{
            selectedRowKey: null,
            selectRow(key) { this.selectedRowKey = key; },
            isSelected(key) { return this.selectedRowKey !== null && String(this.selectedRowKey) === String(key); },
            openRow(url) { if (url) window.location.assign(url); }
        }"
    >
@endif

@forelse ($vm->rows as $row)
    <div
        @if($vm->hasBulkEdit && $row->rowKey !== null)
            x-data="{ rowHovered: false }"
            @mouseenter="rowHovered = true"
            @mouseleave="rowHovered = false"
            class="v-grid v-items-center v-cursor-default"
            @click="if ($event.shiftKey) { $store[@js($bsk)].toggle(@js($row->rowKey)); $event.preventDefault(); }"
            @dblclick="$store[@js($bsk)].openRow(@js($row->openUrl))"
            :class="$store[@js($bsk)].isSelected(@js($row->rowKey))
                ? 'v-bg-primary-50 dark:v-bg-primary-900/20 hover:v-bg-primary-100 dark:hover:v-bg-primary-900/30'
                : 'hover:v-bg-gray-50 dark:hover:v-bg-gray-700'"
        @elseif($rowIx && $row->rowKey !== null)
            class="v-grid v-items-center v-cursor-pointer"
            @click="selectRow(@js($row->rowKey))"
            @dblclick="openRow(@js($row->openUrl))"
            :class="isSelected(@js($row->rowKey)) ? 'v-bg-blue-50 dark:v-bg-blue-900/20 hover:v-bg-blue-100 dark:hover:v-bg-blue-900/30' : 'hover:v-bg-gray-50 dark:hover:v-bg-gray-700'"
        @else
            class="v-grid hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-items-center"
        @endif
        @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
            :style="'grid-template-columns: {{ $bulkPrefix }}' + (Alpine.store('{{ $columnVisibility['storeKey'] }}')?.gridTemplateColumns ?? @js($vm->gridTemplateColumns))"
        @else
            @if($vm->gridTemplateColumns !== '')
                style="grid-template-columns: {{ $bulkPrefix }}{{ $vm->gridTemplateColumns }};"
            @else
                style="grid-template-columns: {{ $bulkPrefix }}repeat({{ $vm->columnCount }}, minmax(0,1fr));"
            @endif
        @endif
    >
        {{-- Bulk checkbox cell --}}
        @if($vm->hasBulkEdit)
            <div
                class="v-pl-3 v-pr-1 v-py-4 v-flex v-items-center v-justify-center"
                @click.stop
                @dblclick.stop
            >
                @if($row->rowKey !== null)
                    <input
                        type="checkbox"
                        x-show="rowHovered || $store[@js($bsk)].selected.length > 0"
                        :checked="$store[@js($bsk)].isSelected(@js($row->rowKey))"
                        @change="$store[@js($bsk)].toggle(@js($row->rowKey))"
                        class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 v-bg-white dark:v-bg-gray-700 v-cursor-pointer"
                    >
                @endif
            </div>
        @endif

        @foreach ($row->cells as $key => $cell)
            @php
                $columnKey = $vm->columnKeyForIndex($loop->index);
                $header    = $vm->headers[$loop->index] ?? [];
                $alignClass = match ($header['align'] ?? '') {
                    'center' => 'v-text-center',
                    'right'  => 'v-text-right',
                    default  => 'v-text-left',
                };
            @endphp
            <div class="
                v-px-6 v-py-4 @if($key === 0 && $vm->hasBulkEdit) v-pl-3 @endif v-text-sm
                v-text-gray-900 dark:v-text-gray-300 {{ $alignClass }} {{ $cell->class }}
            "
                @if(($rowIx || $vm->hasBulkEdit) && $cell->isActions) @click.stop @dblclick.stop @endif
                @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                    x-show="isVisible('{{ $columnKey }}')"
                @endif
            >
                @if ($cell->isActions)
                    <div class="v-flex v-justify-center">
                        @if ($cell->value instanceof \Illuminate\Contracts\Support\Htmlable)
                            {!! $cell->value->toHtml() !!}
                        @elseif (is_array($cell->actions) && count($cell->actions))
                            <x-v-dynamic-table.management.actions :actions="$cell->actions" :maxVisible="$vm->actionsMaxVisible ?? 2" />
                        @endif
                    </div>
                @elseif ($cell->html)
                    {!! $cell->value !!}
                @else
                    {{ $cell->value }}
                @endif
            </div>
        @endforeach
    </div>
@empty
    <div class="v-px-6 v-py-4 v-text-sm text-gray-500 dark:text-gray-400">
        {{ $emptyText }}
    </div>
@endforelse

@if($rowIx)
    </div>
@endif

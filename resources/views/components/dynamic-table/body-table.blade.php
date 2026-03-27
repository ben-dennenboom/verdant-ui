@php
    $rowIx = $vm->rowInteractionEnabled ?? false;
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
        @if($rowIx && $row->rowKey !== null)
            class="v-grid v-items-center v-cursor-pointer"
            @click="selectRow(@js($row->rowKey))"
            @dblclick="openRow(@js($row->openUrl))"
            :class="isSelected(@js($row->rowKey)) ? 'v-bg-blue-50 dark:v-bg-blue-900/20 hover:v-bg-blue-100 dark:hover:v-bg-blue-900/30' : 'hover:v-bg-gray-50 dark:hover:v-bg-gray-700'"
        @else
            class="v-grid hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-items-center"
        @endif
        @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
            :style="'grid-template-columns: ' + (Alpine.store('{{ $columnVisibility['storeKey'] }}')?.gridTemplateColumns ?? @js($vm->gridTemplateColumns))"
        @else
            @if($vm->gridTemplateColumns !== '')
                style="grid-template-columns: {{ $vm->gridTemplateColumns }};"
            @else
                style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
            @endif
        @endif
    >
        @foreach ($row->cells as $cell)
            @php
                $columnKey = $vm->columnKeyForIndex($loop->index);
                $header = $vm->headers[$loop->index] ?? [];
                $alignClass = match ($header['align'] ?? '') {
                    'center' => 'v-text-center',
                    'right' => 'v-text-right',
                    default => 'v-text-left',
                };
            @endphp
            <div class="v-px-6 v-py-4 v-text-sm v-text-gray-900 dark:v-text-gray-300 {{ $alignClass }} {{ $cell->class }}"
                @if($rowIx && $cell->isActions) @click.stop @dblclick.stop @endif
                @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                    x-show="isVisible('{{ $columnKey }}')"
                @endif
            >
                @if ($cell->isActions)
                    <div class="v-flex v-justify-center">
                        {{-- Custom render (Htmlable) --}}
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

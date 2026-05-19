@php
    $rowIx = !$vm->hasBulkEdit && ($vm->rowInteractionEnabled ?? false);
    $bsk = $bulkStoreKey ?? null;
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
            @click="if ($event.shiftKey) { $store[@js($bsk)].toggle(@js($row->rowKey)); $event.preventDefault(); }"
            @dblclick="$store[@js($bsk)].openRow(@js($row->openUrl))"
            :class="$store[@js($bsk)].isSelected(@js($row->rowKey))
                ? 'v-mb-4 v-rounded-lg v-border v-border-primary-300 dark:v-border-primary-600 v-bg-primary-50 dark:v-bg-primary-900/20 v-p-4 v-shadow-sm'
                : 'v-mb-4 v-rounded-lg v-border dark:v-border-gray-700 v-bg-white dark:v-bg-gray-800 v-p-4 v-shadow-sm'"
        @elseif($rowIx && $row->rowKey !== null)
            class="v-mb-4 v-rounded-lg v-border dark:v-border-gray-700 v-bg-white dark:v-bg-gray-800 v-p-4 v-shadow-sm v-cursor-pointer"
            @click="selectRow(@js($row->rowKey))"
            @dblclick="openRow(@js($row->openUrl))"
            :class="isSelected(@js($row->rowKey)) ? 'v-ring-2 v-ring-blue-400 dark:v-ring-blue-600' : ''"
        @else
            class="v-mb-4 v-rounded-lg v-border dark:v-border-gray-700 v-bg-white dark:v-bg-gray-800 v-p-4 v-shadow-sm"
        @endif
    >
        {{-- Bulk checkbox for cards --}}
        @if($vm->hasBulkEdit && $row->rowKey !== null)
            <div class="v-flex v-items-center v-justify-between v-mb-2" x-show="$store[@js($bsk)].selected.length > 0" @click.stop @dblclick.stop>
                <label class="v-flex v-items-center v-gap-2 v-cursor-pointer v-select-none">
                    <input
                        type="checkbox"
                        :checked="$store[@js($bsk)].isSelected(@js($row->rowKey))"
                        @change="$store[@js($bsk)].toggle(@js($row->rowKey))"
                        class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 v-bg-white dark:v-bg-gray-700"
                    >
                    <span class="v-text-xs v-text-gray-500 dark:v-text-gray-400" x-show="$store[@js($bsk)].isSelected(@js($row->rowKey))" x-cloak>Selected</span>
                </label>
            </div>
        @endif

        {{-- Primary title (first two non-action columns) --}}
        <div class="v-font-semibold v-text-gray-900 dark:v-text-gray-100">
            @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                @if(isset($row->cells[0]))<span x-show="isVisible('{{ $vm->columnKeyForIndex(0) }}')">{{ $row->cells[0]->value ?? '' }}</span>@endif
                @if(isset($row->cells[1]))<span x-show="isVisible('{{ $vm->columnKeyForIndex(1) }}')">{{ $row->cells[1]->value ?? '' }}</span>@endif
            @else
                {{ isset($row->cells[0]) ? ($row->cells[0]->value ?? '') : '' }}
                {{ isset($row->cells[1]) ? ($row->cells[1]->value ?? '') : '' }}
            @endif
        </div>

        {{-- Remaining fields --}}
        <div class="v-mt-2 v-space-y-1 v-text-sm">
            @foreach ($row->cells as $cell)
                @continue($cell->isActions)
                @continue($loop->index < 2)

                @php
                    $columnKey = $vm->columnKeyForIndex($loop->index);
                @endphp
                <div class="v-flex v-justify-between v-gap-4"
                    @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']))
                        x-show="isVisible('{{ $columnKey }}')"
                    @endif
                >
                    <span class="v-text-gray-500 dark:v-text-gray-400">
                        {{ $vm->headerLabel($loop->index) }}
                    </span>

                    <span class="v-text-right v-text-gray-900 dark:v-text-gray-300">
                        @if ($cell->html)
                            {!! $cell->value !!}
                        @else
                            {{ $cell->value }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Actions --}}
        @php
            $actionsCell = collect($row->cells)->first(fn ($c) => $c->isActions);
        @endphp

        @if ($actionsCell)
            <div class="v-mt-3 v-flex v-justify-end" @if($rowIx || $vm->hasBulkEdit) @click.stop @dblclick.stop @endif>
                @if ($actionsCell->value instanceof \Illuminate\Contracts\Support\Htmlable)
                    {!! $actionsCell->value->toHtml() !!}
                @elseif (is_array($actionsCell->actions) && count($actionsCell->actions))
                    <x-v-dynamic-table.management.actions :actions="$actionsCell->actions" :maxVisible="$vm->actionsMaxVisible ?? 2" />
                @endif
            </div>
        @endif
    </div>
@empty
    <div class="v-px-6 v-py-4 v-text-sm v-text-gray-500 dark:v-text-gray-400">
        {{ $emptyText ?? 'No data available.' }}
    </div>
@endforelse

@if($rowIx)
    </div>
@endif

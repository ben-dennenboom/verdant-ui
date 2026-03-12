@forelse ($vm->rows as $row)
    <div
        class="v-grid hover:v-bg-gray-50 v-items-center"
        @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
            :style="'grid-template-columns: repeat(' + Alpine.store('{{ $columnVisibility['storeKey'] }}').visibleCount + ', minmax(0,1fr))'"
        @else
            style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
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
            <div class="v-px-6 v-py-4 v-text-sm {{ $alignClass }} {{ $cell->class }}"
                @if(!empty($header['width'])) style="min-width: {{ $header['width'] }}; max-width: {{ $header['width'] }};" @endif
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
    <div class="v-px-6 v-py-4 v-text-sm text-gray-500">
        {{ $emptyText }}
    </div>
@endforelse

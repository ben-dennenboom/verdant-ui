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
                    <div class="v-grid v-justify-items-center v-gap-2">
                        {{-- Custom render (Htmlable) --}}
                        @if ($cell->value instanceof \Illuminate\Contracts\Support\Htmlable)
                            {!! $cell->value->toHtml() !!}
                        @endif

                        {{-- Verdant-UI buttons --}}
                        @if (is_array($cell->actions) && count($cell->actions))
                            @foreach ($cell->actions as $action)
                                <x-dynamic-component
                                    :component="$action['component']"
                                    :href="$action['href']"
                                    :new-window="!empty($action['target'])"
                                    class="v-w-fit"
                                >
                                    {{ $action['label'] }}
                                </x-dynamic-component>
                            @endforeach
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

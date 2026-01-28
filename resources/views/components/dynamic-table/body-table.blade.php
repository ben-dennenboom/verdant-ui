@forelse ($vm->rows as $row)
    <div
        class="v-grid hover:v-bg-gray-50 v-items-center"
        style="grid-template-columns: repeat({{ $vm->columnCount }}, minmax(0,1fr));"
    >
        @foreach ($row->cells as $cell)
            <div class="v-px-6 v-py-4 v-text-sm {{ $cell->class }}">
                @if ($cell->isActions)
                    <div class="v-flex v-justify-end v-gap-2">
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

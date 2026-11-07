@php
    $bulkPrefix = $vm->hasBulkEdit ? '3rem ' : '';
    $gridTemplate = $bulkPrefix . $vm->visibleGridTemplateColumns();
    $columnIndexes = $vm->orderedColumnIndexes();
    $openOn = $vm->rowOpenTrigger();
@endphp

@forelse ($vm->rows as $row)
    @php
        $rowBgClass = match($row->rowStyle?->variant ?? '') {
            'success' => 'v:bg-green-50 v:dark:bg-green-900/20 v:hover:bg-green-100 v:dark:hover:bg-green-900/30',
            'info'    => 'v:bg-blue-50 v:dark:bg-blue-900/20 v:hover:bg-blue-100 v:dark:hover:bg-blue-900/30',
            'warning' => 'v:bg-yellow-50 v:dark:bg-yellow-900/20 v:hover:bg-yellow-100 v:dark:hover:bg-yellow-900/30',
            'danger'  => 'v:bg-red-50 v:dark:bg-red-900/20 v:hover:bg-red-100 v:dark:hover:bg-red-900/30',
            default   => 'v:hover:bg-gray-50 v:dark:hover:bg-gray-700',
        };
        $boldClass = $row->rowStyle?->bold ? ' v:font-semibold' : '';
        $interactive = $openOn && ($row->openUrl || $row->rowKey !== null);
        $cursorClass = $openOn && $row->openUrl && ! $vm->hasBulkEdit ? ' v:cursor-pointer' : '';
        $rowLink = $openOn === 'click' && $row->openUrl;
        $highlightClass = $vm->hasBulkEdit
            ? ' v:has-[[data-v-row-checkbox]:checked]:bg-primary-50 v:dark:has-[[data-v-row-checkbox]:checked]:bg-primary-900/20 v:has-[[data-v-row-checkbox]:checked]:hover:bg-primary-100 v:dark:has-[[data-v-row-checkbox]:checked]:hover:bg-primary-900/30'
            : ' v:data-v-active:bg-blue-50 v:dark:data-v-active:bg-blue-900/20 v:data-v-active:hover:bg-blue-100 v:dark:data-v-active:hover:bg-blue-900/30';
    @endphp
    <div
        class="v:grid v:items-center {{ $rowBgClass }}{{ $boldClass }}{{ $cursorClass }}{{ $highlightClass }}{{ $rowLink ? ' v:relative' : '' }}"
        @if($interactive) data-v-open-on="{{ $openOn }}" @endif
        @if($interactive && $row->openUrl) data-v-open-url="{{ $row->openUrl }}" @endif
        @if($vm->hasBulkEdit && $row->rowKey !== null) data-v-row-select @endif
        data-v-grid="{{ $bulkPrefix }}"
        style="grid-template-columns: {{ $gridTemplate }};"
    >
        @if($rowLink)
            <a
                href="{{ $row->openUrl }}"
                class="v:absolute v:inset-0 v:focus-visible:outline-none v:focus-visible:ring-2 v:focus-visible:ring-inset v:focus-visible:ring-primary-500"
                aria-label="{{ __('verdant::table.open_row') }}"
            ></a>
        @endif
        @if($vm->hasBulkEdit)
            <div class="v:pl-3 v:pr-1 v:py-4 v:flex v:items-center v:justify-center" data-v-no-row-click>
                @if($row->rowKey !== null)
                    <input
                        type="checkbox"
                        name="_ids[]"
                        value="{{ $row->rowKey }}"
                        form="{{ $bulkFormId }}"
                        data-v-row-checkbox
                        class="v:rounded v:border-gray-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:bg-white v:dark:bg-gray-700 v:cursor-pointer"
                    >
                @endif
            </div>
        @endif

        @foreach ($columnIndexes as $index)
            @php
                $cell = $row->cells[$index] ?? null;
                $header = $vm->headers[$index] ?? [];
                $alignClass = match ($header['align'] ?? '') {
                    'center' => 'v:text-center',
                    'right'  => 'v:text-right',
                    default  => 'v:text-left',
                };
            @endphp
            <div class="
                v:px-6 v:py-4 @if($index === 0 && $vm->hasBulkEdit) v:pl-3 @endif v:text-sm
                v:text-gray-900 v:dark:text-gray-300 {{ $alignClass }} {{ $cell?->class }} {{ $rowLink ? 'v:relative' : '' }}
            "
                data-v-col="{{ $vm->columnKeyForIndex($index) }}"
                @if($cell?->isActions) data-v-no-row-click @endif
                @unless($vm->isColumnVisible($index)) style="display: none;" @endunless
            >
                @if (is_null($cell))
                @elseif ($cell->isActions)
                    <div class="v:@container v:relative v:has-[[open]]:z-10 v:flex v:justify-center">
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
    <div class="v:px-6 v:py-4 v:text-sm v:text-gray-500 v:dark:text-gray-400">
        {{ $emptyText }}
    </div>
@endforelse

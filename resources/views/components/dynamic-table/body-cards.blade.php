@php
    $columnIndexes = $vm->orderedColumnIndexes();
    $openOn = $vm->rowOpenTrigger();
    $titleIndexes = array_values(array_intersect($columnIndexes, [0, 1]));
@endphp

@forelse ($vm->rows as $row)
    @php
        $rowBgClass = match($row->rowStyle?->variant ?? '') {
            'success' => 'v:bg-green-50 v:dark:bg-green-900/20',
            'info'    => 'v:bg-blue-50 v:dark:bg-blue-900/20',
            'warning' => 'v:bg-yellow-50 v:dark:bg-yellow-900/20',
            'danger'  => 'v:bg-red-50 v:dark:bg-red-900/20',
            default   => 'v:bg-surface',
        };
        $boldClass = $row->rowStyle?->bold ? ' v:font-semibold' : '';
        $actionsCell = collect($row->cells)->first(fn ($c) => $c->isActions);
        $interactive = $openOn && ($row->openUrl || $row->rowKey !== null);
        $opensUrl = $openOn && $row->openUrl;
        $cursorClass = $opensUrl && ! $vm->hasBulkEdit ? ' v:shadow-sm v:cursor-pointer' : '';
        $rowLink = $opensUrl && $openOn === 'click';
        $highlightClass = $vm->hasBulkEdit
            ? ' v:has-[[data-v-row-checkbox]:checked]:border-primary-300 v:dark:has-[[data-v-row-checkbox]:checked]:border-primary-600 v:has-[[data-v-row-checkbox]:checked]:bg-primary-50 v:dark:has-[[data-v-row-checkbox]:checked]:bg-primary-900/20'
            : ' v:data-v-active:ring-2 v:data-v-active:ring-blue-400 v:dark:data-v-active:ring-blue-600';
    @endphp
    <div
        class="v:mb-4 v:rounded-lg v:border v:border-gray-200 v:dark:border-gray-700 {{ $rowBgClass }} v:p-4{{ $boldClass }}{{ $cursorClass }}{{ $highlightClass }}{{ $rowLink ? ' v:relative' : '' }}"
        @if($interactive) data-v-open-on="{{ $openOn }}" @endif
        @if($opensUrl) data-v-open-url="{{ $row->openUrl }}" @endif
        @if($vm->hasBulkEdit && $row->rowKey !== null) data-v-row-select @endif
    >
        @if($rowLink)
            <a
                href="{{ $row->openUrl }}"
                class="v:absolute v:inset-0 v:focus-visible:outline-none v:focus-visible:ring-2 v:focus-visible:ring-inset v:focus-visible:ring-primary-500 v:rounded-lg"
                aria-label="{{ __('verdant::table.open_row') }}"
            ></a>
        @endif
        @if($vm->hasBulkEdit && $row->rowKey !== null)
            <div class="v:flex v:items-center v:justify-between v:mb-2" data-v-no-row-click>
                <label class="v:flex v:items-center v:gap-2 v:cursor-pointer v:select-none">
                    <input
                        type="checkbox"
                        value="{{ $row->rowKey }}"
                        form="{{ $bulkFormId }}"
                        data-v-row-checkbox
                        class="v:peer v:rounded v:border-gray-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:bg-white v:dark:bg-gray-700"
                    >
                    <span class="v:hidden v:peer-checked:inline v:text-xs v:text-gray-500 v:dark:text-gray-400">{{ __('verdant::table.selected') }}</span>
                </label>
            </div>
        @endif

        <div class="v:font-semibold v:text-gray-900 v:dark:text-gray-100{{ $rowLink ? ' v:relative' : '' }}">
            @foreach ($titleIndexes as $index)
                @isset($row->cells[$index])
                    <span
                        data-v-col="{{ $vm->columnKeyForIndex($index) }}"
                        @unless($vm->isColumnVisible($index)) style="display: none;" @endunless
                    >{{ $row->cells[$index]->value ?? '' }}</span>
                @endisset
            @endforeach
        </div>

        <div class="v:mt-2 v:flex v:flex-col v:gap-1 v:text-sm{{ $rowLink ? ' v:relative' : '' }}">
            @foreach ($columnIndexes as $index)
                @php $cell = $row->cells[$index] ?? null; @endphp
                @continue(is_null($cell) || $cell->isActions || $index < 2)

                <div
                    class="v:flex v:justify-between v:gap-4"
                    data-v-col="{{ $vm->columnKeyForIndex($index) }}"
                    @unless($vm->isColumnVisible($index)) style="display: none;" @endunless
                >
                    <span class="v:text-gray-500 v:dark:text-gray-400">
                        {{ $vm->headerLabel($index) }}
                    </span>

                    <span class="v:text-right v:text-gray-900 v:dark:text-gray-300">
                        @if ($cell->html)
                            {!! $cell->value !!}
                        @else
                            {{ $cell->value }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>

        @if ($actionsCell)
            <div class="v:@container v:relative v:has-[[open]]:z-10 v:mt-3 v:flex v:justify-end" data-v-no-row-click>
                @if ($actionsCell->value instanceof \Illuminate\Contracts\Support\Htmlable)
                    {!! $actionsCell->value->toHtml() !!}
                @elseif (is_array($actionsCell->actions) && count($actionsCell->actions))
                    <x-v-dynamic-table.management.actions :actions="$actionsCell->actions" :maxVisible="$vm->actionsMaxVisible ?? 2" />
                @endif
            </div>
        @endif
    </div>
@empty
    <div class="v:px-6 v:py-4 v:text-sm v:text-gray-500 v:dark:text-gray-400">
        {{ $emptyText ?? 'No data available.' }}
    </div>
@endforelse

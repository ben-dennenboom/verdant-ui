@php
    $resetSortUrl = $vm->resetSortUrl();
@endphp

<div
    class="v:grid v:grid-cols-subgrid v:col-span-full v:bg-gray-50 v:dark:bg-gray-700 v:relative"
    data-v-table-header
>
    @if($vm->hasBulkEdit)
        <div class="v:pl-3 v:pr-1 v:py-3 v:flex v:items-center v:justify-center">
            <input
                type="checkbox"
                data-v-select-all="{{ $bulkFormId }}"
                title="{{ __('verdant::table.select_all_on_page') }}"
                aria-label="{{ __('verdant::table.select_all_on_page') }}"
                class="v:rounded v:border-gray-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:bg-white v:dark:bg-gray-700 v:cursor-pointer"
            >
        </div>
    @endif

    @foreach ($vm->orderedColumnIndexes() as $index)
        @php
            $header = $vm->headers[$index];
            [$alignClass, $justifyClass] = $vm->alignClasses($index);
            $direction = !empty($header['key']) ? $vm->sort?->directionFor($header['key']) : null;
            $isActions = ($header['key'] ?? null) === \Dennenboom\VerdantUI\Tables\DynamicTableViewModel::ACTIONS_KEY;
        @endphp
        <div class="
            {{ $isActions ? 'v:px-3' : 'v:px-6' }} v:min-h-10 v:flex v:items-stretch v:border-b v:border-gray-100 v:dark:border-gray-600 @if($index === 0 && $vm->hasBulkEdit) v:pl-3 @endif v:text-sm v:font-medium v:text-gray-500
            v:dark:text-gray-400 v:uppercase {{ $alignClass }} {{ $header['class'] ?? '' }}
        "
            @if(!empty($header['tooltip'])) title="{{ e($header['tooltip']) }}" @endif
            data-v-col="{{ $vm->columnKeyForIndex($index) }}"
            @unless($vm->isColumnVisible($index)) style="display: none;" @endunless
        >
            @if (!empty($header['sortable']) && !empty($header['key']))
                <a
                    href="{{ $vm->sortUrl($header['key']) }}"
                    title="{{ !empty($header['tooltip']) ? e($header['tooltip']) : __('verdant::table.click_to_sort') }}"
                    class="v:group v:inline-flex v:items-center {{ $justifyClass }} v:uppercase v:gap-1 v:w-full v:py-3"
                >
                    <span class="v:group-hover:underline">{{ $header['label'] }}</span>

                    @if($direction)
                        <i class="v:text-xs fa v:text-blue-500 v:hover:no-underline {{ $direction === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    @endif
                </a>
            @else
                <span class="v:flex v:items-center {{ $justifyClass }} v:py-3 v:w-full">
                    {{ $header['label'] }}
                </span>
            @endif
        </div>
    @endforeach

    @if($resetSortUrl)
        <a
            href="{{ $resetSortUrl }}"
            class="v:absolute v:right-3 v:top-1/2 v:-translate-y-1/2 v:text-red-500 v:cursor-pointer"
            title="{{ __('verdant::table.reset_sorting') }}"
        >
            <i class="fa fa-xmark"></i>
        </a>
    @endif
</div>

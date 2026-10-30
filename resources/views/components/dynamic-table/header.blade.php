@php
    $bulkPrefix = $vm->hasBulkEdit ? '3rem ' : '';
    $initial = $columnVisibility['initial'] ?? null;
@endphp

<div x-data="dynamicTableSort({
        columns: @js($vm->sort?->columns ?? [])
    })"
    class="v:grid v:bg-gray-50 v:dark:bg-gray-700 v:relative"
    @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
        style="grid-template-columns: {{ $bulkPrefix }}{{ $initial['gridTemplateColumns'] }};"
        data-grid-prefix="{{ $bulkPrefix }}"
        :style="'grid-template-columns: {{ $bulkPrefix }}' + (Alpine.store('{{ $columnVisibility['storeKey'] }}')?.gridTemplateColumns ?? @js($vm->gridTemplateColumns))"
    @else
        @if($vm->gridTemplateColumns !== '')
            style="grid-template-columns: {{ $bulkPrefix }}{{ $vm->gridTemplateColumns }};"
        @else
            style="grid-template-columns: {{ $bulkPrefix }}repeat({{ $vm->columnCount }}, minmax(0,1fr));"
        @endif
    @endif
>
    @if($vm->hasBulkEdit)
        <div class="v:pl-3 v:pr-1 v:py-3 v:flex v:items-center v:justify-center"
            @if(!empty($columnVisibility['orderEnabled'])) style="order: -1;" @endif
        >
            <input
                type="checkbox"
                x-show="$store[@js($bulkStoreKey)].selected.length > 0"
                x-cloak
                :checked="$store[@js($bulkStoreKey)].allRowKeys.length > 0 && $store[@js($bulkStoreKey)].allRowKeys.every(k => $store[@js($bulkStoreKey)].selected.includes(k))"
                @change="$store[@js($bulkStoreKey)].toggleAll()"
                title="{{ __('verdant::table.select_all_on_page') }}"
                class="v:rounded v:border-gray-300 v:dark:border-gray-600 v:text-primary-600 v:focus:ring-primary-500 v:bg-white v:dark:bg-gray-700 v:cursor-pointer"
            >
        </div>
    @endif

    @foreach ($vm->headers as $key => $header)
        @php
            $columnKey = $vm->columnKeyForIndex($loop->index);
            $alignClass = match ($header['align'] ?? '') {
                'center' => 'v:text-center',
                'right'  => 'v:text-right',
                default  => 'v:text-left',
            };
        @endphp
        <div class="
            v:px-6 v:min-h-10 v:flex v:items-stretch border-b v:border-gray-100 @if($key === 0 && $vm->hasBulkEdit) v:pl-3 @endif v:text-sm v:font-medium v:text-gray-500
            v:dark:text-gray-400 v:uppercase {{ $alignClass }} {{ $header['class'] ?? '' }}
        "
            @if(!empty($header['tooltip'])) title="{{ e($header['tooltip']) }}" @endif
            @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
                data-column-key="{{ $columnKey }}"
                @if(!empty($columnVisibility['orderEnabled']))
                    style="{{ $initial['visible'][$columnKey] ? '' : 'display:none;' }}order:{{ $initial['orderIndex'][$columnKey] }}"
                    :style="(Alpine.store('{{ $columnVisibility['storeKey'] }}').isVisible('{{ $columnKey }}') ? '' : 'display:none;') + 'order:' + Alpine.store('{{ $columnVisibility['storeKey'] }}').orderIndex('{{ $columnKey }}')"
                @else
                    @unless($initial['visible'][$columnKey]) style="display:none;" @endunless
                    x-show="Alpine.store('{{ $columnVisibility['storeKey'] }}').isVisible('{{ $columnKey }}')"
                @endif
            @endif
        >
            @if (!empty($header['sortable']) && !empty($header['key']))
                <button
                    type="button"
                    title="{{ !empty($header['tooltip']) ? e($header['tooltip']) : __('verdant::table.click_to_sort') }}"
                    class="v:group v:inline-flex v:items-center v:uppercase v:gap-1 v:w-full v:py-3"
                    @click="toggle('{{ $header['key'] }}')"
                >
                    <span class="v:group-hover:underline">{{ $header['label'] }}</span>

                    <template x-if="isActive('{{ $header['key'] }}')">
                        <i
                            class="v:text-xs fa v:text-blue-500 v:hover:no-underline"
                            :class="directionFor('{{ $header['key'] }}') === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'"
                        ></i>
                    </template>
                </button>
            @else
                <span class="v:flex v:items-center v:py-3 v:w-full">
        {{ $header['label'] }}
    </span>
            @endif
        </div>
    @endforeach

    <template x-if="hasSorting()">
        <button
            type="button"
            class="v:absolute v:right-3 v:top-1/2 v:-translate-y-1/2 v:text-red-500 v:cursor-pointer"
            title="{{ __('verdant::table.reset_sorting') }}"
            @click.stop="reset"
        >
            <i class="fa fa-xmark"></i>
        </button>
    </template>
</div>

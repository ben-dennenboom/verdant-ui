@php
    $bulkPrefix = $vm->hasBulkEdit ? '3rem ' : '';
@endphp

<div x-data="dynamicTableSort({
        columns: @js($vm->sort?->columns ?? [])
    })"
    class="v-grid v-bg-gray-50 dark:v-bg-gray-700 v-relative"
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
    @if($vm->hasBulkEdit)
        <div class="v-pl-3 v-pr-1 v-py-3 v-flex v-items-center v-justify-center">
            <input
                type="checkbox"
                x-show="$store[@js($bulkStoreKey)].selected.length > 0"
                :checked="$store[@js($bulkStoreKey)].allRowKeys.length > 0 && $store[@js($bulkStoreKey)].allRowKeys.every(k => $store[@js($bulkStoreKey)].selected.includes(k))"
                @change="$store[@js($bulkStoreKey)].toggleAll()"
                title="Select all on this page"
                class="v-rounded v-border-gray-300 dark:v-border-gray-600 v-text-primary-600 focus:v-ring-primary-500 v-bg-white dark:v-bg-gray-700 v-cursor-pointer"
            >
        </div>
    @endif

    @foreach ($vm->headers as $key => $header)
        @php
            $columnKey = $vm->columnKeyForIndex($loop->index);
            $alignClass = match ($header['align'] ?? '') {
                'center' => 'v-text-center',
                'right'  => 'v-text-right',
                default  => 'v-text-left',
            };
        @endphp
        <div class="
            v-px-6 v-py-3 @if($key === 0 && $vm->hasBulkEdit) v-pl-3 @endif v-text-md v-font-semibold v-text-gray-700
            dark:v-text-gray-300 {{ $alignClass }} {{ $header['class'] ?? '' }}
        "
            @if(!empty($header['tooltip'])) title="{{ e($header['tooltip']) }}" @endif
            @if(!empty($columnVisibility) && !empty($columnVisibility['enabled']) && !empty($columnVisibility['storeKey']))
                x-show="Alpine.store('{{ $columnVisibility['storeKey'] }}').isVisible('{{ $columnKey }}')"
            @endif
        >
            @if (!empty($header['sortable']) && !empty($header['key']))
                <button
                    type="button"
                    title="{{ !empty($header['tooltip']) ? e($header['tooltip']) : 'Click to sort' }}"
                    class="v-inline-flex v-items-center v-gap-1 hover:v-underline"
                    @click="toggle('{{ $header['key'] }}')"
                >
                    {{ $header['label'] }}

                    <template x-if="isActive('{{ $header['key'] }}')">
                        <i
                            class="v-text-xs fa"
                            :class="directionFor('{{ $header['key'] }}') === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'"
                        ></i>
                    </template>
                </button>
            @else
                {{ $header['label'] }}
            @endif
        </div>
    @endforeach

    <template x-if="hasSorting()">
        <button
            type="button"
            class="
                v-absolute v-right-3 v-top-1/2 -v-translate-y-1/2
                v-text-gray-400 dark:v-text-gray-500 hover:v-text-red-500
            "
            title="Reset sorting"
            @click.stop="reset"
        >
            <i class="fa fa-xmark"></i>
        </button>
    </template>
</div>

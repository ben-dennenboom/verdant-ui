@props([
    /**
     * @var array $columns
     * Expects an array of objects: [['id' => 'name', 'label' => 'Name', 'hideable' => true]]
     */
    'columns' => [],
])

<div x-data="{ open: false }" class="v-relative" {{ $attributes }}>

    <x-v-button.toolbar @click="open = !open" tooltip="Manage Columns" tooltip-position="top">
        <i class="fas fa-columns v-text-2xl v-text-gray-600 dark:v-text-gray-300"></i>
    </x-button.toolbar>

    <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="v-transition v-ease-out v-duration-200"
        x-transition:enter-start="v-opacity-0 v-scale-95 v--translate-y-2"
        x-transition:enter-end="v-opacity-100 v-scale-100 v-translate-y-0"
        class="v-column-manager-dropdown v-absolute v-right-0 v-z-50 v-mt-2 v-w-64 v-origin-top-right v-rounded-xl v-bg-white v-p-4 v-shadow-2xl v-ring-1 v-ring-black/5 dark:v-bg-gray-800">

        <div
            class="v-flex v-items-center v-justify-between v-mb-3 v-border-b v-border-gray-100 dark:v-border-gray-700 v-pb-2">
            <span class="v-text-[10px] v-font-bold v-uppercase v-tracking-widest v-text-gray-400">
                Visible Columns
            </span>

            <button @click="resetColumns()" type="button"
                class="v-text-[10px] v-font-bold v-uppercase v-text-primary-600 hover:v-underline">
                Reset
            </button>
        </div>

        <div class="v-block v-max-h-72 v-overflow-y-auto v-w-full" style="display: block !important;">
            @foreach ($columns as $col)
                @if ($col['hideable'] ?? false)
                    <label
                        class="v-flex v-items-center v-w-full v-cursor-pointer v-rounded-lg v-px-2 v-py-2 hover:v-bg-gray-50 dark:hover:v-bg-gray-700/50"
                        style="display: flex !important; width: 100% !important; margin-bottom: 4px !important;">
                        <input type="checkbox" :checked="isColumnVisible('{{ $col['id'] }}')"
                            @change="toggleColumn('{{ $col['id'] }}')"
                            class="v-shrink-0 v-w-4 v-h-4 v-rounded v-border-gray-300 v-text-primary-600"
                            style="margin-right: 16px !important; flex-shrink: 0 !important;">

                        <span class="v-text-sm v-font-medium v-text-gray-700 dark:v-text-gray-200 v-truncate"
                            style="display: block !important;">
                            {{ $col['label'] }}
                        </span>
                    </label>
                @endif
            @endforeach
        </div>
    </div>
</div>

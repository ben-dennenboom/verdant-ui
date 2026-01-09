@props([
    'columns' => 4,
    'class' => '',
    'pagination' => null,
    'header' => null,
])

<div data-grid-id="{{ $attributes->get('id', 'grid') }}"
    {{ $attributes->merge(['class' => "v-rounded-xl v-bg-white dark:v-bg-gray-800 v-border v-border-gray-200 dark:v-border-gray-700 v-overflow-hidden $class"]) }}>

    <div x-ref="gridWrapper" :class="tileView ? 'v-p-4' : ''" class="v-relative v-overflow-x-auto v-overflow-y-visible">

        <div x-ref="gridLayout" class="v-grid v-grid-layout" :class="tileView ? 'v-tile-view v-gap-4' : 'v-table-view'"
            :style="getGridStyle()">

            <div class="v-grid-header-wrapper" style="display: contents;">
                {{ $header }}
            </div>

            <div class="v-grid-body-wrapper" style="display: contents;">
                {{ $slot }}
            </div>
        </div>
    </div>

    @if (isset($pagination))
        <div
            class="v-px-6 v-py-4 v-border-t v-border-gray-200 dark:v-border-gray-700 v-bg-gray-50/50 dark:v-bg-gray-800/50">
            {{ $pagination }}
        </div>
    @endif
</div>

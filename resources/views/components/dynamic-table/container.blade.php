@props([
    'data' => null,
    'headers' => [],
    'rows' => [],
    'class' => '',
    'emptyText' => 'No data available.',
])

@php
    $vm = \Dennenboom\VerdantUI\Tables\DynamicTableViewModel::from(
        $data,
        $headers,
        $rows
    );
@endphp

<div class="v-rounded v-bg-white dark:v-bg-gray-800 v-border {{ $class }}">
    <div class="v-hidden lg:v-block v-overflow-x-auto">
        <div class="v-min-w-full">
            @include('verdant::components.dynamic-table.header')
            @include('verdant::components.dynamic-table.body-table')
        </div>
    </div>

    <div class="v-block lg:v-hidden v-p-4">
        @include('verdant::components.dynamic-table.body-cards')
    </div>

    @include('verdant::components.dynamic-table.pagination')
</div>

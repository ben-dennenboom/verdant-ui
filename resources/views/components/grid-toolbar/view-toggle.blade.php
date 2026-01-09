@php
    $tableText = 'Switch to Table View';
    $tileText = 'Switch to Tile View';
@endphp
<div class="v-inline-block">
    <template x-if="tileView">
        <x-button.toolbar @click="toggleView()" :tooltip="$tableText" tooltip-position="top">
            <i class="fas fa-table v-text-2xl v-text-gray-600 dark:v-text-gray-300"></i>
        </x-button.toolbar>
    </template>

    <template x-if="!tileView">
        <x-button.toolbar @click="toggleView()" :tooltip="$tileText" tooltip-position="top">
            <i class="fas fa-th-large v-text-2xl v-text-gray-600 dark:v-text-gray-300"></i>
        </x-button.toolbar>
    </template>
</div>

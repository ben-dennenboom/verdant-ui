@props([
    'direction' => 'end',
    'tooltip' => null,
])

<x-v-button.toolbar @click="scrollTo('{{ $direction }}')" :tooltip="$tooltip">
    <i class="fas fa-arrow-{{ $direction === 'end' ? 'right' : 'left' }}"></i>
</x-button.toolbar>

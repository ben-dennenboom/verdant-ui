@props([
    'filter' => [],
    'placeholder' => 'Search...',
    'name' => 'search',
    'label' => 'Search',
])

<div class="v-w-full">
    <label for="{{ $name }}" class="v-block v-font-medium v-text-gray-700">{{ $label }}</label>
    <div class="v-mt-1">
        <input
            type="text"
            name="filter[{{ $name }}]"
            id="{{ $name }}"
            value="{{ $filter[$name] ?? '' }}"
            placeholder="{{ $placeholder }}"
            class="v-shadow-sm focus:v-ring-secondary-700 focus:v-border-secondary-700 v-block v-w-full sm:v-text-sm v-border-gray-300"
        />
    </div>
</div>

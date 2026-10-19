@props([
    'filter' => [],
    'placeholder' => 'Search...',
    'name' => 'search',
    'label' => 'Search',
])

<div class="v:w-full">
    <label for="{{ $name }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{{ $label }}</label>
    <div class="v:mt-1">
        <input
            type="text"
            name="filter[{{ $name }}]"
            id="{{ $name }}"
            value="{{ $filter[$name] ?? '' }}"
            placeholder="{{ $placeholder }}"
            class="v:shadow-sm v:focus:ring-secondary-700 v:focus:border-secondary-700 v:block v:w-full v:sm:text-sm v:border-gray-300 v:bg-white v:dark:bg-gray-800 v:text-gray-900 v:dark:text-gray-100 v:dark:border-gray-600 v:dark:placeholder-gray-400"
        />
    </div>
</div>

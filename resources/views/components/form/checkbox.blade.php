@props(['name', 'label', 'value' => null, 'required' => false, 'checked' => false])

<div class="v-mb-4 v-flex v-align-middle v-gap-4 v-items-center">
    <label for="{{ $name }}" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">{{ $label }}</label>
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}"
           value="{{ old($name, $value) }}"
        {{ $checked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'v-rounded focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 dark:v-border-gray-600']) }}>
    @error($name)
    <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>

@props(['name', 'label', 'value' => null, 'required' => false])

<div class="v-mb-4">
    <label for="{{ $name }}" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}"
              {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'v-mt-1 focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 v-text-gray-900 dark:v-text-gray-100 dark:v-border-gray-600']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
    <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>

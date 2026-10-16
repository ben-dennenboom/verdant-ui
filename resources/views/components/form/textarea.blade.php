@props(['name', 'label', 'value' => null, 'required' => false])

<div class="v:mb-4">
    <label for="{{ $name }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
    <textarea name="{{ $name }}" id="{{ $name }}"
              {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => (config('verdant.ui.aligned_form_controls') ? 'v:rounded ' : '').'v:mt-1 v:focus:ring-secondary-500 v:focus:border-secondary-500 v:block v:w-full v:shadow-sm sm:v:text-sm v:border-secondary-300 v:bg-white v:dark:bg-gray-800 v:text-gray-900 v:dark:text-gray-100 v:dark:border-gray-600']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
    <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
    @enderror
</div>

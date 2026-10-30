@props(['name', 'label', 'required' => false, 'accept' => '*'])

@php
    $aligned = (bool) config('verdant.ui.aligned_form_controls');
@endphp

<div class="v:mb-4">
    <label for="{{ $name }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
    <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => ($aligned ? 'v:rounded ' : '') . 'v:mt-1 v:focus:ring-secondary-500 v:focus:border-secondary-500 v:block v:w-full v:sm:text-sm v:px-3 v:p-2 v:border v:border-secondary-300 v:bg-white v:dark:bg-gray-800 v:text-gray-900 v:dark:text-gray-100 v:dark:border-gray-600']) }}>
    @error($name)
    <p class="v:mt-2 v:text-red-600">
        {{ $message }}
    </p>
    @enderror
</div>

<style>

    input[type="file"]:focus {
        outline: none;
        box-shadow: 0 0 0 1px var(--color-secondary-500);
    }
    input[type="file"]::file-selector-button {
        padding: 0.25rem 0.5rem;
        border-radius: 0.125rem;
        margin-right: 0.5rem;
        background-color: var(--color-primary-300);
        color: white;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background-color 200ms ease;
    }
    input[type="file"]::file-selector-button:hover {
        background-color: var(--color-primary-400);
    }
</style>

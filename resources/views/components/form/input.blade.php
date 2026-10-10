@props(['name' => '', 'label' => '', 'type' => 'text', 'value' => null, 'required' => false, 'hidden' => false, 'id' => null])

@php
    $inputId = $id ?? $name;
@endphp

@if($hidden)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
@else
    <div class="v:mb-5 v:w-full">
        @if($label)
            <label for="{{ $inputId }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
        @endif
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $inputId }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'v:rounded v:mt-1 v:focus:ring-secondary-500 v:focus:border-secondary-500 v:block v:w-full v:shadow-sm v:sm:text-sm v:border-secondary-300 v:bg-white v:dark:bg-gray-800 v:text-gray-900 v:dark:text-gray-100 v:dark:border-gray-600']) }}>

        @error($name)
            <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

@props(['name', 'label', 'value' => null, 'required' => false, 'checked' => false, 'id' => null])

@php
    $inputId = $id ?? $name;
    $checkFirst = (bool) config('verdant.ui.checkbox_first');
@endphp

<div class="v:mb-4 v:flex v:align-middle v:gap-4 v:items-center">
    @if(! $checkFirst)
        <label for="{{ $inputId }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{!! $label !!}@if($required)<span class="required_asterisk">*</span>@endif</label>
    @endif
    <input type="checkbox" name="{{ $name }}" id="{{ $inputId }}"
           value="{{ old($name, $value) }}"
        {{ $checked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'v:rounded v:focus:ring-secondary-500 v:focus:border-secondary-500 v:block v:sm:text-sm v:border-secondary-300 v:text-primary-600 v:not-checked:bg-white v:dark:not-checked:bg-gray-800 v:dark:border-gray-600']) }}>
    @if($checkFirst)
        <label for="{{ $inputId }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">{!! $label !!}@if($required)<span class="required_asterisk">*</span>@endif</label>
    @endif
    @error($name)
        <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
    @enderror
</div>

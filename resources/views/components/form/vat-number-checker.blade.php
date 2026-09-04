@props(['name' => '', 'label' => '', 'value' => null, 'required' => false, 'hidden' => false, 'id' => null, 'nameId' => null, 'addressId' => null])

@php
    $inputId = $id ?? $name;
@endphp

@if($hidden)
    <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}">
@else
    <div class="v-mb-5 v-w-full">
        @if($label)
            <label for="{{ $inputId }}" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
        @endif

        <div class="v-relative v-w-full" x-data="vatNumberChecker({ url: '{{ route('verdant.vat-number-check') }}', nameId: '{{ $nameId }}', addressId: '{{ $addressId }}' })">
            <input
                type="text"
                name="{{ $name }}" id="{{ $inputId }}"
                value="{{ old($name, $value) }}"
                x-on:blur="check($event.target.value)"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => 'v-rounded v-mt-1 focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 v-text-gray-900 dark:v-text-gray-100 dark:v-border-gray-600 v-pr-10']) }}
            />

            <span class="v-absolute v-inset-y-0 v-right-0 v-mt-1 v-flex v-items-center v-pr-3 v-pointer-events-none">
                <i style="display: none" x-show="state === 'loading'" class="fa fa-circle-notch fa-spin fa-lg v-text-gray-400" aria-hidden="true"></i>
                <i style="display: none" x-show="state === 'valid'" class="fa fa-circle-check fa-lg v-text-green-500" aria-hidden="true"></i>
                <i style="display: none" x-show="state === 'invalid'" class="fa fa-circle-xmark fa-lg v-text-red-500" aria-hidden="true"></i>
                <i style="display: none" x-show="state === 'error'" class="fa fa-triangle-exclamation fa-lg v-text-yellow-500" aria-hidden="true"></i>
            </span>
        </div>

        @error($name)
        <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

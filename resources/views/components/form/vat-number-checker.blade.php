@props(['name' => '', 'label' => '', 'value' => null, 'required' => false, 'hidden' => false, 'id' => null, 'nameId' => null, 'addressId' => null])

@php
    $inputId = $id ?? $name;
@endphp

@if($hidden)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
@else
    <div class="v-mb-5 v-w-full">
        @if($label)
            <label for="{{ $inputId }}" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
        @endif

        <span class="v-relative">
            <input
                type="text"
                name="{{ $name }}" id="{{ $inputId }}"
                value="{{ old($name, $value) }}"
                onblur="validate('{{ $inputId }}', '{{ $nameId }}', '{{ $addressId }}')"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => 'v-rounded v-mt-1 focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 v-text-gray-900 dark:v-text-gray-100 dark:v-border-gray-600']) }}
            />

            <span id="loadingIndicator" hidden class="v-animate-spin v-rounded-full v-absolute v-right-0 v-top-0 v-bottom-0 v-pointer-events-none fa fa-circle-notch fa-lg"></span>
            <span id="validIndicator" hidden class="v-rounded-full v-absolute v-right-0 v-top-0 v-bottom-0 v-pointer-events-none fa fa-circle-check fa-lg v-text-green-500"></span>
            <span id="invalidIndicator" hidden class="v-rounded-full v-absolute v-right-0 v-top-0 v-bottom-0 v-pointer-events-none fa fa-circle-xmark fa-lg v-text-red-500"></span>
        </span>

        @error($name)
        <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

@push('scripts')
    <script src="{{ asset('js/vat-number-checker.js') }}"></script>
@endpush


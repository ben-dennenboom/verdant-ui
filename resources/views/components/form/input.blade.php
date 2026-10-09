@props(['name' => '', 'label' => '', 'type' => 'text', 'value' => null, 'required' => false, 'hidden' => false, 'id' => null, 'hint' => null, 'revealable' => false])

@php
    $inputId = $id ?? $name;
    $inputClasses = 'v-rounded v-mt-1 focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-secondary-300 v-bg-white dark:v-bg-gray-800 v-text-gray-900 dark:v-text-gray-100 dark:v-border-gray-600';
@endphp

@if($hidden)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
@else
    <div class="v-mb-5 v-w-full" @if($revealable) x-data="{ revealed: false }" @endif>
        @if($label)
            <label for="{{ $inputId }}" class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300">{{ $label }}@if($required)<span class="required_asterisk">*</span>@endif</label>
        @endif

        @if($revealable)
            <div class="v-relative">
                <input type="{{ $type }}" :type="revealed ? 'text' : @js($type)" name="{{ $name }}" id="{{ $inputId }}"
                    value="{{ $type === 'password' ? '' : old($name, $value) }}"
                    {{ $required ? 'required' : '' }}
                    {{ $attributes->merge(['class' => $inputClasses.' v-pr-10']) }}>
                <button type="button"
                        class="v-absolute v-inset-y-0 v-right-0 v-flex v-w-10 v-items-center v-justify-center v-text-gray-400 hover:v-text-gray-700"
                        :aria-label="revealed ? @js(__('verdant::form.hide')) : @js(__('verdant::form.show'))"
                        :aria-pressed="revealed"
                        @click="revealed = ! revealed">
                    <i class="fa-solid" :class="revealed ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
        @else
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $inputId }}"
                value="{{ old($name, $value) }}"
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => $inputClasses]) }}>
        @endif

        @if($hint)
            <p class="v-mt-1 v-text-xs v-text-gray-500 dark:v-text-gray-400">{{ $hint }}</p>
        @endif

        @error($name)
            <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

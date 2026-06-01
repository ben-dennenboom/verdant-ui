@props(['name', 'label', 'value' => '1', 'required' => false, 'checked' => false, 'id' => null])

@php
    $inputId = $id ?? $name;
    $isChecked = (bool) old($name, $checked);
    $xModelVar = $attributes->get('x-model');
@endphp

<div class="v-mb-4"
     x-data="{
        @if($xModelVar)
        get on() {
            return {{ $xModelVar }};
        },
        set on(value) {
            {{ $xModelVar }} = value;
        },
        @else
        on: {{ $isChecked ? 'true' : 'false' }},
        @endif
     }">
    <div class="v-flex v-items-center v-gap-3">
        <button
            type="button"
            role="switch"
            id="{{ $inputId }}"
            :aria-checked="on.toString()"
            @click="on = !on"
            :class="on ? 'v-bg-primary-500' : 'v-bg-gray-200 dark:v-bg-gray-600'"
            class="v-relative v-inline-flex v-h-7 v-w-14 v-flex-shrink-0 v-cursor-pointer v-rounded-full v-border-2 v-border-transparent v-transition-colors v-duration-200 v-ease-in-out focus:v-outline-none focus:v-ring-2 focus:v-ring-primary-500 focus:v-ring-offset-2 dark:focus:v-ring-offset-gray-800"
        >
            <span
                :class="on ? 'v-translate-x-7' : 'v-translate-x-0'"
                class="v-pointer-events-none v-inline-block v-h-6 v-w-6 v-transform v-rounded-full v-bg-white v-shadow v-ring-0 v-transition v-duration-200 v-ease-in-out"
            ></span>
        </button>

        <label for="{{ $inputId }}"
               class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300 v-cursor-pointer v-select-none">
            {!! $label !!}
        </label>
    </div>

    <input type="hidden" name="{{ $name }}" :value="on ? '{{ $value }}' : '0'" {{ $required ? 'required' : '' }}>

    @error($name)
        <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>

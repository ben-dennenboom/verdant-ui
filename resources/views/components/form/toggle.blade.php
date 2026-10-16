@props(['name', 'label', 'value' => '1', 'required' => false, 'checked' => false, 'id' => null, 'description' => null])

@php
    $inputId = $id ?? str_replace(['[]', '[', ']'], ['', '_', ''], $name);
    $isChecked = (bool) old(str_replace(['[]', '[', ']'], ['', '.', ''], $name), $checked);
    $xModelVar = $attributes->get('x-model');
@endphp

<div class="v:mb-4"
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
    <div class="v:flex v:items-center v:gap-3">
        <button
            type="button"
            role="switch"
            id="{{ $inputId }}"
            :aria-checked="on.toString()"
            @click="on = !on"
            :class="on ? 'v:bg-primary-500' : 'v:bg-gray-200 v:dark:bg-gray-600'"
            class="v:relative v:inline-flex v:h-7 v:w-14 v:shrink-0 v:cursor-pointer v:rounded-full v:border-2 v:border-transparent v:transition-colors v:duration-200 v:ease-in-out v:focus:outline-hidden v:focus:ring-2 v:focus:ring-primary-500 v:focus:ring-offset-2 v:dark:focus:ring-offset-gray-800"
        >
            <span
                :class="on ? 'v:translate-x-7' : 'v:translate-x-0'"
                class="v:pointer-events-none v:inline-block v:h-6 v:w-6 v:rounded-full v:bg-white v:ring-0 v:transition v:duration-200 v:ease-in-out"
            ></span>
        </button>

        @if($description)
            <div>
                <label for="{{ $inputId }}"
                       class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300 v:cursor-pointer v:select-none">
                    {!! $label !!}@if($required)<span class="required_asterisk">*</span>@endif
                </label>
                <p class="v:text-sm v:text-gray-500 v:dark:text-gray-400">{{ $description }}</p>
            </div>
        @else
            <label for="{{ $inputId }}"
                   class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300 v:cursor-pointer v:select-none">
                {!! $label !!}@if($required)<span class="required_asterisk">*</span>@endif
            </label>
        @endif
    </div>

    <input type="hidden" name="{{ $name }}" :value="on ? '{{ $value }}' : '0'" {{ $required ? 'required' : '' }}>

    @error($name)
        <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
    @enderror
</div>

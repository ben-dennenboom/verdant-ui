@props(['name' => '', 'label' => '', 'type' => 'text', 'value' => null, 'required' => false, 'hidden' => false])

@if($hidden)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
@else
    <div class="v-mb-5 v-w-full">
        <label for="{{ $name }}" class="v-block v-font-medium v-text-gray-700">{{ $label }}</label>
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
               value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'v-rounded v-mt-1 focus:v-ring-secondary-500 focus:v-border-secondary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-secondary-300']) }}>
        @error($name)
        <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

@props(['name', 'label', 'value' => null, 'required' => false])

<div class="v-mb-4">
    <label for="{{ $name }}" class="v-block v-font-medium v-text-foreground">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}"
              {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'v-mt-1 focus:v-ring-primary-500 focus:v-border-primary-500 v-block v-w-full v-shadow-sm sm:v-text-sm v-border-border v-bg-surface v-text-foreground']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
    <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>

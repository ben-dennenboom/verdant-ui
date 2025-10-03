@props(['name', 'label', 'value' => null, 'required' => false, 'checked' => false])

<div class="v-mb-4 v-flex v-align-middle v-gap-4 v-items-center">
    <label for="{{ $name }}" class="v-block v-font-medium v-text-foreground">{{ $label }}</label>
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}"
           value="{{ old($name, $value) }}"
        {{ $checked ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'v-rounded v-form-checkbox']) }}>
    @error($name)
    <p class="v-mt-2 v-text-red-600">{{ $message }}</p>
    @enderror
</div>

@props([
    'name',
    'label' => '',
    'value' => [],
    'required' => false,
    'placeholder' => 'Type and press Enter…',
    'type' => 'text',
    'id' => null,
])

@php
    $cleanName = str_replace(['[]', '[', ']'], ['', '.', ''], $name);
    $fallbackValue = old($cleanName, $value);
    $tags = is_array($fallbackValue)
        ? array_values(array_filter($fallbackValue, fn ($v) => $v !== null && $v !== ''))
        : [];
    $inputId = $id ?? $cleanName;
@endphp

<div class="v:mb-4"
     x-data="{
        tags: @js($tags),
        draft: '',
        addFromDraft() {
            const value = this.draft.trim().replace(/,$/, '');
            this.draft = '';
            if (value === '' || this.tags.includes(value)) {
                return;
            }
            this.tags.push(value);
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        removeLastTag() {
            if (this.draft === '' && this.tags.length) {
                this.tags.pop();
            }
        },
     }"
>
    @if($label)
        <label for="{{ $inputId }}" class="v:block v:font-medium v:text-gray-700 v:dark:text-gray-300">
            {{ $label }}@if($required)<span class="required_asterisk">*</span>@endif
        </label>
    @endif

    <div
        @click="$refs.tagsInput.focus()"
        class="v:mt-1 v:flex v:flex-wrap v:items-center v:gap-2 v:rounded v:border v:border-secondary-300 v:dark:border-gray-600 v:bg-white v:dark:bg-gray-800 v:px-3 v:py-2 v:shadow-sm v:cursor-text v:focus-within:ring-1 v:focus-within:ring-secondary-500 v:focus-within:border-secondary-500"
    >
        <template x-for="(tag, index) in tags" :key="tag">
            <span class="v:inline-flex v:items-center v:gap-1.5 v:rounded-full v:bg-secondary-100 v:dark:bg-secondary-900 v:text-secondary-800 v:dark:text-secondary-200 v:text-sm v:px-3 v:py-1">
                <span x-text="tag"></span>
                <button
                    type="button"
                    @click="removeTag(index)"
                    class="v:text-secondary-500 v:hover:text-secondary-800 v:dark:hover:text-secondary-100"
                    aria-label="Remove"
                >&times;</button>
            </span>
        </template>

        <input
            id="{{ $inputId }}"
            type="{{ $type }}"
            x-ref="tagsInput"
            x-model="draft"
            @keydown.enter.prevent="addFromDraft()"
            @keydown.comma.prevent="addFromDraft()"
            @keydown.backspace="removeLastTag()"
            @blur="addFromDraft()"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'v:flex-1 v:min-w-32 v:border-0 v:p-1.5 v:text-sm v:bg-transparent v:focus:ring-0 v:text-gray-900 v:dark:text-gray-100']) }}
            placeholder="{{ $placeholder }}"
        >
    </div>

    <template x-for="tag in tags" :key="'hidden-' + tag">
        <input type="hidden" name="{{ $name }}" :value="tag">
    </template>

    @error($cleanName)
        <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
    @enderror
    @error($cleanName.'.*')
        <p class="v:mt-2 v:text-red-600">{{ $message }}</p>
    @enderror
</div>

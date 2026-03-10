@props([
    'label' => '',
    'languages' => [],
    'required' => false,
    'disableCrop' => false,
    'aspectRatio' => null,
    'uploadUrl' => null,
    'minWidth' => 0,
    'minHeight' => 0,
    'maxWidth' => 5000,
    'maxHeight' => 5000,
    'maxScale' => 512,
])

@php
    $languages = \Illuminate\Support\Arr::wrap($languages);
    $firstIndex = array_key_first($languages);
@endphp

<div class="v-mb-5 v-w-full" x-data="{
    activeTab: {{ $firstIndex !== null ? (int) $firstIndex : 0 }},
    languages: @js($languages)
}">
    <label class="v-block v-font-medium v-text-gray-700 dark:v-text-gray-300 v-mb-1">{{ $label }}</label>

    <x-v-form.translation-tabs :languages="$languages" />

    {{-- Image fields (one per language) --}}
    @foreach($languages as $index => $lang)
        @php
            $name = $lang['name'] ?? '';
            $src = $lang['value'] ?? $lang['src'] ?? null;
        @endphp
        <div x-show="activeTab === {{ $index }}"
             x-transition:enter="v-transition v-duration-150 v-ease-out"
             x-transition:enter-start="v-opacity-0"
             x-transition:enter-end="v-opacity-100"
             class="v-mt-1 [&_.v-mb-4]:!v-mb-0"
             @if($index !== $firstIndex) style="display: none" @endif>
            <x-v-form.image-cropper
                :name="$name"
                :label="null"
                :src="$src"
                :required="$required && $index === $firstIndex"
                :disableCrop="$disableCrop"
                :aspectRatio="$aspectRatio"
                :uploadUrl="$uploadUrl"
                :minWidth="$minWidth"
                :minHeight="$minHeight"
                :maxWidth="$maxWidth"
                :maxHeight="$maxHeight"
                :maxScale="$maxScale"
            />
        </div>
    @endforeach
</div>

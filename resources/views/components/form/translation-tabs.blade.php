@props(['languages' => []])

@php
    $languages = \Illuminate\Support\Arr::wrap($languages);
@endphp

<div class="v-flex v-flex-wrap v-gap-1 v-mb-2">
    @foreach($languages as $index => $lang)
        @php
            $langLabel = $lang['label'] ?? '';
            $hasValue = isset($lang['value']) && $lang['value'] !== null && $lang['value'] !== '';
        @endphp
        <button type="button"
                @click="activeTab = {{ $index }}"
                :class="{
                    'v-bg-blue-100 dark:v-bg-blue-900/40 v-border-blue-300 dark:v-border-blue-700 v-font-semibold v-text-gray-900 dark:v-text-gray-100': activeTab === {{ $index }},
                    'v-bg-gray-100 dark:v-bg-gray-700/50 v-border-gray-200 dark:v-border-gray-600 v-text-gray-500 dark:v-text-gray-400 v-grayscale': activeTab !== {{ $index }} && !{{ $hasValue ? 'true' : 'false' }},
                    'v-bg-white dark:v-bg-gray-800 v-border-gray-300 dark:v-border-gray-600 v-text-gray-700 dark:v-text-gray-300 hover:v-bg-gray-50 dark:hover:v-bg-gray-700': activeTab !== {{ $index }} && {{ $hasValue ? 'true' : 'false' }}
                }"
                class="v-inline-flex v-items-center v-gap-1 v-px-2 v-py-1 v-text-sm v-rounded v-border v-transition-colors">
            {{ $langLabel }}
        </button>
    @endforeach
</div>

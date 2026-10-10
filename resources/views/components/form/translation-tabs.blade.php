@props(['languages' => []])

@php
    $languages = \Illuminate\Support\Arr::wrap($languages);
@endphp

<div class="v:flex v:flex-wrap v:gap-1 v:mb-2">
    @foreach($languages as $index => $lang)
        @php
            $langLabel = $lang['label'] ?? '';
            $hasValue = isset($lang['value']) && $lang['value'] !== null && $lang['value'] !== '';
        @endphp
        <button type="button"
                @click="activeTab = {{ $index }}"
                :class="{
                    'v:bg-blue-100 v:dark:bg-blue-900/40 v:border-blue-300 v:dark:border-blue-700 v:font-semibold v:text-gray-900 v:dark:text-gray-100': activeTab === {{ $index }},
                    'v:bg-gray-100 v:dark:bg-gray-700/50 v:border-gray-200 v:dark:border-gray-600 v:text-gray-500 v:dark:text-gray-400 v:grayscale': activeTab !== {{ $index }} && !{{ $hasValue ? 'true' : 'false' }},
                    'v:bg-white v:dark:bg-gray-800 v:border-gray-300 v:dark:border-gray-600 v:text-gray-700 v:dark:text-gray-300 v:hover:bg-gray-50 v:dark:hover:bg-gray-700': activeTab !== {{ $index }} && {{ $hasValue ? 'true' : 'false' }}
                }"
                class="v:inline-flex v:items-center v:gap-1 v:px-2 v:py-1 v:text-sm v:rounded v:border v:transition-colors">
            {{ $langLabel }}
        </button>
    @endforeach
</div>

@props([
    'searchTerm' => '',
    'paramName' => 'search',
    'placeholder' => 'Search…',
    'searchApiUrl' => null,
])

@php
    $currentQuery = request()->except($paramName);
    $clearUrlBase = request()->url() . (count($currentQuery) ? '?' . http_build_query($currentQuery) : '');
    $clearUrl = $searchTerm !== '' ? $clearUrlBase : null;
    $inputId = 'v-table-search-' . $paramName;
    $resultsId = 'v-table-search-results-' . $paramName;
    $inputWrapperClass = 'v-flex v-items-stretch v-rounded v-shadow-sm v-border v-border-gray-300 dark:v-border-gray-600
        v-bg-white dark:v-bg-gray-800 focus-within:v-ring-1 focus-within:v-ring-gray-500 focus-within:v-border-gray-500';
    $inputClass = 'v-flex-1 v-min-w-0 v-border-0 v-border-r v-bg-transparent v-rounded-l v-rounded-r-none v-px-3 v-py-2
        v-shadow-sm sm:v-text-sm v-text-gray-900 dark:v-text-gray-100 placeholder:v-text-gray-400
        dark:placeholder:v-text-gray-500 focus:v-outline-none focus:v-ring-0 focus:v-border-gray-500';
@endphp

<div
    class="v-flex v-items-center v-gap-2 v-flex-1 v-min-w-0 {{ $searchApiUrl ? 'v-relative' : '' }}"
    @if($searchApiUrl)
        x-data="verdantTableSearch({
            apiUrl: @js($searchApiUrl),
            queryParam: @js($paramName),
            searchTerm: @js($searchTerm),
        })"
        @click.outside="open = false"
    @endif
>
    <form
        method="GET"
        action="{{ request()->url() }}"
        class="v-flex v-items-center v-gap-2 v-w-full {{ $searchApiUrl ? 'v-relative v-flex-1' : '' }}"
        role="search"
    >
        @foreach ($currentQuery as $name => $value)
            @if (is_array($value))
                @foreach ($value as $v)
                    <input type="hidden" name="{{ $name }}[]" value="{{ $v }}" />
                @endforeach
            @else
                <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
            @endif
        @endforeach
        <label for="{{ $inputId }}" class="v-sr-only">Search table</label>
        <div class="v-relative v-flex-1">
            <div class="{{ $inputWrapperClass }}">
                <input
                    type="text"
                    id="{{ $inputId }}"
                    name="{{ $paramName }}"
                    value="{{ $searchTerm }}"
                    placeholder="{{ $placeholder }}"
                    autocomplete="off"
                    class="{{ $inputClass }}"
                    aria-label="Search table"
                    @if($searchApiUrl)
                        aria-expanded="false"
                        aria-haspopup="listbox"
                        aria-controls="{{ $resultsId }}"
                        x-model="query"
                        x-ref="input"
                        @focus="if (results.length > 0 || loading) open = true"
                        @keydown.escape="open = false"
                        @keydown.arrow-down.prevent="focusNext()"
                        @keydown.arrow-up.prevent="focusPrev()"
                        @keydown.enter="handleEnter($event)"
                    @endif
                />
                <x-verdant::button.transparent
                    type="submit"
                    icon="magnifying-glass"
                    class="v-shrink-0 v-rounded-l-none v-rounded-r v-p-2 v-border-0"
                    aria-label="Search"
                />
            </div>
            @if($searchApiUrl)
                <div
                    id="{{ $resultsId }}"
                    role="listbox"
                    x-show="open && (results.length > 0 || loading)"
                    x-cloak
                    x-transition
                    class="v-absolute v-left-0 v-right-0 v-z-30 v-mt-1 v-max-h-60 v-overflow-auto v-rounded-md
                        v-border v-border-gray-200 dark:v-border-gray-600 v-bg-white
                        dark:v-bg-gray-800 v-py-1 v-shadow-lg"
                >
                    <template x-if="loading">
                        <div class="v-px-3 v-py-4 v-text-sm v-text-gray-500 dark:v-text-gray-400 v-text-center">
                            Loading…
                        </div>
                    </template>
                    <template x-if="!loading && results.length > 0">
                        <ul class="v-divide-y v-divide-gray-100 dark:v-divide-gray-700">
                            <template x-for="(item, index) in results" :key="index">
                                <li role="option" :aria-selected="focusedIndex === index">
                                    <a
                                        :href="item.url"
                                        class="v-block v-px-3 v-py-2 v-text-sm v-text-gray-900 dark:v-text-gray-100
                                            hover:v-bg-gray-50 dark:hover:v-bg-gray-700"
                                        :class="{ 'v-bg-gray-50 dark:v-bg-gray-700': focusedIndex === index }"
                                        x-text="item.label"
                                        @mouseenter="focusedIndex = index"
                                        @click="open = false"
                                    ></a>
                                </li>
                            </template>
                        </ul>
                    </template>
                </div>
            @endif
        </div>
        @if ($clearUrl !== null || $searchApiUrl)
            <a
                href="{{ $clearUrl ?? $clearUrlBase }}"
                class="v-shrink-0 v-text-sm v-text-gray-500 hover:v-text-gray-700
                    dark:hover:v-text-gray-400 hover:v-underline"

                @if($searchApiUrl)
                    x-show="query.trim() !== ''"
                    x-cloak
                @endif
            >
                Clear
            </a>
        @endif
    </form>
</div>

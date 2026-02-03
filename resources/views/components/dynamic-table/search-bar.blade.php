@props([
    'searchTerm' => '',
    'paramName' => 'search',
    'placeholder' => 'Search…',
    'searchApiUrl' => null,
])

@if ($searchApiUrl)
    <div
        class="v-relative v-w-full v-max-w-sm"
        x-data="verdantTableSearchApi({
            apiUrl: @js($searchApiUrl),
            queryParam: @js($paramName),
        })"
        @click.outside="open = false"
    >
        <label for="v-table-search-api-{{ $paramName }}" class="v-sr-only">Search</label>
        <input
            type="text"
            id="v-table-search-api-{{ $paramName }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="v-w-full v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm v-text-gray-900 dark:v-text-gray-100 placeholder:v-text-gray-500 focus:v-outline-none focus:v-ring-2 focus:v-ring-primary-500 focus:v-border-primary-500"
            aria-label="Search"
            aria-expanded="false"
            aria-haspopup="listbox"
            aria-controls="v-table-search-results-{{ $paramName }}"
            x-model="query"
            x-ref="input"
            @focus="if (results.length > 0 || loading) open = true"
            @keydown.escape="open = false"
            @keydown.arrow-down.prevent="focusNext()"
            @keydown.arrow-up.prevent="focusPrev()"
            @keydown.enter.prevent="selectFocused()"
        />
        <div
            id="v-table-search-results-{{ $paramName }}"
            role="listbox"
            x-show="open && (results.length > 0 || loading)"
            x-cloak
            x-transition
            class="v-absolute v-left-0 v-right-0 v-z-30 v-mt-1 v-max-h-60 v-overflow-auto v-rounded-md v-border v-border-gray-200 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-py-1 v-shadow-lg"
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
                                class="v-block v-px-3 v-py-2 v-text-sm v-text-gray-900 dark:v-text-gray-100 hover:v-bg-gray-50 dark:hover:v-bg-gray-700"
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
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            if (Alpine.data('verdantTableSearchApi')) return;

            Alpine.data('verdantTableSearchApi', (config) => ({
                apiUrl: config.apiUrl ?? '',
                queryParam: config.queryParam ?? 'q',
                query: '',
                results: [],
                loading: false,
                open: false,
                focusedIndex: 0,
                debounceTimer: null,

                init() {
                    this.$watch('query', () => this.debouncedSearch());
                },

                debouncedSearch() {
                    clearTimeout(this.debounceTimer);
                    if (this.query.trim() === '') {
                        this.results = [];
                        this.loading = false;
                        this.open = this.results.length > 0;

                        return;
                    }
                    this.debounceTimer = setTimeout(() => this.search(), 250);
                },

                async search() {
                    this.loading = true;
                    this.open = true;
                    try {
                        const url = new URL(this.apiUrl, window.location.origin);
                        url.searchParams.set(this.queryParam, this.query.trim());
                        const res = await fetch(url.toString(), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!res.ok) {
                            this.results = [];

                            return;
                        }
                        const data = await res.json();
                        this.results = Array.isArray(data) ? data : (data.data ?? data.results ?? []);
                        this.focusedIndex = 0;
                    } catch (e) {
                        this.results = [];
                    } finally {
                        this.loading = false;
                    }
                },

                focusNext() {
                    if (this.focusedIndex < this.results.length - 1) this.focusedIndex++;
                },

                focusPrev() {
                    if (this.focusedIndex > 0) this.focusedIndex--;
                },

                selectFocused() {
                    const item = this.results[this.focusedIndex];
                    if (item && item.url) {
                        window.location.href = item.url;
                    }
                },
            }));
        });
    </script>
@else
    @php
        $currentQuery = request()->except($paramName);
        $clearUrl = $searchTerm !== '' ? request()->url() . (count($currentQuery) ? '?' . http_build_query($currentQuery) : '') : null;
    @endphp
    <form
        method="GET"
        action="{{ request()->url() }}"
        class="v-flex v-items-center v-gap-2 v-w-full v-max-w-sm"
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
        <label for="v-table-search-{{ $paramName }}" class="v-sr-only">Search</label>
        <input
            type="text"
            id="v-table-search-{{ $paramName }}"
            name="{{ $paramName }}"
            value="{{ $searchTerm }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="v-flex-1 v-min-w-0 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm v-text-gray-900 dark:v-text-gray-100 placeholder:v-text-gray-500 focus:v-outline-none focus:v-ring-2 focus:v-ring-primary-500 focus:v-border-primary-500"
            aria-label="Search table"
        />
        <button
            type="submit"
            class="v-shrink-0 v-rounded-md v-border v-border-gray-300 dark:v-border-gray-600 v-bg-white dark:v-bg-gray-800 v-px-3 v-py-2 v-text-sm hover:v-bg-gray-50 dark:hover:v-bg-gray-700 v-text-gray-700 dark:v-text-gray-300"
        >
            Search
        </button>
        @if ($clearUrl !== null)
            <a
                href="{{ $clearUrl }}"
                class="v-shrink-0 v-text-sm v-text-gray-500 hover:v-text-gray-700 dark:hover:v-text-gray-400 hover:v-underline"
            >
                Clear
            </a>
        @endif
    </form>
@endif

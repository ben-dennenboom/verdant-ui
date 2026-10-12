document.addEventListener('alpine:init', () => {
    if (Alpine.data('verdantTableSearch')) return;

    Alpine.data('verdantTableSearch', (config) => ({
        apiUrl: config.apiUrl ?? '',
        queryParam: config.queryParam ?? 'q',
        query: config.searchTerm ?? '',
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
                this.open = false;
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

        handleEnter(e) {
            if (this.open && this.results.length > 0 && this.results[this.focusedIndex]) {
                e.preventDefault();
                this.selectFocused();
            }
        },
    }));
});

document.addEventListener('alpine:init', () => {
    if (Alpine.data('verdantTableClientSearch')) return;

    Alpine.data('verdantTableClientSearch', (config) => ({
        query: config.searchTerm ?? '',

        init() {
            this.$watch('query', () => this.filterRows());
            this.$nextTick(() => this.filterRows());
        },

        filterRows() {
            const table = this.$el.closest('[data-v-table]');
            if (!table) return;

            const terms = this.query.toLowerCase().split(/\s+/).filter((term) => term !== '');
            const rows = table.querySelectorAll('[data-v-search-row]');
            let visibleRows = 0;

            rows.forEach((row) => {
                const text = row.dataset.vSearch ?? '';
                const matches = terms.every((term) => text.includes(term));

                row.toggleAttribute('data-v-search-hidden', !matches);

                if (matches) visibleRows++;
            });

            table.querySelectorAll('[data-v-group-row]').forEach((groupRow) => {
                const group = groupRow.dataset.vGroupRow;
                const hasVisibleRow = table.querySelector(
                    `[data-v-search-row][data-v-row-group="${group}"]:not([data-v-search-hidden])`
                ) !== null;

                groupRow.toggleAttribute('data-v-search-hidden', !hasVisibleRow);
            });

            const nothingMatches = rows.length > 0 && visibleRows === 0;

            table.querySelectorAll('[data-v-search-empty]').forEach((placeholder) => {
                placeholder.toggleAttribute('data-v-search-hidden', !nothingMatches);
            });
        },
    }));
});

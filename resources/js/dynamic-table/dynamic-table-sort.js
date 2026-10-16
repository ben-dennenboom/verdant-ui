document.addEventListener('alpine:init', () => {
    if (Alpine.data('dynamicTableSort')) return;

    Alpine.data('dynamicTableSort', (initial) => ({
        columns: initial.columns ?? [],

        isActive(key) {
            return this.columns.some(c => c.key === key);
        },

        directionFor(key) {
            return this.columns.find(c => c.key === key)?.direction ?? null;
        },

        toggle(key) {
            const existing = [...this.columns];
            const idx = existing.findIndex(c => c.key === key);

            const nextDir = idx === -1
                ? 'asc'
                : existing[idx].direction === 'asc' ? 'desc' : 'asc';

            if (idx !== -1) {
                existing.splice(idx, 1);
            }

            existing.push({ key, direction: nextDir });

            this.columns = existing;
            this.navigate();
        },

        navigate() {
            const params = new URLSearchParams(window.location.search);

            if (this.columns.length) {
                params.set('sort', this.columns.map(c => c.key).join(','));
                params.set('direction', this.columns.map(c => c.direction).join(','));
            } else {
                params.delete('sort');
                params.delete('direction');
            }

            window.location.search = params.toString();
        },

        hasSorting() {
            return this.columns.length > 0
        },

        reset() {
            this.columns = [];
            this.navigate();
        },
    }))
})

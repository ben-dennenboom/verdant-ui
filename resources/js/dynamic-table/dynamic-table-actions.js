document.addEventListener('alpine:init', () => {
    if (Alpine.data('verdantTableActions')) return;

    Alpine.data('verdantTableActions', (config) => ({
        actions: config.actions ?? [],
        maxVisible: config.maxVisible ?? 2,
        visibleCount: config.maxVisible ?? 2,
        open: false,
        csrfToken: config.csrfToken ?? '',

        init() {
            this.$nextTick(() => this.updateVisibleCount());
            const el = this.$el.parentElement;
            if (el && typeof ResizeObserver !== 'undefined') {
                const ro = new ResizeObserver(() => this.updateVisibleCount());
                ro.observe(el);
            }
        },

        updateVisibleCount() {
            const el = this.$el.parentElement;
            if (!el) return;
            const w = el.offsetWidth;
            const max = Math.min(this.maxVisible, this.actions.length);
            if (w < 100) this.visibleCount = 0;
            else if (w < 170) this.visibleCount = 1;
            else this.visibleCount = max;
        },

        get visibleActions() {
            return this.actions.slice(0, this.visibleCount);
        },

        get overflowActions() {
            return this.actions.slice(this.visibleCount);
        },

        get hasOverflow() {
            return this.overflowActions.length > 0;
        },
    }));
});

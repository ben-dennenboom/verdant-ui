document.addEventListener('alpine:init', () => {
    if (Alpine.data('verdantTableBulk')) return;

    Alpine.data('verdantTableBulk', (config) => ({
        _storeKey: config.storeKey,

        init() {
            Alpine.store(this._storeKey, {
                selected: [],
                allRowKeys: (config.allRowKeys ?? []).map(String),

                isSelected(key) {
                    return this.selected.includes(String(key));
                },
                toggle(key) {
                    const k = String(key);
                    this.selected = this.selected.includes(k)
                        ? this.selected.filter(x => x !== k)
                        : [...this.selected, k];
                },
                toggleAll() {
                    this.selected = this.allRowKeys.every(k => this.selected.includes(k))
                        ? [] : [...this.allRowKeys];
                },
                clear() {
                    this.selected = [];
                },
                openRow(url) {
                    if (url) window.location.assign(url);
                },
            });
        },
    }));
});

document.addEventListener('alpine:init', () => {
    if (Alpine.data('verdantColumnPicker')) return;

    Alpine.data('verdantColumnPicker', (config) => {
        let tableEl = null;

        return {
            open: false,
            columns: [],
            saveUrl: config.saveUrl ?? null,
            csrfToken: config.csrfToken ?? '',
            orderEnabled: config.orderEnabled ?? false,
            reorderModalId: config.reorderModalId ?? null,
            draft: [],
            draggingKey: null,

            init() {
                tableEl = this.$root.closest('[data-v-table]');

                try {
                    this.columns = JSON.parse(tableEl?.dataset.vColumns ?? '[]');
                } catch {
                    this.columns = [];
                }
            },

            table() {
                return tableEl;
            },

            setVisible(key, visible) {
                const col = this.columns.find((c) => c.key === key);
                if (!col || col.pinned) return;
                col.visible = visible;
                this.apply();
            },

            showAll() {
                this.columns.forEach((c) => {
                    c.visible = true;
                });
                this.apply();
            },

            hideAll() {
                this.columns.forEach((c) => {
                    c.visible = c.pinned;
                });
                this.apply();
            },

            reset() {
                this.columns.forEach((c) => {
                    c.visible = c.pinned || c.defaultVisible;
                });
                this.apply();
            },

            openReorder() {
                this.draft = this.columns
                    .filter((c) => !c.pinned)
                    .map((c) => ({ key: c.key, label: c.label }));
                this.open = false;
                this.$dispatch('open-modal', this.reorderModalId);
            },

            dragStart(key) {
                this.draggingKey = key;
            },

            drop(targetKey) {
                if (this.draggingKey === null || this.draggingKey === targetKey) return;

                const from = this.draft.findIndex((c) => c.key === this.draggingKey);
                const to = this.draft.findIndex((c) => c.key === targetKey);
                if (from === -1 || to === -1) return;

                const [moved] = this.draft.splice(from, 1);
                this.draft.splice(to, 0, moved);
                this.draggingKey = null;
            },

            moveUp(index) {
                if (index <= 0) return;
                const [moved] = this.draft.splice(index, 1);
                this.draft.splice(index - 1, 0, moved);
            },

            moveDown(index) {
                if (index >= this.draft.length - 1) return;
                const [moved] = this.draft.splice(index, 1);
                this.draft.splice(index + 1, 0, moved);
            },

            resetOrder() {
                this.draft = this.columns
                    .filter((c) => !c.pinned)
                    .sort((a, b) => a.index - b.index)
                    .map((c) => ({ key: c.key, label: c.label }));
                this.saveOrder();
            },

            saveOrder() {
                const byKey = Object.fromEntries(this.columns.map((c) => [c.key, c]));
                const queue = this.draft.map((c) => byKey[c.key]);
                const pinnedSlots = [...this.columns]
                    .sort((a, b) => a.index - b.index)
                    .map((c) => (c.pinned ? c : null));

                this.columns = pinnedSlots.map((c) => c ?? queue.shift());
                this.apply();
            },

            apply() {
                const table = this.table();
                if (!table) return;

                window.verdantTableApplyColumns(table, this.columns, this.orderEnabled);
                table.dataset.vColumns = JSON.stringify(this.columns);

                this.persist();
            },

            persist() {
                const order = this.columns.filter((c) => !c.pinned).map((c) => c.key);

                if (this.saveUrl) {
                    fetch(this.saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            visible_columns: this.columns
                                .filter((c) => c.visible)
                                .map((c) => c.key),
                            ...(this.orderEnabled && { column_order: order }),
                        }),
                    }).catch(() => {});

                    return;
                }

                const key = this.table()?.dataset.vPersist;
                if (!key) return;

                const { write } = window.verdantTableStorage;
                write(
                    `verdant.table.columns.${key}`,
                    Object.fromEntries(this.columns.map((c) => [c.key, c.visible])),
                );
                if (this.orderEnabled) {
                    write(`verdant.table.order.${key}`, order);
                }
            },
        };
    });
});

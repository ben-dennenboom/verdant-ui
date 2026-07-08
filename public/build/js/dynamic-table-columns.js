document.addEventListener('alpine:init', () => {
    if (!Alpine.data('verdantColumnPicker')) {
        Alpine.data('verdantColumnPicker', (config) => ({
            open: false,
            storeKey: config.storeKey ?? 'vtd_default',
            columns: config.columns ?? [],
            panelId: config.panelId ?? 'columns-panel-default',

            resetAndClose() {
                const store = Alpine.store(this.storeKey);
                if (store?.reset) store.reset();
                this.open = false;
            },
            isColumnVisible(key) {
                const store = Alpine.store(this.storeKey);

                return store?.isVisible ? store.isVisible(key) : false;
            },
            setColumnVisible(key, checked) {
                const store = Alpine.store(this.storeKey);
                if (store?.setVisible) store.setVisible(key, checked);
            },
            showAll() {
                const store = Alpine.store(this.storeKey);
                if (store?.showAll) store.showAll();
            },
            hideAll() {
                const store = Alpine.store(this.storeKey);
                if (store?.hideAll) store.hideAll();
            },
        }));
    }

    if (!Alpine.data('verdantColumnReorder')) {
        Alpine.data('verdantColumnReorder', (config) => ({
            storeKey: config.storeKey ?? 'vtd_default',
            orderedColumns: [],
            draggingKey: null,

            init() {
                this.syncFromStore();
            },

            syncFromStore() {
                const store = Alpine.store(this.storeKey);
                this.orderedColumns = store?.columnsForModal ? store.columnsForModal() : [];
            },

            dragStart(key) {
                this.draggingKey = key;
            },

            drop(targetKey) {
                if (this.draggingKey === null || this.draggingKey === targetKey) return;

                const fromIndex = this.orderedColumns.findIndex((c) => c.key === this.draggingKey);
                const toIndex = this.orderedColumns.findIndex((c) => c.key === targetKey);
                if (fromIndex === -1 || toIndex === -1) return;

                const [moved] = this.orderedColumns.splice(fromIndex, 1);
                this.orderedColumns.splice(toIndex, 0, moved);
                this.draggingKey = null;
            },

            moveUp(index) {
                if (index <= 0) return;
                const [moved] = this.orderedColumns.splice(index, 1);
                this.orderedColumns.splice(index - 1, 0, moved);
            },

            moveDown(index) {
                if (index >= this.orderedColumns.length - 1) return;
                const [moved] = this.orderedColumns.splice(index, 1);
                this.orderedColumns.splice(index + 1, 0, moved);
            },

            resetOrder() {
                const store = Alpine.store(this.storeKey);
                if (store?.resetOrder) store.resetOrder();
                this.syncFromStore();
            },

            save() {
                const store = Alpine.store(this.storeKey);
                if (store?.setOrder) store.setOrder(this.orderedColumns.map((c) => c.key));
            },
        }));
    }

    if (Alpine.data('verdantTableColumns')) return;

    Alpine.data('verdantTableColumns', (config) => ({
        storageKey: config.storageKey ?? 'verdant.table.columns.default',
        orderStorageKey: config.orderStorageKey ?? null,
        storeKey: config.storeKey ?? 'vtd_default',
        allKeys: config.allKeys ?? [],
        columnWidths: config.columnWidths ?? [],
        pinned: config.pinned ?? ['actions'],
        defaultVisible: config.defaultVisible ?? null,
        storedVisible: config.storedVisible ?? null,
        columns: config.columns ?? [],
        orderEnabled: config.orderEnabled ?? false,
        defaultOrder: config.defaultOrder ?? null,
        saveUrl: config.saveUrl ?? null,
        csrfToken: config.csrfToken ?? null,
        visible: {},
        order: [],
        widthsByKey: {},

        gridTemplateForVisible() {
            const parts = [];
            this.effectiveOrder().forEach((key) => {
                if (this.visible[key] === false) {
                    return;
                }
                const w = this.widthsByKey[key] ?? null;
                parts.push(w ? w : 'minmax(0, 1fr)');
            });

            return parts.join(' ');
        },

        init() {
            this.widthsByKey = {};
            this.allKeys.forEach((key, i) => {
                this.widthsByKey[key] = this.columnWidths[i] ?? null;
            });

            if (this.saveUrl) {
                this.applyStoredOrDefaults();
            } else {
                try {
                    const stored = localStorage.getItem(this.storageKey);
                    if (stored) {
                        const parsed = JSON.parse(stored);
                        this.visible = { ...parsed };
                        this.allKeys.forEach((key) => {
                            if (!(key in this.visible)) {
                                this.visible[key] = this.visibilityDefaultFor(key);
                            }
                        });
                    } else {
                        this.applyDefaults();
                    }
                } catch (e) {
                    this.applyDefaults();
                }
            }

            if (this.orderEnabled) {
                this.loadOrder();
            }

            Alpine.store(this.storeKey, {
                visibleCount: this.allKeys.filter((k) => this.visible[k] !== false).length,
                gridTemplateColumns: this.gridTemplateForVisible(),
                orderEnabled: this.orderEnabled,
                isVisible: (k) => this.isVisible(k),
                setVisible: (k, v) => this.setVisible(k, v),
                showAll: () => this.showAll(),
                hideAll: () => this.hideAll(),
                reset: () => this.reset(),
                orderIndex: (k) => this.orderIndex(k),
                setOrder: (o) => this.setOrder(o),
                resetOrder: () => this.resetOrder(),
                columnsForModal: () => this.columnsForModal(),
            });
        },

        nonPinnedKeys() {
            return this.allKeys.filter((k) => !this.pinned.includes(k));
        },

        reconcileOrder(candidate, fallback) {
            const valid = candidate.filter((k) => fallback.includes(k));
            const missing = fallback.filter((k) => !valid.includes(k));

            return [...valid, ...missing];
        },

        loadOrder() {
            const defaultOrder = this.nonPinnedKeys();

            if (this.saveUrl) {
                this.order = this.defaultOrder
                    ? this.reconcileOrder(this.defaultOrder, defaultOrder)
                    : defaultOrder;

                return;
            }

            try {
                const stored = this.orderStorageKey ? localStorage.getItem(this.orderStorageKey) : null;
                this.order = stored ? this.reconcileOrder(JSON.parse(stored), defaultOrder) : defaultOrder;
            } catch (e) {
                this.order = defaultOrder;
            }
        },

        effectiveOrder() {
            if (!this.orderEnabled) return this.allKeys;

            const queue = [...this.order];

            return this.allKeys.map((key) => (this.pinned.includes(key) ? key : queue.shift()));
        },

        orderIndex(key) {
            if (!this.orderEnabled) return 0;
            const idx = this.effectiveOrder().indexOf(key);

            return idx === -1 ? 0 : idx;
        },

        persistOrder() {
            try {
                if (this.orderStorageKey) localStorage.setItem(this.orderStorageKey, JSON.stringify(this.order));
            } catch (e) {}

            this.syncVisibleCount();
            this.persistToServer();
        },

        setOrder(newOrderKeys) {
            const defaultOrder = this.nonPinnedKeys();
            this.order = newOrderKeys.filter((k) => defaultOrder.includes(k));
            defaultOrder.forEach((k) => {
                if (!this.order.includes(k)) this.order.push(k);
            });

            this.persistOrder();
        },

        resetOrder() {
            this.order = this.nonPinnedKeys();
            this.persistOrder();
        },

        columnsForModal() {
            const byKey = {};
            this.columns.forEach((c) => { byKey[c.key] = c; });

            return this.order
                .filter((k) => byKey[k])
                .map((k) => ({ key: k, label: byKey[k].label }));
        },

        visibilityDefaultFor(key) {
            const isPinned = this.pinned.includes(key);
            const fromDefault = this.defaultVisible === null
                ? true
                : this.defaultVisible.includes(key);

            return isPinned ? true : fromDefault;
        },

        applyDefaults() {
            this.visible = {};
            this.allKeys.forEach((key) => {
                this.visible[key] = this.visibilityDefaultFor(key);
            });
        },

        applyStoredOrDefaults() {
            if (!this.storedVisible) {
                this.applyDefaults();

                return;
            }

            this.visible = {};
            this.allKeys.forEach((key) => {
                this.visible[key] = this.pinned.includes(key) || this.storedVisible.includes(key);
            });
        },

        syncVisibleCount() {
            if (!Alpine.store || !this.storeKey) return;
            const store = Alpine.store(this.storeKey);
            if (!store) return;
            store.visibleCount = this.allKeys.filter((k) => this.visible[k] !== false).length;
            store.gridTemplateColumns = this.gridTemplateForVisible();
        },

        isVisible(key) {
            return this.visible[key] !== false;
        },

        persist() {
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
            this.persistToServer();
        },

        persistToServer() {
            if (!this.saveUrl) return;

            const payload = {
                visible_columns: this.allKeys.filter((k) => this.visible[k] !== false),
            };

            if (this.orderEnabled) {
                payload.column_order = this.order;
            }

            fetch(this.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken ?? '',
                },
                body: JSON.stringify(payload),
            }).catch(() => {});
        },

        setVisible(key, bool) {
            this.visible[key] = bool;
            this.persist();
        },

        showAll() {
            this.allKeys.forEach((k) => { this.visible[k] = true; });
            this.persist();
        },

        hideAll() {
            this.allKeys.forEach((k) => {
                if (!this.pinned.includes(k)) this.visible[k] = false;
            });
            this.persist();
        },

        reset() {
            this.applyDefaults();
            this.persist();
        },
    }));
});

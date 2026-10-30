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
        columns: config.columns ?? [],
        orderEnabled: config.orderEnabled ?? false,
        saveUrl: config.saveUrl ?? null,
        csrfToken: config.csrfToken ?? null,
        visible: { ...config.initialVisible },
        order: [...(config.initialOrder ?? [])],
        widthsByKey: {},

        gridTemplateForVisible() {
            return window.verdantColumnLayout.gridTemplate(
                this.effectiveOrder(),
                this.visible,
                this.widthsByKey,
            );
        },

        init() {
            this.widthsByKey = {};
            this.allKeys.forEach((key, i) => {
                this.widthsByKey[key] = this.columnWidths[i] ?? null;
            });

            if (!this.saveUrl) {
                const stored = window.verdantColumnLayout.readStored({
                    storageKey: this.storageKey,
                    orderStorageKey: this.orderEnabled ? this.orderStorageKey : null,
                    initialVisible: this.visible,
                    initialOrder: this.order,
                });

                if (stored) {
                    this.visible = stored.visible;
                    this.order = stored.order;
                }
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

        effectiveOrder() {
            return window.verdantColumnLayout.effectiveOrder(
                this.allKeys,
                this.pinned,
                this.order,
                this.orderEnabled,
            );
        },

        orderIndex(key) {
            if (!this.orderEnabled) return 0;
            const idx = this.effectiveOrder().indexOf(key);

            return idx === -1 ? 0 : idx;
        },

        persistOrder() {
            try {
                if (this.orderStorageKey)
                    localStorage.setItem(this.orderStorageKey, JSON.stringify(this.order));
            } catch {}

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
            this.columns.forEach((c) => {
                byKey[c.key] = c;
            });

            return this.order
                .filter((k) => byKey[k])
                .map((k) => ({ key: k, label: byKey[k].label }));
        },

        visibilityDefaultFor(key) {
            const isPinned = this.pinned.includes(key);
            const fromDefault =
                this.defaultVisible === null ? true : this.defaultVisible.includes(key);

            return isPinned ? true : fromDefault;
        },

        applyDefaults() {
            this.visible = {};
            this.allKeys.forEach((key) => {
                this.visible[key] = this.visibilityDefaultFor(key);
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
            } catch {}

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
                    Accept: 'application/json',
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
            this.allKeys.forEach((k) => {
                this.visible[k] = true;
            });
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

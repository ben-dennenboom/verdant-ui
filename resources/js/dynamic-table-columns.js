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

    if (Alpine.data('verdantTableColumns')) return;

    Alpine.data('verdantTableColumns', (config) => ({
        storageKey: config.storageKey ?? 'verdant.table.columns.default',
        storeKey: config.storeKey ?? 'vtd_default',
        allKeys: config.allKeys ?? [],
        pinned: config.pinned ?? ['actions'],
        defaultVisible: config.defaultVisible ?? null,
        visible: {},

        init() {
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

            Alpine.store(this.storeKey, {
                visibleCount: this.allKeys.filter((k) => this.visible[k] !== false).length,
                isVisible: (k) => this.isVisible(k),
                setVisible: (k, v) => this.setVisible(k, v),
                showAll: () => this.showAll(),
                hideAll: () => this.hideAll(),
                reset: () => this.reset(),
            });
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

        syncVisibleCount() {
            if (!Alpine.store || !this.storeKey) return;
            const store = Alpine.store(this.storeKey);
            if (!store) return;
            store.visibleCount = this.allKeys.filter((k) => this.visible[k] !== false).length;
        },

        isVisible(key) {
            return this.visible[key] !== false;
        },

        setVisible(key, bool) {
            this.visible[key] = bool;
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        showAll() {
            this.allKeys.forEach((k) => { this.visible[k] = true; });
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        hideAll() {
            this.allKeys.forEach((k) => {
                if (!this.pinned.includes(k)) this.visible[k] = false;
            });

            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },

        reset() {
            this.applyDefaults();

            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.visible));
            } catch (e) {}

            this.syncVisibleCount();
        },
    }));
});

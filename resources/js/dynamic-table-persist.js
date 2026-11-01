(() => {
    function read(key) {
        try {
            return JSON.parse(localStorage.getItem(key) ?? 'null');
        } catch {
            return null;
        }
    }

    function write(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch {}
    }

    function cameFromSamePage() {
        try {
            const referrer = new URL(document.referrer);

            return (
                referrer.origin === window.location.origin &&
                referrer.pathname === window.location.pathname
            );
        } catch {
            return false;
        }
    }

    function applyColumns(table, columns, orderEnabled) {
        columns.forEach((c, i) => {
            table.querySelectorAll(`[data-v-col="${CSS.escape(c.key)}"]`).forEach((el) => {
                el.style.display = c.visible ? '' : 'none';
                if (orderEnabled) el.style.order = i + 1;
            });
        });

        const template =
            columns
                .filter((c) => c.visible)
                .map((c) => c.width || 'minmax(0, 1fr)')
                .join(' ') || 'minmax(0, 1fr)';

        table.querySelectorAll('[data-v-grid]').forEach((el) => {
            el.style.gridTemplateColumns = el.dataset.vGrid + template;
        });
    }

    function restoreState(key, filters) {
        const storageKey = `verdant.table.state.${key}`;
        const params = new URLSearchParams(window.location.search);
        const managed = ['search', 'sort', 'direction', ...filters.map((f) => f.key)];
        const hasManaged = managed.some((k) => params.has(k) || params.has(k + '[]'));
        const restorable = Object.entries(read(storageKey) ?? {}).filter(([k]) =>
            managed.includes(k),
        );

        if (!hasManaged && !cameFromSamePage() && restorable.length) {
            params.delete('page');
            restorable.forEach(([k, value]) => {
                if (Array.isArray(value)) {
                    value.forEach((v) => params.append(k + '[]', v));
                } else {
                    params.set(k, value);
                }
            });
            window.location.replace(`${window.location.pathname}?${params}${window.location.hash}`);

            return true;
        }

        const state = {};
        ['search', 'sort', 'direction'].forEach((k) => {
            if (params.get(k)) state[k] = params.get(k);
        });
        filters.forEach(({ key: k, multiple }) => {
            if (multiple) {
                const values = params.getAll(k + '[]');
                if (values.length) state[k] = values;
            } else if (params.get(k)) {
                state[k] = params.get(k);
            }
        });
        write(storageKey, state);

        return false;
    }

    function restoreColumns(table, key, columns, orderEnabled) {
        const stored = read(`verdant.table.columns.${key}`);
        const visible = Array.isArray(stored?.visible_columns) ? stored.visible_columns : null;
        const order = orderEnabled ? stored?.column_order : null;
        if (!visible && !Array.isArray(order)) return;

        let effective = columns.map((c) => ({
            ...c,
            visible: c.pinned || (visible ? visible.includes(c.key) : c.visible),
        }));

        if (Array.isArray(order)) {
            const byKey = Object.fromEntries(effective.map((c) => [c.key, c]));
            const movable = effective.filter((c) => !c.pinned).map((c) => c.key);
            const queue = [
                ...order.filter((k) => movable.includes(k)),
                ...movable.filter((k) => !order.includes(k)),
            ];
            effective = [...effective]
                .sort((a, b) => a.index - b.index)
                .map((c) => (c.pinned ? c : byKey[queue.shift()]));
        }

        table.dataset.vColumns = JSON.stringify(effective);
        applyColumns(table, effective, orderEnabled);
    }

    if (!window.verdantTablePersist) {
        window.verdantTableStorage = { read, write };
        window.verdantTableApplyColumns = applyColumns;

        window.verdantTablePersist = (table) => {
            const key = table?.dataset.vPersist;
            if (!key) return;

            const filters = JSON.parse(table.dataset.vPersistFilters ?? '[]');
            if (restoreState(key, filters)) {
                table.style.visibility = 'hidden';

                return;
            }

            if (table.hasAttribute('data-v-persist-columns')) {
                restoreColumns(
                    table,
                    key,
                    JSON.parse(table.dataset.vColumns ?? '[]'),
                    table.hasAttribute('data-v-persist-order'),
                );
            }
        };
    }
})();

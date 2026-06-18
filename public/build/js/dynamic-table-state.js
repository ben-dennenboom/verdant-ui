window.verdantTableState = function (config) {
    const storageKey = config.storageKey ?? 'verdant.table.state.default';
    const searchParam = 'search';
    const sortParam = 'sort';
    const directionParam = 'direction';
    const filters = config.filters ?? [];
    const managedKeys = [searchParam, sortParam, directionParam, ...filters.map((f) => f.key)];

    function cameFromSamePage() {
        if (!document.referrer) return false;

        try {
            const referrer = new URL(document.referrer);
            return referrer.origin === window.location.origin && referrer.pathname === window.location.pathname;
        } catch (e) {
            return false;
        }
    }

    function restoreFromStorage(params) {
        let stored;
        try {
            stored = JSON.parse(localStorage.getItem(storageKey) ?? 'null');
        } catch (e) {
            stored = null;
        }

        if (!stored || Object.keys(stored).length === 0) return false;

        const next = new URLSearchParams(params);
        Object.entries(stored).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach((v) => next.append(key + '[]', v));
            } else {
                next.set(key, value);
            }
        });

        window.location.search = next.toString();
        return true;
    }

    function saveToStorage(params) {
        const state = {};

        const search = params.get(searchParam);
        if (search) state[searchParam] = search;

        const sort = params.get(sortParam);
        if (sort) state[sortParam] = sort;

        const direction = params.get(directionParam);
        if (direction) state[directionParam] = direction;

        filters.forEach(({ key, multiple }) => {
            if (multiple) {
                const values = params.getAll(key + '[]');
                if (values.length) state[key] = values;
            } else if (params.has(key)) {
                const value = params.get(key);
                if (value !== '') state[key] = value;
            }
        });

        try {
            localStorage.setItem(storageKey, JSON.stringify(state));
        } catch (e) {}
    }

    const params = new URLSearchParams(window.location.search);
    const hasManagedParam = managedKeys.some((key) => params.has(key) || params.has(key + '[]'));

    if (!hasManagedParam && !cameFromSamePage() && restoreFromStorage(params)) {
        return;
    }

    saveToStorage(params);
};

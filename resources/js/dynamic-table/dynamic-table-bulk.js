function rowCheckboxes(formId) {
    return [
        ...document.querySelectorAll(`input[data-v-row-checkbox][form="${CSS.escape(formId)}"]`),
    ];
}

function syncMulti(details) {
    const boxes = [...details.querySelectorAll('input[type="checkbox"]')];
    const checked = boxes.filter((b) => b.checked);
    const label = details.querySelector('[data-v-multi-label]');
    if (!label) return;

    if (checked.some((b) => b.value === '_none')) {
        label.textContent = details.dataset.vNoneLabel;
    } else if (checked.length) {
        label.textContent = `${checked.length} ${details.dataset.vCountLabel}`;
    } else {
        label.textContent = details.dataset.vPlaceholder;
    }
}

function sync(formId) {
    const boxes = rowCheckboxes(formId);
    const all = new Set(boxes.map((b) => b.value));
    const selected = new Set(boxes.filter((b) => b.checked).map((b) => b.value));

    document.querySelectorAll(`input[data-v-select-all="${CSS.escape(formId)}"]`).forEach((el) => {
        el.checked = all.size > 0 && selected.size === all.size;
        el.indeterminate = selected.size > 0 && selected.size < all.size;
    });
}

document.addEventListener('change', (e) => {
    const el = e.target;

    const multi = el.closest('[data-v-multi]');
    if (multi && el.checked) {
        multi.querySelectorAll('input[type="checkbox"]').forEach((b) => {
            if (b !== el && (el.value === '_none' || b.value === '_none')) b.checked = false;
        });
    }
    if (multi) {
        syncMulti(multi);

        return;
    }

    if (el.matches('input[data-v-select-all]')) {
        const formId = el.dataset.vSelectAll;
        rowCheckboxes(formId).forEach((b) => {
            b.checked = el.checked;
        });
        sync(formId);
    } else if (el.matches('input[data-v-row-checkbox][form]')) {
        const formId = el.getAttribute('form');
        rowCheckboxes(formId)
            .filter((b) => b.value === el.value)
            .forEach((b) => {
                b.checked = el.checked;
            });
        sync(formId);
    }
});

document.addEventListener('reset', (e) => {
    const formId = e.target.id;
    if (!formId) return;
    setTimeout(() => {
        sync(formId);
        e.target.querySelectorAll('[data-v-multi]').forEach(syncMulti);
    });
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('input[data-v-select-all]').forEach((el) => {
        sync(el.dataset.vSelectAll);
    });
});

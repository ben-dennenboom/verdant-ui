const ignored =
    'a, button, input, select, textarea, label, summary, details, [data-v-no-row-click]';

function rowFor(e) {
    if (e.target.closest(ignored)) return null;

    return e.target.closest('[data-v-open-on], [data-v-row-select]');
}

if (!window.verdantTableRowsInit) {
    window.verdantTableRowsInit = true;

    document.addEventListener('click', (e) => {
        const row = rowFor(e);
        if (!row) return;

        if (e.shiftKey && row.hasAttribute('data-v-row-select')) {
            const checkbox = row.querySelector('input[name="_ids[]"]');
            if (!checkbox) return;
            e.preventDefault();
            window.getSelection()?.removeAllRanges();
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change', { bubbles: true }));

            return;
        }

        if (row.dataset.vOpenOn === 'click') {
            if (!row.dataset.vOpenUrl || window.getSelection()?.toString()) return;

            if (e.metaKey || e.ctrlKey) {
                window.open(row.dataset.vOpenUrl, '_blank', 'noopener');
            } else {
                window.location.assign(row.dataset.vOpenUrl);
            }
        } else if (row.dataset.vOpenOn === 'dblclick' && !row.hasAttribute('data-v-row-select')) {
            row.closest('[data-v-table]')
                ?.querySelectorAll('[data-v-active]')
                .forEach((el) => el.removeAttribute('data-v-active'));
            row.setAttribute('data-v-active', '');
        }
    });

    document.addEventListener('auxclick', (e) => {
        if (e.button !== 1) return;

        const row = rowFor(e);
        if (!row || row.dataset.vOpenOn !== 'click' || !row.dataset.vOpenUrl) return;

        e.preventDefault();
        window.open(row.dataset.vOpenUrl, '_blank', 'noopener');
    });

    document.addEventListener('dblclick', (e) => {
        const row = rowFor(e);
        if (!row || row.dataset.vOpenOn !== 'dblclick' || !row.dataset.vOpenUrl) return;

        window.location.assign(row.dataset.vOpenUrl);
    });
}

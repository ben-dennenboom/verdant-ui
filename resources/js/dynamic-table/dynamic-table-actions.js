const menus = '[data-v-action-menu][open], [data-v-multi][open]';

if (!window.verdantTableActionsInit) {
    window.verdantTableActionsInit = true;

    document.addEventListener('click', (e) => {
        document.querySelectorAll(menus).forEach((menu) => {
            if (!menu.contains(e.target)) menu.open = false;
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;

        document.querySelectorAll(menus).forEach((menu) => {
            const hadFocus = menu.contains(document.activeElement);
            menu.open = false;
            if (hadFocus) menu.querySelector('summary')?.focus();
        });
    });
}

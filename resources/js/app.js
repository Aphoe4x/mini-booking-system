import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: window.matchMedia('(min-width: 1024px)').matches,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    });
});

Alpine.start();

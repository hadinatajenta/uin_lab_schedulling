import "./bootstrap";
import "../css/app.css";

import Alpine from "alpinejs";
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;

import importUsers from "./import-users";

document.addEventListener('alpine:init', () => {
    Alpine.data('importUsers', importUsers);

    Alpine.store('sidebar', {
        expanded: localStorage.getItem('sidebarExpanded') !== 'false',
        openMenus: JSON.parse(localStorage.getItem('openMenus')) || {},
        isMobileOpen: false,

        toggle(menuId) {
            this.openMenus[menuId] = !this.openMenus[menuId];
            this.saveState();
        },

        isOpen(menuId) {
            return this.openMenus[menuId] === true;
        },

        setOpen(menuId, value) {
            this.openMenus[menuId] = value;
            this.saveState();
        },

        toggleExpanded() {
            this.expanded = !this.expanded;
            localStorage.setItem('sidebarExpanded', this.expanded);
        },

        saveState() {
            localStorage.setItem('openMenus', JSON.stringify(this.openMenus));
        }
    });
});

Alpine.start();

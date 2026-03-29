/**
 * Alpine.js navbar component
 * - Sticky při scrollu
 * - Auto-hide při scrollu dolů (skrýt) / nahoru (zobrazit)
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('navbar', () => ({
        isOpen:       false,
        isSticky:     false,
        isHidden:     false,
        openDropdown: null,
        _lastY:       0,
        _ticking:     false,
        stickyOffset: 70,

        init() {
            this._lastY = window.scrollY;
            this.handleScroll();
        },

        handleScroll() {
            const y = window.scrollY;

            // Sticky state
            this.isSticky = y > this.stickyOffset;

            // Auto-hide: skrýt při scrollu dolů > 200px, zobrazit při scrollu nahoru
            if (y <= 200) {
                this.isHidden = false;
            } else if (y > this._lastY + 4) {
                this.isHidden = true;
                this.isOpen   = false; // zavři mobilní menu
            } else if (y < this._lastY - 4) {
                this.isHidden = false;
            }

            this._lastY = y;
        },

        toggleMenu() {
            this.isOpen = !this.isOpen;
        },

        closeMenu() {
            this.isOpen       = false;
            this.openDropdown = null;
        },

        toggleDd(name) {
            this.openDropdown = this.openDropdown === name ? null : name;
        },

        isDdOpen(name) {
            return this.openDropdown === name;
        },
    }));
});

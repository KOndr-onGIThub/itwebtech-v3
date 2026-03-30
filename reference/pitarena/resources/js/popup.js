/**
 * Popup management — Alpine.js komponenty
 * Portováno z main_extension.js (bez jQuery)
 *
 * Konfigurace (v Alpine.data níže):
 *   active    — manuální přepínač (true = zapnuto, false = vypnuto)
 *   from      — datum od 'YYYY-MM-DD', od kdy se popup zobrazuje (nebo null = bez omezení)
 *   to        — datum do 'YYYY-MM-DD', do kdy se popup zobrazuje (nebo null = bez omezení)
 *   popupId   — unikátní ID obsahu; změň ho pokaždé, když změníš text popupu
 *               → tím se automaticky resetuje "nezobrazovat" pro všechny uživatele
 *   checkboxId — id checkboxu "nezobrazovat" v HTML
 *   delay     — prodleva před zobrazením v ms
 */

function makePopupData({ active, from, to, popupId, checkboxId, delay = 1500 }) {
    return {
        show: false,

        init() {
            // 1. manuální přepínač
            if (!active) return;

            // 2. datum-check
            const now = new Date();
            if (from && now < new Date(from)) return;
            if (to   && now > new Date(to + 'T23:59:59')) return;

            // 3. uživatel kliknul "nezobrazovat navždy"
            if (localStorage.getItem('popup_hidden_' + popupId)) return;

            // 4. popup už byl zobrazen v této relaci
            if (sessionStorage.getItem('popup_shown_' + popupId)) return;

            // Označit relaci a zobrazit po prodlevě
            sessionStorage.setItem('popup_shown_' + popupId, '1');
            setTimeout(() => { this.show = true; }, delay);
        },

        close() {
            this.show = false;
            const cb = document.getElementById(checkboxId);
            if (cb && cb.checked) {
                localStorage.setItem('popup_hidden_' + popupId, '1');
            }
        },
    };
}

document.addEventListener('alpine:init', () => {

    // ── Popup: Dovolená ──────────────────────────────────────────────
    Alpine.data('dovolePopup', () => makePopupData({
        active:     false,                // manuální přepínač
        from:       '2025-08-04',         // datum od
        to:         '2025-08-11',         // datum do
        popupId:    '2025-08-dovolena',   // změň při změně obsahu → resetuje "nezobrazovat"
        checkboxId: 'popup-hide-forever-manual',
        delay:      2000,
    }));

    // ── Popup: Akce ──────────────────────────────────────────────────
    Alpine.data('akcePopup', () => makePopupData({
        active:     true,                // manuální přepínač
        from:       '2026-01-25',         // datum od
        to:         '2026-03-15',         // datum do
        popupId:    '2026-01-akcex',       // změň při změně obsahu → resetuje "nezobrazovat"
        checkboxId: 'popup-hide-forever',
        delay:      1500,
    }));

});

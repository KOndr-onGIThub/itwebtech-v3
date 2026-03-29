import './bootstrap';

// ── AOS ─────────────────────────────────────────────────────────
import AOS from 'aos';
window.AOS = AOS;

// Na mobilu nahraď horizontální AOS animace vertikálními (zabraňuje overflow)
if (window.innerWidth < 768) {
  const hAos = ['fade-right','fade-left','slide-right','slide-left','zoom-right','zoom-left'];
  document.querySelectorAll('[data-aos]').forEach(el => {
    if (hAos.includes(el.getAttribute('data-aos'))) {
      el.setAttribute('data-aos', 'fade-up');
    }
  });
}

AOS.init({
  once: true,
  duration: 750,
  easing: 'ease-out-cubic',
  offset: 60,
});

// Automatický AOS refresh při změně výšky SimpleShop formuláře (iframe resize)
(function () {
  const container = document.querySelector('[data-SimpleShopForm]');
  if (!container || !window.ResizeObserver) return;
  const observer = new ResizeObserver(() => AOS.refresh());
  observer.observe(container);
  setTimeout(() => observer.disconnect(), 15000);
})();

// ── Alpine.js ──────────────────────────────────────────────────
import Alpine from 'alpinejs';
window.Alpine = Alpine;

import './navbar';
import './popup';

// ── Floating tooltip (Floating UI, lazy-loaded) ──────────────────
Alpine.data('floatingTooltip', (preferredPlacement = 'top') => ({
  open: false,
  x: -9999,
  y: -9999,
  preferredPlacement,

  async _reposition() {
    const { computePosition, flip, shift, offset } = await import('@floating-ui/dom');
    const { x, y } = await computePosition(this.$refs.trigger, this.$refs.tooltip, {
      strategy: 'fixed',
      placement: this.preferredPlacement,
      middleware: [offset(8), flip(), shift({ padding: 8 })],
    });
    this.x = x;
    this.y = y;
  },

  async toggle() {
    if (this.open) { this.open = false; return; }
    await this._reposition();
    this.open = true;
  },

  preload() { import('@floating-ui/dom'); },
}));

Alpine.start();

// ── Swiper carousely ────────────────────────────────────────────
import './carousel';

// ── LightGallery ────────────────────────────────────────────────
import './gallery';

// ── Cup registration (Vanilla JS, no jQuery) ────────────────────
import './registration';

// ── Forms: Kontaktní + MotoShop (Vanilla JS, axios + SweetAlert2) ──
import './forms';

// ── Premium effects: spotlight, counter, toast ──────────────────
import './effects';

// ── Cookie consent + GTM conditional load ───────────────────────
import './cookies';

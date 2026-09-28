// OND-449 (B-07b) · Video smyčka webu na detailu projektu (`<x-portfolio.demo-video>`).
// V HTML je `preload="none"`, takže se nic nestáhne, dokud video není u obrazovky.
// Hraje jen, když je vidět aspoň z poloviny; omezený pohyb a Save-Data → jen plakát.
// Pauza drží do konce návštěvy (sdílí klíč se záznamy na homepage, WCAG 2.2.2).
// Sám se vypne, když na stránce není `[data-demo-video]`.
(() => {
    const frames = [...document.querySelectorAll('[data-demo-video]')];
    if (!frames.length) return;

    const still = matchMedia('(prefers-reduced-motion: reduce)').matches
        || navigator.connection?.saveData === true;
    if (still || !('IntersectionObserver' in window)) {
        frames.forEach(f => f.classList.add('pd-demo--still'));
        return;
    }

    const KEY = 'pd-live-paused';
    let paused = sessionStorage.getItem(KEY) === '1';
    const visible = new Set();

    const sync = () => frames.forEach(f => {
        const v = f.querySelector('video');
        if (visible.has(f) && !paused) {
            if (v.preload !== 'auto') v.preload = 'auto';
            v.play().catch(() => {});
        } else if (!v.paused) {
            v.pause();
        }
    });

    const setLabels = () => frames.forEach(f => {
        const b = f.querySelector('.pd-demo__toggle');
        b.setAttribute('aria-pressed', String(paused));
        b.setAttribute('aria-label', paused ? b.dataset.labelPlay : b.dataset.labelPause);
    });

    const seen = new IntersectionObserver(es => {
        es.forEach(e => (e.intersectionRatio >= 0.5 ? visible.add(e.target) : visible.delete(e.target)));
        sync();
    }, { threshold: [0, 0.5, 1] });

    setLabels();
    frames.forEach(f => {
        const v = f.querySelector('video');
        v.addEventListener('playing', () => v.classList.add('is-ready'));
        f.querySelector('.pd-demo__toggle').addEventListener('click', () => {
            paused = !paused;
            sessionStorage.setItem(KEY, paused ? '1' : '0');
            setLabels();
            sync();
        });
        seen.observe(f);
    });

    // Změna šířky přes zlom 768 px: <source media> se vybírá jen při načtení.
    matchMedia('(max-width: 767px)').addEventListener('change', () => frames.forEach(f => {
        const v = f.querySelector('video');
        if (v.preload !== 'auto') return;
        v.classList.remove('is-ready');
        v.load();
        sync();
    }));
})();

// OND-440 (návrh 4, specifikace OND-439) · Záznamy živých webů v případovkách.
// Hraje jen záznam na obrazovce, video se stáhne až při přiblížení,
// omezený pohyb a Save-Data → jen plakát (video se nestáhne vůbec).
// Sám se vypne, když na stránce není `[data-live]`.
(() => {
    const frames = [...document.querySelectorAll('[data-live]')];
    if (!frames.length) return;

    const still = matchMedia('(prefers-reduced-motion: reduce)').matches
        || navigator.connection?.saveData === true;
    if (still || !('IntersectionObserver' in window)) {
        frames.forEach(f => f.classList.add('pd-live--still'));
        return;
    }

    const wide = matchMedia('(min-width: 768px)');
    const KEY = 'pd-live-paused';
    let paused = sessionStorage.getItem(KEY) === '1';
    const ratio = new Map();

    // Zdroje nastaví až JS: bez <source> v HTML prohlížeč nic nestahuje (ani s preload="auto").
    const arm = f => {
        const v = f.querySelector('video');
        const variant = wide.matches ? 'desktop' : 'mobile';
        if (v.dataset.variant === variant) return v;
        v.dataset.variant = variant;
        v.preload = 'auto';
        v.classList.remove('is-ready');
        v.replaceChildren(...JSON.parse(f.dataset.live)[variant].map(([src, type]) =>
            Object.assign(document.createElement('source'), { src, type })));
        v.load();
        return v;
    };

    // Hraje jediný rám, který je vidět nejvíc a aspoň ze 60 %. Ostatní stojí, kde se zastavily.
    const sync = () => {
        let best = null, max = 0.6;
        ratio.forEach((r, f) => { if (r >= max) { max = r; best = f; } });
        frames.forEach(f => {
            const v = f.querySelector('video');
            if (f === best && !paused) arm(f).play().catch(() => {});
            else if (!v.paused) v.pause();
        });
    };

    const setLabels = () => frames.forEach(f => {
        const b = f.querySelector('.pd-live__toggle');
        b.setAttribute('aria-pressed', String(paused));
        b.setAttribute('aria-label', paused ? b.dataset.labelPlay : b.dataset.labelPause);
    });

    // „Přiblížení“ = čtvrt obrazovky předem.
    const near = new IntersectionObserver(es => es.forEach(e => {
        if (e.isIntersecting && !paused) { arm(e.target); near.unobserve(e.target); }
    }), { rootMargin: '25% 0px' });
    const seen = new IntersectionObserver(es => {
        es.forEach(e => ratio.set(e.target, e.intersectionRatio));
        sync();
    }, { threshold: [0, 0.25, 0.6, 0.8, 1] });

    setLabels();
    frames.forEach(f => {
        const v = f.querySelector('video');
        v.addEventListener('playing', () => v.classList.add('is-ready'));
        // Pauza (WCAG 2.2.2) platí pro všechny záznamy a drží do konce návštěvy.
        f.querySelector('.pd-live__toggle').addEventListener('click', () => {
            paused = !paused;
            sessionStorage.setItem(KEY, paused ? '1' : '0');
            setLabels();
            sync();
        });
        near.observe(f);
        seen.observe(f);
    });

    // Změna šířky: už načtený rám přepne na druhou variantu.
    wide.addEventListener('change', () => {
        frames.forEach(f => { if (f.querySelector('video').dataset.variant) arm(f); });
        sync();
    });
})();

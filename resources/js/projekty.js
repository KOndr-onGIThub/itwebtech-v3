// ---------------------------------------------------------------------------
// OND-470 — věta nad přehledem /projekty: „Potřebuju [web ▾] pro [obor ▾].“
// Šablona: resources/views/components/portfolio/filter.blade.php.
//
// Žádná knihovna, žádná smyčka na snímek, nic navázaného na scroll:
//   - změna výběru přeskládá karty v DOM (shody nahoru) a jednou je
//     dorovná FLIPem,
//   - při první návštěvě se nad větou jednou ukáže bublina s nápovědou
//     a zmizí při jakékoli interakci s větou.
// `prefers-reduced-motion` = bez FLIPu, bublina se jen objeví.
// ---------------------------------------------------------------------------

const reduceMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const readI18n = (el) => {
    try { return JSON.parse(el.dataset.i18n || '{}'); } catch { return {}; }
};

// „17 projektů“ / „1 projekt“ / „3 projekty“ podle pravidel jazyka stránky.
const countLabel = (forms, n) => {
    const rule = new Intl.PluralRules(document.documentElement.lang || 'cs').select(n);
    return (forms[rule] ?? forms.other ?? ':n').replace(':n', n);
};

function initIntent(form) {
    const grid = document.getElementById(form.dataset.target || 'portfolio-grid');
    if (!grid) return;
    const i18n = readI18n(form);
    const total = Number(form.dataset.total) || 0;
    const sentence = form.querySelector('.pd-intent__sentence');
    const status = form.querySelector('[data-intent-status]');
    const cta = form.querySelector('[data-intent-cta]');
    const rest = grid.querySelector('[data-intent-rest]');
    const cards = [...grid.querySelectorAll('.pd-work')];
    const selects = [...form.querySelectorAll('select')];
    const valueOf = (sel) => sel.parentElement.querySelector('.pd-intent__value');

    // Obnova z URL (návrat z detailu, sdílený odkaz).
    const params = new URLSearchParams(location.search);
    selects.forEach((sel) => {
        const v = params.get(sel.name);
        if (v && [...sel.options].some((o) => o.value === v)) sel.value = v;
    });

    const mirror = (sel) => { valueOf(sel).textContent = sel.selectedOptions[0].textContent; };

    const apply = (animate) => {
        const co = form.elements.co.value;
        const pro = form.elements.pro.value;
        const all = co === 'all' && pro === 'all';
        const inSector = (card) => (card.dataset.sectors || '').split(' ').includes(pro);
        const match = (card) =>
            (co === 'all' || card.dataset.category === co) && (pro === 'all' || inSector(card));

        const hits = cards.filter(match);
        // Zbytek: napřed ty, které sedí aspoň v jednom slově věty — „nejbližší“
        // v hlášce pak platí doslova. Uvnitř skupin původní pořadí.
        const half = (c) => (co !== 'all' && c.dataset.category === co) || (pro !== 'all' && inSector(c));
        const misses = cards.filter((c) => !match(c));
        misses.sort((a, b) => Number(half(b)) - Number(half(a)));

        // FLIP — First: pozice před přeskládáním.
        const before = animate && !reduceMotion() ? new Map(cards.map((c) => [c, c.getBoundingClientRect()])) : null;

        // Přeskládání v DOM: shody, předěl, zbytek.
        const frag = document.createDocumentFragment();
        hits.forEach((c) => frag.appendChild(c));
        frag.appendChild(rest);
        misses.forEach((c) => frag.appendChild(c));
        grid.appendChild(frag);

        // Bez jediné shody se nic netlumí — „nejbližší“ jsou pak celá mřížka.
        const dim = !all && hits.length > 0;
        cards.forEach((c) => { c.dataset.intent = !dim || match(c) ? 'hit' : 'rest'; });
        rest.hidden = !dim || misses.length === 0;

        // Last / Invert / Play.
        if (before) {
            cards.forEach((c) => {
                const a = before.get(c);
                const b = c.getBoundingClientRect();
                const dx = a.left - b.left;
                const dy = a.top - b.top;
                if (Math.abs(dx) < 1 && Math.abs(dy) < 1) return;
                c.animate(
                    [{ transform: `translate(${dx}px, ${dy}px)` }, { transform: 'none' }],
                    { duration: 520, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' }
                );
            });
        }

        // Hlášení (čtečka + vidící). Stejný text se nepřepisuje, ať ho
        // čtečka při načtení neohlásí znovu.
        let text;
        if (all) text = countLabel(i18n.count || {}, total);
        else if (hits.length === 0) text = i18n.none;
        else text = (i18n.match || ':n / :total').replace(':n', hits.length).replace(':total', total);
        if (status.textContent !== text) status.textContent = text;
        cta.hidden = all;

        // URL bez nového záznamu v historii.
        const url = new URL(location.href);
        co === 'all' ? url.searchParams.delete('co') : url.searchParams.set('co', co);
        pro === 'all' ? url.searchParams.delete('pro') : url.searchParams.set('pro', pro);
        if (url.href !== location.href) history.replaceState(history.state, '', url);
    };

    // Rámeček fokusu jen při ovládání klávesnicí. `:focus-visible` na
    // `<select>` se po klepnutí prstem v některých prohlížečích rozsvítí
    // taky, proto si poslední způsob ovládání pamatujeme sami.
    const setInput = (kind) => () => { form.dataset.input = kind; };
    document.addEventListener('pointerdown', setInput('pointer'), { capture: true, passive: true });
    document.addEventListener('keydown', setInput('keyboard'), { capture: true, passive: true });

    const stopHint = initHint(form, sentence, selects);

    selects.forEach((sel) => {
        mirror(sel);
        sel.addEventListener('change', () => { stopHint(); mirror(sel); apply(true); });
    });

    form.addEventListener('submit', (e) => e.preventDefault());
    apply(false);
}

// ---------------------------------------------------------------------------
// OND-478 — nápověda pro první návštěvu: bublina nad prvním slovem
// „Tady si vyberte, co potřebujete.“ Klepnutí na ni otevře výběr.
// Nahradila ukázku věty (OND-470): slova, která se sama přepisují,
// vypadala jako dekorativní rotující nadpis, ne jako ovládání.
//
// Spustí se, až je celá věta v okně nad lištou „Poptávka“ (spodních
// 88 px), stránka je otevřená aspoň HINT_AFTER a scroll stojí HINT_IDLE.
// Na mobilu, kde je věta níž, tedy až když k ní návštěvník dojede
// a zastaví se. Bublina je absolutně nad větou: nic se neposune (CLS 0).
// Zmizí po první interakci s větou (dotyk, klik, fokus, změna, Esc)
// a v téže relaci se už neukáže ani po návratu z detailu. S výběrem
// z URL se neukáže vůbec. Čtečka nic navíc nečte (`aria-hidden`).
// `prefers-reduced-motion`: bublina se jen objeví, bez vyjetí (CSS).
// ---------------------------------------------------------------------------
const HINT_KEY = 'pd-intent-hint';
const HINT_AFTER = 1500;
const HINT_IDLE = 600;

function initHint(form, sentence, selects) {
    const hint = form.querySelector('[data-intent-hint]');
    const noop = () => {};
    let seen = false;
    try { seen = sessionStorage.getItem(HINT_KEY) === '1'; } catch { /* soukromý režim */ }
    if (!hint || seen || selects.some((s) => s.value !== 'all')) return noop;
    if (!('IntersectionObserver' in window)) return noop;

    const word = form.querySelector('.pd-intent__value');
    let done = false;
    let shown = false;
    let timer = 0;
    let inView = false;
    let lastScroll = 0;
    const t0 = performance.now();

    const onScroll = () => { lastScroll = performance.now(); schedule(); };
    const onKey = (e) => { if (e.key === 'Escape') stop(); };
    const onPointer = (e) => { if (!hint.contains(e.target)) stop(); };

    const stop = () => {
        if (done) return;
        done = true;
        clearTimeout(timer);
        observer.disconnect();
        removeEventListener('scroll', onScroll);
        removeEventListener('resize', place);
        form.removeEventListener('pointerdown', onPointer, true);
        form.removeEventListener('focusin', stop);
        document.removeEventListener('keydown', onKey);
        hint.classList.add('is-gone');
    };

    // Šipka míří na začátek prvního slova, bublina se vejde do šířky.
    function place() {
        const f = form.getBoundingClientRect();
        const w = word.getBoundingClientRect();
        const target = w.left - f.left + Math.min(w.width / 2, 40);
        const left = Math.max(0, Math.min(target - 28, f.width - hint.offsetWidth));
        hint.style.setProperty('--hint-left', `${left}px`);
        hint.style.setProperty('--hint-arrow', `${target - left}px`);
    }

    const show = () => {
        if (done || shown) return;
        shown = true;
        observer.disconnect();
        removeEventListener('scroll', onScroll);
        try { sessionStorage.setItem(HINT_KEY, '1'); } catch { /* soukromý režim */ }
        hint.hidden = false;
        place();
        hint.classList.add('is-on');
        addEventListener('resize', place, { passive: true });
    };

    const schedule = () => {
        clearTimeout(timer);
        if (!inView || done || shown) return;
        const now = performance.now();
        const ms = Math.max(HINT_IDLE - (now - lastScroll), HINT_AFTER - (now - t0), 0);
        timer = setTimeout(() => {
            if (!inView || document.visibilityState !== 'visible') return;
            if (performance.now() - lastScroll < HINT_IDLE - 20) return schedule();
            document.fonts.ready.then(show);
        }, ms);
    };

    addEventListener('scroll', onScroll, { passive: true });
    const observer = new IntersectionObserver((entries) => {
        inView = entries[entries.length - 1].isIntersecting;
        schedule();
    // Spodní okraj: na mobilu přes spodek okna leží lišta „Poptávka“,
    // věta pod ní se nepočítá jako viditelná.
    }, { threshold: 1, rootMargin: '0px 0px -88px 0px' });
    observer.observe(sentence);

    form.addEventListener('pointerdown', onPointer, { capture: true, passive: true });
    form.addEventListener('focusin', stop);
    document.addEventListener('keydown', onKey);

    // Bublina dělá, co říká: klepnutí otevře první výběr.
    hint.addEventListener('click', () => {
        stop();
        const sel = selects[0];
        sel.focus({ preventScroll: true });
        try { sel.showPicker(); } catch { /* prohlížeč bez showPicker u selectu: stačí fokus */ }
    });

    return stop;
}

const run = () => {
    const form = document.querySelector('[data-intent]');
    if (form) initIntent(form);
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
else run();

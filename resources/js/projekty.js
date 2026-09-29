// ---------------------------------------------------------------------------
// OND-470 — věta nad přehledem /projekty: „Potřebuju [web ▾] pro [obor ▾].“
// Šablona: resources/views/components/portfolio/filter.blade.php.
//
// Žádná knihovna, žádná smyčka na snímek, nic navázaného na scroll:
//   - změna výběru přeskládá karty v DOM (shody nahoru) a jednou je
//     dorovná FLIPem,
//   - ukázka věty při prvním zobrazení jen přepisuje dvě slova v časovači
//     a zastaví se při jakékoli interakci.
// `prefers-reduced-motion` = bez FLIPu a bez ukázky.
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

    const stopDemo = initDemo(form, sentence, selects, valueOf, mirror);

    selects.forEach((sel) => {
        mirror(sel);
        sel.addEventListener('change', () => { stopDemo(); mirror(sel); apply(true); });
    });

    form.addEventListener('submit', (e) => e.preventDefault());
    apply(false);
}

// ---------------------------------------------------------------------------
// Ukázka věty (OND-470, rozhodnutí CEO): jednou při prvním zobrazení věta
// sama projde pár možností a vrátí se na výchozí znění — návštěvník hned
// vidí, že slova jsou ovládání. Mění jen viditelný text dvou slov; výběr,
// mřížka, URL ani hlášení pro čtečku se nemění (slova jsou `aria-hidden`).
//
// Nespustí se: s `prefers-reduced-motion`, s výběrem z URL, podruhé
// v téže relaci (návrat z detailu). Zastaví ji jakákoli interakce:
// pointer, klávesa, kolečko, dotyk, fokus i změna výběru.
// Stav, který by změnil počet řádků věty, se přeskočí — mřížka pod větou
// se tak nikdy neposune (žádný layout shift).
// ---------------------------------------------------------------------------
const DEMO_KEY = 'pd-intent-demo';
const DEMO_STEPS = [
    { co: 'website', pro: 'all' },
    { co: 'website', pro: 'vyroba' },
    { co: 'application', pro: 'vyroba' },
];

function initDemo(form, sentence, selects, valueOf, mirror) {
    const noop = () => {};
    let seen = false;
    try { seen = sessionStorage.getItem(DEMO_KEY) === '1'; } catch { /* soukromý režim */ }
    if (seen || reduceMotion() || selects.some((s) => s.value !== 'all')) return noop;
    if (!('IntersectionObserver' in window)) return noop;

    const [co, pro] = selects;
    const label = (sel, value) => [...sel.options].find((o) => o.value === value)?.textContent;
    const show = (state, fade) => {
        [[co, state.co], [pro, state.pro]].forEach(([sel, value]) => {
            const el = valueOf(sel);
            const text = label(sel, value);
            if (!text || el.textContent === text) return;
            el.textContent = text;
            if (fade) el.animate([{ opacity: 0, transform: 'translateY(0.12em)' }, { opacity: 1, transform: 'none' }], { duration: 260, easing: 'ease-out' });
        });
    };

    let timer = 0;
    let running = false;
    let observer = null;
    const events = ['pointerdown', 'keydown', 'wheel', 'touchstart'];

    const stop = () => {
        clearTimeout(timer);
        observer?.disconnect();
        events.forEach((e) => document.removeEventListener(e, stop, true));
        if (running) {
            running = false;
            form.classList.remove('is-demo');
            selects.forEach(mirror);
        }
    };
    events.forEach((e) => document.addEventListener(e, stop, { capture: true, passive: true }));
    // Fokus na slovo věty (i bez klávesy, např. z čtečky) ukázku taky ukončí.
    form.addEventListener('focusin', stop, { once: true });

    const run = () => {
        // Jen kroky, které nezmění výšku věty (počet řádků) — změří se
        // synchronně v jednom snímku a vrátí zpět, nic se nevykreslí.
        const base = sentence.offsetHeight;
        const steps = DEMO_STEPS.filter((s) => label(co, s.co) && label(pro, s.pro)).filter((s) => {
            show(s, false);
            const same = sentence.offsetHeight === base;
            selects.forEach(mirror);
            return same;
        });
        if (!steps.length) return stop();

        try { sessionStorage.setItem(DEMO_KEY, '1'); } catch { /* soukromý režim */ }
        running = true;
        form.classList.add('is-demo');
        const queue = [...steps, { co: 'all', pro: 'all' }];
        const next = () => {
            show(queue.shift(), true);
            if (queue.length) timer = setTimeout(next, 1300);
            else timer = setTimeout(stop, 600);
        };
        timer = setTimeout(next, 700);
    };

    observer = new IntersectionObserver((entries) => {
        if (!entries.some((e) => e.isIntersecting)) return;
        observer.disconnect();
        observer = null;
        if (document.visibilityState === 'visible') run();
        else stop();
    }, { threshold: 1 });
    observer.observe(sentence);

    return stop;
}

const run = () => {
    const form = document.querySelector('[data-intent]');
    if (form) initIntent(form);
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
else run();

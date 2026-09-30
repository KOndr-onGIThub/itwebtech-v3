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

    const stopDemo = form.dataset.hint === 'demo'
        ? initDemo(form, sentence, selects, valueOf, mirror)
        : initHint(form, sentence, selects);

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
//
// Kroky: napřed DEMO_STEPS, pak ostatní kombinace (ty, co mění obě slova,
// dřív). Na úzkém mobilu a v de pevná trojice výšku nedrží, náhradní
// kombinace ano (OND-474).
// ---------------------------------------------------------------------------
const DEMO_KEY = 'pd-intent-demo';
const DEMO_MAX = 3;
const DEMO_MIN = 2;
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
    let stopped = false;
    let observer = null;
    const events = ['pointerdown', 'keydown', 'wheel', 'touchstart'];

    const stop = () => {
        stopped = true;
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

    // Kandidáti v pořadí preference, bez duplicit a bez výchozího „all × all“.
    const candidates = () => {
        const values = (sel) => [...sel.options].map((o) => o.value);
        const combos = values(co).flatMap((c) => values(pro).map((p) => ({ co: c, pro: p })))
            .filter((s) => s.co !== 'all' || s.pro !== 'all');
        const both = (s) => s.co !== 'all' && s.pro !== 'all';
        const preferred = DEMO_STEPS.filter((s) => label(co, s.co) && label(pro, s.pro));
        const key = (s) => `${s.co}|${s.pro}`;
        const taken = new Set(preferred.map(key));
        const rest = combos.filter((s) => !taken.has(key(s)));
        return [...preferred, ...rest.filter(both), ...rest.filter((s) => !both(s))];
    };

    const run = () => {
        if (stopped) return;
        // Jen kroky, které nezmění výšku věty (počet řádků) — změří se
        // synchronně v jednom snímku a vrátí zpět, nic se nevykreslí.
        // Přednost mají kroky, kde části věty (sloty i spojka) zůstanou
        // na svých řádcích: přeskok slova na jiný řádek je velký posun
        // i při stejné výšce (de na mobilu CLS 0,067, OND-475). Když
        // takové nejsou aspoň dva (en 320/340 px), doplní se tím, co drží výšku.
        const base = sentence.offsetHeight;
        const lines = () => [...sentence.children].map((el) => el.getBoundingClientRect().top);
        const baseLines = lines();
        const inPlace = [];
        const sameHeight = [];
        for (const s of candidates()) {
            if (inPlace.length === DEMO_MAX) break;
            show(s, false);
            if (sentence.offsetHeight !== base) continue;
            if (lines().every((top, i) => Math.abs(top - baseLines[i]) < 1)) inPlace.push(s);
            else sameHeight.push(s);
        }
        const steps = inPlace.length >= DEMO_MIN ? inPlace : [...inPlace, ...sameHeight].slice(0, DEMO_MIN);
        selects.forEach(mirror);
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
        if (document.visibilityState !== 'visible') return stop();
        // Měřit až s načteným písmem — s náhradním se věta láme jinak
        // a vybraný krok by pak výšku změnil (OND-474).
        document.fonts.ready.then(run);
    // Spodní okraj: na mobilu přes spodek okna leží lišta „Poptávka“,
    // věta pod ní se nepočítá jako viditelná.
    }, { threshold: 1, rootMargin: '0px 0px -88px 0px' });
    observer.observe(sentence);

    return stop;
}

// ---------------------------------------------------------------------------
// OND-478 — nápověda pro první návštěvu (prototyp, `?napoveda=a|b|c`).
// Nahrazuje ukázku věty: přepisování slov vypadalo jako dekorativní
// rotující nadpis, ne jako ovládání.
//
//   a — bublina nad prvním slovem „Tady si vyberte, co potřebujete.“
//       Klepnutí na ni otevře výběr.
//   b — tichý řádek pod větou „↑ Podtržená slova můžete změnit.“
//       Místo má rezervované od načtení, jen se rozsvítí.
//   c — ruka jednou ukáže klepnutí na obě slova a odejde.
//
// Společné: a a c se spustí, až je celá věta v okně, stránka je otevřená
// aspoň HINT_AFTER (a 1,5 s, c 3 s) a scroll stojí HINT_IDLE — na mobilu, kde je věta níž,
// tedy až když k ní návštěvník dojede a zastaví se. Nápověda je absolutně
// nad obsahem nebo v rezervovaném místě: nic se neposune (CLS 0).
// Zmizí po první interakci s větou (dotyk, klik, fokus, změna, Esc)
// a v téže relaci se už neukáže ani po návratu z detailu. Čtečka nic
// navíc nečte (`aria-hidden`), selecty mají vlastní popisky.
// `prefers-reduced-motion`: a i b se jen objeví, c se nepohne — ruka
// se na chvíli ukáže u prvního slova a zmizí.
// ---------------------------------------------------------------------------
const HINT_KEY = 'pd-intent-hint';
// Nejdřív od načtení: bublina čeká, ruka se pohybuje — ta až po přečtení hlavy.
const HINT_AFTER = { a: 1500, c: 3000 };
const HINT_IDLE = 600;

function initHint(form, sentence, selects) {
    const variant = form.dataset.hint;
    const hint = form.querySelector('[data-intent-hint]');
    const key = `${HINT_KEY}-${variant}`;
    const noop = () => {};
    let seen = false;
    try { seen = sessionStorage.getItem(key) === '1'; } catch { /* soukromý režim */ }
    if (!hint || seen || selects.some((s) => s.value !== 'all')) return noop;

    const slots = [...form.querySelectorAll('.pd-intent__slot')];
    let done = false;
    let shown = false;
    let timer = 0;
    let observer = null;
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
        observer?.disconnect();
        removeEventListener('scroll', onScroll);
        removeEventListener('resize', place);
        form.removeEventListener('pointerdown', onPointer, true);
        form.removeEventListener('focusin', stop);
        document.removeEventListener('keydown', onKey);
        hint.getAnimations().forEach((a) => a.cancel());
        slots.forEach((s) => s.classList.remove('is-tapped'));
        hint.classList.add('is-gone');
    };

    // Bublina: šipka míří na začátek prvního slova, bublina se vejde do šířky.
    function place() {
        if (variant !== 'a') return;
        const f = form.getBoundingClientRect();
        const w = slots[0].querySelector('.pd-intent__value').getBoundingClientRect();
        const target = w.left - f.left + Math.min(w.width / 2, 40);
        const left = Math.max(0, Math.min(target - 28, f.width - hint.offsetWidth));
        hint.style.setProperty('--hint-left', `${left}px`);
        hint.style.setProperty('--hint-arrow', `${target - left}px`);
    }

    const show = () => {
        if (done || shown) return;
        shown = true;
        observer?.disconnect();
        removeEventListener('scroll', onScroll);
        try { sessionStorage.setItem(key, '1'); } catch { /* soukromý režim */ }
        hint.hidden = false;
        place();
        hint.classList.add('is-on');
        if (variant === 'a') addEventListener('resize', place, { passive: true });
        if (variant === 'c') playHand().then(stop, noop);
    };

    // Ruka: bod doteku = špička prstu (vlevo nahoře v ikoně).
    const TIP_X = 18;
    const TIP_Y = 4;
    const tapPoint = (slot) => {
        const f = form.getBoundingClientRect();
        const v = slot.querySelector('.pd-intent__value').getBoundingClientRect();
        return { x: v.left - f.left + Math.min(v.width / 2, 72) - TIP_X, y: v.top - f.top + v.height * 0.62 - TIP_Y };
    };
    const at = (p, scale = 1) => `translate(${p.x}px, ${p.y}px) scale(${scale})`;
    const wait = (ms) => new Promise((res) => { timer = setTimeout(res, ms); });
    async function playHand() {
        const [a, b] = slots.map(tapPoint);
        if (reduceMotion()) {
            hint.style.transform = at(a);
            await wait(3000);
            return;
        }
        const move = (from, to, ms) => hint.animate([{ transform: at(from) }, { transform: at(to) }], { duration: ms, easing: 'cubic-bezier(0.45, 0, 0.2, 1)', fill: 'forwards' }).finished;
        const tap = async (p, slot) => {
            await hint.animate([{ transform: at(p) }, { transform: at(p, 0.86) }, { transform: at(p) }], { duration: 360, easing: 'ease-in-out', fill: 'forwards' }).finished;
            slot.classList.add('is-tapped');
            await wait(900);
            slot.classList.remove('is-tapped');
        };
        const start = { x: a.x + 56, y: a.y + 64 };
        hint.style.transform = at(start);
        await hint.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 300, fill: 'forwards' }).finished;
        await move(start, a, 900);
        await tap(a, slots[0]);
        await move(a, b, 1000);
        await tap(b, slots[1]);
        await move(b, { x: b.x + 40, y: b.y + 48 }, 500);
    }

    const schedule = () => {
        clearTimeout(timer);
        if (!inView || done || shown) return;
        const now = performance.now();
        const ms = Math.max(HINT_IDLE - (now - lastScroll), (HINT_AFTER[variant] ?? 0) - (now - t0), 0);
        timer = setTimeout(() => {
            if (!inView || document.visibilityState !== 'visible') return;
            if (performance.now() - lastScroll < HINT_IDLE - 20) return schedule();
            document.fonts.ready.then(show);
        }, ms);
    };

    form.addEventListener('pointerdown', onPointer, { capture: true, passive: true });
    form.addEventListener('focusin', stop);
    document.addEventListener('keydown', onKey);

    if (variant === 'a') {
        // Bublina dělá, co říká: klepnutí otevře první výběr.
        hint.addEventListener('click', () => {
            stop();
            const sel = selects[0];
            sel.focus({ preventScroll: true });
            try { sel.showPicker(); } catch { /* starší prohlížeč: stačí fokus */ }
        });
    }

    if (variant === 'b') {
        // Řádek je statický text v rezervovaném místě — bez čekání.
        show();
        return stop;
    }

    if (!('IntersectionObserver' in window)) return stop;
    addEventListener('scroll', onScroll, { passive: true });
    observer = new IntersectionObserver((entries) => {
        inView = entries[entries.length - 1].isIntersecting;
        schedule();
    // Spodní okraj: na mobilu přes spodek okna leží lišta „Poptávka“,
    // věta pod ní se nepočítá jako viditelná.
    }, { threshold: 1, rootMargin: '0px 0px -88px 0px' });
    observer.observe(sentence);

    return stop;
}

const run = () => {
    const form = document.querySelector('[data-intent]');
    if (form) initIntent(form);
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
else run();

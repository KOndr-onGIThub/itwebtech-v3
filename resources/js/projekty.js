// ---------------------------------------------------------------------------
// OND-470 — věta nad přehledem /projekty: „Potřebuju [web ▾] pro [obor ▾].“
// Šablona: resources/views/components/portfolio/filter.blade.php.
//
// Žádná knihovna, žádná smyčka na snímek, nic navázaného na scroll:
//   - změna výběru přeskládá karty v DOM (shody nahoru) a jednou je
//     dorovná FLIPem,
//   - dokud návštěvník větu nepoužije, ukáže se nad ní (nejvýš jednou
//     za načtení) bublina s nápovědou; zmizí při jakékoli interakci.
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

    // Popisek volby bez počtu (OND-482: text `<option>` nese i „· 4“).
    const labelOf = (o) => o.dataset.label ?? o.textContent;
    selects.forEach((sel) => [...sel.options].forEach((o) => { o.dataset.label = o.textContent.trim(); }));
    const mirror = (sel) => { valueOf(sel).textContent = labelOf(sel.selectedOptions[0]); };

    // OND-482 — počty u voleb, počítané vůči druhému slovu věty.
    // Volba s 0 projekty je `disabled`: návštěvník na prázdno nenarazí,
    // ani ve vlastním seznamu, ani v systémovém výběru telefonu.
    // Vybraná volba (třeba z URL) se nevypíná, stav zůstane souvislý.
    const inSectorOf = (card, pro) => (card.dataset.sectors || '').split(' ').includes(pro);
    const countFor = (co, pro) => cards.filter((c) =>
        (co === 'all' || c.dataset.category === co) && (pro === 'all' || inSectorOf(c, pro))).length;
    const refreshCounts = () => {
        const co = form.elements.co.value;
        const pro = form.elements.pro.value;
        selects.forEach((sel) => {
            [...sel.options].forEach((o) => {
                const n = sel.name === 'co' ? countFor(o.value, pro) : countFor(co, o.value);
                o.dataset.count = n;
                o.disabled = n === 0 && !o.selected;
                const text = `${o.dataset.label} · ${n}`;
                if (o.textContent !== text) o.textContent = text;
            });
        });
        pickers.forEach((p) => p.sync());
    };

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

        refreshCounts();
    };

    // Rámeček fokusu jen při ovládání klávesnicí. `:focus-visible` na
    // `<select>` se po klepnutí prstem v některých prohlížečích rozsvítí
    // taky, proto si poslední způsob ovládání pamatujeme sami.
    const setInput = (kind) => () => { form.dataset.input = kind; };
    document.addEventListener('pointerdown', setInput('pointer'), { capture: true, passive: true });
    document.addEventListener('keydown', setInput('keyboard'), { capture: true, passive: true });

    // OND-482 — vlastní seznam na počítači (myš, touchpad, klávesnice). Na dotyku
    // zůstává systémový výběr telefonu. Zdroj pravdy je pořád `<select>`.
    // Otevření seznamu (i systémového na dotyku) = věta je použitá.
    let hintUsed = () => {};
    const pickers = selects.map((sel) => initPicker(form, sel, i18n, () => hintUsed()));
    const openFirst = () => pickers[0].open();

    hintUsed = initHint(form, sentence, selects, openFirst);

    selects.forEach((sel) => {
        mirror(sel);
        sel.addEventListener('change', () => { hintUsed(); mirror(sel); apply(true); });
    });

    form.addEventListener('submit', (e) => e.preventDefault());
    apply(false);
}

// ---------------------------------------------------------------------------
// OND-482 — rozbalovací seznam ve větě, který patří k webu.
//
// Systémový seznam `<select>` na počítači kreslí prohlížeč (Chrome na
// Windows: bílý, bez odsazení, šedé písmo) a CSS ho mimo Chrome/Edge
// nepřebarví (`appearance: base-select` Firefox ani Safari neumí).
// Proto mimo dotyk (není `pointer: coarse`) vlastní seznam podle ARIA APG „select-only
// combobox“: viditelné slovo je `role="combobox"`, seznam `role="listbox"`.
// `<select>` zůstává zdrojem pravdy: výběr nastaví jeho hodnotu a pošle
// `change`, zbytek věty (přeskládání, URL, hlášení) se nemění.
// Na dotyku (`pointer: coarse`) se nic z toho nezapne: průhledný select
// přes slovo otevře systémový výběr telefonu, jako dosud.
//
// Klávesnice (APG): zavřený Enter/mezerník/↓/↑/Alt+↓ otevře, Home/End
// otevře na první/poslední, písmeno otevře a skočí na shodu. Otevřený
// ↑/↓/Home/End/PageUp/PageDown posouvá, psaní hledá (bez diakritiky),
// Enter/mezerník/Alt+↑ vybere a zavře, Esc zavře beze změny, Tab vybere
// a pustí fokus dál. Klik mimo zavře. Nic se neodsune: seznam je
// absolutně pod slovem, a když se dolů nevejde, otevře se nahoru.
// ---------------------------------------------------------------------------
const GAP = 8;      // mezera slovo–seznam (= --space-2)
const EDGE = 16;    // odstup od okraje okna
const fold = (t) => t.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

function initPicker(form, sel, i18n, onOpen) {
    const slot = sel.parentElement;
    const word = slot.querySelector('.pd-intent__value');
    const label = slot.querySelector('label');
    // Systémový výběr jen na dotyku. `pointer: none` (klávesnice bez myši,
    // některé headless prohlížeče) dostane vlastní seznam taky.
    const coarse = window.matchMedia('(pointer: coarse)');

    const list = document.createElement('span');
    list.className = 'pd-intent__list';
    list.id = `${sel.id}-list`;
    list.setAttribute('role', 'listbox');
    list.setAttribute('aria-labelledby', label.id);
    list.hidden = true;

    const items = [...sel.options].map((o) => {
        const item = document.createElement('span');
        item.className = 'pd-intent__option';
        item.id = `${sel.id}-opt-${o.value}`;
        item.setAttribute('role', 'option');
        item.dataset.value = o.value;
        item.innerHTML = '<span class="pd-intent__option-label"></span><span class="pd-intent__option-count" aria-hidden="true"></span>';
        list.appendChild(item);
        return item;
    });
    slot.appendChild(list);

    let custom = false;
    let active = -1;
    let query = '';
    let queryTimer = 0;

    const isOpen = () => !list.hidden;
    const enabled = (i) => i >= 0 && i < items.length && !sel.options[i].disabled;

    // Popisky, počty a stavy z `<select>` (volá se i po každém výběru).
    function sync() {
        items.forEach((item, i) => {
            const o = sel.options[i];
            const n = Number(o.dataset.count ?? 0);
            item.firstChild.textContent = o.dataset.label ?? o.textContent;
            item.lastChild.textContent = n;
            item.setAttribute('aria-label', `${o.dataset.label}, ${countLabel(i18n.count || {}, n)}`);
            item.setAttribute('aria-selected', String(o.selected));
            o.disabled ? item.setAttribute('aria-disabled', 'true') : item.removeAttribute('aria-disabled');
        });
    }

    function setActive(i, scroll = true) {
        if (!enabled(i)) return;
        items[active]?.classList.remove('is-active');
        active = i;
        items[i].classList.add('is-active');
        word.setAttribute('aria-activedescendant', items[i].id);
        if (scroll) items[i].scrollIntoView({ block: 'nearest' });
    }

    // Další povolená volba ve směru `dir` (bez přetečení na druhý konec).
    const step = (from, dir) => {
        for (let i = from + dir; i >= 0 && i < items.length; i += dir) if (enabled(i)) return i;
        return from;
    };
    const first = () => step(-1, 1);
    const last = () => step(items.length, -1);

    // Dolů, když se vejde; jinak nahoru, když se vejde tam; jinak na
    // větší stranu se svislým posunem uvnitř seznamu. Vodorovně v okně.
    function place() {
        list.classList.remove('is-up');
        list.style.maxHeight = '';
        list.style.setProperty('--list-x', '0px');
        list.style.setProperty('--list-min', `${Math.round(word.getBoundingClientRect().width)}px`);
        const s = slot.getBoundingClientRect();
        const h = list.scrollHeight;
        const nav = document.querySelector('.navbar');
        const top = nav && !nav.classList.contains('is-hidden') ? nav.getBoundingClientRect().bottom : 0;
        const below = innerHeight - s.bottom - GAP - EDGE;
        const above = s.top - Math.max(top, 0) - GAP - EDGE;
        if (h > below && (h <= above || above > below)) list.classList.add('is-up');
        if (h > Math.max(below, above)) list.style.maxHeight = `${Math.max(below, above)}px`;
        const l = list.getBoundingClientRect();
        const vw = document.documentElement.clientWidth;
        const dx = Math.min(0, vw - EDGE - l.right);
        list.style.setProperty('--list-x', `${Math.max(dx, EDGE - l.left)}px`);
    }

    function open(to) {
        onOpen();
        if (!custom) {
            sel.focus({ preventScroll: true });
            try { sel.showPicker(); } catch { /* bez showPicker u selectu: stačí fokus */ }
            return;
        }
        if (document.activeElement !== word) word.focus({ preventScroll: true });
        if (isOpen()) return;
        sync();
        list.hidden = false;
        word.setAttribute('aria-expanded', 'true');
        place();
        setActive(to ?? (enabled(sel.selectedIndex) ? sel.selectedIndex : first()));
        list.classList.remove('is-open');
        void list.offsetWidth; // start přechodu i při rychlém znovuotevření
        list.classList.add('is-open');
        addEventListener('resize', place, { passive: true });
    }

    function close() {
        if (!isOpen()) return;
        list.hidden = true;
        list.classList.remove('is-open');
        word.setAttribute('aria-expanded', 'false');
        word.removeAttribute('aria-activedescendant');
        items[active]?.classList.remove('is-active');
        active = -1;
        query = '';
        removeEventListener('resize', place);
    }

    function choose(i) {
        if (enabled(i) && sel.selectedIndex !== i) {
            sel.selectedIndex = i;
            sel.dispatchEvent(new Event('change', { bubbles: true }));
        }
        close();
    }

    // Psaní prvních písmen: hledá od začátku popisku, bez diakritiky,
    // opakované stejné písmeno cyklí mezi shodami.
    function typeahead(ch) {
        clearTimeout(queryTimer);
        queryTimer = setTimeout(() => { query = ''; }, 600);
        query += fold(ch);
        const same = query.split('').every((c) => c === query[0]);
        const q = same ? query[0] : query;
        const start = same && isOpen() ? active + 1 : Math.max(active, 0);
        for (let k = 0; k < items.length; k++) {
            const i = (start + k) % items.length;
            if (enabled(i) && fold(sel.options[i].dataset.label).startsWith(q)) return i;
        }
        return -1;
    }

    word.addEventListener('keydown', (e) => {
        if (!custom) return;
        const { key, altKey } = e;
        if (!isOpen()) {
            if (key === 'ArrowDown' || key === 'ArrowUp' || key === 'Enter' || key === ' ') { e.preventDefault(); open(); }
            else if (key === 'Home') { e.preventDefault(); open(first()); }
            else if (key === 'End') { e.preventDefault(); open(last()); }
            else if (key.length === 1 && !e.ctrlKey && !e.metaKey && !altKey) {
                const i = typeahead(key);
                open(i >= 0 ? i : undefined);
            }
            return;
        }
        if (key === 'ArrowDown') { e.preventDefault(); setActive(step(active, 1)); }
        else if (key === 'ArrowUp' && altKey) { e.preventDefault(); choose(active); }
        else if (key === 'ArrowUp') { e.preventDefault(); setActive(step(active, -1)); }
        else if (key === 'Home' || key === 'PageUp') { e.preventDefault(); setActive(first()); }
        else if (key === 'End' || key === 'PageDown') { e.preventDefault(); setActive(last()); }
        else if (key === 'Enter' || (key === ' ' && !query)) { e.preventDefault(); choose(active); }
        else if (key === 'Escape') { e.preventDefault(); close(); }
        else if (key === 'Tab') choose(active);
        else if (key.length === 1 && !e.ctrlKey && !e.metaKey && !altKey) {
            e.preventDefault();
            const i = typeahead(key);
            if (i >= 0) setActive(i);
        }
    });

    // Myš: stisk kdekoli ve slotu (slovo, šipka, tečka) přepne seznam,
    // jako to dělal select přes slovo. Stisk v seznamu nesmí vzít fokus.
    slot.addEventListener('mousedown', (e) => {
        if (!custom || e.button !== 0) return;
        if (list.contains(e.target)) { e.preventDefault(); return; }
        e.preventDefault();
        if (isOpen()) close(); else open();
    });
    list.addEventListener('click', (e) => {
        const item = e.target.closest('.pd-intent__option');
        if (item) choose(items.indexOf(item));
    });
    list.addEventListener('pointermove', (e) => {
        const item = e.target.closest('.pd-intent__option');
        if (item) setActive(items.indexOf(item), false);
    });
    document.addEventListener('pointerdown', (e) => { if (isOpen() && !slot.contains(e.target)) close(); }, true);
    word.addEventListener('blur', close);
    // Dotyk: klepnutí na průhledný select otevře systémový výběr a dá mu
    // fokus. Posun prstem přes slovo fokus nedá, takže se nepočítá.
    sel.addEventListener('focus', () => { if (!custom && form.dataset.input === 'pointer') onOpen(); });

    // Režim podle hlavního ukazatele; přepne se i za běhu (tablet s myší).
    function setMode(on) {
        close();
        custom = on;
        form.dataset.picker = on ? 'custom' : 'native';
        if (on) {
            sel.tabIndex = -1;
            sel.setAttribute('aria-hidden', 'true');
            word.removeAttribute('aria-hidden');
            word.tabIndex = 0;
            word.setAttribute('role', 'combobox');
            word.setAttribute('aria-labelledby', label.id);
            word.setAttribute('aria-haspopup', 'listbox');
            word.setAttribute('aria-controls', list.id);
            word.setAttribute('aria-expanded', 'false');
        } else {
            sel.removeAttribute('tabindex');
            sel.removeAttribute('aria-hidden');
            word.setAttribute('aria-hidden', 'true');
            ['tabindex', 'role', 'aria-labelledby', 'aria-haspopup', 'aria-controls', 'aria-expanded']
                .forEach((a) => word.removeAttribute(a));
        }
    }
    setMode(!coarse.matches);
    coarse.addEventListener('change', (e) => setMode(!e.matches));

    return { open: () => open(), sync };
}

// ---------------------------------------------------------------------------
// OND-478 — nápověda pro první návštěvu: bublina nad prvním slovem
// „Tady si vyberte, co potřebujete.“ Klepnutí na ni otevře výběr.
// Nahradila ukázku věty (OND-470): slova, která se sama přepisují,
// vypadala jako dekorativní rotující nadpis, ne jako ovládání.
//
// Spustí se, až je celá věta v okně nad lištou „Poptávka“ (spodních
// 88 px), stránka je otevřená aspoň HINT_AFTER a scroll stojí HINT_IDLE
// a celá bublina je vidět pod navbarem (OND-481).
// Na mobilu, kde je věta níž, tedy až když k ní návštěvník dojede
// a zastaví se. Bublina je absolutně nad větou: nic se neposune (CLS 0).
// Zmizí po první interakci s větou (dotyk, klik, fokus, změna, Esc).
// Spotřebuje se (`sessionStorage`, jen pro tuhle záložku) až tím, že
// návštěvník větu opravdu použije: klepne na bublinu, otevře seznam
// nebo změní výběr. Kdo ji jen viděl, uvidí ji při dalším načtení
// znovu (obnovení, návrat z detailu), pokaždé nejvýš jednou.
// S výběrem z URL se neukáže vůbec. Čtečka nic navíc nečte (`aria-hidden`).
// `prefers-reduced-motion`: bublina se jen objeví, bez vyjetí (CSS).
// ---------------------------------------------------------------------------
// Klíč `pd-intent-used` (dřív `pd-intent-hint`, zapsaný už ukázáním):
// staré „jen viděl“ po nasazení nikomu bublinu neschová.
const HINT_KEY = 'pd-intent-used';
const HINT_AFTER = 1500;
const HINT_IDLE = 600;

// Vrací `used()`: věta je použitá, bublina zmizí a v téže záložce se
// už neukáže. Zapíše se i tehdy, když se bublina tentokrát nespustila
// (třeba výběr z URL), ať se neukáže po návratu na čistou adresu.
function initHint(form, sentence, selects, openFirst) {
    const hint = form.querySelector('[data-intent-hint]');
    const consume = () => {
        try { sessionStorage.setItem(HINT_KEY, '1'); } catch { /* soukromý režim */ }
    };
    let used = false;
    try { used = sessionStorage.getItem(HINT_KEY) === '1'; } catch { /* soukromý režim */ }
    if (!hint || used || selects.some((s) => s.value !== 'all')) return consume;
    if (!('IntersectionObserver' in window)) return consume;

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

    // OND-481: bublina leží nad větou, takže ji může krýt navbar nebo být
    // nad oknem, i když je věta celá vidět. Ukáže se, jen když je celá
    // v okně pod navbarem a nad lištou „Poptávka“. Navbar se
    // vysouvá 0,3 s: platí přísnější z jeho aktuální a cílové spodní hrany.
    const nav = document.querySelector('.navbar');
    const bar = document.querySelector('.mobile-bottom-bar');
    function fits() {
        const r = hint.getBoundingClientRect();
        const top = nav ? Math.max(nav.getBoundingClientRect().bottom, nav.classList.contains('is-hidden') ? 0 : nav.offsetHeight) : 0;
        const b = bar ? bar.getBoundingClientRect() : null;
        const bottom = b && b.height ? b.top : innerHeight;
        return r.height > 0 && r.top >= Math.max(top, 0) && r.bottom <= bottom
            && r.left >= 0 && r.right <= document.documentElement.clientWidth;
    }

    const show = () => {
        if (done || shown) return;
        hint.hidden = false;
        place();
        // Nevejde se: počká se na další zastavení scrollu.
        if (!fits()) { hint.hidden = true; return; }
        shown = true;
        observer.disconnect();
        removeEventListener('scroll', onScroll);
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
        consume();
        stop();
        openFirst();
    });

    return () => { consume(); stop(); };
}

const run = () => {
    const form = document.querySelector('[data-intent]');
    if (form) initIntent(form);
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
else run();

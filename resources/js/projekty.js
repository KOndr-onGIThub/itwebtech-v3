// ---------------------------------------------------------------------------
// OND-471 — prototypy přehledu /projekty (`?v=1|2|3`). Každý blok se sám
// vypne, když jeho kořen na stránce není. Žádná knihovna, žádná smyčka na
// snímek: FLIP běží jednou při změně výběru, náhled rejstříku píše jednu
// CSS proměnnou na pohyb myši (rAF), vitrína jen přepíná `hidden`.
// ---------------------------------------------------------------------------

const reduceMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const readI18n = (el) => {
    try { return JSON.parse(el.dataset.i18n || '{}'); } catch { return {}; }
};

// „21 projektů“ / „1 projekt“ / „3 projekty“ podle pravidel jazyka stránky.
const countLabel = (forms, n) => {
    const rule = new Intl.PluralRules(document.documentElement.lang || 'cs').select(n);
    return (forms[rule] ?? forms.other ?? ':n').replace(':n', n);
};

const fold = (s) => s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();

// --- Varianta 1: věta ------------------------------------------------------
function initSentence(form) {
    const grid = document.querySelector('[data-intent-grid]');
    if (!grid) return;
    const i18n = readI18n(form);
    const total = Number(form.dataset.total) || 0;
    const status = form.querySelector('[data-intent-status]');
    const cta = form.querySelector('[data-intent-cta]');
    const rest = grid.querySelector('[data-intent-rest]');
    const cards = [...grid.querySelectorAll('.pd-work')];
    const selects = [...form.querySelectorAll('select')];

    // Obnova z URL (návrat z detailu, sdílený odkaz).
    const params = new URLSearchParams(location.search);
    selects.forEach((sel) => {
        const v = params.get(sel.name);
        if (v && [...sel.options].some((o) => o.value === v)) sel.value = v;
    });

    const mirror = (sel) => {
        sel.parentElement.querySelector('.pd-intent__value').textContent = sel.selectedOptions[0].textContent;
    };

    const apply = (animate) => {
        const co = form.elements.co.value;
        const pro = form.elements.pro.value;
        const all = co === 'all' && pro === 'all';
        const match = (card) =>
            (co === 'all' || card.dataset.category === co) &&
            (pro === 'all' || (card.dataset.sectors || '').split(' ').includes(pro));

        const hits = cards.filter(match);
        // Zbytek: napřed ty, které sedí aspoň v jednom slově věty — „nejbližší“
        // v hlášce pak platí doslova. Uvnitř skupin původní pořadí.
        const half = (c) =>
            (co !== 'all' && c.dataset.category === co) ||
            (pro !== 'all' && (c.dataset.sectors || '').split(' ').includes(pro));
        const misses = cards.filter((c) => !match(c));
        misses.sort((a, b) => Number(half(b)) - Number(half(a)));

        // FLIP — First: pozice před přeskládáním.
        const doAnimate = animate && !reduceMotion();
        const before = doAnimate ? new Map(cards.map((c) => [c, c.getBoundingClientRect()])) : null;

        // Přeskládání v DOM: shody, předěl, zbytek (vše v původním pořadí).
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

        // Hlášení (čtečka + vidící).
        if (all) status.textContent = countLabel(i18n.count || {}, total);
        else if (hits.length === 0) status.textContent = i18n.none;
        else status.textContent = (i18n.match || ':n / :total').replace(':n', hits.length).replace(':total', total);
        cta.hidden = all;

        // URL bez nového záznamu v historii.
        const url = new URL(location.href);
        co === 'all' ? url.searchParams.delete('co') : url.searchParams.set('co', co);
        pro === 'all' ? url.searchParams.delete('pro') : url.searchParams.set('pro', pro);
        history.replaceState(history.state, '', url);
    };

    selects.forEach((sel) => {
        mirror(sel);
        sel.addEventListener('change', () => { mirror(sel); apply(true); });
    });

    form.addEventListener('submit', (e) => e.preventDefault());
    form.hidden = false;
    apply(false);
}

// --- Varianta 2: rejstřík --------------------------------------------------
function initIndex(root) {
    const list = root.querySelector('[data-index-list]');
    const bar = root.querySelector('[data-index-bar]');
    const input = root.querySelector('#index-q');
    const status = root.querySelector('[data-index-status]');
    const empty = root.querySelector('[data-index-empty]');
    const rows = [...list.querySelectorAll('.pd-index__row')];
    const i18n = readI18n(root);

    const filter = () => {
        const q = fold(input.value);
        let n = 0;
        rows.forEach((row) => {
            const ok = !q || q.split(/\s+/).every((w) => row.dataset.search.includes(w));
            row.hidden = !ok;
            if (ok) n++;
        });
        status.textContent = countLabel(i18n.count || {}, n);
        empty.hidden = n > 0;
        root.classList.toggle('is-filtered', !!q);
    };

    input.addEventListener('input', filter);
    root.querySelectorAll('[data-q]').forEach((chip) => {
        chip.addEventListener('click', () => {
            input.value = input.value === chip.dataset.q ? '' : chip.dataset.q;
            filter();
            input.focus({ preventScroll: true });
        });
    });
    bar.hidden = false;

    // Náhled pod kurzorem — jen pro myš. Jedna proměnná na pohyb, zapsaná v rAF.
    const fine = window.matchMedia('(hover: hover) and (pointer: fine)');
    if (!fine.matches) return;

    let raf = 0;
    let x = 0;
    let y = 0;
    let warmed = false;
    const write = () => {
        raf = 0;
        root.style.setProperty('--peek-x', `${x}px`);
        root.style.setProperty('--peek-y', `${y}px`);
    };
    list.addEventListener('pointerenter', () => {
        // Náhledy stáhni, až když je myš v seznamu — ne při načtení stránky.
        if (warmed) return;
        warmed = true;
        list.querySelectorAll('img[loading="lazy"]').forEach((img) => { img.loading = 'eager'; });
    });
    // Vodorovně náhled stojí v pravé části řádku (přes druh a rok), aby
    // nezakryl název, který člověk právě čte. Svisle jde za kurzorem.
    list.addEventListener('pointermove', (e) => {
        const r = root.getBoundingClientRect();
        x = r.width - Math.min(440, r.width * 0.36) - 24;
        y = e.clientY - r.top;
        if (!raf) raf = requestAnimationFrame(write);
    });
}

// --- Varianta 3: vitrína ---------------------------------------------------
function initShowcase(root) {
    const stage = root.querySelector('[data-show-stage]');
    const items = [...root.querySelectorAll('[data-show-item]')];
    const cards = new Map([...stage.querySelectorAll('[data-show-card]')].map((c) => [c.dataset.showCard, c]));
    const wide = window.matchMedia('(min-width: 1024px)');
    let active = items[0]?.dataset.showItem;
    let open = null; // rozbalená položka na mobilu

    const setTransitionName = (slug) => {
        cards.forEach((card, s) => {
            const v = card.querySelector('.pd-show__visual');
            v.style.viewTransitionName = s === slug ? v.dataset.vt : 'none';
        });
    };

    const show = (slug) => {
        if (!wide.matches || slug === active || !cards.has(slug)) return;
        cards.get(active).hidden = true;
        const next = cards.get(slug);
        next.hidden = false;
        if (!reduceMotion()) {
            next.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 180, easing: 'ease-out' });
        }
        items.forEach((li) => li.classList.toggle('is-active', li.dataset.showItem === slug));
        active = slug;
        setTransitionName(slug);
    };

    items.forEach((li) => {
        const slug = li.dataset.showItem;
        li.addEventListener('pointerenter', () => show(slug));
        li.addEventListener('focusin', () => show(slug));
        const toggle = li.querySelector('[data-show-toggle]');
        toggle.hidden = false;
        toggle.addEventListener('click', () => {
            const card = cards.get(slug);
            const opening = toggle.getAttribute('aria-expanded') !== 'true';
            if (open && open !== li) collapse(open);
            if (opening) {
                li.appendChild(card);
                card.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
                li.classList.add('is-open');
                open = li;
                setTransitionName(slug);
                if (!reduceMotion()) {
                    card.animate([{ opacity: 0, transform: 'translateY(-8px)' }, { opacity: 1, transform: 'none' }], { duration: 240, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' });
                }
            } else {
                collapse(li);
            }
        });
    });

    function collapse(li) {
        const slug = li.dataset.showItem;
        const card = cards.get(slug);
        card.hidden = true;
        stage.appendChild(card);
        li.querySelector('[data-show-toggle]').setAttribute('aria-expanded', 'false');
        li.classList.remove('is-open');
        if (open === li) open = null;
    }

    // Přechod mobil ↔ desktop: karty zpátky do vitríny, zobraz aktivní.
    const sync = () => {
        if (open) collapse(open);
        cards.forEach((card, slug) => { card.hidden = !(wide.matches && slug === active); });
        items.forEach((li) => li.classList.toggle('is-active', wide.matches && li.dataset.showItem === active));
        setTransitionName(wide.matches ? active : null);
    };
    wide.addEventListener('change', sync);
    sync();
}

const run = () => {
    const sentence = document.querySelector('[data-intent]');
    if (sentence) initSentence(sentence);
    const index = document.querySelector('[data-index]');
    if (index) initIndex(index);
    const showcase = document.querySelector('[data-show]');
    if (showcase) initShowcase(showcase);
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
else run();

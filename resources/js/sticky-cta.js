// OND-511 — spodní mobilní lišta „Poptávka" žije jen UPROSTŘED stránky.
//
// Ukáže se, když z obrazovky odjede hlavní tlačítko hera (na podstránkách
// spodní hrana hlavy) — lišta „přebírá štafetu". Schová se, jakmile se
// objeví formulář, závěrečná výzva nebo patička. Plynulý průchod stránkou
// shora dolů = 1× ukázat, 1× schovat.
//
// Zóny značí šablony atributem:
//   data-sticky-cta="start" — hero tlačítko / hlava podstránky (jedna na stránce)
//   data-sticky-cta="hide"  — formulář, závěrečná výzva, patička
// Stránka bez `start` (contact/privacy/cookies lištu nerenderují vůbec,
// chybové stránky ano) lištu neukáže nikdy.
//
// Proti „cirkusu": stav závisí jen na poloze (ne na směru scrollu jako
// navbar), u startu je hystereze (ukázat až když je tlačítko celé nad
// obrazovkou, schovat až když se celé vrátí), u spodních zón taky
// (schovat, když zóna zasáhne 15 % výšky obrazovky zespodu, ukázat až
// když celá odjede pod spodní hranu), změna musí platit 150 ms a po každé
// změně 600 ms nic opačného. Fokus do pole a otevřené menu schovají hned.
//
// Programový sjezd (OND-514): klik na kotvu na téže stránce nebo načtení
// s #kotvou stav lišty zmrazí, dokud sjezd nedoběhne (`scrollend`, jinak
// 200 ms bez `scroll`, nejdýl 2 s); pak platí výsledná poloha hned, bez
// zámku. Míří-li kotva do zóny `hide` nebo nad start, lišta se schová
// rovnou při kliku — při sjezdu na formulář tak neprobleskne.
//
// Jen IntersectionObserver — žádný scroll handler, který čte layout
// (posluchač `scroll` žije jen po dobu sjezdu a nic neměří).
// Bez JS (nebo bez IO) zůstane lišta skrytá, jak ji vykreslil server.

const SETTLE_MS = 150;
const LOCK_MS = 600;
// Hystereze u startu: tlačítko hera se musí vrátit celé; hlava podstránky
// je vysoká, u ní stačí, když se její spodní hrana vrátí o 64 px.
const START_BAND_MAX = 64;
// Programový sjezd: konec = `scrollend`, nebo tolik ms bez `scroll`; strop.
const QUIET_MS = 200;
const FREEZE_MAX_MS = 2000;

const TYPING = 'input:not([type=checkbox]):not([type=radio]):not([type=button]):not([type=submit]):not([type=reset]):not([type=file]):not([type=range]):not([type=color]), textarea, [contenteditable]:not([contenteditable=false])';

const bar = document.querySelector('.mobile-bottom-bar');
const start = document.querySelector('[data-sticky-cta="start"]');

if (bar && start && 'IntersectionObserver' in window) {
    let pastStart = false;
    const inZone = new Set();
    let typing = false;
    let drawer = false;

    let shown = false;
    let lastChange = -Infinity;
    let timer = 0;
    let frozen = false;

    const wanted = () => pastStart && inZone.size === 0 && !typing && !drawer;

    const apply = (on) => {
        shown = on;
        lastChange = performance.now();
        bar.classList.toggle('is-visible', on);
        bar.inert = !on;
    };

    // `now` = schovat hned (fokus, menu), bez čekání a bez zámku.
    // Během programového sjezdu se stav nemění (schovat hned smí jen fokus/menu).
    const update = (now = false) => {
        clearTimeout(timer);
        const want = wanted();
        if (want === shown) return;
        if (now && !want) return apply(false);
        if (frozen) return;
        const wait = Math.max(SETTLE_MS, lastChange + LOCK_MS - performance.now());
        timer = setTimeout(() => {
            if (wanted() !== shown) apply(!shown);
        }, wait);
    };

    // Start: „pryč" = spodní hrana nad horní hranou okna; „zpět" = spodní
    // hrana aspoň `band` px pod ní. Mezi tím se nic nemění.
    const band = Math.min(start.offsetHeight, START_BAND_MAX);
    new IntersectionObserver(([e]) => {
        if (!e.isIntersecting && e.boundingClientRect.bottom <= 0) {
            pastStart = true;
            update();
        }
    }).observe(start);
    new IntersectionObserver(([e]) => {
        if (e.isIntersecting) {
            pastStart = false;
            update();
        }
    }, { rootMargin: `-${band}px 0px 0px 0px` }).observe(start);

    // Spodní zóny: „v zóně" = zasáhne 15 % výšky okna zespodu; „pryč" = celá
    // mimo okno (pod spodní hranou, nebo odjetá nahoru). Mezi tím beze změny.
    const zoneIn = new IntersectionObserver((entries) => {
        for (const e of entries) if (e.isIntersecting) inZone.add(e.target);
        update();
    }, { rootMargin: '0px 0px -15% 0px' });
    const zoneOut = new IntersectionObserver((entries) => {
        for (const e of entries) if (!e.isIntersecting) inZone.delete(e.target);
        update();
    });
    const hideZones = document.querySelectorAll('[data-sticky-cta="hide"]');
    hideZones.forEach((el) => {
        zoneIn.observe(el);
        zoneOut.observe(el);
    });

    // --- Programový sjezd na kotvu ---
    let quiet = 0;
    let cap = 0;
    let moved = false;
    let run = 0; // pořadí sjezdu — nový klik během dojezdu starý thaw zahodí

    const onScroll = () => {
        moved = true;
        clearTimeout(quiet);
        quiet = setTimeout(thaw, QUIET_MS);
    };
    // `scrollend` platí, jen když se od zmrazení opravdu jelo (ne doběh
    // předchozího švihnutí přerušeného klepnutím).
    const onScrollEnd = () => {
        if (moved) thaw();
    };

    function thaw() {
        if (!frozen) return;
        clearTimeout(quiet);
        clearTimeout(cap);
        window.removeEventListener('scroll', onScroll);
        window.removeEventListener('scrollend', onScrollEnd);
        // Dva snímky, ať IntersectionObserver doručí konečnou polohu.
        const id = run;
        requestAnimationFrame(() => requestAnimationFrame(() => {
            if (id !== run) return;
            frozen = false;
            clearTimeout(timer);
            if (wanted() !== shown) apply(!shown);
            lastChange = -Infinity; // bez 600ms zámku
        }));
    }

    const freeze = (wait = QUIET_MS) => {
        run++;
        frozen = true;
        moved = false;
        clearTimeout(timer);
        clearTimeout(quiet);
        clearTimeout(cap);
        quiet = setTimeout(thaw, wait);
        cap = setTimeout(thaw, FREEZE_MAX_MS);
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('scrollend', onScrollEnd);
    };

    // Kotva, u jejíhož cíle lišta být nemá: v zóně `hide` nebo nad koncem startu.
    const hidesAt = (target) =>
        !!target.closest('[data-sticky-cta="hide"]')
        || target.getBoundingClientRect().top < start.getBoundingClientRect().bottom;

    const anchorTarget = (hash) => {
        if (!hash || hash === '#') return null;
        try {
            return document.getElementById(decodeURIComponent(hash.slice(1)));
        } catch {
            return null;
        }
    };

    // Capture: zmrazit dřív, než odkaz zavře menu (`nav-drawer` → update()).
    document.addEventListener('click', (e) => {
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        const a = e.target.closest?.('a[href*="#"]');
        if (!a || (a.target && a.target !== '_self')) return;
        const url = new URL(a.href, location.href);
        if (url.origin !== location.origin || url.pathname !== location.pathname
            || url.search !== location.search) return;
        const target = anchorTarget(url.hash);
        if (!target) return;
        if (hidesAt(target) && shown) apply(false);
        freeze();
    }, true);

    // Příchod s #kotvou: prohlížeč může sjíždět až po načtení, proto delší
    // úvodní čekání (strop 2 s platí i tady).
    const landing = anchorTarget(location.hash);
    if (landing) freeze(hidesAt(landing) ? FREEZE_MAX_MS : QUIET_MS * 2);

    document.addEventListener('focusin', (e) => {
        if (!e.target.matches?.(TYPING)) return;
        typing = true;
        update(true);
    });
    document.addEventListener('focusout', (e) => {
        if (!e.target.matches?.(TYPING)) return;
        typing = false;
        update();
    });

    // Mobilní menu — událost posílá navbar (`x-effect` v layout/navbar).
    window.addEventListener('nav-drawer', (e) => {
        drawer = !!e.detail;
        update(drawer);
    });
}

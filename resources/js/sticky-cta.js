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
// obrazovkou, schovat až když se celé vrátí), spodní zóny se počítají
// od 15 % výšky obrazovky, změna musí platit 150 ms a po každé změně
// 600 ms nic opačného. Fokus do pole a otevřené menu schovají hned.
//
// Jen IntersectionObserver — žádný scroll handler, který čte layout.
// Bez JS (nebo bez IO) zůstane lišta skrytá, jak ji vykreslil server.

const SETTLE_MS = 150;
const LOCK_MS = 600;
// Hystereze u startu: tlačítko hera se musí vrátit celé; hlava podstránky
// je vysoká, u ní stačí, když se její spodní hrana vrátí o 64 px.
const START_BAND_MAX = 64;

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

    const wanted = () => pastStart && inZone.size === 0 && !typing && !drawer;

    const apply = (on) => {
        shown = on;
        lastChange = performance.now();
        bar.classList.toggle('is-visible', on);
        bar.inert = !on;
    };

    // `now` = schovat hned (fokus, menu), bez čekání a bez zámku.
    const update = (now = false) => {
        clearTimeout(timer);
        const want = wanted();
        if (want === shown) return;
        if (now && !want) return apply(false);
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

    const zones = new IntersectionObserver((entries) => {
        for (const e of entries) {
            if (e.isIntersecting) inZone.add(e.target);
            else inZone.delete(e.target);
        }
        update();
    }, { rootMargin: '0px 0px -15% 0px' });
    document.querySelectorAll('[data-sticky-cta="hide"]').forEach((el) => zones.observe(el));

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

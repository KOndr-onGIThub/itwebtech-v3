/* ==========================================================================
   OND-246 — HLOUBKA, spouštěč
   --------------------------------------------------------------------------
   Patří k resources/css/hloubka.css. Dělá přesně tři věci:

     1) POSOUVÁ ZDROJ SVĚTLA o ±7 px podle pozice sekce ve výřezu (vrstva A).
     2) SPOUŠTÍ NÁBOJ na vyjmenovaných prvcích, až dojedou do čtecího pásma
        (vrstva B).
     3) SPOUŠTÍ NÁBOJ V FAQ při rozkliknutí otázky — tam ho nepouští scroll,
        ale člověk.

   CO NEDĚLÁ: nic nezobrazuje ani neskrývá. Když se tenhle soubor vůbec
   nenačte, stránka vypadá stejně jako dnes plus statické nasvícení. Žádný
   obsah na JS nezávisí a nic nestartuje v opacity: 0.

   --------------------------------------------------------------------------
   ČTECÍ PÁSMO — proč zrovna 62 %

   Původní verze spouštěla náboj, jakmile prvek vykoukl zespodu (rootMargin
   -12 %). To znamená, že se animace rozjela u dolní hrany okna, tedy asi
   400 px pod místem, kam se člověk zrovna dívá. Kdo jen rychle projíždí, nic
   nepozná; kdo se začte, tomu se dole rozběhne pohyb a vyruší ho dřív, než
   tam očima dojede.

   Teď musí HORNÍ HRANA prvku překročit 62 % výšky okna — to je spodní okraj
   pásma, ve kterém lidi skutečně čtou. Náboj tedy proběhne chvíli PŘEDTÍM,
   než na prvek přijde řada, ne o celou obrazovku dřív.

   K tomu DRŽENÍ 140 ms: prvek musí v pásmu chvíli vydržet. Kdo stránku
   prolítne kolečkem, nenechá za sebou stopu rozběhnutých animací —
   ty prvky se prostě nespustí a spustí se, až u nich zastaví.

   VÝKON: animuje se výhradně background-position na pseudo-elementech,
   parallax mění jednu CSS proměnnou. Čtení layoutu a zápis stylů jsou
   oddělené do dvou fází, aby nevznikal forced reflow. Počítají se jen sekce,
   které jsou právě ve výřezu.
   ========================================================================== */
(function () {
    'use strict';

    var root = document.querySelector('.pd--depth');
    if (!root) return;   /* zatím jen domovská stránka */

    var reduce = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var hasIO = typeof window.IntersectionObserver === 'function';

    /* Spodní okraj čtecího pásma. Musí sedět s komentářem výše. */
    var READ_ZONE = 0.62;
    /* Jak dlouho musí prvek v pásmu vydržet, než se náboj pustí. */
    var DWELL = 140;
    /* Odklad po načtení, aby se hero nerozběhlo dřív, než se stránka usadí. */
    var BOOT = 350;


    /* ======================================================================
       1. SVĚTLO — posun zdroje (vrstva A)
       ====================================================================== */

    function lightParallax() {
        if (reduce) return;   /* statické nasvícení zůstává, pohyb ne */

        /* Na mobilu a tabletu posun zdroje NEBĚŽÍ. Je to jediná věc v celé
           vrstvě, která počítá při každém snímku scrollu, a na telefonu je
           scroll to nejdražší, co stránka dělá — zatímco ±7px posunu světla
           je na malé obrazovce stejně skoro nepostřehnutelný. Statické
           nasvícení (tedy 95 % efektu) zůstává úplně stejné. */
        var fine = window.matchMedia &&
            window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        if (!fine) return;

        var sections = Array.prototype.slice.call(root.querySelectorAll(':scope > section'));
        if (!sections.length) return;

        var AMP = 7;          /* maximální posun svitu v px */
        var visible = sections;
        var ticking = false;

        function request() {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(update);
        }

        function update() {
            ticking = false;
            var vh = window.innerHeight || document.documentElement.clientHeight;
            var reads = [];
            var i, el, rect, p, y;

            /* fáze 1 — jen čtení layoutu */
            for (i = 0; i < visible.length; i++) {
                el = visible[i];
                rect = el.getBoundingClientRect();
                /* 0 = sekce vstupuje zdola, 1 = odchází nahoře */
                p = (vh - rect.top) / (vh + rect.height);
                if (p < 0) p = 0; else if (p > 1) p = 1;
                reads.push([el, Math.round((0.5 - p) * 2 * AMP)]);
            }

            /* fáze 2 — jen zápis, a to jen když se hodnota opravdu změnila */
            for (i = 0; i < reads.length; i++) {
                el = reads[i][0];
                y = reads[i][1];
                if (el.__pddPy !== y) {
                    el.__pddPy = y;
                    el.style.setProperty('--pdd-py', y + 'px');
                }
            }
        }

        if (hasIO) {
            visible = [];
            var io = new IntersectionObserver(function (entries) {
                for (var i = 0; i < entries.length; i++) {
                    var e = entries[i];
                    var at = visible.indexOf(e.target);
                    if (e.isIntersecting) {
                        if (at === -1) visible.push(e.target);
                    } else if (at !== -1) {
                        visible.splice(at, 1);
                        e.target.__pddPy = 0;
                        e.target.style.setProperty('--pdd-py', '0px');
                    }
                }
                request();
            }, { rootMargin: '140px 0px' });

            for (var i = 0; i < sections.length; i++) io.observe(sections[i]);
        }

        window.addEventListener('scroll', request, { passive: true });
        window.addEventListener('resize', request, { passive: true });
        request();
    }


    /* ======================================================================
       2. PROUD — uzavřený seznam prvků (vrstva B)
       ----------------------------------------------------------------------
       Cokoli tady není, náboj nedostane. Zejména NE dělítka sekcí
       (.pd-section / .pd-strip) — přejezd u každé sekce je přesně ten šum,
       který se tímhle laděním odstraňoval.
       ====================================================================== */

    var TARGETS = [
        '.pd-hero',                     /* podtržení v H1 → CTA */
        '.pd-work',                     /* 02 — každá dlaždice zvlášť (kvůli mobilu) */
        '.pd-price__col--featured',     /* 06 — uzavřená smyčka */
        '.pd-step',                     /* 09 — každý krok zvlášť */
        '.pd-form__panel'               /* 15 — cíl stránky */
    ];

    function armCharges() {
        if (reduce || !hasIO) return;   /* bez IO zůstane vše ve statickém stavu */

        var io = new IntersectionObserver(onEnter, {
            root: null,
            /* spodní okraj kořene vytažený na 62 % výšky okna = čtecí pásmo */
            rootMargin: '0px 0px -' + Math.round((1 - READ_ZONE) * 100) + '% 0px',
            threshold: 0
        });

        function onEnter(entries) {
            for (var i = 0; i < entries.length; i++) {
                var el = entries[i].target;
                if (!entries[i].isIntersecting) {
                    /* prvek z pásma odjel dřív, než doběhlo držení — zrušíme */
                    if (el.__pddTimer) {
                        window.clearTimeout(el.__pddTimer);
                        el.__pddTimer = null;
                    }
                    continue;
                }
                if (el.__pddTimer || el.classList.contains('is-lit')) continue;
                el.__pddTimer = window.setTimeout(confirm.bind(null, el), DWELL);
            }
        }

        /* Po uplynutí držení ověříme, že prvek v pásmu OPRAVDU je. Kdo stránku
           prolítl, sem dojde s prvkem dávno mimo okno a náboj se nepustí. */
        function confirm(el) {
            el.__pddTimer = null;
            var vh = window.innerHeight || document.documentElement.clientHeight;
            var r = el.getBoundingClientRect();
            if (r.top >= vh * READ_ZONE || r.bottom <= 0) return;   /* zůstává pozorovaný */
            el.classList.add('is-lit');
            io.unobserve(el);
        }

        for (var t = 0; t < TARGETS.length; t++) {
            var nodes = root.querySelectorAll(TARGETS[t]);
            for (var n = 0; n < nodes.length; n++) io.observe(nodes[n]);
        }
    }


    /* ======================================================================
       3. FAQ — náboj pouští člověk, ne scroll
       ----------------------------------------------------------------------
       Třídu po doběhnutí sundáme, aby šla otázka otevřít znovu a náboj
       proběhl zas. Při zavírání se nespouští nic.
       ====================================================================== */

    function armFaq() {
        if (reduce) return;

        var items = root.querySelectorAll('.pd-faq__item');
        for (var i = 0; i < items.length; i++) {
            bind(items[i]);
        }

        function bind(item) {
            item.addEventListener('toggle', function () {
                if (!item.open) return;
                item.classList.remove('is-firing');
                void item.offsetWidth;          /* vynutí restart animace */
                item.classList.add('is-firing');
            });
            var q = item.querySelector('.pd-faq__q');
            if (!q) return;
            q.addEventListener('animationend', function () {
                item.classList.remove('is-firing');
            });
        }
    }


    /* ====================================================================== */

    function boot() {
        lightParallax();
        armFaq();
        /* Náboje se armují až po usazení stránky, ať hero nevystřelí
           do rozjeté sazby. */
        window.setTimeout(armCharges, BOOT);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

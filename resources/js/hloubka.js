/* ==========================================================================
   OND-246 — HLOUBKA, spouštěč
   --------------------------------------------------------------------------
   Patří k resources/css/hloubka.css. Dělá přesně dvě věci:

     1) SPOUŠTÍ NÁBOJ na vyjmenovaných prvcích, až dojedou do čtecího pásma
        (vrstva B).
     2) SPOUŠTÍ NÁBOJ V FAQ při rozkliknutí otázky — tam ho nepouští scroll,
        ale člověk.

   CO NEDĚLÁ: nic nezobrazuje ani neskrývá. Když se tenhle soubor vůbec
   nenačte, stránka vypadá stejně jako dnes plus statické nasvícení. Žádný
   obsah na JS nezávisí a nic nestartuje v opacity: 0.

   --------------------------------------------------------------------------
   PROČ TU NENÍ POSUN ZDROJE SVĚTLA

   Byl. Posouval svit o ±7 px podle pozice sekce ve výřezu, aby světlo při
   scrollu „žilo". Změřeno proti současnému webu na 8 prostřídaných dvojicích
   běhů: byla to JEDINÁ věc v celé vrstvě, která počítala při každém snímku
   scrollu, a stála za celou její cenu —

     přepočet stylů při scrollu   s posunem +130 ms   bez posunu +0,06 ms
     rasterizace při scrollu      s posunem +728 ms   bez posunu  +121 ms

   Za to se kupovalo 7 px pohybu světla, kterých si nikdo nevšimne.
   Statické nasvícení (tedy drtivá většina efektu) zůstalo beze změny
   a „hru světel" dělá akcentní náboj, ne tenhle posun. Vyhozeno.

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

   VÝKON: při scrollu tenhle soubor nepočítá nic — jen čeká na
   IntersectionObserver, a každý prvek odpozoruje hned, jak mu náboj pustí.
   Animuje se výhradně background-position na pseudo-elementech.
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
       1. PROUD — uzavřený seznam prvků (vrstva B)
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
       2. FAQ — náboj pouští člověk, ne scroll
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

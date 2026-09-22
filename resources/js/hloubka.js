/* ==========================================================================
   OND-246 — HLOUBKA, spouštěč
   --------------------------------------------------------------------------
   Patří k resources/css/hloubka.css. Dělá přesně tři věci:

     1) ROZSVĚCUJE SEKCE při scrollu — třídy `is-near` a `is-up` (vrstva A0).
     2) SPOUŠTÍ NÁBOJ na vyjmenovaných prvcích, až dojedou do čtecího pásma
        (vrstva B).
     3) SPOUŠTÍ NÁBOJ V FAQ při rozkliknutí otázky — tam ho nepouští scroll,
        ale člověk.

   CO NEDĚLÁ: nic nezobrazuje ani neskrývá. Když se tenhle soubor vůbec
   nenačte, stránka vypadá stejně jako dnes plus statické nasvícení na plný
   jas. Žádný obsah na JS nezávisí a nic nestartuje v opacity: 0.

   --------------------------------------------------------------------------
   JAK SE SVĚTLO ROZSVĚCUJE — a proč ne plynule

   Zadáno bylo „ať se to při scrollování rozsvěcuje, ať roste intenzita".
   Doslovné provedení = přepočítat jas z pozice scrollu při každém snímku.
   To se tu jednou už zkoušelo (posun zdroje o ±7 px) a změřeno na
   8 prostřídaných dvojicích běhů proti současnému webu to stálo:

     přepočet stylů při scrollu   s posunem +130 ms   bez posunu +0,06 ms
     rasterizace při scrollu      s posunem +728 ms   bez posunu  +121 ms

   Proto je to udělané jako DVA SKOKY s dlouhým přechodem místo plynulého
   dopočítávání:

     38 %   sekce je mimo obraz
     62 %   sekce vykoukla (is-near)      ─┐ přechod 1100 ms
    100 %   sekce dojela do pásma (is-up) ─┘ přechod 1100 ms

   Při běžném scrollování jsou ty dva přechody delší než doba, za kterou
   sekce projede od spodní hrany okna do čtecího pásma, takže se překrývají
   a čte se to jako plynulé rozsvěcení. Cena: dva přechody opacity na sekci
   za celou návštěvu. Při scrollu se nepočítá nic.

   Kdyby se to ukázalo jako málo, další krok NENÍ vrátit se k počítání na
   snímek — je to přidat třetí hladinu (např. 38 → 55 → 78 → 100).

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
    /* Stránky bez vrstvy (článek, cookies, gdpr) obal `.pd--depth` nemají
       a tenhle soubor se na nich nedostane dál než sem. */
    if (!root) return;

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
        '.pd-issue',                    /* 03 — přejezd přes číslo, každý problém sám */
        '.pd-services',                 /* 04 — tři svislé linky se staggerem, naráz */
        '.pd-case',                     /* 05 — horní hrana vizuálu, každá případovka sama */
        '.pd-price__col--featured',     /* 06 — uzavřená smyčka */
        '.pd-hood',                     /* 08 — svislé linky zdola nahoru */
        '.pd-step',                     /* 09 — každý krok zvlášť */
        '.pd-versus__col--mine',        /* 11 — levá hrana „mého" sloupce */
        '.pd-promise',                  /* 13 — dva přejezdy přes číslo záruky */
        '.pd-form__panel',              /* 15 — cíl stránky */

        /* OND-251 — PODSTRÁNKY. Jediná položka. Není to výjimka ze seznamu,
           je to TENTÝŽ OBJEKT jako `.pd-form__panel` o řádek výš: poptávkový
           formulář. Na /kontakt se jmenuje `.contact-form`, protože podstránky
           mají starší slovník tříd (viz §E v hloubka.css).

           Všechno ostatní na podstránkách stojí na vrstvě A (nasvícení), která
           se pouští z armLight() a žádný seznam nepotřebuje. Kdo sem bude chtít
           přidat další řádek, ať si napřed přečte tabulku rozhodnutí v §E —
           „na podstránkách se nic nehýbe kromě formuláře" je rozhodnutí, ne
           nedodělek. */
        '.contact-form'
    ];

    /* Kontejnery vs. jednotlivé položky: .pd-services, .pd-hood a .pd-promise
       jsou obaly, protože jejich děti stojí VEDLE SEBE — do pásma dojedou
       naráz a rytmus dělá stagger v CSS. .pd-issue, .pd-case, .pd-work
       a .pd-step se pozorují po kusech, protože jdou (aspoň na mobilu)
       pod sebou a společný obal by je odpálil naslepo. Stejná úvaha jako
       u dlaždic v sekci 02. */

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
       1b. ROZSVĚCOVÁNÍ SEKCÍ (vrstva A0)
       ----------------------------------------------------------------------
       Dva pozorovatelé, obě třídy se jen přidávají a nikdy neberou zpátky.
       Zhasínat sekci za zády čtenáře by nemělo co dělat: kdo se vrací nahoru,
       nemá důvod vidět, jak už jednou nasvícené místo zase tmavne.
       ====================================================================== */

    function armLight() {
        var sections = [];
        for (var c = 0; c < root.children.length; c++) {
            if (root.children[c].tagName === 'SECTION') sections.push(root.children[c]);
        }
        if (!sections.length) return;

        /* Třída na kořeni stlačí výchozí jas z 100 % na 38 %. Přidává se až
           tady, tedy ve stejném kroku, ve kterém se hned poté označí sekce
           viditelné při načtení — mezi tím prohlížeč nepřekresluje, takže
           nevznikne záblesk „nejdřív plný jas, pak tma".
           Bez JS a při reduced-motion se třída nepřidá a svítí se naplno. */
        if (reduce || !hasIO) return;
        root.classList.add('pd--ramp');

        var vh0 = window.innerHeight || document.documentElement.clientHeight;
        for (var s = 0; s < sections.length; s++) {
            var r0 = sections[s].getBoundingClientRect();
            if (r0.top < vh0 && r0.bottom > 0) sections[s].classList.add('is-near');
            if (r0.top < vh0 * READ_ZONE) sections[s].classList.add('is-up');
        }

        /* Horní okraj kořene je vytažený o 10 000 px NAD okno, takže sekce,
           která už oknem prolétla, kořen POŘÁD protíná. Stav „sekce je nad
           čtecím pásmem ⇒ svítí" tím platí z definice a nezávisí na tom,
           jestli pozorovatel stihl zavolat ve správný okamžik.

           Bez toho hrozí tohle: když stránka mezi dvěma snímky poskočí o víc,
           než je sekce vysoká, přejde sekce z „pod oknem" (neprotíná) rovnou
           do „nad oknem" (taky neprotíná) a poměr protnutí se nezmění. Sekce
           by pak zůstala natrvalo ztlumená na 62 % a člověk by na to přišel
           až při návratu nahoru.

           POCTIVĚ: zkusil jsem to i bez vytaženého okraje (skoky po 1400 px
           po 30 ms, čtyři šířky) a Chromium zavolalo pokaždé — 17/17 sekcí
           se rozsvítilo tak i tak. Není to tedy oprava naměřené vady, je to
           pojistka za nula řádků navíc. Kdo bude tenhle kód zjednodušovat,
           ať ví, že se tím o nic měřitelného nepřipraví. */
        watch(sections, '10000px 0px 0px 0px', 'is-near');
        watch(sections, '10000px 0px -' + Math.round((1 - READ_ZONE) * 100) + '% 0px', 'is-up');

        function watch(nodes, margin, cls) {
            var io = new IntersectionObserver(function (entries) {
                for (var i = 0; i < entries.length; i++) {
                    if (!entries[i].isIntersecting) continue;
                    entries[i].target.classList.add(cls);
                    io.unobserve(entries[i].target);
                }
            }, { root: null, rootMargin: margin, threshold: 0 });

            for (var n = 0; n < nodes.length; n++) {
                if (!nodes[n].classList.contains(cls)) io.observe(nodes[n]);
            }
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
        armLight();
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

/* ==========================================================================
   OND-246 — varianta 2: „Proud v lince" — spouštěč
   --------------------------------------------------------------------------
   JS tu dělá tři věci a nic víc:
     1) jednou (a opravdu jen jednou) pustí náboj, když prvek vjede do výřezu
     2) po doběhnutí označí linku jako „projetou" (stopa)
     3) v sekci 09 dopočítá z reálných pozic kroků, kdy má která linka
        vzplanout, aby to sedělo na sjíždějící jiskru
   Žádné importy, ES2015, žádná knihovna. Veškerý pohyb je v CSS.
   ========================================================================== */
(function () {
    'use strict';

    var ON = 'v2-on';
    var LIT = 'v2-lit';

    var reduce = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ----------------------------------------------------------------------
       Co se pozoruje a jak dlouho jeho náboj běží (kvůli stopě).
       Pořadí odpovídá pořadí na stránce.
       ---------------------------------------------------------------------- */
    var GROUPS = [
        { sel: '.pd-hero',              run: 1400 },   // hero: podtržení + CTA
        { sel: '.pd-section',           run: 900  },   // základní pravidlo systému
        { sel: '.pd-strip',             run: 900  },
        { sel: '.pd-works',             run: 1400 },   // 02 — tři dlaždice postupně
        { sel: '.pd-issues',            run: 1300 },   // 03 — svislá dělítka
        { sel: '.pd-services',          run: 1200 },   // 04 — svislá dělítka sloupců
        { sel: '.pd-price',             run: 1900 },   // 06 — uzavřená smyčka
        { sel: '.pd-steps',             run: 2100 },   // 09 — průchod procesem
        { sel: '.pd-form__panel',       run: 1200 }    // 15 — cíl stránky
    ];

    /* ----------------------------------------------------------------------
       09 POSTUP — časování zážehu jednotlivých kroků.
       Jiskra sjíždí po pravé hraně `.pd-steps` lineárně za 1700 ms (+200 ms
       odklad). Linka kroku má vzplanout přesně ve chvíli, kdy jiskra míjí
       jeho horní hranu — a protože kroky mají různou výšku, nelze to napsat
       do CSS natvrdo. Spočítáme poměr offsetTop / celková výška.
       ---------------------------------------------------------------------- */
    function tuneSteps() {
        var list = document.querySelector('.pd-steps');
        if (!list) return;
        var steps = list.querySelectorAll('.pd-step');
        if (!steps.length) return;

        var total = list.offsetHeight || 1;
        var DUR = 1700;   // musí sedět s animací .pd-steps.v2-on::before
        var LEAD = 200;   // musí sedět s jejím animation-delay

        for (var i = 0; i < steps.length; i++) {
            // jiskra má 22 % výšky; její hlava je na spodku pruhu, proto
            // posun o polovinu pruhu dopředu, ať zážeh nepřijde pozdě
            var ratio = steps[i].offsetTop / total;
            var at = LEAD + Math.round(ratio * DUR) - 60;
            if (at < 0) at = 0;
            steps[i].style.setProperty('--v2-delay', at + 'ms');
        }
    }

    /* ----------------------------------------------------------------------
       Jednorázové spuštění při vjezdu do výřezu.
       rootMargin ořezává spodních 12 % výřezu, aby náboj nevyjel dřív, než
       je sekce skutečně vidět. threshold 0 kvůli sekcím vyšším než okno.
       ---------------------------------------------------------------------- */
    function arm() {
        var io = new IntersectionObserver(function (entries) {
            for (var i = 0; i < entries.length; i++) {
                var e = entries[i];
                if (!e.isIntersecting) continue;
                fire(e.target);
                io.unobserve(e.target);
            }
        }, { root: null, rootMargin: '0px 0px -12% 0px', threshold: 0 });

        for (var g = 0; g < GROUPS.length; g++) {
            var nodes = document.querySelectorAll(GROUPS[g].sel);
            for (var n = 0; n < nodes.length; n++) {
                nodes[n].setAttribute('data-v2-run', GROUPS[g].run);
                io.observe(nodes[n]);
            }
        }
    }

    function fire(el) {
        if (el.classList.contains(ON)) return;
        el.classList.add(ON);

        // stopa po průchodu — linka zůstane o chlup světlejší
        var run = parseInt(el.getAttribute('data-v2-run'), 10) || 900;
        window.setTimeout(function () {
            el.classList.add(LIT);
        }, Math.round(run * 0.72));
    }

    /* ----------------------------------------------------------------------
       14 FAQ — náboj nespouští scroll, ale rozkliknutí otázky.
       Třídu po doběhnutí sundáme, aby šla otázka otevřít znovu a náboj
       proběhl zas. Při zavření se nic nespouští.
       ---------------------------------------------------------------------- */
    function armFaq() {
        var items = document.querySelectorAll('.pd-faq__item');
        for (var i = 0; i < items.length; i++) {
            (function (item) {
                item.addEventListener('toggle', function () {
                    if (!item.open) return;
                    item.classList.remove('v2-fire');
                    // vynutí restart animace
                    void item.offsetWidth;
                    item.classList.add('v2-fire');
                });
                var q = item.querySelector('.pd-faq__q');
                if (q) {
                    q.addEventListener('animationend', function () {
                        item.classList.remove('v2-fire');
                    });
                }
            })(items[i]);
        }
    }

    function boot() {
        tuneSteps();

        if (reduce) {
            // pohyb vypnutý: jen dorovnáme statický stav stop, ať linky
            // nevypadají jinak než v pohyblivé verzi
            var lines = document.querySelectorAll('.pd-section, .pd-strip');
            for (var i = 0; i < lines.length; i++) lines[i].classList.add(LIT);
            return;
        }

        if (!('IntersectionObserver' in window)) {
            // bez IO necháme všechno ve statickém stavu — nic nechybí
            return;
        }

        arm();
        armFaq();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    // přepočet časování kroků při změně šířky (kroky mění výšku)
    var rt;
    window.addEventListener('resize', function () {
        window.clearTimeout(rt);
        rt = window.setTimeout(tuneSteps, 200);
    });
})();

/* =========================================================================
   OND-246 — varianta 1 „Světlo shora", nepovinná pohyblivá část.
   -------------------------------------------------------------------------
   CO DĚLÁ: posouvá vertikální pozici svitu podle toho, kde sekce zrovna
   je ve viewportu — o ±9 px. Nic víc. Díky tomu světlo při scrollu „žije"
   a člověk má pocit, že obchází jeden osvětlený objekt, místo aby četl
   sérii statických tapet.

   CO NEDĚLÁ: nic nezobrazuje ani neskrývá. Když se tenhle soubor vůbec
   nenačte, varianta vypadá stejně, jen je světlo zafixované. Žádný obsah
   nezávisí na JS a nic nestartuje v opacity: 0.

   VÝKON: mění se výhradně CSS proměnná, ze které se skládá
   background-position (povolená vlastnost). Počítají se jen sekce,
   které jsou právě ve viewportu (IntersectionObserver). Čtení layoutu
   a zápis stylů jsou oddělené do dvou fází, aby nevznikal forced reflow.
   ========================================================================= */
(function () {
    'use strict';

    var mq = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)');
    if (mq && mq.matches) return; /* pohyb se vypíná úplně, statické nasvícení zůstává */

    var sections = Array.prototype.slice.call(document.querySelectorAll('.pd > section'));
    if (!sections.length) return;

    var AMP = 9;          /* maximální posun svitu v px */
    var visible = [];
    var ticking = false;

    function request() {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }

    function update() {
        ticking = false;
        var vh = window.innerHeight || document.documentElement.clientHeight;
        var i, el, rect, p, y;
        var reads = [];

        /* fáze 1 — jen čtení layoutu */
        for (i = 0; i < visible.length; i++) {
            el = visible[i];
            rect = el.getBoundingClientRect();
            p = (vh - rect.top) / (vh + rect.height); /* 0 = vstupuje zdola, 1 = odchází nahoře */
            if (p < 0) p = 0; else if (p > 1) p = 1;
            y = Math.round((0.5 - p) * 2 * AMP);
            reads.push([el, y]);
        }

        /* fáze 2 — jen zápis, a to jen když se hodnota opravdu změnila */
        for (i = 0; i < reads.length; i++) {
            el = reads[i][0];
            y = reads[i][1];
            if (el.__pdPy !== y) {
                el.__pdPy = y;
                el.style.setProperty('--pd-py', y + 'px');
            }
        }
    }

    if (typeof window.IntersectionObserver === 'function') {
        var io = new IntersectionObserver(function (entries) {
            for (var i = 0; i < entries.length; i++) {
                var e = entries[i];
                var at = visible.indexOf(e.target);
                if (e.isIntersecting) {
                    if (at === -1) visible.push(e.target);
                } else if (at !== -1) {
                    visible.splice(at, 1);
                    e.target.__pdPy = 0;
                    e.target.style.setProperty('--pd-py', '0px');
                }
            }
            request();
        }, { rootMargin: '140px 0px' });

        for (var i = 0; i < sections.length; i++) io.observe(sections[i]);
    } else {
        visible = sections;
    }

    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request, { passive: true });
    request();
})();

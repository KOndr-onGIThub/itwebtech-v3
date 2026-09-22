/* ===========================================================================
   OND-246 — varianta 3 „Vrstvy": chování

   Dělá jen dvě věci a obě jsou levné:
   1) posouvá ohnisko povrchového světla (--mx/--my) za kurzorem, throttlované
      přes requestAnimationFrame — mění se jen dvě CSS proměnné, nic se
      nepřepočítává layoutem;
   2) při vjezdu desky do výřezu jí přidá .is-plate-set, čímž obsah dosedne
      o 2 px do své hladiny (jediný pohyb v celé variantě).

   Bez jemného ukazovátka (dotyk) se bod 1 vůbec nenavěsí — světlo zůstane
   staticky tam, kam ho posadilo CSS. Při prefers-reduced-motion se vypne
   i bod 2.
   =========================================================================== */
(function () {
    'use strict';

    var plates = [].slice.call(
        document.querySelectorAll('.pd-hero, .pd-section, .pd-strip, .pd-clients')
    );
    if (!plates.length) return;

    var reduce =
        window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* --- 2) dosednutí desky ------------------------------------------------ */
    var sections = plates.filter(function (el) {
        return el.classList.contains('pd-section');
    });

    if (reduce || !('IntersectionObserver' in window)) {
        sections.forEach(function (el) {
            el.classList.add('is-plate-set');
        });
    } else {
        var io = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-plate-set');
                        io.unobserve(e.target);
                    }
                });
            },
            { rootMargin: '0px 0px -12% 0px', threshold: 0.02 }
        );
        sections.forEach(function (el) {
            io.observe(el);
        });
    }

    /* --- 1) světlo po povrchu --------------------------------------------- */
    var fine =
        window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (!fine) return;

    var pending = null; // { el, x, y }
    var active = null;
    var ticking = false;

    function flush() {
        ticking = false;
        if (!pending) return;

        var el = pending.el;
        var x = pending.x;
        var y = pending.y;
        pending = null;

        if (active && active !== el) {
            // deska opuštěná kurzorem se vrací ke své statické poloze světla
            active.style.removeProperty('--mx');
            active.style.removeProperty('--my');
        }
        active = el;
        if (!el) return;

        var r = el.getBoundingClientRect();
        el.style.setProperty('--mx', Math.round(x - r.left) + 'px');
        el.style.setProperty('--my', Math.round(y - r.top) + 'px');
    }

    document.addEventListener(
        'pointermove',
        function (ev) {
            if (ev.pointerType && ev.pointerType !== 'mouse') return;
            var t = ev.target;
            var el =
                t && t.closest
                    ? t.closest('.pd-hero, .pd-section, .pd-strip, .pd-clients')
                    : null;
            pending = { el: el, x: ev.clientX, y: ev.clientY };
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(flush);
            }
        },
        { passive: true }
    );

    document.addEventListener(
        'pointerleave',
        function () {
            if (active) {
                active.style.removeProperty('--mx');
                active.style.removeProperty('--my');
                active = null;
            }
        },
        { passive: true }
    );
})();

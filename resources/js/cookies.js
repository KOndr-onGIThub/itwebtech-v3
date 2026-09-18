/**
 * Cookie consent + GA Consent Mode v2 gating (OND-125)
 * ────────────────────────────────────────────────────
 * Vlastní (bez externí knihovny) implementace cookie consent banneru
 * pro GA4 + Microsoft Clarity. Spec:
 *
 *   - GA/Clarity skripty se NIKDY nenačtou bez explicitního consentu.
 *   - „Přijmout vše" → boot GA4 (Consent Mode v2: analytics_storage granted,
 *     reklamní storage zůstává denied) + boot Clarity (consent true).
 *   - „Odmítnout" → kill-switch `window['ga-disable-...']`, aktivní vymazání
 *     GA/Clarity cookies, vyprázdnění `dataLayer`, žádné requesty.
 *   - Křížek / klik mimo modal → jen schová, žádný consent nezapíše.
 *   - localStorage `itwebtech_cookies` (TTL 365 d accepted / 180 d rejected).
 *   - Robustní vůči private mode — všechny storage operace v try/catch.
 *
 * Pre-flush queue (`window.dataLayer`, `window.gtag`, `window.clarity`) je
 * naplněna v `partials/analytics.blade.php` — `analytics.js` event tracker
 * pushuje eventy do queue ještě před tím, než dorazí consent.
 *
 * Public API:
 *   window.ItwebtechAnalytics = {
 *     acceptConsent(),
 *     rejectConsent(),
 *     revokeConsent(),
 *     hasConsent(),         // null | 'accepted' | 'rejected'
 *     measurementId,
 *     clarityId,
 *   };
 */

(function () {
    'use strict';

    var STORAGE_KEY     = 'itwebtech_cookies';
    var STORAGE_VERSION = 1;
    var TTL_ACCEPTED    = 365 * 24 * 60 * 60 * 1000; // 365 d
    var TTL_REJECTED    = 180 * 24 * 60 * 60 * 1000; // 180 d
    var GA4_ID_REGEX    = /^G-[A-Z0-9]+$/;
    var MODAL_DELAY_MS  = 800;
    var HIDE_ANIM_MS    = 350;

    var GA_COOKIE_NAMES = ['_ga', '_gid', '_gat'];
    var GA_COOKIE_PREFIXES = ['_ga_', '_gat_'];
    var CLARITY_COOKIE_NAMES = ['_clck', '_clsk', 'CLID', 'MUID', 'ANONCHK', 'SM'];

    var config = (typeof window !== 'undefined' && window.itwebtechAnalyticsConfig) || {};
    var rawMeasurementId = typeof config.measurementId === 'string' ? config.measurementId : null;
    var measurementId = rawMeasurementId && GA4_ID_REGEX.test(rawMeasurementId) ? rawMeasurementId : null;
    var clarityId = typeof config.clarityId === 'string' && config.clarityId ? config.clarityId : null;

    var ga4Booted = false;
    var clarityBooted = false;

    // ────────────────────────────────────────────────────────────
    // Storage (defense-in-depth: private mode, disabled localStorage)
    // ────────────────────────────────────────────────────────────
    function readConsent() {
        try {
            var raw = window.localStorage.getItem(STORAGE_KEY);
            if (!raw) return null;
            var parsed = JSON.parse(raw);
            if (!parsed || parsed.version !== STORAGE_VERSION) {
                clearConsent();
                return null;
            }
            if (typeof parsed.expiresAt === 'number' && Date.now() > parsed.expiresAt) {
                clearConsent();
                return null;
            }
            if (parsed.status === 'accepted' || parsed.status === 'rejected') {
                return parsed;
            }
            return null;
        } catch (e) {
            return null;
        }
    }

    function writeConsent(status) {
        var ttl = status === 'accepted' ? TTL_ACCEPTED : TTL_REJECTED;
        var now = Date.now();
        var payload = {
            status: status,
            timestamp: now,
            expiresAt: now + ttl,
            version: STORAGE_VERSION,
        };
        try {
            window.localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
        } catch (e) { /* swallow — private mode / disabled storage */ }
        return payload;
    }

    function clearConsent() {
        try {
            window.localStorage.removeItem(STORAGE_KEY);
        } catch (e) { /* swallow */ }
    }

    // ────────────────────────────────────────────────────────────
    // GA kill-switch
    // ────────────────────────────────────────────────────────────
    function setAnalyticsDisabled(disabled) {
        if (!measurementId) return;
        try {
            window['ga-disable-' + measurementId] = !!disabled;
        } catch (e) { /* swallow */ }
    }

    // ────────────────────────────────────────────────────────────
    // Cookie purge — _ga*, _gid, _gat*, _clck, _clsk, CLID, MUID, ...
    // ────────────────────────────────────────────────────────────
    function getCookieDomains() {
        var domains = [''];
        try {
            var host = window.location.hostname || '';
            if (host) {
                domains.push(host);
                // Root domain (např. example.com pro a.b.example.com)
                var parts = host.split('.');
                if (parts.length >= 2) {
                    var root = parts.slice(-2).join('.');
                    if (root !== host) domains.push(root);
                    domains.push('.' + root);
                }
                domains.push('.' + host);
            }
        } catch (e) { /* swallow */ }
        // Dedupe
        var seen = {};
        return domains.filter(function (d) {
            if (seen[d]) return false;
            seen[d] = true;
            return true;
        });
    }

    function eraseCookie(name) {
        var domains = getCookieDomains();
        var paths = ['/', ''];
        for (var i = 0; i < domains.length; i++) {
            for (var j = 0; j < paths.length; j++) {
                try {
                    var parts = [name + '=', 'Max-Age=0', 'expires=Thu, 01 Jan 1970 00:00:00 GMT'];
                    if (paths[j]) parts.push('path=' + paths[j]);
                    if (domains[i]) parts.push('domain=' + domains[i]);
                    document.cookie = parts.join('; ');
                } catch (e) { /* swallow */ }
            }
        }
    }

    function purgeAnalyticsCookies() {
        // Pevně dané jména
        for (var i = 0; i < GA_COOKIE_NAMES.length; i++) eraseCookie(GA_COOKIE_NAMES[i]);
        for (var k = 0; k < CLARITY_COOKIE_NAMES.length; k++) eraseCookie(CLARITY_COOKIE_NAMES[k]);

        // Prefix-based (_ga_<stream>, _gat_<id>)
        try {
            var jar = document.cookie ? document.cookie.split(';') : [];
            for (var c = 0; c < jar.length; c++) {
                var rawName = jar[c].split('=')[0];
                if (!rawName) continue;
                var name = rawName.replace(/^\s+/, '');
                for (var p = 0; p < GA_COOKIE_PREFIXES.length; p++) {
                    if (name.indexOf(GA_COOKIE_PREFIXES[p]) === 0) {
                        eraseCookie(name);
                        break;
                    }
                }
            }
        } catch (e) { /* swallow */ }
    }

    function clearDataLayer() {
        try {
            if (Array.isArray(window.dataLayer)) {
                window.dataLayer.length = 0;
            }
        } catch (e) { /* swallow */ }
    }

    // ────────────────────────────────────────────────────────────
    // GA4 boot (Consent Mode v2)
    // ────────────────────────────────────────────────────────────
    function bootGA4() {
        if (ga4Booted || !measurementId) return;
        ga4Booted = true;

        setAnalyticsDisabled(false);

        // Inicializace queue (idempotentní s pre-flush queue z analytics partial)
        window.dataLayer = window.dataLayer || [];
        if (typeof window.gtag !== 'function') {
            window.gtag = function () { window.dataLayer.push(arguments); };
        }

        try {
            window.gtag('consent', 'default', {
                analytics_storage: 'denied',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
                wait_for_update: 500,
            });
            window.gtag('js', new Date());
            window.gtag('consent', 'update', {
                analytics_storage: 'granted',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
            });

            var s = document.createElement('script');
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId);
            document.head.appendChild(s);

            window.gtag('config', measurementId, { anonymize_ip: true });
        } catch (e) {
            // Pokud GA4 selže, nech kill-switch zapnutý, ale pageview / consent
            // už proběhl přes queue. Detail v console při DEBUG=true v analytics.js.
        }
    }

    // ────────────────────────────────────────────────────────────
    // Microsoft Clarity boot
    // ────────────────────────────────────────────────────────────
    function bootClarity() {
        if (clarityBooted || !clarityId) return;
        clarityBooted = true;

        if (typeof window.clarity !== 'function') {
            window.clarity = function () {
                (window.clarity.q = window.clarity.q || []).push(arguments);
            };
        }

        try {
            var c = document.createElement('script');
            c.async = true;
            c.src = 'https://www.clarity.ms/tag/' + encodeURIComponent(clarityId);
            c.addEventListener('load', function () {
                try { window.clarity('consent', true); } catch (e) { /* swallow */ }
            }, { once: true });
            document.head.appendChild(c);
            // Pre-flush consent — `clarity()` queue zachytí volání ještě před načtením skriptu.
            try { window.clarity('consent', true); } catch (e) { /* swallow */ }
        } catch (e) { /* swallow */ }
    }

    // ────────────────────────────────────────────────────────────
    // Modal — DOM hooks
    // ────────────────────────────────────────────────────────────
    var overlayEl = null;
    var modalEl = null;
    var acceptBtn = null;
    var rejectBtn = null;
    var closeBtn = null;
    var hideTimer = null;
    var lastFocusedEl = null;

    function queryModal() {
        overlayEl = document.getElementById('cookie-overlay');
        if (!overlayEl) return false;
        modalEl   = overlayEl.querySelector('#cookie-modal');
        acceptBtn = overlayEl.querySelector('#cookie-accept');
        rejectBtn = overlayEl.querySelector('#cookie-reject');
        closeBtn  = overlayEl.querySelector('#cookie-close');
        return !!modalEl;
    }

    function showModal() {
        if (!overlayEl) return;
        overlayEl.classList.remove('cookie-overlay--hiding');
        overlayEl.classList.add('cookie-overlay--visible');
        overlayEl.setAttribute('aria-hidden', 'false');
        // OND-231 F3: lišta není modal — focus nepřebíráme. Dřív sem
        // skočil focus na „Přijmout", což na klávesnici odstřihlo
        // uživatele od obsahu, který si právě otevřel.
        try {
            lastFocusedEl = document.activeElement;
        } catch (e) { /* swallow */ }
    }

    function hideModal(persist) {
        if (!overlayEl) return;
        overlayEl.classList.remove('cookie-overlay--visible');
        overlayEl.classList.add('cookie-overlay--hiding');
        overlayEl.setAttribute('aria-hidden', 'true');

        // Vrátit focus
        try {
            if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') {
                lastFocusedEl.focus({ preventScroll: true });
            }
        } catch (e) { /* swallow */ }

        if (hideTimer) clearTimeout(hideTimer);
        hideTimer = setTimeout(function () {
            try {
                if (persist) {
                    overlayEl.parentNode && overlayEl.parentNode.removeChild(overlayEl);
                } else {
                    overlayEl.classList.remove('cookie-overlay--hiding');
                }
            } catch (e) { /* swallow */ }
        }, HIDE_ANIM_MS);
    }

    // ────────────────────────────────────────────────────────────
    // Akce
    // ────────────────────────────────────────────────────────────
    function acceptConsent() {
        writeConsent('accepted');
        bootGA4();
        bootClarity();
        hideModal(true);
    }

    function rejectConsent() {
        writeConsent('rejected');
        setAnalyticsDisabled(true);
        try {
            if (typeof window.clarity === 'function') {
                window.clarity('consent', false);
            }
        } catch (e) { /* swallow */ }
        clearDataLayer();
        purgeAnalyticsCookies();
        hideModal(true);
    }

    function revokeConsent() {
        clearConsent();
        setAnalyticsDisabled(true);
        try {
            if (typeof window.clarity === 'function') {
                window.clarity('consent', false);
            }
        } catch (e) { /* swallow */ }
        clearDataLayer();
        purgeAnalyticsCookies();
        ga4Booted = false;
        clarityBooted = false;
    }

    function dismissOnly() {
        // Křížek / klik mimo / Esc — pouze schová, žádný consent.
        hideModal(false);
    }

    function bindModalEvents() {
        if (!overlayEl) return;

        if (acceptBtn) acceptBtn.addEventListener('click', function (e) {
            e.preventDefault();
            acceptConsent();
        });
        if (rejectBtn) rejectBtn.addEventListener('click', function (e) {
            e.preventDefault();
            rejectConsent();
        });
        if (closeBtn) closeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            dismissOnly();
        });

        // Klik na overlay (mimo modal)
        overlayEl.addEventListener('click', function (e) {
            if (e.target === overlayEl) dismissOnly();
        });

        // Esc = stejné jako křížek (dismiss without consent)
        document.addEventListener('keydown', function (e) {
            if (!overlayEl || !overlayEl.classList.contains('cookie-overlay--visible')) return;
            if (e.key === 'Escape' || e.key === 'Esc') {
                e.preventDefault();
                dismissOnly();
            }
        });
    }

    // ────────────────────────────────────────────────────────────
    // Setup
    // ────────────────────────────────────────────────────────────
    function setupCookieModal() {
        if (!measurementId && !clarityId) {
            // Bez ID nemáme co řídit — modal odstraň (pokud byl renderován)
            var stray = document.getElementById('cookie-overlay');
            if (stray && stray.parentNode) stray.parentNode.removeChild(stray);
            return;
        }

        var consent = readConsent();

        // Defense in depth — defaultně GA disabled, dokud nemáme „accepted".
        setAnalyticsDisabled(!consent || consent.status !== 'accepted');

        if (consent && consent.status === 'accepted') {
            bootGA4();
            bootClarity();
            // Modal nezobrazujeme — pokud byl v DOM, ponech ho skrytý
            var existing = document.getElementById('cookie-overlay');
            if (existing && existing.parentNode) existing.parentNode.removeChild(existing);
            return;
        }

        if (consent && consent.status === 'rejected') {
            // Defense in depth — vyčistit, kdyby předchozí session něco zanechala.
            clearDataLayer();
            purgeAnalyticsCookies();
            var rejectedEl = document.getElementById('cookie-overlay');
            if (rejectedEl && rejectedEl.parentNode) rejectedEl.parentNode.removeChild(rejectedEl);
            return;
        }

        // Žádný consent → zobrazit modal s 800ms zpožděním (nepřebíjet LCP)
        if (!queryModal()) return;
        bindModalEvents();
        setTimeout(showModal, MODAL_DELAY_MS);
    }

    // ────────────────────────────────────────────────────────────
    // Public API
    // ────────────────────────────────────────────────────────────
    window.ItwebtechAnalytics = {
        acceptConsent: acceptConsent,
        rejectConsent: rejectConsent,
        revokeConsent: revokeConsent,
        hasConsent: function () {
            var c = readConsent();
            return c ? c.status : null;
        },
        measurementId: measurementId,
        clarityId: clarityId,
    };

    // ────────────────────────────────────────────────────────────
    // Boot
    // ────────────────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupCookieModal);
    } else {
        setupCookieModal();
    }
})();

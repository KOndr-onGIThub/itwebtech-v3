/**
 * Analytics dispatcher (OND-122, plán §9)
 * ────────────────────────────────────────
 * Odesílá mikrokonverze do Plausible a/nebo GA4 podle `window.__analyticsConfig`,
 * který je naplněn v `resources/views/partials/analytics.blade.php` jen tehdy,
 * pokud je `ANALYTICS_ENABLED=true` a alespoň jeden provider má vyplněnou konfiguraci.
 *
 * Mapping eventů → element:
 *   data-analytics="event_name"          → odešle na click
 *   data-analytics-view="event_name"     → odešle jednou po viditelnosti
 *                                         (IntersectionObserver, ≥40% nebo 600px)
 *   data-analytics-props='{"k":"v"}'     → volitelné props (JSON na elementu)
 *
 * Speciální eventy (server-side):
 *   inline_form_submit_success → emitnuto z home.blade.php přes
 *                                CustomEvent('inline-form-submit-success'),
 *                                který se dispatchne, pokud session
 *                                obsahuje `home_lead_success` (úspěšný POST).
 */

const DEBUG = false; // zapnout pro console.log diagnostiku

// Dedupe: stejný event jméno (bez props) nepošleme 2× během DEDUPE_MS okna.
// Chrání proti click+submit double-fire u submit tlačítka (Enter key).
const DEDUPE_MS = 1000;
const recentDispatches = new Map();

function isEnabled() {
    return typeof window !== 'undefined' && !!window.__analyticsConfig?.enabled;
}

function dispatch(eventName, props = {}) {
    if (!eventName) return;
    const now = Date.now();
    const last = recentDispatches.get(eventName);
    if (last && now - last < DEDUPE_MS) return;
    recentDispatches.set(eventName, now);

    const cfg = window.__analyticsConfig || {};
    const name = eventName;
    const payload = Object.keys(props).length ? { props } : undefined;

    try {
        if (cfg.plausible && typeof window.plausible === 'function') {
            window.plausible(name, payload);
        }
    } catch (e) { /* swallow */ }

    try {
        if (cfg.ga4 && typeof window.gtag === 'function') {
            window.gtag('event', name, props);
        }
    } catch (e) { /* swallow */ }

    if (DEBUG) console.log('[analytics]', name, props); // eslint-disable-line no-console
}

function parseProps(el) {
    const raw = el?.dataset?.analyticsProps;
    if (!raw) return {};
    try { return JSON.parse(raw); } catch { return {}; }
}

// ───────────────────────────────────────────────────────────────────────────
// Click delegation pro [data-analytics]
// ───────────────────────────────────────────────────────────────────────────
function bindClicks() {
    document.addEventListener('click', (e) => {
        const el = e.target.closest('[data-analytics]');
        if (!el) return;
        dispatch(el.dataset.analytics, parseProps(el));
    }, { capture: true, passive: true });
}

// ───────────────────────────────────────────────────────────────────────────
// IntersectionObserver pro [data-analytics-view] (1×)
// ───────────────────────────────────────────────────────────────────────────
function bindViews() {
    const targets = document.querySelectorAll('[data-analytics-view]');
    if (!targets.length || typeof IntersectionObserver === 'undefined') return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            dispatch(el.dataset.analyticsView, parseProps(el));
            observer.unobserve(el);
        });
    }, { threshold: 0.4, rootMargin: '0px 0px -10% 0px' });

    targets.forEach((el) => observer.observe(el));
}

// ───────────────────────────────────────────────────────────────────────────
// FAQ <details> open → faq_open (per plán §9.2)
// Funguje pro nativní <details>/<summary> i pro [data-faq-item] s aria-expanded.
// ───────────────────────────────────────────────────────────────────────────
function bindFaq() {
    // Nativní <details> uvnitř .faq-item
    document.querySelectorAll('.faq-item').forEach((item) => {
        const details = item.matches('details') ? item : item.querySelector('details');
        if (!details) return;
        details.addEventListener('toggle', () => {
            if (!details.open) return;
            const qId = item.dataset.qId || item.getAttribute('id') || '';
            dispatch('faq_open', qId ? { q_id: qId } : {});
        });
    });
}

// ───────────────────────────────────────────────────────────────────────────
// Server-side success → inline_form_submit_success
// home.blade.php dispatchne `inline-form-submit-success` po úspěšném POST.
// ───────────────────────────────────────────────────────────────────────────
function bindFormSuccess() {
    window.addEventListener('inline-form-submit-success', () => {
        dispatch('inline_form_submit_success');
    });
    // FAQ mikro-formulář (OND-121 T18) — server-side success.
    window.addEventListener('faq-form-submit-success', () => {
        dispatch('faq_form_submit_success');
    });
    // Záloha kromě click delegate na submit tlačítku — Enter v textovém poli
    // může v některých prohlížečích vyvolat submit bez synthesized click.
    window.addEventListener('inline-form-submit-attempt', () => {
        dispatch('inline_form_submit_attempt');
    });
}

// ───────────────────────────────────────────────────────────────────────────
// Boot
// ───────────────────────────────────────────────────────────────────────────
function boot() {
    if (!isEnabled()) return;
    bindClicks();
    bindViews();
    bindFaq();
    bindFormSuccess();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

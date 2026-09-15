{{--
    Analytics scripts (OND-122, gated v OND-125)
    ─────────────────────────────────────────────
    Vykreslí Plausible tracking (cookieless, GDPR-OK → načítá se rovnou)
    a pre-flush queue pro GA4 / Microsoft Clarity. Aktuální boot GA4/Clarity
    řídí `resources/js/cookies.js` na základě souhlasu (Consent Mode v2 +
    aktivní kill-switch v případě „Odmítnout").

    Master vypínač: ANALYTICS_ENABLED (default false). Na stagingu vždy false.

    Eventy se odesílají z resources/js/analytics.js přes `data-analytics`
    a `data-analytics-view` atributy v šablonách.
--}}
@php
    $analytics = config('site.analytics');
    $enabled = (bool) ($analytics['enabled'] ?? false);
    $plausibleDomain = $analytics['plausible']['domain'] ?? null;
    $plausibleSrc    = $analytics['plausible']['script_url'] ?? null;
    $ga4Id           = $analytics['ga4']['measurement_id'] ?? null;
    // OND-123 iter3: explicit killswitch — i když je `project_id` nastavené,
    // můžeme Clarity vypnout přes env (kvůli PSI mobile perf, YouTube preconnect).
    $clarityEnabled  = (bool) ($analytics['clarity']['enabled'] ?? true);
    $clarityId       = $clarityEnabled ? ($analytics['clarity']['project_id'] ?? null) : null;
@endphp

@if ($enabled)
    {{-- Plausible (preferované, GDPR-friendly, cookieless → bez consent banneru). --}}
    @if ($plausibleDomain && $plausibleSrc)
        <script defer
                data-domain="{{ $plausibleDomain }}"
                src="{{ $plausibleSrc }}"></script>
        <script>
            // Inicializuj `window.plausible` queue, aby fungovala i před načtením skriptu.
            window.plausible = window.plausible || function () {
                (window.plausible.q = window.plausible.q || []).push(arguments);
            };
        </script>
    @endif

    {{-- GA4 + Microsoft Clarity — OND-125:
         Konfigurace pro `cookies.js` (boot až po consentu) + pre-flush fronty
         pro `analytics.js`, aby eventy zachycené před consentem nezmizely.

         Skripty `gtag/js` a `clarity.ms/tag` se NEINJEKTUJÍ z tohoto partialu
         — to dělá `resources/js/cookies.js` v `bootGA4()` / `bootClarity()`
         po kliknutí na „Přijmout vše".

         Kill-switch `window['ga-disable-...']` se aktivuje v `cookies.js` při
         „Odmítnout" / před prvním zobrazením modalu (defense in depth). --}}
    @if ($ga4Id || $clarityId)
        <script>
            // Config pro cookies.js (consent gating). Validace ID je v JS.
            window.itwebtechAnalyticsConfig = {
                measurementId: @json($ga4Id),
                clarityId: @json($clarityId)
            };

            // Pre-flush fronta — `analytics.js` event tracker push-uje eventy
            // ještě před tím, než boot skripty doběhnou (nebo bez consentu
            // tichý swallow).
            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
            window.clarity = window.clarity || function () {
                (window.clarity.q = window.clarity.q || []).push(arguments);
            };

            // Default consent state (Consent Mode v2) — GA4 ví, že do
            // `bootGA4()` updatu má všechno denied. Pro analytics_storage to
            // bootGA4() přepne na „granted".
            window.gtag('consent', 'default', {
                analytics_storage: 'denied',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
                wait_for_update: 500
            });
        </script>
    @endif

    {{-- Globální flag pro analytics.js — zda existuje aspoň jeden provider.
         Bez něj analytics.js nestaví listenery, takže neflushne fronty
         v prostředí, kde nikoho nezajímají.

         OND-137 P4 §6: pageLang exposujeme do JS, analytics.js ji přidává
         ke každému eventu jako custom dimension `page_lang` (cs/en/de). --}}
    <script>
        window.__analyticsConfig = {
            plausible: @json((bool) ($plausibleDomain && $plausibleSrc)),
            ga4:       @json((bool) $ga4Id),
            enabled:   true,
            pageLang:  @json(app()->getLocale()),
        };
    </script>
@endif

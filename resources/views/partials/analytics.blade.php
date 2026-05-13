{{--
    Analytics scripts (OND-122 / plán §9)
    ─────────────────────────────────────
    Vykreslí Plausible, GA4 a Microsoft Clarity tracking podle config/site.php.
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
    $clarityId       = $analytics['clarity']['project_id'] ?? null;
@endphp

@if ($enabled)
    {{-- Plausible (preferované, GDPR-friendly). Tagged-events varianta umí
         číst data-atributy z HTML i `plausible(event, {props})` z JS. --}}
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

    {{-- GA4 (volitelný paralelní tool). --}}
    @if ($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function () { dataLayer.push(arguments); };
            gtag('js', new Date());
            gtag('config', @json($ga4Id), { 'anonymize_ip': true });
        </script>
    @endif

    {{-- Microsoft Clarity — heatmapy + session recordings. --}}
    @if ($clarityId)
        <script>
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", @json($clarityId));
        </script>
    @endif

    {{-- Globální flag pro analytics.js — zda existuje aspoň jeden provider.
         Bez něj analytics.js nestaví listenery, takže neflushne fronty
         v prostředí, kde nikoho nezajímají. --}}
    <script>
        window.__analyticsConfig = {
            plausible: @json((bool) ($plausibleDomain && $plausibleSrc)),
            ga4:       @json((bool) $ga4Id),
            enabled:   true,
        };
    </script>
@endif

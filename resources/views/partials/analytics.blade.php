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
    // OND-123 iter3: explicit killswitch — i když je `project_id` nastavené,
    // můžeme Clarity vypnout přes env (kvůli PSI mobile perf, YouTube preconnect).
    $clarityEnabled  = (bool) ($analytics['clarity']['enabled'] ?? true);
    $clarityId       = $clarityEnabled ? ($analytics['clarity']['project_id'] ?? null) : null;
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

    {{-- GA4 + Microsoft Clarity — OND-123 follow-up:
         PSI mobile audit hlásil 54 KiB nepoužitého JS (GTM/gtag) + 25 KiB
         Clarity + 3,4 s main-thread práce. Odkládáme jejich init do
         `requestIdleCallback` (fallback `setTimeout`) — měření tím získá idle
         pool po LCP/TTI, ale eventy se stále zachytí. dataLayer/gtag fronta
         je k dispozici synchronně, takže `data-analytics` z UI nic neztratí. --}}
    @if ($ga4Id || $clarityId)
        <script>
            (function () {
                // Pre-flush fronta — Analytics modul může pushovat eventy ještě
                // před tím, než reálné skripty doběhnou.
                window.dataLayer = window.dataLayer || [];
                window.gtag = window.gtag || function () { dataLayer.push(arguments); };
                window.clarity = window.clarity || function () {
                    (window.clarity.q = window.clarity.q || []).push(arguments);
                };

                var ga4Id = @json($ga4Id);
                var clarityId = @json($clarityId);

                function loadAnalytics() {
                    if (ga4Id) {
                        var s = document.createElement('script');
                        s.async = true;
                        s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(ga4Id);
                        document.head.appendChild(s);
                        gtag('js', new Date());
                        gtag('config', ga4Id, { 'anonymize_ip': true });
                    }
                    if (clarityId) {
                        var c = document.createElement('script');
                        c.async = true;
                        c.src = 'https://www.clarity.ms/tag/' + encodeURIComponent(clarityId);
                        document.head.appendChild(c);
                    }
                }

                if (typeof requestIdleCallback === 'function') {
                    requestIdleCallback(loadAnalytics, { timeout: 4000 });
                } else {
                    // Safari: po `load` eventu + malý jitter, aby se nepřebíjelo s LCP.
                    if (document.readyState === 'complete') {
                        setTimeout(loadAnalytics, 2500);
                    } else {
                        window.addEventListener('load', function () {
                            setTimeout(loadAnalytics, 2500);
                        }, { once: true });
                    }
                }
            })();
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

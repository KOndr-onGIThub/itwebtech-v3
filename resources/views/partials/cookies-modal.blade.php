{{--
    Cookie consent modal (OND-125)
    ──────────────────────────────
    Statický HTML markup pro consent banner. JS modul (`resources/js/cookies.js`)
    řídí viditelnost přes třídu `.cookie-overlay--visible` a deleguje akce
    tlačítek na `window.ItwebtechAnalytics`.

    Modal se vykresluje pouze pokud je aspoň jeden analytics provider (GA4 nebo
    Clarity) nakonfigurován. Pre-flush queue a config (`window.itwebtechAnalyticsConfig`)
    nastavuje `partials/analytics.blade.php`.
--}}
@php
    $analytics  = config('site.analytics');
    $enabled    = (bool) ($analytics['enabled'] ?? false);
    $ga4Id      = $analytics['ga4']['measurement_id'] ?? null;
    $clarityOn  = (bool) ($analytics['clarity']['enabled'] ?? true);
    $clarityId  = $clarityOn ? ($analytics['clarity']['project_id'] ?? null) : null;
@endphp

@if ($enabled && ($ga4Id || $clarityId))
<div id="cookie-overlay" class="cookie-overlay" aria-hidden="true">
    <div id="cookie-modal"
         class="cookie-modal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="cookie-title"
         aria-describedby="cookie-text">
        <button id="cookie-close"
                class="cookie-modal__close"
                aria-label="Zavřít"
                type="button">✕</button>
        <div class="cookie-modal__icon-wrap" aria-hidden="true">🍪</div>
        <p id="cookie-title" class="cookie-modal__title">Můžeme používat cookies?</p>
        <p id="cookie-text" class="cookie-modal__text">
            Pro lepší pochopení, jak web používáte, používáme analytické cookies
            (Google Analytics, Microsoft Clarity). Žádné reklamní cookies nepoužíváme.
            <a href="/cookies" class="cookie-modal__policy-link">Detail v zásadách</a>.
        </p>
        <div class="cookie-modal__actions">
            <button id="cookie-accept" class="cookie-modal__accept" type="button">Přijmout vše</button>
            <button id="cookie-reject" class="cookie-modal__reject" type="button">Odmítnout</button>
        </div>
    </div>
</div>
@endif

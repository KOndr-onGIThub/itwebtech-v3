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
    // OND-167 Fix 1 — Modal nesmí přebíjet detail cookie-policy stránky.
    // Defense in depth: server-side suppress + JS guard v cookies.js (window-name match).
    $onCookiePolicy = request()->routeIs('cookies');
@endphp

@if ($enabled && ($ga4Id || $clarityId) && ! $onCookiePolicy)
<div id="cookie-overlay" class="cookie-overlay" aria-hidden="true">
    <div id="cookie-modal"
         class="cookie-modal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="cookie-title"
         aria-describedby="cookie-text">
        <button id="cookie-close"
                class="cookie-modal__close"
                aria-label="{{ __('layout.cookies.close') }}"
                type="button">✕</button>
        <div class="cookie-modal__icon-wrap" aria-hidden="true">🍪</div>
        <p id="cookie-title" class="cookie-modal__title">{{ __('layout.cookies.title') }}</p>
        <p id="cookie-text" class="cookie-modal__text">
            {{ __('layout.cookies.body') }}
            <a href="/cookies" class="cookie-modal__policy-link">{{ __('layout.cookies.policy_link') }}</a>.
        </p>
        <div class="cookie-modal__actions">
            <button id="cookie-accept" class="cookie-modal__accept" type="button">{{ __('layout.cookies.accept') }}</button>
            <button id="cookie-reject" class="cookie-modal__reject" type="button">{{ __('layout.cookies.reject') }}</button>
        </div>
    </div>
</div>
@endif

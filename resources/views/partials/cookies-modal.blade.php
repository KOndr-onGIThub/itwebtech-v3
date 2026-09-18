{{--
    Cookie consent — lišta (OND-125, přepsáno v OND-231 F3)
    ───────────────────────────────────────────────────────
    Do F3 to byl centrovaný modal s tmavým backdropem: na desktopu
    i mobilu překryl celý hero a prvních deset vteřin návštěvy patřilo
    jemu, ne webu. Nově je to nenápadná lišta dole, která nic
    nepřekrývá — první dojem zůstává na webu.

    Právní chování se nemění a je celé v `resources/js/cookies.js`:
    GA4 ani Clarity se bez explicitního „Přijmout" nenačtou, „Odmítnout"
    zapne kill-switch a smaže případné analytické cookies. Lišta drží
    původní id (`cookie-overlay`, `cookie-accept`, `cookie-reject`,
    `cookie-close`), takže JS zůstal beze změny.

    Renderuje se jen pokud je aspoň jeden analytics provider (GA4 nebo
    Clarity) nakonfigurován. Pre-flush queue a config
    (`window.itwebtechAnalyticsConfig`) nastavuje `partials/analytics.blade.php`.
--}}
@php
    $analytics  = config('site.analytics');
    $enabled    = (bool) ($analytics['enabled'] ?? false);
    $ga4Id      = $analytics['ga4']['measurement_id'] ?? null;
    $clarityOn  = (bool) ($analytics['clarity']['enabled'] ?? true);
    $clarityId  = $clarityOn ? ($analytics['clarity']['project_id'] ?? null) : null;
    // OND-167 Fix 1 — Lišta nesmí přebíjet detail cookie-policy stránky.
    // Defense in depth: server-side suppress + JS guard v cookies.js.
    // OND-168: route names jsou per-locale, matchujeme wildcard `*.cookies`.
    $onCookiePolicy = request()->routeIs('*.cookies');
@endphp

@if ($enabled && ($ga4Id || $clarityId) && ! $onCookiePolicy)
<div id="cookie-overlay" class="cookie-overlay" aria-hidden="true">
    {{-- id="cookie-modal" drží kontrakt s cookies.js; role je `region`,
         ne `dialog` — lišta nic neblokuje a nekrade focus. --}}
    <div id="cookie-modal"
         class="cookie-bar"
         role="region"
         aria-labelledby="cookie-title"
         aria-describedby="cookie-text">
        {{-- Titulek je inline uvnitř odstavce, ne na vlastním řádku —
             na mobilu je každý ušetřený řádek kus hero, který lišta
             nepřekryje. --}}
        <p id="cookie-text" class="cookie-bar__text">
            <strong id="cookie-title" class="cookie-bar__title">{{ __('layout.cookies.title') }}</strong>
            {{ __('layout.cookies.body') }}
            <a href="{{ lroute('cookies') }}" class="cookie-bar__policy-link">{{ __('layout.cookies.policy_link') }}</a>.
        </p>
        <div class="cookie-bar__actions">
            <button id="cookie-reject" class="cookie-bar__reject" type="button">{{ __('layout.cookies.reject') }}</button>
            <button id="cookie-accept" class="cookie-bar__accept" type="button">{{ __('layout.cookies.accept') }}</button>
        </div>
        <button id="cookie-close"
                class="cookie-bar__close"
                aria-label="{{ __('layout.cookies.close') }}"
                type="button">✕</button>
    </div>
</div>
@endif

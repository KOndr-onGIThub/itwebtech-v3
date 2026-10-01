{{--
    Cookie consent — lišta (OND-125, přepsáno v OND-231 F3, vzhled OND-508)
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
        {{-- OND-508: nadpis na vlastním řádku s ikonou — na první pohled
             systémové oznámení, ne další odstavec webu. --}}
        <div class="cookie-bar__content">
            <p class="cookie-bar__head">
                <svg class="cookie-bar__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/>
                    <path d="M8.5 8.5v.01"/><path d="M16 15.5v.01"/><path d="M12 12v.01"/><path d="M11 17v.01"/><path d="M7 14v.01"/>
                </svg>
                <strong id="cookie-title" class="cookie-bar__title">{{ __('layout.cookies.title') }}</strong>
            </p>
            <p id="cookie-text" class="cookie-bar__text">
                {{ __('layout.cookies.body') }}
                <a href="{{ lroute('cookies') }}" class="cookie-bar__policy-link">{{ __('layout.cookies.policy_link') }}</a>.
            </p>
        </div>
        <div class="cookie-bar__actions">
            <button id="cookie-reject" class="cookie-bar__reject" type="button">{{ __('layout.cookies.reject') }}</button>
            <button id="cookie-accept" class="cookie-bar__accept" type="button">{{ __('layout.cookies.accept') }}</button>
        </div>
        <button id="cookie-close"
                class="cookie-bar__close"
                aria-label="{{ __('layout.cookies.close') }}"
                type="button">
            <svg aria-hidden="true" focusable="false" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                <path d="M3.5 3.5l9 9M12.5 3.5l-9 9"/>
            </svg>
        </button>
    </div>
</div>
@endif

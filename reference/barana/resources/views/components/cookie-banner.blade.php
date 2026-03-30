@php $gaId = config('services.google.analytics_id'); @endphp
@if ($gaId)
<div
    x-data="cookieConsent('{{ $gaId }}', {{ request()->routeIs('zasady-cookies') ? 'true' : 'false' }})"
    x-show="visible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click.self="close()"
    x-cloak
    aria-label="Nastavení cookies"
    class="cookie-overlay"
>
    <div
        x-show="visible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        role="dialog"
        aria-modal="true"
        class="cookie-modal"
    >
        {{-- X close button --}}
        <button @click="close()" aria-label="Zavřít" class="cookie-modal__close">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="2" y1="2" x2="14" y2="14"/>
                <line x1="14" y1="2" x2="2" y2="14"/>
            </svg>
        </button>

        {{-- Ikona --}}
        <div class="cookie-modal__icon" aria-hidden="true">🍪</div>

        {{-- Text --}}
        <div class="cookie-modal__text">
            <p class="cookie-modal__title">Pomůžeme nám zlepšovat tento web?</p>
            <p class="cookie-modal__desc">
                Používáme analytické cookies (Google Analytics), abychom zjistili, co vás na webu zajímá,
                a postupně vylepšovali obsah i strukturu stránek.
                Data jsou anonymní — neprodáváme je ani nesdílíme s třetími stranami.
                <a href="{{ route('zasady-cookies') }}" class="cookie-modal__link">Více o cookies</a>
            </p>
        </div>

        {{-- Tlačítka --}}
        <div class="cookie-modal__actions">
            <button @click="accept()" class="cookie-modal__accept">
                Souhlasím s cookies
            </button>
            <button @click="decline()" class="cookie-modal__decline">
                Odmítnout
            </button>
        </div>
    </div>
</div>
@endif

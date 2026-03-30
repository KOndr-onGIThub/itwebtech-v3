@extends('layouts.app')

@section('title', 'Zásady cookies — BARANA s.r.o.')
@section('description', 'Informace o používání souborů cookies na webu BARANA s.r.o.')
@section('hide_prefooter') true @endsection

@section('content')
<section class="section-wrapper">
    <div class="container-site max-w-3xl">

        <div class="mb-10" data-reveal>
            <span class="section-eyebrow">Právní informace</span>
            <h1 class="section-title">Zásady cookies</h1>
            <p class="section-sub">Poslední aktualizace: {{ now()->format('j. n. Y') }}</p>
        </div>

        <div class="space-y-8 text-body leading-relaxed" data-reveal>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Co jsou cookies</h2>
                <p>Cookies jsou malé textové soubory, které se ukládají do vašeho prohlížeče při návštěvě webové stránky. Slouží k rozlišení uživatelů, zapamatování preferencí a analýze návštěvnosti.</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Jaké cookies používáme</h2>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="tech-table w-full">
                        <thead>
                            <tr>
                                <th>Typ</th>
                                <th>Účel</th>
                                <th>Platnost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium text-heading">Nezbytné</td>
                                <td>Zajišťují základní funkčnost webu (session, CSRF ochrana)</td>
                                <td>Session</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-heading">Analytické</td>
                                <td>Anonymní statistiky návštěvnosti (Google Analytics)</td>
                                <td>2 roky</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Jak spravovat cookies</h2>
                <p>Cookies můžete spravovat nebo zakázat v nastavení vašeho prohlížeče. Zakázání nezbytných cookies může omezit funkčnost webu.</p>
                <ul class="mt-3 space-y-1.5 text-sm">
                    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener" class="text-sage hover:underline">Google Chrome</a></li>
                    <li><a href="https://support.mozilla.org/cs/kb/povoleni-zakazani-cookies" target="_blank" rel="noopener" class="text-sage hover:underline">Mozilla Firefox</a></li>
                    <li><a href="https://support.apple.com/cs-cz/guide/safari/sfri11471/mac" target="_blank" rel="noopener" class="text-sage hover:underline">Apple Safari</a></li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Kontakt</h2>
                <p>Máte-li dotazy k těmto zásadám, kontaktujte nás na <a href="mailto:info@barana.cz" class="text-sage hover:underline">info@barana.cz</a>.</p>
            </div>

            {{-- Inline cookie consent --}}
            <div
                x-data="{
                    status: null,
                    expired: true,
                    init() {
                        const raw = localStorage.getItem('barana_cookie_consent');
                        if (!raw) return;
                        const parts = raw.includes(':') ? raw.split(':') : [raw, '0'];
                        const [s, ts] = parts;
                        const age = Date.now() - Number(ts);
                        const limit = s === 'accepted' ? 365*86400*1000 : 14*86400*1000;
                        this.status = s;
                        this.expired = age > limit;
                    },
                    accept() {
                        localStorage.setItem('barana_cookie_consent', 'accepted:' + Date.now());
                        this.status = 'accepted';
                        this.expired = false;
                        window.dispatchEvent(new CustomEvent('barana:cookieAccepted'));
                    },
                    decline() {
                        localStorage.setItem('barana_cookie_consent', 'declined:' + Date.now());
                        this.status = 'declined';
                        this.expired = false;
                    }
                }"
                class="rounded-xl border border-gray-200 bg-gray-50 p-6"
            >
                <p class="font-semibold text-anthracite mb-3">Vaše nastavení cookies</p>

                <template x-if="status === 'accepted' && !expired">
                    <p class="text-sm text-body">
                        Analytické cookies jsou povoleny.
                        <button @click="decline()" class="text-sage hover:underline ml-2">Odvolat souhlas</button>
                    </p>
                </template>

                <template x-if="status === 'declined' && !expired">
                    <p class="text-sm text-body">
                        Analytické cookies jsou zakázány.
                        <button @click="accept()" class="text-sage hover:underline ml-2">Povolit analytické cookies</button>
                    </p>
                </template>

                <template x-if="status === null || expired">
                    <div>
                        <p class="text-sm text-body mb-4">Zatím jste neudělili souhlas s analytickými cookies. Pomůžete nám zlepšovat web?</p>
                        <div class="flex gap-3 flex-wrap">
                            <button @click="accept()" class="cookie-modal__accept">Souhlasím s cookies</button>
                            <button @click="decline()" class="cookie-modal__decline_b">Odmítnout</button>
                        </div>
                    </div>
                </template>
            </div>

        </div>

    </div>
</section>
@endsection

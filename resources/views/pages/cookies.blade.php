@extends('layouts.app')

@section('title', 'Cookies a souhlas se zpracováním — itwebtech.cz')
@section('description', 'Informace o cookies a měřicích nástrojích, které používáme na itwebtech.cz, a jak svůj souhlas kdykoli odvolat.')

@section('hide_prefooter') true @endsection

@section('content')

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark + Fraunces italic display.
     Cookies index 08/09 v sitewide schématu. CS-only legal page → strings inline. --}}
<div class="page-hero page-hero--cookies">
    <div class="container-site">
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">COOKIES A MĚŘENÍ</span>
            <span class="page-hero__page-mark-index" aria-hidden="true">08 / 09</span>
        </p>
        <p class="page-hero__upline">Bez reklamních cookies. Bez prodeje dat.</p>
        <h1 class="page-hero__heading">
            Co měřím a <em>proč</em> to dělám.
        </h1>
        <p class="page-hero__subline">Anonymní statistika návštěvnosti — abych věděl, co funguje. Žádné cílení reklam, žádní prostředníci.</p>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">

        {{-- TL;DR card — OND-130 iter 8: plain-language summary nad detailem. --}}
        <aside class="legal-tldr" data-reveal>
            <p class="legal-tldr__eyebrow">V kostce</p>
            <ul class="legal-tldr__list">
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>Měřím jen anonymní návštěvnost (GA4) a anonymizované heatmapy (Clarity).</span>
                </li>
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>Žádné reklamní cookies ani cílení reklam — <code>ad_storage</code> je trvale <code>denied</code>.</span>
                </li>
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>Svůj souhlas můžete kdykoli odvolat tlačítkem dole nebo smazáním cookies v prohlížeči.</span>
                </li>
            </ul>
        </aside>

        <article class="prose-content">

            <p>Tato stránka shrnuje, jaké cookies a měřicí nástroje na webu <strong>itwebtech.cz</strong> používáme, k čemu slouží a jak souhlas s jejich používáním kdykoli odvoláte.</p>

            <h2>Co používáme</h2>
            <ul>
                <li><strong>Google Analytics 4 (GA4)</strong> — anonymní statistika návštěvnosti, ze které vidíme, kolik lidí web navštíví, odkud přicházejí a které sekce zaujmou.</li>
                <li><strong>Microsoft Clarity</strong> — heatmapy a nahrávky relací (s anonymizovaným obsahem), které pomáhají odhalit, kde mají návštěvníci problém najít to, co hledají.</li>
            </ul>
            <p>Žádné reklamní cookies nebo cílení reklam nepoužíváme. V GA4 zůstávají reklamní souhlasy (<code>ad_storage</code>, <code>ad_user_data</code>, <code>ad_personalization</code>) trvale na hodnotě <code>denied</code>.</p>

            <h2>Co měříme</h2>
            <ul>
                <li>Návštěvnost a zdroje (odkud lidé přicházejí, kolik stránek shlédnou, jak dlouho zůstanou).</li>
                <li>Interakci s primárními CTA — klik na „Získat cenovou nabídku“, telefonní číslo, otevření formuláře, odeslání poptávky.</li>
                <li>Nahrávky relací (Clarity) — anonymizovaný video záznam pohybu kurzoru a kliků, aby šlo odhalit místa, kde návštěvník bloudí.</li>
            </ul>

            <h2>Doba uchování</h2>
            <ul>
                <li>Souhlas „Přijmout vše“ — uložen v prohlížeči (<code>localStorage</code>) na <strong>365 dnů</strong>, poté budete znovu dotázáni.</li>
                <li>„Odmítnout“ — uložen na <strong>180 dnů</strong>; po tuto dobu se vás banner znovu nezeptá a žádné měřicí cookies se nenastavují.</li>
                <li>Cookies GA4 (<code>_ga</code>, <code>_ga_*</code>) — standardně 2 roky (pouze pokud udělíte souhlas).</li>
                <li>Cookies Microsoft Clarity (<code>_clck</code>, <code>_clsk</code>, <code>MUID</code>, <code>CLID</code>) — dle nastavení Microsoftu (typicky 1 rok).</li>
            </ul>

            <h2>Jak souhlas odvolat</h2>
            <p>Pokud chcete svůj souhlas odvolat, klikněte na následující tlačítko. Smaže se uložený souhlas i případné GA / Clarity cookies a po obnovení stránky se znovu zobrazí banner.</p>
            <p>
                <button type="button"
                        class="btn btn-primary"
                        onclick="if (window.ItwebtechAnalytics) { window.ItwebtechAnalytics.revokeConsent(); location.reload(); }">
                    Odvolat souhlas a smazat cookies
                </button>
            </p>
            <p>Alternativně můžete cookies pro doménu <code>itwebtech.cz</code> smazat ručně v nastavení vašeho prohlížeče.</p>

            <h2>Správce dat</h2>
            <p>
                Ondřej Kriška – itwebtech<br>
                E-mail: <a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
            </p>
            <p>Pro detailnější informace o zpracování osobních údajů viz <a href="{{ lroute('privacy') }}">zásady ochrany osobních údajů</a>.</p>

        </article>
    </div>
</section>

@endsection

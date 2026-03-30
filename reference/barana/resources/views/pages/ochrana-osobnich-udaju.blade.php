@extends('layouts.app')

@section('title', 'Ochrana osobních údajů — BARANA s.r.o.')
@section('description', 'Zásady ochrany osobních údajů dle GDPR — BARANA s.r.o., IČO: 24568341.')
@section('hide_prefooter') true @endsection

@section('content')
<section class="section-wrapper">
    <div class="container-site max-w-3xl">

        <div class="mb-10" data-reveal>
            <span class="section-eyebrow">Právní informace</span>
            <h1 class="section-title">Ochrana osobních údajů</h1>
            <p class="section-sub">Poslední aktualizace: {{ now()->format('j. n. Y') }}</p>
        </div>

        <div class="space-y-8 text-body leading-relaxed" data-reveal>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Správce osobních údajů</h2>
                <p>BARANA s.r.o., IČO: 24568341, se sídlem Hlavní 49, 69181 Březí (dále jen „správce").</p>
                <p class="mt-2">Kontakt: <a href="mailto:info@barana.cz" class="text-sage hover:underline">info@barana.cz</a> | <a href="tel:+420123456789" class="text-sage hover:underline">+420 123 456 789</a></p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Jaké údaje zpracováváme</h2>
                <ul class="space-y-1.5 text-sm list-disc list-inside">
                    <li>Jméno a příjmení</li>
                    <li>E-mailová adresa</li>
                    <li>Telefonní číslo</li>
                    <li>Obsah zprávy zaslaný prostřednictvím kontaktního formuláře</li>
                    <li>Přílohy dobrovolně přiložené k poptávce</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Účel zpracování</h2>
                <p>Osobní údaje zpracováváme výhradně za účelem odpovědi na vaši poptávku nebo dotaz (čl. 6 odst. 1 písm. b) GDPR — plnění smlouvy nebo provedení opatření před uzavřením smlouvy).</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Doba uchovávání</h2>
                <p>Osobní údaje uchováváme po dobu nezbytně nutnou k vyřízení vaší poptávky, nejdéle však 3 roky od posledního kontaktu. Po uplynutí této doby jsou bezpečně vymazány.</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Vaše práva</h2>
                <ul class="space-y-1.5 text-sm list-disc list-inside">
                    <li>Právo na přístup k osobním údajům</li>
                    <li>Právo na opravu nepřesných údajů</li>
                    <li>Právo na výmaz (právo být zapomenut)</li>
                    <li>Právo na omezení zpracování</li>
                    <li>Právo vznést námitku</li>
                    <li>Právo podat stížnost u Úřadu pro ochranu osobních údajů (www.uoou.cz)</li>
                </ul>
                <p class="mt-3">Pro uplatnění vašich práv nás kontaktujte na <a href="mailto:info@barana.cz" class="text-sage hover:underline">info@barana.cz</a>.</p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-heading mb-3">Předávání třetím stranám</h2>
                <p>Osobní údaje nepředáváme žádným třetím stranám mimo EU, s výjimkou služby Google Analytics (anonymní statistiky). Vaše kontaktní údaje nikdy neprodáváme ani nepronajímáme.</p>
            </div>

        </div>

    </div>
</section>
@endsection

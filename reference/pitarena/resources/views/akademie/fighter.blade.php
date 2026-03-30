@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('fighter') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike akademie FIGHTER.">
  <meta property="og:description" content="Program Fighter je určen pro ty, kteří mají vážný zájem o tento sport a chtějí se systematicky zlepšovat.">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_akademie_pitarena_new_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('fighter') }}">
  <meta name="twitter:title" content="Pitbike akademie FIGHTER.">
  <meta name="twitter:description" content="Program Fighter je určen pro ty, kteří mají vážný zájem o tento sport a chtějí se systematicky zlepšovat.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitbike_akademie_pitarena_new_1200x630.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ route('home') }}"><span itemprop="name">Úvod</span></a>
        <meta itemprop="position" content="1"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ route('programy') }}"><span itemprop="name">Programy</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ route('akademie') }}"><span itemprop="name">Akademie</span></a>
        <meta itemprop="position" content="3"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Fighter</span>
        <meta itemprop="position" content="4"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Facebook SDK --}}
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<section class="section bg-mx-black">
  <div class="page-container max-w-2xl">

    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <h1 class="text-3xl lg:text-4xl font-bold text-mx-white mb-6">Fighter</h1>
    </div>

    <img src="{{ url('images/akademie/fighter_443_334.jpg') }}" alt="program pitbike akademie Fighter"
         width="443" height="334" loading="lazy" class="w-full rounded-lg object-cover mb-6"/>

    <h2 class="text-xl font-bold text-mx-white mt-8 mb-2">Základní popis:</h2>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      Program "Fighter" je určen pro ty, kteří mají vážný zájem o tento sport a chtějí se systematicky zlepšovat.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Věková kategorie:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">Děti od 6 let.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Tréninky:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Délka tréninku: 1 hodina a 20 minut.<br>
      Frekvence: 2x týdně.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-2">Program tréninků:</h3>
    <p class="text-gray-400 text-sm mb-2">Skládá se ze 7 tématických lekcí:</p>
    <ul class="space-y-1 text-gray-400 text-sm mb-3">
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Poloha těla</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Brždění</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Akcelerace</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Zatáčení</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Skoky</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Předjíždění</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Starty</li>
      <li class="flex gap-2"><i class="fa-solid fa-plus text-mx-orange mt-1"></i> Senzomotorika</li>
      <li class="flex gap-2"><i class="fa-solid fa-plus text-mx-orange mt-1"></i> Dynamika</li>
    </ul>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Místo konání tréninků:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Mimo hlavní sezónu v hale, během hlavní sezóny (duben až říjen) na závodní pitbike trati.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Vybavení:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Povinná výbava: Helma, chrániče hrudníku, loktů a kolen, pevné jezdecké boty a kalhoty, rukavice a brýle.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-2">Požadavky pro účast:</h3>
    <ul class="space-y-1 text-gray-400 text-sm mb-3">
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Zájemci by měli umět jezdit na kole.</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Fyzické a duševní zdraví.</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Nadšení pro motorky.</li>
    </ul>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Závody (možnost zúčastnit se):</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-1"><strong class="text-mx-white">Frekvence:</strong> 6 až 10 krát do roka během sezóny (duben - říjen).</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-1"><strong class="text-mx-white">Kategorie jezdců:</strong> Dělení dle výkonnosti motocyklu a rozměru kol motocyklu.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3"><strong class="text-mx-white">Věkový limit:</strong> Děti mohou závodit od 6 let, maximální věk není omezen.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Rodiče:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">Aktivní účast rodičů na trénincích je doporučovaná.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      K dispozici je informační článek na webových stránkách, který rodičům vysvětluje jejich úlohu v podpoře dětí v tomto sportu —
      <a href="{{ url('blog/10-rad-pro-rodice-jak-prispet-k-uspechu-deti-v-pitbike-jezdeni') }}" class="text-mx-gold hover:underline">10 rad pro rodiče, jak přispět k úspěchu dětí v pitbike ježdění</a>.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Další výhody:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-1"><strong class="text-mx-white">Kategorizace jezdců:</strong> Začátek ve skupině B s možností postoupit do skupiny A na základě výkonu.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      <strong class="text-mx-white">Výhody pro skupinu A:</strong> Reprezentace pod týmem MXH Racing.
      Možnost získání sponzorských darů. Výrazné slevy na náhradní díly.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Workshopy:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-1"><strong class="text-mx-white">Frekvence:</strong> Několikrát do roka, hlavně v zimním období.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-1">
      <strong class="text-mx-white">Obsah:</strong> Například údržba závodní motorky, příprava motorky na sezónu/závod, bezpečnost, základy první pomoci.
      Rozbory konkrétních chyb i úspěchů konkrétních jezdců v posledním období, a podobně.
    </p>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      <strong class="text-mx-white">Formát:</strong> Kombinace teorie a praxe, například možnost praktického rozebrání a sestavení části motoru. Máme připravené pracovní stoly i potřebné nářadí.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-3">Cena:</h3>
    <div class="space-y-1 mb-5">
      <div class="text-2xl font-bold text-mx-orange">2.780,- Kč <span class="text-gray-400 font-normal text-sm">/měsíčně</span></div>
      <div class="text-gray-400">6.000,- Kč <span class="text-gray-400 text-xs">/roční klubový poplatek</span></div>
    </div>

    <a class="btn-primary btn-sm inline-flex items-center gap-2"
       href="{{ url('/files/Prihlaska a Potvrzeni o lekarske prohlidce.pdf') }}"
       download="Prihlaska a Potvrzeni o lekarske prohlidce.pdf">
      <i class="fa-solid fa-file-pdf"></i> Stáhnout přihlášku
    </a>

    <div class="flex items-center gap-3 mt-8 pt-5 border-t border-mx-gray2">
      <span class="text-gray-400 text-sm">Doporučte to na FB</span>
      <div class="fb-share-button" data-href="{{ request()->url() }}" data-layout="button_count" data-size="large"></div>
    </div>

  </div>
</section>

@includeIf('sections.programCards')

@includeIf('sections.service')

@endsection

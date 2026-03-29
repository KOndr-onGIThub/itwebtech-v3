@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('mx-start') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike akademie START.">
  <meta property="og:description" content="3 měsíce během kterých se dostanete do skvělé jezdecké formy.">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_akademie_pitarena_new_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('mx-start') }}">
  <meta name="twitter:title" content="Pitbike akademie START.">
  <meta name="twitter:description" content="3 měsíce během kterých se dostanete do skvělé jezdecké formy.">
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
        <span itemprop="name">MX Start</span>
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
      <h1 class="text-3xl lg:text-4xl font-bold text-mx-white mb-6">MX Start</h1>
    </div>

    <img src="{{ url('images/akademie/mx-start_443_334.jpg') }}" alt="program pitbike akademie MX Start"
         width="443" height="334" loading="lazy" class="w-full rounded-lg object-cover mb-6"/>

    <h2 class="text-xl font-bold text-mx-white mt-8 mb-2">Základní popis:</h2>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      Pokud se zatím nechcete vázat. Za tři měsíce trvání tohoto kurzu z vás mistra světa neuděláme, ale garantujeme vám obrovský skok kupředu.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Věková kategorie:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">Děti od 6 let.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Tréninky:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Délka tréninku: 1 hodina a 20 minut.<br>
      Frekvence: 2x týdně po dobu 3 měsíců.
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
      <li class="flex gap-2"><i class="fa-solid fa-plus text-mx-orange mt-1"></i> 2 hod. bezpečnost</li>
      <li class="flex gap-2"><i class="fa-solid fa-plus text-mx-orange mt-1"></i> 2 hod. údržba moto</li>
    </ul>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Délka kurzu:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">3 měsíce bez účasti na závodech. Cílem je připravit účastníka do formy, ve které může začít závodit.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">Po ukončení kurzu má účastník možnost získání závodní licence a rozhodnout se, zda chce v tomto sportu pokračovat.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Vybavení:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">
      Povinná výbava: Helma, chrániče hrudníku, loktů a kolen, pevné jezdecké boty a kalhoty, rukavice a brýle.
    </p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      <em>Nemáte vlastní motorku nebo výbavu? Po předchozí domluvě je možné zajistit <strong class="text-mx-white">zapůjčení motorky i jezdecké výbavy</strong>. Kontaktujte nás na <a href="tel:+420704221663" class="text-mx-gold hover:underline">704 221 663</a>.</em>
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-2">Požadavky pro účast:</h3>
    <ul class="space-y-1 text-gray-400 text-sm mb-3">
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Zájemci by měli umět jezdit na kole.</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Fyzické a duševní zdraví.</li>
      <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Nadšení pro motorky.</li>
    </ul>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Závodní licence:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">Udělena na konci kurzu těm, kteří úspěšně absolvovali všechny lekce.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">Úspěšné absolvování neznamená mistrovskou úroveň, ale spíše viditelný pokrok a snahu účastníka.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Rodiče:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">Aktivní účast rodičů na trénincích je doporučovaná.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      K dispozici je informační článek na webových stránkách, který rodičům vysvětluje jejich úlohu v podpoře dětí v tomto sportu —
      <a href="{{ url('blog/10-rad-pro-rodice-jak-prispet-k-uspechu-deti-v-pitbike-jezdeni') }}" class="text-mx-gold hover:underline">10 rad pro rodiče, jak přispět k úspěchu dětí v pitbike ježdění</a>.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Další výhody:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-2">Krátká doba trvání kurzu (3 měsíce) umožňuje zájemcům zjistit, zda je tento sport pro ně to pravé, aniž by se museli zavázat na celý rok.</p>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">Nemusíte platit roční klubový poplatek.</p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-3">Cena:</h3>
    <div class="mb-5">
      <div class="text-2xl font-bold text-mx-orange">8.500,- Kč <span class="text-gray-400 font-normal text-sm">/celková cena</span></div>
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

@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'akademie/mx-solo')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('mx-solo') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Jeden trenér vs. jeden trénovaný.">
  <meta property="og:description" content="Kurz vedený certifikovanými trenéry a profesionálními závodníky v motokrosu.">
  <meta property="og:image" content="https://pitarena.cz/images/akademie/MX-SOLO_BANNER.png">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('mx-solo') }}">
  <meta name="twitter:title" content="Jeden trenér vs. jeden trénovaný.">
  <meta name="twitter:description" content="Kurz vedený certifikovanými trenéry a profesionálními závodníky v motokrosu.">
  <meta name="twitter:image" content="https://pitarena.cz/images/akademie/MX-SOLO_BANNER.png">
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
        <a itemprop="item" href="{{ route('poukazy') }}"><span itemprop="name">Poukazy</span></a>
        <meta itemprop="position" content="3"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">MX SOLO</span>
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
      <h1 class="text-3xl lg:text-4xl font-bold text-mx-white mb-6">MX SOLO</h1>
    </div>

    <img src="{{ asset('images/akademie/mx-solo-voucher-VZOR.webp') }}" alt="voucher/poukaz pitbike MX SOLO"
         width="1000" height="324" loading="lazy" class="w-full rounded-lg mb-6"/>

    <h2 class="text-xl font-bold text-mx-white mt-8 mb-2">Základní popis:</h2>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Chcete se co nejrychleji dostat do jezdecké formy? V režimu jeden na jednoho toho dosáhnete nejefektivněji.
    </p>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      Naučíme vás správné návyky pro jízdu v terénu a pomůžeme vám odbourat ty špatné.<br>
      Podmínkou je mít vlastní moto (Enduro - Pitbike - Motokros), lze ale domluvit i zapůjčení.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Věková kategorie:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Určeno pro všechny věkové kategorie — <span class="text-mx-orange font-semibold">pro děti i dospělé</span>.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Tréninky:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Délka tréninků a jejich frekvence je plně na domluvě mezi účastníkem a trenérem. Jeden poukaz = 1 hodina a 20 minut.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Zaměření:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Nabídka sedmi lekcí — poloha těla, brždění, akcelerace, zatáčení, skoky, předjíždění a starty.<br>
      Hlavní důraz je kladen na oblasti, kde má účastník potíže nebo kde se chce zlepšit.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Vybavení:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-3">
      Povinná výbava: Helma, chrániče hrudníku, loktů a kolen, pevné jezdecké boty a kalhoty, rukavice a brýle.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-1">Specifika:</h3>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      Individuální trénink, kde se trenér ve 100% času věnuje pouze jednomu jezdci, což zvyšuje efektivitu tréninku.
    </p>

    <h3 class="text-sm font-semibold text-mx-white uppercase tracking-wide mt-5 mb-3">Cena:</h3>
    <div class="mb-5">
      <div class="text-2xl font-bold text-mx-orange">950,- Kč <span class="text-gray-400 font-normal text-sm">/lekce (1h. 20 min)</span></div>
    </div>

    <div class="flex items-center gap-3 mt-8 pt-5 border-t border-mx-gray2">
      <span class="text-gray-400 text-sm">Sdílet na FB</span>
      <div class="fb-share-button" data-href="{{ request()->url() }}" data-layout="button_count" data-size="large"></div>
    </div>

  </div>
</section>

{{-- SimpleShop form --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-2xl">
    {{-- www.SimpleShop.cz form#83337 start --}}
    <div class="bg-white max-w-3xl mx-auto rounded-lg shadow-2xl px-4 py-6 sm:px-8 sm:py-8">
      <div data-SimpleShopForm="roNk"><div>Prodejní formulář je vytvořen v systému <a href="https://www.simpleshop.cz/?utm_source=simpleshop&utm_medium=form&utm_campaign=49201" target="_blank" class="text-mx-gold hover:underline">SimpleShop.cz</a>.</div></div>
    </div>
    <script>
      (function(i, s, o, g, r, a, m){
        i[r] = i[r] || function(){
        (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, "script", "https://form.simpleshop.cz/prj/js/SimpleShopService.js", "sss");
      sss("createForm", "roNk");
    </script>
    {{-- www.SimpleShop.cz form#83337 end --}}
  </div>
</section>

@includeIf('sections.programCards')

@includeIf('sections.service')

@endsection

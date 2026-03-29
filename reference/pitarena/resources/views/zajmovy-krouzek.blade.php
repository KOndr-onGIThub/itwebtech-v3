@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('zajmovy-krouzek') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Moto kroužek pro děti | PIT & GO">
  <meta property="og:description" content="Kompletní vybavení v ceně! Zájmový kroužek pro děti od 4 do 14 let.">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_krouzek_pitarena_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('zajmovy-krouzek') }}">
  <meta name="twitter:title" content="Moto kroužek pro děti | PIT & GO">
  <meta name="twitter:description" content="Kompletní vybavení v ceně! Zájmový kroužek pro děti od 4 do 14 let.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitbike_krouzek_pitarena_1200x630.jpg">
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
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Moto kroužek PIT & GO</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section
  class="parallax-hero"
  style="--hero-img: url('{{ asset('images/pitarena_pitandgo_header.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">PIT & GO<br>Zájmový kroužek</h1>
  </div>
</section>

{{-- Co je PIT & GO --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <img src="{{ asset('/images/krouzek/pitbike_krouzek_1.webp') }}" loading="lazy" width="652" height="491"
             alt="Zájmový moto kroužek pitaréna"
             class="w-full rounded-lg object-cover"/>
      </div>
      <div data-aos="fade-left">
        <div class="section-divider mb-4"></div>
        <span class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-xs font-semibold px-2 py-0.5 rounded mb-4">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
          </span>
          Nepotřebujete motorku ani vybavení!
        </span>
        <h2 class="section-title mb-4">Co je PIT & GO?</h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-4">
          Zájmový kroužek pro děti, které se chtějí naučit jezdit a pochopit svět moto-sportu.
        </p>
        <h3 class="text-mx-white font-semibold mb-3">Pro děti od 4 do 14 let</h3>
        <ul class="space-y-2 text-gray-400 text-sm">
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Základy bezpečné jízdy na motorce</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Základy první pomoci</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Znalosti o mechanice a údržbě</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Správné návyky jízdy v terénu</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Maximálně 4–6 dětí ve skupině</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Kde a jak --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
      <div data-aos="fade-right">
        <div class="section-divider mb-4"></div>
        <h2 class="section-title mb-6">Kde a jak kroužek probíhá?</h2>
        <div class="space-y-4 text-gray-400 text-sm">
          <p>
            <i class="fa-solid fa-map-marker-alt text-mx-orange mr-2"></i>
            <strong class="text-mx-white">Místo:</strong> Areál MX/ENDURO PRAVICE
          </p>
          <p>
            <i class="fa-solid fa-calendar text-mx-orange mr-2"></i>
            <strong class="text-mx-white">Kdy:</strong> od dubna do září <small class="text-gray-400">(mimo letní prázdniny)</small>
          </p>
          <p>
            <i class="fa-regular fa-hourglass-half text-mx-orange mr-2"></i>
            <strong class="text-mx-white">Délka:</strong> 1x týdně, 1 hodina a 20 minut + cca 40 min příprava.<br>
            Den v týdnu dle individuální domluvy.
          </p>
        </div>
      </div>
      <div data-aos="fade-left">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d43351.615244881825!2d16.34039438688065!3d48.84981449487359!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4712b58541ef5cd1%3A0xafc9f2294f6d4f41!2sMX%2FENDURO%20PRAVICE!5e0!3m2!1scs!2sus!4v1728582407827!5m2!1scs!2sus"
          width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          class="rounded-lg w-full"></iframe>
      </div>
    </div>
  </div>
</section>

{{-- Cena --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <img src="{{ asset('/images/krouzek/pitbike_krouzek_2.webp') }}" loading="lazy" width="652" height="491"
             alt="Zájmový moto kroužek pitaréna"
             class="w-full rounded-lg object-cover"/>
      </div>
      <div data-aos="fade-left">
        <div class="section-divider mb-4"></div>
        <h2 class="section-title mb-6">Cena kroužku</h2>
        <div class="text-3xl font-bold text-mx-orange mb-1">2 790 Kč</div>
        <div class="text-gray-400 text-sm mb-6">/ měsíčně</div>
        <p class="text-mx-white font-semibold mb-2">V ceně jsou:</p>
        <ul class="space-y-2 text-gray-400 text-sm mb-6">
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Zapůjčení motorky</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Kompletní moto výbava</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Provozní náklady (benzín, mytí, údržba)</li>
        </ul>
        <p class="text-gray-400 text-sm">
          <i class="fa-solid fa-hand-point-right text-mx-orange mr-2"></i>
          Cenu uhradíte na místě.
        </p>
      </div>
    </div>
  </div>
</section>

{{-- Jak se přihlásit --}}
<section class="section bg-mx-black text-center">
  <div class="page-container max-w-2xl">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-4">Jak se přihlásit?</h2>
    </div>
    <p class="text-gray-400 text-sm mb-2" data-aos="fade-up" data-aos-delay="100">
      Napište nám nebo zavolejte.<br>
      Je potřeba domluvit se na termínu, kdy si přijedete <strong class="text-mx-white">vyzkoušet motorku a výbavu</strong>.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6" data-aos="zoom-in" data-aos-delay="250">
      <a class="btn-primary" href="mailto:trener@pitarena.cz">
        <i class="fa-solid fa-envelope"></i> trener@pitarena.cz
      </a>
      <a class="btn-outline" href="tel:+420704221663">
        <i class="fa-solid fa-phone"></i> 704 221 663
      </a>
    </div>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

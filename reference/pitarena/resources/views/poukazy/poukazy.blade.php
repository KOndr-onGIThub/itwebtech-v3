@extends('layout')
<?php
    $title=null;
    $description=null;
    foreach ($sitemaps as $sitemap){
        if ($sitemap['slug'] == request()->path() ){
            $title = $sitemap['title'];
            $description = $sitemap['description'];
        }
    }
?>

@section('title', $title)
@section('meta_description', $description)

@section('og')
  <meta property="og:url" content="{{ route('poukazy') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_poukazy_pitarena_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('poukazy') }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitbike_poukazy_pitarena_1200x630.jpg">
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
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Poukazy</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section
  class="parallax-hero"
  style="--hero-img: url('{{ asset('images/pitarena_poukazy_header.webp') }}');"
>
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white">Poukazy pro jízdu nejen na pitbike</h1>
    </div>
  </div>
</section>

{{-- Poukazy --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title mb-4">Vyberte si z nabízených variant poukazů</h2>
      <p class="text-gray-400 text-sm max-w-2xl mx-auto">
        Z těchto 3 poukazů si vybere naprostý začátečník i zkušený borec.
        Poukazy jsou vhodné jako dárek pro dítě i další členy rodiny.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- MX SOLO --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500">
        <a href="{{ route('mx-solo') }}" class="block overflow-hidden">
          <img src="{{ asset('images/akademie/mx-solo_.jpg') }}" loading="lazy" alt="MX SOLO" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ route('mx-solo') }}" class="hover:text-mx-gold transition-colors">MX SOLO</a>
          </h5>
          <h6 class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-3">JEDEN TRENÉR / JEDEN TRÉNOVANÝ</h6>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Maximální efektivita tréninku.
            Trenér vždy přizpůsobí tréninkový plán právě a jenom vašim potřebám.
            Domluvte si individuální termíny i to jak dlouho bude lekce trvat, přesně podle vašich možností.
          </p>
          <div class="text-mx-orange font-bold mb-4">950,- Kč <span class="text-gray-400 font-normal text-xs">/lekce (1h. 20 min)</span></div>
          <a class="btn-primary btn-sm" href="{{ route('mx-solo') }}">Detaily programu</a>
        </div>
      </div>

      {{-- MX GO --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500" data-aos-delay="150">
        <a href="{{ route('mx-go') }}" class="block overflow-hidden relative">
          <div class="absolute top-3 left-3 z-10">
            <span class="badge-orange">Nejprodávanější</span>
          </div>
          <img src="{{ asset('images/akademie/mx-go_.jpg') }}" loading="lazy" alt="MX GO" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ route('mx-go') }}" class="hover:text-mx-gold transition-colors">MX GO</a>
          </h5>
          <h6 class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-3">ZÁKLADNÍ KURZ OVLÁDÁNÍ MOTOCYKLU V TERÉNU</h6>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Dvě a půl hodiny s trenérem (nebo 5 h).<br>
            Správné držení těla, ovládání motocyklu v terénu a další.
          </p>
          <div class="space-y-1 mb-4 text-sm">
            <div class="text-mx-orange font-bold">ZDARMA <span class="text-gray-400 font-normal text-xs">/ k nové moto od nás</span></div>
            <div class="text-gray-400">1.650,- Kč <span class="text-gray-400 text-xs">/ s vlastní moto</span></div>
            <div class="text-gray-400">2.500,- Kč <span class="text-gray-400 text-xs">/ včetně zapůjčení moto</span></div>
            <div class="text-gray-400">4.000,- Kč <span class="text-gray-400 text-xs">/ dvojitá porce včetně zapůjčení moto</span></div>
          </div>
          <a class="btn-primary btn-sm" href="{{ route('mx-go') }}">Detaily programu</a>
        </div>
      </div>

      {{-- MX HOBBY MATCH --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500" data-aos-delay="300">
        <a href="{{ route('mx-hobby') }}" class="block overflow-hidden">
          <img src="{{ asset('images/akademie/mx-hobby_.jpg') }}" loading="lazy" alt="MX HOBBY" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ route('mx-hobby') }}" class="hover:text-mx-gold transition-colors">MX HOBBY MATCH</a>
          </h5>
          <h6 class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-3">VLASTNÍ ZÁVOD</h6>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Nejste žádný začátečník, ale na plný závodní zápřah nemáte čas?
            Domluvte se s partou alespoň 10 kámošů a uspořádejte si vlastní závodní den.
          </p>
          <div class="text-mx-orange font-bold mb-4">450,- Kč <span class="text-gray-400 font-normal text-xs">/odpoledne</span></div>
          <a class="btn-primary btn-sm" href="{{ route('mx-hobby') }}">Detaily programu</a>
        </div>
      </div>

    </div>

    {{-- Download guide --}}
    <div class="text-center mt-8" data-aos="flip-right" data-aos-duration="600" data-aos-delay="200">
      <a class="btn-outline" href="{{ url('/files/jak_objednat_Akademie_202602.pdf') }}" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Návod jak objednat
      </a>
    </div>

  </div>
</section>

{{-- CTA contact --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-2xl text-center">
    <blockquote class="border-l-4 border-mx-orange pl-6 text-left mb-8">
      <p class="text-gray-300 text-lg italic leading-relaxed">
        Nevybrali jste si nebo si nejste jistí?<br>
        Dejte nám vědět.
      </p>
    </blockquote>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a class="btn-outline-white" href="mailto:trener@pitarena.cz">
        <i class="fa-solid fa-envelope"></i> trener@pitarena.cz
      </a>
      <a class="btn-primary" href="tel:+420704221663">
        <i class="fa-solid fa-phone"></i> 704 221 663
      </a>
    </div>
  </div>
</section>

@includeIf('sections.service')

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

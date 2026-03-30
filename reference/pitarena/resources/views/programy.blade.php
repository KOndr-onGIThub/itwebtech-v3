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
  <meta property="og:url" content="{{ route('programy') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_programy_pitarena_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('programy') }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitbike_programy_pitarena_1200x630.jpg">
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
        <span itemprop="name">Programy</span>
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
  style="--hero-img: url('{{ asset('images/akademie/programy_header.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">Tréninky a zážitky na motorce</h1>
  </div>
</section>

{{-- Training programs --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-2">Tréninkové i zážitkové programy pitbike motokrosu</h2>
      <p class="text-gray-400">Vyberte si ten, který vám nejlépe sedí.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- MX soustředění --}}
      <a href="{{ route('mxsoustredeni') }}"
         class="group card-dark p-6 flex gap-4 items-center hover:border-mx-orange transition-colors col-span-1 lg:col-span-2"
         data-aos="fade-up" data-aos-delay="100">
        <div class="text-mx-orange text-3xl flex-shrink-0">
          <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="flex-1">
          <span class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-xs font-semibold px-2 py-0.5 rounded mb-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
            </span>
            NOVINKA!
          </span>
          <h3 class="text-mx-white font-semibold text-lg mb-1">MX soustředění</h3>
          <p class="text-gray-400 text-xs mb-1">pro děti i dospělé</p>
          <p class="text-gray-400 text-sm">Užijte si 3 dny v rytmu motokrosového ježdění.</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all flex-shrink-0"></i>
      </a>

      {{-- Akademie --}}
      <a href="{{ route('akademie') }}"
         class="group card-dark p-6 flex gap-4 items-center hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="150">
        <div class="text-mx-orange text-3xl flex-shrink-0">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-mx-white font-semibold text-lg mb-1">Akademie</h3>
          <p class="text-gray-400 text-xs mb-1">pro děti</p>
          <p class="text-gray-400 text-sm">Dlouhodobější programy s cílem systematického zlepšování.</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all flex-shrink-0"></i>
      </a>

      {{-- Poukazy --}}
      <a href="{{ route('poukazy') }}"
         class="group card-dark p-6 flex gap-4 items-center hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="200">
        <div class="text-mx-orange text-3xl flex-shrink-0">
          <i class="fa-solid fa-ticket"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-mx-white font-semibold text-lg mb-1">Poukazy</h3>
          <p class="text-gray-400 text-xs mb-1">pro děti i dospělé</p>
          <p class="text-gray-400 text-sm">Zakupte si online poukazy na jednorázové výcvikové a zážitkové programy.</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all flex-shrink-0"></i>
      </a>

      {{-- Moto kroužek --}}
      <a href="{{ route('zajmovy-krouzek') }}"
         class="group card-dark p-6 flex gap-4 items-center hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="250">
        <div class="text-mx-orange text-3xl flex-shrink-0">
          <i class="fa-brands fa-meetup"></i>
        </div>
        <div class="flex-1">
          <span class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-xs font-semibold px-2 py-0.5 rounded mb-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
            </span>
            Nepotřebujete motorku!
          </span>
          <h3 class="text-mx-white font-semibold text-lg mb-1">Moto kroužek</h3>
          <p class="text-gray-400 text-xs mb-1">pro děti</p>
          <p class="text-gray-400 text-sm">Zájmový kroužek od dubna do září (mimo prázdniny).</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all flex-shrink-0"></i>
      </a>

      {{-- Letní Kemp --}}
      <a href="{{ route('kemp') }}"
         class="group card-dark p-6 flex gap-4 items-center hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="300">
        <div class="text-mx-orange text-3xl flex-shrink-0">
          <i class="fa-brands fa-free-code-camp"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-mx-white font-semibold text-lg mb-1">Letní Kemp</h3>
          <p class="text-gray-400 text-xs mb-1">pro děti</p>
          <p class="text-gray-400 text-sm">Zažijte jeden prázdninový týden v roli opravdového závodního jezdce.</p>
        </div>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all flex-shrink-0"></i>
      </a>

    </div>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

{{-- Moto sekce --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-2">Nové – Zánovní – Půjčené</h2>
      <p class="text-gray-400">Čemu dáváš přednost?</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <a href="{{ route('prodej-pitbike') }}"
         class="group card-dark p-6 text-center flex flex-col items-center gap-3 hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="150">
        <i class="fa-solid fa-motorcycle text-mx-orange text-4xl"></i>
        <h3 class="text-mx-white font-semibold">Prodej nových pitbike</h3>
        <p class="text-gray-400 text-xs">pro děti i dospělé</p>
        <p class="text-gray-400 text-sm">YCF PITBIKE Z PRVNÍ RUKY</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto"></i>
      </a>

      <a href="{{ route('bazar') }}"
         class="group card-dark p-6 text-center flex flex-col items-center gap-3 hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="250">
        <i class="fa-solid fa-gauge-high text-mx-orange text-4xl"></i>
        <h3 class="text-mx-white font-semibold">Moto bazar</h3>
        <p class="text-gray-400 text-xs">pro děti i dospělé</p>
        <p class="text-gray-400 text-sm">POUZE PROVĚŘENÉ STROJE</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto"></i>
      </a>

      <a href="{{ route('pitbike-pujcovna') }}"
         class="group card-dark p-6 text-center flex flex-col items-center gap-3 hover:border-mx-orange transition-colors"
         data-aos="fade-up" data-aos-delay="350">
        <i class="fa-solid fa-arrows-rotate text-mx-orange text-4xl"></i>
        <h3 class="text-mx-white font-semibold">Pitbike Půjčovna</h3>
        <p class="text-gray-400 text-xs">pro děti i dospělé</p>
        <p class="text-gray-400 text-sm">JEDNODUŠEJI A LEVNĚJI TO UŽ NEJDE</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto"></i>
      </a>

    </div>
  </div>
</section>

@includeIf('sections.service')

@endsection

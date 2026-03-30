@extends('layout')
<?php
    $title=null;
    foreach ($sitemaps as $sitemap){
        if ($sitemap['slug'] == request()->path() ){
            $title = $sitemap['title'];
            $description = $sitemap['description'];
        }
    }
    if (!$title){
        $title = 'Kupte nový pitbike přímo na závodní dráze';
        $description = 'U nás máte jedinečnou možnost vyzkoušet si některé motorky přímo na motokrosové trati. Zastavte se u nás';
    }
?>

@section('title', $title )
@section('meta_description', $description )

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_moto_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitbike_moto_1200x630_2023.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Moto</li>
    </ol>
  </div>
</nav>
@endsection


@section('content')

  {{-- Parallax hero --}}
  <section class="parallax-hero" style="--hero-img: url('{{ asset('images/moto/moto_header.webp') }}');">
    <div class="parallax-hero-content">
      <div data-aos="fade-up">
        <div class="section-divider section-divider-center"></div>
        <h1 class="balanced-text">Moto pro celou rodinu</h1>
      </div>
    </div>
  </section>


  {{-- 3 main cards --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center">Nové - Zánovní - Půjčené</h2>
        <p class="text-gray-400 mb-8">Čemu dáváš přednost?</p>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <a href="{{ route('prodej-pitbike') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-motorcycle text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Prodej nových pitbike</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti i dospělé</i>
          <p class="text-gray-300 text-sm">YCF PITBIKE Z PRVNÍ RUKY</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

        <a href="{{ route('bazar') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-tag text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Moto bazar</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti i dospělé</i>
          <p class="text-gray-300 text-sm">POUZE PROVĚŘENÉ STROJE</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

        <a href="{{ route('pitbike-pujcovna') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-rotate text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Pitbike Půjčovna</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti i dospělé</i>
          <p class="text-gray-300 text-sm">JEDNODUŠEJI A LEVNĚJI TO UŽ NEJDE</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

      </div>
    </div>
  </section>


  @includeIf('sections.whyBuyHere')


  {{-- Programy sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Tréninkové i zážitkové programy</h2>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <a href="{{ route('akademie') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-chart-line text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Akademie</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti</i>
          <p class="text-gray-300 text-sm">Dlouhodobější programy s cílem systematického zlepšování.</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

        <a href="{{ route('poukazy') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-ticket text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Poukazy</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti i dospělé</i>
          <p class="text-gray-300 text-sm">Zakupte si online poukazy na jednorázové výcvikové a zážitkové programy.</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

        <a href="{{ route('zajmovy-krouzek') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-children text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Moto kroužek</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti</i>
          <span class="inline-flex items-center gap-1.5 bg-white/5 text-gray-300 text-xs font-semibold px-2 py-0.5 rounded mb-3">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
            </span>
            Nepotřebujete motorku!
          </span>
          <p class="text-gray-300 text-sm">Zájmový kroužek od dubna do září (mimo prázdniny).</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

        <a href="{{ route('kemp') }}" class="flex flex-col items-center text-center p-6 rounded-xl bg-mx-gray border border-mx-gray2 hover:border-mx-orange transition-all group">
          <i class="fa-solid fa-campground text-4xl text-mx-orange mb-4 group-hover:scale-110 transition-transform"></i>
          <h3 class="text-xl font-semibold text-mx-white mt-2 mb-1">Letní Kemp</h3>
          <i class="text-gray-400 text-sm mb-3">pro děti</i>
          <p class="text-gray-300 text-sm">Zažijte jeden prázdninový týden v roli opravdového závodního jezdce.</p>
          <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
        </a>

      </div>
    </div>
  </section>

  {{-- Quote / CTA sekce --}}
  <section class="section bg-mx-black">
    <div class="page-container">
      <div class="border-l-4 border-mx-orange pl-6 max-w-2xl mx-auto">
        <p class="text-lg text-mx-white mb-4">
          Pokud potřebujete s výběrem poradit nebo si chcete stroj vyzkoušet, zastavte se za námi.
        </p>
        <a class="btn-primary btn-sm" href="tel:+420704221663">
          <i class="fa-solid fa-phone mr-2"></i> 704 221 663
        </a>
      </div>
    </div>
  </section>

  @includeIf('sections.distanceToUs')

  @includeIf('sections.service')

@endsection

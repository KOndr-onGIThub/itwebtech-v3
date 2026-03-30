@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('akademie') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike akademie vás dostane na vrchol.">
  <meta property="og:description" content="Vyberte si program pitbike akademie, který vám nejlépe vyhovuje. Dostanete se do jezdecké formy pod vedením profesionálních trenérů.">
  <meta property="og:image" content="https://pitarena.cz/images/pitbike_akademie_pitarena_new_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('akademie') }}">
  <meta name="twitter:title" content="Pitbike akademie vás dostane na vrchol.">
  <meta name="twitter:description" content="Vyberte si program pitbike akademie, který vám nejlépe vyhovuje. Dostanete se do jezdecké formy pod vedením profesionálních trenérů.">
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
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Akademie</span>
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
  style="--hero-img: url('{{ asset('images/akademie/pitarena_akademie_header_2.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">PITBIKE<br>AKADEMIE</h1>
  </div>
</section>

{{-- Intro --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-4">Tréninkové a vzdělávací programy</h2>
      <p class="text-gray-400 text-sm leading-relaxed max-w-3xl mx-auto">
        Programy, které jsou ideální pro ty, kteří chtějí zlepšit své dovednosti v jízdě na motocyklu.
        Každý program je pečlivě strukturován tak, aby zahrnoval jak teoretické, tak praktické aspekty jízdy.
        Účastníci se mohou těšit na komplexní přístup k učení, který zahrnuje vše od základních principů až po pokročilé techniky.
        Tato nabídka je ideální pro všechny nadšence do motocyklů, kteří chtějí své dovednosti posunout na novou úroveň.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Fighter --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="600">
        <a href="{{ route('fighter') }}" class="block overflow-hidden">
          <img src="{{ asset('images/akademie/fighter.jpg') }}" loading="lazy" alt="Fighter akademie" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-2">
            <a href="{{ route('fighter') }}" class="hover:text-mx-gold transition-colors">Fighter</a>
          </h5>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Pro ty, kteří to myslí s ježděním opravdu vážně.
            Celoroční tréninky 2x týdně – účast na závodech – workshopy.
            Povedeme vás po cestě na stupně vítězů.
          </p>
          <div class="mb-4 space-y-1">
            <div class="text-mx-orange font-bold">2.780,- Kč <span class="text-gray-400 font-normal text-xs">/měsíčně</span></div>
            <div class="text-gray-400 text-sm">6.000,- Kč <span class="text-gray-400 text-xs">/roční klubový poplatek</span></div>
          </div>
          <a class="btn-primary btn-sm" href="{{ route('fighter') }}">Detaily programu</a>
        </div>
      </div>

      {{-- MX Start --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="600" data-aos-delay="150">
        <a href="{{ route('mx-start') }}" class="block overflow-hidden">
          <img src="{{ asset('images/akademie/mx-start.jpg') }}" loading="lazy" alt="MX Start akademie" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-2">
            <a href="{{ route('mx-start') }}" class="hover:text-mx-gold transition-colors">MX Start</a>
          </h5>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Tří měsíční intenzivní kurz. 2x týdně trénink na závodní dráze.
            Vhodné zejména pro ty, kteří ještě nevědí zda je to bude dlouhodobě bavit.
            Na konci kurzu získáte závodní licenci.
          </p>
          <div class="mb-4">
            <div class="text-mx-orange font-bold">8.500,- Kč <span class="text-gray-400 font-normal text-xs">/celková cena</span></div>
          </div>
          <a class="btn-primary btn-sm" href="{{ route('mx-start') }}">Detaily programu</a>
        </div>
      </div>

      {{-- MX Mini --}}
      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="600" data-aos-delay="300">
        <a href="{{ route('mx-mini') }}" class="block overflow-hidden">
          <img src="{{ asset('images/akademie/mx-mini.jpg') }}" loading="lazy" alt="MX Mini akademie" width="418" height="315"
               class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500"/>
        </a>
        <div class="p-5">
          <h5 class="text-mx-white font-semibold text-lg mb-2">
            <a href="{{ route('mx-mini') }}" class="hover:text-mx-gold transition-colors">MX Mini</a>
          </h5>
          <p class="text-gray-400 text-sm leading-relaxed mb-4">
            Program pro ty nejmenší (do 7 let), kteří do světa motorek teprve nakukují.<br>
            Celoroční program s tréninky 1x týdně. Žádné extrémy, děti to musí hlavně bavit.
          </p>
          <div class="mb-4 space-y-1">
            <div class="text-mx-orange font-bold">1.750,- Kč <span class="text-gray-400 font-normal text-xs">/měsíčně</span></div>
            <div class="text-gray-400 text-sm">3.000,- Kč <span class="text-gray-400 text-xs">/roční klubový poplatek</span></div>
          </div>
          <a class="btn-primary btn-sm" href="{{ route('mx-mini') }}">Detaily programu</a>
        </div>
      </div>

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

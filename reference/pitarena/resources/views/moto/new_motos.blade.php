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
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li><a href="{{ route('moto') }}">Moto</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Nové</li>
    </ol>
  </div>
</nav>
@endsection


@section('content')

<section class="section bg-mx-black">
  <div class="page-container">

    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h1 class="section-title-center text-center">Prodej nových pitbike</h1>
    </div>

    {{-- Promo nabídka --}}
    <div class="mt-6 mb-10 text-center">
      <p class="text-gray-300 text-base leading-relaxed">
        <strong class="text-mx-white">Nyní ZDARMA ke každé motorce:</strong><br>
        2,5 hodinový program <a href="{{ url('akademie/mx-go') }}" class="text-mx-gold hover:underline">MX GO</a><br>
        +<br>
        10 vstupů na trať
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

      {{-- 50A --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">3–6 LET</span>
          <a href="{{ url('prodej-pitbike/50A') }}">
            <img src="{{ url('images/moto_new/50A_2025.webp') }}" alt="PITBIKE YCF 50A" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/50A') }}" class="hover:text-mx-gold">YCF 50A</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">33.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 50 ccm 4 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>automatická převodovka</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>elektrický startér</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/50A') }}">Detaily</a>
        </div>
      </div>

      {{-- START F88SE --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">6–8 LET</span>
          <a href="{{ url('prodej-pitbike/START_F88SE') }}">
            <img src="{{ url('images/moto_new/START_F88SE_2025.webp') }}" alt="PITBIKE YCF START F88SE" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/START_F88SE') }}" class="hover:text-mx-gold">YCF START F88SE</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">42.990,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 88 ccm 4,5 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>poloautomatická převodovka</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>elektrický startér</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/START_F88SE') }}">Detaily</a>
        </div>
      </div>

      {{-- LITE F88S --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">6–8 LET</span>
          <a href="{{ url('prodej-pitbike/LITE_F88S') }}">
            <img src="{{ url('images/moto_new/LITE_F88S_2025.webp') }}" alt="PITBIKE YCF LITE F88S" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/LITE_F88S') }}" class="hover:text-mx-gold">YCF LITE F88S</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">31.490,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 88 ccm 5 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>poloautomatická převodovka</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/LITE_F88S') }}">Detaily</a>
        </div>
      </div>

      {{-- START F125SE --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">8–10 LET</span>
          <a href="{{ url('prodej-pitbike/START_F125SE') }}">
            <img src="{{ url('images/moto_new/START_F125SE.webp') }}" loading="lazy" alt="PITBIKE YCF START F125SE" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/START_F125SE') }}" class="hover:text-mx-gold">YCF START F125SE</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">43.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 123,7 ccm 6 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>poloautomatická převodovka</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>elektrický startér + nožní startovací páka</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/START_F125SE') }}">Detaily</a>
        </div>
      </div>

      {{-- PILOT F125 --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">10–14 LET</span>
          <a href="{{ url('prodej-pitbike/PILOT_F125') }}">
            <img src="{{ url('images/moto_new/PILOT_F125.webp') }}" loading="lazy" alt="PITBIKE YCF PILOT F125" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/PILOT_F125') }}" class="hover:text-mx-gold">YCF PILOT F125</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">46.490,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 125 ccm 6,5 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>nožní startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/PILOT_F125') }}">Detaily</a>
        </div>
      </div>

      {{-- PILOT F150E --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">10–14 LET</span>
          <a href="{{ url('prodej-pitbike/PILOT_F150E') }}">
            <img src="{{ url('images/moto_new/PILOT_F150E.webp') }}" loading="lazy" alt="PITBIKE YCF PILOT F150E" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/PILOT_F150E') }}" class="hover:text-mx-gold">YCF PILOT F150E</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">52.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 150 ccm 9 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>elektrický startér + nožní startovací páka</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/PILOT_F150E') }}">Detaily</a>
        </div>
      </div>

      {{-- FACTORY SP2 150 --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">10–14 LET</span>
          <a href="{{ url('prodej-pitbike/FACTORY_SP2_150') }}">
            <img src="{{ url('images/moto_new/FACTORY_SP2_150.webp') }}" loading="lazy" alt="PITBIKE YCF FACTORY SP2 150" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/FACTORY_SP2_150') }}" class="hover:text-mx-gold">YCF FACTORY SP2 150</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">64.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 149,8 ccm 13 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>nožní startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/FACTORY_SP2_150') }}">Detaily</a>
        </div>
      </div>

      {{-- FACTORY SP3 190 --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">14–99 LET</span>
          <a href="{{ url('prodej-pitbike/FACTORY_SP3_190') }}">
            <img src="{{ url('images/moto_new/FACTORY_SP3_190.webp') }}" loading="lazy" alt="PITBIKE YCF FACTORY SP3 190" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/FACTORY_SP3_190') }}" class="hover:text-mx-gold">YCF FACTORY SP3 190</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">87.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 187,2 ccm 18,64 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>nožní startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/FACTORY_SP3_190') }}">Detaily</a>
        </div>
      </div>

      {{-- BIGY 125MX --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">14–99 LET</span>
          <a href="{{ url('prodej-pitbike/BIGY_125MX') }}">
            <img src="{{ url('images/moto_new/BIGY_125MX.webp') }}" loading="lazy" alt="PITBIKE YCF BIGY 125MX" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/BIGY_125MX') }}" class="hover:text-mx-gold">YCF BIGY 125MX</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">56.990,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 125 ccm 6,5 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>nožní startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/BIGY_125MX') }}">Detaily</a>
        </div>
      </div>

      {{-- BIGY 150E XL --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">14–99 LET</span>
          <a href="{{ url('prodej-pitbike/BIGY_150E_XL') }}">
            <img src="{{ url('images/moto_new/BIGY_150E_XL.webp') }}" loading="lazy" alt="PITBIKE YCF BIGY FACTORY 150E XL" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/BIGY_150E_XL') }}" class="hover:text-mx-gold">YCF BIGY FACTORY 150E XL</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">75.900,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 150 ccm 10 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>elektrický startér + nožní startovací páka</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/BIGY_150E_XL') }}">Detaily</a>
        </div>
      </div>

      {{-- BIGY 190 DAYTONA XL --}}
      <div class="card-dark" data-aos="flip-left" data-aos-duration="600" data-aos-delay="200">
        <div class="relative">
          <span class="badge-orange absolute top-3 left-3">14–99 LET</span>
          <a href="{{ url('prodej-pitbike/BIGY_190_daytona_XL') }}">
            <img src="{{ url('images/moto_new/BIGY_190_daytona_XL.webp') }}" loading="lazy" alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="418" height="315" class="w-full object-cover">
          </a>
        </div>
        <div class="card-dark-body">
          <h4 class="text-mx-white font-semibold text-lg mb-1">
            <a href="{{ url('prodej-pitbike/BIGY_190_daytona_XL') }}" class="hover:text-mx-gold">YCF BIGY FACTORY 190 DAYTONA XL</a>
          </h4>
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-bold text-mx-orange">93.490,- Kč</span>
            <span class="text-gray-400 text-xs">vč. DPH</span>
          </div>
          <ul class="space-y-1 text-gray-400 text-sm mb-4">
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>čtyřtaktní motor 190 ccm 18,64 kW</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>manuální řazení se spojkovou páčkou</li>
            <li class="flex items-start gap-2"><i class="fa-solid fa-check text-mx-orange mt-0.5 text-xs"></i>nožní startovací páka se zesíleným kloubem</li>
          </ul>
          <a class="btn-primary btn-sm" href="{{ url('prodej-pitbike/BIGY_190_daytona_XL') }}">Detaily</a>
        </div>
      </div>

    </div>
  </div>
</section>

@includeIf('sections.whyBuyHere')

{{-- CTA quote --}}
<section class="section-sm bg-mx-black border-t border-mx-gray2">
  <div class="page-container text-center">
    <p class="text-gray-300 text-lg italic mb-6">
      Pokud potřebujete s výběrem poradit nebo si chcete stroj vyzkoušet, zastavte se za námi.
    </p>
    <a class="btn-primary" href="tel:+420704221663">
      <i class="fa-solid fa-phone"></i>
      704 221 663
    </a>
  </div>
</section>

@includeIf('sections.distanceToUs')
@includeIf('sections.service')

@endsection

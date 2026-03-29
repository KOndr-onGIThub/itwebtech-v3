@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'] )
@section('meta_description', $sitemap['description'] )
@endif
@empty
@endforelse

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="{{ url('/bazar') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike bazar - značkový pitbike za zlomek ceny.">
  <meta property="og:description" content="Garantovaný původ a nájezd max 50 MH u všech motorek. Přijďte si stroje osobně prohlédnout a vyzkoušet.">
  <meta property="og:image" content="https://pitarena.cz/images/bazar_YCF_1200x630.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ url('/bazar') }}">
  <meta name="twitter:title" content="Pitbike bazar - značkový pitbike za zlomek ceny.">
  <meta name="twitter:description" content="Garantovaný původ a nájezd max 50 MTH u všech motorek. Přijďte si stroje osobně prohlédnout a vyzkoušet.">
  <meta name="twitter:image" content="https://pitarena.cz/images/bazar_YCF_1200x630.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Bazar</li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

  {{-- Parallax hero --}}
  <section class="parallax-hero" style="--hero-img: url('{{ asset('images/pitarena_bazar_header.webp') }}');">
    <div class="parallax-hero-content">
      <div class="section-divider section-divider-center"></div>
      <h1>BAZAR</h1>
    </div>
  </section>


  {{-- KTM SX50 --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

        <div data-aos="fade-right">
          <div class="section-divider mb-4"></div>
          <h2 class="section-title mb-2">KTM SX50</h2>
          <p class="text-2xl font-bold text-mx-orange mb-4">60.000,-Kč</p>
          <p class="text-gray-300">
            r.v. 2020<br>
            Nový píst (na novej píst najeto cca.1 mth), nové brzdy - destičky zadní i přední, nový přední kotouč, nová řetězovka.<br>
            Vše děláno u Brumly. Jsou doklady a staré díly.
            Motorka je teď na originál malých kolech 10- a 12 přední.
            Pneu dobré, motorka v pořádku. Může se vyzkoušet ta trati.
            <br>
            Věřím ze ještě někomu dobře poslouží, pro nás už je malá.
          </p>
        </div>

        <div>
          <span class="badge-orange mb-4 inline-block">PO SERVISU</span>
          <div class="moto-gallery-swiper swiper" data-loop="true" data-nav="true">
            <div class="swiper-wrapper">
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (1)a.jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (1).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (2).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (2).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (3).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (3).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (4).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (4).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (5).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (5).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (6).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (6).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/KTM_SX50 (7).jpeg') }}">
                <img src="{{ url('/images/bazar/KTM_SX50 (7).jpeg') }}" loading="lazy" alt="KTM SX50" width="886" height="668" class="w-full rounded-lg">
              </a></div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>

      </div>
    </div>
  </section>


  {{-- YCF 50A --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

        <div data-aos="fade-right">
          <div class="section-divider mb-4"></div>
          <h2 class="section-title mb-2">PITBIKE YCF 50A</h2>
          <p class="text-2xl font-bold text-mx-orange mb-4">23.900,-Kč</p>
          <p class="text-gray-300">
            Skvělá terénní motorka pro děti od 3 do 7 let.
            Snadné ovládání zajišťují benzinový motor o objemu 50 ccm, elektrický startér,
            automatická převodovka a přední a zadní brzdové páčky s provedením jako u silničního kola.
            Pro začátečníky možno namontovat postranní stabilizační kolečka.
          </p>
        </div>

        <div>
          <span class="badge-orange mb-4 inline-block">TOP STAV</span>
          <div class="moto-gallery-swiper swiper" data-loop="true" data-nav="true">
            <div class="swiper-wrapper">
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/A50 (1).jpeg') }}">
                <img src="{{ url('/images/bazar/A50 (1).jpeg') }}" loading="lazy" alt="YCF 50A" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/A50 (2).jpeg') }}">
                <img src="{{ url('/images/bazar/A50 (2).jpeg') }}" loading="lazy" alt="YCF 50A" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/A50 (3).jpeg') }}">
                <img src="{{ url('/images/bazar/A50 (3).jpeg') }}" loading="lazy" alt="YCF 50A" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/A50 (4).jpeg') }}">
                <img src="{{ url('/images/bazar/A50 (4).jpeg') }}" loading="lazy" alt="YCF 50A" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/A50 (5).jpeg') }}">
                <img src="{{ url('/images/bazar/A50 (5).jpeg') }}" loading="lazy" alt="YCF 50A" width="886" height="668" class="w-full rounded-lg">
              </a></div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>

      </div>
    </div>
  </section>


  {{-- YCF F88 --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

        <div data-aos="fade-right">
          <div class="section-divider mb-4"></div>
          <h2 class="section-title mb-2">PITBIKE YCF F88</h2>
          <p class="text-2xl font-bold text-mx-orange mb-4">
            <del class="text-gray-400 font-normal text-lg mr-2">29.900,-Kč</del>
            20.000,-Kč
          </p>
          <p class="text-gray-300">
            Skvěle padne do rukou dětem a začátečníkům ve věku 6-8 let. Benzinový motor o objemu 88 ccm zajišťuje dostatečný výkon.
            Díky nastavitelné plynové rukojeti a poloautomatické převodovce s automatickou spojkou se tato terénní motorka skvěle hodí pro děti.
            Díky nastavitelnému podvozku můžete regulovat výšku sedadla.
          </p>
        </div>

        <div>
          <span class="badge-orange mb-4 inline-block">NEJPRODÁVANĚJŠÍ</span>
          <div class="moto-gallery-swiper swiper" data-loop="true" data-nav="true">
            <div class="swiper-wrapper">
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/F88LITE (1).jpeg') }}">
                <img src="{{ url('/images/bazar/F88LITE (1).jpeg') }}" loading="lazy" alt="YCF F88" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/F88LITE (2).jpeg') }}">
                <img src="{{ url('/images/bazar/F88LITE (2).jpeg') }}" loading="lazy" alt="YCF F88" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/F88LITE (3).jpeg') }}">
                <img src="{{ url('/images/bazar/F88LITE (3).jpeg') }}" loading="lazy" alt="YCF F88" width="886" height="668" class="w-full rounded-lg">
              </a></div>
              <div class="swiper-slide"><a class="moto-gallery-item block" href="{{ url('/images/bazar/F88LITE (4).jpeg') }}">
                <img src="{{ url('/images/bazar/F88LITE (4).jpeg') }}" loading="lazy" alt="YCF F88" width="886" height="668" class="w-full rounded-lg">
              </a></div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>

      </div>
    </div>
  </section>


  {{-- CTA quote sekce --}}
  <section class="section bg-mx-black">
    <div class="page-container">
      <div class="bg-mx-orange/10 border border-mx-orange rounded-xl px-8 py-8 max-w-2xl mx-auto text-center">
        <p class="text-mx-white text-lg font-medium mb-2">
          Garantovaný původ a nájezd max 50 MTH u všech motorek.
        </p>
        <p class="text-gray-300 mb-4">
          Zavolejte a přijďte si stroje osobně prohlédnout a vyzkoušet.
        </p>
        <a class="btn-primary" href="tel:+420704221663">
          <i class="fa-solid fa-phone mr-2"></i> 704 221 663
        </a>
      </div>
    </div>
  </section>


  @includeIf('sections.distanceToUs')

  @includeIf('sections.service')

@endsection

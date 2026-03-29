@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'gallery')
@section('title', $sitemap['title'] )
@section('meta_description', $sitemap['description'] )
@endif
@empty
@endforelse

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="https://pitarena.cz">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection



@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li><a href="{{ route('get.galleries') }}">Galerie</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">{{ $thisCat }}</li>
    </ol>
  </div>
</nav>
@endsection


@section('content')

<section class="bg-mx-black section-lg">
  <div class="page-container">

    <div class="text-center mb-6" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <span class="badge-orange mb-2 inline-block">{{ $thisCat }}</span>
      <h1 class="section-title mt-2">Galerie - {{ $thisCat }}</h1>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" data-lg-gallery>
      @foreach ($mixes as $mix)
      <a class="lg-item" href="../{{ $mix->href_big }}">
        <img src="../{{ $mix->src_small }}" loading="lazy" alt="Galerie - {{ $thisCat }}" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
      </a>
      @endforeach
    </div>

    {{-- Autor --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 items-center mt-10 p-6 bg-mx-black rounded-lg">
      <div class="flex justify-center sm:justify-start">
        <img class="rounded-full w-32 h-32 object-cover" src="../images/katerina_holcmannova_193px.webp" width="193" height="193" alt="Katerina Holcmannova">
      </div>
      <div class="sm:col-span-3">
        <h5 class="text-white font-semibold text-lg">Kateřina Holcmannová</h5>
        <h6 class="text-gray-400 text-sm mb-2">Autorka fotografie</h6>
        <p class="text-gray-300">Pokud máte zájem nechat nafotit svého malého závodníka nebo sebe, napište Katce na <a href="mailto:6.bholcmannova.katerina@seznam.cz" class="text-orange-400 hover:underline">6.bholcmannova.katerina@seznam.cz</a></p>
      </div>
    </div>

    {{-- Navigace --}}
    <div class="flex justify-between items-center mt-8">
      <a href="{{ route('get.galleries') }}" class="btn-outline flex items-center gap-2">
        <i class="fa-solid fa-chevron-left text-[11px]"></i>
        <span>Zpět na všechny kategorie</span>
      </a>
      <a href="{{ url('gallery').'/'. $nextCat }}" class="btn-outline flex items-center gap-2">
        <span>Další kategorie: {{ $nextCat }}</span>
        <i class="fa-solid fa-chevron-right text-[11px]"></i>
      </a>
    </div>

  </div>
</section>

@endsection

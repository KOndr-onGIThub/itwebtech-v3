@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Závodní kategorie Pitbike YCF Cup">
  <meta property="og:description" content="Podívejte se na přehled závodních kategorií pro YCF Cup, jejich parametry a věkové limity. Vyberte tu správnou kategorii pro vás.">
  <meta property="og:image" content="https://pitarena.cz/images/cup/ycf_cup_categorie.png">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Závodní kategorie Pitbike YCF Cup">
  <meta name="twitter:description" content="Podívejte se na přehled závodních kategorií pro YCF Cup, jejich parametry a věkové limity.">
  <meta name="twitter:image" content="https://pitarena.cz/images/cup/ycf_cup_categorie.png">
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
        <a itemprop="item" href="{{ route('cup') }}"><span itemprop="name">Závody</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Závodní kategorie Pitbike</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section class="parallax-hero" style="--hero-img: url('{{ asset('images/cup/ycf_cup_categorie.png') }}')">
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white">
        Závodní kategorie Pitbike pro YCF Cup
      </h1>
    </div>
  </div>
</section>

{{-- Intro --}}
<section class="section-sm bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="section-divider"></div>
    <p class="text-gray-300 text-sm leading-relaxed mb-5">
      Pitbike závody v rámci YCF Cupu jsou rozděleny do několika kategorií podle věku jezdců (rozhodující je věk v den 1. závodu sezóny)
      a technických parametrů motocyklů. V každé kategorii platí specifická pravidla pro typ a výkon motorky, aby byla zajištěna férovost závodů.
      Prohlédněte si jednotlivé kategorie níže a zjistěte, která z nich je pro vás nebo vaše dítě vhodná.
    </p>
    <h2 class="text-xl font-bold text-mx-white mb-3">
      Značka motocyklu není rozhodující.<br>
      Přihlásit se můžete i s pitbiky jiných značek než je YCF.
    </h2>
    <p class="text-gray-400 text-sm italic leading-relaxed mb-6">
      Jezdec spadající dle kritérií do více kategorií má právo volby,
      ale může být přeřazen do jiné kategorie na základě rozhodnutí ředitele závodu s ohledem na jeho výkonnost.
      Změna kategorie proběhne při jezdcovo prvním závodě v seriálu.
    </p>
    <div data-aos="zoom-out" data-aos-duration="600" data-aos-delay="200">
      <a class="btn-outline btn-sm" href="#tabulka">Tabulka aktuálně otevřených kategorií</a>
    </div>
  </div>
</section>

{{-- Přehled kategorií --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-10">Přehled závodních kategorií</h2>
    </div>

    @php
    $categories = [
      ['name' => 'Mini 50', 'img' => 'mini.webp', 'alt' => 'YCF CUP kategorie MINI', 'specs' => [
        'MOTOR' => ['čtyřdobý motor o maximálním obsahu 50 ccm', 'dvoudobý motor o maximálním obsahu 50 ccm (jen vzduchem chlazený - "Čína")'],
        'KOLA' => ['kola o maximální velikosti přední 10", zadní 10"'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 5 do 7 let <small class="text-gray-400">(+ ten komu bude 8 v tomto roce)</small>'],
      ]],
      ['name' => 'MX 50', 'img' => 'mx50.webp', 'alt' => 'YCF CUP kategorie MX 50', 'specs' => [
        'MOTOR' => ['dvoudobý motor o maximálním obsahu 50 ccm (včetně vodou chlazených)'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka od 5 do 10 let včetně'],
      ]],
      ['name' => 'MX 65', 'img' => 'mx65.webp', 'alt' => 'YCF CUP kategorie MX 65', 'specs' => [
        'MOTOR' => ['dvoudobý motor o maximálním obsahu 65 ccm (včetně vodou chlazených)'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka od 5 do 12 let včetně'],
      ]],
      ['name' => 'MX 85', 'img' => 'mx85.webp', 'alt' => 'YCF CUP kategorie MX 85', 'specs' => [
        'MOTOR' => ['dvoudobý motor o maximálním obsahu 85 ccm (včetně vodou chlazených)'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka od 7 do 18 let včetně'],
      ]],
      ['name' => 'Junior 90', 'img' => 'junior90.webp', 'alt' => 'YCF CUP kategorie JUNIOR 90', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 98 ccm'],
        'KOLA' => ['kola o maximální velikosti přední 12", zadní 10"'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 5 do 10 let včetně'],
      ]],
      ['name' => 'Junior 125', 'img' => 'junior125.webp', 'alt' => 'YCF CUP kategorie JUNIOR 125', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 125 ccm'],
        'KOLA' => ['kola o maximální velikosti přední 14", zadní 12"'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 7 do 15 let včetně'],
      ]],
      ['name' => 'Hobby', 'img' => 'hobbyB.webp', 'alt' => 'YCF CUP kategorie HOBBY', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 160 ccm'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 12 let do neomezeno'],
      ]],
      ['name' => 'Open', 'img' => 'openB.webp', 'alt' => 'YCF CUP kategorie OPEN', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 212 ccm'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 15 let do neomezeno'],
      ]],
      ['name' => 'Senior', 'img' => 'senior.webp', 'alt' => 'YCF CUP kategorie SENIOR', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 212 ccm'],
        'KOLA' => ['kola nerozhodují'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodníka je od 35 let do neomezeno'],
      ]],
      ['name' => 'Lady', 'img' => 'lady.webp', 'alt' => 'YCF CUP kategorie LADY', 'specs' => [
        'MOTOR' => ['čtyřtaktní motor o maximálním obsahu 190 ccm'],
        'KOLA' => ['kola o maximální velikosti přední 17", zadní 14"'],
        'VĚK ZÁVODNÍKA' => ['věková hranice závodnice je od 12 let do neomezeno'],
      ]],
    ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      @foreach ($categories as $i => $cat)
      <div class="card-dark overflow-hidden" data-aos="{{ $loop->odd ? 'zoom-in-right' : 'zoom-in-left' }}" data-aos-duration="600" data-aos-delay="150">
        <img src="{{ asset('images/cup/'.$cat['img']) }}" loading="lazy" alt="{{ $cat['alt'] }}" class="w-full h-52 object-cover">
        <div class="p-5">
          <h3 class="text-xl font-bold text-mx-white mb-4">{{ $cat['name'] }}</h3>
          @foreach ($cat['specs'] as $label => $items)
          <p class="text-xs font-semibold text-mx-orange uppercase tracking-wide mt-3 mb-1">{{ $label }}</p>
          <ul class="space-y-1">
            @foreach ($items as $item)
            <li class="flex gap-2 text-sm text-gray-300">
              <i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0 text-xs"></i>
              <span>{!! $item !!}</span>
            </li>
            @endforeach
          </ul>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@includeIf('sections.start_cup', ['background' => 'bg-mx-dark', 'text' => 'Máš už vybranou kategorii? Zaregistruj se do závodů.'])

{{-- Tabulka kategorií --}}
<section id="tabulka" class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Tabulka kategorií</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm border-collapse">
        <thead>
          <tr class="bg-mx-gray text-gray-400 uppercase text-xs">
            <th class="px-4 py-3 text-left font-semibold">KATEGORIE</th>
            <th class="px-4 py-3 text-left font-semibold">MOTOR</th>
            <th class="px-4 py-3 text-left font-semibold">KOLA</th>
            <th class="px-4 py-3 text-left font-semibold">VĚK</th>
          </tr>
        </thead>
        <tbody>
          @php
          $rows = [
            ['MINI 50', '4T do 50 ccm, 2T do 50 ccm (vzduch chl.)', 'max 10"/10"', '5–7 (max 8 v tomto roce)'],
            ['MX 50', '2T do 50 ccm', 'libovolná', '5–10'],
            ['MX 65', '2T do 65 ccm', 'libovolná', '5–12'],
            ['MX 85', '2T do 85 ccm', 'libovolná', '7–18'],
            ['JUNIOR 90', '4T do 98 ccm', 'max 12"/10"', '5–10'],
            ['JUNIOR 125', '4T do 125 ccm', 'max 14"/12"', '7–15'],
            ['HOBBY', '4T do 160 ccm', 'libovolná', '12–99'],
            ['OPEN', '4T do 212 ccm', 'libovolná', '15–99'],
            ['SENIOR', '4T do 212 ccm', 'libovolná', '35–99'],
            ['LADY', '4T do 160 ccm', 'max 17"/14"', '12–99'],
          ];
          @endphp
          @foreach ($rows as $row)
          <tr class="border-t border-mx-gray2 odd:bg-red-900/40 even:bg-red-900/60 text-white">
            <td class="px-4 py-2 font-semibold">{{ $row[0] }}</td>
            <td class="px-4 py-2 text-gray-200">{{ $row[1] }}</td>
            <td class="px-4 py-2 text-gray-200">{{ $row[2] }}</td>
            <td class="px-4 py-2 text-gray-200">{{ $row[3] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

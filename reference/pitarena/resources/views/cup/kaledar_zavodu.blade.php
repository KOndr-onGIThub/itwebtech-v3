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
  <meta property="og:title" content="Kalendář závodů YCF Cup 2026">
  <meta property="og:description" content="Naplánujte si závody YCF Cup a užijte si jedinečný zážitek na pitbike závodech!">
  <meta property="og:image" content="https://pitarena.cz/images/cup/YCF_CUP_kaledar.png">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Kalendář závodů YCF Cup 2026">
  <meta name="twitter:description" content="Podívejte se na kalendář závodů YCF Cup a užijte si závody pitbiků!">
  <meta name="twitter:image" content="https://pitarena.cz/images/cup/YCF_CUP_kaledar.png">
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
        <span itemprop="name">Kalendář závodů YCF Cup 2026</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section class="parallax-hero" style="--hero-img: url('{{ asset('images/cup/YCF_CUP_kaledar.webp') }}')">
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white">
        Kalendář závodů YCF Cup 2026
      </h1>
    </div>
  </div>
</section>

<section class="section bg-mx-black">
  <div class="page-container">

    <div class="section-divider section-divider-center"></div>
    <p class="text-center text-gray-300 mb-6" data-aos="fade-up">
      Připravte se na sezónu plnou adrenalinu a zábavy na pitbikách!<br>
      Zde najdete kompletní přehled závodů YCF CUP pro rok 2026.
    </p>
    <div class="text-center mb-12" data-aos="zoom-in" data-aos-delay="150">
      <a class="btn-primary" href="{{ route('registrace-cup.index') }}">Registrace do závodu</a>
    </div>

    <ul class="space-y-16 max-w-4xl mx-auto">

      {{-- Závod 1 --}}
      <li data-aos="fade-up">
        <h2 class="text-xl font-bold text-mx-white mb-3">
          <span class="text-mx-orange">1.</span> 11. dubna 2026 – Pravice
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          Startujeme na domácí trati v Pravicích – technická přírodní dráha s rytmem zatáček a skoků tě hned od prvního kola postaví do tempa.
        </p>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <img src="{{ asset('images/cup/trat-pravice.webp') }}" loading="lazy" alt="Závod Pravice 2026" class="w-full h-[280px] object-cover rounded-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d43351.615244881825!2d16.34039438688065!3d48.84981449487359!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4712b58541ef5cd1%3A0xafc9f2294f6d4f41!2sMX%2FENDURO%20PRAVICE!5e0!3m2!1scs!2sus!4v1728582407827!5m2!1scs!2sus" class="w-full h-[280px] rounded-lg border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa závodu Pravice"></iframe>
        </div>
      </li>

      {{-- Závod 2 --}}
      <li data-aos="fade-up">
        <h2 class="text-xl font-bold text-mx-white mb-3">
          <span class="text-mx-orange">2.</span> 30. května 2026 – Smrk
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          Technická trať Smrk u Třebíče s přírodním profilem a proměnlivým rytmem hostí závod YCF CUP 30. května 2026.
        </p>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <img src="{{ asset('images/cup/trat-smrk.webp') }}" loading="lazy" alt="Závod pitbike YCF CUP Smrk" class="w-full h-[280px] object-cover rounded-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20846.15057432401!2d15.998202699999997!3d49.223911199999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470d5d76fd981f8f%3A0x4dd4397b68b6c5f5!2sMotokrosov%C3%BD%20are%C3%A1l%20Smrk!5e0!3m2!1scs!2scz!4v1768410738767!5m2!1scs!2scz" class="w-full h-[280px] rounded-lg border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa závodu Smrk"></iframe>
        </div>
      </li>

      {{-- Závod 3 --}}
      <li data-aos="fade-up">
        <h2 class="text-xl font-bold text-mx-white mb-3">
          <span class="text-mx-orange">3.</span> 27. června 2026 – Vranov
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          Přírodní trať AMK Zbrojovky Brno (také známá jako trať ve Vranově u Brna / Vranovský žleb) s výrazným převýšením prověří fyzičku i techniku každého jezdce.
        </p>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <img src="{{ asset('images/cup/trat-vranov.webp') }}" loading="lazy" alt="YCF CUP závod ve Vranově pořádá PitAréna" class="w-full h-[280px] object-cover rounded-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d156389.48228284853!2d16.483647955337595!3d49.2855011102403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47128e1eaad96bd7%3A0xba45f5efb469b49a!2sSportovn%C3%AD%20are%C3%A1l%20AMK%20Zbrojovky%20Brno!5e0!3m2!1scs!2scz!4v1768411057132!5m2!1scs!2scz" class="w-full h-[280px] rounded-lg border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa závodu Vranov"></iframe>
        </div>
      </li>

      {{-- Závod 4 --}}
      <li data-aos="fade-up">
        <h2 class="text-xl font-bold text-mx-white mb-3">
          <span class="text-mx-orange">4.</span> 26. září 2026 – Miroslav
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          Na hlinité trati v Miroslavi, která prověří jak rytmus jízdy, tak obratnost v zatáčkách, si přijde na své začátečník i profesionál.
        </p>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <img src="{{ asset('images/cup/trat-miroslav.webp') }}" loading="lazy" alt="Závod YCF cup Miroslav" class="w-full h-[280px] object-cover rounded-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d95085.3158058461!2d16.30005950293369!3d48.96158415774621!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4712b138bc4c2d4f%3A0x2bdd819cd2c3182f!2sMotocross%20track%20Miroslav!5e0!3m2!1scs!2sus!4v1728582640504!5m2!1scs!2sus" class="w-full h-[280px] rounded-lg border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa závodu Miroslav"></iframe>
        </div>
      </li>

      {{-- Závod 5 --}}
      <li data-aos="fade-up">
        <h2 class="text-xl font-bold text-mx-white mb-3">
          <span class="text-mx-orange">5.</span> 3. října 2026 – Pravice (finále)
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-5">
          Sezóna se uzavírá tam, kde začala – v Pravicích, kde se bude rozhodovat o celkovém pořadí YCF Cupu.
        </p>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
          <img src="{{ asset('images/cup/trat-pravice.webp') }}" loading="lazy" alt="Finálový pitbike motokros závod Pravice" class="w-full h-[280px] object-cover rounded-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d43351.615244881825!2d16.34039438688065!3d48.84981449487359!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4712b58541ef5cd1%3A0xafc9f2294f6d4f41!2sMX%2FENDURO%20PRAVICE!5e0!3m2!1scs!2sus!4v1728582407827!5m2!1scs!2sus" class="w-full h-[280px] rounded-lg border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa finálového závodu Pravice"></iframe>
        </div>
      </li>

    </ul>
  </div>
</section>

@includeIf('sections.start_cup', ['background' => '', 'text' => 'Vybral si jen jeden závodní den, nebo si troufneš na všechny?<br>Při registraci si naklikáš to co ti sedí.'])

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

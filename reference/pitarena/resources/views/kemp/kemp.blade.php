@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'kemp')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('kemp') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Týden opravdového závodníka. Připojte se k nám.">
  <meta property="og:description" content="Profesionální tréninky, zábavné táborové aktivity a nezapomenutelné zážitky čekají na vaše děti. Sdílejte s přáteli a zjistěte více.">
  <meta property="og:image" content="https://pitarena.cz/images/kemp/pitbike_motokros_kemp_2026_2.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('kemp') }}">
  <meta name="twitter:title" content="Týden opravdového závodníka. Připojte se k nám.">
  <meta name="twitter:description" content="Profesionální tréninky, zábavné táborové aktivity a nezapomenutelné zážitky čekají na vaše děti. Sdílejte s přáteli a zjistěte více.">
  <meta name="twitter:image" content="https://pitarena.cz/images/kemp/pitbike_motokros_kemp_2026_2.jpg">
@endsection

@section('extra_css')
<style>
  /* Sticky CTA lišta */
  #kemp-sticky-bar {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 500;
    background: #ce1212;
    color: #fff;
    box-shadow: 0 -3px 14px rgba(0,0,0,.35);
    transform: translateY(100%);
    transition: transform .35s cubic-bezier(.4,0,.2,1);
    will-change: transform;
  }
  #kemp-sticky-bar.is-visible { transform: translateY(0); }
  .kemp-sticky-bar__inner {
    display: flex; align-items: center; justify-content: center;
    gap: 1rem; padding: .6rem 1.25rem;
  }
  .kemp-sticky-bar__text {
    margin: 0; font-size: .95rem; color: #fff;
    white-space: nowrap; line-height: 1.3; text-align: center;
  }
  .kemp-bar-spots { display: block; font-size: .8rem; font-weight: 700; letter-spacing: .02em; }
  @media (max-width: 479px) { .kemp-sticky-bar__text { display: none; } }
  .kemp-bar-btn {
    display: inline-block; padding: .4rem 1rem; border-radius: 4px;
    background: #fff; color: #ce1212 !important; font-weight: 700;
    border: 2px solid #fff; text-decoration: none; white-space: nowrap;
    transition: background .2s, border-color .2s;
  }
  .kemp-bar-btn:hover { background: #f5f5f5; border-color: #f5f5f5; color: #a50f0f !important; }
  @keyframes kemp-btn-pulse {
    0%, 100% { transform: scale(1); }
    35%       { transform: scale(1.09); }
    65%       { transform: scale(1.04); }
  }
  .kemp-sticky-bar__btn--pulse { animation: kemp-btn-pulse .5s ease .55s 2; }
</style>
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
        <span itemprop="name">Letní Kemp</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Facebook SDK --}}
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

{{-- Hero --}}
<section
  id="kemp-hero"
  class="parallax-hero min-h-[420px] sm:min-h-[520px]"
  style="--hero-img: url('{{ asset('images/kemp/pitbike_kemp_header_2026.webp') }}'); background-attachment: scroll;"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center mb-4 sm:mb-5"></div>
    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 sm:mb-6 balanced-text">Pitbike Kemp 2026</h1>
    <div class="flex flex-row gap-2 sm:gap-3 justify-center mb-5 sm:mb-6">
      <div class="flex flex-col items-center bg-black/40 rounded-lg px-3 py-2 sm:px-6 sm:py-4 backdrop-blur-sm border border-white/15">
        <span id="hero-spots-0" class="block mb-1 min-h-[1.2em]"></span>
        <span class="text-white font-bold text-sm sm:text-xl lg:text-2xl">5.7. – 11.7.2026</span>
        <span class="text-gray-300 text-xs mt-0.5 sm:mt-1">děti 6–12 let</span>
      </div>
      <div class="flex flex-col items-center bg-black/40 rounded-lg px-3 py-2 sm:px-6 sm:py-4 backdrop-blur-sm border border-white/15">
        <span id="hero-spots-1" class="block mb-1 min-h-[1.2em]"></span>
        <span class="text-white font-bold text-sm sm:text-xl lg:text-2xl">26.7. – 1.8.2026</span>
        <span class="text-gray-300 text-xs mt-0.5 sm:mt-1">děti 8–14 let</span>
      </div>
    </div>
    <a href="#koupit" class="btn-primary">Zajistit místo pro mé dítě</a>
  </div>
</section>
<script>
(function () {
  var hero = document.getElementById('kemp-hero');
  if (!hero || window.matchMedia('(max-width: 767px)').matches) return;
  var update = function () {
    hero.style.backgroundPositionY = (window.scrollY * 0.35) + 'px';
  };
  window.addEventListener('scroll', update, { passive: true });
  update();
})();
</script>

{{-- Intro --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <p class="hero-eyebrow mb-4" data-aos="fade-up">LETNÍ KEMP 2026</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center mb-10">

      {{-- Levý sloupec: text --}}
      <div>
        <h2 class="section-title mb-4" data-aos="fade-up" data-aos-delay="100">
          Vaše dítě se vrátí silnější, zralejší a s&nbsp;úsměvem až za ušima.
        </h2>
        <p class="text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="150">
          6denní motokrosový kemp vedený certifikovanými trenéry – bezpečně, s péčí, v malé skupině.
        </p>
      </div>

      {{-- Pravý sloupec: mapa ČR --}}
      <div class="flex flex-col items-center pt-2" data-aos="fade-left" data-aos-delay="200">
        {{-- Mapa ČR — geoViewBox z mapsvg.com, dot: Pravice 48.8499°N 16.3721°E → left 63.3%, top 88.1% --}}
        <div class="relative w-full max-w-xs lg:max-w-sm" aria-label="Mapa České republiky – místo konání kempu">
          <img src="/images/cr-outline.svg" alt="Česká republika" class="w-full h-auto" />
          <a href="https://maps.app.goo.gl/j8uYTawn6qhzD1Se9" target="_blank" rel="noopener"
             class="absolute p-3 -translate-x-1/2 -translate-y-1/2"
             style="left:63.3%;top:88.1%"
             aria-label="Otevřít PitAréna Pravice na Google Maps">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-mx-orange opacity-60"></span>
              <span class="relative inline-flex h-3 w-3 rounded-full bg-mx-orange" style="box-shadow:0 0 8px rgba(206,18,18,0.8)"></span>
            </span>
          </a>
        </div>
        <div class="mt-3 text-center lg:text-left">
          <p class="text-mx-white font-semibold text-sm">PitAréna Pravice</p>
          <p class="text-gray-400 text-xs mb-1.5">Jihomoravský kraj</p>
          <a href="https://maps.app.goo.gl/j8uYTawn6qhzD1Se9" target="_blank" rel="noopener"
             class="text-mx-orange text-xs hover:underline inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> zobrazit na mapě
          </a>
        </div>
      </div>

    </div>

    {{-- Feature boxy --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
      <div class="card-dark flex items-start gap-4 p-5" data-aos="fade-up" data-aos-delay="0">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
          <i class="fa-solid fa-shield-halved text-mx-orange text-sm"></i>
        </div>
        <div>
          <p class="text-mx-white font-semibold text-sm mb-1">Bezpečně & s péčí</p>
          <p class="text-gray-400 text-xs leading-relaxed">Certifikovaní trenéři a profesionální závodníci</p>
        </div>
      </div>
      <div class="card-dark flex items-start gap-4 p-5" data-aos="fade-up" data-aos-delay="100">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
          <i class="fa-solid fa-users text-mx-orange text-sm"></i>
        </div>
        <div>
          <p class="text-mx-white font-semibold text-sm mb-1">Malá skupina</p>
          <p class="text-gray-400 text-xs leading-relaxed">Individuální přístup a pozornost každému dítěti</p>
        </div>
      </div>
      <div class="card-dark flex items-start gap-4 p-5" data-aos="fade-up" data-aos-delay="200">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
          <i class="fa-solid fa-trophy text-mx-orange text-sm"></i>
        </div>
        <div>
          <p class="text-mx-white font-semibold text-sm mb-1">Vzdělávání i zábava</p>
          <p class="text-gray-400 text-xs leading-relaxed">Tréninky, táborové hry, bezpečnost i údržba motorky</p>
        </div>
      </div>
    </div>

    <div class="text-center" data-aos="zoom-in" data-aos-delay="300">
      <a class="btn-primary" href="#koupit">Zajistit místo pro mé dítě</a>
    </div>
  </div>
</section>

{{-- Presentation --}}
<section id="info" class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="order-2 lg:order-1" data-aos="fade-right">
        <div class="section-divider mb-4"></div>
        <h2 class="section-title mb-3">Pitbike Kemp</h2>
        <h3 class="text-mx-white font-semibold mb-6">Nechte své děti okusit týden profesionálního jezdce.</h3>
        <div class="space-y-4">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
              <i class="fa-solid fa-flag-checkered text-mx-orange text-sm"></i>
            </div>
            <div>
              <p class="text-mx-white font-semibold text-sm mb-1">Certifikovaní trenéři a profesionální závodníci</p>
              <p class="text-gray-400 text-xs leading-relaxed">Každoroční táborové soustředění vedené odborníky přímo v terénu.</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
              <i class="fa-solid fa-people-group text-mx-orange text-sm"></i>
            </div>
            <div>
              <p class="text-mx-white font-semibold text-sm mb-1">Táborové hry a skupinová zábava</p>
              <p class="text-gray-400 text-xs leading-relaxed">Není to jen o ježdění — hrajeme hry, bavíme se a tvoříme partu.</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
              <i class="fa-solid fa-shield-halved text-mx-orange text-sm"></i>
            </div>
            <div>
              <p class="text-mx-white font-semibold text-sm mb-1">Bezpečnost na trati i mimo ni</p>
              <p class="text-gray-400 text-xs leading-relaxed">Vzdělávání o správném chování při trénincích a závodech.</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-mx-orange/10 border border-mx-orange/30 flex items-center justify-center">
              <i class="fa-solid fa-wrench text-mx-orange text-sm"></i>
            </div>
            <div>
              <p class="text-mx-white font-semibold text-sm mb-1">Péče o motorku v praxi</p>
              <p class="text-gray-400 text-xs leading-relaxed">Děti se naučí základní údržbu — prakticky, ne jen z knížky.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="order-1 lg:order-2" data-aos="fade-left">
        <video
          class="w-full rounded-lg"
          width="652" height="491"
          autoplay muted loop playsinline
          preload="metadata"
          poster="{{ asset('images/kemp/sestrih_kemp_2025-poster.webp') }}"
          aria-label="Sestřih z Pitbike kempu 2025"
        >
          <source src="{{ asset('video/sestrih_kemp_2025.mp4') }}" type="video/mp4">
          <img src="{{ asset('images/pitbike_kemp_2025.webp') }}" width="652" height="491" alt="Motokros na Moravě">
        </video>
      </div>
    </div>
  </div>
</section>

@includeIf('sections/testimonials', ['background' => 'bg-mx-black', 'hideLeaveReview' => true, 'only' => [
  'dorota_havlickova', 'veronika_vejtasova','bretislav_vejtasa',
  'petr_kyjovsky', 'tomas_trejbal', 'patrik_krejci', 'roman_psenicka',
  'petr_janicek', 'jiri_kohout', 'tomas_bastl', 'daniel_martinek']])

{{-- Komu je kemp určen --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <h2 class="section-title mb-3">Komu je kemp určen?</h2>
      <p class="text-gray-400 text-sm mb-8">Pro bezpečnost a kvalitu kempu splňte jednu z těchto podmínek:</p>
    </div>

    <div class="space-y-3 mb-8">
      <div class="card-dark p-5 flex gap-4 items-start" data-aos="fade-up" data-aos-delay="0">
        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-mx-orange/10 border border-mx-orange/40 flex items-center justify-center">
          <span class="text-mx-orange font-bold text-sm">A</span>
        </div>
        <div class="text-gray-400 text-sm leading-relaxed">
          <strong class="text-mx-white">Vracíte se z minulých ročníků?</strong> Vítejte zpět — registrujte se přímo.
        </div>
      </div>
      <div class="card-dark p-5 flex gap-4 items-start" data-aos="fade-up" data-aos-delay="100">
        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-mx-orange/10 border border-mx-orange/40 flex items-center justify-center">
          <span class="text-mx-orange font-bold text-sm">B</span>
        </div>
        <div class="text-gray-400 text-sm leading-relaxed">
          <strong class="text-mx-white">Dítě má zkušenost s okruhovou jízdou v terénu?</strong> Doložte min. 2 závody MX/Pitbike
          nebo absolvovaný program <a href="{{ route('mx-go') }}" target="_blank" class="text-mx-gold hover:underline">MX-GO</a> (min. 2×2,5 h).
          (Jízda za domem, na poli nebo v lese se nezapočítává.) Zkušenosti popište v poznámce při registraci.
        </div>
      </div>
      <div class="card-dark p-5 flex gap-4 items-start" data-aos="fade-up" data-aos-delay="200">
        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-mx-orange/10 border border-mx-orange/40 flex items-center justify-center">
          <span class="text-mx-orange font-bold text-sm">C</span>
        </div>
        <div class="text-gray-400 text-sm leading-relaxed">
          <strong class="text-mx-white">Žádné nebo minimální zkušenosti?</strong>
          Nevadí — stačí k registraci přidat <a href="{{ route('mxsoustredeni') }}" target="_blank" class="text-mx-gold hover:underline">MX soustředění</a>, které proběhne před kempem.
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4" data-aos="zoom-in" data-aos-delay="300">
      <a class="btn-primary" href="#koupit">Zajistit místo pro mé dítě</a>
      <a class="btn-outline" href="{{ route('mxsoustredeni') }}" target="_blank">MX soustředění</a>
    </div>
  </div>
</section>

{{-- Předprodej --}}
<section id="predprodej-trigger" class="section bg-mx-black">
  <div class="page-container max-w-3xl">

    <div class="text-center mb-8" data-aos="fade-up">
      <p class="hero-eyebrow mb-3">CENA &amp; TERMÍNY</p>
      <h2 class="section-title mb-3">Předprodej zahájen</h2>
      {{-- Urgency indikátor --}}
      <div class="inline-flex items-center gap-2 mt-1">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
        </span>
        <p class="text-orange-400 font-semibold text-sm">Nyní si již můžete zakoupit tento voucher, který vám zajistí místo pro sezonu 2026.</p>
      </div>
    </div>

    <img src="{{ asset('images/kemp_voucher_registrace-VZOR-2026.webp') }}" loading="lazy" alt="voucher pitbike kemp"
         width="1000" height="324" class="w-full rounded-lg mb-8" data-aos="fade-up"/>

    {{-- Termíny --}}
    <div class="card-dark p-5 mb-6" data-aos="fade-up">
      <h3 class="text-xs font-bold uppercase tracking-widest text-mx-orange mb-4">Termíny kempu</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex gap-3 items-start">
          <i class="fa-solid fa-calendar text-mx-orange mt-0.5 flex-shrink-0"></i>
          <div>
            <span id="terminy-spots-0" class="text-xs font-semibold block mb-1"></span>
            <p class="text-mx-white font-semibold text-sm">5.7. – 11.7.2026</p>
            <p class="text-gray-400 text-xs">děti od 6 do 12 let</p>
          </div>
        </div>
        <div class="flex gap-3 items-start">
          <i class="fa-solid fa-calendar text-mx-orange mt-0.5 flex-shrink-0"></i>
          <div>
            <span id="terminy-spots-1" class="text-xs font-semibold block mb-1"></span>
            <p class="text-mx-white font-semibold text-sm">26.7. – 1.8.2026</p>
            <p class="text-gray-400 text-xs">děti od 8 do 14 let</p>
          </div>
        </div>
      </div>
      <p class="text-gray-500 text-xs italic mt-3">* Termíny jsou věkově oddělené záměrně — aby měl každý vrstevníky kolem sebe. Pokud chcete přihlásit sourozence nebo kamarády, kteří věkem nespadají do stejného termínu, domluvte se s námi telefonicky — výjimky jsou možné.</p>
    </div>

    {{-- Pricing — 2 splátky jedné ceny --}}
    <div class="mb-3" data-aos="fade-up" data-aos-delay="100">
      <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-3">Cena kempu · placeno ve 2 splátkách</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- 1. splátka --}}
        <div class="rounded-xl border border-mx-orange/40 p-5 flex flex-col" style="background: rgba(206,18,18,0.07);">
          <p class="text-[10px] font-bold uppercase tracking-widest text-mx-orange mb-2">1. splátka — nyní</p>
          <p class="text-4xl font-black text-mx-white mb-1">2 500 <span class="text-xl font-bold">Kč</span></p>
          <p class="text-gray-400 text-xs mt-auto">Rezervační voucher (místenka)</p>
        </div>
        {{-- 2. splátka --}}
        <div class="rounded-xl border border-white/10 bg-white/[0.03] p-5 flex flex-col" x-data="floatingTooltip('top-start')">
          <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">2. splátka — před kempem</p>
          <p class="text-4xl font-black text-mx-white mb-1">5 000 <span class="text-xl font-bold">Kč</span></p>
          <div class="flex items-center gap-1.5 mt-auto">
            <p class="text-gray-400 text-xs">Doplatek</p>
            <button x-ref="trigger" @click="toggle()" @mouseenter="preload()" class="focus:outline-none flex-shrink-0" aria-label="Kdy budu platit?">
              <span class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-mx-orange text-mx-orange hover:bg-mx-orange hover:text-white transition-colors text-[10px] font-bold leading-none">?</span>
            </button>
          </div>
          <template x-teleport="body">
            <div x-ref="tooltip"
                 :class="open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                 class="transition-opacity duration-150 rounded-lg border-l-2 border-mx-orange bg-mx-gray p-3 text-xs text-gray-300 leading-relaxed shadow-xl"
                 :style="`position:fixed;left:${x}px;top:${y}px;z-index:9999;width:260px`"
                 @click.outside="open = false" @keydown.escape.window="open = false">
              <div class="flex items-start justify-between gap-2 mb-1">
                <p class="font-semibold text-mx-white">Kdy budu platit doplatek?</p>
                <button @click="open = false" class="text-mx-orange hover:text-mx-white transition-colors flex-shrink-0 leading-none" aria-label="Zavřít">✕</button>
              </div>
              <p>Cca 60 dní před zahájením kempu. Výzvu k platbě obdržíte emailem — nemusíte na nic myslet.</p>
            </div>
          </template>
        </div>
      </div>
      {{-- Celkem --}}
      <div class="flex items-center justify-between mt-3 px-1">
        <p class="text-gray-500 text-xs uppercase tracking-widest">Celková cena kempu</p>
        <p class="text-mx-white font-bold text-lg">7 500 Kč</p>
      </div>
    </div>

    <div class="mb-6" data-aos="zoom-in" data-aos-delay="150">
      <a href="#koupit" class="btn-primary w-full text-center block">Zajistit místo pro mé dítě</a>
    </div>

    {{-- Volitelné položky --}}
    <div class="flex items-start gap-3 rounded-lg border border-white/8 bg-white/[0.02] px-4 py-3 mb-4" data-aos="fade-up">
      <i class="fa-solid fa-circle-info text-gray-500 flex-shrink-0 mt-0.5 text-sm"></i>
      <p class="text-gray-400 text-xs leading-relaxed">
        Níže uvedené doplňky <strong class="text-gray-300">neplatíte nyní</strong> — možnost jejich výběru bude součástí platby 2. splátky (doplatku). Zatím si jen projděte, co vám může přijít vhod.
      </p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8" data-aos="fade-up" data-aos-delay="100">
      {{-- Večeře + mechanik --}}
      <div class="card-dark p-5 flex flex-col gap-4">
        <h4 class="text-xs font-bold uppercase tracking-widest text-mx-orange">Potřebujete něco navíc?</h4>
        <div class="flex flex-col gap-3">
          <div class="flex gap-3 items-start">
            <i class="fa-solid fa-moon text-mx-orange flex-shrink-0 mt-0.5 text-sm"></i>
            <div>
              <p class="text-mx-white font-semibold text-sm">Večeře + večerní program + sprchy</p>
              <p class="text-gray-500 text-xs mt-0.5">Povinné pro ty, kteří neodjíždí večer domů. Přespání ve vlastních stanech.</p>
              <p class="text-gray-400 text-xs font-semibold mt-1.5">1 400 Kč</p>
            </div>
          </div>
          <div class="border-t border-white/5 pt-3 flex gap-3 items-start" x-data="floatingTooltip('top-start')">
            <i class="fa-solid fa-wrench text-mx-orange flex-shrink-0 mt-0.5 text-sm"></i>
            <div class="flex-1">
              <div class="flex items-center gap-1.5">
                <p class="text-mx-white font-semibold text-sm">Mechanik</p>
                <button x-ref="trigger" @click="toggle()" @mouseenter="preload()" class="focus:outline-none flex-shrink-0" aria-label="Co zahrnuje mechanik?">
                  <span class="inline-flex items-center justify-center w-4 h-4 rounded-full border border-mx-orange text-mx-orange hover:bg-mx-orange hover:text-white transition-colors text-[10px] font-bold leading-none">?</span>
                </button>
              </div>
              <p class="text-gray-500 text-xs mt-0.5">Denní údržba motorky po celou dobu kempu.</p>
              <p class="text-gray-400 text-xs font-semibold mt-1.5">500 Kč</p>
            </div>
            {{-- Tooltip teleportovaný do body — Floating UI řeší pozici a přetékání --}}
            <template x-teleport="body">
              <div x-ref="tooltip"
                   :class="open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                   class="transition-opacity duration-150 rounded-lg border-l-2 border-mx-orange bg-mx-gray px-3 py-2 text-xs text-gray-300 leading-relaxed shadow-xl"
                   :style="`position:fixed;left:${x}px;top:${y}px;z-index:9999;width:260px`"
                   @click.outside="open = false" @keydown.escape.window="open = false">
                <div class="flex items-start justify-between gap-2 mb-0.5">
                  <p class="font-semibold text-mx-white">Pro koho je vhodné?</p>
                  <button @click="open = false" class="text-mx-orange hover:text-mx-white transition-colors flex-shrink-0 leading-none" aria-label="Zavřít">✕</button>
                </div>
                <p>Pro jezdce, kteří sami nezvládají denní údržbu motorky.<br /><strong>Zahrnuje:</strong> mazání řetězu, čištění vzduchového filtru, mytí motorky, kontrolu technického stavu, atd.</p>
              </div>
            </template>
          </div>
        </div>
      </div>
      {{-- Zapůjčení motorky --}}
      <div class="card-dark p-5 flex flex-col gap-4">
        <h4 class="text-xs font-bold uppercase tracking-widest text-mx-orange">Nemáte vlastní motorku?</h4>
        <div class="flex gap-3 items-start">
          <i class="fa-solid fa-motorcycle text-mx-orange flex-shrink-0 mt-0.5 text-sm"></i>
          <div>
            <p class="text-mx-white font-semibold text-sm">Zapůjčení pitbike YCF</p>
            <p class="text-gray-500 text-xs mt-0.5">Na celou dobu kempu. Nutno domluvit předem telefonicky na <a href="tel:+420704221663" class="text-mx-gold hover:underline">704 221 663</a>.</p>
            <p class="text-gray-400 text-xs font-semibold mt-1.5">4 500 Kč</p>
          </div>
        </div>
      </div>
    </div>

    <p class="text-center text-sm text-gray-400 mt-4">
      <a href="files/Storno_podminky_kempu.pdf" target="_blank" class="hover:text-mx-white transition-colors inline-flex items-center gap-1.5">
        <i class="fa-solid fa-file-pdf text-mx-orange"></i> Storno podmínky
      </a>
    </p>

  </div>
</section>

{{-- FAQ --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-8">Nejčastější dotazy</h2>
    </div>

    <div class="space-y-2" x-data="{ open: null }">

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq8' ? null : 'faq8'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Je program kempu vhodný i pro úplné začátečníky?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq8' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq8' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p class="mb-3">Ano, ALE ... Abychom zajistili nejlepší zážitek pro všechny účastníky, stanovili jsme podmínky pro účast:</p>
              <ul class="space-y-2">
                <li><span class="text-mx-orange font-bold">A)</span> Již jste se zúčastnil/a našeho kempu v minulých letech? Jste vítáni znovu.</li>
                <li><span class="text-mx-orange font-bold">B)</span> Jste nováček, ale máte zkušenosti s okruhovou jízdou v terénu? Je potřeba doložit jiné zkušenosti (např. účast na alespoň 2 závodech MX/Pitbike nebo absolvování programu <a href="{{ route('mx-go') }}" target="_blank" class="text-mx-gold hover:underline">MX-GO</a> min. 2×2,5h). <strong class="text-mx-white">Nezapočítává se jízda na "polňačce, v lese, za domem".</strong></li>
                <li><span class="text-mx-orange font-bold">C)</span> Nemáte žádné nebo minimální zkušenosti? Nevadí! Je ale povinné se zúčastnit <a href="{{ route('mxsoustredeni') }}" target="_blank" class="text-mx-gold hover:underline">MX soustředění</a> před kempem.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq1' ? null : 'faq1'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Co když bude špatné počasí?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq1' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq1' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Kemp se koná za každého počasí.<br>
              1) Pokud by z důvodu nepříznivého počasí nebylo možné jezdit, připravíme alternativní program (workshopy o údržbě motorek, závodní strategie apod.).<br>
              2) V případě extrémního počasí zajistíme ubytování pod pevnou střechou v blízkém areálu.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq2' ? null : 'faq2'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Co když nemáme vlastní motorku?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq2' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq2' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Nemáte vlastní motorku? Žádný problém! Provozujeme pitbike půjčovnu a motorku vám rádi zapůjčíme. Je nutné si zapůjčení domluvit předem telefonicky. Cena zápůjčky je 4 500 Kč na celou dobu kempu.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq3' ? null : 'faq3'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Co když nemáme jezdecké vybavení?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq3' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq3' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Pokud nemáte vlastní jezdecké vybavení, v omezené míře jsme schopni vám jej zapůjčit. Zavolejte nám co nejdříve, abychom ověřili dostupnost a vaši velikost. Doporučujeme si základní vybavení přivézt vlastní.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq4' ? null : 'faq4'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Mám si dovést vlastní benzín?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq4' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq4' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Ano — starost o pohonné hmoty je součástí závodníkovy přípravy. 10litrový kanistr bude dostatečný. Pokud chcete, lze po domluvě zajistit kompletní zásobu benzínu (s příplatkem).</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq5' ? null : 'faq5'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Chceme zůstat přes noc. Co budeme potřebovat?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq5' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq5' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Vezměte si vlastní stan, spacák, karimatku nebo jiný nocleh (např. karavan). K dispozici budou sprchy a večerní program. Doporučujeme přibalit teplejší oblečení pro večery.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq6' ? null : 'faq6'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Co zahrnuje základní cena kempu?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq6' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq6' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Cena zahrnuje kompletní denní program kempu, výuku jízdy, odborný dohled a doprovodný program. Také snídaně, obědy a pitný režim. Další služby (večeře, zapůjčení motorky, mechanik) nejsou v základní ceně zahrnuty.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq7' ? null : 'faq7'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Jaká je věková hranice pro účast?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq7' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq7' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Kemp je určen zejména dětem od 6 do 14 let. Mladší nebo starší děti pouze po domluvě s organizátorem, pokud splňují základní podmínky pro účast.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq9' ? null : 'faq9'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Můžeme se přijít podívat na děti během kempu?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq9' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq9' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p class="mb-2">Bohužel ne. Zkušenost ukázala, že po návštěvě rodičů se dětem hůř loučí, stýská se jim a narušuje to program. Z téhož důvodu nemají děti během kempu u sebe mobilní telefony.</p>
              <p>Před začátkem kempu založíme WhatsApp skupinu pro rodiče, kam průběžně posíláme fotky a krátká videa. V naléhavých případech jsme samozřejmě na telefonu.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === 'faq10' ? null : 'faq10'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> Co když zaplatím, ale nakonec se nemohu zúčastnit?</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === 'faq10' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'faq10' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Pokud se nemůžete zúčastnit, dejte nám to co nejdříve vědět. Napište na <a href="mailto:standa@pitarena.cz" class="text-mx-gold hover:underline">standa@pitarena.cz</a> nebo zavolejte na <a href="tel:+420704221663" class="text-mx-gold hover:underline">704 221 663</a>. Podrobnosti najdete v <a href="files/Storno_podminky_kempu.pdf" target="_blank" class="text-mx-gold hover:underline">storno podmínkách</a>.</p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <blockquote class="border-l-4 border-mx-orange pl-6 text-left mt-10 mb-6">
      <p class="text-gray-300 text-lg italic leading-relaxed">
        Máte jiný dotaz?<br>
        Napište nám, nebo zavolejte.
      </p>
    </blockquote>
    <div class="flex flex-col sm:flex-row gap-4">
      <a class="btn-primary" href="mailto:kemp@pitarena.cz">
        <i class="fa-solid fa-envelope"></i> kemp@pitarena.cz
      </a>
      <a class="btn-outline-white" href="tel:+420704221663">
        <i class="fa-solid fa-phone"></i> 704 221 663
      </a>
    </div>
  </div>
</section>

{{-- Weekly program accordion --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center mb-8" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-3">Orientační rozpis průběhu</h2>
      <p class="text-gray-400 text-sm">
        <i class="fa-solid fa-ban text-mx-orange mr-2"></i>
        Během kempu platí pro účastníky <span class="text-mx-orange font-semibold">zákaz používání mobilů, tabletů</span> atp.
      </p>
    </div>

    <div class="space-y-2" x-data="{ open: null }">

      @php
        $days = [
          ['id' => 'ned', 'label' => 'Neděle', 'items' => [
            ['time' => '15:00–17:00', 'text' => 'Sraz'],
            ['time' => null, 'text' => 'Představení — Uvítání'],
            ['time' => null, 'text' => 'Stavba stanů'],
            ['time' => null, 'text' => 'Večeře'],
            ['time' => null, 'text' => 'Umývárna'],
            ['time' => null, 'text' => 'Seznámení s pravidly'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'pon', 'label' => 'Pondělí', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček (+ příjezd těch, co nezůstávají přes noc)'],
            ['time' => null, 'text' => 'Snídaně'],
            ['time' => null, 'text' => 'Seznámení s programem, rozdělení do skupin, seznámení s motorkou a tratí'],
            ['time' => null, 'text' => 'Oběd'],
            ['time' => null, 'text' => 'Trénink ve skupinách'],
            ['time' => '17:00', 'text' => 'Odjezd těch, co nezůstávají přes noc'],
            ['time' => null, 'text' => 'Workshop první pomoc'],
            ['time' => null, 'text' => 'Seznámení s vlajkami používanými v motokrosu'],
            ['time' => null, 'text' => 'Večeře + Motokrosový kvíz'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'ute', 'label' => 'Úterý', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček (+ příjezd těch, co nezůstávají přes noc)'],
            ['time' => null, 'text' => 'Snídaně'],
            ['time' => null, 'text' => 'Technika na motorce, trénink techniky ve skupinkách'],
            ['time' => null, 'text' => 'Oběd — Odpočinek'],
            ['time' => null, 'text' => 'Opakování techniky ve skupinách'],
            ['time' => '17:00', 'text' => 'Odjezd těch, kteří nezůstávají přes noc'],
            ['time' => null, 'text' => 'Pokladová hra — cesta plná úkolů a hádanek'],
            ['time' => null, 'text' => 'Večeře — Volná zábava, deskové hry'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'str', 'label' => 'Středa', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček (+ příjezd těch, co nezůstávají přes noc)'],
            ['time' => null, 'text' => 'Snídaně'],
            ['time' => null, 'text' => 'Technika na motorce, rovnováha, prostřídání skupin'],
            ['time' => null, 'text' => 'Oběd — Odpočinek'],
            ['time' => null, 'text' => 'Opakování techniky ve skupinách'],
            ['time' => '17:00', 'text' => 'Odjezd těch, kteří nezůstávají přes noc'],
            ['time' => null, 'text' => 'Kolektivní hry — Večeře — Volná zábava'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'ctvr', 'label' => 'Čtvrtek', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček (+ příjezd těch, co nezůstávají přes noc)'],
            ['time' => null, 'text' => 'Snídaně'],
            ['time' => null, 'text' => 'Program sestaven trenéry podle aktuálních potřeb účastníků'],
            ['time' => null, 'text' => 'Oběd — Odpočinek — Opakování technik'],
            ['time' => '17:00', 'text' => 'Odjezd těch, kteří nezůstávají přes noc'],
            ['time' => null, 'text' => 'Pokladová hra — hledání svítících pokladů'],
            ['time' => null, 'text' => 'Večeře — Stezka odvahy'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'pat', 'label' => 'Pátek', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček (+ příjezd těch, co nezůstávají přes noc)'],
            ['time' => null, 'text' => 'Snídaně'],
            ['time' => null, 'text' => 'Tréninkové rozjížďky'],
            ['time' => null, 'text' => 'Oběd'],
            ['time' => null, 'text' => 'Závody — simulace kompletního závodu'],
            ['time' => '17:00', 'text' => 'Odjezd těch, kteří nezůstávají přes noc'],
            ['time' => null, 'text' => 'Anonymní dopis — Diskuze o programu'],
            ['time' => null, 'text' => 'Večeře — Umývárna'],
            ['time' => '22:00', 'text' => 'Večerka'],
          ]],
          ['id' => 'sob', 'label' => 'Sobota', 'items' => [
            ['time' => '07:00', 'text' => 'Budíček'],
            ['time' => null, 'text' => 'Snídaně — Úklid, balení'],
            ['time' => '10:00–11:00', 'text' => 'Vyhlášení tábora, certifikát o účasti'],
            ['time' => '11:00–12:00', 'text' => 'Rozloučení — Hurá domů!! 😃'],
          ]],
        ];
      @endphp

      @foreach($days as $day)
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === '{{ $day['id'] }}' ? null : '{{ $day['id'] }}'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-calendar text-mx-orange mr-2"></i> {{ $day['label'] }}</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === '{{ $day['id'] }}' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '{{ $day['id'] }}' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 border-t border-mx-gray2 pt-4">
              <ul class="space-y-1 text-gray-400 text-sm">
                @foreach($day['items'] as $item)
                <li class="flex gap-3">
                  @if($item['time'])
                  <span class="text-mx-orange font-semibold whitespace-nowrap">{{ $item['time'] }}</span>
                  @else
                  <span class="text-mx-gray2 flex-shrink-0">—</span>
                  @endif
                  <span>{{ $item['text'] }}</span>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

{{-- Gallery --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">

    @php
      $heading          = 'Fotky z předešlých ročníků';
      $baseBig          = 'images/kemp/big/';
      $baseSmall        = 'images/kemp/small/';
      $defaultAlt       = 'Galerie - Kemp Pitarena.cz';
      $altFromFilename  = true;
      $imgWidth         = 886;
      $imgHeight        = 668;
      $photos = [
        // 2025
        ['big' => 'kemp_2025 (1).jpg',  'small' => 'kemp_2025 (1).webp'],
        ['big' => 'kemp_2025 (1).jpeg',  'small' => 'kemp_2025 (1).jpeg'],
        ['big' => 'kemp_2025 (2).jpg',  'small' => 'kemp_2025 (2).webp'],
        ['big' => 'kemp_2025 (3).jpg',  'small' => 'kemp_2025 (3).webp'],
        ['big' => 'kemp_2025 (4).jpg',  'small' => 'kemp_2025 (4).webp'],
        ['big' => 'kemp_2025 (5).jpg',  'small' => 'kemp_2025 (5).webp'],
        ['big' => 'kemp_2025 (6).jpg',  'small' => 'kemp_2025 (6).webp'],
        ['big' => 'kemp_2025 (7).jpg',  'small' => 'kemp_2025 (7).webp'],
        ['big' => 'kemp_2025 (8).jpg',  'small' => 'kemp_2025 (8).webp'],
        ['big' => 'kemp_2025 (9).jpg',  'small' => 'kemp_2025 (9).webp'],
        ['big' => 'kemp_2025 (10).jpg',  'small' => 'kemp_2025 (10).webp'],
        ['big' => 'kemp_2025 (11).jpg',  'small' => 'kemp_2025 (11).webp'],
        ['big' => 'kemp_2025 (12).jpg',  'small' => 'kemp_2025 (12).webp'],
        ['big' => 'kemp_2025 (13).jpg',  'small' => 'kemp_2025 (13).webp'],
        ['big' => 'kemp_2025 (14).jpg',  'small' => 'kemp_2025 (14).webp'],
        ['big' => 'kemp_2025 (15).jpg',  'small' => 'kemp_2025 (15).webp'],
        ['big' => 'kemp_2025 (16).jpg',  'small' => 'kemp_2025 (16).webp'],
        ['big' => 'kemp_2025 (17).jpg',  'small' => 'kemp_2025 (17).webp'],
        ['big' => 'kemp_2025 (18).jpg',  'small' => 'kemp_2025 (18).webp'],
        ['big' => 'kemp_2025 (19).jpg',  'small' => 'kemp_2025 (19).webp'],
        ['big' => 'kemp_2025 (20).jpg',  'small' => 'kemp_2025 (20).webp'],
        ['big' => 'kemp_2025 (21).jpg',  'small' => 'kemp_2025 (21).webp'],
        ['big' => 'kemp_2025 (22).jpg',  'small' => 'kemp_2025 (22).webp'],
        ['big' => 'kemp_2025 (23).jpg',  'small' => 'kemp_2025 (23).webp'],
        ['big' => 'kemp_2025 (24).jpg',  'small' => 'kemp_2025 (24).webp'],
        ['big' => 'kemp_2025 (25).jpg',  'small' => 'kemp_2025 (25).webp'],
        ['big' => 'kemp_2025 (26).jpg',  'small' => 'kemp_2025 (26).webp'],
        ['big' => 'kemp_2025 (27).jpg',  'small' => 'kemp_2025 (27).webp'],
        ['big' => 'kemp_2025 (28).jpg',  'small' => 'kemp_2025 (28).webp'],
        ['big' => 'kemp_2025 (29).jpg',  'small' => 'kemp_2025 (29).webp'],
        // 2024
        ['big' => 'pitbike_kemp_2024 (1).jpg',  'small' => 'pitbike_kemp_2024 (1).webp'],
        ['big' => 'pitbike_kemp_2024 (2).jpg',  'small' => 'pitbike_kemp_2024 (2).webp'],
        ['big' => 'pitbike_kemp_2024 (3).jpg',  'small' => 'pitbike_kemp_2024 (3).webp'],
        ['big' => 'pitbike_kemp_2024 (4).jpg',  'small' => 'pitbike_kemp_2024 (4).webp'],
        ['big' => 'pitbike_kemp_2024 (5).jpg',  'small' => 'pitbike_kemp_2024 (5).webp'],
        ['big' => 'pitbike_kemp_2024 (6).jpg',  'small' => 'pitbike_kemp_2024 (6).webp'],
        ['big' => 'pitbike_kemp_2024 (7).jpg',  'small' => 'pitbike_kemp_2024 (7).webp'],
        ['big' => 'pitbike_kemp_2024 (8).jpg',  'small' => 'pitbike_kemp_2024 (8).webp'],
        ['big' => 'pitbike_kemp_2024 (9).jpg',  'small' => 'pitbike_kemp_2024 (9).webp'],
        ['big' => 'pitbike_kemp_2024 (10).jpg', 'small' => 'pitbike_kemp_2024 (10).webp'],
        ['big' => 'pitbike_kemp_2024 (11).jpg', 'small' => 'pitbike_kemp_2024 (11).webp'],
        ['big' => 'pitbike_kemp_2024 (12).jpg', 'small' => 'pitbike_kemp_2024 (12).webp'],
        ['big' => 'pitbike_kemp_2024 (14).jpg', 'small' => 'pitbike_kemp_2024 (14).webp'],
        // 2023
        ['big' => 'pitbike_kemp_pitarena_cz (2).jpeg',  'small' => 'pitbike_kemp_pitarena_cz (2).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (3).jpeg',  'small' => 'pitbike_kemp_pitarena_cz (3).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (6).jpeg',  'small' => 'pitbike_kemp_pitarena_cz (6).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (9).jpeg',  'small' => 'pitbike_kemp_pitarena_cz (9).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (11).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (11).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (12).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (12).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (13).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (13).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (15).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (15).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (16).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (16).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (18).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (18).webp'],
        ['big' => 'pitbike_kemp_pitarena_cz (19).jpeg', 'small' => 'pitbike_kemp_pitarena_cz (19).webp'],
      ];
      $makeAlt = function (string $filename) use ($defaultAlt) {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = str_replace('_', ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $defaultAlt . ': ' . trim($name);
      };
    @endphp

    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-8">{{ $heading }}</h2>
    </div>

    <div class="swiper max-w-3xl mx-auto" data-lg-gallery
         data-loop="true" data-autoplay="4500">
      <div class="swiper-wrapper">
        @foreach ($photos as $i => $__p)
        @php
          $href = asset($baseBig   . $__p['big']);
          $src  = asset($baseSmall . $__p['small']);
          $alt  = $altFromFilename ? $makeAlt($__p['small']) : $defaultAlt;
          $isFirst = $i === 0;
        @endphp
        <div class="swiper-slide">
          <a class="lg-item block" href="{{ $href }}">
            <img
              src="{{ $src }}"
              loading="{{ $isFirst ? 'eager' : 'lazy' }}"
              decoding="async"
              alt="{{ $alt }}"
              width="{{ $imgWidth }}"
              height="{{ $imgHeight }}"
              class="w-full rounded-lg object-cover"
            />
          </a>
        </div>
        @endforeach
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-pagination"></div>
    </div>

    <div class="text-center mt-8">
      <p class="text-gray-400 mb-3">Líbí se ti kemp? Sdílej ho s ostatními jezdci.</p>
      <div class="fb-share-button" data-href="{{ request()->url() }}" data-layout="button_count" data-size="large"></div>
    </div>

  </div>
</section>

{{-- Závěrečné CTA --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center mb-6" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-2">Získejte víc</h2>
      <p class="text-mx-orange font-semibold">Zajistěte si své místo na pitbike motokrosovém kempu a vyberte si táborové tričko.</p>
    </div>

    <img src="{{ asset('images/kemp/KEMP_2026_BONUSY.webp') }}" loading="lazy" alt="bonus program pitbike/MX kempu"
         width="1000" height="324" class="w-full rounded-lg mb-6" data-aos="fade-up"/>

    {{-- Bonusy --}}
    <div class="card-dark p-5 mb-6 bg-mx-orange/5 border-mx-orange/20" data-aos="fade-up">
      <ul class="space-y-3 text-gray-400 text-sm">
        <li class="flex gap-3 items-center">
          <i class="fa-solid fa-check text-mx-orange flex-shrink-0"></i>
          <span>Překvapení neprozradíme :)</span>
        </li>
        <li class="flex gap-3 items-center">
          <i class="fa-solid fa-check text-mx-orange flex-shrink-0"></i>
          <span>Soutěžit se bude o věcné ceny.</span>
        </li>
      </ul>
    </div>

    {{-- T-shirt — uvítací dárek --}}
    <div class="card-dark p-5 mb-8" data-aos="fade-up">
      <p class="hero-eyebrow mb-4">UVÍTACÍ DÁREK</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
        <div>
          <img src="{{ asset('/images/kemp/small/pitbike_kemp_2024 (13).webp') }}" loading="lazy"
               alt="Tričko s logem kempu" class="w-full rounded-lg"/>
        </div>
        <div>
          <h4 class="text-mx-white font-semibold mb-3">Uvítací dárek pro každého účastníka kempu</h4>
          <p class="text-gray-400 text-sm leading-relaxed mb-3">Každý, kdo se zaregistruje na náš letní pitbike motokrosový kemp, obdrží na uvítanou originální tričko s logem kempu.</p>
          <ul class="space-y-2 text-gray-400 text-sm mb-3">
            <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i> Při objednání rezervačního poukazu si vyberte velikost trička.</li>
            <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i> Tričko dostanete při příjezdu.</li>
          </ul>
          <button class="open-modal text-mx-orange hover:underline text-sm">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Tabulka velikostí
          </button>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4" data-aos="zoom-in" data-aos-delay="150">
      <a class="btn-primary" href="#koupit">Zajistit místo pro mé dítě</a>
      <a class="btn-outline" href="files/Storno_podminky_kempu.pdf" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Storno podmínky
      </a>
    </div>
  </div>
</section>

{{-- SimpleShop forma --}}
<section id="koupit" class="section bg-mx-dark">
  <div class="page-container text-center max-w-3xl">
    <div data-aos="fade-up">
      <p class="hero-eyebrow mb-3">REGISTRACE</p>
      <h2 class="section-title mb-8">Místo na kempu zajistíte zde</h2>
    </div>
    <!-- www.SimpleShop.cz form#131202 start -->
    <div class="bg-white max-w-3xl mx-auto rounded-lg shadow-2xl px-4 py-6 sm:px-8 sm:py-8">
      <div data-SimpleShopForm="5Qqrw"><div>Prodejní formulář je vytvořen v systému <a href="https://www.simpleshop.cz/?utm_source=simpleshop&utm_medium=form&utm_campaign=49201" target="_blank" class="text-mx-gold hover:underline">SimpleShop.cz</a>.</div></div>
    </div>
    <script>
    (function(i, s, o, g, r, a, m){
      i[r] = i[r] || function(){
      (i[r].q = i[r].q || []).push(arguments)
      }, i[r].l = 1 * new Date();
      a = s.createElement(o),
      m = s.getElementsByTagName(o)[0];
      a.async = 1;
      a.src = g;
      m.parentNode.insertBefore(a, m)
    })(window, document, "script", "https://form.simpleshop.cz/prj/js/SimpleShopService.js", "sss");
    sss("createForm", "5Qqrw");
    </script>
    <!-- www.SimpleShop.cz form#131202 end -->
  </div>
</section>

{{-- Co nezapomenout --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center mb-6" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="section-title mb-4">Co nezapomenout</h2>
      <a class="btn-primary btn-sm inline-flex items-center gap-2"
         href="{{ asset('/files/Seznam Veci pro Pitbike Kemp Pitarena 2024.jpg') }}"
         download="Seznam Veci pro Pitbike Kemp Pitarena 2024.jpg">
        <i class="fa-solid fa-image"></i> Stáhnout seznam v JPG
      </a>
    </div>
    <a href="{{ asset('images/seznam_veci.webp') }}" target="_blank">
      <img src="{{ asset('images/seznam_veci.webp') }}" loading="lazy" alt="Seznam věcí na pitbike kemp"
           width="890" height="1168" class="w-full rounded-lg"/>
    </a>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

{{-- Sticky CTA lišta --}}
<div id="kemp-sticky-bar" aria-hidden="true" role="complementary" aria-label="Rychlá rezervace kempu">
  <div class="kemp-sticky-bar__inner">
    <p class="kemp-sticky-bar__text">
      Pitbike&nbsp;Kemp&nbsp;2026 &mdash; předprodej zahájen
      <span id="kemp-bar-spots" class="kemp-bar-spots"></span>
    </p>
    <a id="kemp-sticky-btn" class="kemp-bar-btn" href="#koupit">Zajistit místo</a>
  </div>
</div>

{{-- Modal pro tabulku velikostí --}}
<div id="sizeChartModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);justify-content:center;align-items:center;z-index:1000;">
  <div style="background:#1f2937;padding:24px;border-radius:8px;max-width:818px;width:90%;text-align:center;position:relative;">
    <button id="closeModal" style="position:absolute;top:10px;right:14px;border:none;background:none;font-size:1.5rem;cursor:pointer;color:#fff;">&times;</button>
    <h5 class="text-mx-white font-semibold mb-4">Tabulka velikostí triček</h5>
    <img src="{{ asset('files/tabulka_velikosti.png') }}" loading="lazy" alt="Tabulka velikostí triček" style="max-width:100%;height:auto;">
  </div>
</div>

@endsection

@section('scripts')

{{-- Modal pro tabulku velikostí --}}
<script>
  const openModalLinks = document.querySelectorAll('.open-modal');
  const closeModal = document.querySelector('#closeModal');
  const modal = document.getElementById('sizeChartModal');

  openModalLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      modal.style.display = 'flex';
    });
  });

  closeModal.addEventListener('click', function () {
    modal.style.display = 'none';
  });

  window.addEventListener('click', function (e) {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>

{{-- Sticky CTA lišta --}}
<script>
(function () {
  var bar     = document.getElementById('kemp-sticky-bar');
  var trigger = document.getElementById('predprodej-trigger');
  var form    = document.getElementById('koupit');
  if (!bar || !trigger || !form) return;

  var triggerPassed = false;
  var formVisible   = false;
  var pulseDone     = false;

  function update() {
    var show = triggerPassed && !formVisible;
    bar.classList.toggle('is-visible', show);
    bar.setAttribute('aria-hidden', show ? 'false' : 'true');
    if (show && !pulseDone) {
      pulseDone = true;
      var btn = document.getElementById('kemp-sticky-btn');
      if (btn) {
        btn.classList.add('kemp-sticky-bar__btn--pulse');
        btn.addEventListener('animationend', function () {
          btn.classList.remove('kemp-sticky-bar__btn--pulse');
        }, { once: true });
      }
    }
  }

  new IntersectionObserver(function (entries) {
    var e = entries[0];
    if (e.isIntersecting) {
      triggerPassed = true;
    } else {
      triggerPassed = e.boundingClientRect.top < 0;
    }
    update();
  }, { threshold: 0 }).observe(trigger);

  new IntersectionObserver(function (entries) {
    formVisible = entries[0].isIntersecting;
    update();
  }, { threshold: 0 }).observe(form);
}());
</script>

{{-- Zbývající volná místa ze SimpleShop --}}
<script>
(function () {
  var THRESHOLD = 10; // pod tímto počtem zobrazíme přesné číslo

  // Badge pro hero (pill s bg — čitelné na fotce)
  function renderHeroBadge(el, count) {
    if (!el || isNaN(count)) return;
    if (count <= 0) {
      el.innerHTML = '<span style="display:inline-block;background:#ce1212;color:#fff;font-weight:700;font-size:0.7rem;padding:1px 8px;border-radius:999px;letter-spacing:0.04em;">VYPRODÁNO</span>';
    } else if (count <= THRESHOLD) {
      el.innerHTML = '<span style="display:inline-block;background:#ce1212;color:#fff;font-weight:700;font-size:0.7rem;padding:1px 8px;border-radius:999px;letter-spacing:0.04em;">Posledních\u00a0' + count + '\u00a0míst!</span>';
    } else {
      el.innerHTML = '<span style="display:inline-block;background:rgba(2,251,170,0.15);color:#02fbaa;font-weight:700;font-size:0.7rem;padding:1px 8px;border-radius:999px;border:1px solid rgba(2,251,170,0.35);">&#10003;\u00a0Volná místa</span>';
    }
  }

  // Badge pro tmavé karty (barevný text, bez bg)
  function renderBadge(el, count) {
    if (!el || isNaN(count)) return;
    if (count <= 0) {
      el.innerHTML = '<span style="color:#ff0000;font-weight:700;">VYPRODÁNO</span>';
    } else if (count <= THRESHOLD) {
      el.innerHTML = '<span style="color:#ff0000;font-weight:700;">Posledních\u00a0' + count + '\u00a0míst!</span>';
    } else {
      el.innerHTML = '<span style="color:#02fbaa;font-weight:600;">&#10003;\u00a0Volná místa</span>';
    }
  }

  fetch('/api/kemp-spots')
    .then(function (r) { return r.ok ? r.json() : null; })
    .then(function (data) {
      if (!data) return;
      // Hero — pill badge
      renderHeroBadge(document.getElementById('hero-spots-0'), data[0]);
      renderHeroBadge(document.getElementById('hero-spots-1'), data[1]);
      // Termíny kartička — text badge
      renderBadge(document.getElementById('terminy-spots-0'), data[0]);
      renderBadge(document.getElementById('terminy-spots-1'), data[1]);

      // Sticky bar — celkový počet
      var spotsEl = document.getElementById('kemp-bar-spots');
      if (spotsEl) {
        var total = (parseInt(data[0], 10) || 0) + (parseInt(data[1], 10) || 0);
        if (total <= 0) {
          var stickyBar = document.getElementById('kemp-sticky-bar');
          if (stickyBar) { stickyBar.style.display = 'none'; }
        } else if (total <= THRESHOLD) {
          spotsEl.textContent = '⚠ Posledních ' + total + ' míst celkem!';
        }
        // nad prahem sticky bar nic nezobrazuje (nechceme číslo)
      }
    })
    .catch(function () {});
})();
</script>

@endsection

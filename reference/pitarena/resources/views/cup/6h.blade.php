@extends('layout')
<?php
    $title = null;
    foreach ($sitemaps as $sitemap) {
        if ($sitemap['slug'] == 'cup/6h') {
            $title = $sitemap['title'];
            $description = $sitemap['description'];
        }
    }
    if (!$title) {
        $title = 'Fechtl & Pitbike 6H Cup - Vytrvalostní závod týmů';
        $description = 'Šestihodinový vytrvalostní závod týmů na pitbike trati v PITARENA Pravice. 5. září 2026. Sestav tým, zaregistruj se a závoď!';
    }
?>

@section('title', $title)
@section('meta_description', $description)

@section('og')
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Fechtl & Pitbike 6H Cup - Vytrvalostní závod týmů">
  <meta property="og:description" content="Šestihodinový vytrvalostní závod týmů na pitbike trati v PITARENA Pravice. 5. září 2026.">
  <meta property="og:image" content="https://pitarena.cz/images/cup/6h_cup_text_2.png">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Fechtl & Pitbike 6H Cup - Vytrvalostní závod týmů">
  <meta name="twitter:description" content="Šestihodinový vytrvalostní závod týmů na pitbike trati v PITARENA Pravice. 5. září 2026.">
  <meta name="twitter:image" content="https://pitarena.cz/images/cup/6h_cup_text_2.png">
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
        <span itemprop="name">6H Cup</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Load Facebook SDK for JavaScript --}}
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

{{-- Parallax hero --}}
<section class="parallax-hero" style="--hero-img: url('{{ asset('images/cup/6h_cup_2.webp') }}')">
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white mb-2">
        Fechtl &amp; Pitbike 6H Cup
      </h1>
    </div>
    <p class="text-2xl font-semibold text-mx-orange" data-aos="fade-up" data-aos-delay="100">5. 9. 2026</p>
  </div>
</section>

{{-- Úvodní pitch --}}
<section class="section-sm bg-mx-black text-center">
  <div class="page-container max-w-2xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-4">Šestihodinový vytrvalostní závod týmů</h2>
    </div>
    <p class="text-gray-300 text-base leading-relaxed mb-6">
      Sestav tým 1&ndash;3 jezdců, osedlej jednu motorku a závoď 6 hodin v kuse!<br>
      Sobota <strong class="text-mx-white">5. září 2026</strong> &middot; PITARENA Pravice
    </p>
    <div data-aos="zoom-out" data-aos-duration="600" data-aos-delay="200">
      <a class="btn-primary" href="#registrace">Registrovat tým</a>
    </div>
  </div>
</section>

{{-- Týmy a startovné --}}
<section class="section-sm bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Týmy a startovné</h2>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div class="card-dark p-6" data-aos="fade-right" data-aos-duration="600">
        <h3 class="text-lg font-semibold text-mx-white mb-4">Složení týmu:</h3>
        <ul class="space-y-2">
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span><strong class="text-mx-white">1&ndash;3 jezdci</strong> na jeden tým</span></li>
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span><strong class="text-mx-white">1 motocykl</strong> na tým (celý závod se jede na jednom stroji)</span></li>
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span>Motocykl musí odpovídat zvolené kategorii</span></li>
        </ul>
        <p class="mt-4 text-sm text-mx-white font-semibold">Maximální kapacita závodu: 60 týmů</p>
      </div>
      <div class="card-dark p-6" data-aos="fade-left" data-aos-duration="600">
        <h3 class="text-lg font-semibold text-mx-white mb-4">Startovné za tým:</h3>
        <ul class="space-y-2">
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span><strong class="text-mx-white">500 Kč</strong> &ndash; předregistrace online <strong class="text-mx-white">do 15. 8. 2026</strong></span></li>
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span><strong class="text-mx-white">1 000 Kč</strong> &ndash; doplatek na místě v den závodu</span></li>
          <li class="flex gap-2 text-sm text-gray-300"><i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i><span>celkem 1 500 Kč / tým</span></li>
        </ul>
        <p class="mt-4 text-xs text-gray-400 leading-relaxed">
          Pokud nebude do 15. 8. přihlášeno minimálně 30 týmů, závod může být zrušen &ndash; v&nbsp;takovém případě budou vráceny veškeré platby za předregistraci.<br>
          Po 15. 8. jsou možné pouze doplňkové registrace do konce srpna (pokud bude volná kapacita).
        </p>
      </div>
    </div>
  </div>
</section>

{{-- Kategorie --}}
<section class="section-sm bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-3">Kategorie</h2>
    </div>
    <p class="text-center text-gray-400 text-sm mb-8">Všechny kategorie startují společně ale hodnotí se zvlášť. Kategorie se liší technickými parametry motocyklu.</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="card-dark p-5" data-aos="fade-up" data-aos-delay="0">
        <p class="text-base font-bold text-mx-white mb-1"><i class="fa-solid fa-motorcycle text-mx-orange mr-2"></i>Klasik</p>
        <a href="https://slovackyfechtlcup.cz/pravidla/klasik/" target="_blank" class="text-sm text-mx-gold underline">Detailní parametry Klasik</a>
      </div>
      <div class="card-dark p-5" data-aos="fade-up" data-aos-delay="100">
        <p class="text-base font-bold text-mx-white mb-1"><i class="fa-solid fa-motorcycle text-mx-orange mr-2"></i>Ořežplech</p>
        <a href="https://slovackyfechtlcup.cz/pravidla/orezplech/" target="_blank" class="text-sm text-mx-gold underline">Detailní parametry Ořežplech</a>
      </div>
      <div class="card-dark p-5" data-aos="fade-up" data-aos-delay="200">
        <p class="text-base font-bold text-mx-white mb-1"><i class="fa-solid fa-motorcycle text-mx-orange mr-2"></i>Speciál</p>
        <a href="https://slovackyfechtlcup.cz/pravidla/special/" target="_blank" class="text-sm text-mx-gold underline">Detailní parametry Speciál</a>
      </div>
      <div class="card-dark p-5" data-aos="fade-up" data-aos-delay="300">
        <p class="text-base font-bold text-mx-white mb-1"><i class="fa-solid fa-motorcycle text-mx-orange mr-2"></i>Pitbike</p>
        <ul class="space-y-1">
          <li class="text-xs text-gray-300">Motor max. 212 ccm</li>
          <li class="text-xs text-gray-300">Kola libovolná</li>
          <li class="text-xs text-gray-300">Musí se jednat o pitbike motocykl</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Trať --}}
<section class="section-sm bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-4">Trať</h2>
    </div>
    <p class="text-gray-400 text-sm mb-6">
      Základ tvoří klasická trať PitArény, která bude pro tento závod prodloužena o další úsek.
    </p>
    <div class="max-w-2xl mx-auto" data-lg-gallery>
      <a class="lg-item block relative" href="{{ asset('images/trat/plan_6h.webp') }}" data-lg-size="1920-1080">
        <img src="{{ asset('images/trat/plan_6h_small.webp') }}" loading="lazy" alt="Plán trati 6H Cup" class="w-full rounded-lg">
        <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
          <i class="fa-solid fa-magnifying-glass text-3xl text-mx-white"></i>
        </div>
      </a>
      <p class="mt-2 text-xs text-gray-400 italic">* Orientační náčrt &ndash; skutečné rozvržení se může lišit.</p>
    </div>
    <p class="mt-6">
      <a class="btn-outline btn-sm inline-flex items-center gap-2" href="https://maps.app.goo.gl/34NtD4qbedVFJSR19" target="_blank">
        <i class="fa-solid fa-map-marker-alt"></i> Navigovat na místo závodu
      </a>
    </p>
  </div>
</section>

{{-- Harmonogram --}}
<section class="section-sm bg-mx-black">
  <div class="page-container max-w-2xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-6">Harmonogram dne</h2>
    </div>
    <div data-aos="fade-up" data-aos-duration="500">
      <div class="overflow-x-auto card-dark">
        <table class="w-full text-sm">
          <tbody>
            @foreach([
              ['08:00–09:00', 'Prezence a přejímka'],
              ['09:30', 'Povinná rozprava (pro všechny jezdce)'],
              ['10:00', 'Zaváděcí kola'],
              ['10:30', 'Hromadný start závodu'],
              ['16:30', 'Konec závodu'],
              ['17:00', 'Vyhlášení výsledků'],
            ] as [$time, $desc])
            <tr class="border-b border-mx-gray2">
              <td class="px-4 py-3 font-bold text-mx-orange w-32">{{ $time }}</td>
              <td class="px-4 py-3 text-gray-300">{{ $desc }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <p class="mt-4 text-gray-400 text-sm">Občerstvení bude zajištěno po celou dobu závodu.</p>
      <h3 class="text-lg font-semibold text-mx-white mt-6 mb-2">Možnost příjezdu den před závodem.</h3>
      <ul class="space-y-1">
        @foreach(['Možnost přenocování ve stanu nebo karavanu.', 'K dispozici je pouze plocha &ndash; nezajišťujeme elektřinu, vodu ani další zázemí.', 'Není nutné se předem hlásit.'] as $item)
        <li class="flex gap-2 text-sm text-gray-400">
          <i class="fa-solid fa-circle-dot text-mx-orange mt-0.5 flex-shrink-0 text-xs"></i>
          <span>{!! $item !!}</span>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>

{{-- Pravidla závodu – accordion --}}
<section class="section-sm bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">
        Organizační pravidla závodu
      </h2>
    </div>
    <div x-data="{ open: null }" class="space-y-2" data-aos="fade-up">

      @php
      $rules6h = [
        ['id' => 'r1', 'icon' => 'fa-gear', 'title' => 'Motocykl a servis', 'items' => [
          'Každý tým startuje na 1 společném motocyklu.',
          'Rám motocyklu musí po celou dobu závodu zůstat původní.',
          'Motocykl může být během závodu opravován bez omezení (běžný servis a výměna dílů).',
          'Je povolen 1 náhradní motor na tým.',
          'Náhradní motor musí být při registraci předložen a označen pořadatelem.',
        ]],
        ['id' => 'r2', 'icon' => 'fa-users', 'title' => 'Střídání jezdců', 'items' => [
          'Maximálně 3 jezdci na tým.',
          'Střídání jezdců je libovolné a záleží na úvaze týmu.',
          'Místo střídání jezdců bude upřesněno v den závodu při rozpravě.',
        ]],
        ['id' => 'r3', 'icon' => 'fa-shield-halved', 'title' => 'Povinná výstroj jezdců', 'items' => [
          '<strong>Reflexní vesta</strong> (žlutá nebo oranžová) &ndash; povinná během závodu.',
          'Ochranná motokrosová / motocyklová <strong>přilba</strong>.',
          'Ochranné <strong>brýle</strong> nebo jiná adekvátní ochrana zraku.',
          'Doporučena je kompletní motokrosová výstroj (pevné boty, rukavice, chrániče kolen, loktů a páteře).',
          'Jezdec musí být vybaven tak, aby byla zajištěna jeho vlastní bezpečnost i bezpečnost ostatních účastníků závodu.',
        ]],
        ['id' => 'r4', 'icon' => 'fa-clipboard-check', 'title' => 'Přejímka před závodem', 'items' => [
          'Přejímka proběhne před startem závodu.',
          'Kontrola zařazení do kategorie (kubatura, typ motocyklu).',
          'Kontrola bezpečnosti (zejména funkčnost brzd).',
        ]],
        ['id' => 'r5', 'icon' => 'fa-wrench', 'title' => 'Technická kontrola po závodě', 'items' => [
          'Po skončení závodu se první tři týmy v kategorii Fechtl dostaví ke kontrole k technickému komisaři.',
          '<strong>Klasik:</strong> proběhne rozborka.',
          '<strong>Ořežplech a Speciál:</strong> proběhne kontrola průměru válce, zdvihu a kliky.',
        ]],
        ['id' => 'r6', 'icon' => 'fa-hammer', 'title' => 'Mechanici', 'items' => [
          'Mechanici jsou povoleni.',
        ]],
        ['id' => 'r7', 'icon' => 'fa-droplet', 'title' => 'Tankování', 'items' => [
          'Tankování je povoleno výhradně v tankovacím koridoru.',
          'Tankování mimo vyhrazený prostor není povoleno.',
          'Každý tým musí mít při tankování malý hasicí sprej.',
          'V depu musí mít tým k dispozici větší hasicí přístroj.',
        ]],
        ['id' => 'r8', 'icon' => 'fa-map-marker-alt', 'title' => 'Depo', 'items' => [
          'Vyhrazený prostor pro týmy v depu.',
          'Oddělené od závodní trati.',
        ]],
        ['id' => 'r9', 'icon' => 'fa-clock', 'title' => 'Časomíra', 'items' => [
          'Bude zajištěna profesionální časomíra.',
          'Motocykly budou označeny čipem (čip = identifikace motocyklu/týmu).',
        ]],
      ];
      @endphp

      @foreach ($rules6h as $rule)
      <div class="card-dark">
        <button class="w-full flex justify-between items-center p-4 text-left gap-4"
                @click="open = open === '{{ $rule['id'] }}' ? null : '{{ $rule['id'] }}'">
          <span class="font-medium text-mx-white flex items-center gap-2">
            <i class="fa-solid {{ $rule['icon'] }} text-mx-orange"></i> {{ $rule['title'] }}
          </span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open === '{{ $rule['id'] }}' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '{{ $rule['id'] }}' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-4 pb-4">
              <ul class="space-y-2">
                @foreach ($rule['items'] as $item)
                <li class="flex gap-2 text-sm text-gray-300">
                  <i class="fa-solid fa-circle-dot text-mx-orange mt-0.5 flex-shrink-0 text-xs"></i>
                  <span>{!! $item !!}</span>
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

{{-- Registrace – SimpleShop --}}
<section id="registrace" class="section bg-mx-dark">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-3">Registrace týmu</h2>
    </div>
    <p class="text-center text-gray-400 text-sm mb-4">Zaregistrujte svůj tým včas.</p>
    <p class="text-center text-sm text-gray-300 mb-8">
      <strong class="text-mx-white">Předregistrace do 15. 8. 2026.</strong> Konání závodu je podmíněno předregistrací minimálně 30 týmů. Po tomto datu pouze doplňkové registrace do konce srpna (dle kapacity).
    </p>
    <!-- www.SimpleShop.cz form#141728 start -->
    <div class="bg-white max-w-3xl mx-auto rounded-lg shadow-2xl px-4 py-6 sm:px-8 sm:py-8">
      <div data-SimpleShopForm="PrNRK"><div>Prodejní formulář je vytvořen v systému <a href="https://www.simpleshop.cz/?utm_source=simpleshop&utm_medium=form&utm_campaign=49201" target="_blank">SimpleShop.cz</a>.</div></div>
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
    sss("createForm", "PrNRK");
    </script>
    <!-- www.SimpleShop.cz form#141728 end -->
  </div>
</section>

{{-- Kontakt --}}
<section class="section-sm bg-mx-black">
  <div class="page-container max-w-xl">
    <blockquote class="border-l-4 border-mx-orange pl-6">
      <p class="text-lg text-mx-white font-semibold mb-4">
        Máte dotaz k 6H Cupu?<br>
        Zavolejte Davidovi.
      </p>
      <a class="btn-outline btn-sm inline-flex items-center gap-2" href="tel:+420721857719">
        <i class="fa-solid fa-phone"></i> 721 857 719
      </a>
    </blockquote>
  </div>
</section>

{{-- FB Share --}}
<section class="section-sm bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <p class="text-gray-300 mb-4">Pozvi kámoše do týmu &ndash; sdílej závod na Facebooku.</p>
    <div class="fb-share-button" data-href="{{ request()->url() }}" data-layout="button_count" data-size="large"></div>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

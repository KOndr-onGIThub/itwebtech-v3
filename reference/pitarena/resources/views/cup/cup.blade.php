@extends('layout')
<?php
    $title = null;
    foreach ($sitemaps as $sitemap) {
        if ($sitemap['slug'] == request()->path()) {
            $title = $sitemap['title'];
            $description = $sitemap['description'];
        }
    }
    if (!$title) {
        $title = 'Pitbike závody - YCF CUP';
        $description = 'Zažij adrenalin a soutěžte v Pitbike závodech s YCF Cup. Zjisti více o kategoriích, pravidlech a termínech závodů.';
    }
?>

@section('title', $title)
@section('meta_description', $description)

@section('og')
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike závody - YCF CUP">
  <meta property="og:description" content="Zjisti více o kategoriích, pravidlech a termínech závodů.">
  <meta property="og:image" content="https://pitarena.cz/images/cup/YCF_CUP.png">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Pitbike závody - YCF CUP">
  <meta name="twitter:description" content="Zjisti více o kategoriích, pravidlech a termínech závodů.">
  <meta name="twitter:image" content="https://pitarena.cz/images/cup/YCF_CUP.png">
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
        <span itemprop="name">Závody</span>
        <meta itemprop="position" content="2"/>
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
<section class="parallax-hero" style="--hero-img: url('{{ asset('images/cup/YCF_CUP.webp') }}')">
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white">
        Pitbike závody – YCF Cup
      </h1>
    </div>
  </div>
</section>

{{-- Intro --}}
<section class="section-sm bg-mx-black">
  <div class="page-container text-center">
    <p class="text-gray-300 text-lg leading-relaxed" data-aos="fade-up">
      Zažij adrenalin na profesionální úrovni.<br>
      Připoj se k závodníkům všech věkových kategorií a staň se součástí Pitbike závodů u nás v Pitareně!
    </p>
  </div>
</section>

{{-- Proč se účastnit --}}
<section class="section bg-mx-black">
  <div class="page-container text-center">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl lg:text-3xl font-bold text-mx-white mb-4">Proč se účastnit Pitbike závodů?</h2>
    </div>
    <p class="text-gray-300 text-base leading-relaxed max-w-2xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
      Pitbike závody v PitAréně jsou o adrenalinu, výzvách a nezapomenutelných zážitcích.
      Ať už jsi začátečník nebo zkušený jezdec, na naší trati najdeš vždy nové výzvy a spoustu zábavy!
    </p>
    <div data-aos="zoom-in" data-aos-delay="200">
      <a class="btn-primary" href="{{ route('registrace-cup.index') }}">Chci závodit</a>
    </div>
  </div>
</section>

{{-- Galerie –– Swiper + LightGallery --}}
<section class="section-sm bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Pitbike závody v obrazech</h2>
    </div>
    <div class="swiper" data-lg-gallery
         data-loop="true" data-autoplay="3500" data-space-between="4"
         data-slides-per-view-sm="2" data-slides-per-view-lg="3">
      <div class="swiper-wrapper">
        @foreach([2,3,4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,21,22,23] as $n)
        <div class="swiper-slide">
          <a class="lg-item block" href="{{ asset('/images/cup/A/'.$n.'.webp') }}" data-lg-size="1600-1066">
            <img src="{{ asset('/images/cup/A/'.$n.'.webp') }}" loading="lazy" alt="Pitbike závody" class="w-full h-56 object-cover">
          </a>
        </div>
        @endforeach
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

{{-- Navigační karty --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl lg:text-3xl font-bold text-mx-white mb-10">Objev svět Pitbike závodů</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <a href="{{ route('cup.kaledar_zavodu') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="fade-up">
        <i class="fa-solid fa-calendar text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Kalendář závodů</h3>
        <p class="text-gray-400 text-sm">Sleduj aktuální plán a nepropásni žádnou akci v&nbsp;sezóně.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

      <a href="{{ route('cup.kategorie') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="fade-up" data-aos-delay="100">
        <i class="fa-solid fa-flag-checkered text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Kategorie</h3>
        <p class="text-gray-400 text-sm">Vyber si svou kategorii a poznej své soupeře na&nbsp;trati.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

      <a href="{{ route('cup.pravidla') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="fade-up" data-aos-delay="200">
        <i class="fa-solid fa-gavel text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Pravidla</h3>
        <p class="text-gray-400 text-sm">Seznam se s pravidly a dodržuj bezpečnost na&nbsp;trati.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

      <a href="{{ route('registrace-cup.index') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="zoom-in" data-aos-delay="100">
        <i class="fa-solid fa-user text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Registrace</h3>
        <p class="text-gray-400 text-sm">Přihlas se do závodů a přidej se k Pitbike komunitě.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

      <a href="{{ route('get.objednavka_cup') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="fade-up" data-aos-delay="300">
        <i class="fa-solid fa-user-plus text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Do-objednávka</h3>
        <p class="text-gray-400 text-sm">K existující registraci si můžeš dokoupit závody zde.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

      <a href="{{ route('cup.vysledky') }}" class="group card-dark flex flex-col items-center text-center p-6 hover:border-mx-orange transition-colors" data-aos="fade-up" data-aos-delay="450">
        <i class="fa-solid fa-trophy text-4xl text-mx-orange mb-4"></i>
        <h3 class="text-lg font-semibold text-mx-white mb-2">Výsledky</h3>
        <p class="text-gray-400 text-sm">Prohlédni si výkony závodníků a zjisti, kdo se dostal na vrchol.</p>
        <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all mt-auto pt-4"></i>
      </a>

    </div>
  </div>
</section>

{{-- Tabulka registrovaných jezdců --}}
<section id="tabulka-jezdcu" class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">
        Aktuálně registrovaní jezdci v roce {{ date('Y') }}
      </h2>
    </div>
    <div class="overflow-x-auto" data-aos="fade-up" data-aos-delay="100">
      <table class="w-full text-sm text-left border-collapse">
        <thead>
          <tr class="bg-mx-gray text-gray-400 uppercase text-xs">
            <td class="px-4 py-3">Kategorie</td>
            <td class="px-4 py-3">Jméno (startovní číslo)</td>
            <td class="px-4 py-3">Celkem jezdců</td>
          </tr>
        </thead>
        <tbody>
          @forelse ($riderCategories as $riderCategory)
          <tr class="border-t border-mx-gray2 odd:bg-red-900/40 even:bg-red-900/60 text-white">
            <td class="px-4 py-2 font-medium">{{ $riderCategory->name }}</td>
            <td class="px-4 py-2 max-w-[40vw]">{!! $ridersByCategory[$riderCategory->id] ?? 'Žádní jezdci' !!}</td>
            <td class="px-4 py-2">{{ $ridersCountByCategory[$riderCategory->id] ?? 0 }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="px-4 py-3 text-gray-400">Žádné kategorie</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot class="border-t-2 border-mx-orange bg-mx-gray">
          <tr>
            <td class="px-4 py-2"></td>
            <td class="px-4 py-2"></td>
            <td class="px-4 py-2 font-bold text-mx-white">{{ $countOfRiders }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Nejčastější dotazy</h2>
    </div>
    <div x-data="{ open: null }" class="space-y-2">

      @php
      $faqs = [
        ['id' => 'f1', 'q' => 'Je potřeba mít zkušenosti se závody?', 'a' => 'Ne, naše pitbike závody jsou vhodné pro všechny úrovně jezdců, včetně úplných začátečníků. Stačí když si při přejímce (případně při rozpravě) vyžádáš krátkou instruktáž (o významu vlajek, řazení na start, atd...)'],
        ['id' => 'f2', 'q' => 'Jaký je minimální věk pro jízdu na pitbike závodech?', 'a' => 'Minimální věk pro účast v závodech je 5 let.'],
        ['id' => 'f3', 'q' => 'Je nutné se registrovat předem, nebo mohu přijít bez registrace?', 'a' => 'Doporučujeme <a href="'.route('registrace-cup.index').'" class="text-mx-gold underline">online registraci</a> předem. Ušetříš si tím čas ráno na závodech a nám pomůžeš lépe celou akci zorganizovat. <br>Pokud to ale nestihneš, můžeš se registrovat i na místě.'],
        ['id' => 'f4', 'q' => 'Mohu se zúčastnit závodu na motorce jiné značky než YCF?', 'a' => 'Ano, značka ani model motorky nerozhoduje. Rozhoduje pouze velikost kol a obsah motoru viz <a href="'.route('cup.kategorie').'" class="text-mx-gold underline">kategorie</a>.'],
        ['id' => 'f5', 'q' => 'Musím mít vlastní motorku?', 'a' => 'Nemusíš. Nabízíme také <a href="'.route('pitbike-pujcovna').'" class="text-mx-gold underline">pitbike půjčovnu</a>. Půjčení je ale nutné domluvit předem.'],
        ['id' => 'f6', 'q' => 'Co vše potřebuji pro vyplnění online registrace?', 'a' => 'Pokud již víš do jaké <a href="'.route('cup.kategorie').'" class="text-mx-gold underline">kategorie</a> se chceš přihlásit, nepotřebuješ nic dalšího. Dříve pojišťovna vyžadovala dodat potvrzenou lékařskou zprávu, nyní stačí na prvním závodě jen podepsat čestné prohlášení.'],
        ['id' => 'f7', 'q' => 'Kolik stojí startovné?', 'a' => 'Startovné se skládá z platby za závod a za licenci.<br><strong>1. Závod:</strong> Při roční platbě je to 1000 Kč /závod (lze rozdělit na 2 splátky). Při platbě za samostatný závod je to 1200 Kč.<br>Využít můžete také Akční nabídku, pokud je v platnosti (zobrazí se upozornění), obvykle při včasné online registraci na začátku sezóny.<br>Kategorie MINI je slevněná na 650 Kč /závod.<br><strong>2. Licence:</strong> 500 Kč na celý rok, nebo 100 Kč na jeden závod. Pokud máš licenci Moravia cup, stačí ji doložit a neplatíš nic.'],
      ];
      @endphp

      @foreach ($faqs as $faq)
      <div class="card-dark">
        <button class="w-full flex justify-between items-center p-4 text-left gap-4"
                @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'">
          <span class="font-medium text-mx-white text-sm">{{ $faq['q'] }}</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open === '{{ $faq['id'] }}' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '{{ $faq['id'] }}' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-4 pb-4">
              <p class="text-gray-400 text-sm leading-relaxed">{!! $faq['a'] !!}</p>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

{{-- FB share --}}
<section class="section-sm bg-mx-black text-center">
  <div class="page-container">
    <p class="text-gray-300 mb-4">Znáš někoho, kdo by chtěl závodit? Pošli mu to.</p>
    <div class="fb-share-button" data-href="{{ request()->url() }}" data-layout="button_count" data-size="large"></div>
  </div>
</section>

@includeIf('sections.start_cup', ['background' => 'bg-mx-dark', 'text' => 'Vybral sis jen jeden závodní den, nebo si troufneš na všechny?<br>Při registraci si naklikáš to co ti sedí.'])

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@includeIf('sections.akce')

@endsection

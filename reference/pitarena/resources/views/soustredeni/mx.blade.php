@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'mx-soustredeni')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ route('mxsoustredeni') }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="MX soustředění pro všechny úrovně">
  <meta property="og:description" content="Získej jistotu a techniku v terénu během 3 dnů.">
  <meta property="og:image" content="https://pitarena.cz/images/mx-soustredeni/og.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('mxsoustredeni') }}">
  <meta name="twitter:title" content="MX soustředění pro všechny úrovně">
  <meta name="twitter:description" content="Získej jistotu a techniku v terénu během 3 dnů.">
  <meta name="twitter:image" content="https://pitarena.cz/images/mx-soustredeni/og.jpg">
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
        <span itemprop="name">MX soustředění</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Video hero --}}
<section class="relative h-[60vh] min-h-[400px] overflow-hidden flex items-center justify-center">
  <div class="absolute inset-0" aria-hidden="true">
    <video
      class="w-full h-full object-cover"
      autoplay muted loop playsinline
      preload="metadata"
      poster="{{ asset('images/mx-soustredeni/mx-soustredeni-poster.webp') }}"
      role="presentation"
    >
      <source src="{{ asset('video/MX_soustredeni_compress.mp4') }}" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-black/50"></div>
  </div>
  <div class="relative z-10 text-center page-container">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h1 class="text-4xl lg:text-6xl font-bold text-white">
      <span class="text-mx-orange">MX</span> soustředění
    </h1>
    </div>
  </div>
</section>

{{-- Intro --}}
<section class="section bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title mb-4">Prodloužený víkend plný adrenalinu</h2>
    </div>
    <p class="text-xl font-semibold text-mx-white mb-3" data-aos="fade-up" data-aos-delay="100">
      Zúčastni se motokrosového soustředění pod vedením zkušených trenérů.
    </p>
    <p class="text-gray-400 mb-8" data-aos="fade-up" data-aos-delay="150">
      Ve vybrané termíny od pátku do neděle pořádáme motokrosové soustředění.<br>
      Podmínkou pro účast je rezervace termínu.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="zoom-in" data-aos-delay="250">
      <a class="btn-primary" href="#koupit">Rezervovat termín</a>
      <a class="btn-outline" href="#info">Více informací</a>
    </div>
  </div>
</section>

{{-- Pro děti i dospělé --}}
<section id="info" class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <img src="{{ asset('/images/mx-soustredeni/MX_soustredeni_pro_vsechny.webp') }}" loading="lazy"
             width="652" height="491" alt="Motokros soustředění pravice" class="w-full rounded-lg object-cover"/>
      </div>
      <div data-aos="fade-left">
        <div class="section-divider mb-4"></div>
        <h2 class="section-title mb-5">Pro děti i dospělé</h2>
        <ul class="space-y-4 text-gray-400 text-sm">
          <li class="flex gap-3">
            <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
            <span><strong class="text-mx-white">Pro Nováčky bez zkušeností:</strong><br>
            "Získáš potřebné základy a jistotu v sedle. Chceš na Letní kemp, ale nemáš zkušenosti? Tohle je tvá vstupenka!"</span>
          </li>
          <li class="flex gap-3">
            <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
            <span><strong class="text-mx-white">Pro Pokročilé jezdce:</strong><br>
            "Hledáš intenzivní trénink a chceš posunout své dovednosti na další úroveň? Zkušení trenéři ti pomohou zdokonalit techniku i strategii."</span>
          </li>
          <li class="flex gap-3">
            <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
            <span><strong class="text-mx-white">Pro Rodiny s dětmi:</strong><br>
            "Společný zážitek, který sblíží. Jezděte bok po boku s vašimi dětmi a sdílejte radost z terénního ježdění."</span>
          </li>
          <li class="flex gap-3">
            <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
            <span><strong class="text-mx-white">Pro Všechny, kdo milují akci:</strong><br>
            "Pro každého, kdo chce prožít víkend plný prachu, hlíny, bláta a nezapomenutelných zážitků!"</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- Co tě čeká --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title mb-2">Co tě čeká?</h2>
      <p class="text-gray-400 text-sm">Veškerý program přizpůsobíme úrovni skupiny. Níže je ilustrační příklad.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500">
        <img src="{{ asset('images/mx-soustredeni/patek.webp') }}" loading="lazy" alt="MX soustředění pátek"
             width="418" height="315" class="w-full h-48 object-cover"/>
        <div class="p-5">
          <h3 class="text-mx-white font-semibold text-lg mb-3">Pátek</h3>
          <ul class="space-y-2 text-gray-400 text-sm">
            <li class="flex gap-2"><i class="fa-solid fa-steam text-mx-orange mt-1 flex-shrink-0"></i> Příjezd, registrace, ubytování</li>
            <li class="flex gap-2"><i class="fa-solid fa-steam text-mx-orange mt-1 flex-shrink-0"></i> Seznámení s trenéry, rozdělení do skupin</li>
            <li class="flex gap-2"><i class="fa-solid fa-steam text-mx-orange mt-1 flex-shrink-0"></i> Bezpečnostní briefing a kontrola vybavení</li>
            <li class="flex gap-2"><i class="fa-solid fa-steam text-mx-orange mt-1 flex-shrink-0"></i> Základní/úvodní jízdy: postoj, rozjezdy, brzdění</li>
            <li class="flex gap-2"><i class="fa-solid fa-steam text-mx-orange mt-1 flex-shrink-0"></i> Společné shrnutí dne</li>
          </ul>
        </div>
      </div>

      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500" data-aos-delay="150">
        <img src="{{ asset('images/mx-soustredeni/sobota.webp') }}" loading="lazy" alt="MX soustředění sobota"
             width="418" height="315" class="w-full h-48 object-cover"/>
        <div class="p-5">
          <h3 class="text-mx-white font-semibold text-lg mb-3">Sobota</h3>
          <ul class="space-y-2 text-gray-400 text-sm">
            <li class="flex gap-2"><i class="fa-solid fa-rocket text-mx-orange mt-1 flex-shrink-0"></i> Dopolední blok: technika zatáček, volba stopy</li>
            <li class="flex gap-2"><i class="fa-solid fa-rocket text-mx-orange mt-1 flex-shrink-0"></i> Odpolední blok: startovní procedury, skoky, rytmus</li>
            <li class="flex gap-2"><i class="fa-solid fa-rocket text-mx-orange mt-1 flex-shrink-0"></i> Průběžná individuální zpětná vazba trenérů</li>
            <li class="flex gap-2"><i class="fa-solid fa-rocket text-mx-orange mt-1 flex-shrink-0"></i> Večerní sdílení postřehů, analýza videí</li>
          </ul>
        </div>
      </div>

      <div class="card-dark overflow-hidden" data-aos="zoom-in-left" data-aos-duration="500" data-aos-delay="300">
        <img src="{{ asset('images/mx-soustredeni/nedele.webp') }}" loading="lazy" alt="MX soustředění neděle"
             width="418" height="315" class="w-full h-48 object-cover"/>
        <div class="p-5">
          <h3 class="text-mx-white font-semibold text-lg mb-3">Neděle</h3>
          <ul class="space-y-2 text-gray-400 text-sm">
            <li class="flex gap-2"><i class="fa-solid fa-flag-checkered text-mx-orange mt-1 flex-shrink-0"></i> Zpevnění naučených návyků, delší úseky</li>
            <li class="flex gap-2"><i class="fa-solid fa-flag-checkered text-mx-orange mt-1 flex-shrink-0"></i> Zábavná „výzva" / mini challenge</li>
            <li class="flex gap-2"><i class="fa-solid fa-flag-checkered text-mx-orange mt-1 flex-shrink-0"></i> Závěrečné tipy pro další trénink a bezpečnou jízdu</li>
            <li class="flex gap-2"><i class="fa-solid fa-flag-checkered text-mx-orange mt-1 flex-shrink-0"></i> Předání doporučení k Letnímu kempu a rozloučení</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- Termíny a cena --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title text-center mb-10">Termíny a cena</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
      <div data-aos="fade-right">
        <h3 class="text-mx-white font-semibold mb-4">Vypsané termíny 2026:</h3>
        <ul class="space-y-2 text-gray-400 text-sm">
          <li class="flex gap-2"><i class="fa-solid fa-calendar text-mx-orange mt-1"></i> <span><strong class="text-mx-white">3.–5. 7. 2026</strong> (před 1. vlnou Letního kempu)</span></li>
          <li class="flex gap-2"><i class="fa-solid fa-calendar text-mx-orange mt-1"></i> <span><strong class="text-mx-white">24.–26. 7. 2026</strong> (před 2. vlnou kempu)</span></li>
          <li class="text-gray-400 text-xs italic pl-6">Další termíny (duben–květen 2026) brzy upřesníme</li>
        </ul>
      </div>
      <div data-aos="fade-left">
        <h3 class="text-mx-white font-semibold mb-4">Cena a platba:</h3>
        <ul class="space-y-2 text-gray-400 text-sm">
          <li class="flex gap-2"><i class="fa-solid fa-arrow-right text-mx-orange mt-1"></i> <span><strong class="text-mx-white">Záloha – voucher: 700 Kč</strong> k zakoupení online</span></li>
          <li class="flex gap-2"><i class="fa-solid fa-arrow-right text-mx-orange mt-1"></i> <span><strong class="text-mx-white">Doplatek: 3 000 Kč</strong> cca 60 dní před konáním</span></li>
          <li class="flex gap-2 pt-2 border-t border-mx-gray2"><i class="fa-solid fa-equals text-mx-orange mt-1"></i> <span class="text-mx-white font-semibold">Celkem: 3 700 Kč včetně jídla a zázemí</span></li>
        </ul>
      </div>
    </div>

    {{-- Co je v ceně --}}
    <div class="max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
      <h3 class="text-mx-white font-semibold mb-4">Co je v ceně?</h3>
      <ul class="space-y-3 text-gray-400 text-sm">
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Intenzivní trénink:</strong> Pod vedením zkušených trenérů.</span>
        </li>
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Kompletní stravování:</strong> Po celou dobu soustředění.</span>
        </li>
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Zázemí areálu:</strong> Využití trati, sprch, toalet a velkého společného stanu.</span>
        </li>
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Teoretické brífinky a rozbory:</strong> Analýza jízdy, tipy a triky pro zlepšení.</span>
        </li>
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-check text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Nezapomenutelné zážitky:</strong> A nová přátelství v komunitě MX nadšenců.</span>
        </li>
        <li class="flex gap-3 p-3 card-dark rounded">
          <i class="fa-solid fa-info-circle text-mx-orange mt-1 flex-shrink-0"></i>
          <span><strong class="text-mx-white">Možnost zapůjčení motorky a vybavení:</strong> Pro dětské účastníky, kteří navazují na Letní kemp (po předchozí domluvě!).</span>
        </li>
      </ul>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10" data-aos="zoom-in" data-aos-delay="150">
      <a class="btn-primary" href="#koupit">Rezervovat termín</a>
      <a class="btn-outline" href="files/Storno_podminky_soustredeni.pdf" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Storno podmínky
      </a>
    </div>
  </div>
</section>

{{-- Co budeš potřebovat --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <div class="section-divider mb-4"></div>
        <h2 class="section-title mb-5">Co budeš potřebovat?</h2>
        <ol class="space-y-2 text-gray-400 text-sm mb-5">
          <li class="flex gap-3"><span class="text-mx-orange font-bold text-lg flex-shrink-0">1.</span> Terénní motorku</li>
          <li class="flex gap-3"><span class="text-mx-orange font-bold text-lg flex-shrink-0">2.</span> Solidní helmu</li>
          <li class="flex gap-3"><span class="text-mx-orange font-bold text-lg flex-shrink-0">3.</span> Chrániče na hrudník, lokty a kolena</li>
          <li class="flex gap-3"><span class="text-mx-orange font-bold text-lg flex-shrink-0">4.</span> Pevné boty, kalhoty a dres</li>
          <li class="flex gap-3"><span class="text-mx-orange font-bold text-lg flex-shrink-0">5.</span> Rukavice a brýle</li>
        </ol>
        <p class="text-gray-400 text-sm italic">Pokud nemáš své vlastní vybavení, nemusíš se bát. Po telefonické domluvě ti vše potřebné rádi půjčíme.</p>
      </div>
      <div data-aos="fade-left">
        <img src="{{ asset('/images/mx-soustredeni/equip.png') }}" loading="lazy" width="652" height="491"
             alt="Motokros – ochranné vybavení" class="w-full rounded-lg object-cover"/>
      </div>
    </div>
  </div>
</section>

{{-- SimpleShop form --}}
<section id="koupit" class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title text-center mb-8">Rezervace termínu</h2>
    </div>
    <!-- www.SimpleShop.cz form#131426 start -->
    <div class="bg-white max-w-3xl mx-auto rounded-lg shadow-2xl px-4 py-6 sm:px-8 sm:py-8">
      <div data-SimpleShopForm="2l3wo"><div>Prodejní formulář je vytvořen v systému <a href="https://www.simpleshop.cz/?utm_source=simpleshop&utm_medium=form&utm_campaign=49201" target="_blank" class="text-mx-gold hover:underline">SimpleShop.cz</a>.</div></div>
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
    sss("createForm", "2l3wo");
    </script>
    <!-- www.SimpleShop.cz form#131426 end -->
  </div>
</section>

{{-- Gallery --}}
<section class="section bg-mx-black">
  <div class="page-container">

    @php
      $heading         = 'Fotky z akcí, které pořádáme';
      $baseBig         = 'images/kemp/big/';
      $baseSmall       = 'images/kemp/small/';
      $defaultAlt      = 'Galerie - Pitarena.cz (MX)';
      $altFromFilename = true;
      $imgWidth        = 886;
      $imgHeight       = 668;
      $photos = [
        ['big' => 'kemp_2025 (1).jpg',  'small' => 'kemp_2025 (1).webp'],
        ['big' => 'kemp_2025 (2).jpg',  'small' => 'kemp_2025 (2).webp'],
        ['big' => 'kemp_2025 (3).jpg',  'small' => 'kemp_2025 (3).webp'],
        ['big' => 'kemp_2025 (4).jpg',  'small' => 'kemp_2025 (4).webp'],
        ['big' => 'kemp_2025 (5).jpg',  'small' => 'kemp_2025 (5).webp'],
        ['big' => 'kemp_2025 (6).jpg',  'small' => 'kemp_2025 (6).webp'],
        ['big' => 'kemp_2025 (7).jpg',  'small' => 'kemp_2025 (7).webp'],
        ['big' => 'kemp_2025 (8).jpg',  'small' => 'kemp_2025 (8).webp'],
        ['big' => 'kemp_2025 (9).jpg',  'small' => 'kemp_2025 (9).webp'],
        ['big' => 'kemp_2025 (10).jpg', 'small' => 'kemp_2025 (10).webp'],
        ['big' => 'kemp_2025 (11).jpg', 'small' => 'kemp_2025 (11).webp'],
        ['big' => 'kemp_2025 (12).jpg', 'small' => 'kemp_2025 (12).webp'],
        ['big' => 'pitbike_kemp_2024 (1).jpg',  'small' => 'pitbike_kemp_2024 (1).webp'],
        ['big' => 'pitbike_kemp_2024 (2).jpg',  'small' => 'pitbike_kemp_2024 (2).webp'],
        ['big' => 'pitbike_kemp_2024 (3).jpg',  'small' => 'pitbike_kemp_2024 (3).webp'],
        ['big' => 'pitbike_kemp_2024 (4).jpg',  'small' => 'pitbike_kemp_2024 (4).webp'],
        ['big' => 'pitbike_kemp_2024 (5).jpg',  'small' => 'pitbike_kemp_2024 (5).webp'],
        ['big' => 'pitbike_kemp_2024 (6).jpg',  'small' => 'pitbike_kemp_2024 (6).webp'],
        ['big' => 'pitbike_kemp_2024 (7).jpg',  'small' => 'pitbike_kemp_2024 (7).webp'],
        ['big' => 'pitbike_kemp_2024 (8).jpg',  'small' => 'pitbike_kemp_2024 (8).webp'],
      ];
      $makeAlt = function (string $filename) use ($defaultAlt) {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = str_replace('_', ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $defaultAlt . ': ' . trim($name);
      };
    @endphp

    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title text-center mb-8">{{ $heading }}</h2>
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
            <img src="{{ $src }}" loading="{{ $isFirst ? 'eager' : 'lazy' }}" decoding="async"
                 alt="{{ $alt }}" width="{{ $imgWidth }}" height="{{ $imgHeight }}"
                 class="w-full rounded-lg object-cover"/>
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

{{-- Testimonials --}}
@includeIf('sections/testimonials', ['background' => 'bg-mx-black'])

{{-- FAQ --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="section-title text-center mb-8">Nejčastější dotazy</h2>
    </div>

    <div class="space-y-2" x-data="{ open: null }">

      @php
        $faqs = [
          ['id' => 'f1', 'q' => 'Je soustředění jen pro začátečníky?', 'a' => 'Ne. Je pro <strong class="text-mx-white">všechny úrovně</strong> – od prvních metrů až po pokročilé jezdce. Rozdělíme tě do skupiny, která ti sedne.'],
          ['id' => 'f2', 'q' => 'Musím mít vlastní motorku?', 'a' => 'Nemusíš, ale <strong class="text-mx-white">zapůjčení je možné pouze pro dětské účastníky</strong>, kteří následně jedou na Letní kemp (omezená kapacita, nutná domluva předem). Dospělým doporučujeme vlastní stroj.'],
          ['id' => 'f3', 'q' => 'Jaký je minimální věk?', 'a' => 'Soustředění doporučujeme pro děti <strong class="text-mx-white">od 6 let</strong>. V zásadě je to ale individuální – některé děti to zvládnou i v nižším věku. Pod 6 let je ale spoluúčast rodiče nutná.'],
          ['id' => 'f4', 'q' => 'Co když bude pršet?', 'a' => 'Budeme mokří :) Počasí nás nezastaví. Pokud by došlo k klimatickým extrémům, dohodneme se na náhradním termínu, vrácení peněz, nebo jiné kompenzaci.'],
          ['id' => 'f5', 'q' => 'Jak se přihlásím?', 'a' => 'Kup si <a href="#koupit" class="text-mx-gold hover:underline">voucher</a>. My ti následně pošleme organizační informace včetně pokynů k doplatku přibližně 60 dní před akcí.'],
          ['id' => 'f6', 'q' => 'Kde budu spát a co jíst?', 'a' => 'Spíš <strong class="text-mx-white">ve vlastním stanu, karavanu,</strong> apod. Plná penze je v ceně po celou dobu.'],
          ['id' => 'f7', 'q' => 'Mohu se přihlásit na poslední chvíli?', 'a' => '<strong class="text-mx-white">Ne</strong>. Nezbyde na tebe místo. Kapacita je omezená — otevíráme pouze pro 20–30 účastníků.'],
        ];
      @endphp

      @foreach($faqs as $faq)
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center" @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'">
          <span class="font-semibold text-mx-white text-sm"><i class="fa-regular fa-circle-question text-mx-orange mr-2"></i> {{ $faq['q'] }}</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300 flex-shrink-0 ml-3" :class="{ 'rotate-180': open === '{{ $faq['id'] }}' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '{{ $faq['id'] }}' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              {!! $faq['a'] !!}
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

{{-- Final CTA --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <div class="section-divider mb-4"></div>
        <p class="text-gray-400 mb-3">Chceš získat jistotu v terénu, posunout techniku, nebo se připravit na Letní kemp?</p>
        <h2 class="section-title mb-4">Přidej se na MX soustředění</h2>
        <p class="text-gray-400 text-sm mb-6">
          Připrav se na dny plné adrenalinu, učení a nového přátelství.<br>Začni svou MX cestu s námi!
        </p>
        <a class="btn-primary" href="#koupit" data-aos="flip-right" data-aos-duration="600">Rezervovat</a>
      </div>
      <div data-aos="fade-left">
        <img src="{{ asset('images/mx-soustredeni/mx-soustredeni-pitarena.webp') }}" loading="lazy"
             width="652" height="491" alt="MX soustředění - Pitaréna.cz" class="w-full rounded-lg object-cover"/>
      </div>
    </div>
  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

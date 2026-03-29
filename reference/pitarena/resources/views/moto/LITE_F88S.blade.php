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
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-0.jpg') }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-0.jpg') }}">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li><a href="{{ route('moto') }}">Moto</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li><a href="{{ route('prodej-pitbike') }}">Nové</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">LITE F88S</li>
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


<section class="section-lg bg-mx-black">
  <div class="page-container max-w-3xl">

    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <span class="badge-orange mb-3 inline-block">6-8 LET</span>
      <h1 class="section-title">PITBIKE YCF LITE F88S</h1>
    </div>

    {{-- Video --}}
    <div class="aspect-video mt-6 rounded-xl overflow-hidden shadow-xl shadow-black/50">
      <iframe class="w-full h-full" width="886" height="668"
              src="https://www.youtube.com/embed/g4j_RvDNkA4?si=vshhunkkFwNprHOd"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Představujeme PITBIKE YCF LITE F88S: Výkonný společník pro nezapomenutelné jízdní zážitky.
      </p>
      <p>
        Máte chuť objevit svět pitbiků, ale hledáte model, který by byl jak výkonný, tak lehký a snadno ovladatelný?
        PITBIKE YCF LITE F88S je tady právě pro vás! Srdcem tohoto stroje je vzduchem chlazený, čtyřdobý jednoválec o objemu 88 ccm,
        který vám poskytne solidních 5 kW výkonu. Nožní startovací páka se zesíleným kloubem dává jasně najevo, že na kvalitě se tady nešetřilo.
      </p>
    </div>

    {{-- Swiper galerie s lightboxem --}}
    <div class="moto-gallery-swiper swiper mt-6" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-1b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-1.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-2b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-2.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-3b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-3.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-4b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-4.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-5b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-5.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-6b.webp') }}">
            <img src="{{ asset('/images/moto_new/LITE_F88S/25-LITE-88-STD-6.webp') }}" loading="lazy"
                 alt="PITBIKE YCF LITE F88S" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Co opravdu zaujme u YCF LITE F88S, je jeho poloautomatická převodovka, která umožňuje snadné řazení bez potřeby spojkové páčky.
        Tento model je dokonalým spojením pohodlí a výkonu s jeho robustními pneumatikami Knobby a odolným ocelovým rámem.
        Navíc, díky kompaktním rozměrům a lehké hmotnosti pouhých 60 kg, je tento pitbike ideální pro mladší jezdce nebo ty, kteří hledají něco snadno ovladatelného do terénu.
      </p>
      <p>
        Ať už jste začátečník nebo zkušenější jezdec, s PITBIKE YCF LITE F88S získáte spolehlivého a zábavného parťáka na každou jízdu.
        Buďte připraveni na nezapomenutelné jízdní zážitky s tímto skvostem.
      </p>
    </div>

    {{-- Cena --}}
    <div class="mt-6 flex items-baseline gap-3">
      <span class="text-3xl font-bold text-mx-orange">31.490,- Kč</span>
      <span class="text-gray-400 text-sm">vč. DPH</span>
    </div>
    <p class="text-gray-400 text-xs mt-1">Doprava a montáž nejsou součástí ceny.</p>

    {{-- Facebook share --}}
    <div class="mt-6">
      <p class="text-gray-400 text-sm mb-2">Doporučte tento stroj na FB</p>
      <div class="fb-share-button"
           data-href="{{ request()->url() }}"
           data-layout="button_count"
           data-size="large"></div>
    </div>

  </div>
</section>

@includeIf('sections.motoShopForm')
@includeIf('sections.whyBuyHere')
@includeIf('sections.distanceToUs')

{{-- Zpět na prodej --}}
<section class="section-sm bg-mx-black border-t border-mx-gray2">
  <div class="page-container max-w-3xl">
    <a href="{{ url('/prodej-pitbike') }}"
       class="inline-flex items-center gap-2 text-gray-400 hover:text-mx-orange transition-colors text-sm font-medium">
      <i class="fa-solid fa-chevron-left"></i>
      Další Pitbike moto
    </a>
  </div>
</section>

@endsection

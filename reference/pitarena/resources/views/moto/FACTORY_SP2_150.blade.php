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
  <meta property="og:image" content="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-1.jpg') }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-1.jpg') }}">
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
      <li class="current">FACTORY SP2 150</li>
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
      <span class="badge-orange mb-3 inline-block">10-14 LET</span>
      <h1 class="section-title">PITBIKE YCF FACTORY SP2 150</h1>
    </div>

    {{-- Video --}}
    <div class="aspect-video mt-6 rounded-xl overflow-hidden shadow-xl shadow-black/50">
      <iframe class="w-full h-full" width="886" height="668"
              src="https://www.youtube.com/embed/wZcyvXdyqAo?si=Px2dPsO_ByNGdt0l"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Otevřete novou kapitolu své motokrosové kariéry s PITBIKE YCF FACTORY SP2 150.
      </p>
      <p>
        Zážitek z jízdy na motokrosové dráze je nezapomenutelný, obzvláště když máte správný stroj.
        Představujeme novinku v řadě pitbike – PITBIKE YCF FACTORY SP2 150.
        Vybaven vzduchem chlazeným čtyřdobým jednoválcem s maximálním výkonem 13 kW, tato mašina představuje kombinaci výkonu a precizního ovládání.
        Karburátor Nibbi PE24SP s Airboxem zajišťuje hladký a přesný tok paliva, což vede k bezchybné reakci na každý stisk plynu.
      </p>
    </div>

    {{-- Swiper galerie s lightboxem --}}
    <div class="moto-gallery-swiper swiper mt-6" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-1.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-1.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-2.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-2.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-3.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-3.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-4.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-4.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-5.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-5.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-6.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP2_150/23-FACTORYSP2-150-STD-6.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP2 150" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Z hlediska designu a funkčnosti je tento model nekompromisní.
        Jeho deltaboxový rám z vysoce pevnostní ocele, černá povrchová úprava, hliníkové ráfky a závodní koncovka FACTORY vytvářejí dokonalý vzhled.
        Navíc, plně nastavitelné odpružení a vysoký standard brzdového systému vám poskytnou plnou kontrolu na každé trati.
      </p>
      <p>
        Pokud hledáte dokonalý poměr mezi výkonem, designem a cenou, PITBIKE YCF FACTORY SP2 150 je tou správnou volbou.
        Buďte připraveni na vzrušující závodní dobrodružství s tímto skvělým společníkem.
      </p>
    </div>

    {{-- Cena --}}
    <div class="mt-6 flex items-baseline gap-3">
      <span class="text-3xl font-bold text-mx-orange">64.900,- Kč</span>
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

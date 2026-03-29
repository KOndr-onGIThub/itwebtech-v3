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
  <meta property="og:image" content="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-1.jpg') }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-1.jpg') }}">
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
      <li class="current">FACTORY SP3 190</li>
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
      <span class="badge-orange mb-3 inline-block">14-99 LET</span>
      <h1 class="section-title">PITBIKE YCF FACTORY SP3 190</h1>
    </div>

    {{-- Video --}}
    <div class="aspect-video mt-6 rounded-xl overflow-hidden shadow-xl shadow-black/50">
      <iframe class="w-full h-full" width="886" height="668"
              src="https://www.youtube.com/embed/odNocUPMXzQ?si=BXU5IsiV3codrnoT"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Odhalte svou vášeň pro motokros s PITBIKE YCF FACTORY SP3 190.
      </p>
      <p>
        Máte chuť zkoušet nové výzvy a hledáte perfektní stroj, který vás nikdy nenechá na holičkách?
        Představujeme PITBIKE YCF FACTORY SP3 190, revoluční model v řadě pitbike, vybavený japonským motorem DAYTONA ANIMA.
        Tento vzduchem chlazený čtyřdobý jednoválec o zdvihovém objemu 187,2 ccm nabízí úchvatných 18,64 kW (25HP) výkonu.
        Zjistíte, že když otočíte plyn, dostane se vám rychlé a silné odezvy, díky které si jízdu naplno užijete.
      </p>
    </div>

    {{-- Swiper galerie s lightboxem --}}
    <div class="moto-gallery-swiper swiper mt-6" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-1.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-1.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-2.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-2.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-3.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-3.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-4.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-4.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-5.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-5.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-6.webp') }}">
            <img src="{{ asset('/images/moto_new/FACTORY_SP3_190/23-FACTORYSP3-190-STD-6.webp') }}" loading="lazy"
                 alt="PITBIKE YCF FACTORY SP3 190" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Z každého detailu tohoto stroje dýchá kvalita a inovace.
        Hliníková kyvná vidlice, deltaboxový rám a titanový výfuk s černou závodní koncovkou FACTORY zdůrazňují odhodlání značky YCF přinést nejlepší.
        Přidáme-li k tomu plně stavitelné odpružení ENGI, hliníkové ráfky a pneumatiky Knobby ARRO, dostaneme motocykl připravený pro jakýkoliv terén či trať.
      </p>
      <p>
        Chcete pro sebe to nejlepší? Nechte se unést adrenalinem s PITBIKE YCF FACTORY SP3 190.
        Ať už jste začátečník nebo zkušený jezdec, tento motocykl je vaším spolehlivým partnerem v dobrodružství motokrosu!
      </p>
    </div>

    {{-- Cena --}}
    <div class="mt-6 flex items-baseline gap-3">
      <span class="text-3xl font-bold text-mx-orange">87.900,- Kč</span>
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

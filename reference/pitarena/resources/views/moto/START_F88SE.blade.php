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
  <meta property="og:image" content="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-0.jpg') }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-0.jpg') }}">
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
      <li class="current">START F88SE</li>
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
      <h1 class="section-title">PITBIKE YCF START F88SE</h1>
    </div>

    {{-- Video --}}
    <div class="aspect-video mt-6 rounded-xl overflow-hidden shadow-xl shadow-black/50">
      <iframe class="w-full h-full" width="886" height="668"
              src="https://www.youtube.com/embed/Fhc3XgSwI2M?si=NdNcSady3yBUyvEU"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        Odhalte nový rozměr pitbike zážitků s PITBIKE YCF START F88SE.
      </p>
      <p>
        Pokud hledáte pitbike, který kombinuje moderní technologii s vysokou výkonností a komfortem, nechte se okouzlit modelem YCF START F88SE.
        Srdce tohoto skvostu, vzduchem chlazený čtyřdobý jednoválec o objemu 85,8 ccm, nabízí výkon 4,5 kW a zajišťuje, že jste vždy připraveni na jízdu plnou adrenalinu.
        A díky kombinovanému elektrickému a nožnímu startu se startováním motoru nemusíte nikdy trápit.
      </p>
    </div>

    {{-- Swiper galerie s lightboxem --}}
    <div class="moto-gallery-swiper swiper mt-6" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-1b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-1.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-2b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-2.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-3b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-3.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-4b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-4.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-5b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-5.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-6b.webp') }}">
            <img src="{{ asset('/images/moto_new/START_F88SE/25-START-88SE-STD-6.webp') }}" loading="lazy"
                 alt="PITBIKE YCF START F88SE" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        YCF START F88SE přináší perfektní rovnováhu mezi výkonem a komfortem.
        Jeho poloautomatická převodovka zaručuje plynulé a jednoduché řazení bez spojky,
        zatímco robustní ocelový rám a vysoká světlá výška zaručují nekompromisní výkon na všech typech terénu.
        S kompaktními rozměry a hmotností 65 kg je tento pitbike ideální pro ty, kdo hledají výkonný, ale zároveň snadno ovladatelný stroj.
      </p>
      <p>
        Nejenže tento model vypadá skvěle, ale také je vybaven špičkovými komponenty - od odpružení až po brzdový systém,
        což z něj činí nejen výkonný, ale i bezpečný pitbike.
        Prozkoumejte své jízdní dovednosti a objevte nové horizonty s PITBIKE YCF START F88SE.
        Je čas začít novou kapitolu vašeho jízdního dobrodružství.
      </p>
    </div>

    {{-- Cena --}}
    <div class="mt-6 flex items-baseline gap-3">
      <span class="text-3xl font-bold text-mx-orange">42.990,- Kč</span>
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

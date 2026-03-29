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
  <meta property="og:image" content="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-0.jpg') }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-0.jpg') }}">
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
      <li class="current">BIGY 190 daytona MX</li>
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
      <h1 class="section-title">PITBIKE YCF BIGY FACTORY 190 DAYTONA XL</h1>
    </div>

    {{-- Video --}}
    <div class="aspect-video mt-6 rounded-xl overflow-hidden shadow-xl shadow-black/50">
      <iframe class="w-full h-full" width="886" height="668"
              src="https://www.youtube.com/embed/VU2CiE6Gdxg?si=6cCVzTApDmVTjdFS"
              title="YouTube video player" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>

    <div class="space-y-4 mt-6 text-gray-400 leading-relaxed">
      <p>
        PITBIKE YCF BIGY FACTORY 190 DAYTONA XL – Nezkrotná síla legendy!
      </p>
      <p>
        Připravte se na dominanci na trati. S modelem YCF BIGY FACTORY 190 DAYTONA XL nepřichází jen pitbike,
        ale stroj zrozený pro opravdové jezdce, kteří touží po absolutním výkonu a nekompromisní kontrole.
        Tento model je ztělesněním brutality, preciznosti a nespoutané vášně, navržený pro ty, kteří se nespokojí s obyčejným.
      </p>
    </div>

    <h2 class="mt-8 text-xl font-bold text-white">Design, který vás pohltí – Výkon, který vás ohromí!</h2>
    <div class="space-y-4 mt-4 text-gray-400 leading-relaxed">
      <p>
        Už na první pohled je zřejmé, že BIGY FACTORY 190 DAYTONA XL není stroj pro každého.
        Jeho agresivní linie, robustní konstrukce a prémiové komponenty jasně signalizují, že jde o špičkový závodní stroj.
        Srdcem je jednoválcový čtyřtaktní motor 190cc Daytona Anima, který je zárukou neuvěřitelné síly a okamžité odezvy.
        Nožní startování podtrhuje autentický závodní zážitek a precizní 4stupňová manuální převodovka (N-1-2-3-4) zajišťuje plnou kontrolu nad každým koněm.
      </p>
    </div>

    {{-- Swiper galerie s lightboxem --}}
    <div class="moto-gallery-swiper swiper mt-6" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-1b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-1.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-2b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-2.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-3b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-3.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-4b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-4.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-5b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-5.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
        <div class="swiper-slide">
          <a class="moto-gallery-item block" href="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-6b.webp') }}">
            <img src="{{ asset('/images/moto_new/BIGY_190_daytona_XL/25-BIGY-190D-XL-STD-6.webp') }}" loading="lazy"
                 alt="PITBIKE YCF BIGY FACTORY 190 DAYTONA XL" width="886" height="668" class="w-full rounded-lg">
          </a>
        </div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>

    <h2 class="mt-8 text-xl font-bold text-white">Proč právě YCF BIGY FACTORY 190 DAYTONA XL?</h2>
    <div class="space-y-4 mt-4 text-gray-400 leading-relaxed">
      <p>
        <strong>Motor Daytona Anima</strong>: Srdcem tohoto stroje je legendární motor Daytona Anima 190cc, známý svou nekompromisní silou,
        spolehlivostí a vysokým výkonem, který vás katapultuje vpřed.
        <strong>Jízda, která vás dostane</strong>: Díky pečlivě vyladěnému podvozku s nastavitelnými tlumiči si BIGY FACTORY 190 DAYTONA XL poradí s každou překážkou na trati,
        zatímco vy si užíváte maximální kontrolu a stabilitu.
        <strong>Kvalita bez kompromisů</strong>: YCF je synonymem pro spolehlivost a odolnost.
        Každá součástka je navržena tak, aby vydržela i ty nejextrémnější podmínky.
        <strong>Vzhled, který budí respekt</strong>: S agresivním designem a prémiovým zpracováním budete na trati nepřehlédnutelní.
      </p>
    </div>

    <h2 class="mt-8 text-xl font-bold text-white">Technika, která vítězí</h2>
    <ul class="mt-4 space-y-2 text-gray-400 leading-relaxed list-disc list-inside">
      <li><strong>Motor</strong>: 190 ccm 4T jednoválec</li>
      <li><strong>Startování</strong>: nožní</li>
      <li><strong>Výška sedla</strong>: 910 mm</li>
      <li><strong>Převodovka</strong>: 4stupňová manuální (N-1-2-3-4)</li>
      <li><strong>Přední vidlice</strong>: Nastavitelná 800 mm Engi, upside-down</li>
      <li><strong>Zadní tlumič</strong>: Nastavitelný 355 mm Engi</li>
      <li><strong>Přední kolo</strong>: 19 palců</li>
      <li><strong>Zadní kolo</strong>: 16 palců</li>
    </ul>

    <h2 class="mt-8 text-xl font-bold text-white">Už žádné kompromisy!</h2>
    <div class="space-y-4 mt-4 text-gray-400 leading-relaxed">
      <p>
        YCF BIGY FACTORY 190 DAYTONA XL je stvořen pro ty, kteří chtějí překonávat své limity a zažít skutečnou sílu.
        Ať už jste zkušený jezdec, který hledá ultimativní stroj pro závody, nebo nadšenec toužící po nekompromisním zážitku z jízdy,
        BIGY FACTORY 190 DAYTONA XL je volba, která vás nikdy nezklame.
      </p>
      <p>
        Přijďte si pro svůj YCF BIGY FACTORY 190 DAYTONA XL a poznejte rozdíl, který dělá šampiona.
      </p>
    </div>

    {{-- Cena --}}
    <div class="mt-6 flex items-baseline gap-3">
      <span class="text-3xl font-bold text-mx-orange">93.490,- Kč</span>
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

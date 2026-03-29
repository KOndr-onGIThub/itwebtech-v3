@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'servis')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="https://pitarena.cz/servis">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Servis motocyklů, čtyřkolek a skútrů v Pravicích">
  <meta property="og:description" content="Špičková diagnostika a servis civilních i závodních motorek. Objednejte se pohodlně vašich na našich stránkách.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_servis_pravice.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz/servis">
  <meta name="twitter:title" content="Servis motocyklů, čtyřkolek a skútrů v Pravicích">
  <meta name="twitter:description" content="Špičková diagnostika a servis civilních i závodních motorek. Objednejte se pohodlně vašich na našich stránkách.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_servis_pravice.jpg">
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
        <span itemprop="name">Servis</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section
  class="parallax-hero"
  style="--hero-img: url('{{ asset('images/servis_pitarena_header.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">MOTO SERVIS</h1>
  </div>
</section>

{{-- Intro --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl text-center">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-4">Servis motocyklů, čtyřkolek a skútrů</h2>
    </div>
    <p class="text-gray-400 mb-8" data-aos="fade-up" data-aos-delay="100">
      Můžete se zde objednat na termín, který vám vyhovuje, nebo nám zavolejte, pokud máte k servisu nějaký dotaz.
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left">
      <ul class="space-y-2 text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="150">
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Máme špičkové diagnostiky TEXA a BOSCH</li>
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Děláme záruční i pozáruční servis</li>
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Jsme vybavený pneuservis</li>
      </ul>
      <ul class="space-y-2 text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="250">
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Proklepneme motorku před koupí</li>
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Připravíme váš stroj na sezónu</li>
        <li class="flex gap-2"><i class="fa-solid fa-check text-mx-orange mt-1"></i> Zkrátka servis od A do Z!</li>
      </ul>
    </div>
  </div>
</section>

{{-- YCF servis --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <img src="{{ asset('images/ycf_moto_servis.webp') }}" loading="lazy" width="652" height="491"
             alt="Autorizovaný servis motocyklů YCF"
             class="w-full rounded-lg object-cover"/>
      </div>
      <div data-aos="fade-left">
        <div class="section-divider"></div>
        <h2 class="section-title mb-2">Autorizovaný servis YCF</h2>
        <p class="text-gray-400 text-sm mb-6">Záruční i pozáruční servis motocyklů značky YCF</p>
        <div class="reservanto-widget"
             data-text="OBJEDNEJTE SE"
             data-id="20449"
             data-color-text="#ffffff"
             data-color-text-shadow="#990000"
             data-color-bg="#ff0000"
             data-color-bg-hover="#eb0000"
             data-color-boxshadow="#d10000"
             data-segment="1011"
             data-resourceid="31041"></div>
        <script defer id="reservanto-widget-script" type="text/javascript"
                src="https://booking.reservanto.cz/Script/reservanto-script.js?id=20449&noAction=true"></script>
      </div>
    </div>
  </div>
</section>

{{-- Other service --}}
<section class="section bg-mx-dark">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <div class="section-divider"></div>
        <h2 class="section-title mb-2">Servisujeme nejen závodní stroje.</h2>
        <p class="text-gray-400 text-sm mb-6">Děláme diagnostiku a servis motocyklů, čtyřkolek a skútrů všech značek</p>
        <div class="reservanto-widget"
             data-text="OBJEDNEJTE SE"
             data-id="20449"
             data-color-text="#ffffff"
             data-color-text-shadow="#990000"
             data-color-bg="#ff0000"
             data-color-bg-hover="#eb0000"
             data-color-boxshadow="#d10000"
             data-segment="1011"
             data-resourceid="31041"></div>
        <script defer type="text/javascript"
                src="https://booking.reservanto.cz/Script/reservanto-script.js?id=20449&noAction=true"></script>
      </div>
      <div data-aos="fade-left">
        <img src="{{ asset('images/kompletni_moto_servis.webp') }}" loading="lazy"
             alt="servis motocyklů Pravice"
             class="w-full rounded-lg object-cover"/>
      </div>
    </div>
  </div>
</section>

{{-- Price list --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-2xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-10">CENÍK MOTO SERVISU</h2>
    </div>

    <div class="space-y-0" data-aos="fade-up" data-aos-delay="100">

      <div class="bg-mx-orange px-5 py-2 text-white font-bold text-xs uppercase tracking-wider">SERVIS</div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Moto servis</span>
        <span class="text-mx-orange font-bold">820,- Kč / 1h</span>
      </div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Výměna oleje <small class="text-gray-400">(+cena oleje dle množství)</small></span>
        <span class="text-mx-orange font-bold">300,- Kč</span>
      </div>

      <div class="bg-mx-orange px-5 py-2 text-white font-bold text-xs uppercase tracking-wider mt-4">DIAGNOSTIKA</div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Diagnostika</span>
        <span class="text-mx-orange font-bold">700,- Kč</span>
      </div>

      <div class="bg-mx-orange px-5 py-2 text-white font-bold text-xs uppercase tracking-wider mt-4">PNEUSERVIS</div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Přezutí pneu – demontované kolo</span>
        <span class="text-mx-orange font-bold">360,- Kč</span>
      </div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Přezutí pneu – na moto</span>
        <span class="text-mx-orange font-bold">990,- Kč</span>
      </div>

      <div class="bg-mx-orange px-5 py-2 text-white font-bold text-xs uppercase tracking-wider mt-4">KRÁSA & ÚDRŽBA</div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Mytí</span>
        <span class="text-mx-orange font-bold">390,- Kč</span>
      </div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Mytí velmi znečištěné moto</span>
        <span class="text-mx-orange font-bold">690,- Kč</span>
      </div>
      <div class="flex justify-between items-center py-3 px-5 border-b border-mx-gray2 text-sm">
        <span class="text-gray-300">Kompletní kontrola tech. stavu</span>
        <span class="text-mx-orange font-bold">990,- Kč</span>
      </div>

    </div>
  </div>
</section>

{{-- Reservation --}}
<section class="section bg-mx-dark">
  <div class="page-container max-w-2xl text-center">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-8">Objednejte se ať nečekáte</h2>
    </div>
    <div style="max-width:100%;height:550px;" class="reservanto-iframe" data-id="20449" data-seg="1011" data-resourceid="31041"></div>
    <script defer type="text/javascript" src="https://booking.reservanto.cz/Script/reservanto-iframe.js"></script>
  </div>
</section>

{{-- Why choose us --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-2xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-10">Proč si vybrat náš moto servis?</h2>
    </div>

    <div class="space-y-6 text-gray-400 text-sm leading-relaxed" data-aos="fade-up" data-aos-delay="100">
      <div class="flex gap-4">
        <div class="text-mx-orange text-2xl flex-shrink-0"><i class="fa-solid fa-certificate"></i></div>
        <div>
          <h4 class="text-mx-white font-semibold mb-1">1) Odbornost a zkušenosti</h4>
          <p>Náš tým má letité zkušenosti s opravami a údržbou motocyklů všech značek. Motorkama žijeme už od dětství.</p>
        </div>
      </div>
      <div class="flex gap-4">
        <div class="text-mx-orange text-2xl flex-shrink-0"><i class="fa-solid fa-wrench"></i></div>
        <div>
          <h4 class="text-mx-white font-semibold mb-1">2) Profi vybavení</h4>
          <p>Využíváme špičkové diagnostické nástroje, jako jsou TEXA a BOSCH, což nám umožňuje rychle a přesně diagnostikovat a řešit problémy s vaším motocyklem.</p>
        </div>
      </div>
      <div class="flex gap-4">
        <div class="text-mx-orange text-2xl flex-shrink-0"><i class="fa-solid fa-calendar-check"></i></div>
        <div>
          <h4 class="text-mx-white font-semibold mb-1">3) Flexibilita</h4>
          <p>Nabízíme možnost objednání na termín, který vám vyhovuje. Pokud máte nějaký dotaz nebo potřebujete rychlou konzultaci, neváhejte nás kontaktovat.</p>
        </div>
      </div>
      <div class="flex gap-4">
        <div class="text-mx-orange text-2xl flex-shrink-0"><i class="fa-solid fa-euro-sign"></i></div>
        <div>
          <h4 class="text-mx-white font-semibold mb-1">4) Cenová transparentnost</h4>
          <p>Náš ceník je jasný a transparentní. Vždy víte, kolik zaplatíte za konkrétní službu, bez skrytých poplatků.</p>
        </div>
      </div>
      <div class="flex gap-4">
        <div class="text-mx-orange text-2xl flex-shrink-0"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div>
          <h4 class="text-mx-white font-semibold mb-1">5) Díly skladem</h4>
          <p>Máme skladem velké množství náhradních dílů. Takže práce nestojí a vy nečekáte.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Location --}}
<section class="bg-mx-black py-20 relative overflow-hidden bg-grid">

  <div class="relative z-10 page-container max-w-2xl text-center" data-aos="fade-up">
    <div class="section-divider section-divider-center"></div>
    <h2 class="section-title mb-4">Kde nás najdete?</h2>
    <p class="text-gray-400 text-sm mb-10">
      Naše dílna se nachází jen kousek od naší <a href="{{ route('trat') }}" target="_blank" class="text-mx-gold hover:underline">motokrosové trati na Jižní Moravě</a>, na pomezí okresů Znojmo a Břeclav.
      Přijďte nás navštívit a přesvědčte se o kvalitě našich služeb.
    </p>
  </div>

  <div class="relative z-10 flex justify-center px-4" data-aos="fade-up" data-aos-delay="100">
    <div class="bg-mx-gray border border-mx-gray3 rounded-lg p-10 max-w-sm w-full text-center shadow-card">

      <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-lg bg-mx-gray2 border border-mx-gray3 flex items-center justify-center">
          <i class="fa-solid fa-location-dot text-2xl text-mx-orange"></i>
        </div>
      </div>

      <h3 class="text-mx-light font-bold text-xl uppercase tracking-wide mb-1">PitAréna</h3>
      <p class="text-gray-400 text-sm mb-2">Stanislav Holcmann</p>
      <p class="text-gray-500 text-sm mb-8">Pravice 671 78</p>

      <a href="https://maps.app.goo.gl/xHDp9K4pVJNENCmq6" target="_blank" rel="noopener noreferrer"
         class="btn-primary">
        <i class="fa-solid fa-map-location-dot"></i>
        Otevřít Google mapu
      </a>

    </div>
  </div>
</section>


{{-- Contact CTA --}}
<section class="section bg-mx-black text-center">
  <div class="page-container">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-4">Dotazy na servis</h2>
    </div>
    <p class="text-gray-400 mb-6" data-aos="fade-up" data-aos-delay="100">
      Máte nějaký dotaz k servisu? Napište nám nebo zavolejte. K dispozici jsou zde také různé
      <a href="{{ route('parts') }}" class="text-mx-gold hover:underline">servisní dokumenty a katalogy</a>.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="zoom-in" data-aos-delay="200">
      <a class="btn-primary" href="mailto:servis@pitarena.cz">
        <i class="fa-solid fa-envelope"></i> servis@pitarena.cz
      </a>
      <a class="btn-outline" href="tel:+420704221663">
        <i class="fa-solid fa-phone"></i> +420 704 221 663
      </a>
    </div>
  </div>
</section>

{{-- Moto brands --}}
<section class="section bg-mx-black">
  <div class="page-container text-center">
    <p class="text-gray-400 mb-6" data-aos="fade-up">Servisujeme tyto značky</p>
    <div class="flex flex-wrap justify-center gap-4 items-center" data-aos="fade-up" data-aos-delay="100">
      <img src="{{ url('/images/moto_brands/Aprilia-Logo.jpg') }}" loading="lazy" alt="logo aprilia" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/BMW-Logo.jpg') }}" loading="lazy" alt="logo BMW" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/Ducati-Motor-Logo.jpg') }}" loading="lazy" alt="logo Ducati" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/Honda-Logo.jpg') }}" loading="lazy" alt="logo Honda" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/indian-logo.jpg') }}" loading="lazy" alt="logo Indian" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/kawasaki-logo.jpg') }}" loading="lazy" alt="logo kawasaki" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/KTM-Logo.jpg') }}" loading="lazy" alt="logo KTM" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/royal-enfield-logo.jpg') }}" loading="lazy" alt="logo Royal Enfield" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/Suzuki-Logo.jpg') }}" loading="lazy" alt="logo Suzuki" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/Triumph-Logo.jpg') }}" loading="lazy" alt="logo Triumph" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
      <img src="{{ url('/images/moto_brands/Yamaha-Logo.jpg') }}" loading="lazy" alt="logo Yamaha" class="h-10 object-contain opacity-70 hover:opacity-100 transition-opacity">
    </div>
  </div>
</section>

@endsection


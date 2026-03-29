@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == '')
@section('title', $sitemap['title'] )
@section('meta_description', $sitemap['description'] )
@endif
@empty
@endforelse

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="https://pitarena.cz">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection


@section('content')

  {{-- ═══ HERO SWIPER ══════════════════════════════════════════ --}}
  <div class="swiper hero-swiper" data-autoplay="4000" data-loop="true" data-nav="true">
    <div class="swiper-wrapper">

      {{-- POZOR: první slide má prioritu načítání obrázku --}}
      <div class="swiper-slide relative"
           style="background-image: url('{{ url('images/merch_2025-11_banner.webp') }}')">
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(7,7,10,0.93) 0%, rgba(7,7,10,0.75) 40%, rgba(7,7,10,0.3) 72%, transparent 100%); background-color: rgba(7,7,10,0.4);"></div>
        <div class="relative z-10 page-container py-16 lg:py-24">
          <div class="max-w-2xl">
            <p class="hero-eyebrow mb-5">PitArena — 2025</p>
            <h1 class="display-title mb-8" data-aos="fade-up">
              Nová kolekce<br>oblečení PitAréna
            </h1>
            <div data-aos="fade-up" data-aos-delay="100">
              <a class="btn-primary-lg" href="https://eshop.tymoveobleceni.cz/pitbike-motokros/" target="_blank">
                Do e-Shopu
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide relative"
           style="background-image: url('{{ url('images/pitarena_cz_02.jpg') }}')">
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(7,7,10,0.93) 0%, rgba(7,7,10,0.75) 40%, rgba(7,7,10,0.3) 72%, transparent 100%); background-color: rgba(7,7,10,0.4);"></div>
        <div class="relative z-10 page-container py-16 lg:py-24">
          <div class="max-w-2xl">
            <p class="hero-eyebrow mb-5">Pitbike akademie</p>
            <h2 class="display-title mb-5" data-aos="fade-up">
              Škola<br>pitbike<br>motokrosu
            </h2>
            <p class="text-lg text-gray-200/75 mb-8 font-light max-w-md leading-relaxed" data-aos="fade-up" data-aos-delay="50">
              Profesionální vedení pro začátečníky i pokročilé
            </p>
            <div data-aos="fade-up" data-aos-delay="100">
              <a class="btn-primary-lg" href="{{ route('akademie') }}">Program Akademie</a>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide relative"
           style="background-image: url('{{ url('images/pitarena_cz_03.jpg') }}')">
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(7,7,10,0.93) 0%, rgba(7,7,10,0.75) 40%, rgba(7,7,10,0.3) 72%, transparent 100%); background-color: rgba(7,7,10,0.4);"></div>
        <div class="relative z-10 page-container py-16 lg:py-24">
          <div class="max-w-2xl">
            <p class="hero-eyebrow mb-5">Servis motocyklů</p>
            <h2 class="display-title mb-5" data-aos="fade-up">
              Servis<br>všech<br>značek
            </h2>
            <p class="text-lg text-gray-200/75 mb-8 font-light max-w-md leading-relaxed" data-aos="fade-up" data-aos-delay="50">
              Od základní údržby po špičkovou diagnostiku
            </p>
            <div data-aos="fade-up" data-aos-delay="100">
              <a class="btn-primary-lg" href="{{ route('servis') }}">Objednat se na servis</a>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide relative"
           style="background-image: url('{{ url('images/pujcovna/pitarena_cz_05.webp') }}')">
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(7,7,10,0.93) 0%, rgba(7,7,10,0.75) 40%, rgba(7,7,10,0.3) 72%, transparent 100%); background-color: rgba(7,7,10,0.4);"></div>
        <div class="relative z-10 page-container py-16 lg:py-24">
          <div class="max-w-2xl">
            <p class="hero-eyebrow mb-5">Půjčovna</p>
            <h2 class="display-title mb-5" data-aos="fade-up">
              Půjčovna<br>Pitbike
            </h2>
            <p class="text-lg text-gray-200/75 mb-8 font-light max-w-md leading-relaxed" data-aos="fade-up" data-aos-delay="50">
              Nejdostupnější cesta k závodnímu stroji
            </p>
            <div data-aos="fade-up" data-aos-delay="100">
              <a class="btn-primary-lg" href="{{ route('pitbike-pujcovna') }}">Chci si půjčit závodní pitbike</a>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>


  {{-- ═══ VŠE PRO MILOVNÍKY PITBIKE ════════════════════════════ --}}
  <section class="section-lg">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

        <div data-aos="fade-right">
          <div class="section-divider"></div>
          <h2 class="section-title">Vše pro milovníky<br>pitbike motokrosu</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-8">
            <a href="{{ route('trat') }}" class="feature-card group" data-aos="fade-up">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-person-biking text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Volné jízdy</span>
              <span class="text-xs text-gray-400">pro celé rodiny</span>
            </a>
            <a href="{{ route('programy') }}" class="feature-card group" data-aos="fade-up" data-aos-delay="50">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-chart-line text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Programy</span>
              <span class="text-xs text-gray-400">trénink a zážitky</span>
            </a>
            <a href="{{ route('cup') }}" class="feature-card group" data-aos="fade-up" data-aos-delay="100">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-flag-checkered text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Závody</span>
              <span class="text-xs text-gray-400">YCF CUP</span>
            </a>
            <a href="{{ route('servis') }}" class="feature-card group" data-aos="fade-up" data-aos-delay="150">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-wrench text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Servis</span>
              <span class="text-xs text-gray-400">všechna moto</span>
            </a>
            <a href="{{ route('moto') }}" class="feature-card group col-span-2 sm:col-span-1" data-aos="fade-up" data-aos-delay="200">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-motorcycle text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Moto</span>
              <span class="text-xs text-gray-400">půjčovna · prodej · bazar</span>
            </a>
            <a href="{{ route('akademie') }}" class="feature-card group" data-aos="fade-up" data-aos-delay="250">
              <div class="flex justify-between items-center w-full">
                <i class="fa-solid fa-graduation-cap text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                <i class="fa-solid fa-arrow-right text-gray-600 group-hover:text-mx-orange group-hover:translate-x-1 transition-all"></i>
              </div>
              <span class="font-bold text-mx-white text-sm leading-tight">Akademie</span>
              <span class="text-xs text-gray-400">naučte se jezdit</span>
            </a>
          </div>
        </div>

        <div data-aos="fade-left">
          <img src="images/pitbike_pitarena_cz.webp" loading="lazy" width="652" height="491"
               alt="pitbike motokros" class="w-full rounded-xl shadow-2xl shadow-black/50">
        </div>

      </div>
    </div>
  </section>


  {{-- ═══ STATS ═════════════════════════════════════════════════ --}}
  <div class="py-16" style="border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
    <div class="page-container">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 lg:divide-x lg:divide-white/[0.05]">
        <div class="text-center lg:text-left lg:pl-8 first:pl-0" data-aos="fade-up">
          <div class="stat-number text-mx-orange">6+</div>
          <div class="stat-label">let na trati</div>
        </div>
        <div class="text-center lg:text-left lg:pl-8" data-aos="fade-up" data-aos-delay="100">
          <div class="stat-number">600+</div>
          <div class="stat-label">spokojených jezdců</div>
        </div>
        <div class="text-center lg:text-left lg:pl-8" data-aos="fade-up" data-aos-delay="200">
          <div class="stat-number">200+</div>
          <div class="stat-label">dní na trati ročně</div>
        </div>
        <div class="text-center lg:text-left lg:pl-8" data-aos="fade-up" data-aos-delay="300">
          <div class="stat-number">10</div>
          <div class="stat-label">závodních kategorií</div>
        </div>
      </div>
    </div>
  </div>


  {{-- ═══ DÁRKOVÝ POUKAZ ════════════════════════════════════════ --}}
  <section class="section-sm">
    <div class="page-container">
      <div class="max-w-3xl mx-auto text-center">

        <div data-aos="fade-up">
          <div class="section-divider section-divider-center"></div>
          <h2 class="section-title">Dárkový poukaz</h2>
          <p class="text-gray-400 mt-3 leading-relaxed">
            Nejoblíbenější a nejprodávanější naučný program je MX-GO. Ať už máte vlastní moto nebo ne (zapůjčíme),
            <strong class="text-mx-white">MX-GO vám dá základy pitbike motokrosu</strong>.
            Poukaz lze <a href="https://form.simpleshop.cz/9gPp/buy/" target="_blank" class="text-mx-gold hover:underline">zakoupit online</a>.
          </p>
        </div>

        <a href="{{ route('mx-go') }}" class="block mt-6" data-aos="zoom-in" data-aos-delay="100">
          <img src="{{ asset('images/voucher_mxGO-VZOR.webp') }}" loading="lazy"
               alt="voucher pitbike kemp" width="1000" height="324"
               class="w-full rounded-lg shadow-xl shadow-black/40"/>
        </a>
        <a href="{{ route('mx-go') }}" class="text-mx-gold hover:underline text-sm mt-2 inline-block">
          Detaily programu MX-GO
        </a>

        <div class="flex flex-wrap justify-center gap-4 mt-6" data-aos="fade-up" data-aos-delay="200">
          <a class="btn-primary" href="https://form.simpleshop.cz/9gPp/buy/" target="_blank">KOUPIT MX-GO</a>
          <a class="btn-outline" href="{{ route('poukazy') }}">Další vouchery</a>
        </div>

      </div>
    </div>
  </section>


  {{-- ═══ AKTUALITY ══════════════════════════════════════════════ --}}
  @php
  /*
   * AKTUALITY — editovatelný seznam
   * • 1. položka = velká featured karta (vlevo)
   * • 2. a 3. položka = menší karty (vpravo)
   *
   * Dostupná pole:
   *   image    – cesta k obrázku (ideální rozměr 418 × 315)
   *   alt      – alt text obrázku
   *   date     – datum přes obrázek (jen featured, volitelné)
   *   location – ['text'=>'...','url'=>'...'] (volitelné)
   *   tag      – malý štítek nad názvem (volitelné)
   *   title    – nadpis (může obsahovat <br> nebo emoji)
   *   body     – hlavní text (může obsahovat <strong>...)
   *   details  – pole řádků s doplňujícími info (volitelné)
   *   time     – čas konání, např. 'od 14:00' (volitelné)
   *   buttons  – pole tlačítek:
   *              [['label'=>'...', 'href'=>'...', 'class'=>'btn-primary btn-sm', 'external'=>true]]
   *              nebo pro Laravel route: ['label'=>'...', 'route'=>'nazev_routy', 'class'=>'...']
   */
  $aktuality = [

    // ① FEATURED — velká karta vlevo
    [
      'image'   => 'images/kemp_pitbike_motokros_2024_aktuality.gif',
      'alt'     => 'pitbike kemp 2026',
      'tag'     => 'léto 2026 · Pravice',
      'title'   => '🌞 Pitbike kemp 2026 ⛺',
      'body'    => 'Registrace spuštěny! <strong class="text-mx-white">Dva termíny</strong>, každý pro jinou věkovou skupinu. Místa se rychle vyprodají.',
      'buttons' => [
        ['label' => 'Více o kempu', 'route' => 'kemp', 'class' => 'btn-primary btn-sm text-xs'],
      ],
    ],

    // ② karta vpravo nahoře
    [
      'image'   => 'images/aktuality/merch_2025-11.webp',
      'alt'     => 'Nová kolekce oblečení Pitaréna',
      'tag'     => 'aktuálně · e-Shop',
      'title'   => '🏁 Nová kolekce oblečení Pitaréna 👕',
      'body'    => 'Vznikla <strong class="text-mx-white">kolekce oblečení Pitaréna</strong>! Trička, mikiny, legíny v našem vlastním designu pro děti i dospělé.',
      'buttons' => [
        ['label' => 'Zobrazit kolekci', 'href' => 'https://eshop.tymoveobleceni.cz/pitbike-motokros/', 'class' => 'btn-outline btn-sm text-xs', 'external' => true],
      ],
    ],

    // ③ karta vpravo dole
    [
      'image'    => 'images/aktuality/kemp_mlynska_2026.webp',
      'alt'      => 'Open Air Camp Festival',
      'date'     => '30.05.2026',
      'location' => ['text' => 'Jaroslavice', 'url' => 'https://maps.app.goo.gl/aAFXGnfkb5yW2AGn6'],
      'title'    => '🎸 OPEN AIR CAMP FESTIVAL<br>30. 5. 2026 🤘',
      'body'     => 'Tahle akce je pro všechny, kdo milujou živou muziku bez pózy. Hraje se tady <strong class="text-mx-white">punk, ska a rocková klasika s pořádnou energií</strong> — od Nšoči přes Tri Groše až po legendární E!E (a samozřejmě Stará Pešatová, Psychohlína a K.P.D.).',
      /* 'details'  => [
        '<strong class="text-mx-white">Vstupné:</strong>',
        '480 Kč v předprodeji (do 25. května)',
        '640 Kč na místě',
      ], */
      /* 'time'     => 'Startujeme od 14:00', */
      'buttons'  => [
        ['label' => 'Předprodej a další info', 'href' => 'https://www.smsticket.cz/vstupenky/64916-open-air-camp-festival-kemp-mlynska-jaroslavice?utm_campaign=smsticket-prodejni-odkaz', 'class' => 'btn-outline btn-sm', 'external' => true],
      ],
    ],

  ];
  @endphp

  <section id="aktuality" class="section-lg">
    <div class="page-container">

      <div class="text-center mb-12" data-aos="fade-up">
        <div class="section-divider section-divider-center"></div>
        <h2 class="section-title">Aktuality a novinky</h2>
      </div>

      {{-- Pricing-card style: 3 karty vedle sebe, první zvýrazněná --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        @foreach($aktuality as $i => $item)
          @php $first = ($i === 0); @endphp

          <div class="flex flex-col overflow-hidden rounded-lg transition-transform duration-300
                      {{ $first
                          ? 'ring-2 ring-mx-orange shadow-xl shadow-mx-orange/20 lg:-translate-y-2'
                          : 'card-dark' }}"
               data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">

            {{-- oranžový pruh nahoře jen u první karty --}}
            @if($first)
              <div class="h-1 bg-mx-orange"></div>
            @endif

            {{-- obrázek --}}
            <div class="relative overflow-hidden {{ $first ? 'bg-zinc-900' : '' }}">
              <img src="{{ $item['image'] }}" alt="{{ $item['alt'] }}"
                   class="w-full aspect-[418/315] object-cover transition-transform duration-700 hover:scale-[1.03]"
                   @if($i > 0) loading="lazy" @endif>
              @if(!empty($item['date']) || !empty($item['location']))
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 right-0 flex justify-between items-center px-4 py-3 text-xs text-gray-300">
                  @if(!empty($item['date']))
                    <span class="font-bold text-white text-sm">{{ $item['date'] }}</span>
                  @endif
                  @if(!empty($item['location']))
                    <a href="{{ $item['location']['url'] }}" target="_blank"
                       class="flex items-center gap-1.5 hover:text-mx-orange transition-colors">
                      <i class="fa-solid fa-location-dot text-mx-orange"></i> {{ $item['location']['text'] }}
                    </a>
                  @endif
                </div>
              @endif
            </div>

            {{-- obsah --}}
            <div class="card-dark-body flex flex-col flex-1 gap-3">
              @if(!empty($item['tag']))
                <span class="text-[10px] font-semibold uppercase tracking-widest text-mx-muted">{{ $item['tag'] }}</span>
              @endif
              <h5 class="text-mx-white font-bold {{ $first ? 'text-lg' : 'text-base' }} leading-snug">{!! $item['title'] !!}</h5>
              <p class="text-gray-400 text-sm leading-relaxed">{!! $item['body'] !!}</p>
              @if(!empty($item['details']) || !empty($item['time']))
                <div class="text-sm text-gray-400 space-y-1">
                  @foreach($item['details'] ?? [] as $line)
                    <p>{!! $line !!}</p>
                  @endforeach
                  @if(!empty($item['time']))
                    <p class="flex items-center gap-1.5 mt-1">
                      <i class="fa-regular fa-clock text-mx-orange"></i> {{ $item['time'] }}
                    </p>
                  @endif
                </div>
              @endif
              @if(!empty($item['buttons']))
                <div class="mt-auto pt-2 flex flex-wrap gap-2">
                  @foreach($item['buttons'] as $btn)
                    <a class="{{ $btn['class'] }}" href="{{ !empty($btn['route']) ? route($btn['route']) : $btn['href'] }}"
                       @if(!empty($btn['external'])) target="_blank" @endif>{{ $btn['label'] }}</a>
                  @endforeach
                </div>
              @endif
            </div>

          </div>
        @endforeach

      </div>
    </div>
  </section>


  @includeIf('sections.start_cup', ['background' => '', 'text' => 'Jsi připraven postavit se s námi na start?<br>Zaregistruj se a užij si opravdovou jízdu.'])


  {{-- ═══ TÝM PITARÉNY (parallax + Swiper) ═════════════════════ --}}
  <section
    class="parallax-hero"
    style="--hero-img: url('{{ asset('images/pitbike_rodina.jpg') }}');"
  >
    <div class="parallax-hero-content w-full py-20">
      <div class="page-container">
        <div data-aos="fade-up">
          <div class="section-divider section-divider-center"></div>
          <h2 class="section-title-center mb-10">Tým Pitarény</h2>
        </div>

        <div class="swiper"
             data-autoplay="4000" data-loop="true" data-nav="true"
             data-slides-per-view-sm="2" data-slides-per-view-lg="3"
             data-space-between="24">
          <div class="swiper-wrapper pb-10">

            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <img class="w-24 h-24 rounded-full object-cover" src="images/Standa-Holcmann_193px.jpg"
                     loading="lazy" alt="Stanislav Holcmann" width="193" height="193"/>
                <h5 class="text-mx-white font-semibold text-base">Stanislav Holcmann</h5>
                <p class="text-mx-orange text-sm font-medium">Majitel a trenér</p>
                <p class="text-gray-400 text-sm leading-relaxed">
                  Trenér, milovník motokrosu a duše celého areálu Pitaréna.
                </p>
              </div>
            </div>

            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <img class="w-24 h-24 rounded-full object-cover" src="images/david_jonas_193px.jpg"
                     loading="lazy" alt="David Jonas" width="193" height="193"/>
                <h5 class="text-mx-white font-semibold text-base">David Jonáš</h5>
                <p class="text-mx-orange text-sm font-medium">Trenér</p>
                <p class="text-gray-400 text-sm leading-relaxed">
                  Bývalý motokrosový závodník. Dnes provozovatel motokrosové trati v Miroslavi a trenér našich svěřenců.
                </p>
              </div>
            </div>

            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <img class="w-24 h-24 rounded-full object-cover" src="images/michal_jancik_193px.jpg"
                     alt="Michal Jancik" width="193" height="193"/>
                <h5 class="text-mx-white font-semibold text-base">Michal Jančík</h5>
                <p class="text-mx-orange text-sm font-medium">Servisman</p>
                <p class="text-gray-400 text-sm leading-relaxed">
                  Zkušený mechanik, který se stará o stroje všech našich jezdců.
                </p>
              </div>
            </div>

            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <img class="w-24 h-24 rounded-full object-cover" src="images/katerina_holcmannova_193px.webp"
                     loading="lazy" alt="Katerina Holcmannova" width="193" height="193"/>
                <h5 class="text-mx-white font-semibold text-base">Kateřina Holcmannová</h5>
                <p class="text-mx-orange text-sm font-medium">Fotografka</p>
                <p class="text-gray-400 text-sm leading-relaxed">
                  Katka pro vás tvoří profi fotky na sítě, do alba nebo třeba na web.
                </p>
              </div>
            </div>

            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <img class="w-24 h-24 rounded-full object-cover" src="images/marek_dubovan_193px.webp"
                     loading="lazy" alt="Marek Dubovan" width="193" height="193"/>
                <h5 class="text-mx-white font-semibold text-base">Marek Dubovan</h5>
                <p class="text-mx-orange text-sm font-medium">Video-pilot</p>
                <p class="text-gray-400 text-sm leading-relaxed">
                  Pilotovat dron, natočit video a pak ho zpracovat umí Marek.
                </p>
              </div>
            </div>

          </div>
          <div class="swiper-button-prev !text-white"></div>
          <div class="swiper-button-next !text-white"></div>
        </div>
      </div>
    </div>
  </section>


  {{-- ═══ PITBIKE ARÉNA ══════════════════════════════════════════ --}}
  <section class="section-lg">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

        <div data-aos="fade-right">
          <img src="images/pitarena_zasady.webp" loading="lazy" width="652" height="491"
               alt="bezpecnost pitbike" class="w-full rounded-xl shadow-2xl shadow-black/50">
        </div>

        <div data-aos="fade-left">
          <div class="section-divider"></div>
          <h2 class="section-title">Pitbike aréna</h2>
          <h4 class="text-lg font-light mt-3 mb-5" style="color: rgba(220,220,240,0.7)">
            Bezpečnost na prvním místě, kvalitní služby a profesionální přístup.
          </h4>
          <div class="space-y-3 text-gray-400 leading-relaxed">
            <p>
              Svěřte svůj motokrosový zážitek do našich rukou. Naše motokrosová trať klade velký důraz na
              <strong class="text-mx-white">bezpečnost, kvalitu a profesionální přístup</strong>.
            </p>
            <p>
              S pečlivě udržovanou tratí, moderním vybavením a <strong class="text-mx-white">zkušenými trenéry</strong>
              vám poskytujeme ideální prostředí pro trénink, volné jízdy a závody.
            </p>
            <p>
              Bez ohledu na váš věk nebo úroveň dovedností, u nás se budete cítit v bezpečí a vychutnáte si
              <strong class="text-mx-white">motokrosové dobrodružství</strong>.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>


  @includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

  @includeIf('sections.service')

  @includeIf('sections/testimonials')


  {{-- ═══ PODPOROVATELÉ (parallax + Swiper) ════════════════════ --}}
  @php
  $sponsors = [
    [
      'logo'        => 'images/sponzors/ycf.svg',
      'alt'         => 'logo YCF sponzor',
      'name'        => 'YCF Riding',
      'tagline'     => 'E-Shop zbraní na kolech',
      'description' => 'Francouzské pitbike motocykly pro začátečníky i pokročilé jezdce.',
      'url'         => 'https://www.ycf-riding.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'ycf-riding.cz',
    ],
    [
      'logo'        => 'images/sponzors/SOS_beer.webp',
      'alt'         => 'logo SOS BEER sponzor',
      'name'        => 'SOS BEER SERVIS',
      'tagline'     => 'Váš parťák na každou příležitost',
      'description' => 'Vše, co potřebujete k dokonalé oslavě, vybavení hospody, kavárny či baru.',
      'url'         => 'https://www.sosbeerservis.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'sosbeerservis.cz',
    ],
    [
      'logo'        => 'images/sponzors/life_like.svg',
      'alt'         => 'logo life-like sponzor',
      'name'        => 'LIFE LIKE',
      'tagline'     => 'E-shop plný dobrůtek',
      'description' => 'Ořechy, čokoláda a naše Twistry jsou skvělá kombinace.',
      'url'         => 'https://www.lifelike.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'lifelike.cz',
    ],
    [
      'logo'        => 'images/sponzors/ondraweb.webp',
      'alt'         => 'logo OndraWeb sponzor',
      'name'        => 'Ondřej Kriška',
      'tagline'     => 'Webové stránky a aplikace',
      'description' => 'Pomáhám firmám vytvářet webové stránky, které jsou klíčovým pilířem jejich byznysu.',
      'url'         => 'https://ondraweb.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'ondraweb.cz',
    ],
    [
      'logo'        => 'images/sponzors/ardcar_logo.svg',
      'alt'         => 'logo ardcar sponzor',
      'name'        => 'ARD Car',
      'tagline'     => 'Rodinný autoservis',
      'description' => 'Rodinný autoservis se specializací na Volvo, Land Rover a Jaguar.',
      'url'         => 'https://ardcar.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'ardcar.cz',
    ],
    [
      'logo'        => 'images/sponzors/johnnyservis.svg',
      'alt'         => 'logo JOHNNY SERVIS sponzor',
      'name'        => 'JOHNNY SERVIS',
      'tagline'     => 'Zázemí pro každou příležitost',
      'description' => 'Již více než 25 let pomáháme leaderům na trhu se zázemím pro jejich podnikání v terénu.',
      'url'         => 'https://www.johnnyservis.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'johnnyservis.cz',
    ],
    [
      'logo'        => 'images/sponzors/j-mont-logo.svg',
      'alt'         => 'logo J-MONT sponzor',
      'name'        => 'J-MONT',
      'tagline'     => 'Stavební firma',
      'description' => 'Dřevostavby, chaty, chalupy, altány, střechy, parkovací domy apod.',
      'url'         => 'https://j-mont.webnode.cz/?utm_source=pitarena&utm_medium=referral&utm_campaign=sponsoring&utm_content=carousel&utm_term=no_KW',
      'label'       => 'j-mont.webnode.cz',
    ],
  ];
  @endphp
  <section
    class="parallax-hero"
    style="--hero-img: url('{{ asset('images/sponzor_background.webp') }}');"
  >
    <div class="parallax-hero-content w-full py-20">
      <div class="page-container">
        <div data-aos="fade-up">
          <div class="section-divider section-divider-center"></div>
          <h2 class="section-title-center mb-10">Naši podporovatelé</h2>
        </div>

        <div class="swiper"
             data-autoplay="4000" data-loop="true" data-nav="true"
             data-slides-per-view-sm="2" data-slides-per-view-lg="3"
             data-space-between="24">
          <div class="swiper-wrapper pb-10">

            @foreach($sponsors as $sponsor)
            <div class="swiper-slide h-auto">
              <div class="h-full rounded-xl p-6 text-center flex flex-col items-center gap-3" style="background: rgba(15,15,26,0.92); border: 1px solid rgba(255,255,255,0.08); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);">
                <div class="bg-white rounded-lg p-2 w-24 h-24 flex items-center justify-center shrink-0">
                  <img class="w-full h-full object-contain" src="{{ $sponsor['logo'] }}" loading="lazy"
                       alt="{{ $sponsor['alt'] }}" width="193" height="193"/>
                </div>
                <h5 class="text-mx-white font-semibold text-base">{{ $sponsor['name'] }}</h5>
                <p class="text-mx-orange text-sm font-medium">{{ $sponsor['tagline'] }}</p>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $sponsor['description'] }}</p>
                <a class="btn-primary btn-sm mt-auto" href="{{ $sponsor['url'] }}" target="_blank">{{ $sponsor['label'] }}</a>
              </div>
            </div>
            @endforeach

          </div>
          <div class="swiper-button-prev !text-white"></div>
          <div class="swiper-button-next !text-white"></div>
        </div>

        <p class="text-center text-gray-300 text-sm mt-8 bg-mx-dark/80 rounded-xl px-6 py-4 border border-mx-gray2">
          Staňte se sponzorem mladých závodníků. Získejte reklamu u nás na webu, reklamní banner na trati během závodů a další výhody.
          <br class="hidden sm:block">
          <a class="btn-outline btn-sm mt-3 inline-flex" href="{{ route('o-nas') }}" target="_blank">Napište nám</a>
        </p>

      </div>
    </div>
  </section>


  {{-- ═══ BLOG ═══════════════════════════════════════════════════ --}}
  <section class="section-lg">
    <div class="page-container">

      <div class="text-center mb-12" data-aos="fade-up">
        <div class="section-divider section-divider-center"></div>
        <h2 class="section-title">Tipy a rady<br>z oblasti motokrosu</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <article class="card-dark flex flex-col">
          <a href="{{ route('blog.show', ['blog' => 'pitbike-motokros-cesta-k-rozvoji-deti-v-digitalni-dobe']) }}">
            <img src="images/blog/pitbike-motokros-cesta-k-rozvoji-deti-v-digitalni-dobe_small.webp"
                 loading="lazy" width="418" height="315" alt="chyby v pitbike"
                 class="w-full aspect-[418/315] object-cover">
          </a>
          <div class="card-dark-body flex flex-col flex-1 gap-3">
            <h5 class="text-mx-white font-semibold leading-snug">
              <a href="{{ route('blog.show', ['blog' => 'pitbike-motokros-cesta-k-rozvoji-deti-v-digitalni-dobe']) }}"
                 class="hover:text-mx-orange transition-colors">
                Pitbike motokros: Cesta k rozvoji dětí v digitální době
              </a>
            </h5>
            <p class="text-gray-400 text-sm leading-relaxed flex-1">
              Zjistěte, proč je pitbike motokros ideální aktivitou pro děti v digitální době.
              Posiluje sebevědomí, zodpovědnost, fyzičku i vztahy v rodině.
            </p>
            <div class="flex items-center gap-3 pt-3 border-t border-mx-gray2 mt-auto">
              <img class="w-9 h-9 rounded-full" src="{{ url('images/logo/pitarena_logo_grey_74x74.png') }}"
                   width="74" height="74" alt="logo pitarena">
              <div class="text-xs text-gray-400">
                <span>tým Pitarény</span>
                <span class="mx-1">·</span>
                <time datetime="2025-07-01">1. července 2025</time>
              </div>
            </div>
          </div>
        </article>

        <article class="card-dark flex flex-col">
          <a href="{{ route('blog.show', ['blog' => 'jak-zacit-s-motokrosem-u-deti']) }}">
            <img src="images/blog/jak-zacit-s-motokrosem-u-deti-pitarena-cz.webp"
                 loading="lazy" width="418" height="315" alt="motokros deti"
                 class="w-full aspect-[418/315] object-cover">
          </a>
          <div class="card-dark-body flex flex-col flex-1 gap-3">
            <h5 class="text-mx-white font-semibold leading-snug">
              <a href="{{ route('blog.show', ['blog' => 'jak-zacit-s-motokrosem-u-deti']) }}"
                 class="hover:text-mx-orange transition-colors">
                Jak začít s motokrosem u dětí: Kompletní průvodce pro rodiče
              </a>
            </h5>
            <p class="text-gray-400 text-sm leading-relaxed flex-1">
              Motokros může být skvělým sportem pro děti.
              Pomáhá jim vyvíjet fyzickou zdatnost, zlepšuje koordinaci a zvyšuje sebevědomí.
            </p>
            <div class="flex items-center gap-3 pt-3 border-t border-mx-gray2 mt-auto">
              <img class="w-9 h-9 rounded-full" src="{{ url('images/logo/pitarena_logo_grey_74x74.png') }}"
                   loading="lazy" width="74" height="74" alt="logo pitarena">
              <div class="text-xs text-gray-400">
                <span>tým Pitarény</span>
                <span class="mx-1">·</span>
                <time datetime="2023-06-02">2. června 2023</time>
              </div>
            </div>
          </div>
        </article>

        <article class="card-dark flex flex-col">
          <a href="{{ route('blog.show', ['blog' => 'klasicky-motokros-vs-pitbike-motokros']) }}">
            <img src="images/blog/klasicky-motokros-vs-pitbike-motokros-pitarena-cz.webp"
                 loading="lazy" width="418" height="315" alt="motokros vs pitbike"
                 class="w-full aspect-[418/315] object-cover">
          </a>
          <div class="card-dark-body flex flex-col flex-1 gap-3">
            <h5 class="text-mx-white font-semibold leading-snug">
              <a href="{{ route('blog.show', ['blog' => 'klasicky-motokros-vs-pitbike-motokros']) }}"
                 class="hover:text-mx-orange transition-colors">
                Klasický motokros vs. Pitbike motokros: rozdíl je nejen v ceně
              </a>
            </h5>
            <p class="text-gray-400 text-sm leading-relaxed flex-1">
              Zjistěte, jaký druh motocyklu je pro vás ten pravý, porovnejte náklady
              a připravte se na cestu plnou vzrušení, výzev a nezapomenutelných závodů.
            </p>
            <div class="flex items-center gap-3 pt-3 border-t border-mx-gray2 mt-auto">
              <img class="w-9 h-9 rounded-full" src="{{ url('images/logo/pitarena_logo_grey_74x74.png') }}"
                   loading="lazy" width="74" height="74" alt="logo pitarena seda">
              <div class="text-xs text-gray-400">
                <span>tým Pitarény</span>
                <span class="mx-1">·</span>
                <time datetime="2023-06-28">28. června 2023</time>
              </div>
            </div>
          </div>
        </article>

      </div>

      <div class="text-center mt-8">
        <a class="btn-primary" href="{{ route('blog.index') }}">Další články</a>
      </div>

    </div>
  </section>


  @includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

  @includeIf('sections.akce')

  @includeIf('sections.akce-manual')

@endsection

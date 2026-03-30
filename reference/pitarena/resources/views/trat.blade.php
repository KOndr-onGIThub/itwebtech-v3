@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'trat')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="https://pitarena.cz/trat">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz/trat">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Trať</li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section
  class="parallax-hero"
  style="--hero-img: url('{{ asset('images/motokros_pitbike_pravice_action_view.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">Pitbike motokrosová trať</h1>
    <p class="text-gray-300 mt-2">Pitbike Aréna → pitarena.cz</p>
  </div>
</section>

{{-- Statistiky --}}
<div class="py-16" style="border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
  <div class="page-container">
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-10 lg:divide-x lg:divide-white/[0.05]">
      <div class="text-center lg:text-left lg:pl-8 first:pl-0" data-aos="fade-up">
        <div class="stat-number text-mx-orange">1200</div>
        <div class="stat-label">metrů</div>
      </div>
      <div class="text-center lg:text-left lg:pl-8" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-number">11</div>
        <div class="stat-label">zatáček</div>
      </div>
      <div class="text-center lg:text-left lg:pl-8 col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-number">100%</div>
        <div class="stat-label">zábavy</div>
      </div>
    </div>
  </div>
</div>

{{-- Rozvrh --}}
@php
  $season = now()->format('m');
  $isSummer = ($season > 3 && $season < 11);

  $closures = [
    ['from' => '2026-04-06', 'to' => '2026-04-11', 'type' => 'zavod', 'note' => true],
    ['from' => '2026-05-30', 'to' => null,         'type' => 'zavod', 'note' => false],
    ['from' => '2026-06-27', 'to' => null,         'type' => 'zavod', 'note' => false],
    ['from' => '2026-07-03', 'to' => '2026-07-11', 'type' => 'kemp',  'note' => false],
    ['from' => '2026-07-24', 'to' => '2026-08-01', 'type' => 'kemp',  'note' => false],
    ['from' => '2026-09-26', 'to' => null,         'type' => 'zavod', 'note' => false],
    ['from' => '2026-09-28', 'to' => '2026-10-03', 'type' => 'zavod', 'note' => true],
  ];
  $half = (int) ceil(count($closures) / 2);
@endphp

<section class="section bg-mx-black">
  <div class="page-container">

    <div class="text-center mb-8" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Základní rozpis pro trať</h2>
    </div>

    {{-- Legenda --}}
    <div class="flex flex-wrap justify-center gap-4 mb-6" data-aos="fade-up" data-aos-delay="100">
      <span class="flex items-center gap-2 text-sm">
        <span class="w-4 h-4 rounded bg-green-600 inline-block"></span> Volné jízdy
      </span>
      <span class="flex items-center gap-2 text-sm">
        <span class="w-4 h-4 rounded bg-blue-600 inline-block"></span> Trénink
      </span>
      <span class="flex items-center gap-2 text-sm">
        <span class="w-4 h-4 rounded bg-mx-orange inline-block"></span> Závody
      </span>
    </div>

    {{-- Tabs Alpine --}}
    <div x-data="{ tab: '{{ $isSummer ? 'summer' : 'winter' }}' }">

      <div class="flex justify-center mb-6">
        <div class="inline-flex bg-mx-gray border border-mx-gray2 rounded-lg p-1 gap-1">
          <button @click="tab='summer'"
                  :class="tab==='summer' ? 'bg-mx-orange text-white' : 'text-gray-400 hover:text-white'"
                  class="px-5 py-2 rounded-md text-sm font-semibold transition-all duration-200">
            ☀ LÉTO
          </button>
          <button @click="tab='winter'"
                  :class="tab==='winter' ? 'bg-mx-orange text-white' : 'text-gray-400 hover:text-white'"
                  class="px-5 py-2 rounded-md text-sm font-semibold transition-all duration-200">
            ❄ ZIMA
          </button>
        </div>
      </div>

      {{-- Panels wrapper --}}
      <div class="relative overflow-hidden">

      {{-- LÉTO --}}
      <div x-show="tab==='summer'"
           x-transition:enter="transition-opacity duration-300 ease-out"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="absolute transition-opacity duration-200 ease-in"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="w-full">
        <div class="overflow-x-auto" style="overflow-y:hidden">
        <p class="text-sm text-gray-400 mb-3 text-center">Duben – Říjen</p>
        @php
          $sPx   = 36;   // px na hodinu
          $sMin  = 9;
          $sMax  = 19;
          $sTot  = ($sMax - $sMin) * $sPx; // 360px
          $sSched = [
            'po' => [
              ['s'=>9, 'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>17,'e'=>19,'t'=>'tým B',       'sub'=>'17–19','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
            ],
            'ut' => [
              ['s'=>9, 'e'=>15,'t'=>'Volné jízdy','sub'=>'09–15','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>15,'e'=>17,'t'=>'MX Start',    'sub'=>'15–17','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
              ['s'=>17,'e'=>19,'t'=>'tým A',       'sub'=>'17–19','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
            ],
            'st' => [
              ['s'=>9, 'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>17,'e'=>19,'t'=>'tým B',       'sub'=>'17–19','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
            ],
            'ct' => [
              ['s'=>9, 'e'=>15,'t'=>'Volné jízdy','sub'=>'09–15','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>15,'e'=>17,'t'=>'MX Start',    'sub'=>'15–17','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
              ['s'=>17,'e'=>19,'t'=>'Volné jízdy','sub'=>'17–19','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
            ],
            'pa' => [
              ['s'=>9, 'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>17,'e'=>19,'t'=>'tým A',       'sub'=>'17–19','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
            ],
            'so' => [
              ['s'=>9, 'e'=>19,'t'=>'Volné jízdy','sub'=>'09–19','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
            ],
            'ne' => [
              ['s'=>9, 'e'=>10,'t'=>'Volné jízdy','sub'=>'9–10', 'cls'=>'bg-green-800/80 border-l-2 border-green-500'],
              ['s'=>10,'e'=>12,'t'=>'mini',        'sub'=>'10–12','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
              ['s'=>12,'e'=>19,'t'=>'Volné jízdy','sub'=>'12–19','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
            ],
          ];
          $sDays      = ['po','ut','st','ct','pa','so','ne'];
          $sDayLabels = ['Pondělí','Úterý','Středa','Čtvrtek','Pátek','Sobota','Neděle'];
        @endphp

        <div style="min-width:580px">

          {{-- Záhlaví dnů --}}
          <div class="flex mb-0.5" style="padding-left:2.75rem; gap:2px;">
            @foreach($sDayLabels as $dl)
            <div class="flex-1 text-center text-[11px] text-gray-400 font-semibold py-1.5 bg-mx-gray2 rounded-sm">{{ $dl }}</div>
            @endforeach
          </div>

          {{-- Timeline --}}
          <div class="flex" style="height:{{ $sTot }}px">

            {{-- Osa času --}}
            <div class="flex-shrink-0 relative" style="width:2.75rem; height:{{ $sTot }}px">
              @for($h = $sMin; $h <= $sMax; $h++)
              <div class="absolute right-1 text-[10px] text-gray-500 font-mono leading-none"
                   style="top:{{ ($h-$sMin)*$sPx }}px; transform:translateY(-50%);">
                {{ sprintf('%02d',$h) }}:00
              </div>
              @endfor
            </div>

            {{-- Sloupce dnů + mřížka --}}
            <div class="relative flex flex-1 gap-0.5">

              {{-- Horizontální čáry --}}
              @for($h = $sMin; $h <= $sMax; $h++)
              <div class="absolute left-0 right-0 pointer-events-none"
                   style="top:{{ ($h-$sMin)*$sPx }}px; border-top:1px solid rgba(255,255,255,{{ $h==$sMin||$h==$sMax ? '0.12' : '0.05' }})"></div>
              @endfor

              {{-- Den --}}
              @foreach($sDays as $dk)
              <div class="relative flex-1" style="height:{{ $sTot }}px">
                @foreach($sSched[$dk] as $ev)
                @php
                  $evTop = ($ev['s'] - $sMin) * $sPx + 1;
                  $evH   = ($ev['e'] - $ev['s']) * $sPx - 2;
                @endphp
                <div class="absolute left-0 right-0 {{ $ev['cls'] }} rounded-sm overflow-hidden flex flex-col items-center justify-center text-center"
                     style="top:{{ $evTop }}px; height:{{ $evH }}px; padding:2px 3px;">
                  <span class="text-white font-semibold leading-tight" style="font-size:10px">{{ $ev['t'] }}</span>
                  @if($evH > 38)
                  <span class="text-white/70 leading-none" style="font-size:9px">{{ $ev['sub'] }}</span>
                  @endif
                </div>
                @endforeach
              </div>
              @endforeach

            </div>
          </div>
        </div>
        </div>{{-- /overflow-x-auto --}}
      </div>

      {{-- ZIMA --}}
      <div x-show="tab==='winter'"
           x-transition:enter="transition-opacity duration-300 ease-out"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="absolute transition-opacity duration-200 ease-in"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="w-full">
        <div class="overflow-x-auto" style="overflow-y:hidden">
        <p class="text-sm text-gray-400 mb-3 text-center">Listopad – Březen</p>
        @php
          $wPx   = 36;
          $wMin  = 9;
          $wMax  = 17;
          $wTot  = ($wMax - $wMin) * $wPx; // 288px
          $wSched = [
            'po' => [['s'=>9,'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500']],
            'ut' => [['s'=>9,'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500']],
            'st' => [['s'=>9,'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500']],
            'ct' => [['s'=>9,'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500']],
            'pa' => [['s'=>9,'e'=>17,'t'=>'Volné jízdy','sub'=>'09–17','cls'=>'bg-green-800/80 border-l-2 border-green-500']],
            'so' => [
              ['s'=>9, 'e'=>12,'t'=>'tým A',       'sub'=>'09–12','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
              ['s'=>12,'e'=>17,'t'=>'Volné jízdy','sub'=>'12–17','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
            ],
            'ne' => [
              ['s'=>9, 'e'=>12,'t'=>'tým B+mini',  'sub'=>'09–12','cls'=>'bg-blue-800/80 border-l-2 border-blue-500'],
              ['s'=>12,'e'=>17,'t'=>'Volné jízdy','sub'=>'12–17','cls'=>'bg-green-800/80 border-l-2 border-green-500'],
            ],
          ];
          $wDays      = ['po','ut','st','ct','pa','so','ne'];
          $wDayLabels = ['Pondělí','Úterý','Středa','Čtvrtek','Pátek','Sobota','Neděle'];
        @endphp

        <div style="min-width:580px">

          {{-- Záhlaví dnů --}}
          <div class="flex mb-0.5" style="padding-left:2.75rem; gap:2px;">
            @foreach($wDayLabels as $dl)
            <div class="flex-1 text-center text-[11px] text-gray-400 font-semibold py-1.5 bg-mx-gray2 rounded-sm">{{ $dl }}</div>
            @endforeach
          </div>

          {{-- Timeline --}}
          <div class="flex" style="height:{{ $wTot }}px">

            {{-- Osa času --}}
            <div class="flex-shrink-0 relative" style="width:2.75rem; height:{{ $wTot }}px">
              @for($h = $wMin; $h <= $wMax; $h++)
              <div class="absolute right-1 text-[10px] text-gray-500 font-mono leading-none"
                   style="top:{{ ($h-$wMin)*$wPx }}px; transform:translateY(-50%);">
                {{ sprintf('%02d',$h) }}:00
              </div>
              @endfor
            </div>

            {{-- Sloupce dnů + mřížka --}}
            <div class="relative flex flex-1 gap-0.5">

              {{-- Horizontální čáry --}}
              @for($h = $wMin; $h <= $wMax; $h++)
              <div class="absolute left-0 right-0 pointer-events-none"
                   style="top:{{ ($h-$wMin)*$wPx }}px; border-top:1px solid rgba(255,255,255,{{ $h==$wMin||$h==$wMax ? '0.12' : '0.05' }})"></div>
              @endfor

              {{-- Den --}}
              @foreach($wDays as $dk)
              <div class="relative flex-1" style="height:{{ $wTot }}px">
                @foreach($wSched[$dk] as $ev)
                @php
                  $evTop = ($ev['s'] - $wMin) * $wPx + 1;
                  $evH   = ($ev['e'] - $ev['s']) * $wPx - 2;
                @endphp
                <div class="absolute left-0 right-0 {{ $ev['cls'] }} rounded-sm overflow-hidden flex flex-col items-center justify-center text-center"
                     style="top:{{ $evTop }}px; height:{{ $evH }}px; padding:2px 3px;">
                  <span class="text-white font-semibold leading-tight" style="font-size:10px">{{ $ev['t'] }}</span>
                  @if($evH > 38)
                  <span class="text-white/70 leading-none" style="font-size:9px">{{ $ev['sub'] }}</span>
                  @endif
                </div>
                @endforeach
              </div>
              @endforeach

            </div>
          </div>
        </div>
        </div>{{-- /overflow-x-auto --}}
      </div>

      </div>{{-- /panels wrapper --}}

    </div>{{-- /tabs --}}

    {{-- Uzavírky --}}
    <div class="mt-8 bg-mx-gray border border-mx-gray2 rounded-xl p-5">
      <p class="font-semibold text-mx-white mb-4">Kdy trať NENÍ dostupná pro volné jízdy?</p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2">
        @foreach($closures as $c)
        @php
          $from = \Carbon\Carbon::parse($c['from']);
          $to   = $c['to'] ? \Carbon\Carbon::parse($c['to']) : null;
          $label = $c['type'] === 'zavod' ? 'Závod' : 'Kemp';
          $badgeClass = $c['type'] === 'zavod' ? 'badge-orange' : 'bg-blue-900/40 text-blue-300 text-xs px-2.5 py-0.5 rounded-full font-semibold';
        @endphp
        <div class="flex items-center justify-between py-2 border-b border-mx-gray2 text-sm">
          <span class="text-gray-300">
            @if($to)
              {{ $from->translatedFormat('j. n.') }} – {{ $to->translatedFormat('j. n. Y') }}@if($c['note'])<sup>*</sup>@endif
            @else
              {{ $from->translatedFormat('j. n. Y') }}
            @endif
          </span>
          <span class="{{ $badgeClass }}">{{ $label }}</span>
        </div>
        @endforeach
      </div>
      <p class="text-xs text-gray-400 mt-3">* Trať uzavřena od pondělí daného týdne kvůli přípravě na závod.</p>
    </div>

    {{-- Karty: Závody / Volné jízdy / Tréninky --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">

      <div class="card-dark" data-aos="fade-up">
        <div class="relative overflow-hidden">
          <img src="{{ asset('images/home-09.webp') }}" loading="lazy" alt="závody pitbike"
               class="w-full h-48 object-cover"/>
          <span class="absolute top-3 left-3 badge-orange">Závody</span>
        </div>
        <div class="card-dark-body text-sm text-gray-400 space-y-1">
          @foreach([['11.04.2026','Pravice','https://maps.app.goo.gl/JvJrTme5z1gK1Tqh7'],['30.05.2026','Smrk','https://maps.app.goo.gl/as6ytWzjNYpFvh9Z7'],['27.06.2026','Vranov','https://maps.app.goo.gl/g9CV32g9v5YUR2rg9'],['26.09.2026','Miroslav','https://maps.app.goo.gl/n4sVik1v81UTAPwr8'],['03.10.2026','Pravice','https://maps.app.goo.gl/JvJrTme5z1gK1Tqh7']] as [$date,$place,$url])
          <div class="flex justify-between">
            <span>{{ $date }}</span>
            <a href="{{ $url }}" target="_blank" class="text-mx-gold hover:underline flex items-center gap-1">
              <i class="fa-solid fa-location-dot text-xs"></i> {{ $place }}
            </a>
          </div>
          @endforeach
        </div>
      </div>

      <div class="card-dark" data-aos="fade-up" data-aos-delay="150">
        <div class="relative overflow-hidden">
          <img src="{{ asset('images/home-05.webp') }}" loading="lazy" alt="volné jízdy pitbike"
               class="w-full h-48 object-cover"/>
          <span class="absolute top-3 left-3 inline-block px-2.5 py-0.5 rounded-full bg-green-600/80 text-white text-xs font-semibold">Volné jízdy</span>
        </div>
        <div class="card-dark-body text-sm text-gray-400">
          <div class="flex items-center gap-3 mb-3">
            <img class="w-10 h-10 rounded-full object-cover" loading="lazy"
                 src="{{ asset('images/Stanislav_Holcmann_74x74.jpg') }}" alt="Stanislav Holcmann foto"/>
            <div>
              <p class="font-semibold text-mx-white text-sm">Stanislav Holcmann</p>
              <a href="tel:+420704221663" class="text-xs text-mx-gold hover:underline">(+420) 704 221 663</a>
            </div>
          </div>
          <p class="font-semibold text-mx-white mb-1">Ceny volných jízd:</p>
          <ul class="space-y-0.5 text-xs">
            <li>• pitbike Děti 150,- Kč/den</li>
            <li>• pitbike Dospělí 250,- Kč/den</li>
            <li>• velká kroska 350,- Kč/den</li>
          </ul>
        </div>
      </div>

      <div class="card-dark" data-aos="fade-up" data-aos-delay="300">
        <div class="relative overflow-hidden">
          <img src="{{ asset('images/trenink_pitbike_motokros.webp') }}" loading="lazy" alt="tréninky pitbike"
               class="w-full h-48 object-cover"/>
          <span class="absolute top-3 left-3 inline-block px-2.5 py-0.5 rounded-full bg-blue-600/80 text-white text-xs font-semibold">Tréninky</span>
        </div>
        <div class="card-dark-body text-sm text-gray-400">
          <div class="flex items-center gap-3">
            <img class="w-10 h-10 rounded-full object-cover" loading="lazy"
                 src="{{ asset('images/david_jonas.png') }}" alt="David Jonáš"/>
            <div>
              <p class="font-semibold text-mx-white text-sm">David Jonáš</p>
              <p class="text-xs text-gray-400">Trenér</p>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

{{-- Motokrosová trať na Moravě --}}
<section class="section bg-mx-black">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <div data-aos="fade-right">
        <div class="section-divider"></div>
        <h2 class="section-title">Motokrosová trať na Moravě</h2>
        <p class="text-gray-400 mt-4 leading-relaxed">
          Zveme tě na naši moderní motokrosovou trať na Jižní Moravě, která je skvělým místem pro všechny nadšence do motokrosu.
          Nacházíme se na pomezí okresů Znojmo a Břeclav, kde naše trať nabízí <strong class="text-mx-white">ideální podmínky pro jezdce všech úrovní</strong>.
        </p>
        <p class="text-gray-400 mt-3 leading-relaxed">
          Trať je navržená tak, aby byla vhodná nejen <strong class="text-mx-white">pro jízdu na pitbicích ale i pro silné MX mašiny</strong>.
          Jezdí u nás 5letý děcka, profi závodníci i starý pardálové. Tak dojeď taky.
        </p>
      </div>
      <div data-aos="fade-left">
        <img src="{{ asset('images/motokros_na_morave.webp') }}" loading="lazy"
             width="652" height="491" alt="Motokros na Moravě"
             class="w-full rounded-xl shadow-2xl shadow-black/50"/>
      </div>
    </div>
  </div>
</section>

{{-- Co nabízíme --}}
<section class="section bg-mx-black">
  <div class="page-container">

    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Co nabízíme?</h2>
    </div>

    {{-- Polygon + video --}}
    <div class="mt-16 lg:mt-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <div class="order-2 lg:order-1" data-aos="fade-right">
        <div class="aspect-video rounded-xl overflow-hidden">
          <iframe width="652" height="491"
                  src="https://www.youtube-nocookie.com/embed/HN2VFD4WnDc"
                  title="Polygon pro trénování"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  allowfullscreen
                  class="w-full h-full"></iframe>
        </div>
      </div>
      <div class="order-1 lg:order-2" data-aos="fade-left">
        <h3 class="text-2xl font-semibold text-mx-white mb-3">Polygon pro trénování</h3>
        <p class="text-gray-400 leading-relaxed mb-3">
          Máme k dispozici rozlehlý polygon. Vhodný jak pro začátečníky, kteří si ještě na trať netroufnou, tak pro trénování základních praktik jízdy na terénním motocyklu.
        </p>
        <p class="text-gray-400 leading-relaxed">
          V tomhle videu se můžeš podívat jak na polygonu probíhá trénink.
          Pokud přijedeš v časech volných jízd (viz rozpis nahoře) pak je tento prostor volný právě pro tebe.
        </p>
      </div>
    </div>

    {{-- Akademie + video --}}
    <div class="mt-16 lg:mt-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <div data-aos="fade-right">
        <h4 class="text-2xl font-semibold text-mx-white mb-3">Akademie a volné jízdy</h4>
        <p class="text-gray-400 leading-relaxed mb-3">
          Zapojit se můžeš do volných jízd pro veřejnost (vyber si termín v rozpisu nahoře).
          Nebo se rovnou zapiš do některého z našich <a href="{{ route('programy') }}" class="text-mx-gold hover:underline">programů</a>.
        </p>
        <p class="text-gray-400 leading-relaxed">
          Ve videu nahlédněte jak fajně si můžete zajezdit na pitbike motokrosové trati v Pravicích.<br>
          <strong class="text-mx-white">Pro všechny typy motocyklů:</strong> Ať už máte menší pitbike nebo plnokrevný motokrosový motocykl, naše trať je připravena vám nabídnout nezapomenutelný zážitek.
        </p>
      </div>
      <div data-aos="fade-left">
        <div class="aspect-video rounded-xl overflow-hidden">
          <iframe width="886" height="668"
                  src="https://www.youtube-nocookie.com/embed/bdlbJSpva_o"
                  title="Volné jízdy pitbike"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  allowfullscreen
                  class="w-full h-full"></iframe>
        </div>
      </div>
    </div>

  </div>
</section>

{{-- Co budeš potřebovat --}}
<section class="section bg-mx-black">
  <div class="page-container">

    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Co budeš potřebovat?</h2>
    </div>

    <div class="mt-16 lg:mt-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <div data-aos="fade-right">
        <img src="{{ asset('images/prilba_rukavice.webp') }}" loading="lazy"
             width="652" height="491" alt="Výbava na pitbike ježdění"
             class="w-full rounded-xl shadow-2xl shadow-black/50"/>
      </div>
      <div data-aos="fade-left">
        <h4 class="text-xl font-semibold text-mx-white mb-4">Základní vybavení</h4>
        <ol class="space-y-2 text-gray-400 text-sm">
          @foreach(['terénní motorku','solidní helmu','chrániče na hrudník, lokty a kolena','pevné boty, kalhoty a dres','a nezapomeň na rukavice a brýle'] as $i => $item)
          <li class="flex items-start gap-3">
            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-mx-orange/20 text-mx-orange text-xs flex items-center justify-center font-bold">{{ $i+1 }}</span>
            {{ $item }}
          </li>
          @endforeach
        </ol>
        <p class="text-gray-400 text-sm mt-4 leading-relaxed">
          Pokud nemáš své vlastní vybavení, nemusíš se bát.
          Po telefonické domluvě ti vše potřebné rádi půjčíme.
        </p>
        <p class="text-mx-white font-semibold mt-3">Tak na co ještě čekáš? Vydej se k nám na trať.</p>
        <p class="text-gray-400 text-sm mt-1">Těšíme se</p>
      </div>
    </div>

  </div>
</section>

{{-- Poloha --}}
<section class="bg-mx-black py-20 relative overflow-hidden bg-grid">

  <div class="relative z-10 flex justify-center items-center px-4">
    <div class="bg-mx-dark border border-mx-gray3 rounded-lg p-10 max-w-sm w-full text-center shadow-card" data-aos="fade-up">

      <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-lg bg-mx-gray2 border border-mx-gray3 flex items-center justify-center">
          <i class="fa-solid fa-location-dot text-2xl text-mx-orange"></i>
        </div>
      </div>

      <h3 class="text-mx-light font-bold text-xl uppercase tracking-wide mb-1">PitAréna</h3>
      <p class="text-gray-400 text-sm mb-2">Stanislav Holcmann</p>
      <p class="text-gray-500 text-sm mb-8">Pravice 671 78</p>

      <a href="https://maps.app.goo.gl/CYCKw4MyKTBf2HVY6" target="_blank" rel="noopener noreferrer"
         class="btn-primary">
        <i class="fa-solid fa-map-location-dot"></i>
        Otevřít Google mapu
      </a>

    </div>
  </div>
</section>

{{-- Kontakt --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="flex flex-wrap items-center gap-4" data-aos="fade-up">
      <p class="text-gray-400 text-sm flex-1 min-w-52">
        Kdyby nebylo něco jasný, tak tady jsou kontakty:
      </p>
      <div class="flex flex-wrap gap-3">
        <a href="https://www.facebook.com/YCFCUPCZ" target="_blank" class="btn-primary btn-sm" data-aos="zoom-in" data-aos-delay="150">
          <i class="fa-brands fa-facebook-f"></i> Facebook
        </a>
        <a href="tel:+420704221663" class="btn-outline btn-sm" data-aos="zoom-in" data-aos-delay="250">
          <i class="fa-solid fa-phone"></i> 704 221 663
        </a>
        <a href="mailto:trener@pitarena.cz" class="btn-primary btn-sm" data-aos="zoom-in" data-aos-delay="350">
          <i class="fa-solid fa-paper-plane"></i> trener@pitarena.cz
        </a>
      </div>
    </div>
  </div>
</section>

@includeIf('sections.service')

@endsection


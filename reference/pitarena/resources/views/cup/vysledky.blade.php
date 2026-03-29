@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="Výsledky YCF Cup">
<meta property="og:description" content="Podívejte se na výsledky závodů YCF Cup a zjistěte, jak si vedli nejlepší závodníci.">
<meta property="og:image" content="{{ asset('images/cup/YCF_CUP_vysledky.png') }}">
<meta property="og:locale" content="cs_CZ">
<meta property="fb:app_id" content="966242223397117">
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="pitarena.cz">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="Výsledky YCF Cup">
<meta name="twitter:description" content="Podívejte se na výsledky závodů YCF Cup a zjistěte, jak si vedli nejlepší závodníci.">
<meta name="twitter:image" content="{{ asset('images/cup/YCF_CUP_vysledky.png') }}">
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
        <a itemprop="item" href="{{ route('cup') }}"><span itemprop="name">Závody</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Výsledky</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section class="parallax-hero" style="--hero-img: url('{{ asset('images/cup/YCF_CUP_vysledky.webp') }}')">
  <div class="parallax-hero-content">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="text-4xl lg:text-5xl font-semibold text-white">Výsledky YCF Cup</h1>
    </div>
  </div>
</section>

{{-- Konečné výsledky 2025 --}}
<section class="section bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-6">Konečné výsledky 2025</h2>
    </div>
    <div data-aos="fade-up" data-aos-duration="500">
      <a class="btn-primary inline-flex items-center gap-2 mb-4" href="{{ asset('files/vysledky/2025/__konecne_vysledky_YCF-CUP-2025.pdf') }}" target="_blank">
        <i class="fa-solid fa-eye"></i> Zobrazit výsledky
      </a>
      <p class="text-gray-400 text-sm leading-relaxed mt-3">
        Zeleně označení jezdci mají zdarma vstup na vyhlášení sezóny v Loděnicích 1.11. od 15:00.
        Jejich doprovod a všichni ostatní mohou zakoupit vstupenky nejpozději do 25.10.2025
        <a href="https://form.simpleshop.cz/Wr2nO/buy/" target="_blank" class="text-mx-gold underline">
          zde&nbsp;→&nbsp;Vstupenky na vyhlášení
        </a>
      </p>
    </div>
  </div>
</section>

{{-- Závody 2025 --}}
<section class="section bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Závody 2025</h2>
    </div>
    <div class="space-y-6">

      <div data-aos="fade-up" data-aos-duration="500">
        <h3 class="text-lg font-semibold text-mx-white mb-2">1. závod – Pravice – 5.4.2025</h3>
        <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250405_pravice.pdf') }}" target="_blank">
          <i class="fa-solid fa-eye"></i> Zobrazit výsledky
        </a>
      </div>

      <div data-aos="fade-up" data-aos-duration="500">
        <h3 class="text-lg font-semibold text-mx-white mb-2">2. závod – Znojmo – 17.5.2025</h3>
        <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250517_Naceratice.pdf') }}" target="_blank">
          <i class="fa-solid fa-eye"></i> Zobrazit výsledky
        </a>
      </div>

      <div data-aos="fade-up" data-aos-duration="500">
        <h3 class="text-lg font-semibold text-mx-white mb-2">3. závod – Pravice – 19.7.2025</h3>
        <div class="flex flex-wrap gap-2 justify-center">
          <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250719_Pravice.pdf') }}" target="_blank">
            <i class="fa-solid fa-eye"></i> Zobrazit výsledky
          </a>
          <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250719_Pravice-detail.pdf') }}" target="_blank">
            <i class="fa-solid fa-eye"></i> Detail
          </a>
        </div>
      </div>

      <div data-aos="fade-up" data-aos-duration="500">
        <h3 class="text-lg font-semibold text-mx-white mb-2">4. závod – Miroslav – 27.9.2025</h3>
        <div class="flex flex-wrap gap-2 justify-center">
          <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250927_Miroslav.pdf') }}" target="_blank">
            <i class="fa-solid fa-eye"></i> Zobrazit výsledky
          </a>
          <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20250927_Miroslav-detail.pdf') }}" target="_blank">
            <i class="fa-solid fa-eye"></i> Detail
          </a>
        </div>
      </div>

      <div data-aos="fade-up" data-aos-duration="500">
        <h3 class="text-lg font-semibold text-mx-white mb-2">5. závod – Pravice – 18.10.2025</h3>
        <a class="btn-outline btn-sm inline-flex items-center gap-2" href="{{ asset('files/vysledky/2025/20251018_Pravice.pdf') }}" target="_blank">
          <i class="fa-solid fa-eye"></i> Zobrazit výsledky
        </a>
      </div>

    </div>
  </div>
</section>

{{-- Archiv výsledků --}}
<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Archiv výsledků</h2>
    </div>
    <div x-data="{ open: null }" class="space-y-2">

      {{-- 2024 --}}
      <div class="card-dark">
        <button class="w-full flex justify-between items-center p-4 text-left gap-4"
                @click="open = open === 'y2024' ? null : 'y2024'">
          <span class="font-medium text-mx-white flex items-center gap-2">
            <i class="fa-solid fa-trophy text-mx-orange"></i> 2024
          </span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open === 'y2024' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'y2024' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-4 pb-4">
              <ul class="space-y-2">
                @foreach([
                  ['27.4.2024 - Pravice', 'files/vysledky/2024/20240427_pravice.pdf'],
                  ['08.6.2024 - Vranov', 'files/vysledky/2024/20240608_vranov.pdf'],
                  ['29.6.2024 - Načeratice', 'files/vysledky/2024/20240629_naceratice.pdf'],
                  ['31.8.2024 - Miroslav', 'files/vysledky/2024/20240831_miroslav.pdf'],
                  ['28.9.2024 - Smrk', 'files/vysledky/2024/20240928_smrk.pdf'],
                  ['26.10.2024 - Pravice', 'files/vysledky/2024/20241026_pravice.pdf'],
                ] as [$label, $file])
                <li>
                  <a href="{{ asset($file) }}" target="_blank" class="flex items-center gap-2 text-sm text-gray-300 hover:text-mx-gold transition-colors">
                    <i class="fa-solid fa-bolt text-mx-orange text-xs"></i> {{ $label }}
                  </a>
                </li>
                @endforeach
                <li>
                  <a href="{{ asset('files/vysledky/2024/_prubezne_vysledky_YCF-CUP-2024-6.pdf') }}" target="_blank" class="flex items-center gap-2 text-sm text-mx-white font-semibold hover:text-mx-gold transition-colors">
                    <i class="fa-solid fa-trophy text-mx-orange text-xs"></i> <strong>Kompletní výsledky 2024</strong>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {{-- 2023 – Tabulky vítězů --}}
      <div class="card-dark">
        <button class="w-full flex justify-between items-center p-4 text-left gap-4"
                @click="open = open === 'y2023t' ? null : 'y2023t'">
          <span class="font-medium text-mx-white flex items-center gap-2">
            <i class="fa-solid fa-trophy text-mx-orange"></i> 2023 – Tabulky vítězů
          </span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open === 'y2023t' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'y2023t' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-4 pb-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                @php
                $winners2023 = [
                  'MX MINI START' => [['1.', 'Eliška Holcmannová', '188 b.'], ['2.', 'Ondřej Kriška', '187 b.'], ['3.', 'Matyas Bukáček', '94 b.']],
                  'MINI DVOUTAKT' => [['1.', 'Matyas Bukáček', '140 b.'], ['2.', 'Maxmilián Mačalí', '94 b.'], ['3.', 'Eliška Holcmannová', '36 b.']],
                  'MX HOBBY' => [['1.', 'David Vařeka', '204 b.'], ['2.', 'Lukáš Káňa', '196 b.'], ['3.', 'Matěj Matušinec', '194 b.']],
                  'MX JUNIOR' => [['1.', 'Filip Grob', '225 b.'], ['2.', 'Adam Horký', '197 b.'], ['3.', 'Karolína Matušincová', '161 b.']],
                  'MX LADY' => [['1.', 'Veronika Dostálíková', '244 b.'], ['2.', 'Barbora Hajdíková', '138 b.'], ['3.', 'Karolína Matušincová', '120 b.']],
                  'MX' => [['1.', 'Adam Pernes', '237 b.'], ['2.', 'Tomáš Dostálík', '206 b.'], ['3.', 'Pavel Kubovic', '175 b.']],
                  'MX PRO FIGHTER' => [['1.', 'David Kosmák', '250 b.'], ['2.', 'Ondřej Matušinec', '200 b.'], ['3.', 'Adam Pernes', '80 b.']],
                  'MX SENIOR' => [['1.', 'Michal Káňa', '214 b.'], ['2.', 'Robert Nerad', '191 b.'], ['3.', 'Vlastislav Busta', '47 b.']],
                ];
                @endphp
                @foreach ($winners2023 as $cat => $riders)
                <div class="bg-mx-gray rounded p-3">
                  <p class="text-xs font-bold text-mx-orange uppercase mb-2">{{ $cat }}</p>
                  <table class="w-full text-xs text-gray-300">
                    @foreach ($riders as $r)
                    <tr>
                      <td class="py-0.5 pr-2 font-bold text-mx-white">{{ $r[0] }}</td>
                      <td class="py-0.5 pr-2">{{ $r[1] }}</td>
                      <td class="py-0.5 text-gray-400">{{ $r[2] }}</td>
                    </tr>
                    @endforeach
                  </table>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- 2023 – Jednotlivé závody --}}
      <div class="card-dark">
        <button class="w-full flex justify-between items-center p-4 text-left gap-4"
                @click="open = open === 'y2023z' ? null : 'y2023z'">
          <span class="font-medium text-mx-white flex items-center gap-2">
            <i class="fa-solid fa-bolt text-mx-orange"></i> 2023 – Jednotlivé závody
          </span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-200 flex-shrink-0"
             :class="{ 'rotate-180': open === 'y2023z' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === 'y2023z' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-4 pb-4">
              <ul class="space-y-2">
                @foreach([
                  ['29.4.2023 - Pravice', 'files/vysledky/2023/20230429_pravice.pdf'],
                  ['17.6.2023 - Miroslav', 'files/vysledky/2023/20230517_miroslav.pdf'],
                  ['09.09.2023 - Smrk', 'files/vysledky/2023/20230909_smrk.pdf'],
                  ['14.10.2023 - Miroslav', 'files/vysledky/2023/20231014_miroslav.pdf'],
                  ['21.10.2023 - Pravice', 'files/vysledky/2023/20231021_pravice.pdf'],
                ] as [$label, $file])
                <li>
                  <a href="{{ asset($file) }}" target="_blank" class="flex items-center gap-2 text-sm text-gray-300 hover:text-mx-gold transition-colors">
                    <i class="fa-solid fa-chevron-right text-mx-orange text-xs"></i> {{ $label }}
                  </a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@includeIf('sections.start_cup', ['background' => 'bg-mx-dark', 'text' => 'Postav se taky na stupně vítězů. Zaregistruj se na celou sezónu a získej ZÁVOD ZDARMA.'])

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

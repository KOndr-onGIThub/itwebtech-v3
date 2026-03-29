@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

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
        <span itemprop="name">Pravidla YCF CUP</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

<section class="section bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h1 class="text-3xl font-bold text-mx-white uppercase tracking-widest mb-4">Pravidla YCF CUP</h1>
    </div>
    <p class="text-gray-300 text-lg mb-4" data-aos="fade-up" data-aos-delay="100">
      Aby byly závody bezpečné a férové pro všechny účastníky, prosíme, dodržujte následující pravidla.
    </p>
    <p class="text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="200">
      Každý jezdec je povinen seznámit se s těmito pravidly a je zodpovědný za jejich dodržování.
      U jezdců mladších 18 let přebírají zodpovědnost zákonní zástupci.
    </p>
  </div>
</section>

@php
$sections = [
  [
    'title' => 'Základní pravidla pro jezdce',
    'items' => [
      '<strong>Respekt k ostatním:</strong> Během závodu je nutné dodržovat fair play, vzájemný respekt a ohleduplnost.',
      '<strong>Pokyny organizátorů:</strong> Všichni jezdci musí respektovat pokyny organizátorů a traťových komisařů.',
    ],
  ],
  [
    'title' => 'Technická přejímka',
    'subsections' => [
      ['heading' => 'Poučení', 'items' => [
        'Technická přejímka se dělá z důvodu celkové bezpečnosti a dodržování daných pravidel. Respektive mít motorku způsobilou závodu nemá být výhoda, ale základ!',
        'Technická přejímka je nedílnou součástí každého motorsportu a dělá se kvůli bezpečnosti daného jezdce a všech účastníků závodu.',
        'Motorka musí splňovat podmínky dané kategorie a mít funkční všechny ovládací prvky.',
        'Na technické přejímce se nebudou kontrolovat úpravy v motoru - tkzv. "podvody" od toho je možné vznést protest.',
      ]],
      ['heading' => 'Co se bude na moto kontrolovat', 'items' => [
        '<strong>Funkčnost brzd:</strong> "Bez funkční brzdy můžeš zranit sebe nebo jiné závodníky!"',
        '<strong>Chod plynové rukojeti:</strong> "Plyn musí jít zlehka a také se bez problému vracet!"',
        '<strong>Optická kontrola nosných částí:</strong> "S prasklým rámem nebo svárem akorát riskuješ zdraví!"',
        '<strong>Optická kontrola dle podmínek dané kategorie:</strong> "Typ motoru a velikost kol."',
      ]],
      ['heading' => 'Kdy se bude konat technická přejímka', 'items' => [
        'Technické přejímky a registrace bude možné udělat po domluvě už den před závodním dnem. Jinak klasicky ráno v závodní den před tréninky od 7:30 do 8:30',
      ]],
    ],
  ],
  [
    'title' => 'Protesty',
    'items' => [
      'Protesty je možno podávat při jednotlivých závodech do 30 minut po zveřejnění výsledku a předávají se výhradně řediteli závodu. Po uzávěrce závodu je pořadí neměnné.',
      'Cena protestu je 1.000,- Kč. Částka se vrací, je-li protest uznán jako oprávněný.',
      'Při vznesení protestu musí daný závodník přistavit motocykl, který bude následně zkontrolován a v případě potřeby rozebrán. V případě prokázání zakázaných úprav není organizátor závodu povinen odškodnit závodníka.',
    ],
  ],
  [
    'title' => 'Sankce za porušení pravidel',
    'intro' => 'Při porušení pravidel hrozí následující sankce:',
    'items' => [
      'Napomenutí',
      'Penalizace časem',
      'Diskvalifikace ze závodu',
      'Zákaz účasti na dalším závodě',
    ],
  ],
];
@endphp

@foreach ($sections as $section)
<section class="section-sm bg-mx-black" data-aos="fade-up">
  <div class="page-container max-w-3xl">
    <h2 class="text-xl font-bold text-mx-white text-center mb-6">{{ $section['title'] }}</h2>
    @if (!empty($section['intro']))
    <p class="text-gray-300 text-sm mb-4">{{ $section['intro'] }}</p>
    @endif
    @if (!empty($section['items']))
    <ul class="space-y-3">
      @foreach ($section['items'] as $item)
      <li class="flex gap-3 text-sm text-gray-300">
        <i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i>
        <span>{!! $item !!}</span>
      </li>
      @endforeach
    </ul>
    @endif
    @if (!empty($section['subsections']))
    @foreach ($section['subsections'] as $sub)
    <h3 class="text-base font-semibold text-mx-white mt-7 mb-3">{{ $sub['heading'] }}</h3>
    <ul class="space-y-3">
      @foreach ($sub['items'] as $item)
      <li class="flex gap-3 text-sm text-gray-300">
        <i class="fa-solid fa-check text-mx-orange mt-0.5 flex-shrink-0"></i>
        <span>{!! $item !!}</span>
      </li>
      @endforeach
    </ul>
    @endforeach
    @endif
  </div>
</section>
@endforeach

{{-- PDF dokumenty --}}
<section class="section-sm bg-mx-black" data-aos="fade-up">
  <div class="page-container max-w-3xl">
    <h2 class="text-xl font-bold text-mx-white text-center mb-6">OFICIÁLNÍ PRAVIDLA PRO PITBIKOVÉ ZÁVODY – YCF CUP</h2>
    <p class="text-gray-400 text-sm mb-3">Dokument obsahuje zejména:</p>
    <ul class="space-y-2 mb-6">
      @foreach(['Rozdělení kategorií jezdců podle věku a výkonu motorek.', 'Závodníci musí mít závodní licenci, bezpečnostní výbavu a podepsat čestné prohlášení.', 'Body jsou přidělovány na základě výsledků závodů.', 'Pokyny pro protesty a fair play během závodů.'] as $item)
      <li class="flex gap-2 text-sm text-gray-300">
        <i class="fa-solid fa-circle-dot text-mx-orange mt-0.5 flex-shrink-0 text-xs"></i>
        {{ $item }}
      </li>
      @endforeach
    </ul>
    <div data-aos="zoom-in" data-aos-delay="150">
      <a href="{{ asset('/files/Pravidla YCF CUP - platná od 4.4.2025.pdf') }}" target="_blank" class="btn-primary btn-sm inline-flex items-center gap-2">
        <i class="fa-solid fa-file-pdf"></i> Pravidla YCF Cup (PDF)
      </a>
      <p class="text-gray-400 text-xs mt-2">Platnost této verze: <strong class="text-gray-400">od 4.4.2025</strong></p>
    </div>
  </div>
</section>

<section class="section-sm bg-mx-dark" data-aos="fade-up">
  <div class="page-container max-w-3xl">
    <h2 class="text-xl font-bold text-mx-white text-center mb-6">TECHNICKÉ ŘÁDY YCF CUP</h2>
    <p class="text-gray-400 text-sm mb-3">Dokument obsahuje zejména:</p>
    <ul class="space-y-2 mb-6">
      @foreach(['Povinné technické kontroly motorek před každým závodem.', 'Specifikace bezpečnostní výbavy pro jezdce (helma, chrániče, obuv atd.).', 'Rozdělení na tréninky, kvalifikace a finální závody.', 'Pravidla bodování a umístění.', 'Penalizace za porušení pravidel jako jízda pod vlivem návykových látek nebo ignorování vlajek.'] as $item)
      <li class="flex gap-2 text-sm text-gray-300">
        <i class="fa-solid fa-circle-dot text-mx-orange mt-0.5 flex-shrink-0 text-xs"></i>
        {{ $item }}
      </li>
      @endforeach
    </ul>
    <div data-aos="zoom-in" data-aos-delay="150">
      <a href="{{ asset('/files/technicke_rady_YCF_Cup-od_23.1.2025.pdf') }}" target="_blank" class="btn-primary btn-sm inline-flex items-center gap-2">
        <i class="fa-solid fa-file-pdf"></i> Technické řády YCF Cup (PDF)
      </a>
      <p class="text-gray-400 text-xs mt-2">Platnost této verze: <strong class="text-gray-400">od 23.1.2025</strong></p>
    </div>
  </div>
</section>

<section class="section-sm bg-mx-dark" data-aos="fade-up">
  <div class="page-container max-w-3xl">
    <h2 class="text-xl font-bold text-mx-white text-center mb-6">VÝZNAM VLAJEK</h2>
    <p class="text-gray-400 text-sm mb-6">
      Seznamte se s významem vlajek používaných při závodech i trénincích.
    </p>
    <div data-aos="zoom-in" data-aos-delay="150">
      <a href="{{ asset('/files/Vlajky.pdf') }}" target="_blank" class="btn-primary btn-sm inline-flex items-center gap-2">
        <i class="fa-solid fa-file-pdf"></i> Vlajky (PDF)
      </a>
    </div>
  </div>
</section>

@includeIf('sections.start_cup', ['background' => 'bg-mx-dark', 'text' => 'Pravidla sou nuda, ale musí to bejt ...<br>Zaregistruj se do závodů a užij si férovou jízdu.'])

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

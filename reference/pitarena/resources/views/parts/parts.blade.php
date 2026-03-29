@extends('layout')

@section('title', 'Dokumenty pro servis PitAréna')
@section('meta_description', 'Dokumenty pro servis PitAréna')

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
        <span itemprop="name">Díly</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

<section class="section bg-mx-black">
  <div class="page-container max-w-2xl">
    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <h1 class="section-title mb-8">Díly</h1>
    </div>

    @php
    $parts = [
      'motory' => [
        'name' => 'Motory 2025',
        'desc' => 'Motory podle modelu motorky',
        'route' => 'parts.motory',
        'link' => null,
        'file' => null
      ],
      'karburatory' => [
        'name' => 'Karburátory 2024',
        'desc' => 'Nastavení karburátorů',
        'route' => null,
        'link' => null,
        'file' => 'CARBURATION range 2024 - France setting.pdf'
      ],
      'katalog 2024' => [
        'name' => 'Katalog 2024',
        'desc' => null,
        'route' => null,
        'link' => 'https://www.ycf-shop.cz/user/documents/upload/YCFcatal2024_CZ_DEF_WEB_Komplet.pdf',
        'file' => null
      ],
    ]
    @endphp

    <div class="space-y-4">
      @forelse ($parts as $slug => $part)
        @php
          $name  = $part['name']  ?? '';
          $desc  = $part['desc']  ?? null;
          $route = $part['route'] ?? null;
          $link  = $part['link']  ?? null;
          $file  = $part['file']  ?? null;

          $href  = null;
          $attrs = '';
          $label = null;
          $error = null;

          if ($route) {
            if (Route::has($route)) {
              $href  = route($route);
              $label = 'Zobrazit →';
            } else {
              $error = 'ERROR – neexistující rúta: ' . $route;
            }
          } elseif ($link) {
            $href  = $link;
            $attrs = ' target="_blank" rel="noopener"';
            $label = 'Otevřít odkaz';
          } elseif ($file) {
            $href  = asset('files/parts/' . $file);
            $attrs = ' target="_blank" rel="noopener"';
            $label = 'Otevřít soubor ' . $file;
          }
        @endphp

        <article class="card-dark p-5 {{ $error ? 'border-red-500' : '' }}">
          <h4 class="text-mx-white font-semibold mb-1">{{ $name }}</h4>
          @if ($desc)
            <p class="text-gray-400 text-sm mb-3">{{ $desc }}</p>
          @endif
          <div>
            @if ($error)
              <span class="text-red-400 text-sm">{{ $error }}</span>
            @elseif ($href)
              <a href="{{ $href }}"{!! $attrs !!} class="btn-primary btn-sm">
                {{ $label }}
              </a>
            @else
              <span class="text-gray-400 text-sm">Odkaz zatím není k&nbsp;dispozici.</span>
            @endif
          </div>
        </article>
      @empty
        <p class="text-gray-400">Žádné položky nebyly nalezeny.</p>
      @endforelse
    </div>
  </div>
</section>

@includeIf('sections.service')

@endsection

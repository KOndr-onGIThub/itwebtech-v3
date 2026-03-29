@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
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
        <span itemprop="name">Blog</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

<section class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center mb-10" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h1 class="section-title">Blog</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

      @forelse ($blogs as $blog)
        @if ($blog->is_published)
          <article class="card-dark overflow-hidden group" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 150 }}">
            <a href="{{ route('blog.show', ['blog' => $blog]) }}" class="block overflow-hidden">
              <img src="{{ url('images/blog/') . '/' . $blog->img_url }}"
                   alt="{{ $blog->title }}"
                   width="886" height="668"
                   loading="lazy"
                   class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"/>
            </a>
            <div class="p-5">
              <h5 class="text-mx-white font-semibold mb-2 leading-snug">
                <a href="{{ route('blog.show', ['blog' => $blog]) }}" class="hover:text-mx-orange transition-colors">
                  {{ $blog->title }}
                </a>
              </h5>
              <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $blog->description }}</p>
              <a class="btn-primary btn-sm" href="{{ route('blog.show', ['blog' => $blog]) }}">Přečíst</a>
            </div>
          </article>
        @endif
      @empty
        <div class="col-span-3 text-center text-gray-400">
          Nikdo zatím nevytvořil žádný článek.
        </div>
      @endforelse

    </div>

  </div>
</section>

@includeIf('sections.service')

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

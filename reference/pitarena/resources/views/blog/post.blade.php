@extends('layout')

@section('title', $blog->title)
@section('meta_description', $blog->description)

@section('og')
  <meta property="og:url" content="{{ route('blog.show', ['blog' => $blog]) }}">
  <meta property="og:type" content="article">
  <meta property="og:title" content="{{ $blog->title }}">
  <meta property="og:description" content="{{ $blog->description }}">
  <meta property="og:image" content="{{ url('images/blog/') . '/' . $blog->img_url }}">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="{{ route('blog.show', ['blog' => $blog]) }}">
  <meta name="twitter:title" content="{{ $blog->title }}">
  <meta name="twitter:description" content="{{ $blog->description }}">
  <meta name="twitter:image" content="{{ url('images/blog/') . '/' . $blog->img_url }}">
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
        <a itemprop="item" href="{{ route('blog.index') }}"><span itemprop="name">Blog</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{ $blog->title }}</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Facebook SDK --}}
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">

    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <h1 class="text-3xl lg:text-4xl font-bold text-mx-white leading-tight mb-6">
        {{ $blog->title }}
      </h1>
    </div>

    {{-- Meta --}}
    <div class="flex items-center gap-4 mb-6 text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="100">
      <img src="{{ url('images/logo/pitarena_logo_grey_74x74.png') }}" width="36" height="36" alt="PitArena logo" class="rounded-full">
      <span>od <span class="text-mx-white">tým Pitarény</span></span>
      <time class="text-gray-400">{{ $blog->updated_at->format('d. m. Y') }}</time>
    </div>

    {{-- Hero image --}}
    <div class="mb-8" data-aos="fade-up" data-aos-delay="150">
      <img src="{{ url('images/blog/') . '/' . $blog->img_url }}"
           alt="{{ $blog->title }}"
           width="886" height="668"
           class="w-full rounded-lg object-cover"/>
    </div>

    {{-- Content --}}
    <div class="prose prose-invert prose-sm max-w-none text-gray-300 leading-relaxed mb-8">
      {!! $blog->content !!}
    </div>

    {{-- Share --}}
    <div class="pt-4 border-t border-mx-gray2 mb-8 text-sm text-gray-400" data-aos="fade-up">
      <span class="mr-3">Sdílejte tento článek s přáteli</span>
      <div class="fb-share-button inline-block"
           data-href="{{ route('blog.show', ['blog' => $blog]) }}"
           data-layout="button_count"
           data-size="large">
      </div>
    </div>

    {{-- Prev / Next --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
      @if (isset($previous))
        <a href="{{ route('blog.index') . '/' . $previous->url }}"
           class="flex items-center gap-2 text-gray-400 hover:text-mx-orange transition-colors">
          <i class="fa-solid fa-chevron-left"></i>
          <span>{{ $previous->title }}</span>
        </a>
      @else
        <a href="{{ route('blog.index') }}"
           class="flex items-center gap-2 text-gray-400 hover:text-mx-orange transition-colors">
          <i class="fa-solid fa-chevron-left"></i>
          <span>Zpět na seznam článků</span>
        </a>
      @endif

      @if (isset($next))
        <a href="{{ url('blog') . '/' . $next->url }}"
           class="flex items-center justify-end gap-2 text-gray-400 hover:text-mx-orange transition-colors text-right">
          <span>{{ $next->title }}</span>
          <i class="fa-solid fa-chevron-right"></i>
        </a>
      @else
        <a href="{{ url('blog') }}"
           class="flex items-center justify-end gap-2 text-gray-400 hover:text-mx-orange transition-colors text-right">
          <span>Zpět na seznam článků</span>
          <i class="fa-solid fa-chevron-right"></i>
        </a>
      @endif
    </div>

  </div>
</section>

@includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@includeIf('sections.service')

@endsection

@extends('layout')

@section('title', 'Platba vyžadována – 402')
@section('meta_description', 'Pro přístup k obsahu je vyžadována platba.')

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
        <span itemprop="name">Platba vyžadována</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')
<section class="section bg-mx-dark min-h-[50vh] flex items-center">
  <div class="page-container max-w-xl text-center">
    <div class="text-8xl font-bold text-mx-orange mb-4">402</div>
    <h1 class="text-2xl font-semibold text-mx-white mb-4">Platba vyžadována</h1>
    <p class="text-gray-400 text-sm leading-relaxed mb-8">
      Pro přístup k tomuto obsahu je vyžadována platba.
    </p>
    <a class="btn-primary" href="{{ route('home') }}">
      <i class="fa-solid fa-house"></i> Zpět na úvod
    </a>
  </div>
</section>
@endsection

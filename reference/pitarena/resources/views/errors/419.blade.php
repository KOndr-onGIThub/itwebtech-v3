@extends('layout')

@section('title', 'Relace vypršela – 419')
@section('meta_description', 'Stránce vypršela platnost relace.')

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
        <span itemprop="name">Relace vypršela</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')
<section class="section bg-mx-dark min-h-[50vh] flex items-center">
  <div class="page-container max-w-xl text-center">
    <div class="text-8xl font-bold text-mx-orange mb-4">419</div>
    <h1 class="text-2xl font-semibold text-mx-white mb-4">Relace vypršela</h1>
    <p class="text-gray-400 text-sm leading-relaxed mb-4">
      Stránce vypršela platnost relace (CSRF token). Zkuste stránku obnovit a akci zopakovat.
    </p>
    <p class="text-gray-400 text-sm leading-relaxed mb-8">
      <strong class="text-mx-white">Dbáme na maximální zabezpečení vašich dat</strong> — hlídáme dobu nečinnosti před odesláním formulářů,
      aby nedošlo k tomu, že někdo jiný pošle data za vás.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a class="btn-primary" onclick="history.back(); return false;" href="#">
        <i class="fa-solid fa-rotate-left"></i> Zpět na formulář
      </a>
      <a class="btn-outline" href="{{ route('home') }}">
        <i class="fa-solid fa-house"></i> Úvod
      </a>
    </div>
  </div>
</section>
@endsection

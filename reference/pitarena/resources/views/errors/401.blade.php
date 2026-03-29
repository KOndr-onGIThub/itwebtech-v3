@extends('layout')

@section('title', 'Přístup odepřen – 401')
@section('meta_description', 'Pro přístup k této stránce musíte být přihlášeni.')

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
        <span itemprop="name">Přístup odepřen</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')
<section class="section bg-mx-dark min-h-[50vh] flex items-center">
  <div class="page-container max-w-xl text-center">
    <div class="text-8xl font-bold text-mx-orange mb-4">401</div>
    <h1 class="text-2xl font-semibold text-mx-white mb-4">Přístup odepřen</h1>
    <p class="text-gray-400 text-sm leading-relaxed mb-8">
      Pro přístup k této stránce musíte být přihlášeni.<br>
      Zadejte prosím správné přihlašovací údaje.
    </p>
    <a class="btn-primary" href="{{ route('home') }}">
      <i class="fa-solid fa-house"></i> Zpět na úvod
    </a>
  </div>
</section>
@endsection

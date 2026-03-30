@extends('layout')

@section('title', 'Dokumenty pro trenéry')
@section('meta_description', 'Dokumenty ke stažení pro trenéry pitarény.')

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
        <span itemprop="name">Trenér</span>
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
      <h1 class="section-title mb-2">Dokumenty ke stažení pro trenéry Pitarény</h1>
    </div>
    <p class="text-gray-400 text-sm mb-8">Dokumenty pouze pro potřeby Pitarény.</p>

    <div class="space-y-2" x-data="{ open: null }">

      @php
      $docs = [
        ['id' => '1', 'title' => 'KARTA JEZDCE', 'note' => 'VEŘEJNÝ - BEZ HESLA', 'file' => '/files/karta_jezdce.pdf', 'name' => 'karta_jezdce.pdf'],
        ['id' => '2', 'title' => 'LEKCE', 'note' => 'NEVEŘEJNÝ - ZAHESLOVANÝ', 'file' => '/files/LEKCE_soupis_CHRANENO.pdf', 'name' => 'LEKCE_soupis_CHRANENO.pdf'],
        ['id' => '3', 'title' => 'TECHNIKA JÍZDY', 'note' => 'JEŠTĚ NENÍ K DISPOZICI - BUDE PŘIDÁNO POZDĚJI - NEVEŘEJNÝ - ZAHESLOVANÝ', 'file' => null, 'name' => null],
        ['id' => '4', 'title' => 'VLAJKY', 'note' => 'VEŘEJNÝ - BEZ HESLA', 'file' => '/files/Vlajky.pdf', 'name' => 'Vlajky.pdf'],
        ['id' => '5', 'title' => 'POTVRZENÍ PLATBY', 'note' => 'VEŘEJNÝ - BEZ HESLA', 'file' => '/files/potvrzeni_platby.pdf', 'name' => 'potvrzeni_platby.pdf'],
      ];
      @endphp

      @foreach ($docs as $doc)
      <div class="card-dark">
        <button
          class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '{{ $doc['id'] }}' ? null : '{{ $doc['id'] }}'">
          <span class="font-semibold text-mx-white text-sm">{{ $doc['title'] }}</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '{{ $doc['id'] }}' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '{{ $doc['id'] }}' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-4 text-gray-400 text-sm border-t border-mx-gray2">
              <p class="mt-3 mb-3">{{ $doc['note'] }}</p>
              @if ($doc['file'])
              <div class="flex gap-3 flex-wrap">
                <a href="{{ url($doc['file']) }}" target="_blank" class="btn-primary btn-sm">
                  <i class="fa-solid fa-external-link-alt"></i> Otevřít
                </a>
                <a href="{{ url($doc['file']) }}" download="{{ $doc['name'] }}"
                   class="btn-outline btn-sm">
                  <i class="fa-solid fa-download"></i> Stáhnout
                </a>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

@includeIf('sections.service')

@endsection

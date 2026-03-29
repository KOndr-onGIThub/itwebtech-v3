@extends('layout')

@section('title', 'Motory YCF 2025')
@section('meta_description', 'Motory YCF 2025 — katalog náhradních dílů')

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
        <a itemprop="item" href="{{ route('parts') }}"><span itemprop="name">Díly</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Motory 2025</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

@php
  $motors = [
    ['name' => '50AE', 'link' => 'https://docs.google.com/spreadsheets/d/1aRL-evbk6-AS5RobUaTKv1FS--BLoDRK/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'BIGY 125', 'link' => 'https://docs.google.com/spreadsheets/d/1spuI-TgqSU1NIbRAdh-nZFq38FI9W-cW/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'BIGY 150', 'link' => 'https://docs.google.com/spreadsheets/d/1dg8x67Nah-5dCYC0tE63U9j0rBt-M4Yh/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'BIGY 150E FACTORY XL', 'link' => 'https://docs.google.com/spreadsheets/d/1aHa_fZNEYVeuS5RWxfu20CB2ueplkwjq/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'BIGY 190 FACTORY DAYTONA XL', 'link' => 'https://docs.google.com/spreadsheets/d/1Lf5DDKguZHFtLYst5dNMV_W5vTvof9FR/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'FACTORY 150 SP2', 'link' => 'https://docs.google.com/spreadsheets/d/1AaBsk6LdyyJtfo0OslIwo6vl55r7Vojs/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'FACTORY 190 SP3 DAYTONA', 'link' => 'https://docs.google.com/spreadsheets/d/1HdxtXr0T5nRm6ddg5MKDQ98SRT7DMvf4/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'GP 107A', 'link' => 'https://docs.google.com/spreadsheets/d/1cZCcmwJZZlk5J18-BObFhMEOgma8LYms/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'GP 124', 'link' => 'https://docs.google.com/spreadsheets/d/1LTXcp3_NfuNRU6ogEiPPfZswJ_-dFPKJ/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'GP 157', 'link' => 'https://docs.google.com/spreadsheets/d/1Y7VLztlx_yp_1RvA26aQuDHna7egASPB/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'GP 187 DAYTONA', 'link' => 'https://docs.google.com/spreadsheets/d/1cHohqFmH0Z4h3cLEJcxU-_YKwJS3lE-_/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'LITE 88S', 'link' => 'https://docs.google.com/spreadsheets/d/1VXG421sKDsvCYBqwUMUUIq4KC8sQuorv/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'LITE 125', 'link' => 'https://docs.google.com/spreadsheets/d/1CV0cwqA_3bcZFMJtmxcmFpSzw7RVSrPA/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'PILOT 125', 'link' => 'https://docs.google.com/spreadsheets/d/1bnoITj3wiwyjWPGNSWRkHv48bX0nX9M9/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'PILOT 150E', 'link' => 'https://docs.google.com/spreadsheets/d/1XGfTzKY4FOwWZbr0tsuUCy8ne_hPdHUL/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SM 125', 'link' => 'https://docs.google.com/spreadsheets/d/1GHV-3-J19IE7M4Lo84thtO0vvXMd1Wd8/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SM 155', 'link' => 'https://docs.google.com/spreadsheets/d/1RgYs863-TzqagxOPC8Dp5cDzRSmyCf4H/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SM 190 DAYTONA', 'link' => 'https://docs.google.com/spreadsheets/d/1riOPeT1mA8ODlZ-jgEQi-vh3n3Zti9XJ/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'START 88SE', 'link' => 'https://docs.google.com/spreadsheets/d/19TSacBt3e3ZcBNtEP4530XroaiZ-Q47Z/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'START 125', 'link' => 'https://docs.google.com/spreadsheets/d/1EoGVEggoj9hgkPv3HzO1_dUGNlSmP-Ze/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'START 125SE', 'link' => 'https://docs.google.com/spreadsheets/d/1HV690z9EUoqsdRKwHHy28ue45CQtkFVw/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SUNDAY 124SE', 'link' => 'https://docs.google.com/spreadsheets/d/1PspFhddqP1-sa5yXcwHWJMPMIV9FlOZy/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SUNDAY 147E', 'link' => 'https://docs.google.com/spreadsheets/d/1wjO9T9o3FWn8cHBABdi4VGhDpCWhzQ5W/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
    ['name' => 'SUNDAY 187 DAYTONA', 'link' => 'https://docs.google.com/spreadsheets/d/1LQG9U0mAWxlU224PEM7jzOwUodQnDwS3/edit?usp=drive_link&ouid=109579409851305565129&rtpof=true&sd=true'],
  ];
@endphp

<section class="section bg-mx-dark">
  <div class="page-container">
    <h1 class="section-title text-center mb-8">Motory YCF 2025</h1>

    @forelse ($motors as $motor)
    @if ($loop->first)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @endif
      <div class="card-dark overflow-hidden flex flex-col sm:flex-row items-center">
        <a href="{{ $motor['link'] }}" target="_blank" rel="noopener" class="flex-shrink-0 block sm:w-1/2">
          <img src="{{ asset('files/parts/' . $motor['name'] . '.jpg') }}"
               alt="{{ $motor['name'] }}" width="700" height="500"
               loading="lazy" class="w-full object-cover"/>
        </a>
        <div class="p-5 text-center sm:w-1/2">
          <a href="{{ $motor['link'] }}" target="_blank" rel="noopener" class="btn-primary btn-sm">
            {{ $motor['name'] }}
          </a>
        </div>
      </div>
    @if ($loop->last)
    </div>
    @endif
    @empty
    <p class="text-gray-400 text-center">Momentálně nejsou dostupné žádné motory k zobrazení.</p>
    @endforelse

  </div>
</section>

@endsection

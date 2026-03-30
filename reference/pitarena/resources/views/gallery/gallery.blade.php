@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'gallery')
@section('title', $sitemap['title'] )
@section('meta_description', $sitemap['description'] )
@endif
@empty
@endforelse

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="https://pitarena.cz">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection



@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Galerie</li>
    </ol>
  </div>
</nav>
@endsection


@section('content')

    <section class="bg-mx-black section-lg">
        <div class="page-container">
            <div class="text-center" data-aos="fade-up">
                <div class="section-divider section-divider-center mb-6"></div>
                <h1 class="section-title mb-8">FOTO</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

                <a href="{{ route('get.galleries').'/'.$smrk202309[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $smrk202309[0]['src_small'] }}" alt="foto {{ $smrk202309[0]['category'] }}" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $smrk202309[0]['category'] }}</span>
                        <h5 class="font-semibold">Závod na trati ve Smrku ze září 2023</h5>
                    </div>
                </a>

                <a href="{{ route('get.galleries').'/'.$miroslav202306[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $miroslav202306[0]['src_small'] }}" alt="foto {{ $miroslav202306[0]['category'] }}" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $miroslav202306[0]['category'] }}</span>
                        <h5 class="font-semibold">Závod na motokrosové trati v Miroslavi z června 2023</h5>
                    </div>
                </a>

                <a href="{{ route('get.galleries').'/'.$minis[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $minis[0]['src_small'] }}" alt="foto {{ $minis[0]['category'] }}" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $minis[0]['category'] }}</span>
                        <h5 class="font-semibold">Takhle u nás jezdí děcka už od školky</h5>
                    </div>
                </a>

                <a href="{{ route('get.galleries').'/'.$juniors[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $juniors[0]['src_small'] }}" alt="Pitbike {{ $juniors[0]['category'] }}" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $juniors[0]['category'] }}</span>
                        <h5 class="font-semibold">Talentovaní junioři</h5>
                    </div>
                </a>

                <a href="{{ route('get.galleries').'/'.$seniors[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $seniors[0]['src_small'] }}" alt="Pitbike {{ $seniors[0]['category'] }}" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $seniors[0]['category'] }}</span>
                        <h5 class="font-semibold">Profíci i ti co jezdí čistě pro zábavu</h5>
                    </div>
                </a>

                <a href="{{ route('get.galleries').'/'.$mixes[0]['category'] }}" class="card-dark group block">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <img src="{{ $mixes[0]['src_small'] }}" alt="mix fotek z Pitarény v Pravicích" width="886" height="668" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">{{ $mixes[0]['category'] }}</span>
                        <h5 class="font-semibold">Další úlovky z Pitbike Arény Pravice</h5>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <section class="bg-mx-black section-lg">
        <div class="page-container">
            <div class="text-center" data-aos="fade-up">
                <div class="section-divider section-divider-center mb-6"></div>
                <h2 class="section-title mb-8">VIDEO</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/Y9AqNSNsGfw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">Závody</span>
                        <h5 class="font-semibold">Sestřih Pitbike závodů ze dne 17. 6. 2023 v Miroslavi</h5>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/dVFw-NY44bI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">Závody</span>
                        <h5 class="font-semibold">Sestřih Pitbike závodů ze dne 29.4.2023 v YCF Aréně Pravice</h5>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/ugLOPL1bZRQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">PVA EXPO PRAHA</span>
                        <h5 class="font-semibold">PVA EXPO PRAHA 2023</h5>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/HN2VFD4WnDc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">Trénink Y.R.P</span>
                        <h5 class="font-semibold">Trénink členů Y.R.P z 25.2.2023</h5>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/bdlbJSpva_o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">Volné jízdy</span>
                        <h5 class="font-semibold">Volné jízdy podzim 2022</h5>
                    </div>
                </div>

                <div class="card-dark">
                    <div class="aspect-video overflow-hidden rounded-t-lg">
                        <iframe width="443" height="334" src="https://www.youtube-nocookie.com/embed/JS_prYh2LyY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <div class="card-dark-body">
                        <span class="badge-orange mb-2 inline-block">Závody</span>
                        <h5 class="font-semibold">MEZINÁRODNÍ MISTROVSTVÍ PITBIKE Moravia Cup 1.10.2022 Pravice</h5>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @includeIf('sections.offerOverview', ['background' => 'bg-mx-dark'])

@endsection

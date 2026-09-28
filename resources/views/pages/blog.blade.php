@extends('layouts.app')

@section('title', __('blog.meta.title'))
@section('description', __('blog.meta.description'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro blog listing.
     Pozn.: viz price.blade.php — schema-context klíč řešíme přes PHP blok,
     aby ho nesežrala Blade direktiva (Laravel 12 CompilesContexts). --}}
@push('jsonld')
@php
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'), 'item' => lroute('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.blog'), 'item' => lroute('blog')],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@endpush

@section('content')
{{-- ============================================================
     OND-404 — /zapisky ve slovníku nové homepage (6. z 9).
     Obsah beze změny (lang/*/blog.php + články z DB), mění se
     slovník. Obal `pd` nese tokeny ACID, `pd--depth-sub` vypíná
     vrstvu B. Kužel vrstvy A v hloubka.css §E je psaný na
     `section[data-pdd="blog-list"]` — sekce musí zůstat PŘÍMÝMI
     dětmi obalu.

     Příběh: člověk je ve fázi 2 („potřebuju vůbec nový web? teď?
     za kolik?"). Stránka mu ukáže otázky, na které odpovídám,
     a na konci ho pošle o krok dál — na ceník nebo k práci. Do
     formuláře ho netlačí: ten má v liště i ve spodní liště mobilu.
     ============================================================ --}}
<div class="pd pd--depth pd--depth-sub">

{{-- Hlava — `.pd-page-head` (základ OND-379 §2a), vlevo jako všude. --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('blog.hero.page_mark_label') }} <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ __('blog.hero.upline') }}</p>
        <h1 class="pd-heading pd-heading--sub">{!! __('blog.hero.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('blog.hero.subline') }}</p>
    </div>
</section>

{{-- Výpis — `.pd-works` z /projekty v řádcích (`--notes`). Článek se
     vybírá podle otázky v titulku, ne podle obrázku: náhled je malý
     čtverec vlevo (rozhodnutí boardu OND-292 „doplnit screenshoty"
     platí dál), titulek nese váhu. Celý řádek je jeden odkaz — žádné
     „Přečíst" u každého (5× stejná výzva je šum). Dřív tu byl vnořený
     `<main class="blog-articles">` uvnitř `<main>` layoutu = dva
     hlavní orientační body na stránce; teď obyčejný seznam. --}}
<section class="pd-section" data-pdd="blog-list">
    <div class="container-site">
        @php
            $notes = collect($articles ?? [])->filter(fn ($a) => $a->translation($locale)?->title);
        @endphp
        @if ($notes->isNotEmpty())
        <ol class="pd-works pd-works--notes" role="list">
            @foreach ($notes as $dbArticle)
                @php
                    $t   = $dbArticle->translation($locale);
                    $url = lroute('blog') . '/' . $dbArticle->slug($locale);
                @endphp
                <li class="pd-work">
                    <a href="{{ $url }}" class="pd-work__link"
                       data-analytics="blog_card_click"
                       data-analytics-props='{"slug":"{{ $dbArticle->slug($locale) }}"}'>
                        @if ($t->img_preview)
                        <div class="pd-work__visual">
                            <x-responsive-image
                                path="articles/{{ $t->img_preview }}"
                                alt=""
                                loading="{{ $loop->index < 2 ? 'eager' : 'lazy' }}"
                                sizes="(max-width: 639px) 38vw, 200px"
                            />
                        </div>
                        @endif
                        <h2 class="pd-work__title">{{ $t->title }}</h2>
                        @if ($t->description)
                        <p class="pd-work__text">{{ $t->description }}</p>
                        @endif
                    </a>
                </li>
            @endforeach
        </ol>
        @else
        <p class="pd-intro">{{ __('blog.empty') }}</p>
        @endif
    </div>
</section>

{{-- Závěr — fáze 2 ještě nechce psát poptávku. Místo výzvy dvě cesty
     o krok dál po nákupní ose: „za kolik?" (ceník) a „umí to?"
     (projekty). Každá cesta mluví jazykem cílové stránky: nadřádek,
     titulek a první věta jsou PŘESNĚ texty z hlavy /cenik a /projekty
     (Information Scent — člověk po kliknutí uvidí totéž, co slíbil
     odkaz). Nadpis je `blog.cta.heading`. Ani jedno nové slovo. --}}
@php
    $routes = [
        ['href' => lroute('price'),    'ns' => 'price'],
        ['href' => lroute('projects'), 'ns' => 'projects'],
    ];
@endphp
<section class="pd-section pd-next" data-pdd="blog-next">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('blog.cta.heading') }}</h2>
            </header>

            <ul class="pd-points pd-points--next" role="list">
                @foreach ($routes as $r)
                <li class="pd-point">
                    <a href="{{ $r['href'] }}" class="pd-next__link">
                        <span class="pd-work__meta">{{ __($r['ns'] . '.hero.page_mark_label') }}</span>
                        <span class="pd-point__title">{{ trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<br>', ' ', __($r['ns'] . '.hero.heading_html'))))) }} <span class="pd-next__arrow" aria-hidden="true">&rarr;</span></span>
                        <span class="pd-point__text">{{ __($r['ns'] . '.hero.upline') }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

</div>
@endsection

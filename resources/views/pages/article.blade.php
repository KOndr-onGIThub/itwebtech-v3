@extends('layouts.app')

{{-- OND-406: přípona `| ONDRAWEB`, ne `— Ondřej Kriška, ONDRAWEB` jako ostatní
     stránky. Titulky článků mají 43–63 znaků, dlouhá přípona by je všechny
     poslala přes ~60 znaků, kde Google řeže. Svislice odděluje značku, pomlčka
     by se u titulků ve tvaru otázky četla jako pokračování věty. --}}
@section('title', $translation?->title ? $translation->title . ' | ' . config('app.name') : config('app.name'))
{{-- OND-162 F5: article description z DB je často 237-268 znaků (perex-style),
     ale Google ořezává <meta description> kolem 155-160. Trimneme na 155
     s ellipsis (= 156 total), aby SERP snippet byl celý a ne useknutý
     uprostřed věty. JSON-LD Article description (níž v article.blade.php
     a v @push('jsonld')) zůstává plný, schema.org limit nemá. --}}
@section('description', \Illuminate\Support\Str::limit($translation?->description ?? '', 155, '…'))

{{-- OND-137 P4 §SEO: BreadcrumbList JSON-LD pro detail článku.
     OND-299: Article blok tady dřív byl taky, ale duplikoval ten v body
     (ř. ~200, $articleLd) — dvě Article entity se stejným mainEntityOfPage.@id
     na jedné stránce. Tenhle byl chudší podmnožina (bez dateModified,
     author.url, jobTitle, publisher.logo), takže šel pryč celý.
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
            ['@type' => 'ListItem', 'position' => 3, 'name' => $translation?->title ?? ($article?->slug ?? ''), 'item' => url()->current()],
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
     OND-406 — detail článku ve slovníku nové homepage (7. z 9).
     Obsah beze změny (lang/*/blog.php + článek z DB), mění se slovník.

     OND-251 — vrstva hloubky VĚDOMĚ VYPNUTÁ: obal je jen `pd` (tokeny
     a základ #0A0A0B), žádné `pd--depth`. Článek je čtecí stránka;
     nasvícení pod souvislým odstavcem mění kontrast pod právě čteným
     řádkem a čtenáři nic nedá. Rozbor v §E hloubka.css.

     Příběh: člověk je ve fázi 2 — přišel s jednou otázkou (z /zapisky
     nebo z Googlu) a chce odpověď. Stránka mu ji dá co nejdřív: titulek,
     hned pod ním Ondřejův úvod, pak text. Na konci ho pošle o krok dál
     do obsahu (další článek, ceník, realizace) — ne rovnou do formuláře,
     ten má v liště a ve spodní liště mobilu.
     ============================================================ --}}
<div class="pd">

{{-- Hlava — `.pd-page-head` (základ OND-379 §2a). Nadřádek je zároveň
     cesta zpět: „← ZPĚT NA ZÁPISKY" říká, kde člověk je, i jak se vrátí.
     Dřív tu byly dva řádky (zpětný odkaz + „ZÁPISKY") a k tomu druhá
     šipka jako ikona. Popis článku (`description`) se v hlavě netiskne:
     je to souhrn pro výsledky vyhledávání a perex pod titulkem říká totéž
     Ondřejovým hlasem (u článku o ceně skoro stejnou větou). Zůstává
     v `meta description` a v JSON-LD. --}}
<section class="pd-section pd-page-head pd-page-head--article" data-sticky-cta="start">
    <div class="container-site">
        <p class="pd-eyebrow"><a href="{{ lroute('blog') }}" class="pd-eyebrow__back">{{ __('blog.back_to_blog') }}</a></p>
        @php
            // Česká sazba: jednopísmenná předložka/spojka nezůstane na konci
            // řádku (nezlomitelná mezera za ní). Jen v H1 a jen v cs; text
            // z DB se nemění, mění se jen mezera, podle které se láme.
            $h1 = (string) $translation?->title;
            if ($locale === 'cs') {
                $h1 = preg_replace('/(?<![\p{L}\p{N}])([ksvzouaiKSVZOUAI]) +/u', "$1\u{00A0}", $h1);
            }
        @endphp
        <h1 class="pd-heading pd-heading--sub pd-heading--article">{{ $h1 }}</h1>
    </div>
</section>

<article class="pd-section pd-article" data-pdd="article">
    <div class="container-site">
        <div class="pd-prose">

            @if ($translation?->perex)
            <div class="pd-prose__lead">
                {!! $translation->perex !!}
            </div>
            @endif

            @php
                // Master image_url (Filament upload, storage public disk) má přednost.
                // Při fallbacku použije legacy `img_main` přes Vite-built `responsive-image` pipeline.
                $heroImage = $article?->hero_image_url;
            @endphp
            {{-- Hlavní obrázek až pod perexem: čtenář přišel pro odpověď,
                 ne pro ilustraci. Na 390 px je tak první věta článku vidět
                 v první obrazovce (dřív ~1 170 px od vrchu). --}}
            @if ($heroImage)
            <figure class="pd-figure pd-figure--prose">
                <img src="{{ $heroImage }}" alt="{{ $translation?->title }}" loading="lazy" />
            </figure>
            @elseif ($translation?->img_main)
            <figure class="pd-figure pd-figure--prose">
                <x-responsive-image
                    path="articles/{{ $translation->img_main }}"
                    alt="{{ $translation->title }}"
                    loading="lazy"
                    sizes="(max-width: 767px) 100vw, 656px"
                />
            </figure>
            @endif

            {!! $translation?->content_1 !!}

            @if ($translation?->img_mid)
            <figure class="pd-figure pd-figure--prose">
                <x-responsive-image
                    path="articles/{{ $translation->img_mid }}"
                    alt="{{ $translation->img_mid_alt ?? '' }}"
                    loading="lazy"
                    sizes="(max-width: 767px) 100vw, 656px"
                />
            </figure>
            @endif

            {!! $translation?->content_mid !!}

            {!! $translation?->content_2 !!}

            @if ($translation?->img_end)
            <figure class="pd-figure pd-figure--prose">
                <x-responsive-image
                    path="articles/{{ $translation->img_end }}"
                    alt="{{ $translation->img_end_alt ?? '' }}"
                    loading="lazy"
                    sizes="(max-width: 767px) 100vw, 656px"
                />
            </figure>
            @endif

            {{-- Bonus — dodatek pod článkem (Filament „Bonus blok (HTML)").
                 Deska `.pd-aside` ho odliší plochou, ne čárou. Dnes prázdný ve
                 všech 15 překladech. `extra` se nevykresluje (a nevykresloval). --}}
            @if ($translation?->bonus)
            <aside class="pd-aside">
                {!! $translation->bonus !!}
            </aside>
            @endif

            {{-- Podpis — kdo to napsal (E-E-A-T, osobní hlas). Bez tlačítek:
                 výzva k poptávce je v liště a na mobilu ve spodní liště; tady
                 by byla třetí. Zůstává jen LinkedIn jako tichý odkaz. --}}
            <footer class="pd-author">
                <p class="pd-eyebrow">{{ __('blog.article.author.eyebrow') }}</p>
                <div class="pd-author__body">
                    <span class="pd-author__photo">
                        <img
                            src="{{ asset_v('img/about/ondrej_kriska_2026.jpg') }}"
                            alt=""
                            width="200" height="150"
                            loading="lazy"
                        />
                    </span>
                    <div>
                        <p class="pd-author__name">{{ __('blog.article.author.name') }}</p>
                        <p class="pd-author__role">{{ __('blog.article.author.role') }}</p>
                    </div>
                </div>
                <p class="pd-author__bio">{{ __('blog.article.author.bio') }}</p>
                <a href="{{ __('blog.article.author.linkedin_url') }}" class="pd-author__link"
                   target="_blank" rel="noopener noreferrer"
                   data-analytics="article_author_linkedin_click">{{ __('blog.article.author.linkedin_label') }} <span aria-hidden="true">&#8599;</span></a>
            </footer>

        </div>
    </div>
</article>

{{-- JSON-LD Article schema — OND-130 iter 8: rich snippets + E-E-A-T.
     Vychází ze stejné translation entity jako article body (single source of truth).
     Validace: https://search.google.com/test/rich-results --}}
@php
    $articleLd = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'Article',
        'headline'    => $translation?->title,
        'description' => $translation?->description,
        'image'       => $article?->hero_image_url
            ?? ($translation?->img_main ? url('storage/articles/' . $translation->img_main) : null),
        'inLanguage'  => $locale ?? app()->getLocale(),
        'datePublished' => optional($article?->created_at)->toIso8601String(),
        'dateModified'  => optional($article?->updated_at)->toIso8601String(),
        'author'      => [
            '@type'    => 'Person',
            'name'     => __('blog.article.author.name'),
            'url'      => __('blog.article.author.linkedin_url'),
            'jobTitle' => __('blog.article.author.role'),
        ],
        'publisher'   => [
            '@type' => 'Organization',
            'name'  => config('app.name'),
            'url'   => url('/'),
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => asset_v('img/logo/logo_main_svg.svg'),
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => url()->current(),
        ],
    ], static fn ($v) => $v !== null && $v !== '');
@endphp
<script type="application/ld+json">
{!! json_encode($articleLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- Závěr — stejná fáze jako /zapisky (OND-404 §4): člověk ještě nepíše
     poptávku. Stejná komponenta `.pd-next` a stejný nadpis, jen o jednu
     cestu víc: nejdřív DALŠÍ ČLÁNEK (zůstat v obsahu, další otázka), pak
     „za kolik?" a „umí to?". Texty cest jsou hlavy cílových stránek,
     ani jedno nové slovo. `$next` posílá controller (OND-406). --}}
@php
    $routes = [];
    if (! empty($next)) {
        $routes[] = [
            'href'  => lroute('blog') . '/' . $next->slug($locale),
            'meta'  => __('blog.article.page_mark_label'),
            'title' => $next->translation($locale)?->title,
            'text'  => null,
        ];
    }
    foreach (['price' => 'price', 'projects' => 'projects'] as $route => $ns) {
        $routes[] = [
            'href'  => lroute($route),
            'meta'  => __($ns . '.hero.page_mark_label'),
            'title' => trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<br>', ' ', __($ns . '.hero.heading_html'))))),
            'text'  => __($ns . '.hero.upline'),
        ];
    }
@endphp
<section class="pd-section pd-next" data-pdd="article-next">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('blog.cta.heading') }}</h2>
            </header>

            <ul class="pd-points pd-points--next" role="list">
                @foreach ($routes as $r)
                <li class="pd-point">
                    <a href="{{ $r['href'] }}" class="pd-next__link">
                        <span class="pd-work__meta">{{ $r['meta'] }}</span>
                        <span class="pd-point__title">{{ $r['title'] }} <span class="pd-next__arrow" aria-hidden="true">&rarr;</span></span>
                        @if ($r['text'])
                        <span class="pd-point__text">{{ $r['text'] }}</span>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

</div>
@endsection

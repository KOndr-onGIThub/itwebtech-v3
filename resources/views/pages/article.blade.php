@extends('layouts.app')

@section('title', $translation?->title ?? config('app.name'))
{{-- OND-162 F5: article description z DB je často 237-268 znaků (perex-style),
     ale Google ořezává <meta description> kolem 155-160. Trimneme na 155
     s ellipsis (= 156 total), aby SERP snippet byl celý a ne useknutý
     uprostřed věty. JSON-LD Article description (níž v article.blade.php
     a v @push('jsonld')) zůstává plný, schema.org limit nemá. --}}
@section('description', \Illuminate\Support\Str::limit($translation?->description ?? '', 155, '…'))

{{-- OND-137 P4 §SEO: Article + BreadcrumbList JSON-LD pro detail článku.
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

    $articleLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $translation?->title,
        'description' => $translation?->description,
        'image' => $article?->hero_image_url ?: null,
        'datePublished' => optional($article?->published_at)->toAtomString(),
        'inLanguage' => app()->getLocale(),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => url()->current(),
        ],
        'author' => [
            '@type' => 'Person',
            'name'  => $article?->author ?: 'Ondřej Kriška',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name'  => config('app.name'),
            'url'   => url('/'),
        ],
    ]);
    $articleJson = json_encode($articleLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
<script type="application/ld+json">
{!! $articleJson !!}
</script>
@endpush

@section('content')

{{-- OND-251 — vrstva hloubky VĚDOMĚ VYPNUTÁ (žádný obal `pd--depth`).
     Článek je čtecí stránka. Náboj u odstavce nebo dělítka ruší při čtení —
     to byla původní výtka k návrhu 2. Nepatří sem ale ani nasvícení plochy:
     text běží přes celou výšku okna, takže rozsvěcování při scrollu by měnilo
     kontrast POD právě čteným odstavcem. A kontaktní stíny u obrázků samy
     nedávají smysl — stín je vidět jen na nasvícené ploše (§C hloubka.css),
     kterou tu schválně nemáme. Buď celá vrstva, nebo žádná; půlka je horší
     než nic. Rozbor v §E hloubka.css. --}}

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark eyebrow + Plex Sans title (post OND-145 swap).
     Title je dynamický (DB), proto heading_html nedává smysl; jen statická
     page-mark eyebrow + článek title. --}}
<div class="page-hero page-hero--article">
    <div class="container-site">
        <a href="{{ lroute('blog') }}" class="back-link">
            <x-icon.arrow-right class="w-4 h-4 shrink-0 rotate-180" />
            {{ __('blog.back_to_blog') }}
        </a>
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt per CEO PR #78/#80/#82/#83 precedent. --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('blog.article.page_mark_label') }}</span>
        </p>
        <h1 class="page-hero__heading">{{ $translation?->title }}</h1>
        @if ($translation?->description)
        <p class="page-hero__subline">{{ $translation->description }}</p>
        @endif
    </div>
</div>

<article class="article-body">
    <div class="container-site container-site--narrow">

        @php
            // Master image_url (Filament upload, storage public disk) má přednost.
            // Při fallbacku použije legacy `img_main` přes Vite-built `responsive-image` pipeline.
            $heroImage = $article?->hero_image_url;
        @endphp
        @if ($heroImage)
        <figure class="article-figure">
            <img src="{{ $heroImage }}" alt="{{ $translation?->title }}" loading="lazy" />
        </figure>
        @elseif ($translation?->img_main)
        <figure class="article-figure">
            <x-responsive-image
                path="articles/{{ $translation->img_main }}"
                alt="{{ $translation->title }}"
                loading="lazy"
                sizes="(max-width: 768px) 100vw, 760px"
            />
        </figure>
        @endif

        @if ($translation?->perex)
        <div class="article-perex">
            {!! $translation->perex !!}
        </div>
        @endif

        @if ($translation?->content_1)
        <div class="article-content">
            {!! $translation->content_1 !!}
        </div>
        @endif

        @if ($translation?->img_mid)
        <figure class="article-figure">
            <x-responsive-image
                path="articles/{{ $translation->img_mid }}"
                alt="{{ $translation->img_mid_alt ?? '' }}"
                loading="lazy"
                sizes="(max-width: 768px) 100vw, 760px"
            />
        </figure>
        @endif

        @if ($translation?->content_mid)
        <div class="article-content">
            {!! $translation->content_mid !!}
        </div>
        @endif

        @if ($translation?->content_2)
        <div class="article-content">
            {!! $translation->content_2 !!}
        </div>
        @endif

        @if ($translation?->img_end)
        <figure class="article-figure">
            <x-responsive-image
                path="articles/{{ $translation->img_end }}"
                alt="{{ $translation->img_end_alt ?? '' }}"
                loading="lazy"
                sizes="(max-width: 768px) 100vw, 760px"
            />
        </figure>
        @endif

        @if ($translation?->bonus)
        <div class="article-bonus">
            {!! $translation->bonus !!}
        </div>
        @endif

        {{-- Autor box — OND-130 iter 8: foto + Ondřej + LinkedIn + contact CTA.
             E-E-A-T signál pro Google + osobní podpis pro čtenáře. --}}
        <aside class="article-author" data-reveal>
            <p class="article-author__eyebrow">{{ __('blog.article.author.eyebrow') }}</p>
            <div class="article-author__body">
                {{-- OND-297: obal kolem fotky. Zdroj je od OND-295 celý záběr
                     1080×810 na šířku, do kolečka 96 px se z něj přímým
                     `object-fit: cover` vejde obličej jen jako malý flek
                     v horní polovině. Přiblížení řeší CSS na obalu, v repu
                     leží jediný soubor sdílený s /o-mne a /kontakt. --}}
                <span class="article-author__photo">
                    <img
                        src="{{ asset_v('img/about/ondrej_kriska_2026.jpg') }}"
                        alt="{{ __('blog.article.author.name') }}"
                        class="article-author__photo-img"
                        width="200" height="150"
                        loading="lazy"
                    />
                </span>
                <div class="article-author__content">
                    <p class="article-author__name">{{ __('blog.article.author.name') }}</p>
                    <p class="article-author__role">{{ __('blog.article.author.role') }}</p>
                    <p class="article-author__bio">{{ __('blog.article.author.bio') }}</p>
                    <div class="article-author__actions">
                        <a
                            href="{{ __('blog.article.author.linkedin_url') }}"
                            class="btn btn-secondary btn-sm"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-analytics="article_author_linkedin_click"
                        >
                            {{ __('blog.article.author.linkedin_label') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </a>
                        <a
                            href="{{ lroute('contact') }}"
                            class="btn btn-primary btn-sm"
                            data-analytics="article_author_contact_click"
                        >
                            {{ __('blog.article.author.contact_cta') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </a>
                    </div>
                </div>
            </div>
        </aside>

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

{{-- CTA sekce --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <div class="cta-block">
            <h2>{{ __('blog.cta.heading') }}</h2>
            <p>{{ __('blog.cta.text') }}</p>
            <div class="cta-block__actions">
                <a href="{{ lroute('contact') }}" class="btn btn-primary">
                    {{ __('blog.cta.primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

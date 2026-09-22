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

{{-- OND-251 — vrstva hloubky ZAPNUTÁ, ale jen tiché nasvícení a stíny
     pod náhledy. Blog je rozcestník: tady se vybírá, nečte. Kdyby tu bylo
     světla jako na ceníku, slibovalo by to rozhodnutí, které se tu nedělá.
     Vlastní článek vrstvu NEMÁ — viz §E hloubka.css. --}}
<div class="pd--depth pd--depth-sub">

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark + Plex Sans display (post OND-145 swap). --}}
<div class="page-hero page-hero--blog">
    <div class="container-site">
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt per CEO PR #78/#80/#82/#83 precedent. --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('blog.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('blog.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('blog.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('blog.hero.subline') }}</p>
    </div>
</div>

<section class="section-wrapper" data-reveal data-pdd="blog-list">
    <div class="container-site">
        <div class="blog-layout">

            <main class="blog-articles">

                {{-- DB articles --}}
                @forelse ($articles ?? [] as $dbArticle)
                    @php $t = $dbArticle->translation($locale); @endphp
                    @if ($t && $t->title)
                    <article class="blog-card" data-reveal>
                        @if ($t->img_preview)
                        <a href="{{ lroute('blog') }}/{{ $dbArticle->slug($locale) }}" class="blog-card__img-link">
                            <x-responsive-image
                                path="articles/{{ $t->img_preview }}"
                                alt="{{ $t->title }}"
                                loading="lazy"
                                class-img="blog-card__img"
                                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 380px"
                            />
                        </a>
                        @endif
                        <div class="blog-card__body">
                            <h2 class="blog-card__title">
                                <a href="{{ lroute('blog') }}/{{ $dbArticle->slug($locale) }}">{{ $t->title }}</a>
                            </h2>
                            @if ($t->description)
                            <p class="blog-card__desc">{{ $t->description }}</p>
                            @endif
                            <a href="{{ lroute('blog') }}/{{ $dbArticle->slug($locale) }}" class="btn btn-secondary btn-sm">
                                {{ __('blog.read_more') }}
                                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                            </a>
                        </div>
                    </article>
                    @endif
                @empty
                    <p class="blog-empty">{{ __('blog.empty') }}</p>
                @endforelse

                {{-- OND-204 (OND-197 bod 11b): odebrány karty „Obsah v přípravě"
                     a „audit webu zdarma" + postranní nabídka. Audit sliboval
                     výsledek za klienta a stránka měla tři výzvy k akci vedle
                     sebe. Zůstává jedna CTA na konci článku (blog.cta.*). --}}

            </main>

        </div>
    </div>
</section>

</div>
@endsection

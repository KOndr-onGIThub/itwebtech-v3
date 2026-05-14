@extends('layouts.app')

@section('title', $translation?->title ?? config('app.name'))
@section('description', $translation?->description ?? '')

{{-- OND-137 P4 §SEO: Article + BreadcrumbList JSON-LD pro detail článku. --}}
@push('jsonld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'), 'item' => lroute('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.blog'), 'item' => lroute('blog')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $translation?->title ?? ($article?->slug ?? ''), 'item' => url()->current()],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode(array_filter([
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
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<div class="page-hero page-hero--article">
    <div class="container-site">
        <a href="{{ lroute('blog') }}" class="back-link">
            <x-icon.arrow-right class="w-4 h-4 shrink-0 rotate-180" />
            {{ __('blog.back_to_blog') }}
        </a>
        <h1>{{ $translation?->title }}</h1>
        @if ($translation?->description)
        <p class="page-hero__desc">{{ $translation->description }}</p>
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
                alt=""
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
                alt=""
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

    </div>
</article>

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

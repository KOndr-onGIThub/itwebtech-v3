@extends('layouts.app')

@section('title', $translation?->title ?? config('app.name'))
@section('description', $translation?->description ?? '')

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

        @if ($translation?->img_main)
        <figure class="article-figure">
            <img src="/img/articles/{{ $translation->img_main }}" alt="{{ $translation->title }}" loading="lazy">
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
            <img src="/img/articles/{{ $translation->img_mid }}" alt="" loading="lazy">
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
            <img src="/img/articles/{{ $translation->img_end }}" alt="" loading="lazy">
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

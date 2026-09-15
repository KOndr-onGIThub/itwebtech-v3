@extends('layouts.app')

@section('title', __('blog.meta.title'))
@section('description', __('blog.meta.description'))

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('blog.subheading') }}</p>
        <h1>{{ __('blog.heading') }}</h1>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">
        <div class="blog-layout">

            <main class="blog-articles">

                {{-- Article list --}}
                @foreach ($articles as $slug => $article)
                <article class="blog-article-card" data-reveal>
                    <time class="blog-article-date" datetime="{{ $article['published_at'] }}">
                        {{ \Carbon\Carbon::parse($article['published_at'])->translatedFormat('j. F Y') }}
                    </time>
                    <h2 class="blog-article-title">
                        <a href="{{ lroute('article', ['slug' => $slug]) }}">{{ $article['title'] }}</a>
                    </h2>
                    <p class="blog-article-perex">{{ $article['meta_description'] }}</p>
                    <a href="{{ lroute('article', ['slug' => $slug]) }}" class="blog-article-link">
                        Číst článek
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </a>
                </article>
                @endforeach

                {{-- Conversion-first section --}}
                <article class="blog-conversion-card" data-reveal>
                    <p class="section-subheading">{{ __('blog.now.subheading') }}</p>
                    <h2>{{ __('blog.now.heading') }}</h2>
                    <p>{{ __('blog.now.desc') }}</p>
                    <ul class="blog-conversion-list">
                        @foreach (__('blog.now.items') as $item)
                        <li>
                            <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </article>

                <article class="blog-conversion-card blog-conversion-card--highlight" data-reveal>
                    <p class="section-subheading">{{ __('blog.audit.subheading') }}</p>
                    <h2>{{ __('blog.audit.heading') }}</h2>
                    <ul class="blog-conversion-list">
                        @foreach (__('blog.audit.items') as $item)
                        <li>
                            <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="blog-conversion-actions">
                        <a href="{{ lroute('contact') }}" class="btn btn-primary">
                            {{ __('blog.audit.cta_primary') }}
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </a>
                        <a href="{{ lroute('price') }}" class="btn btn-secondary">
                            {{ __('blog.audit.cta_secondary') }}
                        </a>
                    </div>
                </article>
            </main>

            {{-- Sidebar --}}
            <aside class="blog-sidebar">
                <div class="sidebar-ad">
                    <p class="section-subheading">{{ __('blog.sidebar_ad.subheading') }}</p>
                    <h2>{{ __('blog.sidebar_ad.heading') }}</h2>
                    <p>{{ __('blog.sidebar_ad.text') }}</p>
                    <div class="sidebar-ad__actions">
                        <a href="{{ lroute('price') }}" class="btn btn-secondary">
                            {{ __('blog.sidebar_ad.cta_price') }}
                        </a>
                        <a href="{{ lroute('contact') }}" class="btn btn-primary">
                            {{ __('blog.sidebar_ad.cta_contact') }}
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection

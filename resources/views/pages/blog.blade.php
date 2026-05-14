@extends('layouts.app')

@section('title', __('blog.meta.title'))
@section('description', __('blog.meta.description'))

@section('content')

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark + Fraunces italic display --}}
<div class="page-hero page-hero--blog">
    <div class="container-site">
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('blog.hero.page_mark_label') }}</span>
            <span class="page-hero__page-mark-index" aria-hidden="true">{{ __('blog.hero.page_mark_index') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('blog.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('blog.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('blog.hero.subline') }}</p>
    </div>
</div>

<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <div class="blog-layout">

            <main class="blog-articles">

                {{-- DB articles --}}
                @foreach ($articles ?? [] as $dbArticle)
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
                @endforeach

                {{-- Conversion-first blog fallback --}}
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
                        <a href="{{ lroute('contact') }}" class="btn btn-primary">
                            {{ __('blog.sidebar_ad.cta_contact') }}
                        </a>
                        <a href="{{ lroute('price') }}" class="btn btn-secondary">
                            {{ __('blog.sidebar_ad.cta_price') }}
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection

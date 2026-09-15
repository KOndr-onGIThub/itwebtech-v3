@extends('layouts.app')

@section('title', $article['title'] . ' — Ondraweb')
@section('description', $article['meta_description'])

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">
            <a href="{{ lroute('blog') }}">Jak na to</a>
        </p>
        <h1>{{ $article['title'] }}</h1>
        <time class="blog-article-date" datetime="{{ $article['published_at'] }}">
            {{ \Carbon\Carbon::parse($article['published_at'])->translatedFormat('j. F Y') }}
        </time>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">
        <div class="article-layout">

            <article class="article-content prose">
                {!! nl2br(e($article['content'])) !!}
            </article>

            <aside class="blog-sidebar">
                <div class="sidebar-ad">
                    <p class="section-subheading">Chcete pomoci?</p>
                    <h2>Pojďme si o tom promluvit</h2>
                    <p>Napište mi a domluvíme si bezplatný hovor. Ukáži vám, co by mohlo fungovat ve vaší situaci.</p>
                    <div class="sidebar-ad__actions">
                        <a href="{{ lroute('contact') }}" class="btn btn-primary">
                            Napsat
                            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                        </a>
                        <a href="{{ lroute('price') }}" class="btn btn-secondary">
                            Ceník
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection

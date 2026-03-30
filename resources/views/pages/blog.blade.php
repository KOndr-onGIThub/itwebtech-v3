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

            {{-- Articles list --}}
            <main class="blog-articles">
                {{-- TODO: loop $articles from DB (Fáze 3) --}}
                <div class="projects-preview-placeholder">
                    <x-icon.file-text class="w-10 h-10 mx-auto mb-3 opacity-30" />
                    <p>{{ __('blog.empty') }}</p>
                </div>
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

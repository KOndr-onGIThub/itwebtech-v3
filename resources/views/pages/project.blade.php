@extends('layouts.app')

@section('title', $translation?->title ?? config('app.name'))
@section('description', $translation?->description ?? '')

@section('content')

<div class="page-hero page-hero--project">
    <div class="container-site">
        <a href="{{ lroute('projects') }}" class="back-link">
            <x-icon.arrow-right class="w-4 h-4 shrink-0 rotate-180" />
            {{ __('projects.back_to_projects') }}
        </a>
        <div class="project-hero__meta">
            @if ($project->customer)
            <span class="project-hero__customer">{{ $project->customer }}</span>
            @endif
            @if ($project->kind)
            <span class="project-hero__kind">{{ $project->kind }}</span>
            @endif
        </div>
        <h1>{{ $translation?->title }}</h1>
        @if ($translation?->description)
        <p class="page-hero__desc">{{ $translation->description }}</p>
        @endif
        @if ($project->price_czk || $project->price_eur)
        <div class="project-hero__price">
            @if ($project->price_czk)
            <span>{{ $project->price_czk }}</span>
            @endif
            @if ($project->price_eur)
            <span class="price-eur">{{ $project->price_eur }}</span>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Hlavní obsah --}}
@if ($translation?->content)
<section class="section-wrapper">
    <div class="container-site container-site--narrow">
        <div class="project-content">
            {!! $translation->content !!}
        </div>
    </div>
</section>
@endif

{{-- Porovnání před/po --}}
@if ($project->comparison && ($project->img_before || $project->img_after))
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('projects.before_after') }}</h2>
        </header>
        <div class="before-after-grid">
            @if ($project->img_before)
            <figure class="before-after-item">
                <img src="/{{ $project->img_before }}" alt="{{ __('projects.before') }}" loading="lazy">
                <figcaption>{{ __('projects.before') }}</figcaption>
            </figure>
            @endif
            @if ($project->img_after)
            <figure class="before-after-item">
                <img src="/{{ $project->img_after }}" alt="{{ __('projects.after') }}" loading="lazy">
                <figcaption>{{ __('projects.after') }}</figcaption>
            </figure>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Screenshoty --}}
@if ($project->screens && $project->screens->count())
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('projects.screenshots') }}</h2>
        </header>
        <div class="screenshots-grid" data-reveal-group>
            @foreach ($project->screens as $screen)
            <figure class="screenshot-item">
                @if ($screen->is_video && $screen->video_url)
                    <iframe src="{{ $screen->video_url }}" loading="lazy" allowfullscreen class="screenshot-item__video"></iframe>
                @elseif ($screen->screen_shot)
                    <img src="/{{ $screen->screen_shot }}" alt="{{ $screen->title }}" loading="lazy">
                @endif
                @if ($screen->title)
                <figcaption>{{ $screen->title }}</figcaption>
                @endif
            </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Reference klienta --}}
@if ($translation?->client_says && $project->client_name)
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <blockquote class="testimonial">
            <div class="testimonial__text">
                {!! $translation->client_says !!}
            </div>
            <footer class="testimonial__author">
                @if ($project->client_photo)
                <img src="/{{ $project->client_photo }}" alt="{{ $project->client_name }}" class="testimonial__photo" loading="lazy">
                @endif
                <div>
                    <strong>{{ $project->client_name }}</strong>
                    @if ($project->client_role)
                    <span>{{ $project->client_role }}</span>
                    @endif
                </div>
            </footer>
        </blockquote>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <div class="cta-block">
            <h2>{{ $translation?->cta ?? __('projects.cta.heading') }}</h2>
            <div class="cta-block__actions">
                <a href="{{ lroute('contact') }}" class="btn btn-primary">
                    {{ __('projects.cta.primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                    {{ __('projects.back_to_projects') }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('title', __('projects.meta.title'))
@section('description', __('projects.meta.description'))

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('projects.subheading') }}</p>
        <h1>{{ __('projects.heading') }}</h1>
        <p>{{ __('projects.intro') }}</p>
    </div>
</div>

{{-- Conversion snapshots --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.snapshots.subheading') }}</p>
            <h2>{{ __('projects.snapshots.heading') }}</h2>
            <p class="section-header__desc">{{ __('projects.snapshots.desc') }}</p>
        </header>

        <div class="project-snapshots" data-reveal-group>
            @foreach (__('projects.snapshots.items') as $snapshot)
            <article class="project-snapshot">
                <div class="project-snapshot__meta">
                    <span>{{ $snapshot['type'] }}</span>
                    <span>{{ $snapshot['timeline'] }}</span>
                </div>
                <h3>{{ $snapshot['title'] }}</h3>
                <p>{{ $snapshot['summary'] }}</p>
                <ul>
                    @foreach ($snapshot['outcomes'] as $outcome)
                    <li>
                        <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                        <span>{{ $outcome }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Project fit --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.fit.subheading') }}</p>
            <h2>{{ __('projects.fit.heading') }}</h2>
        </header>

        <div class="project-fit" data-reveal-group>
            <ul class="project-fit__list">
                @foreach (__('projects.fit.items') as $item)
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>{{ $item }}</span>
                </li>
                @endforeach
            </ul>

            <aside class="project-fit__cta">
                <h3>{{ __('projects.fit.cta_heading') }}</h3>
                <p>{{ __('projects.fit.cta_text') }}</p>
                <div class="project-fit__actions">
                    <a href="{{ lroute('contact') }}" class="btn btn-primary">
                        {{ __('projects.fit.cta_primary') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </a>
                    <a href="{{ lroute('price') }}" class="btn btn-secondary">
                        {{ __('projects.fit.cta_secondary') }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- Why me --}}
<section class="section-wrapper section-alt" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.why_me.subheading') }}</p>
            <h2>{{ __('projects.why_me.heading') }}</h2>
        </header>

        <div class="why-grid" data-reveal-group>
            @foreach (__('projects.why_me.items') as $item)
            <div class="why-card">
                <h3>{{ $item['title'] }}</h3>
                <p>{!! $item['description'] !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

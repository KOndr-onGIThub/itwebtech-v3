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
        <div class="page-hero__actions">
            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('projects.intro_cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        </div>
    </div>
</div>

{{-- Projects list --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site" x-data="{ filter: 'all' }">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.list.subheading') }}</p>
            <h2>{{ __('projects.list.heading') }}</h2>
        </header>

        @if (count($projects))
        {{-- Filters --}}
        <div class="filter-tabs">
            <button type="button" class="filter-tab" :class="{ 'filter-tab--active': filter === 'all' }" @click="filter = 'all'">{{ __('projects.filter_all') }}</button>
            <button type="button" class="filter-tab" :class="{ 'filter-tab--active': filter === 'web' }" @click="filter = 'web'">{{ __('projects.filter_websites') }}</button>
            <button type="button" class="filter-tab" :class="{ 'filter-tab--active': filter === 'app' }" @click="filter = 'app'">{{ __('projects.filter_webapps') }}</button>
            <button type="button" class="filter-tab" :class="{ 'filter-tab--active': filter === 'other' }" @click="filter = 'other'">{{ __('projects.filter_other') }}</button>
        </div>

        <div class="projects-grid" data-reveal-group>
            @foreach ($projects as $slug => $project)
            <article
                class="project-card"
                x-show="filter === 'all' || filter === '{{ $project['category'] }}'"
                x-transition.opacity
            >
                <div class="project-card__body">
                    <span class="project-card__field">{{ $project['field'] }}</span>
                    <h3><a href="{{ lroute('project', null, ['url' => $slug]) }}">{{ $project['name'] }}</a></h3>
                    <p>{{ $project['summary'] }}</p>
                </div>
                <div class="project-card__actions">
                    <a href="{{ lroute('project', null, ['url' => $slug]) }}" class="btn btn-secondary">
                        {{ __('projects.list.detail') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                    </a>
                    @if (!empty($project['live_url']))
                    <a href="{{ $project['live_url'] }}" target="_blank" rel="noopener" class="project-card__live">
                        {{ __('projects.list.live') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </a>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        @else
        <p>{{ __('projects.empty') }}</p>
        @endif
    </div>
</section>

{{-- Project fit --}}
<section class="section-wrapper section-alt" data-reveal>
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
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <header class="section-header">
            <p class="section-subheading">{{ __('projects.why_me.subheading') }}</p>
            <h2>{{ __('projects.why_me.heading') }}</h2>
        </header>

        <div class="why-grid" data-reveal-group>
            @foreach (__('projects.why_me.items') as $item)
            <div class="why-card">
                <h3>{{ $item['title'] }}</h3>
                <p>{{ $item['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

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

{{-- Filter tabs + Projects grid --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <nav class="filter-tabs" aria-label="Project filter">
            <button class="filter-tab filter-tab--active" data-filter="all">
                {{ __('projects.filter_all') }}
            </button>
            <button class="filter-tab" data-filter="web-site">
                {{ __('projects.filter_websites') }}
            </button>
            <button class="filter-tab" data-filter="web-app">
                {{ __('projects.filter_webapps') }}
            </button>
            <button class="filter-tab" data-filter="other">
                {{ __('projects.filter_other') }}
            </button>
        </nav>

        <div class="projects-grid" data-reveal-group>
            {{-- TODO: loop $projects from DB (Fáze 3) --}}
            <div class="projects-preview-placeholder" style="grid-column:1/-1;">
                <x-icon.layers class="w-10 h-10 mx-auto mb-3 opacity-30" />
                <p>{{ __('projects.empty') }}</p>
            </div>
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

@extends('layouts.app')

@section('title', $project['name'] . ' — ' . __('projects.meta.title'))
@section('description', $project['summary'])

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('projects.list.field') }}: {{ $project['field'] }}</p>
        <h1>{{ $project['name'] }}</h1>
        <p>{{ $project['summary'] }}</p>
        @if (!empty($project['live_url']))
        <div class="page-hero__actions">
            <a href="{{ $project['live_url'] }}" target="_blank" rel="noopener" class="btn btn-primary">
                {{ __('projects.detail.live') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        </div>
        @endif
    </div>
</div>

{{-- Detail --}}
<section class="section-wrapper" data-reveal>
    <div class="container-site">
        <div class="project-detail" data-reveal-group>

            <div class="project-detail__block">
                <h2>{{ __('projects.detail.brief') }}</h2>
                <p>{{ $project['brief'] }}</p>
            </div>

            <div class="project-detail__block">
                <h2>{{ __('projects.detail.built') }}</h2>
                <p>{{ $project['built'] }}</p>
            </div>

            @if (!empty($project['features']))
            <div class="project-detail__block">
                <h2>{{ __('projects.detail.features') }}</h2>
                <ul class="project-detail__features">
                    @foreach ($project['features'] as $feature)
                    <li>
                        <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (!empty($project['result']))
            <div class="project-detail__block">
                <h2>{{ __('projects.detail.result') }}</h2>
                <p>{{ $project['result'] }}</p>
            </div>
            @endif

            <div class="project-detail__footer">
                @if (!empty($project['live_url']))
                <a href="{{ $project['live_url'] }}" target="_blank" rel="noopener" class="btn btn-primary">
                    {{ __('projects.detail.live') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                </a>
                @else
                <p class="project-detail__internal">{{ __('projects.detail.internal') }}</p>
                @endif

                <a href="{{ lroute('projects') }}" class="btn btn-secondary">
                    <x-icon.arrow-left class="w-4 h-4 shrink-0" />
                    {{ __('projects.detail.back') }}
                </a>
            </div>

        </div>
    </div>
</section>

@endsection

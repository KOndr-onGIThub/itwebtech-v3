@extends('layouts.app')

@section('title', __('errors.404.meta.title'))
@section('description', __('errors.404.meta.description'))

@section('content')
<section class="section-wrapper error-page error-page--404">
    <div class="container-site">
        <p class="section-subheading">{{ __('errors.404.eyebrow') }}</p>
        <h1 class="error-page__heading">{{ __('errors.404.heading') }}</h1>
        <p class="error-page__lead">{{ __('errors.404.subheading') }}</p>
        <p class="error-page__help">{{ __('errors.404.help') }}</p>

        <div class="error-page__ctas">
            <a href="{{ lroute('home') }}" class="btn btn-primary">
                {{ __('errors.404.cta_primary') }}
            </a>
            <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                {{ __('errors.404.cta_secondary') }}
            </a>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', __('errors.500.meta.title'))
@section('description', __('errors.500.meta.description'))

@section('content')
<section class="section-wrapper error-page error-page--500">
    <div class="container-site">
        <h1 class="error-page__heading">{{ __('errors.500.heading') }}</h1>
        <p class="error-page__lead">{{ __('errors.500.subheading') }}</p>
        <p class="error-page__help">{{ __('errors.500.help') }}</p>

        <div class="error-page__ctas">
            <a href="{{ url()->previous() }}" class="btn btn-primary">
                {{ __('errors.500.cta_primary') }}
            </a>
            <a href="{{ lroute('contact') }}" class="btn btn-secondary">
                {{ __('errors.500.cta_secondary') }}
            </a>
        </div>
    </div>
</section>
@endsection

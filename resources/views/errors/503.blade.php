@extends('layouts.app')

@section('title', __('errors.503.meta.title'))
@section('description', __('errors.503.meta.description'))

@section('content')
<section class="section-wrapper error-page error-page--503">
    <div class="container-site">
        <h1 class="error-page__heading">{{ __('errors.503.heading') }}</h1>
        <p class="error-page__lead">{{ __('errors.503.subheading') }}</p>

        <div class="error-page__ctas">
            <a href="mailto:ok@ondraweb.cz" class="btn btn-primary">
                {{ __('errors.503.cta_primary') }}
            </a>
        </div>
    </div>
</section>
@endsection

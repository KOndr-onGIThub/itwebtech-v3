@extends('layouts.app')

@section('title', __('home.meta.title') . ' — ' . config('app.name'))
@section('description', __('home.meta.description'))

@section('content')

<section class="section-wrapper">
    <div class="container-site">
        <div class="section-header">
            <h1 class="section-header__title">{{ __('home.hero.title') }}</h1>
            <p class="section-header__subtitle">{{ __('home.hero.subtitle') }}</p>
            <a href="#" class="btn btn-primary">
                {{ __('home.hero.cta') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        </div>
    </div>
</section>

@endsection

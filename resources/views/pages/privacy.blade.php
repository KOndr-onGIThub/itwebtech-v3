@extends('layouts.app')

@section('title', __('privacy.meta.title'))
@section('description', __('privacy.meta.description'))

@section('hide_prefooter') true @endsection

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <h1>{{ __('privacy.heading') }}</h1>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">
        <article class="prose-content">
            {!! __('privacy.content') !!}
        </article>
    </div>
</section>

@endsection

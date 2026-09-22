@extends('layouts.app')

@section('title', __('about.meta.title'))
@section('description', __('about.meta.description'))

@section('content')

{{-- OND-251 — vrstva hloubky ZAPNUTÁ: jen světlo a hmota, žádný pohyb.
     Osobní stránka. Na homepage je osobní sekce (07) vědomě úleva po
     nejjasnějším místě — tady platí totéž. Rozbor v §E hloubka.css. --}}
<div class="pd--depth pd--depth-sub">

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('about.subheading') }}</p>
        <h1>{{ __('about.heading') }}</h1>
    </div>
</div>

{{-- Intro + portrait --}}
<section class="section-wrapper" data-pdd="about-intro">
    <div class="container-site">
        <div class="about-intro">
            <div class="about-intro__text">
                <p class="section-prose-text">{{ __('about.intro') }}</p>
            </div>

            {{-- OND-202: Ondrovo intro video místo statického portrétu
                 (kap. 9 bod 1 — umístění homepage + O mně). --}}
            <div class="about-intro__photo about-intro__video">
                <x-video-intro :ariaLabel="__('about.video_aria')" />
            </div>
        </div>
    </div>
</section>

{{-- Story sections --}}
<section class="section-wrapper section-alt" data-pdd="about-story">
    <div class="container-site">
        <article class="prose-content">
            @foreach (__('about.sections') as $section)
                <h2>{{ $section['heading'] }}</h2>
                <p>{{ $section['text'] }}</p>
            @endforeach
        </article>
    </div>
</section>

{{-- CTA --}}
<section class="section-wrapper section-cta" data-reveal data-pdd="about-cta">
    <div class="container-site">
        <h2 style="font-size:clamp(1.5rem,3vw,2.25rem);letter-spacing:-0.025em;margin-bottom:1.5rem;width:100%;text-align:center;">
            {{ __('about.cta_text') }}
        </h2>
        <a href="{{ lroute('contact') }}" class="btn btn-primary">
            {{ __('about.cta_button') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
        </a>
    </div>
</section>

</div>
@endsection

@extends('layouts.app')

@section('title', __('cookies.meta.title'))
@section('description', __('cookies.meta.description'))

@section('hide_prefooter') true @endsection

@section('content')

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark + Plex Sans display (post OND-145 swap).
     OND-168 (2026-05-22): localizován do EN/DE — všechny stringy přesunuty do
     lang/{cs,en,de}/cookies.php a route přesunuta pod localized routes group
     ({cs,en,de}.cookies). --}}
<div class="page-hero page-hero--cookies">
    <div class="container-site">
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('cookies.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('cookies.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('cookies.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('cookies.hero.subline') }}</p>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">

        {{-- TL;DR card — OND-130 iter 8: plain-language summary nad detailem. --}}
        <aside class="legal-tldr" data-reveal>
            <p class="legal-tldr__eyebrow">{{ __('cookies.tldr.eyebrow') }}</p>
            <ul class="legal-tldr__list">
                @foreach (__('cookies.tldr.items') as $item)
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>{!! $item !!}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        <article class="prose-content">

            <p>{!! __('cookies.intro') !!}</p>

            <h2>{{ __('cookies.what_we_use.heading') }}</h2>
            <ul>
                @foreach (__('cookies.what_we_use.items') as $item)
                <li>{!! $item !!}</li>
                @endforeach
            </ul>
            <p>{!! __('cookies.what_we_use.note') !!}</p>

            <h2>{{ __('cookies.what_we_measure.heading') }}</h2>
            <ul>
                @foreach (__('cookies.what_we_measure.items') as $item)
                <li>{!! $item !!}</li>
                @endforeach
            </ul>

            <h2>{{ __('cookies.retention.heading') }}</h2>
            <ul>
                @foreach (__('cookies.retention.items') as $item)
                <li>{!! $item !!}</li>
                @endforeach
            </ul>

            <h2>{{ __('cookies.revoke.heading') }}</h2>
            <p>{{ __('cookies.revoke.description') }}</p>
            <p>
                <button type="button"
                        class="btn btn-primary"
                        onclick="if (window.ItwebtechAnalytics) { window.ItwebtechAnalytics.revokeConsent(); location.reload(); }">
                    {{ __('cookies.revoke.button') }}
                </button>
            </p>
            <p>{!! __('cookies.revoke.manual') !!}</p>

            <h2>{{ __('cookies.controller.heading') }}</h2>
            <p>
                {{ __('cookies.controller.name') }}<br>
                {{ __('cookies.controller.email_label') }}: <a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
            </p>
            <p>{!! __('cookies.controller.see_privacy_html', [
                'link' => '<a href="' . lroute('privacy') . '">' . __('cookies.controller.see_privacy_link') . '</a>',
            ]) !!}</p>

        </article>
    </div>
</section>

@endsection

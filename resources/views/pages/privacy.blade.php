@extends('layouts.app')

@section('title', __('privacy.meta.title'))
@section('description', __('privacy.meta.description'))

@section('hide_prefooter') true @endsection

@section('content')

{{-- Page hero — OND-130 iter 8: plán §3.1 page-mark + Fraunces italic display --}}
<div class="page-hero page-hero--privacy">
    <div class="container-site">
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('privacy.hero.page_mark_label') }}</span>
            <span class="page-hero__page-mark-index" aria-hidden="true">{{ __('privacy.hero.page_mark_index') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('privacy.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('privacy.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('privacy.hero.subline') }}</p>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">

        {{-- TL;DR card — OND-130 iter 8: plain-language summary nad právním textem.
             Sníží bounce ze stránky a respektuje, že většina návštěvníků hledá
             rychlou odpověď. Detailní GDPR text následuje níž. --}}
        <aside class="legal-tldr" data-reveal>
            <p class="legal-tldr__eyebrow">{{ __('privacy.tldr.eyebrow') }}</p>
            <ul class="legal-tldr__list">
                @foreach (__('privacy.tldr.items') as $item)
                <li>
                    <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                    <span>{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        <article class="prose-content">
            {!! __('privacy.content') !!}
        </article>
    </div>
</section>

@endsection

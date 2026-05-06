@extends('layouts.app')

@section('title', __('contact.meta.title'))
@section('description', __('contact.meta.description'))

@section('content')

{{-- Page hero --}}
<div class="page-hero">
    <div class="container-site">
        <p class="section-subheading">{{ __('contact.subheading') }}</p>
        <h1>{{ __('contact.heading') }}</h1>
    </div>
</div>

<section class="section-wrapper">
    <div class="container-site">

        <div class="contact-layout">

            {{-- Contact info --}}
            <aside class="contact-info">

                <div class="contact-info__photo-wrap">
                    <picture>
                        <source srcset="{{ asset('img/about/ondrej_kriska_preview.webp') }}" type="image/webp">
                        <img
                            src="{{ asset('img/about/ondrej_kriska.jpg') }}"
                            alt="Ondřej Kriška"
                            class="contact-info__photo"
                            loading="lazy"
                            width="260"
                            height="300"
                        >
                    </picture>
                </div>

                <dl>
                    <div>
                        <dt>{{ __('contact.address_label') }}</dt>
                        <dd>
                            Ondřej Kriška<br>
                            Česká republika
                        </dd>
                    </div>

                    <div>
                        <dt>E-mail</dt>
                        <dd>
                            <a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
                        </dd>
                    </div>

                    <div>
                        <dt>{{ __('contact.hours_label') }}</dt>
                        <dd>{!! __('contact.open_hours') !!}</dd>
                    </div>
                </dl>

                <a href="#kontaktni-formular" class="btn btn-primary" style="margin-top:2rem;display:inline-flex;">
                    {{ __('contact.cta_consultation') }}
                </a>
            </aside>

            {{-- Contact form --}}
            <div id="kontaktni-formular" x-data="contactForm">
                <h2 style="margin-bottom:.5rem;">{{ __('contact.form_heading') }}</h2>
                <p style="color:var(--color-ink-500);font-size:.9375rem;margin-bottom:1.75rem;line-height:1.65;">{{ __('contact.form_subheading') }}</p>

                <form @submit.prevent="submit" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('contact.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="{{ __('contact.name') }}">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="email">{{ __('contact.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="vas@email.cz">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="tel">{{ __('contact.tel') }} <span aria-hidden="true">*</span></label>
                            <input type="tel" id="tel" name="tel" required autocomplete="tel"
                                   placeholder="+420 000 000 000">
                        </div>
                    </div>
                    <div style="margin-bottom:1.125rem;"></div>

                    <div class="form-group">
                        <label for="subject">{{ __('contact.subject') }}</label>
                        <input type="text" id="subject" name="subject"
                               placeholder="{{ __('contact.subject') }}">
                    </div>

                    <div class="form-group">
                        <label for="message">{{ __('contact.message') }}</label>
                        <textarea id="message" name="message" rows="5"
                                  placeholder="{{ __('contact.message_placeholder') }}"></textarea>
                    </div>

                    <x-form.file-drop />

                    <div class="form-group form-group--checkbox">
                        <label>
                            <input type="checkbox" name="gdpr" required>
                            {{ __('contact.agree') }}
                            <a href="{{ lroute('privacy') }}">{{ __('contact.policy') }}</a>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <button type="submit" class="btn btn-primary" :disabled="loading" style="width:100%;justify-content:center;">
                            <span x-show="!loading" style="display:flex;align-items:center;">
                                {{ __('contact.send') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-left:.375rem;">
                                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </span>
                            <span x-show="loading" x-cloak style="display:flex;align-items:center;gap:.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                </svg>
                                {{ __('contact.sending') ?? '...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@endsection

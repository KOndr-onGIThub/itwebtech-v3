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

                <a href="#kontaktni-formular" class="btn btn-primary contact-info__cta">
                    {{ __('contact.cta_consultation') }}
                </a>
            </aside>

            {{-- Contact form --}}
            <div id="kontaktni-formular" class="contact-form" x-data="contactForm">
                <h2 class="contact-form__title">{{ __('contact.form_heading') }}</h2>
                <p class="contact-form__subtitle">{{ __('contact.form_subheading') }}</p>

                <form @submit.prevent="submit" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('contact.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" required autocomplete="name"
                               placeholder="{{ __('contact.name') }}">
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group form-group--inline">
                            <label for="email">{{ __('contact.email') }} <span aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                   placeholder="vas@email.cz">
                        </div>
                        <div class="form-group form-group--inline">
                            <label for="tel">{{ __('contact.tel') }} <span aria-hidden="true">*</span></label>
                            <input type="tel" id="tel" name="tel" required autocomplete="tel"
                                   placeholder="+420 000 000 000">
                        </div>
                    </div>

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

                    <div class="form-group form-group--inline">
                        <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                            <span class="btn__inner" x-show="!loading">
                                {{ __('contact.send') }}
                                <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                            </span>
                            <span class="btn__inner" x-show="loading" x-cloak>
                                <svg class="btn__spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
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

@endsection

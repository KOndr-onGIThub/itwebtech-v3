{{-- ===================================================
     Inline poptávkový formulář (OND-100, T05)
     Persistuje do landing_leads se source = home.inline.
     =================================================== --}}
<section id="{{ __('home.anchors.poptavka') }}" class="section-wrapper section-alt section-inline-form" data-reveal>
    <div class="container-site inline-form-shell">
        <div class="inline-form-intro">
            <p class="section-subheading">{{ __('home.inline_form.eyebrow') }}</p>
            <h2>{{ __('home.inline_form.heading') }}</h2>
            <p class="section-header__desc">{{ __('home.inline_form.description') }}</p>
        </div>

        <div class="inline-form-panel">
            @if (session('home_lead_success'))
                <div class="landing-alert landing-alert--success" role="status">
                    {{ __('home.inline_form.success') }}
                </div>
            @endif

            {{-- Zobrazujeme chybu jen pokud cíl je tento formulář (T18 přidal druhý formulář na FAQ). --}}
            @if ($errors->any() && session('home_lead_target') !== 'faq')
                <div class="landing-alert landing-alert--error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Po neúspěšném submitu FAQ formuláře (T18) nechceme předvyplnit inline formulář
                 FAQ daty ani zobrazit FAQ chyby zde — proto guard přes home_lead_target. --}}
            @php($isInlineTarget = session('home_lead_target') !== 'faq')

            <form
                method="POST"
                action="{{ route('home.lead.store') }}"
                novalidate
                x-data="{ submitting: false }"
                @submit="submitting = true; window.dispatchEvent(new CustomEvent('inline-form-submit-attempt'))"
            >
                @csrf

                <div class="inline-form-grid">
                    <div class="form-group">
                        <label for="home-lead-name">{{ __('home.inline_form.name') }} <span aria-hidden="true">*</span></label>
                        <input
                            type="text"
                            id="home-lead-name"
                            name="name"
                            value="{{ $isInlineTarget ? old('name') : '' }}"
                            required
                            placeholder="{{ __('home.inline_form.placeholders.name') }}"
                            autocomplete="name"
                        >
                        @if ($isInlineTarget) @error('name') <p class="landing-field-error">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="form-group">
                        <label for="home-lead-email">{{ __('home.inline_form.email') }} <span aria-hidden="true">*</span></label>
                        <input
                            type="email"
                            id="home-lead-email"
                            name="email"
                            value="{{ $isInlineTarget ? old('email') : '' }}"
                            required
                            placeholder="{{ __('home.inline_form.placeholders.email') }}"
                            autocomplete="email"
                        >
                        @if ($isInlineTarget) @error('email') <p class="landing-field-error">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="form-group form-group--full">
                        <label for="home-lead-phone">{{ __('home.inline_form.phone') }}</label>
                        <input
                            type="tel"
                            id="home-lead-phone"
                            name="phone"
                            value="{{ $isInlineTarget ? old('phone') : '' }}"
                            placeholder="{{ __('home.inline_form.placeholders.phone') }}"
                            autocomplete="tel"
                        >
                        @if ($isInlineTarget) @error('phone') <p class="landing-field-error">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="form-group form-group--full">
                        <label for="home-lead-message">{{ __('home.inline_form.message') }} <span aria-hidden="true">*</span></label>
                        <textarea
                            id="home-lead-message"
                            name="message"
                            rows="5"
                            required
                            placeholder="{{ __('home.inline_form.placeholders.message') }}"
                        >{{ $isInlineTarget ? old('message') : '' }}</textarea>
                        @if ($isInlineTarget) @error('message') <p class="landing-field-error">{{ $message }}</p> @enderror @endif
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary inline-form__submit"
                    :disabled="submitting"
                    data-analytics="inline_form_submit_attempt"
                >
                    <span class="btn__inner" x-show="!submitting">
                        {{ __('home.inline_form.submit') }}
                        <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
                    </span>
                    <span class="btn__inner" x-show="submitting" x-cloak>{{ __('home.inline_form.submitting') }}</span>
                </button>

                <p class="inline-form__privacy">
                    {{ __('home.inline_form.privacy_prefix') }}<a href="{{ lroute('privacy') }}">{{ __('home.inline_form.privacy_link') }}</a>.
                </p>
            </form>
        </div>
    </div>
</section>

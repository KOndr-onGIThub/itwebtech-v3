{{-- OND-448 (B-01): jediný poptávkový formulář webu. Vykresluje ho homepage
     (sekce „Poptávka“) i /kontakt — stejná pole ve stejném pořadí, stejné
     chování, stejné potvrzení. Liší se jen tím, co je kolem (slot) a jestli
     je pod tlačítkem věta s e-mailem (`note` — /kontakt má e-mail vlevo).

     ODESLÁNÍ: `contactForm` (resources/js/app.js) přes AJAX na `POST /contact`
     (`ContactController::send`), bez znovunačtení stránky. `source` rozliší
     místo v DB (`contact_submissions.source`), v mailu i v analytice.
     `locale` nese jazyk stránky — POST nemá jazyk v URL a server podle
     něj nastaví jazyk ještě před validací.

     STAV PO ODESLÁNÍ: potvrzení vykreslí server (`partials.lead-confirmation`,
     pole `confirmation` v JSON odpovědi) a vloží se místo formuláře. Obsah
     slotu musí nést `x-show="!submitted"` sám — je uvnitř téhož `x-data`.

     `.pd-form__panel` dává desku, nabitou hranu i proud do políčka
     (hloubka.css §B11, §C); `$root.scrollIntoView` po odeslání míří sem.

     @param string $source  `home` | `contact`
     @param bool   $note    věta „Nebo mi napište na …“ pod tlačítkem
     @param string $id      id kořene (kotva), nepovinné --}}
@props([
    'source' => 'contact',
    'note'   => true,
    'id'     => null,
])

<div{!! $id ? ' id="'.e($id).'"' : '' !!} class="pd-form__panel"
     x-data="contactForm({ genericError: @js(__('contact.message_error')), source: @js($source) })"
     data-sticky-cta="hide">

    <div class="pd-form__thanks" x-show="submitted" x-cloak x-html="confirmation"></div>

    {{ $slot }}

    <form @submit.prevent="submit" novalidate x-show="!submitted" x-ref="form">
        @csrf

        {{-- OND-280: past na boty — server na ni odpoví stejně jako na úspěch. --}}
        <x-form.honeypot id="lead-website-url" />

        <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
        <input type="hidden" name="source" value="{{ $source }}">

        {{-- Jména polí (`name`, `email`, `tel`, `message`, `attachment[]`)
             nesou serverovou validaci i `errors.*` v contactForm. --}}
        <div class="pd-form__grid">
            <div class="pd-field">
                <label for="lead-name">{{ __('home.inline_form.name') }} <span aria-hidden="true">*</span></label>
                <input type="text" id="lead-name" name="name" required autocomplete="name"
                       placeholder="{{ __('home.inline_form.placeholders.name') }}"
                       @input="clearError('name')"
                       :aria-invalid="errors.name ? 'true' : null"
                       :aria-describedby="errors.name ? 'lead-name-error' : null">
                <p class="pd-field__error" id="lead-name-error" x-show="errors.name" x-text="errors.name" x-cloak></p>
            </div>

            <div class="pd-field">
                <label for="lead-email">{{ __('home.inline_form.email') }} <span aria-hidden="true">*</span></label>
                <input type="email" id="lead-email" name="email" required autocomplete="email"
                       placeholder="{{ __('home.inline_form.placeholders.email') }}"
                       @input="clearError('email')"
                       :aria-invalid="errors.email ? 'true' : null"
                       :aria-describedby="errors.email ? 'lead-email-error' : null">
                <p class="pd-field__error" id="lead-email-error" x-show="errors.email" x-text="errors.email" x-cloak></p>
            </div>

            {{-- OND-256/4: telefon je nepovinný, pošťouchnutí pod polem říká,
                 co člověk získá, když ho vyplní (rozhodnutí boardu, OND-254). --}}
            <div class="pd-field pd-field--full">
                <label for="lead-tel">{{ __('home.inline_form.phone') }}</label>
                <input type="tel" id="lead-tel" name="tel" autocomplete="tel"
                       placeholder="{{ __('home.inline_form.placeholders.phone') }}"
                       @input="clearError('tel')"
                       :aria-invalid="errors.tel ? 'true' : null"
                       :aria-describedby="errors.tel ? 'lead-tel-error' : 'lead-tel-hint'">
                <p class="pd-field__hint" id="lead-tel-hint">{{ __('home.inline_form.phone_hint') }}</p>
                <p class="pd-field__error" id="lead-tel-error" x-show="errors.tel" x-text="errors.tel" x-cloak></p>
            </div>

            <div class="pd-field pd-field--full">
                <label for="lead-message">{{ __('home.inline_form.message') }} <span aria-hidden="true">*</span></label>
                <textarea id="lead-message" name="message" rows="5" required
                          placeholder="{{ __('home.inline_form.placeholders.message') }}"
                          @input="clearError('message')"
                          :aria-invalid="errors.message ? 'true' : null"
                          :aria-describedby="errors.message ? 'lead-message-error' : null"></textarea>
                <p class="pd-field__error" id="lead-message-error" x-show="errors.message" x-text="errors.message" x-cloak></p>
            </div>
        </div>

        {{-- Přílohy (B-01): ve výchozím stavu jen textové tlačítko, zóna
             `x-form.file-drop` (limity beze změny) se rozbalí pod ním a fokus
             skočí na „Vybrat soubory“. S vybraným souborem nebo s chybou
             příloh ze serveru zůstane rozbalená. Zóna je v DOMu pořád —
             její `<input type="file">` nese soubory do FormData. --}}
        <div class="pd-attach" @file-drop:files="attachFiles = $event.detail">
            <button type="button" class="pd-attach__toggle"
                    aria-controls="lead-attachments"
                    aria-expanded="false"
                    :aria-expanded="attachOpen ? 'true' : 'false'"
                    @click="toggleAttachments()">
                <span aria-hidden="true">+</span><span class="pd-attach__label">{{ __('home.inline_form.attach_toggle') }}</span>
            </button>
            <div id="lead-attachments" x-show="attachOpen" x-cloak x-ref="attachments">
                <x-form.file-drop />
            </div>
        </div>

        {{-- Pád bez 422 (500, výpadek sítě) — jediná souhrnná hláška. --}}
        <p class="pd-alert pd-alert--error" role="alert" x-ref="formError"
           x-show="formError" x-text="formError" x-cloak></p>

        <button type="submit" class="pd-cta pd-form__submit" :disabled="loading"
                data-analytics="inline_form_submit_attempt"
                data-analytics-props='{"form_source":"{{ $source }}"}'>
            <span class="btn__inner" x-show="!loading">
                {{ __('home.inline_form.submit') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
            </span>
            <span class="btn__inner" x-show="loading" x-cloak>{{ __('home.inline_form.submitting') }}</span>
        </button>

        @if ($note)
        {{-- OND-490 (bod 15): e-mail ve větě je klikací `mailto:` odkaz. Text se
             nejdřív escapuje, pak se v něm adresa obalí odkazem. --}}
        <p class="pd-form__note">{!! str_replace('ok@ondraweb.cz', '<a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>', e(__('home.inline_form.note'))) !!}</p>
        @endif

        {{-- B-01: místo zaškrtávacího souhlasu jen informace s odkazem —
             zpráva se zpracovává kvůli jednání o smlouvě (čl. 6 odst. 1 písm. b). --}}
        <p class="pd-form__privacy">
            {{ __('home.inline_form.privacy_prefix') }}<a href="{{ lroute('privacy') }}">{{ __('home.inline_form.privacy_link') }}</a>.
        </p>
    </form>
</div>

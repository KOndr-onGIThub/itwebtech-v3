{{-- OND-437 (návrh 2 z OND-429): potvrzení po odeslání poptávky.
     Vykresluje ho server místo formuláře — homepage po přesměrování,
     /kontakt vloží HTML z JSON odpovědi `ContactController::send`.

     @param string      $stampKey    lang klíč štítku (`… · :received`)
     @param string      $headingKey  lang klíč nadpisu
     @param string      $email       adresa z formuláře (escapuje se)
     @param \DateTimeInterface $receivedAt  čas uložení na serveru
     @param bool        $steps       tři kroky jen na homepage — /kontakt má
                                     pod formulářem vlastní „Co se stane potom“
     @param string      $headingTag  h3 na homepage (nad deskou stojí h2 sekce),
                                     h2 na /kontakt (nahrazuje h2 formuláře)

     Fokus: `tabindex="-1"` + `data-lead-confirmation`, na které skočí fokus
     po přesměrování (home.blade.php) nebo po odeslání (contactForm). --}}
@php
    $headingTag = $headingTag ?? 'h2';
    $replyDate = \App\Support\ReplyDate::date();
    $articleLink = '<a href="' . e(route(app()->getLocale() . '.article', ['slug' => __('home.how_i_work.cta_more_slug')])) . '">'
        . e(__('home.inline_form.confirmation.more_article')) . '</a>';
@endphp
<div class="pd-done" role="status" tabindex="-1" data-lead-confirmation>
    <p class="pd-eyebrow">{{ __($stampKey, ['received' => \App\Support\ReplyDate::received($receivedAt)]) }}</p>
    <{{ $headingTag }} class="pd-done__title">{{ __($headingKey) }}</{{ $headingTag }}>
    <p class="pd-done__lead">{!! \App\Support\ReplyDate::sentence('home.inline_form.confirmation.reply', $replyDate, ['email' => $email]) !!}</p>

    @if ($steps ?? false)
    <ol class="pd-done__steps" aria-label="{{ __('home.inline_form.confirmation.steps_aria') }}">
        @foreach (__('home.inline_form.confirmation.steps') as $step)
        <li><b>{{ $step['label'] }}</b><span>{{ $step['text'] }}</span></li>
        @endforeach
    </ol>
    @endif

    <p class="pd-sign">
        &mdash; {{ __('home.inline_form.confirmation.signature') }}
        <svg class="pd-sign__mark" viewBox="0 0 220 60" fill="none" aria-hidden="true">
            <path d="M4 40C16 12 28 8 34 26C40 44 46 20 54 18C62 16 60 38 70 38C82 38 84 10 96 10C110 10 104 44 118 44C136 44 132 14 150 14C166 14 158 34 172 30C182 27 184 16 194 16C202 16 200 26 210 24"
                  stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </p>

    <p class="pd-done__more">{!! str_replace(':article_link', $articleLink, e(__('home.inline_form.confirmation.more'))) !!}</p>
</div>

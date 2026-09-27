@extends('layouts.app')

@section('title', __('cookies.meta.title'))
@section('description', __('cookies.meta.description'))

{{-- OND-266 (5): právní stránky nesmí být slepá ulička — bez navigace
     z nich vedlo ven jen zpětné tlačítko. Od OND-387 nese navigaci
     patička v `layouts/app.blade.php` na všech stránkách stejně; předpatička
     i s prodejním tlačítkem je pryč, a sem ani žádné nepatří (§E). --}}

@section('content')

{{-- OND-251 — vrstva hloubky VĚDOMĚ VYPNUTÁ (žádný obal `pd--depth`).
     Právní text. Nasvícení pod souvislým odstavcem snižuje kontrast a nic za
     to nedává — tahle stránka nemá co prodat, má být čitelná a nudná.
     Rozbor v §E hloubka.css. --}}

{{-- OND-168 (2026-05-22): localizován do EN/DE — všechny stringy v
     lang/{cs,en,de}/cookies.php, route pod localized routes group
     ({cs,en,de}.cookies). --}}
<div class="pd">

{{-- Hlava — `.pd-page-head` (základ OND-379 §2a), vlevo jako všude. --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('cookies.hero.page_mark_label') }} <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ __('cookies.hero.upline') }}</p>
        <h1 class="pd-heading pd-heading--sub">{!! __('cookies.hero.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('cookies.hero.subline') }}</p>
    </div>
</section>

{{-- OND-408 — text ve `.pd-prose` z detailu článku (předloha OND-406 §4b).
     Text beze změny, mění se jen obal. Tlačítko „Odvolat souhlas" je
     funkční ovládání, ne prodejní výzva: dostalo `.pd-cta`, `onclick`
     beze změny (volá `ItwebtechAnalytics.revokeConsent()` z cookies.js). --}}
<section class="pd-section">
    <div class="container-site">
        <div class="pd-prose">
            <aside class="pd-aside">
                <p class="pd-eyebrow">{{ __('cookies.tldr.eyebrow') }}</p>
                <ul>
                    @foreach (__('cookies.tldr.items') as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                </ul>
            </aside>

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
                        class="pd-cta"
                        onclick="if (window.ItwebtechAnalytics) { window.ItwebtechAnalytics.revokeConsent(); location.reload(); }">
                    {{ __('cookies.revoke.button') }}
                </button>
            </p>
            <p>{!! __('cookies.revoke.manual') !!}</p>

            <h2>{{ __('cookies.controller.heading') }}</h2>
            <p>
                {{ __('cookies.controller.name') }}<br>
                {{ __('cookies.controller.email_label') }}: <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>
            </p>
            <p>{!! __('cookies.controller.see_privacy_html', [
                'link' => '<a href="' . lroute('privacy') . '">' . __('cookies.controller.see_privacy_link') . '</a>',
            ]) !!}</p>
        </div>
    </div>
</section>

</div>

@endsection

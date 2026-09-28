@extends('layouts.app')

@section('title', __('privacy.meta.title'))
@section('description', __('privacy.meta.description'))

{{-- OND-266 (5): právní stránky nesmí být slepá ulička — bez navigace
     z nich vedlo ven jen zpětné tlačítko. Od OND-387 nese navigaci
     patička v `layouts/app.blade.php` na všech stránkách stejně; předpatička
     i s prodejním tlačítkem je pryč, a sem ani žádné nepatří (§E). --}}

@section('content')

{{-- OND-251 — vrstva hloubky VĚDOMĚ VYPNUTÁ (žádný obal `pd--depth`).
     Totéž co u cookies: právní text, kde je čitelnost jediné kritérium.
     Rozbor v §E hloubka.css. --}}

<div class="pd">

{{-- Hlava — `.pd-page-head` (základ OND-379 §2a), vlevo jako všude. --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('privacy.hero.page_mark_label') }} <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ __('privacy.hero.upline') }}</p>
        <h1 class="pd-heading pd-heading--sub">{!! __('privacy.hero.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('privacy.hero.subline') }}</p>
    </div>
</section>

{{-- OND-408 — text ve `.pd-prose` z detailu článku (předloha OND-406 §4b).
     Text beze změny, mění se jen obal. Shrnutí „V kostce" nese deska
     `.pd-aside` (plocha, ne čára); ikony u bodů jsou pryč, odrážku dělá
     pomlčka. `privacy.content` je jeden řetězec s `h2`/`h3` uvnitř, proto
     se nečlení do `.pd-steps--chapters` — to by byla změna tvaru `lang/`. --}}
<section class="pd-section">
    <div class="container-site">
        <div class="pd-prose">
            <aside class="pd-aside">
                <p class="pd-eyebrow">{{ __('privacy.tldr.eyebrow') }}</p>
                <ul>
                    @foreach (__('privacy.tldr.items') as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </aside>

            {!! __('privacy.content') !!}
        </div>
    </div>
</section>

</div>

@endsection

@extends('layouts.app')

@section('title', __('about.meta.title'))
@section('description', __('about.meta.description'))

@section('content')

{{-- ============================================================
     OND-380 — /o-mne ve slovníku nové homepage (1. z 9 podstránek).
     Návrh a měření: OND-365. Obsah je NEDOTČENÝ, mění se jen
     slovník. Všech devět lang klíčů (`subheading`, `heading`,
     `intro`, čtyři `sections`, `cta_text`, `cta_button`) sedí
     na stejném místě a ve stejném pořadí jako dřív.

     `pd` přibylo k `pd--depth pd--depth-sub`: tokeny `--pd-accent`
     a základ #0A0A0B žijí na téhle třídě.

     OND-251 — vrstva hloubky ZAPNUTÁ: jen světlo a hmota, žádný
     pohyb (osobní stránka, rozbor v §E hloubka.css). Sekce si drží
     `data-pdd`, na které jsou navázané kužely (about-intro /
     about-story / about-cta), a video si drží `.about-intro__video`,
     na kterém visí kontaktní stín z §E2. Nové CSS je v podpis.css §F.
     ============================================================ --}}
<div class="pd pd--depth pd--depth-sub">

{{-- ===================================================
     01 — KDO TO JE
     Hlava stránky a intro jsou jedna sekce, ne dvě. Titulek,
     věta o tom, kdo to je, a tvář vedle sebe: přesně ta otázka,
     kvůli které sem člověk přišel, zodpovězená nad ohybem.
     =================================================== --}}
<section class="pd-section pd-page-head" data-pdd="about-intro">
    <div class="container-site">
        <div class="pd-page-head__grid">
            <div class="pd-page-head__text">
                <p class="pd-eyebrow">{{ __('about.subheading') }}</p>
                <h1 class="pd-heading pd-heading--sub">{{ __('about.heading') }}</h1>
                <p class="pd-sub">{{ __('about.intro') }}</p>
            </div>

            {{-- OND-202: Ondrovo intro video místo statického portrétu.
                 `.pd-steps__media` je rám z homepage (vlasová linka);
                 `.about-intro__video` drží kontaktní stín z hloubka.css §E2. --}}
            <div class="pd-steps__media about-intro__video">
                <x-video-intro :ariaLabel="__('about.video_aria')" />
            </div>
        </div>
    </div>
</section>

{{-- ===================================================
     02 — VYPRÁVĚNÍ
     Čtyři kapitoly v gramatice kroků z homepage: tenká acidová
     číslice, nadpis, odstavec na 62 ch. Číslice není ozdoba —
     říká, že je to konečný seznam, který jde přečíst celý.
     Portrét zůstává po druhé kapitole (OND-295), jen bez
     zaoblení a bez blur stínu.
     =================================================== --}}
<section class="pd-section" data-pdd="about-story">
    <div class="container-site">
        <ol class="pd-steps pd-steps--chapters">
            @foreach (__('about.sections') as $i => $section)
            <li class="pd-step">
                <span class="pd-step__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="pd-step__body">
                    <h2 class="pd-step__title">{{ $section['heading'] }}</h2>
                    <p class="pd-step__text">{{ $section['text'] }}</p>

                    @if ($loop->index === 1)
                    <figure class="pd-figure">
                        <picture>
                            <source srcset="{{ asset_v('img/about/ondrej_kriska_2026_preview.webp') }}" type="image/webp">
                            <img
                                src="{{ asset_v('img/about/ondrej_kriska_2026.jpg') }}"
                                alt="{{ __('about.portrait_alt') }}"
                                loading="lazy"
                                decoding="async"
                                width="1080"
                                height="810"
                            >
                        </picture>
                    </figure>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ===================================================
     03 — VÝZVA
     Věta a tlačítko, vlevo, na základu. Bez pruhu #161A24,
     bez zlaté elipsy, bez zaobleného tlačítka se svitem.
     =================================================== --}}
<section class="pd-section pd-about-cta" data-pdd="about-cta">
    <div class="container-site">
        <p class="pd-lead">{{ __('about.cta_text') }}</p>
        <a href="{{ lroute('contact') }}" class="pd-cta">
            {{ __('about.cta_button') }}
            <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
        </a>
    </div>
</section>

</div>
@endsection

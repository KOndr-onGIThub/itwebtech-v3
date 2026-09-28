@props(['project'])

{{-- ============================================================
     OND-449 (B-07b) — video smyčka webu pod prvním blokem galerie.
     Zapíná se daty: `portfolio_projects.demo_video` = základ jména
     v `public/videos/portfolio/` (např. `barana-demo`). Soubory:
       {základ}.webm|.mp4 + {základ}-poster.jpg             počítač 16 : 9
       {základ}-mobile.webm|.mp4 + {základ}-mobile-poster.jpg  telefon 4 : 5
     Bez počítačového MP4 na disku se nevykreslí nic.

     Jeden <video>, varianta podle šířky přes <source media>. U každé
     nejdřív AV1 WebM, pak H.264 MP4; `codecs` v typu, aby Safari bez AV1
     sáhlo rovnou po MP4. `preload="none"`: nic se nestahuje, dokud video
     nespustí demo-videos.js (až u obrazovky). Plakát je <picture> pod
     videem — omezený pohyb, Save-Data i bez JS zůstane jen on.
     Video je dekorace (text říká totéž), proto `aria-hidden`; pauza
     (WCAG 2.2.2) je tlačítko v rohu.
     ============================================================ --}}
@php
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    $base = $project->demo_video ? 'videos/portfolio/' . basename($project->demo_video) : null;
    $exists = $base && is_file(public_path("{$base}.mp4"));
    // Úroveň AV1 5.0 / H.264 High 4.0 jsou horní hranice pro 720p i 720 × 900;
    // zařízení, které je umí, přehraje i nižší úroveň, jinak vezme MP4.
    $webm = 'video/webm; codecs="av01.0.05M.08"';
    $mp4 = 'video/mp4; codecs="avc1.640028"';
@endphp

@if ($exists)
<figure class="pd-gallery__band pd-demo" data-demo-video>
    <div class="pd-gallery__frame pd-demo__screen">
        <picture>
            <source media="(max-width: 767px)" srcset="{{ asset_v("{$base}-mobile-poster.jpg") }}" width="720" height="900">
            <img src="{{ asset_v("{$base}-poster.jpg") }}" width="1280" height="720" alt="" loading="lazy" decoding="async">
        </picture>
        <video muted loop playsinline preload="none" aria-hidden="true" tabindex="-1" disablepictureinpicture disableremoteplayback>
            <source media="(max-width: 767px)" src="{{ asset_v("{$base}-mobile.webm") }}" type="{{ $webm }}">
            <source media="(max-width: 767px)" src="{{ asset_v("{$base}-mobile.mp4") }}" type="{{ $mp4 }}">
            <source src="{{ asset_v("{$base}.webm") }}" type="{{ $webm }}">
            <source src="{{ asset_v("{$base}.mp4") }}" type="{{ $mp4 }}">
        </video>
        <button
            type="button"
            class="pd-live__toggle pd-demo__toggle"
            aria-pressed="false"
            aria-label="{{ __('home.portfolio.live.pause') }}"
            data-label-pause="{{ __('home.portfolio.live.pause') }}"
            data-label-play="{{ __('home.portfolio.live.play') }}"
        >
            <svg class="pd-live__i-pause" viewBox="0 0 12 12" aria-hidden="true"><path d="M2.5 1.5h2.5v9H2.5zM7 1.5h2.5v9H7z" fill="currentColor"/></svg>
            <svg class="pd-live__i-play" viewBox="0 0 12 12" aria-hidden="true"><path d="M3 1.5l7 4.5-7 4.5z" fill="currentColor"/></svg>
        </button>
    </div>
</figure>
@endif

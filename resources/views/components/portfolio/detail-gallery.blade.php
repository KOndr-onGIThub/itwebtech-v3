@props(['screenshots'])

@php
    $screenshots = $screenshots ?? collect();
    $hero    = $screenshots->firstWhere('type', 'hero');
    $gallery = $screenshots->where('type', 'gallery')->values();

    // Pokud chybí hero, použij první gallery jako hero a ostatní do galerie.
    if (! $hero && $gallery->count() > 0) {
        $hero = $gallery->shift();
        $gallery = $gallery->values();
    }
@endphp

@if ($hero || $gallery->count())
<section class="portfolio-detail-gallery section-wrapper" data-reveal>
    <div class="container-site">
        @if ($hero)
            <figure class="portfolio-detail-gallery__hero">
                <x-responsive-image
                    path="{{ $hero->path }}"
                    alt="{{ $hero->translation()?->alt ?? '' }}"
                    sizes="(max-width: 1024px) 100vw, 1100px"
                    loading="eager"
                    fetchpriority="high"
                    :lightbox-gallery="'portfolio-screenshots'"
                />
                @if ($hero->translation()?->caption)
                    <figcaption>{{ $hero->translation()->caption }}</figcaption>
                @endif
            </figure>
        @endif

        @if ($gallery->count())
            <div class="portfolio-detail-gallery__grid" data-reveal-group>
                @foreach ($gallery as $shot)
                    <figure class="portfolio-detail-gallery__item">
                        <x-responsive-image
                            path="{{ $shot->path }}"
                            alt="{{ $shot->translation()?->alt ?? '' }}"
                            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 540px"
                            loading="lazy"
                            :lightbox-gallery="'portfolio-screenshots'"
                        />
                        @if ($shot->translation()?->caption)
                            <figcaption>{{ $shot->translation()->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif

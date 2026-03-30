@props([
    'eyebrow'           => null,
    'title',
    'subtitle'          => null,
    'ctaPrimaryLabel'   => 'Poptejte nezávazně',
    'ctaPrimaryRoute'   => 'kontakt',
    'ctaSecondaryLabel' => null,
    'ctaSecondaryRoute' => null,
    'ctaSecondaryUrl'   => null,
    'imagePath'         => null,
    'imageAlt'          => 'BARANA — bioklimatické pergoly',
    'small'             => false,
])

<section class="{{ $small ? 'hero hero--sm' : 'hero' }}">
    {{-- Pozadí --}}
    @if ($imagePath)
        <x-responsive-image
            path="{{ $imagePath }}"
            alt="{{ $imageAlt }}"
            class-img="hero__bg"
            sizes="100vw"
            loading="eager"
            fetchpriority="high"
        />
    @else
        <div class="absolute inset-0 bg-anthracite-soft"></div>
    @endif

    <div class="hero__overlay"></div>

    <div class="hero__content">
        @if ($eyebrow)
            <span class="hero__eyebrow">{{ $eyebrow }}</span>
        @endif

        <h1 class="hero__title">{{ $title }}</h1>

        @if ($subtitle)
            <p class="hero__sub">{{ $subtitle }}</p>
        @endif

        @if ($ctaPrimaryLabel || $ctaSecondaryLabel)
            <div class="hero__ctas">
                @if ($ctaPrimaryLabel)
                    <a href="{{ route($ctaPrimaryRoute) }}" class="btn btn-primary btn-lg">
                        {{ $ctaPrimaryLabel }}
                        <x-icon.arrow-right class="w-5 h-5 shrink-0" />
                    </a>
                @endif

                @if ($ctaSecondaryLabel)
                    @php
                        $secHref = $ctaSecondaryUrl ?? ($ctaSecondaryRoute ? route($ctaSecondaryRoute) : '#');
                    @endphp
                    <a href="{{ $secHref }}" class="btn btn-ghost btn-lg">
                        {{ $ctaSecondaryLabel }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

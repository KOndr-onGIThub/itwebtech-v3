@props([
    'eyebrow'  => null,
    'title',
    'subtitle' => null,
    'btnLabel' => 'Poptejte nezávazně',
    'btnRoute' => 'kontakt',
    'btnUrl'   => null,
])

<section class="cta-band">
    <div class="cta-band__inner container-site" data-reveal>
        @if ($eyebrow)
            <span class="section-eyebrow text-gold/80">{{ $eyebrow }}</span>
        @endif
        <h2 class="cta-band__title">{{ $title }}</h2>
        @if ($subtitle)
            <p class="cta-band__sub">{{ $subtitle }}</p>
        @endif
        @php $href = $btnUrl ?? route($btnRoute); @endphp
        <a href="{{ $href }}" class="cta-band__btn">
            {{ $btnLabel }}
            <x-icon.arrow-right class="w-5 h-5 shrink-0" />
        </a>
    </div>
</section>

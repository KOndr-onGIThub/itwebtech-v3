@props([
    'path',
    'alt' => '',
    'sizes' => '100vw',
    'loading' => null,
    'fetchpriority' => null,
    'lightboxGallery' => null,
])

{{--
    Polymorfní renderer pro portfolio screenshot:
    - `img/projects/...` (seedovaná data) → `<x-responsive-image>` (build-time AVIF/WebP varianty z Vite).
    - `portfolio/...` (admin upload do `storage/app/public/portfolio/...`) → prosté `<img src=Storage::url>`.
    Auto-resize/AVIF pro storage cesty je follow-up (mimo OND-71).
--}}

@if (screenshot_is_storage($path))
    @php
        $url  = screenshot_url($path);
        $dims = screenshot_dimensions($path);
        // OND-137 P4: default loading="lazy" pro storage screenshoty — caller
        // může přepsat eager (např. hero/above-the-fold) přes explicit prop.
        $effectiveLoading = $loading ?? 'lazy';
    @endphp
    @if ($lightboxGallery)
        <a href="{{ $url }}" data-glightbox data-gallery="{{ $lightboxGallery }}">
    @endif
        <img
            src="{{ $url }}"
            alt="{{ $alt }}"
            sizes="{{ $sizes }}"
            @if ($dims) width="{{ $dims['width'] }}" height="{{ $dims['height'] }}" @endif
            loading="{{ $effectiveLoading }}"
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            decoding="async"
        >
    @if ($lightboxGallery)
        </a>
    @endif
@else
    <x-responsive-image
        :path="$path"
        :alt="$alt"
        :sizes="$sizes"
        :loading="$loading"
        :fetchpriority="$fetchpriority"
        :lightbox-gallery="$lightboxGallery"
    />
@endif

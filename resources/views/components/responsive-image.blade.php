@props([
    'path',                     // Path to image in resources/img/...
    'alt' => 'image',
    'classPicture' => null,
    'classImg' => null,
    'width' => null,
    'height' => null,
    'sizes' => '100vw',
    'loading' => null,
    'dataCue' => null,
    'style' => null,
    'decoding' => null,
    'fetchpriority' => null,
    'lightboxTitle' => null,     // Lightbox title
    'lightboxGallery' => null,   // Gallery name
])

{{-- Anchor tag for lightbox, if title or gallery is provided --}}
@if ($lightboxTitle || $lightboxGallery)
    <a
        href="" {{-- href will be set by Alpine --}}
        {!! $lightboxTitle ? "data-glightbox=\"title: {$lightboxTitle}\"" : '' !!}
        {!! $lightboxGallery ? "data-gallery=\"{$lightboxGallery}\"" : '' !!}
    >
@endif

<picture
    {!! $classPicture ? "class=\"$classPicture\"" : '' !!}
    x-data
    x-init="
        const variants = window.sharedImages['{{ $path }}'];
        if (!variants || !Array.isArray(variants)) {
            console.error('Image {{ $path }} not found or has invalid structure');
            return;
        }

        const formatOrder = ['avif', 'webp'];
        const numFormats = formatOrder.length;
        const formatMap = { avif: [], webp: [] };
        const widthList = ['320', '480', '640', '768', '960', '1280', '1536'];

        for (let i = 0; i < variants.length; i++) {
            const formatIndex = i % numFormats;
            const widthIndex = Math.floor(i / numFormats);
            const width = widthList[widthIndex] ?? '1000';
            const format = formatOrder[formatIndex];
            formatMap[format].push(`${variants[i]} ${width}w`);
        }

        $el.querySelector('source[type=\'image/avif\']').srcset = formatMap.avif.join(', ');
        $el.querySelector('source[type=\'image/webp\']').srcset = formatMap.webp.join(', ');
        $el.querySelector('img').src = formatMap.webp[0]?.split(' ')[0];
        $el.querySelector('img').srcset = formatMap.webp.join(', ');

        const aTag = $el.parentElement?.tagName === 'A' ? $el.parentElement : null;
        if (aTag && !aTag.getAttribute('href')) {
            const biggest = formatMap.webp.at(-1)?.split(' ')[0];
            if (biggest) aTag.setAttribute('href', biggest);
        }
    "
>
    {{-- AVIF --}}
    <source type="image/avif">

    {{-- WebP --}}
    <source type="image/webp">

    {{-- Image --}}
    <img
        {!! $classImg ? "class=\"$classImg\"" : '' !!}
        alt="{{ $alt }}"
        sizes="{{ $sizes }}"
        width="{{ $width }}"
        height="{{ $height }}"
        {!! $loading ? "loading=\"$loading\"" : '' !!}
        {!! $style ? "style=\"$style\"" : '' !!}
        {!! $dataCue ? "data-cue=\"$dataCue\"" : '' !!}
        {!! $decoding ? "decoding=\"$decoding\"" : '' !!}
        {!! $fetchpriority ? "fetchpriority=\"$fetchpriority\"" : '' !!}
    >
</picture>

{{-- Close anchor tag --}}
@if ($lightboxTitle || $lightboxGallery)
    </a>
@endif

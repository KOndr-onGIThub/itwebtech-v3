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

{{--
    OND-123 iter3:
    Pokud existují buildem vygenerované varianty (`public/build/assets/{basename}-*.avif|webp`),
    vyrenderujeme `<source srcset>` server-side. Tím se vyřeší LCP block na hero obrázku,
    protože preload skener prohlížeče vidí srcset hned v HTML streamu a nemusí čekat,
    až Alpine `x-init` doběhne.

    Fallback (dev bez `npm run build`): původní Alpine flow s `window.sharedImages`.
--}}
@php
    $srcsets = responsive_image_srcsets($path);
@endphp

{{-- Anchor tag for lightbox, if title or gallery is provided --}}
@if ($lightboxTitle || $lightboxGallery)
    @php
        // OND-265: `data-glightbox` se dřív vypisoval JEN při vyplněném
        // `lightboxTitle`. Galerie případovek posílá pouze `lightboxGallery`,
        // takže odkaz vznikl, ale atribut ne — `app.js` se bez
        // `[data-glightbox]` na stránce rovnou vrátí a knihovna se ani
        // nestáhla. Klik na obrázek pak vyhodil návštěvníka z webu na holý
        // soubor (~101 obrázků na 24 detailech, ověřeno na produkci).
        // Titulek dopočítáváme z `alt`; středník je v GLightboxu oddělovač
        // parametrů, takže ho z titulku vyhazujeme.
        $glightboxTitle = trim(str_replace(';', ',', (string) ($lightboxTitle ?: $alt)));
        // Cíl odkazu = největší varianta (ne 320px `fallback`).
        $lightboxHref = $srcsets ? ($srcsets['largest'] ?? $srcsets['fallback']) : '';
    @endphp
    <a
        href="{{ $lightboxHref }}"
        data-glightbox="{{ $glightboxTitle !== '' ? 'title: ' . $glightboxTitle : '' }}"
        {!! $lightboxGallery ? "data-gallery=\"{$lightboxGallery}\"" : '' !!}
    >
@endif

@if ($srcsets)
    {{-- Server-rendered picture — žádné Alpine, žádné x-init, žádné JS závislosti. --}}
    <picture {!! $classPicture ? "class=\"$classPicture\"" : '' !!}>
        @if ($srcsets['avif'] !== '')
            <source type="image/avif" srcset="{{ $srcsets['avif'] }}" sizes="{{ $sizes }}">
        @endif
        @if ($srcsets['webp'] !== '')
            <source type="image/webp" srcset="{{ $srcsets['webp'] }}" sizes="{{ $sizes }}">
        @endif
        <img
            {!! $classImg ? "class=\"$classImg\"" : '' !!}
            src="{{ $srcsets['fallback'] }}"
            srcset="{{ $srcsets['webp'] !== '' ? $srcsets['webp'] : $srcsets['avif'] }}"
            alt="{{ $alt }}"
            sizes="{{ $sizes }}"
            @if ($width) width="{{ $width }}" @endif
            @if ($height) height="{{ $height }}" @endif
            {!! $loading ? "loading=\"$loading\"" : '' !!}
            {!! $style ? "style=\"$style\"" : '' !!}
            {!! $dataCue ? "data-cue=\"$dataCue\"" : '' !!}
            {!! $decoding ? "decoding=\"$decoding\"" : '' !!}
            {!! $fetchpriority ? "fetchpriority=\"$fetchpriority\"" : '' !!}
        >
    </picture>
@else
    {{-- Dev fallback: Alpine x-init z window.sharedImages (původní chování). --}}
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
        <source type="image/avif">
        <source type="image/webp">
        <img
            {!! $classImg ? "class=\"$classImg\"" : '' !!}
            alt="{{ $alt }}"
            sizes="{{ $sizes }}"
            @if ($width) width="{{ $width }}" @endif
            @if ($height) height="{{ $height }}" @endif
            {!! $loading ? "loading=\"$loading\"" : '' !!}
            {!! $style ? "style=\"$style\"" : '' !!}
            {!! $dataCue ? "data-cue=\"$dataCue\"" : '' !!}
            {!! $decoding ? "decoding=\"$decoding\"" : '' !!}
            {!! $fetchpriority ? "fetchpriority=\"$fetchpriority\"" : '' !!}
        >
    </picture>
@endif

{{-- Close anchor tag --}}
@if ($lightboxTitle || $lightboxGallery)
    </a>
@endif

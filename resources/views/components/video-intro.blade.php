@props([
    'ariaLabel' => '',
    'class' => null,
])

{{--
    OND-202 — Ondrovo intro video (kap. 9 bod 1 master promptu).
    Vertikální video 9:16, mluví do kamery.

    Kontrakt pro výměnu a titulky (drž při každé úpravě):
    - Soubor žije na stabilní cestě `public/video/ondra-uvod.mp4` MIMO Vite
      pipeline — novou verzi videa nasadíš prostým přepsáním souboru.
      Cache-buster `?v=filemtime` se o invalidaci postará sám.
    - Titulky (OND-203, Content Writer): nahraj WebVTT jako
      `public/video/ondra-uvod.cs.vtt` — <track> se objeví automaticky,
      žádná změna kódu. Stejně tak `ondra-uvod.en.vtt` / `ondra-uvod.de.vtt`.
    - Poster `public/video/ondra-uvod-poster.webp` = klidný frame z videa;
      při výměně videa vyměň i poster.
    - NIKDY nepřidávej autoplay se zvukem (explicitní zadání boardu).
--}}
@php
    $videoFile  = public_path('video/ondra-uvod.mp4');
    $posterFile = public_path('video/ondra-uvod-poster.webp');
    $videoUrl   = asset('video/ondra-uvod.mp4') . '?v=' . (is_file($videoFile) ? filemtime($videoFile) : '0');
    $posterUrl  = is_file($posterFile)
        ? asset('video/ondra-uvod-poster.webp') . '?v=' . filemtime($posterFile)
        : null;

    $tracks = [];
    foreach (['cs' => 'Česky', 'en' => 'English', 'de' => 'Deutsch'] as $srclang => $label) {
        $trackFile = public_path("video/ondra-uvod.{$srclang}.vtt");
        if (is_file($trackFile)) {
            $tracks[] = [
                'src'     => asset("video/ondra-uvod.{$srclang}.vtt") . '?v=' . filemtime($trackFile),
                'srclang' => $srclang,
                'label'   => $label,
            ];
        }
    }
    $currentLocale = app()->getLocale();
@endphp

<video
    {{ $attributes->class(['video-intro', $class]) }}
    controls
    preload="metadata"
    playsinline
    @if ($posterUrl) poster="{{ $posterUrl }}" @endif
    @if ($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    width="1080"
    height="1920"
>
    <source src="{{ $videoUrl }}" type="video/mp4">
    @foreach ($tracks as $track)
    <track
        kind="captions"
        src="{{ $track['src'] }}"
        srclang="{{ $track['srclang'] }}"
        label="{{ $track['label'] }}"
        @if ($track['srclang'] === $currentLocale) default @endif
    >
    @endforeach
</video>

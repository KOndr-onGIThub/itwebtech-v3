@props([
    'images',
    'gallery'    => 'gallery',
    'cols'       => 4,       // lg+ (1024px+)
    'colsMd'     => null,    // md+ (768px+), nepovinné
    'colsMobile' => 2,       // výchozí (< 768px nebo < 1024px)
])

@php
$mapMobile = [1=>'grid-cols-1',2=>'grid-cols-2',3=>'grid-cols-3',4=>'grid-cols-4',5=>'grid-cols-5',6=>'grid-cols-6'];
$mapMd     = [1=>'md:grid-cols-1',2=>'md:grid-cols-2',3=>'md:grid-cols-3',4=>'md:grid-cols-4',5=>'md:grid-cols-5',6=>'md:grid-cols-6'];
$mapLg     = [1=>'lg:grid-cols-1',2=>'lg:grid-cols-2',3=>'lg:grid-cols-3',4=>'lg:grid-cols-4',5=>'lg:grid-cols-5',6=>'lg:grid-cols-6'];
$classes  = ($mapMobile[$colsMobile] ?? 'grid-cols-2');
$classes .= $colsMd !== null ? ' ' . ($mapMd[$colsMd] ?? 'md:grid-cols-3') : '';
$classes .= ' ' . ($mapLg[$cols] ?? 'lg:grid-cols-4');
@endphp

<div class="gallery-grid {{ $classes }}" data-reveal-group>
    @foreach ($images as $img)
        <div class="gallery-item aspect-4/3">
            <x-responsive-image
                path="{{ $img['path'] }}"
                alt="{{ $img['alt'] ?? 'Realizace BARANA' }}"
                sizes="(min-width: 1280px) 320px, (min-width: 768px) 33vw, 50vw"
                class-picture="block w-full h-full"
                class-img="gallery-item__img"
                loading="lazy"
                lightbox-title="{{ $img['alt'] ?? 'Realizace BARANA' }}"
                lightbox-gallery="{{ $gallery }}"
            />
            <div class="gallery-item__overlay" aria-hidden="true">
                <div class="gallery-item__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        <line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
                    </svg>
                </div>
            </div>
        </div>
    @endforeach
</div>

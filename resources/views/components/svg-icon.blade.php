{{-- resources/views/components/svg-icon.blade.php --}}
@props([
    'class' => 'w-5 h-5 inline ml-1',
    'fill' => 'none',
    'viewBox' => '0 0 24 24',
    'stroke' => 'currentColor',
    'strokeWidth' => 2,
    'xmlns' => 'http://www.w3.org/2000/svg',
])

<svg
    xmlns="{{ $xmlns }}"
    fill="{{ $fill }}"
    viewBox="{{ $viewBox }}"
    stroke-width="{{ $strokeWidth }}"
    stroke="{{ $stroke }}"
    class="{{ $class }}"
    aria-hidden="true"
    focusable="false"
>
    {{ $slot }}
</svg>

@props(['hreflangs' => []])
@php
    $locale      = app()->getLocale();
    $currentPage = current_page();
    $navItems = [
        ['route' => 'home',     'label' => __('layout.nav.home')],
        ['route' => 'projects', 'label' => __('layout.nav.projects')],
        ['route' => 'price',    'label' => __('layout.nav.price')],
        ['route' => 'blog',     'label' => __('layout.nav.blog')],
        ['route' => 'contact',  'label' => __('layout.nav.contact')],
    ];
    $langLabels = ['cs' => 'CZ', 'en' => 'EN', 'de' => 'DE'];
@endphp

<div x-data="{ open: false }">

    {{-- Navbar --}}
    <header class="navbar">
        <div class="container-site navbar__inner">

            <a href="{{ lroute('home') }}" class="navbar__logo">
                <img src="{{ asset('img/logo/logo_main_svg.svg') }}" alt="{{ config('app.name') }}" class="navbar__logo-img" width="274" height="58">
            </a>

            {{-- Desktop nav --}}
            <nav class="navbar__nav" aria-label="Main navigation">
                @foreach ($navItems as $item)
                    @php
                        $isActive = $currentPage === $item['route']
                            || str_starts_with($currentPage, $item['route'] . '.');
                    @endphp
                    <a href="{{ lroute($item['route']) }}"
                       class="navbar__link {{ $isActive ? 'navbar__link--active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Right side: lang switcher + CTA + hamburger --}}
            <div class="navbar__right">

                <div class="navbar__lang" role="group" aria-label="{{ __('layout.nav.lang_switcher') }}">
                    @foreach ($langLabels as $code => $label)
                        <a href="{{ $hreflangs[$code] ?? lroute($currentPage, $code) }}"
                           hreflang="{{ $code }}"
                           lang="{{ $code }}"
                           @if($locale === $code) aria-current="true" @endif
                           class="navbar__lang-item {{ $locale === $code ? 'navbar__lang-item--active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <x-phone-cta class="navbar__phone" />

                <button
                    type="button"
                    class="btn btn-primary navbar__cta"
                    @click="$dispatch('open-consultation-modal')"
                    data-analytics="sticky_cta_click"
                >
                    {{ __('home.sticky.cta') }}
                </button>

                <button class="navbar__hamburger"
                        @click="open = true"
                        :aria-expanded="open.toString()"
                        aria-label="Open menu">
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                    <span class="navbar__hamburger-line"></span>
                </button>

            </div>
        </div>
    </header>

    {{-- Mobile drawer overlay --}}
    <div class="drawer-overlay"
         x-show="open"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         x-cloak>
    </div>

    {{-- Mobile drawer panel --}}
    <div class="drawer"
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         x-cloak>

        <div class="drawer__header">
            <a href="{{ lroute('home') }}" class="navbar__logo" @click="open = false">
                <img src="{{ asset('img/logo/logo_main_svg.svg') }}" alt="{{ config('app.name') }}" class="navbar__logo-img" width="274" height="58">
            </a>
            <button class="drawer__close" @click="open = false" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Language switcher in drawer --}}
        <div class="drawer__lang" role="group" aria-label="{{ __('layout.nav.lang_switcher') }}">
            @foreach ($langLabels as $code => $label)
                <a href="{{ $hreflangs[$code] ?? lroute($currentPage, $code) }}"
                   hreflang="{{ $code }}"
                   lang="{{ $code }}"
                   @if($locale === $code) aria-current="true" @endif
                   class="navbar__lang-item {{ $locale === $code ? 'navbar__lang-item--active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <nav class="drawer__nav">
            @foreach ($navItems as $item)
                @php
                    $isActive = $currentPage === $item['route']
                        || str_starts_with($currentPage, $item['route'] . '.');
                @endphp
                <a href="{{ lroute($item['route']) }}"
                   class="drawer__link {{ $isActive ? 'drawer__link--active' : '' }}"
                   @click="open = false">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="drawer__cta">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="open = false; $dispatch('open-consultation-modal')"
                    data-analytics="sticky_cta_click"
                >
                    {{ __('home.sticky.cta') }}
                </button>
                <x-phone-cta class="drawer__phone" />
            </div>
        </nav>

    </div>

</div>

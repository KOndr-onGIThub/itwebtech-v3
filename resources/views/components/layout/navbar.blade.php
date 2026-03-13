@php
    $locale      = app()->getLocale();
    $currentPage = current_page();
    $navItems = [
        ['route' => 'home', 'label' => __('layout.nav.home')],
        // TODO: add your nav items here, e.g.:
        // ['route' => 'about',   'label' => __('layout.nav.about')],
        // ['route' => 'contact', 'label' => __('layout.nav.contact')],
    ];
    $langLabels = ['cs' => 'CZ', 'en' => 'EN', 'de' => 'DE'];
@endphp

<div x-data="{ open: false }">

    {{-- Navbar --}}
    <header class="navbar">
        <div class="container-site navbar__inner">

            {{-- Logo — TODO: replace with your logo --}}
            <a href="{{ lroute('home') }}" class="navbar__logo">
                <img src="{{ asset('img/logo/logo_main_svg.svg') }}" alt="{{ config('app.name') }}" class="navbar__logo-img" width="274" height="58">
            </a>

            {{-- Desktop nav (centered via CSS grid) --}}
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

                {{-- Language switcher --}}
                <div class="navbar__lang">
                    @foreach ($langLabels as $code => $label)
                        <a href="{{ lroute($currentPage, $code) }}"
                           class="navbar__lang-item {{ $locale === $code ? 'navbar__lang-item--active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                {{-- CTA (hidden on mobile) — TODO: update CTA route --}}
                <a href="{{ lroute('home') }}" class="btn btn-primary navbar__cta">
                    {{ __('layout.cta.contact') }}
                </a>

                {{-- Hamburger --}}
                <button class="navbar__hamburger"
                        @click="open = true"
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

    {{-- Mobile drawer panel (slides from right) --}}
    <div class="drawer"
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         x-cloak>

        {{-- Drawer header --}}
        <div class="drawer__header">
            <a href="{{ lroute('home') }}" class="navbar__logo" @click="open = false">
                <img src="{{ asset('img/logo/logo_main_svg.svg') }}" alt="{{ config('app.name') }}" class="navbar__logo-img" width="274" height="58">
            </a>
            <button class="drawer__close" @click="open = false" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Nav links + CTA --}}
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
                {{-- TODO: update CTA route --}}
                <a href="{{ lroute('home') }}"
                   class="btn btn-primary"
                   style="justify-content: center;"
                   @click="open = false">
                    {{ __('layout.cta.contact') }}
                </a>
            </div>
        </nav>

    </div>

</div>

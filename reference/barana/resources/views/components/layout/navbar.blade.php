@php
    $currentPage = current_page();
    $navItems = [
        ['route' => 'home',          'label' => 'Úvod'],
        ['route' => 'pergoly',       'label' => 'Pergoly'],
        ['route' => 'brany-a-ploty', 'label' => 'Brány a ploty'],
        ['route' => 'o-nas',         'label' => 'O nás'],
        ['route' => 'jak-to-probiha','label' => 'Jak to probíhá'],
        ['route' => 'realizace',     'label' => 'Realizace'],
    ];
@endphp

<div x-data="{ open: false }">

    {{-- Navbar --}}
    <header class="navbar">
        <div class="container-site navbar__inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="navbar__logo">
                <img src="{{ asset('assets/img/logo_main.svg') }}" alt="BARANA s.r.o." class="navbar__logo-img" width="274" height="58">
            </a>

            {{-- Desktop nav --}}
            <nav class="navbar__nav" aria-label="Hlavní navigace">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="navbar__link {{ $currentPage === $item['route'] ? 'navbar__link--active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Right side: CTA + hamburger --}}
            <div class="navbar__right">
                <a href="{{ route('kontakt') }}" class="navbar__cta">
                    Kontakty
                </a>

                <button class="navbar__hamburger"
                        @click="open = true"
                        aria-label="Otevřít menu"
                        aria-expanded="false"
                        :aria-expanded="open.toString()">
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
         x-cloak
         role="dialog"
         aria-modal="true"
         aria-label="Navigační menu">

        <div class="drawer__header">
            <a href="{{ route('home') }}" class="navbar__logo" @click="open = false">
                <img src="{{ asset('assets/img/logo_main.svg') }}" alt="BARANA s.r.o." class="navbar__logo-img" width="274" height="58">
            </a>
            <button class="drawer__close" @click="open = false" aria-label="Zavřít menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <nav class="drawer__nav">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="drawer__link {{ $currentPage === $item['route'] ? 'drawer__link--active' : '' }}"
                   @click="open = false">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="drawer__cta">
                <a href="{{ route('kontakt') }}"
                   class="btn btn-primary w-full justify-center"
                   @click="open = false">
                    Kontakty
                </a>
            </div>
        </nav>

    </div>

</div>

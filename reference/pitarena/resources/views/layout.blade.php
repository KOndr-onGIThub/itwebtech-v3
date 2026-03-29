<!DOCTYPE html>
<html lang="cs">
  <head>

    <meta charset="utf-8">

    {{-- ► Poppins — bundlováno přes Vite (@fontsource/poppins) --}}

    {{-- Site Title --}}
    <title>@yield('title', 'PITARENA.CZ - VŠE CO POTŘEBUJETE VE SVĚTĚ MX')</title>
    <meta name="description" content="@yield('meta_description', 'Volné jízdy pro celé rodiny, tréniky, závody, servis motorek, půjčovna a prodej nových pitbike a moto vybavení i bazar zánovních strojů.')">

    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="dark">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ url('/safari-pinned-tab.svg') }}" color="#ce1212">
    <meta name="msapplication-TileColor" content="#ce1212">
    <meta name="theme-color" content="#07070a">

    @yield('og')

    {{-- Facebook verification --}}
    <meta name="facebook-domain-verification" content="cxn104985qcr5tzxerayfoij88imeq" />

    {{-- Font Awesome 6 Free + AOS — bundlováno přes Vite (viz app.css) --}}

    {{-- Tailwind + Alpine.js (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('extra_css')

    {{-- Schema.org --}}
    <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "SportsOrganization",
        "name": "Pitbike Aréna Pravice",
        "alternateName": "MX/Enduro Pravice",
        "url": "https://pitarena.cz",
        "logo": "https://pitarena.cz/images/logo/pitarena_logo_grey_500x200.png",
        "sameAs": "https://www.facebook.com/YCFCUPCZ"
      }
    </script>

    {{-- Google Tag Manager — načítá se podmíněně po souhlasu s cookies (viz cookies.js) --}}

  </head>

  <body class="bg-mx-black text-mx-light">

    {{-- Page loader --}}
    <div
      id="page-loader"
      class="page-loader"
      x-data="{ loading: true }"
      x-show="loading"
      x-init="window.addEventListener('load', () => { setTimeout(() => loading = false, 120) })"
      x-transition:leave="transition-opacity duration-500"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
    >
      <div class="spinner"></div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         NAVBAR
    ════════════════════════════════════════════════════════════ --}}
    <header
      x-data="navbar()"
      @scroll.window="handleScroll()"
      :class="{
        'bg-mx-black/[0.96] backdrop-blur-xl border-b border-[rgba(206,18,18,0.15)] shadow-navbar': isSticky,
        'bg-transparent': !isSticky,
        '-translate-y-full': isHidden,
        'translate-y-0': !isHidden,
      }"
      class="fixed top-0 left-0 right-0 z-40 transition-all duration-300"
    >
      {{-- Info lišta --}}
      <div
        :class="isSticky ? 'hidden' : 'block'"
        class="hidden lg:block border-b text-xs text-mx-muted"
        style="background: rgba(7,7,10,0.85); border-color: rgba(255,255,255,0.05);"
      >
        <div class="page-container flex items-center justify-end gap-6 py-1.5">
          <a href="tel:+420704221663" class="flex items-center gap-1.5 hover:text-mx-gold transition-colors">
            <i class="fa-solid fa-phone text-mx-orange text-[10px]"></i>
            (+420) 704 221 663
          </a>
          <a href="mailto:standa@pitarena.cz" class="flex items-center gap-1.5 hover:text-mx-gold transition-colors">
            <i class="fa-solid fa-envelope text-mx-orange text-[10px]"></i>
            standa@pitarena.cz
          </a>
          <a href="https://goo.gl/maps/7aSFFMEQKgmchLudA?coh=178573&entry=tt" target="_blank" class="flex items-center gap-1.5 hover:text-mx-gold transition-colors">
            <i class="fa-solid fa-location-dot text-mx-orange text-[10px]"></i>
            Pravice, okr. Znojmo
          </a>
        </div>
      </div>

      {{-- Hlavní navbar --}}
      <nav class="page-container" itemscope itemtype="https://www.schema.org/SiteNavigationElement">
        <div class="flex items-center justify-between h-16 lg:h-[72px]">

          {{-- Logo --}}
          <a href="{{ route('home') }}" class="flex-shrink-0" aria-label="PitArena - domovská stránka">
            <img
              src="{{ url('images/logo/pitarena.svg') }}"
              alt="logo pitarena"
              width="60" height="23"
              class="h-12 lg:h-16 w-auto"
            />
          </a>

          {{-- Desktop nav --}}
          <ul class="hidden lg:flex items-center gap-0.5" role="menu">

            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ url('/#aktuality') }}"
                 class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">Aktuality</a>
            </li>

            {{-- ZÁVODY dropdown --}}
            <li class="relative" itemprop="name" role="menuitem"
                x-data="{ open: false, timer: null }"
                @mouseenter="clearTimeout(timer); open = true"
                @mouseleave="timer = setTimeout(() => open = false, 180)">
              <a itemprop="url" href="{{ route('cup') }}"
                 class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04] flex items-center gap-1.5">
                ZÁVODY <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform duration-200" :class="open && 'rotate-180 text-mx-orange'"></i>
              </a>
              <div x-show="open" x-transition:enter="transition ease-out duration-150"
                   x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                   class="navbar-dropdown" @mouseenter="clearTimeout(timer)" @click.away="open = false">
                <a itemprop="url" href="{{ route('cup') }}" class="navbar-dropdown-item">Přehled závodů</a>
                <div class="border-b border-white/[0.06] my-1"></div>
                <a itemprop="url" href="{{ route('cup.kaledar_zavodu') }}" class="navbar-dropdown-item">Kalendář závodů</a>
                <a itemprop="url" href="{{ route('cup.kategorie') }}" class="navbar-dropdown-item">Závodní kategorie</a>
                <a itemprop="url" href="{{ route('cup.pravidla') }}" class="navbar-dropdown-item">Pravidla</a>
                <a itemprop="url" href="{{ route('registrace-cup.index') }}" class="navbar-dropdown-item">Registrace</a>
                <a itemprop="url" href="{{ route('get.objednavka_cup') }}" class="navbar-dropdown-item">Do-objednávka</a>
                <a itemprop="url" href="{{ route('cup.vysledky') }}" class="navbar-dropdown-item">Výsledky závodů</a>
                <a itemprop="url" href="{{ route('cup.6h') }}" class="navbar-dropdown-item">Fechtl & Pitbike 6H Cup</a>
              </div>
            </li>

            {{-- PROGRAMY dropdown --}}
            <li class="relative" itemprop="name" role="menuitem"
                x-data="{ open: false, timer: null }"
                @mouseenter="clearTimeout(timer); open = true"
                @mouseleave="timer = setTimeout(() => open = false, 180)">
              <a itemprop="url" href="{{ route('programy') }}"
                 class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04] flex items-center gap-1.5">
                PROGRAMY <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform duration-200" :class="open && 'rotate-180 text-mx-orange'"></i>
              </a>
              <div x-show="open" x-transition:enter="transition ease-out duration-150"
                   x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                   class="navbar-dropdown" @mouseenter="clearTimeout(timer)" @click.away="open = false">
                <a itemprop="url" href="{{ route('programy') }}" class="navbar-dropdown-item">Přehled programů</a>
                <div class="border-b border-white/[0.06] my-1"></div>
                <a itemprop="url" href="{{ route('akademie') }}" class="navbar-dropdown-item">Akademie</a>
                <a itemprop="url" href="{{ route('poukazy') }}" class="navbar-dropdown-item">Poukazy na jízdu</a>
                <a itemprop="url" href="{{ route('zajmovy-krouzek') }}" class="navbar-dropdown-item">Zájmový kroužek</a>
                <a itemprop="url" href="{{ route('kemp') }}" class="navbar-dropdown-item">Kemp</a>
                <a itemprop="url" href="{{ route('mxsoustredeni') }}" class="navbar-dropdown-item">MX soustředění</a>
              </div>
            </li>

            {{-- MOTO dropdown --}}
            <li class="relative" itemprop="name" role="menuitem"
                x-data="{ open: false, timer: null }"
                @mouseenter="clearTimeout(timer); open = true"
                @mouseleave="timer = setTimeout(() => open = false, 180)">
              <a itemprop="url" href="{{ route('moto') }}"
                 class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04] flex items-center gap-1.5">
                MOTO <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform duration-200" :class="open && 'rotate-180 text-mx-orange'"></i>
              </a>
              <div x-show="open" x-transition:enter="transition ease-out duration-150"
                   x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                   class="navbar-dropdown" @mouseenter="clearTimeout(timer)" @click.away="open = false">
                <a itemprop="url" href="{{ route('moto') }}" class="navbar-dropdown-item">Přehled moto</a>
                <div class="border-b border-white/[0.06] my-1"></div>
                <a itemprop="url" href="{{ route('pitbike-pujcovna') }}" class="navbar-dropdown-item">Půjčovna</a>
                <a itemprop="url" href="{{ route('prodej-pitbike') }}" class="navbar-dropdown-item">Nové moto</a>
                <a itemprop="url" href="{{ route('bazar') }}" class="navbar-dropdown-item">Bazar</a>
              </div>
            </li>

            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ route('trat') }}" class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">Trať</a>
            </li>
            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ route('servis') }}" class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">Servis</a>
            </li>
            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ route('blog.index') }}" class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">Blog</a>
            </li>
            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ route('get.galleries') }}" class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">Galerie</a>
            </li>
            <li itemprop="name" role="menuitem">
              <a itemprop="url" href="{{ route('o-nas') }}" class="navbar-link px-3 py-2 rounded-md hover:bg-white/[0.04]">O nás</a>
            </li>

          </ul>

          {{-- Hamburger (mobile) --}}
          <button
            @click="toggleMenu()"
            class="lg:hidden flex flex-col justify-center items-center w-10 h-10 gap-1.5 rounded-lg focus:outline-none"
            style="background: rgba(255,255,255,0.04);"
            aria-label="Otevřít menu"
            :aria-expanded="isOpen"
          >
            <span class="block w-5 h-0.5 bg-white transition-all duration-300"
                  :class="isOpen ? 'rotate-45 translate-y-2' : ''"></span>
            <span class="block w-5 h-0.5 bg-white transition-all duration-300"
                  :class="isOpen ? 'opacity-0' : ''"></span>
            <span class="block w-5 h-0.5 bg-white transition-all duration-300"
                  :class="isOpen ? '-rotate-45 -translate-y-2' : ''"></span>
          </button>

        </div>

        {{-- Mobile menu --}}
        <div
          x-show="isOpen"
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 -translate-y-2"
          x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100 translate-y-0"
          x-transition:leave-end="opacity-0 -translate-y-2"
          class="lg:hidden rounded-b-2xl pb-4"
          style="background: rgba(10,10,16,0.97); border-top: 1px solid rgba(255,255,255,0.06); backdrop-filter: blur(20px);"
          @click.away="closeMenu()"
        >
          <ul class="flex flex-col gap-0.5 px-2 pt-3" role="menu">

            <li role="menuitem">
              <a href="{{ url('/#aktuality') }}" @click="closeMenu()"
                 class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-orange font-semibold text-sm uppercase tracking-wide transition-colors">Aktuality</a>
            </li>

            {{-- ZÁVODY accordion --}}
            <li role="menuitem" x-data="{ open: false }">
              <button @click="toggleDd('zavody')"
                      class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-orange font-semibold text-sm uppercase tracking-wide transition-colors">
                ZÁVODY
                <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform" :class="isDdOpen('zavody') && 'rotate-180 text-mx-orange'"></i>
              </button>
              <div x-show="isDdOpen('zavody')" x-transition class="pl-4 flex flex-col gap-0.5 mt-0.5">
                <a href="{{ route('cup') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Přehled závodů</a>
                <div class="border-b border-white/[0.06] my-1 mx-4"></div>
                <a href="{{ route('cup.kaledar_zavodu') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Kalendář závodů</a>
                <a href="{{ route('cup.kategorie') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Závodní kategorie</a>
                <a href="{{ route('cup.pravidla') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Pravidla</a>
                <a href="{{ route('registrace-cup.index') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Registrace</a>
                <a href="{{ route('get.objednavka_cup') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Do-objednávka</a>
                <a href="{{ route('cup.vysledky') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Výsledky závodů</a>
                <a href="{{ route('cup.6h') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Fechtl & Pitbike 6H Cup</a>
              </div>
            </li>

            {{-- PROGRAMY accordion --}}
            <li role="menuitem">
              <button @click="toggleDd('programy')"
                      class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-orange font-semibold text-sm uppercase tracking-wide transition-colors">
                PROGRAMY
                <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform" :class="isDdOpen('programy') && 'rotate-180 text-mx-orange'"></i>
              </button>
              <div x-show="isDdOpen('programy')" x-transition class="pl-4 flex flex-col gap-0.5 mt-0.5">
                <a href="{{ route('programy') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Přehled programů</a>
                <div class="border-b border-white/[0.06] my-1 mx-4"></div>
                <a href="{{ route('akademie') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Akademie</a>
                <a href="{{ route('poukazy') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Poukazy na jízdu</a>
                <a href="{{ route('zajmovy-krouzek') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Zájmový kroužek</a>
                <a href="{{ route('kemp') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Kemp</a>
                <a href="{{ route('mxsoustredeni') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">MX soustředění</a>
              </div>
            </li>

            {{-- MOTO accordion --}}
            <li role="menuitem">
              <button @click="toggleDd('moto')"
                      class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-orange font-semibold text-sm uppercase tracking-wide transition-colors">
                MOTO
                <i class="fa-solid fa-chevron-down text-[9px] text-mx-muted transition-transform" :class="isDdOpen('moto') && 'rotate-180 text-mx-orange'"></i>
              </button>
              <div x-show="isDdOpen('moto')" x-transition class="pl-4 flex flex-col gap-0.5 mt-0.5">
                <a href="{{ route('moto') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Přehled moto</a>
                <div class="border-b border-white/[0.06] my-1 mx-4"></div>
                <a href="{{ route('pitbike-pujcovna') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Půjčovna</a>
                <a href="{{ route('prodej-pitbike') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Nové moto</a>
                <a href="{{ route('bazar') }}" @click="closeMenu()" class="block px-4 py-2.5 rounded-lg text-gray-400 hover:bg-white/[0.04] hover:text-mx-gold text-sm transition-colors">Bazar</a>
              </div>
            </li>

            <li role="menuitem">
              <a href="{{ route('trat') }}" @click="closeMenu()" class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-gold font-semibold text-sm uppercase tracking-wide transition-colors">Trať</a>
            </li>
            <li role="menuitem">
              <a href="{{ route('servis') }}" @click="closeMenu()" class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-gold font-semibold text-sm uppercase tracking-wide transition-colors">Servis</a>
            </li>
            <li role="menuitem">
              <a href="{{ route('blog.index') }}" @click="closeMenu()" class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-gold font-semibold text-sm uppercase tracking-wide transition-colors">Blog</a>
            </li>
            <li role="menuitem">
              <a href="{{ route('get.galleries') }}" @click="closeMenu()" class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-gold font-semibold text-sm uppercase tracking-wide transition-colors">Galerie</a>
            </li>
            <li role="menuitem">
              <a href="{{ route('o-nas') }}" @click="closeMenu()" class="block px-4 py-3 rounded-lg text-gray-200 hover:bg-white/[0.04] hover:text-mx-gold font-semibold text-sm uppercase tracking-wide transition-colors">O nás</a>
            </li>

            {{-- Kontaktní info (mobile) --}}
            <li class="mt-3 pt-3 px-4 flex flex-col gap-2" style="border-top: 1px solid rgba(255,255,255,0.06);">
              <a href="tel:+420704221663" class="flex items-center gap-2 text-sm text-mx-muted hover:text-mx-gold transition-colors">
                <i class="fa-solid fa-phone text-mx-orange w-4"></i> (+420) 704 221 663
              </a>
              <a href="mailto:standa@pitarena.cz" class="flex items-center gap-2 text-sm text-mx-muted hover:text-mx-gold transition-colors">
                <i class="fa-solid fa-envelope text-mx-orange w-4"></i> standa@pitarena.cz
              </a>
            </li>

          </ul>
        </div>
      </nav>
    </header>

    {{-- Odsazení pod fixed navbar --}}
    <div class="h-16 lg:h-[100px]"></div>

    @yield('breadcrumbs')

    @yield('content')

    {{-- ═══════════════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════════════════ --}}
    <footer class="relative border-gradient-top mt-20" style="background: #0e0e15;">
      <div class="page-container py-14 lg:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-14">

          {{-- Sloupec 1: Logo + info --}}
          <div class="sm:col-span-2 lg:col-span-1">
            <a href="{{ route('home') }}">
              <img src="{{ asset('images/logo/pitarena.svg') }}" alt="PitArena logo" class="h-16 w-auto mb-5 opacity-90">
            </a>
            <address class="not-italic text-sm leading-relaxed" style="color: rgba(185,185,210,0.75);">
              PITBIKE YCF CUP, z.s.<br>
              Komenského 240, 671 68 Šanov<br>
              IČO: 19227019<br>
              č.ú. 131-3333910217/0100
            </address>
          </div>

          {{-- Sloupec 2: Navigace --}}
          <div>
            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] mb-5 text-mx-gold">Navigace</h3>
            <ul class="space-y-2.5 text-sm" style="color: rgba(185,185,210,0.75);">
              <li><a href="{{ route('cup') }}" class="hover:text-mx-gold transition-colors">Závody</a></li>
              <li><a href="{{ route('programy') }}" class="hover:text-mx-gold transition-colors">Programy</a></li>
              <li><a href="{{ route('moto') }}" class="hover:text-mx-gold transition-colors">Moto</a></li>
              <li><a href="{{ route('trat') }}" class="hover:text-mx-gold transition-colors">Trať</a></li>
              <li><a href="{{ route('servis') }}" class="hover:text-mx-gold transition-colors">Servis</a></li>
              <li><a href="{{ route('blog.index') }}" class="hover:text-mx-gold transition-colors">Blog</a></li>
              <li><a href="{{ route('get.galleries') }}" class="hover:text-mx-gold transition-colors">Galerie</a></li>
              <li><a href="{{ route('o-nas') }}" class="hover:text-mx-gold transition-colors">O nás</a></li>
            </ul>
          </div>

          {{-- Sloupec 3: Kontakty --}}
          <div>
            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] mb-5 text-mx-gold">Kontakt</h3>
            <ul class="space-y-3 text-sm" style="color: rgba(185,185,210,0.75);">
              <li>
                <a href="tel:+420704221663" class="flex items-center gap-2.5 hover:text-mx-gold transition-colors group">
                  <span class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all group-hover:bg-mx-orange/15"
                        style="background: rgba(212,148,10,0.08);"">
                    <i class="fa-solid fa-phone text-mx-gold text-[10px]"></i>
                  </span>
                  (+420) 704 221 663
                </a>
              </li>
              <li>
                <a href="mailto:standa@pitarena.cz" class="flex items-center gap-2.5 hover:text-mx-gold transition-colors group">
                  <span class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all group-hover:bg-mx-orange/15"
                        style="background: rgba(212,148,10,0.08);"">
                    <i class="fa-solid fa-envelope text-mx-gold text-[10px]"></i>
                  </span>
                  standa@pitarena.cz
                </a>
              </li>
              <li>
                <a href="https://goo.gl/maps/7aSFFMEQKgmchLudA?coh=178573&entry=tt" target="_blank" class="flex items-center gap-2.5 hover:text-mx-gold transition-colors group">
                  <span class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-all group-hover:bg-mx-orange/15"
                        style="background: rgba(212,148,10,0.08);">
                    <i class="fa-solid fa-location-dot text-mx-gold text-[10px]"></i>
                  </span>
                  Pravice, okr. Znojmo
                </a>
              </li>
            </ul>
          </div>

          {{-- Sloupec 4: Social + platby --}}
          <div>
            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] mb-5 text-mx-gold">Sledujte nás</h3>
            <div class="flex gap-2.5 mb-8">
              <a href="https://www.facebook.com/YCFCUPCZ" target="_blank" aria-label="Facebook"
                 class="flex items-center justify-center w-9 h-9 rounded-full transition-all duration-200"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                 onmouseover="this.style.background='rgba(59,89,152,0.3)';this.style.borderColor='rgba(59,89,152,0.5)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.08)';">
                <i class="fa-brands fa-facebook-f text-sm text-gray-400"></i>
              </a>
              <a href="https://www.instagram.com/ycfcup/" target="_blank" aria-label="Instagram"
                 class="flex items-center justify-center w-9 h-9 rounded-full transition-all duration-200"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                 onmouseover="this.style.background='rgba(225,48,108,0.25)';this.style.borderColor='rgba(225,48,108,0.4)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.08)';">
                <i class="fa-brands fa-instagram text-sm text-gray-400"></i>
              </a>
              <a href="https://www.youtube.com/watch?v=dVFw-NY44bI" target="_blank" aria-label="YouTube"
                 class="flex items-center justify-center w-9 h-9 rounded-full transition-all duration-200"
                 style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);"
                 onmouseover="this.style.background='rgba(206,18,18,0.2)';this.style.borderColor='rgba(206,18,18,0.35)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.borderColor='rgba(255,255,255,0.08)';">
                <i class="fa-brands fa-youtube text-sm text-gray-400"></i>
              </a>
            </div>
            <img
              src="{{ asset('images/paticka_web_tmava.webp') }}"
              loading="lazy"
              width="350" height="32"
              alt="Platební metody — VISA, MASTERCARD, Google Pay, Apple Pay"
              class="w-full max-w-[200px] opacity-60"
            >
          </div>

        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-mx-muted"
             style="border-top: 1px solid rgba(255,255,255,0.05);">
          <div class="flex flex-wrap gap-4">
            <a href="{{ route('obchodni-podminky') }}" class="hover:text-mx-gold transition-colors">Obchodní podmínky</a>
            <a href="{{ route('ochrana-osobnich-udaju') }}" class="hover:text-mx-gold transition-colors">Ochrana osobních údajů</a>
            <a href="{{ url('trener-documents') }}" class="hover:text-mx-gold transition-colors">Pro trenéry</a>
            <a href="{{ route('parts') }}" class="hover:text-mx-gold transition-colors">Pro servis</a>
          </div>
          <div class="flex items-center gap-4">
            <span>© {{ date('Y') }} PITBIKE YCF CUP, z.s.</span>
            <a href="https://ondraweb.cz/" target="_blank" class="hover:text-mx-gold transition-colors">Web: ONDRAWEB.CZ</a>
          </div>
        </div>

      </div>
    </footer>

    @includeIf('sections.akce-manual')

    {{-- AOS — inicializováno v app.js (po načtení modulu) --}}

    @yield('scripts')

    {{-- ═══ Cookie Consent Modal ═══ --}}
    <div id="cookie-overlay" class="cookie-overlay" aria-hidden="true">
      <div id="cookie-modal" class="cookie-modal" role="dialog" aria-modal="true" aria-label="Souhlas se cookies">
        <button id="cookie-close" class="cookie-modal__close" aria-label="Zavřít">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="cookie-modal__icon-wrap">
          <i class="fa-solid fa-cookie-bite"></i>
        </div>
        <p class="cookie-modal__title">Pomůžete nám web zlepšovat?</p>
        <p class="cookie-modal__text">Používáme Google Analytics jen pro statistiky návštěv — bez reklam, bez přeprodeje dat. Pomáhá nám vědět, co vás zajímá.</p>
        <div class="cookie-modal__actions">
          <button id="cookie-accept" class="cookie-modal__accept">
            <i class="fa-solid fa-check"></i> Přijmout vše
          </button>
          <button id="cookie-reject" class="cookie-modal__reject">Odmítnout</button>
        </div>
      </div>
    </div>

    {{-- Social Proof Toast --}}
{{--     <div id="proof-toast" class="proof-toast" role="status" aria-live="polite">
      <div class="proof-toast__icon">
        <i class="fa-solid fa-fire-flame-curved"></i>
      </div>
      <p class="proof-toast__text">
        <span class="proof-toast__strong">Tomáš z Brna</span> si právě rezervoval jízdu na trati!
      </p>
      <button data-toast-close class="ml-1 text-mx-muted hover:text-white transition-colors text-lg leading-none flex-shrink-0"
              aria-label="Zavřít">&times;</button>
    </div> --}}

  </body>
</html>

<!DOCTYPE html>
<html lang="{{ session()->get('locale') == 'en' ? 'en' : 'cs' }}">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	{{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- 1) Preload Google‐Fonts CSS --}}
    <link
        rel="preload"
        as="style"
        href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;700&display=swap"
    />

    {{-- 2) Načíst stylesheet bez blokování renderu --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;700&display=swap"
        rel="stylesheet"
        media="print"
        onload="this.media='all'; this.onload=null;"
    />
    <noscript>
        <link
        href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;700&display=swap"
        rel="stylesheet"
        />
    </noscript>


	<link rel="preload" href="{{ asset('/assets/fonts/material-icons/material-icons.woff2') }}" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="{{ asset('/assets/fonts/material-icons/material-icons-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>

	{{-- Primary Meta Tags --}}
    <title>@yield('title', 'Vývoj webových aplikací, stránek a design')</title>
    <meta name="description" content="@yield('meta_description', 'Programuji aplikace a webové stránky, které jsou efektivní, jednoduché na použití a poskytují uživatelům maximální komfort.')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', 'https://itwebtech.cz/')">
    <meta property="og:title" content="@yield('title', 'Vývoj webových aplikací, stránek a design')">
    <meta property="og:description" content="@yield('meta_description', 'Vývoj webových aplikací, stránek a design, zaměřený na maximalizaci online potenciálu.')">
    <meta property="og:image" content="@yield('og_img', 'https://itwebtech.cz/assets/img/header/itwebtech_header_reference.jpg')">
    <meta property="og:locale" content="cs_CZ">
    <meta property="fb:app_id" content="1029157518134592">
    
    {{-- facebook verification --}}
    <meta name="facebook-domain-verification" content="48l2k6xass48xi14vipz6ryt6qaqii" />
    {{-- google verification --}}
    <meta name="google-site-verification" content="E414B_pEgt8Akg6iBKBZxPuKEgdgVdZXOqxF-xjQNCU" />

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="@yield('og_url', 'https://itwebtech.cz/')">
    <meta property="twitter:title" content="@yield('title', 'Vývoj webových aplikací, stránek a design')">
    <meta property="twitter:description" content="@yield('meta_description', 'Vývoj webových aplikací, stránek a design, zaměřený na maximalizaci online potenciálu.')">
    <meta property="twitter:image" content="@yield('og_img', 'https://itwebtech.cz/assets/img/header/itwebtech_header_reference.jpg')">

    {{-- prevent dark mode --}}
    {{-- <meta name="color-scheme" content="only light"> --}}

    {{-- faviocons  --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2023">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2023">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=2023">
    <link rel="manifest" href="/site.webmanifest?v=2023">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=2023" color="#7ed957">
    <link rel="shortcut icon" href="/favicon.ico?v=2023">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png?v=2023">
    <meta name="theme-color" content="#676767">

	{{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap-grid.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/flickity/flickity.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/flickity/flickity-fade.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/lightGallery/css/lightgallery.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/lightGallery/css/lg-zoom.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/lightGallery/css/lg-thumbnail.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/libs/lightGallery/css/lg-video.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/assets/css/style.min.css').'?'.env('APP_VERSION') }}">
	{{-- <link rel="stylesheet" href="assets/css/ecommerce.css"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/css/main_extension.css').'?'.env('APP_VERSION') }}">

    <!-- Google Tag Manager -->
    <script style="display: none">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PN2JJ99');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Meta Pixel Code -->
    <script style="display: none">
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1490995828319130');
        fbq('track', 'PageView');
    </script>
    <!-- End Meta Pixel Code -->

    {{-- schema org --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ProfessionalService",
            "name": "Webové stránky a aplikace na míru - itwebtech",
            "image": "https://itwebtech.cz/assets/img/logo/itwebtech_500x500_black.png",
            "@id": "",
            "url": "https://itwebtech.cz/",
            "telephone": "+420728697712",
            "address": {
            "@type": "PostalAddress",
            "streetAddress": "Dunajovická 116",
            "addressLocality": "Březí u Mikulova",
            "postalCode": "69181",
            "addressCountry": "CZ"
            },
            "geo": {
            "@type": "GeoCoordinates",
            "latitude": 48.82056313007655,
            "longitude": 16.56607942152941
            }
        }
    </script>

	@yield('CSS_links')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PN2JJ99"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- Facebook (noscript) -->
<noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1490995828319130&ev=PageView&noscript=1"/>
</noscript>

<main class="main">
		
<div class="main-inner">


{{-- captcha errors--}}
@if ($errors->has('g-recaptcha-response'))
    <div id="alert_recaptcha" class="alert_wrap">
        <div class="alert alert_error">
            <div class="alert_message">
                {{ $errors->first('g-recaptcha-response') }}
                google captcha si myslí, že jste robot
            </div>
            <div id="btn_close_alert_recaptcha">
                <i class="fa fa-times-circle"></i>
            </div>
        </div>
    </div>
@endif

{{-- errors from form --}}
@if ($errors->any())
<div class="NG_valid_list">
    @php
        $writeErrors = implode("\\n", $errors->all() );
        echo('<script>alert("' . $writeErrors) . "\\n \\n" . __('layout.form_validation') .'")</script>';
    @endphp
</div>
@endif

{{-- aler box pro info o uspesnem odeslani formulare --}}
@if (session('status_demand'))
<div id="alert_message_sent" class="alert_wrap">
    <div class="alert alert_success">
        <div class="alert_message">
            {!! __(session('status_demand')) !!}
        </div>
        <div class="header_modal_close popup_close" id="btn_close_alert">
            <i class="material-icons md-24">cancel</i>
        </div>
    </div>
</div>
@endif

<!-- Begin mobile main menu -->
<nav class="mmm">
    <div class="mmm-content">
        <ul class="mmm-list">
            <li>
                <a href="{{url('/')}}">@lang('layout.menu.home')</a>
            </li> 
            <li>
                <a href="{{url('/projects')}}">@lang('layout.menu.projects')</a>
            </li> 
            <li>
                <a href="{{url('/price')}}">@lang('layout.menu.price')</a>
            </li>
            <li>
                <a href="{{url('/jak-na-to')}}">@lang('layout.menu.article')</a>
            </li> 
            <li>
                <a href="{{url('/contact')}}">@lang('layout.menu.contact')</a>
            </li> 

        </ul>
        <ul>
            <li>
                                <!-- inserted reservanto code -->
                                <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                                <!-- end of inserted reservanto code -->
            </li>
        </ul>
    </div>
    <div class="mmm-footer">

        <label for="select-lang-mob">CZ/EN</label>
        <select id="select-lang-mob" class="changeLang navbar-nav">
            <option value="cs" {{ session()->get('locale') == 'cs' ? 'selected' : '' }}>Česky</option>
            <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
        </select>
    </div>
</nav><!-- End mobile main menu -->

<!-- Begin header -->
<header class="header">
    <!-- Begin header top -->
    <nav class="header-top">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <!-- Begin header top info -->
                    <ul class="header-top-info">
                        <li>
                            <a href="mailto:ok@itwebtech.cz">
                                <i class="material-icons md-18">mail_outline</i>
                                <span>ok@itwebtech.cz</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+420%20728%20697%20712" class="">
                                <i class="material-icons md-18">phone_in_talk</i>
                                <span>+420 728 697 712</span>
                            </a>
                        </li>
                    </ul><!-- Ennd header top info -->
                </div>
                <div class="col-auto">
                    <div class="header-top-links">
                        <!-- Begin widget socials -->
                        <ul class="widget-socials">
                            <li>
                                <a href="https://www.facebook.com/ondraweb/" target="_blank" title="Facebook">
                                    <svg viewBox="0 0 320 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#facebook-icon"></use></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/@itwebtech/featured" target="_blank" title="Youtube">
                                    <svg viewBox="0 0 448 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#youtube-icon"></use></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/in/ondrej-kriska/" target="_blank" title="LinkedIn">
                                    <svg viewBox="0 0 448 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#linkedin-icon"></use></svg>
                                </a>
                            </li>
                        </ul><!-- End widget socials -->
                        <div class="btn-group-outer">
                            <div class="btn-group align-items-center">
                                <a href="{{ url('/contact') }}" class="header-button {{-- header_modal_open --}}">
                                    <i class="material-icons">ring_volume</i>
                                    <span>@lang('layout.demand')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav><!-- End header top -->
    <!-- Begin header fixed -->
    <nav class="header-fixed">
        <div class="container">
            <div class="row flex-nowrap align-items-center justify-content-between">
                <div class="col-auto d-block d-xl-none header-fixed-col">
                    <div class="main-mnu-btn">
                        <span class="bar bar-1"></span>
                        <span class="bar bar-2"></span>
                        <span class="bar bar-3"></span>
                        <span class="bar bar-4"></span>
                    </div>
                </div>
                <div class="col-auto header-fixed-col logo-wrapper">
                    <!-- Begin logo -->
                    <a href="/" class="logo" title="itwebtech">
                        <img src="{{ asset('/assets/img/logo/itwebtech_400x100_transparent.svg') }}" width="133" height="36" alt="logo itwebtech">
                    </a><!-- End logo -->
                </div>
                <div class="col-auto col-xl col-static header-fixed-col">
                    <div class="row flex-nowrap align-items-center justify-content-end">
                        <div class="col header-fixed-col d-none d-xl-block col-static">
<!-- Begin main menu -->
<nav class="main-mnu">
    <ul class="main-mnu-list" itemscope itemtype="https://www.schema.org/SiteNavigationElement" role="menu">
        <li class="menu-item-has-children" itemprop="name" role="menuitem">
            <a itemprop="url" href="{{url('/')}}" data-title="@lang('layout.menu.home')">
                <span>@lang('layout.menu.home')</span>
            </a>
        </li>
        <li class="menu-item-has-children" itemprop="name" role="menuitem">
            <a itemprop="url" href="{{url('/projects')}}" data-title="@lang('layout.menu.projects')">
                <span>@lang('layout.menu.projects')</span>
            </a>
        </li>
        <li class="menu-item-has-children" itemprop="name" role="menuitem">
            <a itemprop="url" href="{{url('/price')}}" data-title="@lang('layout.menu.price')">
                <span>@lang('layout.menu.price')</span>
            </a>
        </li>
        <li class="menu-item-has-children" itemprop="name" role="menuitem">
            <a itemprop="url" href="{{url('/jak-na-to')}}" data-title="@lang('layout.menu.article')">
                <span>@lang('layout.menu.article')</span>
            </a>
        </li>
        <li class="menu-item-has-children" itemprop="name" role="menuitem">
            <a itemprop="url" href="{{url('/contact')}}" data-title="@lang('layout.menu.contact')">
                <span>@lang('layout.menu.contact')</span>
            </a>
        </li>

    </ul>
</nav><!-- End main menu -->
                        </div>
                        <div class="col-auto header-fixed-col col-static">
                            <!-- Begin header actions -->
                            <ul class="header-actions">


                                <li class="d-none d-lg-block">
                                    <!-- inserted reservanto code -->
                                    <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                                    <!-- end of inserted reservanto code -->
                                </li>

                                <!-- Begin header languarge -->
                                <li class="d-none d-lg-block">
                                    <div class="header-lang">
                                       {{-- <div class="header-lang-current"></div> --}}
                                            <select id="select-lang"  class="changeLang navbar-nav" style="margin-bottom: 0">
                                                <option value="cs" {{ session()->get('locale') == 'cs' ? 'selected' : '' }}>Česky</option>
                                                <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
                                            </select>
                                    </div>
                                </li><!-- End header languarge -->

                                <!-- Begin header navbar -->
                                <li class="d-block d-lg-none">
                                    <div class="header-navbar">
                                        <div class="header-navbar-btn">
                                            <i class="material-icons md-24">more_vert</i>
                                        </div>
                                        <ul class="header-navbar-content">
                                            <li>
            <!-- inserted reservanto code -->
            <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
            <!-- end of inserted reservanto code -->
                                            </li>
                                            <li>
                                                <a href="mailto:ok@itwebtech.cz">
                                                    <i class="material-icons md-20">mail_outline</i>
                                                    <span>ok@itwebtech.cz</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="tel:+420%20728%20697%20712" class="">
                                                    <i class="material-icons md-20">support_agent</i>
                                                    <span>+420 728 697 712</span>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="btn-group align-items-center">
                                                    <a href="{{ url('/contact') }}" class="header-button {{-- header_modal_open --}}">
                                                        <i class="material-icons">ring_volume</i>
                                                        <span>@lang('layout.demand')</span>
                                                    </a>
                                                </div>
                                            </li>
                                            <li>
                                                <!-- Begin widget socials -->
                                                <ul class="widget-socials">
                                                    <li>
                                                        <a href="https://www.facebook.com/ondraweb/" target="_blank" title="Facebook">
                                                            <svg viewBox="0 0 320 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#facebook-icon"></use></svg>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.youtube.com/@itwebtech/featured" target="_blank" title="Youtube">
                                                            <svg viewBox="0 0 448 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#youtube-icon"></use></svg>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://www.linkedin.com/in/ondrej-kriska/" target="_blank" title="LinkedIn">
                                                            <svg viewBox="0 0 448 512"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#linkedin-icon"></use></svg>
                                                        </a>
                                                    </li>
                                                </ul><!-- End widget socials -->
                                            </li>
                                        </ul>
                                    </div>
                                </li><!-- End header navbar -->
                            </ul><!-- End header actions -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav><!-- End header fixed -->
</header><!-- End header -->


@yield('content')

		<!-- Begin footer -->
<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="row justify-content-between items">
                <div class="col-xl-3 col-lg-3 col-md-5 col-12 item">
                    <!-- Begin brand info -->
                    <div class="widget-brand-info">
                        <div class="widget-brand-info-main wbim-p">
                            <a href="/" class="logo" title="itwebtech">
                                <img data-src="{{ asset('/assets/img/logo/itwebtech_400x100_black.svg') }}" class="lazy" width="133" height="36" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="itwebtech logo black">
                            </a>
                            <p>@lang('layout.who_am_i')<br>Ondřej Kriška, IČO: 19231407</p>
                        </div>
                        <ul class="widget-socials widget-socials-bordered widget-socials-bordered-hover-bg wbim-socials">
                            <li>
                                <a href="https://www.facebook.com/ondraweb/" target="_blank" title="Facebook">
                                    <svg viewBox="0 0 320 512">
                                        <use xlink:href="{{ asset('/assets/img/sprite.svg') }}#facebook-icon"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/in/ondrej-kriska/" target="_blank" title="LinkedIn">
                                    <svg viewBox="0 0 448 512">
                                        <use xlink:href="{{ asset('/assets/img/sprite.svg') }}#linkedin-icon"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/@itwebtech/featured" target="_blank" title="Youtube">
                                    <svg viewBox="0 0 448 512">
                                        <use xlink:href="{{ asset('/assets/img/sprite.svg') }}#youtube-icon"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End brand info -->
                </div>
                <div class="col-md-auto col-5 col-lg-2 item">
                    <div class="footer-item">
                        <p class="item-heading widget-heading">@lang('layout.important_links.head')</p>
                        <nav class="footer-nav">
                            <ul class="footer-mnu">
                                <li><a href="{{ url('price') }}" class="hover-link" data-title="@lang('layout.important_links.price_list')"><span>@lang('layout.important_links.price_list')</span></a></li>
                                <li><a href="{{ url('contact') }}" class="hover-link" data-title="@lang('layout.important_links.contact_form')"><span>@lang('layout.important_links.contact_form')</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-xs-4 col-lg-4 col-12 item">
                    <div class="footer-item">
                        <p class="item-heading widget-heading">@lang('layout.menu.contact')</p>
                        <ul class="widget-contacts">
                            <li>
                                <i class="material-icons md-22">location_on</i>
                                <div class="widget-contacts-info">
                                    <a href="https://maps.app.goo.gl/Sk4rcac7x2sDk8RW7" target="_blank" rel="noopener">Dunajovická 116, Březí u Mikulova, 691 81</a>
                                </div>
                            </li>
                            <li>
                                <i class="material-icons md-22">smartphone</i>
                                <div class="widget-contacts-info">
                                    <a href="tel:+420%20728%20697%20712" class="">+420 728 697 712</a>
                                </div>
                            </li>
                            <li>
                                <i class="material-icons md-22">email</i>
                                <div class="widget-contacts-info">
                                    <a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-item">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row justify-content-between items">
                <div class="col-md-auto col-12 item">
                    <nav class="footer-links">
                        <ul>
                            {{-- <li><a href="terms-and-conditions.html">Terms and Conditions</a></li> --}}
                            <li><a href="{{ url('/privacy-policy') }}">@lang('layout.demand_form.policy')</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-auto col-12 item">
                    <div class="copyright">itwebtech All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
</footer><!-- End footer -->



<!-- Begin cookie message -->
<div class="cookie-message">
    <div class="cm-content">
        <span class="cmc-title">Cookies</span>
        <p class="cmc-desc">@lang('layout.cookie_informed') <a href="{{ url('/privacy-policy') }}">@lang('layout.demand_form.policy')</a></p>
    </div>
    <div class="mc-btn btn btn-with-icon btn-small ripple">
        <span>@lang('layout.accept_cookies')</span>
        <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
    </div>
</div><!-- End cookie message -->

	</main><!-- End main -->

<script src="{{ asset('/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/libs/lozad/lozad.min.js') }}"></script>
<script src="{{ asset('/assets/libs/device/device.js') }}"></script>
<script src="{{ asset('/assets/libs/spincrement/jquery.spincrement.min.js') }}"></script>
<script src="{{ asset('/assets/libs/jquery-popup-overlay-gh-pages/jquery.popupoverlay.min.js') }}"></script>
<script src="{{ asset('/assets/libs/flickity/flickity.pkgd.min.js') }}"></script>
<script src="{{ asset('/assets/libs/flickity/flickity-imagesloaded.js') }}"></script>
<script src="{{ asset('/assets/libs/flickity/bg-lazyload.js') }}"></script>
<script src="{{ asset('/assets/libs/flickity/flickity-fade.js') }}"></script>
<script src="{{ asset('/assets/libs/pristine/pristine.min.js') }}"></script>
<script src="{{ asset('/assets/libs/isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('/assets/libs/lightGallery/lightgallery.min.js').'?'.env('APP_VERSION') }}"></script>
<script src="{{ asset('/assets/libs/lightGallery/plugins/zoom/lg-zoom.min.js') }}"></script>
<script src="{{ asset('/assets/libs/lightGallery/plugins/thumbnail/lg-thumbnail.min.js') }}"></script>
<script src="{{ asset('/assets/libs/lightGallery/plugins/video/lg-video.min.js') }}"></script>
<script src="{{ asset('/assets/libs/jquery-ui-range/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/assets/libs/easy-pie-chart/easypiechart.min.js') }}"></script>
<script src="{{ asset('/assets/libs/gsap/gsap.min.js') }}"></script>
<script src="{{ asset('/assets/libs/gsap/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('/assets/js/animations.min.js') }}"></script>
<script src="{{ asset('/assets/js/custom.min.js') }}"></script>
<script src="{{ asset('/assets/js/main_extension.js') }}"></script>
	

{{-- LANG --}}
<script type="text/javascript">
    var url = "{{ route('changeLang') }}";
    $(".changeLang").change(function(){
        window.location.href = url + "?lang="+ $(this).val();
    });
</script>
{{-- reservanto --}}
<script style="display: none" defer id="reservanto-widget-script" type="text/javascript" src="https://booking.reservanto.cz/Script/reservanto-script.js?id=20854"></script>

</body></html>
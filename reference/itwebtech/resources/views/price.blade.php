@extends('layout')

@forelse ($sitemaps as $sitemap)
	@if ($sitemap['slug'] == 'price')
		@section('title', $sitemap['title'] )   
		@section('meta_description', $sitemap['description'] )
	@endif
@empty
@endforelse

@section('CSS_links')
    {{-- <link rel="stylesheet" href={{ asset('css/home.css').'?'.env('APP_VERSION')}} type="text/css"> --}}
@endsection

@section('content')


<nav class="bread-crumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="bread-crumbs-list">
                    <li>
                        <a href="{{url('/')}}">@lang('layout.menu.home')</a>
                        <i class="material-icons md-18">chevron_right</i>
                    </li>
                    <li>@lang('layout.menu.price')</li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="banner lazy section" data-background-image="{{ asset('/assets/img/header/banner-center-price.gif') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="section-heading shm-none heading-center">
                        {{-- <div class="section-subheading"></div> --}}
                        <h1><span class="text-color-extra">@lang('price.pricelist')</span></h1>
                        {{-- <p class="section-desc">@lang('price.header_paragraph')</p> --}}
                        <blockquote><strong>@lang('price.blockquote')</strong></blockquote>
                    </div>
                    <footer class="section-footer col-12 section-footer-animate">
                        <div class="btn-group align-items-center justify-content-center">
{{--                             <a href="{{ url('contact') }}" class="btn btn-with-icon btn-w240 ripple">
                                <span>@lang('price.cta_calculation')</span>
                                <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
                            </a> --}}
                            <!-- inserted reservanto code -->
                            <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                            <!-- end of inserted reservanto code -->
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- packages of websites --}}
<div class="section section-bgc section-animate-items" data-body-columns="4">
    <div class="container">
        <div class="row items">
            <header class="col-12">
                <div class="section-heading heading-center section-heading-animate">
                    <div class="section-subheading">@lang('price.package.groupe_name')</div>
                    <h2>@lang('price.package.groupe_heading')</h2>
                </div>
            </header>
            <div class="item col-md-6 col-xl-3 pricing-col-4 animate-item">
                <div>
                    <div class="pricing-item-icon">
                        <i class="material-icons material-icons-outlined md-48">person</i>
                    </div>
                    <div class="pricing-item item-style pricing-item-i">
                        <header class="pricing-item-header">
                            <div class="pricing-item-heading">@lang('price.package.1.name')</div>
                            <div class="pricing-item-price pricing-item-price-first">@lang('price.package.1.cost')</div>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">person</i>
                                        <p><strong>@lang('price.package.1.item_1')</strong></p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.package.1.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.package.1.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.package.1.item_4')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.package.1.item_5')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.package.1.item_6')</p>
                                    </li>
                                </ul>
                            </div>
                            <footer class="pricing-item-footer">
                                <a href="{{ url('/contact?option=package-basic') }}" class="btn btn-with-icon btn-wide ripple">
                                    <span>@lang('layout.demand')</span>
                                    <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                                </a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item col-md-6 col-xl-3 pricing-col-4 animate-item">
                <div>
                    <div class="pricing-item-icon">
                        <i class="material-icons material-icons-outlined md-48">person_add</i>
                    </div>
                    <div class="pricing-item item-style pricing-item-i">
                        <div class="pricing-item-badge">@lang('price.popular')</div>
                        <header class="pricing-item-header">
                            <div class="pricing-item-heading">@lang('price.package.2.name')</div>
                            <div class="pricing-item-price pricing-item-price-first">@lang('price.package.2.cost')</div>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">person_add</i>
                                        <p><strong>@lang('price.package.2.item_1')</strong></p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.2.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.2.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.2.item_4')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.2.item_5')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.2.item_6')</p>
                                    </li>
                                </ul>
                            </div>
                            <footer class="pricing-item-footer">
                                <a href="{{ url('/contact?option=package-standard') }}" class="btn btn-with-icon btn-wide ripple">
                                    <span>@lang('layout.demand')</span>
                                    <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                                </a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item col-md-6 col-xl-3 pricing-col-4 animate-item">
                <div>
                    <div class="pricing-item-icon">
                        <i class="material-icons material-icons-outlined md-48">data_exploration</i>
                    </div>
                    <div class="pricing-item item-style pricing-item-i">
                        {{-- <div class="pricing-item-badge">@lang('price.bestseller')</div> --}}
                        <header class="pricing-item-header">
                            <div class="pricing-item-heading">@lang('price.package.3.name')</div>
                            <div class="pricing-item-price pricing-item-price-first">@lang('price.package.3.cost')</div>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">data_exploration</i>
                                        <p><strong>@lang('price.package.3.item_1')</strong></p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.3.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.3.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.3.item_4')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.3.item_5')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.3.item_6')</p>
                                    </li>
                                </ul>
                            </div>
                            <footer class="pricing-item-footer">
                                <a href="{{ url('/contact?option=package-profesional') }}" class="btn btn-with-icon btn-wide ripple">
                                    <span>@lang('layout.demand')</span>
                                    <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                                </a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item col-md-6 col-xl-3 pricing-col-4 animate-item">
                <div>
                    <div class="pricing-item-icon">
                        <i class="material-icons material-icons-outlined md-48">diamond</i>
                    </div>
                    <div class="pricing-item item-style pricing-item-i">
                        {{-- <div class="pricing-item-badge">@lang('price.vip')</div> --}}
                        <header class="pricing-item-header">
                            <div class="pricing-item-heading">@lang('price.package.4.name')</div>
                            <div class="pricing-item-price pricing-item-price-first">@lang('price.package.4.cost')</div>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">diamond</i>
                                        <p><strong>@lang('price.package.4.item_1')</strong></p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.4.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.4.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.4.item_4')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.4.item_5')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">add</i>
                                        <p>@lang('price.package.4.item_6')</p>
                                    </li>
                                </ul>
                            </div>
                            <footer class="pricing-item-footer">
                                <a href="{{ url('/contact?option=package-premium') }}" class="btn btn-with-icon btn-wide ripple">
                                    <span>@lang('layout.demand')</span>
                                    <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                                </a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
            <blockquote><strong>@lang('price.package.maintenance_free')</strong></blockquote>
        </div>
    </div>
</div>

{{-- long-term cooperation --}}
{{-- <div class="section section-44562 section-animate-items" data-body-columns="3" id="section-44562">
    <div class="container">
        <div class="row items">
            <header class="col-12">
                <div class="section-heading heading-center section-heading-animate">
                    <div class="section-subheading">@lang('price.longer.groupe_name')</div>
                    <h2>@lang('price.longer.groupe_heading')</h2>
                </div>
            </header>
            <div class="pricing-toggle litem animate-item">
                <label class="toggle">
                    <input type="checkbox" name="PricingToggle">
                    <span class="toggle-text toggle-text-before">@lang('price.monthly')</span>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text toggle-text-after">@lang('price.annual')<span class="badge badge-danger">@lang('price.month_gratis')</span></span>
                </label>
            </div>
            <div class="item col-md-6 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.longer.1.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.longer.1.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.longer.1.cost_month')</div>
                                <div class="pricing-item-price pricing-item-price-second none">@lang('price.longer.1.cost_year')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.1.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.1.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.1.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.1.item_4')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.longer.1.item_5')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=multi-seo') }}" class="btn btn-with-icon btn-wide ripple">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>
            <div class="item col-md-6 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.longer.2.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.longer.2.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.longer.2.cost_month')</div>
                                <div class="pricing-item-price pricing-item-price-second none">@lang('price.longer.2.cost_year')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.2.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.2.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.2.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.longer.2.item_4')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.longer.2.item_5')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=multi-social_media') }}" class="btn btn-with-icon btn-wide ripple btn-border">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>

        </div>
    </div>
</div> --}}

{{-- all servicess --}}
{{-- <div class="section section-bgc section-44561 section-animate-items" data-body-columns="3" id="section-44561">
    <div class="container">
        <div class="row items">
            <header class="col-12">
                <div class="section-heading heading-center section-heading-animate">
                    <div class="section-subheading">@lang('price.one_off.groupe_name')</div>
                    <h2>@lang('price.one_off.groupe_heading')</h2>
                </div>
            </header>
            <div class="item col-md-6 col-xl-4 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.one_off.1.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.one_off.1.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.one_off.1.cost')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.1.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.1.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.1.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.1.item_4')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.1.item_5')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.one_off.1.item_6')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=single-web') }}" class="btn btn-with-icon btn-wide ripple btn-border">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>
            <div class="item col-md-6 col-xl-4 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.one_off.2.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.one_off.2.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.one_off.2.cost')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.2.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.2.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.2.item_3')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.2.item_4')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.one_off.2.item_5')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=single-app') }}" class="btn btn-with-icon btn-wide ripple">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>
            <div class="item col-xl-4 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.one_off.3.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.one_off.3.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.one_off.3.cost')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.3.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.3.item_2')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.one_off.3.item_3')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=single-content') }}" class="btn btn-with-icon btn-wide ripple btn-border">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>
            <div class="item col-xl-4 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.one_off.4.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.one_off.4.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.one_off.4.cost')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.4.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.4.item_2')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.4.item_3')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.one_off.4.item_4')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=single-design') }}" class="btn btn-with-icon btn-wide ripple">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>
            <div class="item col-xl-4 pricing-col animate-item">
                <div class="pricing-h">
                    <div class="pricing-item item-style pricing-item2">
                        <div class="pricing-item-badge">@lang('price.one_off.5.name')</div>
                        <header class="pricing-item-header">
                            <div class="pih-center">
                                <div class="pricing-item-heading">@lang('price.one_off.5.short_name')</div>
                                <div class="pricing-item-price pricing-item-price-first">@lang('price.one_off.5.cost')</div>
                            </div>
                            <svg width="0" height="0" viewBox="0 0 346 169" xmlns="http://www.w3.org/2000/svg"><path d="M0.522461 10.3056C0.522461 4.7828 4.99961 0.305664 10.5225 0.305664H335.178C340.7 0.305664 345.178 4.78282 345.178 10.3057V168.992C345.178 168.992 286.005 164.814 230.751 140.126C175.497 115.438 143.823 108.385 103.784 108.385C63.7451 108.385 0.522461 151.883 0.522461 151.883V10.3056Z" /></svg>
                        </header>
                        <div class="pricing-item-h">
                            <div class="pricing-item-content">
                                <ul class="pricing-item-list">
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.5.item_1')</p>
                                    </li>
                                    <li class="active">
                                        <i class="material-icons md-24">check</i>
                                        <p>@lang('price.one_off.5.item_2')</p>
                                    </li>
                                    <li>
                                        <i class="material-icons md-24">star</i>
                                        <p>@lang('price.one_off.5.item_3')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <footer class="pricing-item-footer pif-abs">
                        <a href="{{ url('/contact?option=single-eshop') }}" class="btn btn-with-icon btn-wide ripple btn-border">
                            <span>@lang('layout.demand')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                        </a>
                    </footer>
                </div>
            </div>

        </div>
    </div>
</div> --}}

{{-- testimonials --}}
<div class="section section-animate-items" data-body-columns="3">
    <div class="container">
        <div class="row items">
            <div class="item col-md-6 col-lg-4 animate-item">
                <div class="reviews-item item-style reviews-item-vertical">
                    <div class="reviews-item-header">
                        <div class="reviews-item-img">
                            <img data-src="assets/img/testimonials/Pavel_Baudys.jpg"
                                class="lazy" width="75" height="75"
                                src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                alt="obrázek nebo foto zákazníka itwebtech Pavla Baudyše">
                        </div>
                        <div class="reviews-item-info">
                            <div class="gold_font">
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                            </div>
                            <h3 class="reviews-item-name item-heading">Pavel Baudyš</h3>
                            <div class="reviews-item-position">Toyota management</div>
                        </div>
                    </div>
                    <div class="reviews-item-text">
                        <p>
                            Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se, což je viditelné na jeho výsledcích. To je dle mého názoru základním předpokladem pro nejen uspokojení rozdílných potřeb jednotlivých zákazníků, ale i pro překonání jejich očekávání.
                        </p>
                        <a href="https://g.co/kgs/6rn2eF" target="_blank">celá recenze je na Googlu</a>
                    </div>
                </div>
            </div>
            <div class="item col-md-6 col-lg-4 animate-item">
                <div class="reviews-item item-style reviews-item-vertical">
                    <div class="reviews-item-header">
                        <div class="reviews-item-img">
                            <img data-src="assets/img/testimonials/Ivo_Stepanek.jpg"
                                class="lazy" width="75" height="75"
                                src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                alt="obrázek nebo foto zákazníka itwebtech Ivo Štěpánka">
                        </div>
                        <div class="reviews-item-info">
                            <div class="gold_font">
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                            </div>
                            <h3 class="reviews-item-name item-heading">Ing. Ivo Štěpánek</h3>
                            <div class="reviews-item-position">
                                podnikatel
                            </div>
                        </div>
                    </div>
                    <div class="reviews-item-text">
                        <p>
                            Služby pana Ondřeje Krišky vřele doporučuji.<br>
                            Jedná rychle a efektivně, což já jsem ve svém podnikání velmi uvítal, jakož i jeho výhodné ceny. 
                            Byl to pro mě velký rozdíl mezi předchozím IT dodavatelem těchto služeb.<br>
                            Je dobře, že v této zemi máme i takové odborníky.<br>
                            Děkuji za dosavadní spolupráci.
                        </p>
                        <a href="https://g.co/kgs/4xgvgW" target="_blank">recenze je na Googlu</a>
                    </div>
                </div>
            </div>
            <div class="item col-md-6 col-lg-4 animate-item">
                <div class="reviews-item item-style reviews-item-vertical">
                    <div class="reviews-item-header">
                        <div class="reviews-item-img">
                            <img data-src="assets/img/testimonials/Lukas-Srnak.jpg"
                                class="lazy" width="75" height="75"
                                src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                alt="obrázek nebo foto zákazníka itwebtech Lukáše Srnáka">
                        </div>
                        <div class="reviews-item-info">
                            <div class="gold_font">
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                                <i class="material-icons md-28">star</i>
                            </div>
                            <h3 class="reviews-item-name item-heading">Lukas Srnák</h3>
                            <div class="reviews-item-position">podnikatel</div>
                        </div>
                    </div>
                    <div class="reviews-item-text">
                        <p>
                            Rychlost<br> Ochota<br> Cena<br>
                            Naprosto perfektní přístup a jednání… <br>
                            Mohu vřele doporučit..!
                        </p>
                        <a href="https://goo.gl/maps/woUXU42hzWadG8JEA" target="_blank">recenze je na Googlu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="section section-animate-items">
    <div class="container">
        <div class="row items">
            <header class="col-12">
                <div class="section-heading heading-center section-heading-animate">
                        @lang('price.pricelist_info')
                </div>
            </header>
            <div class="col-12 item content animate-item">
                <p style="text-align: center;">
                    <strong>
                        @lang('price.sales_info')
                    </strong>
                </p>
            </div>
            <footer class="section-footer col-12 item section-footer-animate">
                <div class="btn-group align-items-center justify-content-center">
                    <!-- inserted reservanto code -->
                    <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                    <!-- end of inserted reservanto code -->
                    <a href="{{ url('/contact') }}" class="btn btn-with-icon ripple btn-border">
                        <span>@lang('layout.menu.contact')</span>
                        <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                    </a>
                </div>
            </footer>
        </div>
    </div>
</div> --}}

@endsection
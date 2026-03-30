@extends('layout')

@forelse ($sitemaps as $sitemap)
	@if ($sitemap['slug'] == 'projects')
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
        <ul class="list-style-none bread-crumbs-list">
            <li>
                <a href="{{ url('/') }}">@lang('layout.menu.home')</a>
                <i class="material-icons md-18">chevron_right</i>
            </li>
            <li>@lang('layout.menu.projects')</li>
        </ul>
    </div>
</nav>

<div class="banner lazy section" data-background-image="{{ asset('/assets/img/header/banner-center-projects.gif') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="section-heading shm-none heading-center">
                        {{-- <div class="section-subheading">@lang('project.projects_subtitle')</div> --}}
                        <h1><span class="text-color-extra">@lang('project.projects')</span></h1>
                        <p class="section-desc"><strong>@lang('project.header_paragraph')</strong></p>
                    </div>
{{--                     <footer class="section-footer col-12 section-footer-animate">
                        <div class="btn-group align-items-center justify-content-center">
                            <a href="{{ url('contact') }}" class="btn btn-with-icon btn-w240 ripple">
                                <span>@lang('price.cta_calculation')</span>
                                <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
                            </a>
                            <!-- inserted reservanto code -->
                            <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                            <!-- end of inserted reservanto code -->
                        </div>
                    </footer> --}}
                </div>
            </div>
        </div>
    </div>
</div>



			{{-- HOW I DO OI --}}
			<section class="section section-bgc section-animate-items" data-body-columns="3">
				<div class="container">
					<div class="row litems">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('project.why_me.subheading')</div>
								<h2>@lang('project.why_me.heading')</h2>
							</div> 
						</header>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">01</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.expert.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.expert.description')</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">02</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.stability.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.stability.description')</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">03</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.tester.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.tester.description')</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">04</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.designer.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.designer.description')</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">05</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.customizer.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.customizer.description')</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 litem animate-item">
							<div class="ini">
								<div class="ini-count">06</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('project.why_me.detailer.title')</h3>
									<div class="ini-desc">
										<p>@lang('project.why_me.detailer.description')</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>



<div class="section section-animate-items" data-body-columns="3">
    <div class="container">
        <div class="row items">
{{--             <header class="col-12">
                <div class="section-heading heading-center section-heading-animate">
                    <div class="section-subheading">@lang('project.projects_subtitle')</div>
                    <h1>@lang('project.projects')</h1>
                </div>
            </header> --}}
            <div class="col-12 item">
                <div class="section-nav">
                    <ul class="section-nav-list pitem-nav-list animate-item">
                        <li class="hover-link active" data-filter="*" data-title="@lang('project.pitem-all')"><span>@lang('project.pitem-all')</span></li>
                        <li class="hover-link" data-filter=".pitem-web-site" data-title="@lang('project.pitem-web-site')"><span>@lang('project.pitem-web-site')</span></li>
                        <li class="hover-link" data-filter=".pitem-web-app" data-title="@lang('project.pitem-web-app')"><span>@lang('project.pitem-web-app')</span></li>
                        <li class="hover-link" data-filter=".pitem-other" data-title="@lang('project.pitem-other')"><span>@lang('project.pitem-other')</span></li>
                    </ul>
                </div>
                <div class="row items pitems">
                    @forelse ($projects as $project)
                    
                    <div class="col-lg-4 col-sm-6 col-12 item pitem-col {{ $project->kind }} ">
                        <div class="pitem pitem-full item-style">
                            <a href="{{ asset('projects/'.$project->url) }}" class="pitem-img el">
                                <img data-src="{{ asset($project->img) }}"
                                    class="lazy img-cover el-absolute"
                                    src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                    alt="" />
                            </a>
                            <div class="pitem-info">
                                <h2 class="item-heading">
                                    <a href="{{ asset('projects/'.$project->url) }}">{{ $project->name }}</a>
                                </h2>
                                <div class="pitem-desc">
                                    <p>{!! $project->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('components.cta01')
@endsection
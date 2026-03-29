@extends('layout')

@forelse ($sitemaps as $sitemap)
	@if ($sitemap['slug'] == '')
		@section('title', $sitemap['title'] )   
		@section('meta_description', $sitemap['description'] )
	@endif
@empty
@endforelse

@section('CSS_links')
    {{-- <link rel="stylesheet" href={{ asset('css/home.css').'?'.env('APP_VERSION')}} type="text/css"> --}}
@endsection

@section('content')


			<div class="intro">
				<div class="intro-slider">
					<div class="intro-item intro-item-type-2">
						<div class="intro-item-img-right" style="background-image: url('assets/img/header/itwebtech_3.webp');"></div>
						{{-- <div class="intro-item-img-right">
							<video autoplay muted loop src="{{ asset('/assets/vid/banner_06.mp4') }}">
							</video>
						</div> --}}
						<div class="container">
							<div class="row">
								<div class="col">
									<div class="intro-box">
										<div class="section-heading shm-none">
											<div class="section-subheading"><strong>@lang('home.main_subheading')</strong></div>
											<h1>@lang('home.h1')</h1>
											<p class="section-desc">
												<strong>
													@foreach (__('home.bio') as $item)
														{!! $item !!}
														<br>
													@endforeach
												</strong>
                                            </p>
										</div>
										<div class="btn-group intro-btns">
											<div class="intro-play-btn play-video play-video-static el-ripple" data-src="https://youtu.be/cn4LP6jdN7k">
												<i class="material-icons material-icons-outlined">play_arrow</i>
											</div>
											<!-- inserted reservanto code -->
											<div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
											<!-- end of inserted reservanto code -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- CLIENTS --}}
			<section class="section section-animate-items" data-body-columns="5">
				<div class="container">
					<div class="row items">
						<div class="col-12 item">
							{{-- <div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.brands_subtitle')</div>
							</div> --}}
							<div class="row">
								<div class="flickity-carusel-init brands-carusel" data-flickity="{ &quot;imagesLoaded&quot;: true, &quot;lazyLoad&quot;: true, &quot;autoPlay&quot;: 7000, &quot;groupCells&quot;: true, &quot;contain&quot;: true, &quot;pageDots&quot;: true, &quot;prevNextButtons&quot;: false }">
{{-- 									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-2 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/upstyle.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="spolupráce s upstyle.cz" />
										</div>
									</div> --}}
{{-- 									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-5 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/elektro-srnak.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="logo elektro-srnak od itwebtech" />
										</div>
									</div> --}}
									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-2 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/toyota.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="pracoval jsem pro Toyotu" />
										</div>
									</div>
									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-2 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/yolk_studio.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="yolk studio se kterým spolupracuji" />
										</div>
									</div>
{{-- 									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-5 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/realitackyvakci.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="logo realiťačky v akci od itwebtech" />
										</div>
									</div> --}}
{{-- 									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-5 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/strechyzajic.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="logo strechy zajic od itwebtech" />
										</div>
									</div> --}}
{{-- 									<div class="carusel-col-min carusel-col-mobile-2 carusel-col-5 animate-item">
										<div class="brands-min">
											<img data-flickity-lazyload-src="assets/img/brands/pitarena.png"
												width="1" height="1"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="logo pitarena od itwebtech" />
										</div>
									</div> --}}

								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			{{-- SERVICES --}}
			<section class="section section-animate-items" data-body-columns="3">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.services_subtitle')</div>
								<h2>@lang('home.services')</h2>
							</div> 
						</div>
						<div class="col-lg-4 col-md-6 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">important_devices</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">important_devices</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_a.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_a.description')
                                </div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">settings_applications</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">settings_applications</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_b.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_b.description')
                                </div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">shopping_cart</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">shopping_cart</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_c.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_c.description')
                                </div>
							</div>
						</div>
						
{{-- 						<div class="col-12 white-space-top-5">
							<div class="section-heading heading-center section-heading-animate">
								<h2>@lang('home.other_services')</h2>
							</div> 
						</div> --}} 

					{{-- 	<div class="col-lg-4 col-md-6 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">query_stats</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">query_stats</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_d.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_d.description')
                                </div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">design_services</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">design_services</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_e.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_e.description')
                                </div>
							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-12 item animate-item">
							<div class="iitem iitem-modern item-style">
								<div class="iitem-icon">
									<i class="material-icons material-icons-outlined md-48">thumb_up</i>
								</div>
								<div class="iitem-icon-bg">
									<i class="material-icons material-icons-outlined">thumb_up</i>
								</div>
								<h3 class="iitem-heading item-heading-large">@lang('home.service_f.title')</h3>
								<div class="iitem-desc text-to-left">
                                    @lang('home.service_f.description')
                                </div>
							</div>
						</div> --}}

						<footer class="col-12 section-footer section-footer-animate">
							<div class="btn-group align-items-center justify-content-center">
								<!-- inserted reservanto code -->
								<div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
								<!-- end of inserted reservanto code -->
{{-- 								<a href="{{ url('price') }}" class="btn btn-with-icon btn-w240 ripple">
									<span>@lang('home.what_about_price')</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
								</a> --}}
							</div>
						</footer>
						<video autoplay muted loop src="{{ asset('/assets/vid/success_2.mp4') }}">
						</video>
					</div>
				</div>
			</section>

			{{-- ABOUT --}}
			<div class="section section-bgc">
				<div class="container">
					<div class="row">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading"></div>
								<h2>@lang('home.about_title')</h2>
								{{-- <p class="section-desc">@lang('home.about_desc')</p> --}}
							</div>
						</header>
					</div>
					<div class="about-container">
						<div class="row align-items-center items animate-about">
							<div class="item col-lg-6 col-xxl-5 order-2 order-lg-1">
								<div class="content content-margin-min about-content about-item">
									<h3>@lang('home.about_content_title')</h3>
									<div class="content content-margin-min item">
										<ul>
											@forelse (__('home.about_content') as $key => $value)
												<li>{{ $value }}</li>
											@empty
												<li>Důkladně s vámi projdu váš byznys a definujeme si očekávané přínosy naší spolupráce.</li>
												<li>Zjistím co vaši potenciální klienti hledají online.</li>
												<li>Navrhnu řešení, které povede ke splnění vašich obchodních cílů.</li>
											@endforelse
										</ul>
									</div>
								</div>

								<div class="col-xl-5 offset-xl-2 col-lg-6 offset-lg-1 col-12" {{-- style="--el-border-radius: 250px;" --}}>
									<div class="main-counter animate-item" style="opacity: 1; transform: translate(0px, 0px);">
										<div class="main-counter-item">
											<div class="main-counter-item-center">
												<div>
													<div class="main-counter-numb">
														<span class="spincrement" data-from="0" data-to="10" style="opacity: 1;">{{ now()->format('Y') - 2005 }}</span>
													</div>
													<div class="main-counter-heading">@lang('home.about_experienced_years')</div>
												</div>
											</div>
											<div class="main-counter-item-circ"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="item col-lg-6 col-xxl-5 order-1 order-lg-2 offset-xxl-1">
								<div class="about-imgs about-imgs-3">
									<div class="about-media-item img-style el" data-src="assets/img/webapp.jpg">
										<img data-src="assets/img/webapp_preview.jpg"
											class="lazy img-cover el-absolute"
											src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
											alt="full stack developer" />
									</div>
									<div class="about-media-item img-style el" data-src="assets/img/ondrej_kriska_profilova_fotografie.webp">
										<img data-src="assets/img/ondrej_kriska_profilova_fotografie_preview.webp"
											class="lazy img-cover el-absolute"
											src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
											alt="Ondřej Kriška profilova fotka" />
									</div>
									<div class="about-media-item img-style el el-5x4" data-src="https://youtu.be/78iiJ9wt8Qo?si=wUjBqpdl6ex0ccNV">
										<img data-src="assets/img/chci_zaujmout.jpg"
											class="lazy img-cover el-absolute"
											src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
											alt="Ondřej Kriška - ukázkové video - itwebtech" />
										<div class="play-video el-ripple"><i class="material-icons material-icons-outlined">play_arrow</i></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="section section-bgc">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div>
								<div class="section-heading shm-none heading-center">
									<h4 class="">@lang('home.about_desc')</h4>
								</div>
								<footer class="section-footer col-12 section-footer-animate">
									<div class="btn-group align-items-center justify-content-center">
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

			{{-- ADVANTAGES --}}
			<div class="section section-animate-items" data-body-columns="2">
				<div class="container">
					<div class="row litems">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.advantages.subheading')</div>
								<h2>@lang('home.advantages.heading')</h2>
							</div>
						</header>
						<div class="col-md-6 col-12 litem animate-item">
							<div class="ini ini-bg ini-wide">
								<div class="ini-count-large">01</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('home.advantages.adv01.h')</h3>
									<div class="ini-desc">
										<p>
											@lang('home.advantages.adv01.p')
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-12 litem animate-item">
							<div class="ini ini-bg ini-wide">
								<div class="ini-count-large">02</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('home.advantages.adv02.h')</h3>
									<div class="ini-desc">
										<p>
											@lang('home.advantages.adv02.p')
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-12 litem animate-item">
							<div class="ini ini-bg ini-wide">
								<div class="ini-count-large">03</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('home.advantages.adv03.h')</h3>
									<div class="ini-desc">
										<p>
											@lang('home.advantages.adv03.p')
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-12 litem animate-item">
							<div class="ini ini-bg ini-wide">
								<div class="ini-count-large">04</div>
								<div class="ini-info">
									<h3 class="ini-heading item-heading-large">@lang('home.advantages.adv04.h')</h3>
									<div class="ini-desc">
										<p>
											@lang('home.advantages.adv04.p')
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- WHAT CLIENTS SAY --}}
			<div class="section section-bgc section-animate-items" data-body-columns="3">
				<div class="container">
					<div class="row items">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<h2>@lang('home.testimonials')</h2>
								<strong class="section-subheading">Hodnocení 5 z 5 (celkem 21)</strong>
							</div>
						</header>
						<div class="col-12 item">
							<div class="flickity-carusel-init reviews-carusel" data-flickity="{ &quot;autoPlay&quot;: 10000, &quot;groupCells&quot;: true, &quot;contain&quot;: true, &quot;prevNextButtons&quot;: false }">

								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/makoplast.png') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech MAKOplast s.r.o.">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Radka Láníková Ouředníková</h3>
												<div class="reviews-item-position">
													<strong>MAKOplast s.r.o.</strong><br>
													jednatelka
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Skvělá spolupráce, profesionální přístup, web hotový včas a podle představ.
											</p>
											<p>
												<strong>Doporučuji pana Krišku všem, kdo chtějí kvalitní webové stránky.</strong>
											</p>
											<a href="https://www.firmy.cz/detail/13470851-ondrej-kriska-itwebtech-brezi.html#hodnoceni" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/firmy_cz.svg') }}" alt="firmy cz logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/ales_horky.png') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Aleš Horký">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Aleš Horký</h3>
												<div class="reviews-item-position">
													<strong>ExHot</strong><br>
													výrobky z nerezu
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Ondřeje Krišku bych rozhodně doporučil pro jeho vynalézavý a neotřelý  styl práce, jdoucí ruku v ruce s flexibilním a profesionálním přístupem k zákazníkovi.
											</p>
											<p>
												<strong>Ondřeje Krišku bych rozhodně doporučil</strong>
											</p>
											<a href="https://maps.app.goo.gl/qyVP3SVKMoBhNLT67" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google recenze logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/michal_cvrcek.jpg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Michal Cvrček">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Michal Cvrček</h3>
												<div class="reviews-item-position">
													<strong>Cyklocentrum Březí</strong><br>
													spolumajitel
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Perfektní spolupráce. Výborné nápady a přístup. Rychlost, vstřícnost, ochota, pofesionalita......
											</p>
											<p>
												<strong>Vřele doporučuji.</strong>
											</p>
											<a href="https://maps.app.goo.gl/kHYPxVwu34jfXyai6" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google recenze logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/adela_polaskova.jpg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Adála Polášková">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Adéla Polášková</h3>
												<div class="reviews-item-position">
													<strong>Mušov21 restaurace</strong><br>
													spolumajitelka
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Děkuji Ondrovi za skvělou spolupráci v rámci zpracování loga tak, aby mohlo být použito na firemní textil. Vše proběhlo rychle, precizně a během několika hodin.
											</p>
											<p>
												<strong>Určitě doporučuji 👍</strong>
											</p>
											<a href="https://www.firmy.cz/detail/13470851-ondrej-kriska-itwebtech-brezi.html#hodnoceni" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/firmy_cz.svg') }}" alt="firmy cz logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/jana_vesela.jpg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Jana Veselá">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Jana Veselá</h3>
												<div class="reviews-item-position">
													<strong>Kemp Veselka</strong><br>
													provozovatelka kempu
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												100% spokojenost s vytvořením našich webových stránek. Krásný a funkční web za minimální náklady.
											</p>
											<p>
												<strong>Můžeme vřele doporučit.</strong>
											</p>
											<a href="https://www.facebook.com/share/p/1DfE2GejvK/" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/fb.png') }}" alt="fb logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/peter_vidlicka.jpeg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Peter Vidlička">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Peter Vidlička</h3>
												<div class="reviews-item-position">
													<strong>Yolk studio</strong><br>
													co-founder
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Ondra je velice spolehlivy a sikovny vyvojar s kterym nam vzdy hodne dobre spolupracovalo.
											</p>
											<a href="https://maps.app.goo.gl/nXuNG6TdNJSJs64f6" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/magda_pernicova.jpeg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Magda Pernicová Novotná">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Magda Pernicová Novotná</h3>
												<div class="reviews-item-position">
													<strong>Realiťačky v akci</strong><br>
													Makléřka
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Profesionální, ale zároveň lidský a trpělivý přístup. 
												Pan Kriška opravdu naslouchal mým potřebám a následně tyto informace zpracoval 
												až do mé úplné spokojenosti.
											</p>
											<p>
												Zároveň nemá problém vložit do webových stránek i své zkušenosti a profi um, 
												a ulehčil mi tak mé laické hledání a rozhodování. 
												<br><strong>Vřele doporučuji.</strong>

											</p>
											<a href="https://www.firmy.cz/detail/13470851-ondrej-kriska-itwebtech-brezi.html#hodnoceni" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/firmy_cz.svg') }}" alt="Firmy.cz logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="{{ asset('/assets/img/testimonials/rostislav_toman.jpeg') }}"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Rostislav Toman">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Rostislav Toman</h3>
												<div class="reviews-item-position">
													<strong>Tradiční výroba pralinek, s.r.o.</strong><br>
													Manager
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Oceňuji vysokou odbornost a profesionalitu. Postupnými kroky jsme odladili očekávání, 
												realitu a na základě rad i optimalizaci toku dat, zobrazení i celou logiku reportu. 
												Zažil jsem předčená očekávání v praxi. 
											</p>
											<p>
												Z mé strany 
												<strong>jednoznačně doporučení na spolupráci a jasná volba v příštích projektech</strong>. 
												Určitě se ještě pracovně uvidíme.
											</p>
											<a href="https://maps.app.goo.gl/rgeMvjSTexrMaauX9" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Hana_Jaskmanicka.jpeg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Hana Jaskmanická">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Hana Jaskmanická</h3>
												<div class="reviews-item-position">
													<strong>VP INDUSTRY</strong><br>
													Výkonná ředitelka
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Chtěli jsme mít pro naši firmu kvalitní a odlišné webové stránky.<br>
												Díky individuálnímu přístupu, flexibilitě a profesionalitě odpovídá výsledek našim představám.<br>
											</p>
											<p>
												<strong>Vřele doporučuji</strong>, svým individuálním přístupem dokáže přesně odhadnout přání zákazníka.
											</p>
											<a href="https://maps.app.goo.gl/C6iGkdZu5rCtimqq6" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Ivo_Stepanek.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Ing. Ivo Štěpánek">
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
													<strong>J. K. fire and safety consulting</strong><br>
													Podnikatel v oblasti BOZP 
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Služby pana Ondřeje Krišky vřele doporučuji.<br>
												<strong>Jedná rychle a efektivně</strong>, což já jsem ve svém podnikání velmi uvítal, jakož i jeho výhodné ceny. 
												Byl to pro mě velký rozdíl mezi předchozím IT dodavatelem těchto služeb.<br>
												Je dobře, že v této zemi máme i takové odborníky.<br>
												Děkuji za dosavadní spolupráci.
											</p>
											<a href="https://g.co/kgs/4xgvgW" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Vaclav-Pesice.png"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Václava Pešice">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Václav Pešice</h3>
												<div class="reviews-item-position">
													<strong>Upstyle systems</strong><br>
													Software developer  
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												S Ondrou je skvělá spolupráce. Vždy se snaží udělat pro klienty maximum.<br>
												Dělal webové stránky pro mého klienta a za velmi nízkou cenu odvedl perfektní práci.<br>
												Často také spolupracujeme na vývoji různých webových aplikací 
												a vždy je radost s ním komunikovat o jejich vývoji a dalším rozvoji.<br>
												<strong>Rozhodně má moje doporučení.</strong>
											</p>
											<a href="https://g.co/kgs/CEs5uj" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Pavel_Baudys.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Pavla Baudyše">
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
												<div class="reviews-item-position">
													<strong>Toyota</strong><br>
													Ředitel řízení výroby, montáže a logistiky<br>
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												S potěšením mohu poskytnout tuto referenci pro Ondřeje Krišku, který pracoval v naší společnosti Toyota 18 let.
											</p>
											<p>
												Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se, což je viditelné na jeho výsledcích. To je dle mého názoru základním předpokladem pro nejen uspokojení rozdílných potřeb jednotlivých zákazníků, ale i pro <strong>překonání jejich očekávání</strong>.
											</p>
											<a href="https://g.co/kgs/6rn2eF" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Stanislav-Holcmann.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Stanislav Holcmann">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Stanislav Holcmann</h3>
												<div class="reviews-item-position">
													<strong>Pitbike Aréna</strong><br>
													Majitel PitArény  
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Tenhle web meister dělá stránky pro nás a můžu jenom vřele doporučit.
											</p>
											<p>
												<strong>Výborná komunikace,kvalitně odvedená práce,spoustu inovativních,praktických nápadů</strong>.
											</p>
											<a href="https://www.firmy.cz/detail/13470851-ondrej-kriska-itwebtech-brezi.html#hodnoceni" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/firmy_cz.svg') }}" alt="firmy cz logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Lukas-Srnak.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Lukas Srnak">
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
												<div class="reviews-item-position">
													<strong>Elektro Srnák</strong><br>
													Podnikatel v oblasti elektroinstalací a stavebních prací.
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Rychlost<br> Ochota<br> Cena<br>
												Naprosto perfektní přístup a jednání… <br>
												<strong>Mohu vřele doporučit..!</strong>
											</p>
											<a href="https://goo.gl/maps/woUXU42hzWadG8JEA" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Jaroslav-Zajic.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech Jaroslav Zajíc">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Jaroslav Zajíc</h3>
												<div class="reviews-item-position">
													<strong>Střechy Zajíc</strong><br>
													Zkušený profesionál a živnostník, který staví nejen střechy.
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												Webové stránky vypadají skvěle a jejich ovládání je intuitivní. 
												Díky proaktivnímu přístupu a odborným radám byl celý proces snadný. 
												Určitě se obrátím znovu, až bude potřeba další práce. <br>
												<strong>Doporučuji.</strong>
											</p>
											<a href="https://www.firmy.cz/detail/13470851-ondrej-kriska-itwebtech-brezi.html#hodnoceni" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/firmy_cz.svg') }}" alt="firmy cz logo"></a>
										</div>
									</div>
								</div>
								<div class="carusel-col carusel-col-3 animate-item">
									<div class="reviews-item item-style">
										<div class="reviews-item-header">
											<div class="reviews-item-img">
												<img data-src="assets/img/testimonials/Jan-Stybor.jpg"
													class="lazy" width="75" height="75"
													src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
													alt="obrázek nebo foto klienta itwebtech - Jan Stybor">
											</div>
											<div class="reviews-item-info">
												<div class="gold_font">
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
													<i class="material-icons md-28">star</i>
												</div>
												<h3 class="reviews-item-name item-heading">Jan Stybor</h3>
												<div class="reviews-item-position">
													<strong>Toyota</strong><br>
													Vedoucí projektového oddělení.
												</div>
											</div>
										</div>
										<div class="reviews-item-text">
											<p>
												<strong>Oceňuji profesionální přístup k práci.</strong><br>
												Pří vývoji aplikace důsledně  analyzuje  stav a  chce poznat současné procesy jak se provádí. 
												Následně shromažďuje požadavky od zákazníků a zjišťuje vize, které by v budoucnu chtěli do aplikace promítnout. 
												Připraví plán na základě kterého se zákazníkem dohodne na klíčových milnících. 
											</p>
											<a href="https://maps.app.goo.gl/qbnsv8T3qkuemtUGA" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials/google.png') }}" alt="google logo"></a>
										</div>
									</div>
								</div>


							</div>
						</div>
						<footer class="col-12 section-footer section-footer-animate">
							<div class="section-heading heading-center section-heading-animate">
								<h3>@lang('home.guarantee_heding')</h3>
								<blockquote>
									@lang('home.guarantee_block')
								</blockquote>
								<h4>Všechny mé recenze naleznete na <a href="https://ondraweb.cz/recenze" target="_blank">ondraweb.cz/recenze</a> </h4>								
							</div>
						</footer>
					</div>
				</div>
			</div>

			{{-- PROCESS STEPS--}}
			<div class="section section-animate-items" data-body-columns="4">
				<div class="container">
					<div class="row">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.steps.subheading')</div>
								<h2>@lang('home.steps.heading')</h2>
							</div>
						</header>
					</div>
					<div
						class="row justify-content-center process-steps-row row-cols-1 row-cols-sm-2 row-cols-xl-4 litems">
						<div class="col litem animate-item">
							<div class="process-item process-item-arrow">
								<div class="process-item-counter">1</div>
								<div class="process-item-details">
									<h3 class="process-item-heading item-heading-large">@lang('home.steps.step01.h')</h3>
									<p class="process-item-desc">@lang('home.steps.step01.p')</p>
                            <!-- inserted reservanto code -->
                            <div class="reservanto-button-margin-top reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                            <!-- end of inserted reservanto code -->
								</div>
							</div>
						</div>
						<div class="col litem animate-item">
							<div class="process-item process-item-arrow">
								<div class="process-item-counter">2</div>
								<div class="process-item-details">
									<h3 class="process-item-heading item-heading-large">@lang('home.steps.step02.h')</h3>
									<p class="process-item-desc">@lang('home.steps.step02.p')</p>
								</div>
							</div>
						</div>
						<div class="col litem animate-item">
							<div class="process-item process-item-arrow">
								<div class="process-item-counter">3</div>
								<div class="process-item-details">
									<h3 class="process-item-heading item-heading-large">@lang('home.steps.step03.h')</h3>
									<p class="process-item-desc">@lang('home.steps.step03.p')</p>
								</div>
							</div>
						</div>
						<div class="col litem animate-item">
							<div class="process-item process-item-arrow">
								<div class="process-item-counter">4</div>
								<div class="process-item-details">
									<h3 class="process-item-heading item-heading-large">@lang('home.steps.step04.h')</h3>
									<p class="process-item-desc">@lang('home.steps.step04.p')</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- RECENT PROJECTS --}}
			<section class="section section-bgc section-animate-items" data-body-columns="3">
				<div class="container">
					<div class="row">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.projects_subtitle')</div>
								<h2>@lang('home.projects_title')</h2>
							</div> 
						</header>
					</div>
					<div class="row pitems">
						@forelse ($projects as $project)
							

						<div class="col-lg-4 col-md-6 col-sm-6 col-12 item pitem-col {{ $project->kind }} ">
							<div class="pitem el pitem-flip">
								<div class="pitem-card pitem-card-front">
									<img data-src="{{ $project->img }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="ukázka práce itwebtech Ondřej Kriška">
								</div>
								<a href="{{ url('/projects').'/'.$project->url }}" class="pitem-card pitem-card-details pitem-card-back">
									<div class="pitem-card-center">
										<h3 class="pitem-heading">{{ $project->name }}</h3>
										<div class="pitem-desc">
											<p>{!! $project->description !!}</p>
										</div>
										<div class="pitem-btns wrapp-btn-circl-arrow justify-content-center">
											<div class="btn-circl-arrow btn-circl-arrow-white">
												<svg viewBox="0 0 13 9" width="13" height="9" width="13px" height="9px"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
						@empty
							
						@endforelse
					</div>
					<div class="row">
						<footer class="section-footer col-12 section-footer-animate">
							<div class="btn-group align-items-center justify-content-center">
								<a href="{{ asset('/projects') }}" class="btn btn-with-icon btn-w240 ripple">
									<span>@lang('home.all_projects_btn')</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
								</a>
							</div>
						</footer>
					</div>
				</div>
			</section>

			{{-- latest news --}}
			<section class="section section-animate-items" data-body-columns="3">
				<div class="container">
					<div class="row">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">Poslední články</div>
								<h2>Jak na lepší web</h2>
							</div> 
						</header>
						@forelse ($latest_news as $article)
							<div class="col-lg-4 col-md-6 col-12 item animate-item">
								<article class="news-item item-style">
									<a href="{{ url( 'jak-na-to/' . $article->slug) }}" class="news-item-img el">
										<img data-src="{{ asset('/assets/img/articles/' . $article->img_preview ) }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ $article->title }}">
									</a>
									<div class="news-item-info">
										<div class="news-item-date">{{ $article->created_at->format('d.m.Y') }}</div>
										<h3 class="news-item-heading item-heading">
											<a href="{{ url( 'jak-na-to/' . $article->slug) }}" title="{{ $article->title }}">{{ $article->title }}</a>
										</h3>
										<div class="news-item-desc">
											{!! $article->description !!}
										</div>
									</div>
								</article>
							</div>
							@empty
							
							@endforelse 
							<footer class="section-footer col-12 section-footer-animate">
								<div class="btn-group align-items-center justify-content-center">
									<a href="{{ url('jak-na-to') }}" class="btn btn-with-icon btn-w240 ripple">
										<span>Další články</span>
										<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
									</a>
								</div>
							</footer>
					</div>
				</div>
			</section>

			@includeIf('components.cta01')

		</div>

@endsection

			{{-- GOOD FOR WHO --}}
{{-- 			<div class="section">
				<div class="container">
					<div class="row">
						<header class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('home.yes-no.subheading')</div>
								<h2>@lang('home.yes-no.heading')</h2>
							</div>
						</header>
					</div>
					<div class="tabs">
						<ul class="tabs-nav justify-content-md-center">
							<li class="active">@lang('home.yes-no.suitable')</li>
							<li>@lang('home.yes-no.no_suitable')</li>
						</ul>
						<div class="tabs-container">
							<div class="tabs-item active">
								<div class="about-container">
									<div class="row align-items-center items animate-about">
										<div class="item col-lg-6 col-xxl-5 order-2 order-lg-1">
											<div class=" content-margin-min about-content about-item">
												<div class="section-subheading"></div>
												<div class="content-margin-min item">
													<ol>
														<li>
															@lang('home.yes-no.yes.0')
														</li>
														<li>
															@lang('home.yes-no.yes.1')
														</li>
														<li>
															@lang('home.yes-no.yes.2')
														</li>
														<li>
															@lang('home.yes-no.yes.3')
														</li>
														<li>
															@lang('home.yes-no.yes.4')
														</li>
													</ol>
												</div>
											</div>
											<div class="btn-group align-items-center about-item justify-content-center justify-content-lg-start">
												<a href="{{ url('/contact') }}" class="btn btn-with-icon ripple">
													<span>@lang('home.get_quotation')</span>
													<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
												</a>
											</div>
										</div>
										<div class="item col-lg-6 col-xxl-5 order-1 order-lg-2 offset-xxl-1">
											<div class="about-imgs about-imgs-1">
												<div class="about-media-item item-bordered item-border-radius el" data-src="assets/img/cooperation_high.jpg">
													<img data-src="assets/img/cooperation_high.jpg"
														class="lazy img-cover el-absolute"
														src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
														alt="vysoká míra pravděpodobnosti spolupráce"/>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tabs-item">
								<div class="about-container">
									<div class="row align-items-center items animate-about">
										<div class="item col-lg-6 col-xxl-5 order-2 order-lg-1">
											<div class="content-margin-min about-content about-item">
												<div class="section-subheading"></div>
												<div class="content-margin-min item">
													<ol>
														<li>
															@lang('home.yes-no.no.0')
														</li>
														<li>
															@lang('home.yes-no.no.1')
														</li>
													</ol>
												</div>
											</div>
											<div class="btn-group align-items-center about-item justify-content-center justify-content-lg-start">
												<a href="{{ url('/contact') }}" class="btn btn-with-icon ripple">
													<span>@lang('home.get_quotation')</span>
													<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
												</a>
											</div>
										</div>
										<div class="item col-lg-6 col-xxl-5 order-1 order-lg-2 offset-xxl-1">
											<div class="about-imgs about-imgs-1">
												<div class="about-media-item item-bordered item-border-radius el" data-src="assets/img/cooperation_low.jpg">
													<img data-src="assets/img/cooperation_low.jpg"
														class="lazy img-cover el-absolute"
														src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
														alt="nižší míra pravděpodobnosti spolupráce" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
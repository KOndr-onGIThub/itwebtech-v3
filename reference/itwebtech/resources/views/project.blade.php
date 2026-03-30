@extends('layout')

@section('title', $oneProject->name . ' | itwebtech')

@section('meta_description', $oneProject->description )
@section('og_img', asset($oneProject->og_img !== null ? $oneProject->og_img : $oneProject->img))
@section('og_url', route('project', $oneProject->url) )

@section('CSS_links')
    {{-- <link rel="stylesheet" href={{ asset('css/home.css').'?'.env('APP_VERSION')}} type="text/css"> --}}
@endsection



@section('content')

			<!-- Begin bread crumbs -->
			<nav class="bread-crumbs">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<ul class="bread-crumbs-list">
								<li>
									<a href="{{ url('/') }}">@lang('layout.menu.home')</a>
									<i class="material-icons md-18">chevron_right</i>
								</li>
								<li>
									<a href="{{ url('/projects') }}">@lang('layout.menu.projects')</a>
									<i class="material-icons md-18">chevron_right</i>
								</li>
								<li>{{ $oneProject->name }}</li>
							</ul>
						</div>
					</div>
				</div>
			</nav><!-- End bread crumbs -->

			<article class="section">
				<div class="container">
					<div class="row items">
						<div class="col-12">
							<div class="section-heading heading-center">
								<h1>{{ $oneProject->name }}</h1>
								<p class="section-desc">{!! $oneProject->description !!}</p>
                                <p class="section-desc">{!! $oneProject->content !!}</p>
							</div>
						</div>
						<div class="item col-12">
							<div class="row pitem-details pitem-details-cols row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 justify-content-center text-center">
								<div class="pitem-details-row col">
									<div class="pitem-details-label">@lang('project.info_client'):</div>
									<div class="pitem-details-desc">{{ $oneProject->customer }}</div>
								</div>
								<div class="pitem-details-row col">
									<div class="pitem-details-label">@lang('project.info_date'):</div>
									<div class="pitem-details-desc">{{ $oneProject->created_at->format('m / Y') }}</div>
								</div>
								<div class="pitem-details-row col">
									<div class="pitem-details-label">@lang('project.info_categories'):</div>
									<div class="pitem-details-desc">{{ __('project.'.$oneProject->kind) }}</div>
								</div>
								<div class="pitem-details-row col">
									<div class="pitem-details-label">@lang('project.info_price'):</div>
									<div class="pitem-details-desc">
                                        @if (session()->get('locale') == 'en')
                                        {{ $oneProject->price_eur }}
                                        @else
                                        {{ $oneProject->price_czk }}
                                        @endif
                                    </div>
								</div>
							</div>
						</div>
@if($oneProject->comparison)
                        <div class="col-12 litem">
                            <div class="compare-image-container el el-2x1">
                                <div class="compare-image img-style el el-2x1">
                                    <img data-src="{{ asset($oneProject->img_before) }}"
                                        class="lazy img-cover el-absolute"
                                        src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                        alt="obrázek před realizací" />
                                </div>
                                <div class="compare-image img-style el el-2x1 compare-image-overlay">
                                    <img data-src="{{ asset($oneProject->img_after) }}"
                                        class="lazy img-cover el-absolute"
                                        src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                        alt="obrázek po realizaci" />
                                </div>
                                <div class="compare-image-caption compare-image-caption-before">Předtím</div>
                                <div class="compare-image-caption compare-image-caption-after">Potom</div>
                                <input type="range" class="compare-image-range" min="0" max="100" value="50" />
                                <div class="compare-image-slider"></div>
                            </div>
                        </div>
@endif
					</div>
				</div>
			</article>

			<div class="section section-without-padding-top">
				<div class="container">
					<div class="about-containers">
@forelse ($projectScreens as $projectScreen)
                            

						<div class="about-container acpb">
							<div class="row align-items-center items animate-about">

                                @if($loop->iteration % 2 == 0)
								<div class="item col-lg-6 col-xxl-5 order-1 order-lg-1 content-col-reverse">
								@else
								<div class="item col-lg-6 col-xxl-5 order-1 order-lg-2 offset-xxl-1 content-col-reverse">
								@endif
									<div class="content content-margin-min about-content about-item">
										<h2>{{ $projectScreen->title }}</h2>
										<p>{!! $projectScreen->description !!}</p>
									</div>
								</div>
								@if($loop->iteration % 2 == 0)
								<div class="item col-lg-6 col-xxl-5 order-2 order-lg-2 offset-xxl-1">
								@else
								<div class="item col-lg-6 col-xxl-5 order-2 order-lg-1">
								@endif
									<div class="about-imgs about-img -1">
										<div class="about-media-item img-style el el-4x5"
											data-src="{{ asset( $projectScreen->is_video ? $projectScreen->video_url : $projectScreen->screen_shot) }}">
											<img data-src="{{ asset($projectScreen->screen_shot) }}"
												class="lazy img-cover el-absolute"
												src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
												alt="{{ $projectScreen->title }}" />
												@if($projectScreen->is_video)
													<div class="play-video el-ripple play-video-lg"><i class="material-icons material-icons-outlined">play_arrow</i></div>
												@endif
										</div>
									</div>
								</div>

							</div>
						</div>
@empty
                            
@endforelse

					</div>
			
				</div>
			</div>



@if ($oneProject->testimonial)
<div class="section section-animate-items" data-body-columns="1">
	<div class="container">
		<div class="row items">
			<header class="col-12">
				<div class="section-heading heading-center section-heading-animate">
					<h2>Jak je spokojen klient?</h2>
				</div>
			</header>
			<div class=" item animate-item">
				<div class="reviews-item item-style">
					<div class="reviews-item-header">
						<div class="reviews-item-img">
							<img data-src="{{ asset('/assets/img/testimonials') . '/' . $oneProject->client_photo }}"
								class="lazy" width="75" height="75"
								src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
								alt="foto recenzenta">
						</div>
						<div class="reviews-item-info">
							<div class="gold_font">
								<i class="material-icons md-28">star</i>
								<i class="material-icons md-28">star</i>
								<i class="material-icons md-28">star</i>
								<i class="material-icons md-28">star</i>
								<i class="material-icons md-28">star</i>
							</div>
							<h3 class="reviews-item-name item-heading">{{ $oneProject->client_name }}</h3>
							<div class="reviews-item-position">{{ $oneProject->client_role }}</div>
						</div>
					</div>
					<div class="reviews-item-text">
						<p>
							{!! $oneProject->client_says !!}
						</p>
						<a href="{{ $oneProject->testimonial_src }}" target="_blank">zdroj recenze <img src="{{ asset('/assets/img/testimonials') . '/' . $oneProject->testimonial_source_img }}" alt="zdroj recenze"></a>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endif



<div class="section">
	<div class="container">
		<div class="about-container">
			<div class="row align-items-center items animate-about">
{{-- 				<header class="col-12">
					<div class="section-heading section-heading-animate">
						<h2 class="extra balanced-text">xxx</h2>
					</div>
				</header> --}}
				<div class="item col-lg-6 col-xxl-5 order-2 order-lg-1">
					<div class="content content-margin-min about-content about-item">
						<h3 class="balanced-text">„@if($oneProject->cta){{ $oneProject->cta }}@else Chcete uspět v online byznyse? @endif“</h3>
						<p>Kontaktujte mě pro nezávaznou konzultaci.</p>
						
					</div>
					<footer class="section-footer about-btns-item section-footer-animate">
						<div class="btn-group align-items-center">
						<!-- inserted reservanto code -->
						<div class="reservanto-widget reservanto-button-margin-top" data-text="@lang('contact.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" ></div>
						<!-- end of inserted reservanto code -->
						</div>
					</footer>
				</div>
				<div class="item col-lg-6 col-xxl-5 order-1 order-lg-2 offset-xxl-1">
					<div class="about-imgs about-imgs-1">
						<div class="about-media-item {{-- item-bordered --}} item-border-radius el el-4x5"
							data-src="{{ asset('/assets/img/ok.webp') }}">
							<img data-src="{{ asset('/assets/img/ok.webp') }}"
								class="lazy img-cover el-absolute"
								src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
								alt="Ondřej Kriška | Ondraweb.cz | Profilová fotografie." />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section class="section section-animate-items">
	<div class="container">
		<div class="row">
			<footer class="section-footer col-12 section-footer-animate">
				<div class="btn-group align-items-center justify-content-center">
					<a href="{{ route('projects') }}" class="btn btn-with-icon btn-w240 ripple">
						<span>@lang('project.btn_all_projects')</span>
						<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
					</a>
				</div>
			</footer>
		</div>
	</div>
</section>

			@includeIf('components.cta01')
@endsection
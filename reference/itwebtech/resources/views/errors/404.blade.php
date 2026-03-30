@extends('layout')

@section('title', 'error 404')

@section('meta_description')
error 404 
@endsection

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
									<a href="{{url('/')}}">@lang('layout.menu.home')</a>
									<i class="material-icons md-18">chevron_right</i>
								</li>
								<li>404</li>
							</ul>
						</div>
					</div>
				</div>
			</nav><!-- End bread crumbs -->

			<div class="section">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="section-heading heading-center section-heading-animate">
								<div class="section-subheading">@lang('err.404.subheading')</div>
								<h1>@lang('err.404.heading')</h1>
								<p class="section-desc">@lang('err.404.p.0')</p>
								<p class="section-desc">@lang('err.404.p.1')</p>
							</div> 
							<div class="btn-group align-items-center justify-content-center">
								<a href="{{url('/')}}" class="btn btn-with-icon ripple">
									<span>@lang('err.404.go_home_btn')</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
										<use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use>
									</svg>
								</a>
								<a href="{{url('/contact')}}" class="btn btn-border btn-with-icon ripple">
									<span>@lang('err.404.contact_btn')</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
										<use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use>
									</svg>
								</a>
								<a href="{{url('/price')}}" class="btn btn-with-icon ripple">
									<span>@lang('err.404.services_btn')</span>
									<svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9">
										<use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use>
									</svg>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>




@endsection



			
	
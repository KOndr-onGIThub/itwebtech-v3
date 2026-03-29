@extends('layout')

@section('title')
	@lang('layout.demand_form.policy') - itwebtech
@endsection

@section('meta_description')
	@lang('layout.demand_form.policy') 
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
								<li>@lang('layout.demand_form.policy')</li>
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
								<h1>itwebtech - @lang('layout.demand_form.policy')</h1>
							</div>
							<div class="content">
								@lang('/privacypolicy.allinone')


							</div>
						</div>
					</div>
				</div>
			</div>





@endsection



			
	
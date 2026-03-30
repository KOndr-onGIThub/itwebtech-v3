@extends('layout')

@forelse ($sitemaps as $sitemap)
	@if ($sitemap['slug'] == 'contact')
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
						<li>@lang('layout.menu.contact')</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>

	<div class="section">
		<div class="container">
			<div class="row content-items">

				{{-- aler box pro info o uspesnem odeslani formulare --}}
				@if (session('status'))
				<div id="alert_message_sent" class="alert_wrap">
					<div class="alert alert_success">
						<div class="alert_message">
							{!! __(session('status')) !!}
						</div>
						<div class="header_modal_close popup_close" id="btn_close_alert">
							<i class="material-icons md-24">cancel</i>
						</div>
					</div>
				</div>
				@endif
				<div class="col-12">
					<div class="section-heading">
						<div class="section-subheading">@lang('contact.contact_subtitle')</div>
						<h1>@lang('contact.contact')</h1>

<!-- inserted reservanto code -->
<div class="reservanto-widget reservanto-button-margin-top" data-text="@lang('contact.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" ></div>
<!-- end of inserted reservanto code -->

				</div>
				</div>
				<div class="col-xl-4 col-md-5 content-item">
					<div class="contact-info section-bgc">
						<h3>@lang('contact.address')</h3>
						<ul class="contact-list">
							<li>
								<i class="material-icons material-icons-outlined md-22">location_on</i>
								<div class="footer-contact-info">
									<a href="https://goo.gl/maps/AF5mNKD3sq2uaC5p6" rel="noopener" target="_blank">Dunajovická 116,<br>Březí u Mikulova, 691 81</a>
								</div>
							</li>
							<li>
								<i class="material-icons material-icons-outlined md-22">mail_outline</i>
								<div class="footer-contact-info">
									<a href="mailto:ok@itwebtech.cz">ok@itwebtech.cz</a>
								</div>
							</li>
							<li>
								<i class="material-icons material-icons-outlined md-22">phone_iphone</i>
								<div class="footer-contact-info">
									<a class="" href="tel:+420%20728%20697%20712">+420 728 697 712</a>
								</div>
							</li>
							<li>
								<i class="material-icons material-icons-outlined md-22">schedule</i>
								<div class="footer-contact-info">
									<p>@lang('contact.open_hours')</p>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-xl-8 col-md-7 content-item">
					<form id="contact_form" action="/contact" method="post" class="form-submission contact-form contact-form-padding" novalidate>
					@csrf
						<input type="hidden" name="Subject" value="Contact form">
						<div class="row gutters-default">
							<div class="col-12">
								<h3>@lang('contact.contact_form')</h3>
								<h4>@lang('contact.send_message')</h4>
								{{-- <p>Nebudu vás trápit složitým formulářem na jehož vyplnění byste museli být zkušeným vývojářem. Stačí zanechat kontakt a já se vám obratem ozvu. </p> --}}
							</div>
							<div class="col-xl-4 col-sm-6 col-12">
								<div class="form-field">
									<label for="contact-name" class="">@lang('contact.name')</label>
									<input type="text" class="form-field-input" name="name" value="{{ old('name') }}" autocomplete="on" id="contact-name" required data-pristine-required-message="@lang('contact.required')">
								</div>
							</div>
							<div class="col-xl-4 col-sm-6 col-12">
								<div class="form-field">
									<label for="contact-tel" class="">@lang('contact.tel')</label>
									<input type="tel" class="form-field-input mask-phone" name="tel" value="{{ old('tel') }}" autocomplete="on" id="contact-tel" required data-pristine-required-message="@lang('contact.required')">
								</div>
							</div>
							<div class="col-xl-4 col-12">
								<div class="form-field">
									<label for="contact-email" class="">@lang('contact.email')</label>
									<input type="email" class="form-field-input" name="email" value="{{ old('email') }}" autocomplete="on" id="contact-email" required data-pristine-required-message="@lang('contact.required')" data-pristine-email-message="@lang('contact.enter_valid_email')">
								</div>
							</div>

							<label for="demand-website">@lang('layout.demand_form.demand_point')</label>
							
							<div class="col-xl-3 col-sm-4 col-6">
								<div class="form-field form-field-checkbox">
									<div class="checkbox">
										<input type="checkbox" class="checkbox-input" name="web" id="demand-website">
										<label for="demand-website" class="checkbox-label">@lang('layout.demand_form.website')</label>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-4 col-6">
								<div class="form-field form-field-checkbox">
									<div class="checkbox">
										<input type="checkbox" class="checkbox-input" name="app" id="demand-webapp">
										<label for="demand-webapp" class="checkbox-label">@lang('layout.demand_form.webapp')</label>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-4 col-6">
								<div class="form-field form-field-checkbox">
									<div class="checkbox">
										<input type="checkbox" class="checkbox-input" name="eshop" id="demand-eshop">
										<label for="demand-eshop" class="checkbox-label">@lang('layout.demand_form.eshop')</label>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-4 col-6">
								<div class="form-field form-field-checkbox">
									<div class="checkbox">
										<input type="checkbox" class="checkbox-input" name="other" id="demand-other">
										<label for="demand-other" class="checkbox-label">@lang('layout.demand_form.other')</label>
									</div>
								</div>
							</div>

							<div class="col-12">
								<div class="form-field">
									<label for="selected_option">@lang('contact.interested_offer_part_1') "@lang('layout.demand')" <a href="{{ url('price') }}">@lang('contact.interested_offer_part_2')</a></label>
									<select name="selected_price_option" id="selected_option">
										<option value="not-selected">@lang('contact.not_selected')</option>
										{{-- <optgroup label="@lang('price.one_off.groupe_name')">
										<option value="single-web" {{ request()->get('option') == 'single-web' ? 'selected' : '' }} >@lang('price.one_off.1.short_name')</option>
										<option value="single-app" {{ request()->get('option') == 'single-app' ? 'selected' : '' }} >@lang('price.one_off.2.short_name')</option>
										<option value="single-content" {{ request()->get('option') == 'single-content' ? 'selected' : '' }} >@lang('price.one_off.3.short_name')</option>
										<option value="single-design" {{ request()->get('option') == 'single-design' ? 'selected' : '' }} >@lang('price.one_off.4.short_name')</option>
										<option value="single-eshop" {{ request()->get('option') == 'single-eshop' ? 'selected' : '' }} >@lang('price.one_off.5.short_name')</option>
										<optgroup label="@lang('price.longer.groupe_name')">
										<option value="multi-seo" {{ request()->get('option') == 'multi-seo' ? 'selected' : '' }} >@lang('price.longer.1.short_name')</option>
										<option value="multi-social_media" {{ request()->get('option') == 'multi-social_media' ? 'selected' : '' }} >@lang('price.longer.2.short_name')</option> --}}
										<optgroup label="@lang('price.package.groupe_name')">
										<option value="package-basic @lang('price.package.1.cost')" {{ request()->get('option') == 'package-basic' ? 'selected' : '' }} >@lang('price.package.1.name') (@lang('price.package.1.cost'))</option>
										<option value="package-standard @lang('price.package.2.cost')" {{ request()->get('option') == 'package-standard' ? 'selected' : '' }} >@lang('price.package.2.name') (@lang('price.package.2.cost'))</option>
										<option value="package-profesional @lang('price.package.3.cost')" {{ request()->get('option') == 'package-profesional' ? 'selected' : '' }} >@lang('price.package.3.name') (@lang('price.package.3.cost'))</option>
										<option value="package-premium @lang('price.package.4.cost')" {{ request()->get('option') == 'package-premium' ? 'selected' : '' }} >@lang('price.package.4.name') (@lang('price.package.4.cost'))</option>
									</select>
								</div>
							</div>

							<div class="col-12">
								<div class="form-field">
									<label for="contact-message" class="">@lang('contact.message')</label>
									<textarea name="message" class="form-field-input" id="contact-message" cols="30" rows="4" placeholder="@lang('contact.message_placeholder')">{{ old('message') }}</textarea>
								</div>
								<div class="form-field form-field-checkbox">
									<div class="checkbox">
										<input type="checkbox" class="checkbox-input" name="privacypolicy" id="checkbox-popup" checked required data-pristine-required-message="@lang('contact.policy_not_agreed')">
										<label for="checkbox-popup" class="checkbox-label">@lang('contact.agree') <a href="{{ url('/privacy-policy') }}">@lang('contact.policy')</a></label>
									</div>
								</div>
								<div class="form-btn">
									<button type="submit" class="btn btn-w240 ripple"><span>@lang('contact.send')</span></button>
									<div class="g-recaptcha" 
									data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"
									data-size="invisible" 
									data-callback='onSubmit' 
									{{-- data-action='submit' --}} >
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Begin map -->
	<div class="map">
		<iframe class="lazy" data-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d409704.7392618907!2d16.43354898051773!3d49.04989294269036!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x66be6e0a59ec45a7%3A0xcea91464a27623df!2sitwebtech%20-%20Ond%C5%99ej%20Kri%C5%A1ka!5e0!3m2!1scs!2scz!4v1695894752026!5m2!1scs!2scz" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div><!-- End map -->
	{{-- reCaptcha --}}
	<script src="https://www.google.com/recaptcha/api.js"></script> 
@endsection
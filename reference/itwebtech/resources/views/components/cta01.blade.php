<div class="banner lazy section" data-background-image="{{ asset('/assets/img/header/banner-center-bg.gif') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="section-heading shm-none heading-center">
                        {{-- <div class="section-subheading"></div> --}}
                        <h2 class="text-color-extra">@lang('components.cta01.h2')</h2>
                        <p class="section-desc">
                            @lang('components.cta01.p')
                        </p>
                        @if (is_array(__('components.cta01.value')))
                            <ul>
                                @forelse (__('components.cta01.value') as $key => $val)
                                    <li>{{ $val }}</li>
                                @empty
                                    
                                @endforelse
                            </ul>
                        @endif
                    </div>
                    <footer class="section-footer col-12 section-footer-animate">
                        <div class="btn-group align-items-center justify-content-center">
{{--                             <a href="{{ url('contact') }}" class="btn btn-with-icon btn-w240 ripple">
                                <span>@lang('components.cta01.btn_consultation')</span>
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
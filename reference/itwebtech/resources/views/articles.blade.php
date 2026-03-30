@extends('layout')

@forelse ($sitemaps as $sitemap)
	@if ($sitemap['slug'] == 'jak-na-to')
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
                    <li>@lang('layout.menu.article')</li>
                </ul>
            </div>
        </div>
    </div>
</nav>




<div class="section">
    <div class="container">
        <div class="row content-items">
            <div class="col-lg-9 col-12 content-item">
                <div class="section-heading heading-center section-heading-animate">
                    <div class="section-subheading">@lang('article.subheading')</div>
                    <h1>@lang('article.heading')</h1>
                </div>
                <div class="news-list items">
{{-- {{ dd($articles) }} --}}
                    <!-- Begin news item -->
                @forelse ($articles as $article)
                    @if ($article->published)
                        <article class="news-wide-item item">
                            <a href="{{ route('article', $article->slug) }}" class="news-wide-item-img item-bordered item-border-radius">
                                <img data-src="{{ asset('assets/img/articles').'/'.$article->img_preview }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                            </a>
                            <div class="news-wide-item-info">
                                <h2 class="news-wide-item-heading item-heading">
                                    <a href="{{ route('article', $article->slug) }}">{{ $article->title }}</a>
                                </h2>
                                <div class="news-wide-item-row">
                                    <div class="news-wide-item-date">{{ ' • ' . __("article.updated") . ' ' . $article->updated_at->format('d.m.Y')}}</div>
                                </div>
                                <div class="news-wide-item-desc">
                                    <p>{!! $article->description !!}</p>
                                </div>
                                <div class="wrapp-btn-link">
                                    <a href="{{ route('article', $article->slug) }}" class="btn-link">
                                        <span>@lang('article.read_more')</span>
                                        <svg class="btn-link-ico btn-link-ico-right" viewBox="0 0 13 9" width="13" height="9" width="13px" height="9px"><use xlink:href="assets/img/sprite.svg#arrow-right"></use></svg>
                                    </a>
                                </div>
                            </div>
                        </article><!-- End news item -->
                        <hr>
                    @endif
                @empty
                    @lang('article.nothing_publisked')
                @endforelse
                    {{-- <div class="item">
                        <div class="news-nav justify-content-center">
                            <a href="#!" class="btn ripple btn-w240"><span>Load More</span></a>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-3 col-12 content-item">
                <!-- Begin sidebar -->
{{-- <aside class="sidebar items">
    <div class="sidebar-item item">
    <form action="#!" method="post">
    <div class="form-field">
        <label for="sidebar-field-search" class="form-field-label">Search...</label>
        <input type="search" class="form-field-input" name="Search" value="" autocomplete="off" id="sidebar-field-search">
        <button type="submit" class="search-btn"><i class="material-icons md-22">search</i></button>
    </div>
    </form>
    </div>
    <div class="sidebar-item item">
    <div class="section-bgc sibebar-item-bg-style">
    <p class="sidebar-item-heading item-heading">Sign Up to News</p>
    <p class="sidebar-item-desc">Subscribe to our news to get the latest updates and offers</p>
    <form action="#!" method="post" class="form-submission subscribe-news-form" novalidate>
        <div class="form-field">
            <label for="subscribe-news-email" class="form-field-label">Your Email</label>
            <input type="email" class="form-field-input" name="SubscribeEmail" value="" autocomplete="off" id="subscribe-news-email" required data-pristine-required-message="This field is required." data-pristine-email-message="Please enter a valid email address.">
        </div>
        <div class="form-btn">
            <button type="submit" class="btn btn-wide ripple"><span>Send Message</span></button>
        </div>
    </form>
    </div>
    </div>
    <div class="sidebar-item item">
    <p class="sidebar-item-heading item-heading">Featured Tags</p>
    <ul class="sidebar-tags">
    <li><a href="#!">Software</a></li>
    <li><a href="#!">Development</a></li>
    <li><a href="#!">Programming</a></li>
    <li><a href="#!">Software</a></li>
    <li><a href="#!">Development</a></li>
    <li><a href="#!">IT</a></li>
    </ul>
    </div>
    <div class="sidebar-item item">
    <p class="sidebar-item-heading item-heading">Recent Posts</p>
    <ul class="sidebar-posts">
    <li>
        <a href="#!" class="sidebar-post">
            <div class="sidebar-post-img item-bordered item-border-radius">
                <img data-src="assets/img/news-img-1.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
            </div>
            <div class="sidebar-post-title">Benefits of Async/Await in Programming</div>
        </a>
    </li>
    <li>
        <a href="#!" class="sidebar-post">
            <div class="sidebar-post-img item-bordered item-border-radius">
                <img data-src="assets/img/news-img-2.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
            </div>
            <div class="sidebar-post-title">Key Considerations and Warnings of iPaaS</div>
        </a>
    </li>
    <li>
        <a href="#!" class="sidebar-post">
            <div class="sidebar-post-img item-bordered item-border-radius">
                <img data-src="assets/img/news-img-3.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
            </div>
            <div class="sidebar-post-title">Usage of Hibernate Query Language</div>
        </a>
    </li>
    </ul>
    </div>
    <div class="sidebar-item item">
    <div class="section-bgc sibebar-item-bg-style">
    <p class="sidebar-item-heading item-heading">Categories</p>
    <ul class="sidebar-cat">
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Startup</a>
            <span class="sidebar-cat-count">25</span>
        </li>
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Software Development</a>
            <span class="sidebar-cat-count">14</span>
        </li>
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Hybrid Cloud Management</a>
            <span class="sidebar-cat-count">20</span>
        </li>
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Software Solutions</a>
            <span class="sidebar-cat-count">3</span>
        </li>
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Creating Better Software</a>
            <span class="sidebar-cat-count">18</span>
        </li>
        <li class="sidebar-cat-item">
            <a href="#!" class="sidebar-cat-link">Through Design Thinking</a>
            <span class="sidebar-cat-count">9</span>
        </li>
    </ul>
    </div>
    </div>
</aside> --}}<!-- End sidebar -->
            </div>
        </div>
    </div>
</div>


@includeIf('components.cta01')
@endsection
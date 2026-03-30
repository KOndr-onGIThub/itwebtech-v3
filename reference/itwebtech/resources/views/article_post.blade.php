@extends('layout')

@section('title', $translated_article->title)

@section('meta_description', $translated_article->description ) 


@section('og_img', asset('assets/img/articles').'/'.$translated_article->img_main )
@section('meta_description', asset('assets/img/articles').'/'.$translated_article->perex )
@section('og_url', route('article', $article_slug->slug) )


@section('og_type', 'article')

@section('CSS_links')
    {{-- <link rel="stylesheet" href={{ asset('css/home.css').'?'.env('APP_VERSION')}} type="text/css"> --}}
    <style>.widget-socials button{height: 100% !important;} </style>
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
                    <li>
                        <a href="{{url('/jak-na-to')}}">@lang('layout.menu.article')</a>
                        <i class="material-icons md-18">chevron_right</i>
                    </li>
                    @php
                    $cutter = '';
                    $howManyCharacters = strlen($translated_article->title);
                        /* looking for all spaces */
                        preg_match_all('/ /', $translated_article->title, $result, PREG_OFFSET_CAPTURE);
                        /*  */
                        if($result[0]){
                            /* how many are there? */
                            $howManySpaces = count($result[0]);
                            if($howManySpaces >=3){
                                $howManyCharacters = $result[0][2][1]; // na jake pozici je treti [2] mezera 
                                $cutter = '...';
                            }   
                        }
                    @endphp

                    <li>{{ substr($translated_article->title, 0, $howManyCharacters) . $cutter }}</li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="section">
    <div class="container">
        <div class="row content-items">
            <div class="col-lg-9 col-12 content-item">
                <div class="news-post">
                    <header class="news-post-header">
                        <h1 class="news-post-title" style="text-wrap:balance">{{ $translated_article->title }}</h1>
                        <div class="news-post-meta">
                            <div class="news-post-meta-item">
                                <i class="material-icons md-22">access_time</i>
                                <span>@lang('article.updated') {{ $article_info->updated_at->format('d.m.Y') }}</span>
                            </div>
                            {{-- <div class="news-post-meta-item">
                                <span>od &nbsp;</span>
                                <a href="#!">Ondřej Kriška</a>
                            </div> --}}
                            {{-- <div class="news-post-meta-item">
                                <i class="material-icons md-20">chat_bubble</i>
                                <span>18</span>
                            </div> --}}
                        </div>
                        <div class="news-post-img item-bordered item-border-radius">
                            <img data-src="{{ asset('assets/img/articles').'/'.$translated_article->img_main }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ $translated_article->title }}">
                        </div>
                    </header>
                    <article class="news-post-article content">

                    @if ($article_info->published)
    
                        @isset($translated_article)
                            {!! $translated_article->perex !!}
                            {!! $translated_article->content_1 !!}
                            {!! $translated_article->content_mid !!}
                            @if ($translated_article->img_mid)
                            <img data-src="{{ asset('assets/img/articles').'/'.$translated_article->img_mid }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ $translated_article->img_mid }}">
                            @endif
                            {!! $translated_article->content_2 !!}
                            @if ($translated_article->img_end)
                            <img data-src="{{ asset('assets/img/articles').'/'.$translated_article->img_end }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ $translated_article->img_end }}">
                            @endif
                            {!! $translated_article->bonus !!}
                            {!! $translated_article->extra !!}
                        @endisset 
                    @else
                        <blockquote>@lang('article.not_publisked')</blockquote>
                    @endif



                       

                    </article>
                    <footer class="news-post-footer">
                        <div class="row align-items-center justify-content-between items">
                            <div class="col-md col-12 item">
                                <ul class="news-post-cat">
                                    @forelse ( $article_info->tags as $tag)
                                        <li>{{ $tag->name }}</li>
                                    @empty
                                        
                                    @endforelse

                                </ul>
                            </div>
                            <div class="col-md-auto col-12 item">
                                <div class="news-post-share">
                                    <p class="news-post-share-title">@lang('article.share')</p>
                                    <ul class="widget-socials widget-socials-bordered">
                                        <li>
                                                            <!-- Your share button code -->
                                            <div class="fb-share-button" 
                                            data-href="{{ route('article', $article_info->slug) }}" 
                                            data-layout="button_count"
                                            data-size="large">
                                            </div>
                                        </li>
                                        {{-- <li>
                                            <a href="#!" title="Instagram">
                                                <svg viewBox="0 0 448 512">
                                                    <use xlink:href="{{ asset('assets/img/sprite.svg#instagram-icon') }}"></use>
                                                </svg>
                                            </a>
                                        </li>--}}
                                        <li>
                                            <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                                            <script type="IN/Share"></script>
                                        </li> 
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
                {{-- <div class="page-sections">
                    <section class="section">
                        <div class="row items">
                            <div class="col-12">
                                <h2>Recent Posts</h2>
                            </div>
                            <div class="col-md-6 col-12 item">
                                <!-- Begin news item -->
                                <article class="news-item item-style">
                                    <a href="news-post.html" class="news-item-img el">
                                        <img data-src="assets/img/news-img-1.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                    </a>
                                    <div class="news-item-info">
                                        <div class="news-item-date">07/01/2021</div>
                                        <h2 class="news-item-heading item-heading">
                                            <a href="news-post.html" title="Benefits Of Async/Await">Benefits Of Async/Await</a>
                                        </h2>
                                        <div class="news-item-desc">
                                            <p>Asynchronous functions are a good and bad thing in JavaScript.</p>
                                        </div>
                                    </div>
                                </article><!-- End news item -->
                            </div>
                            <div class="col-md-6 col-12 item">
                                <!-- Begin news item -->
                                <article class="news-item item-style">
                                    <a href="news-post.html" class="news-item-img el">
                                        <img data-src="assets/img/news-img-2.jpg" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                    </a>
                                    <div class="news-item-info">
                                        <div class="news-item-date">05/01/2021</div>
                                        <h2 class="news-item-heading item-heading">
                                            <a href="news-post.html" title="Key Considerations Of IPaaS">Key Considerations Of IPaaS</a>
                                        </h2>
                                        <div class="news-item-desc">
                                            <p>Digital transformation requires cloud appropriate adoption</p>
                                        </div>
                                    </div>
                                </article><!-- End news item -->
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <h2>Comments</h2>
                                <!-- Begin comments -->
                                <div class="comments">
                                    <!-- Begin comment item -->
                                    <div class="comment-item item-style">
                                        <div class="comment-item-row">
                                            <header class="comment-item-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="comment-item-author">
                                                            <div class="comment-item-author-img">
                                                                <img data-src="assets/img/auth-img-1.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                                            </div>
                                                            <div class="comment-item-author-info">
                                                                <h2 class="comment-item-author-name item-heading">Catherine Williams</h2>
                                                                <p class="comment-item-author-date">Jan 23, 2021</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#!" class="comment-item-reply">Reply</a>
                                                    </div>
                                                </div>
                                            </header>
                                            <div class="comment-item-desc">
                                                <p>PathSoft offers a high caliber of resources skilled in Microsoft Azure.NET, mobile and Quality Assurance. They became our true business partners over the past three years.</p>
                                            </div>
                                        </div>
                                        <div class="comment-item-list">
                                            <div class="comment-item-row">
                                                <header class="comment-item-header">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="comment-item-author">
                                                                <div class="comment-item-author-img">
                                                                    <img data-src="assets/img/auth-img-2.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                                                </div>
                                                                <div class="comment-item-author-info">
                                                                    <h2 class="comment-item-author-name item-heading">Rupert Wood</h2>
                                                                    <p class="comment-item-author-date">Jan 23, 2021</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="#!" class="comment-item-reply">Reply</a>
                                                        </div>
                                                    </div>
                                                </header>
                                                <div class="comment-item-desc">
                                                    <p>PathSoft offers a high caliber of resources skilled in Microsoft Azure.NET</p>
                                                </div>
                                            </div>
                                            <div class="comment-item-row">
                                                <header class="comment-item-header">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="comment-item-author">
                                                                <div class="comment-item-author-img">
                                                                    <img data-src="assets/img/auth-img-1.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                                                </div>
                                                                <div class="comment-item-author-info">
                                                                    <h2 class="comment-item-author-name item-heading">Catherine Williams</h2>
                                                                    <p class="comment-item-author-date">Jan 23, 2021</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <a href="#!" class="comment-item-reply">Reply</a>
                                                        </div>
                                                    </div>
                                                </header>
                                                <div class="comment-item-desc">
                                                    <p>PathSoft offers a high caliber</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- End comment item -->
                                    <!-- Begin comment item -->
                                    <div class="comment-item item-style">
                                        <div class="comment-item-row">
                                            <header class="comment-item-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="comment-item-author">
                                                            <div class="comment-item-author-img">
                                                                <img data-src="assets/img/auth-img-3.jpg" class="img-cover lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="">
                                                            </div>
                                                            <div class="comment-item-author-info">
                                                                <h2 class="comment-item-author-name item-heading">Samantha Brown</h2>
                                                                <p class="comment-item-author-date">Jan 23, 2021</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#!" class="comment-item-reply">Reply</a>
                                                    </div>
                                                </div>
                                            </header>
                                            <div class="comment-item-desc">
                                                <p>PathSoft offers a high caliber of resources skilled in Microsoft Azure.NET, mobile and Quality Assurance. They became our true business partners over the past three years.</p>
                                            </div>
                                        </div>
                                    </div><!-- End comment item -->
                                </div><!-- End comments -->
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <h2>Add Your Comment</h2>
                                <form action="#!" method="post" class="form-submission comments-form" novalidate>
                                    <div class="row gutters-default">
                                        <div class="col-sm-6 col-6">
                                            <div class="form-field">
                                                <label for="comments-name" class="form-field-label">Your Name</label>
                                                <input type="text" class="form-field-input" name="CommentsName" value="" autocomplete="off" id="comments-name" required data-pristine-required-message="This field is required.">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-6">
                                            <div class="form-field">
                                                <label for="comments-email" class="form-field-label">Your Email</label>
                                                <input type="email" class="form-field-input" name="CommentsEmail" value="" autocomplete="off" id="comments-email" required data-pristine-required-message="This field is required." data-pristine-email-message="Please enter a valid email address.">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-field">
                                                <label for="comments-message" class="form-field-label">Your comment</label>
                                                <textarea name="CommentsMessage" class="form-field-input" id="comments-message" cols="30" rows="6" required data-pristine-required-message="This field is required."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-btn">
                                                <button type="submit" class="btn btn-w240 ripple"><span>Add comment</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div> --}}
            </div>
            <div class="col-lg-3 col-12 content-item">
                <!-- Begin sidebar -->
<aside class="sidebar items">
{{--     <div class="sidebar-item item">
    <form action="#!" method="post">
    <div class="form-field">
        <label for="sidebar-field-search" class="form-field-label">Search...</label>
        <input type="search" class="form-field-input" name="Search" value="" autocomplete="off" id="sidebar-field-search">
        <button type="submit" class="search-btn"><i class="material-icons md-22">search</i></button>
    </div>
    </form>
    </div> --}}
{{--     <div class="sidebar-item item">
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
    </div> --}}
{{--     <div class="sidebar-item item">
    <p class="sidebar-item-heading item-heading">Featured Tags</p>
    <ul class="sidebar-tags">
    <li><a href="#!">Software</a></li>
    <li><a href="#!">Development</a></li>
    <li><a href="#!">Programming</a></li>
    <li><a href="#!">Software</a></li>
    <li><a href="#!">Development</a></li>
    <li><a href="#!">IT</a></li>
    </ul>
    </div> --}}
    <div class="sidebar-item item">
    <p class="sidebar-item-heading item-heading">@lang('article.last_articles_aside')</p>
    <ul class="sidebar-posts">
        @forelse ($random3_articles_aside_list as $random_article_aside)
            

        <li>
            <a href="{{ $random_article_aside->slug }}" class="sidebar-post">
                <div class="sidebar-post-img item-bordered item-border-radius">
                    <img data-src="{{ asset('/assets/img/articles/' . $random_article_aside->img_preview ) }}" class="lazy" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" alt="{{ $random_article_aside->title }}">
                </div>
                <div class="sidebar-post-title">{{ $random_article_aside->title }}</div>
            </a>
        </li>
        @empty
            <li>
                <div class="sidebar-post-title">@lang('article.nothing_publisked')</div>
            </li>
        @endforelse
    </ul>
    <p class="sidebar-item-heading item-heading">
        <div class="btn-group align-items-center justify-content-center top-2">
            <a href="{{ url('jak-na-to') }}" class="btn btn-with-icon btn-w240 ripple">
                <span>@lang('article.last_articles_aside')</span>
                <svg class="btn-icon-right" viewBox="0 0 13 9" width="13" height="9"><use xlink:href="{{ asset('/assets/img/sprite.svg') }}#arrow-right"></use></svg>
            </a>
        </div>
    </p>
    </div>
{{--     <div class="sidebar-item item">
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
    </div> --}}
</aside><!-- End sidebar -->
            </div>
        </div>
    </div>
</div>

<div class="banner lazy section" data-background-image="{{ asset('assets/img/intro-img5.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <div class="section-heading shm-none">
                        <div class="section-subheading">@lang('article.my_advartisement_post.sub_heading')</div>
                        <h2><span class="text-color-extra">@lang('article.my_advartisement_post.heading')</span></h2>
                        <p class="section-desc">@lang('article.my_advartisement_post.paragraph')</p>
                    </div>
                    <div class="btn-group align-items-center">
{{--                         <a href="{{ url('/price') }}" class="btn btn-with-icon ripple btn-small">
                            <span>@lang('article.my_advartisement_post.btn1')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="{{ asset('assets/img/sprite.svg#arrow-right') }}"></use></svg>
                        </a> --}}
                        <a href="{{ url('/contact') }}" class="btn btn-with-icon ripple btn-small btn-border">
                            <span>@lang('article.my_advartisement_post.btn2')</span>
                            <svg class="btn-icon-right" width="13" height="9" viewBox="0 0 13 9"><use xlink:href="{{ asset('assets/img/sprite.svg#arrow-right') }}"></use></svg>
                        </a>
                        <!-- inserted reservanto code -->
                        <div class="reservanto-widget" data-text="@lang('home.reservanto_button_text')" data-id="20854" data-color-text="#313131" data-color-text-shadow="transparent" data-color-bg="#ffcc00" data-color-bg-hover="#ffdf00" data-color-boxshadow="#c29b00" data-resourceid="32112"></div>
                        <!-- end of inserted reservanto code -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

@endsection
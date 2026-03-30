{{-- Mini foto galerie — Swiper (20 fotek, odkaz na galerii) --}}
<section class="section-sm bg-mx-black overflow-hidden">
  <div class="swiper mini-gallery-swiper"
       data-slides-per-view-sm="3"
       data-slides-per-view-md="4"
       data-slides-per-view-lg="6"
       data-slides-per-view-xl="8"
       data-autoplay="3000"
       data-loop="true">
    <div class="swiper-wrapper">

      @for($i = 1; $i <= 20; $i++)
      <div class="swiper-slide">
        <a href="{{ route('get.galleries') }}" class="group block relative overflow-hidden aspect-square">
          <img
            src="{{ url('images/gallery/minigallery/motokros_pravice (' . $i . ').jpg') }}"
            loading="lazy"
            alt="fotografie z pitbike areny Pravice"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
          />
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
            <i class="fa-brands fa-flickr text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
          </div>
        </a>
      </div>
      @endfor

    </div>
  </div>
</section>

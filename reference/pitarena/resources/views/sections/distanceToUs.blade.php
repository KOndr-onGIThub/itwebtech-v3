<section class="section bg-mx-dark">
  <div class="page-container max-w-3xl">

    <div class="text-center mb-8">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Kudy k nám</h2>
    </div>

    {{-- Swiper s LightGallery --}}
    <div class="swiper distance-swiper" data-loop="true" data-nav="true">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <a href="{{ asset('/images/mapa_pitarena_showroom.png') }}"
             class="distance-gallery-item group block relative rounded-xl overflow-hidden cursor-zoom-in">
            <img src="{{ asset('/images/mapa_pitarena_showroom.png') }}"
                 loading="lazy" alt="Pitarena - showroom mapa"
                 width="886" height="668"
                 class="w-full h-auto transition-transform duration-500 group-hover:scale-105"/>
            <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/30 transition-all duration-300">
              <i class="fa-solid fa-magnifying-glass-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
            </div>
          </a>
        </div>

        <div class="swiper-slide">
          <a href="{{ asset('/images/mapa_pitarena_showroom_detail.png') }}"
             class="distance-gallery-item group block relative rounded-xl overflow-hidden cursor-zoom-in">
            <img src="{{ asset('/images/mapa_pitarena_showroom_detail.png') }}"
                 loading="lazy" alt="Pitarena - showroom mapa detail"
                 width="886" height="668"
                 class="w-full h-auto transition-transform duration-500 group-hover:scale-105"/>
            <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/30 transition-all duration-300">
              <i class="fa-solid fa-magnifying-glass-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
            </div>
          </a>
        </div>

      </div>
      <div class="swiper-button-prev !text-mx-orange"></div>
      <div class="swiper-button-next !text-mx-orange"></div>
    </div>

  </div>
</section>

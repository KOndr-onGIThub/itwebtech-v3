<section class="section-sm {{ isset($background) ? $background : '' }}" style="border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
  <div class="page-container">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

      <a href="{{ route('trat') }}"
         class="flex flex-col items-center gap-2 py-4 px-3 rounded-lg text-gray-300 hover:text-mx-orange hover:bg-mx-gray transition-all duration-200 text-center group"
         data-aos="fade-up">
        <i class="fa-solid fa-person-biking text-2xl text-mx-orange group-hover:scale-110 transition-transform"></i>
        <span class="text-sm font-semibold">Volné jízdy</span>
      </a>

      <a href="{{ route('programy') }}"
         class="flex flex-col items-center gap-2 py-4 px-3 rounded-lg text-gray-300 hover:text-mx-orange hover:bg-mx-gray transition-all duration-200 text-center group"
         data-aos="fade-up" data-aos-delay="100">
        <i class="fa-solid fa-chart-line text-2xl text-mx-orange group-hover:scale-110 transition-transform"></i>
        <span class="text-sm font-semibold">Programy</span>
      </a>

      <a href="{{ route('cup') }}"
         class="flex flex-col items-center gap-2 py-4 px-3 rounded-lg text-gray-300 hover:text-mx-orange hover:bg-mx-gray transition-all duration-200 text-center group"
         data-aos="fade-up" data-aos-delay="200">
        <i class="fa-solid fa-flag-checkered text-2xl text-mx-orange group-hover:scale-110 transition-transform"></i>
        <span class="text-sm font-semibold">Závody</span>
      </a>

      <a href="{{ route('servis') }}"
         class="flex flex-col items-center gap-2 py-4 px-3 rounded-lg text-gray-300 hover:text-mx-orange hover:bg-mx-gray transition-all duration-200 text-center group"
         data-aos="fade-up" data-aos-delay="300">
        <i class="fa-solid fa-wrench text-2xl text-mx-orange group-hover:scale-110 transition-transform"></i>
        <span class="text-sm font-semibold">Servis</span>
      </a>

      <a href="{{ route('moto') }}"
         class="col-span-2 sm:col-span-1 flex flex-col items-center gap-2 py-4 px-3 rounded-lg text-gray-300 hover:text-mx-orange hover:bg-mx-gray transition-all duration-200 text-center group"
         data-aos="fade-up" data-aos-delay="400">
        <i class="fa-solid fa-motorcycle text-2xl text-mx-orange group-hover:scale-110 transition-transform"></i>
        <span class="text-sm font-semibold">Moto</span>
      </a>

    </div>
  </div>
</section>

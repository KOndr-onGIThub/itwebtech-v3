<section class="section section-cta {{ isset($background) ? $background : '' }}">
  <div class="page-container">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

      {{-- Text --}}
      <div>
        <div class="section-subtitle">YCF CUP 2026</div>
        <div class="section-divider"></div>
        <h2 class="section-title" data-aos="fade-right">
          Překračuj své limity,<br>objevuj vítězství!
        </h2>
        <p class="text-gray-400 mt-4 leading-relaxed" data-aos="fade-right" data-aos-delay="150">
          {!! isset($text) ? $text : 'Zaregistruj se do závodů.' !!}
        </p>
        <p class="text-gray-400 mt-3 leading-relaxed" data-aos="fade-right" data-aos-delay="250">
          Registrace pro sezónu 2026 je spuštěna. Jsi připraven?
        </p>
        <div class="mt-6" data-aos="zoom-in" data-aos-delay="350">
          <a href="{{ route('registrace-cup.index') }}" class="btn-primary">
            <i class="fa-solid fa-flag-checkered"></i>
            Chci závodit
          </a>
        </div>
      </div>

      {{-- Obrázek --}}
      <div data-aos="fade-left" data-aos-delay="200">
        <img
          src="{{ asset('images/start_ycf_cup.webp') }}"
          loading="lazy"
          width="652" height="491"
          alt="YCF CUP startuje"
          class="w-full rounded-xl shadow-2xl shadow-black/50"
        />
      </div>

    </div>
  </div>
</section>

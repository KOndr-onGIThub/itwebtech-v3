@extends('layout')

@section('title', 'Půjčovna Pitbike motorek. Zažijte adrenalin na závodní trati.')
@section('meta_description', 'Pro děti i dospělé, bez ohledu na věk. Vyzkoušejte skutečný závodní zážitek od 150 Kč! Půjčte si opravdový závodní stroj a svezte se na závodní trati.')

@section('og')
  {{-- Facebook Meta Tags --}}
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Půjčovna Pitbike motorek = Nejdostupnější jízda.">
  <meta property="og:description" content="Pro děti i dospělé, bez ohledu na věk. Vyzkoušejte skutečný závodní zážitek!">
  <meta property="og:image" content="{{ asset('/images/pujcovna/YCF_CUP_pujcovna_2.jpg') }}">
  <meta property="og:locale" content="cs_CZ">

  {{-- Twitter Meta Tags --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="{{ route('home') }}">
  <meta property="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Půjčovna Pitbike motorek = Nejdostupnější jízda.">
  <meta name="twitter:description" content="Pro děti i dospělé, bez ohledu na věk. Vyzkoušejte skutečný závodní zážitek!">
  <meta name="twitter:image" content="{{ asset('/images/pujcovna/YCF_CUP_pujcovna_2.jpg') }}">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list">
      <li><a href="{{ route('home') }}">Úvod</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li><a href="{{ route('moto') }}">Moto</a></li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current">Pitbike Půjčovna</li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

  {{-- Parallax hero --}}
  <section class="parallax-hero" style="--hero-img: url('{{ asset('images/pujcovna/YCF_CUP_pujcovna_2.webp') }}');">
    <div class="parallax-hero-content">
      <div data-aos="fade-up">
        <div class="section-divider section-divider-center"></div>
        <h1 class="balanced-text">Pitbike Půjčovna</h1>
      </div>
    </div>
  </section>

  {{-- Header / CTA sekce --}}
  <section class="section-lg bg-mx-black text-center">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center">Zažij adrenalin na závodní trati!</h2>
      </div>
      <p data-aos="fade-up" data-aos-delay="100">
        Pro děti i dospělé, bez ohledu na věk. Vyzkoušej skutečný závodní zážitek.
        <br>
        Půjčujeme závodní pitbike motokrosové motorky.
      </p>
      <p class="text-xl font-medium text-mx-white mt-3" data-aos="fade-up" data-aos-delay="150">
        Určeno pro absolventy programu <a href="{{ route('mx-go') }}" target="_blank" class="text-mx-gold hover:underline">MX-GO</a> a ty kdo zvládnou samostatně ovládat danou motorku *.
      </p>
      <p class="mt-0 text-gray-400 text-sm" data-aos="fade-up" data-aos-delay="200">* Týká se zejména dětí. Je zkrátka potřeba udržet rovnováhu, umět zastavit, apod..</p>
      <a class="btn-primary mt-4 inline-block" href="#koupit" data-aos="zoom-in" data-aos-delay="300">Chci si půjčit závodní pitbike</a>
    </div>
  </section>

  {{-- Why Rent sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div data-aos="fade-right">
          <img src="{{ asset('/images/pujcovna/pitbike_pujcovna_1.webp') }}" loading="lazy" width="652" height="491"
              alt="Pitbike pro děti i dospělé" class="w-full rounded-xl">
        </div>
        <div data-aos="fade-left">
          <div class="section-divider mb-4"></div>
          <h2 class="section-title mb-4">Adrenalin pro rodiče i děti!</h2>
          <p class="text-gray-300 mb-4">
            Vy a vaše děti si zasloužíte skutečný zážitek. Nejde jen o jízdu, jde o pocit svobody a čas strávený na trati s rodinou.
            Představte si své dítě, jak se směje pod helmou, nebo jak vy sám přebíráte otěže a berete zatáčky na plný plyn.
            U nás není žádná překážka příliš velká a každý závod je jen výzvou.
          </p>
          <ul class="list-disc list-inside space-y-2 text-gray-300 mb-6">
            <li>Široký výběr motorek pro každou věkovou skupinu.</li>
            <li>Profesionálně udržovaná trať a motorky.</li>
            <li>Adrenalin a radost pro celou rodinu!</li>
          </ul>
          <a class="btn-primary" href="#koupit" data-aos="flip-right" data-aos-duration="600">Chci si půjčit závodní pitbike</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Motorcycles sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Vyber si svůj stroj!</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- YCF A50 --}}
        <div class="card-dark" data-aos="flip-up" data-aos-duration="500">
          <div class="card-dark-body">
            <span class="badge-orange mb-3">50 ccm</span>
            <img src="{{ asset('/images/pujcovna/ycf_a50.webp') }}" loading="lazy" width="418" height="315" alt="půjčovna YCF A50" class="w-full rounded-lg mb-4">
            <h5 class="text-mx-white font-semibold mb-2">YCF A50 (4-7 let)</h5>
            <p class="text-gray-400 text-sm">Automatické řazení, ideální pro nejmenší a úplné začátečníky.</p>
          </div>
        </div>

        {{-- YCF F88 --}}
        <div class="card-dark" data-aos="flip-up" data-aos-duration="500" data-aos-delay="200">
          <div class="card-dark-body">
            <span class="badge-orange mb-3">88 ccm</span>
            <img src="{{ asset('/images/pujcovna/ycf_f88.webp') }}" loading="lazy" width="418" height="315" alt="půjčovna YCF F88" class="w-full rounded-lg mb-4">
            <h5 class="text-mx-white font-semibold mb-2">YCF F88 (6-10 let)</h5>
            <p class="text-gray-400 text-sm">Poloautomatické řazení, bezpečná a zábavná volba pro menší děti.</p>
          </div>
        </div>

        {{-- YCF F125 --}}
        <div class="card-dark" data-aos="flip-up" data-aos-duration="500" data-aos-delay="400">
          <div class="card-dark-body">
            <span class="badge-orange mb-3">125 ccm</span>
            <img src="{{ asset('/images/pujcovna/ycf_f125.webp') }}" loading="lazy" width="418" height="315" alt="půjčovna YCF F125" class="w-full rounded-lg mb-4">
            <h5 class="text-mx-white font-semibold mb-2">YCF F125 (8-14 let)</h5>
            <p class="text-gray-400 text-sm">Poloautomatické řazení, připraveno pro starší mladé jezdce.</p>
          </div>
        </div>

        {{-- YCF F125 Bigy --}}
        <div class="card-dark" data-aos="flip-up" data-aos-duration="500">
          <div class="card-dark-body">
            <span class="badge-orange mb-3">125 ccm</span>
            <img src="{{ asset('/images/pujcovna/ycf_f125_bigy.webp') }}" loading="lazy" width="418" height="315" alt="půjčovna YCF F125 Bigy" class="w-full rounded-lg mb-4">
            <h5 class="text-mx-white font-semibold mb-2">YCF F125 Bigy (13-99 let)</h5>
            <p class="text-gray-400 text-sm">Pro teenagery a dospělé, zábava s poloautomatickým řazením.</p>
          </div>
        </div>

        {{-- YCF F150 Bigy --}}
        <div class="card-dark" data-aos="flip-up" data-aos-duration="500" data-aos-delay="200">
          <div class="card-dark-body">
            <span class="badge-orange mb-3">150 ccm</span>
            <img src="{{ asset('/images/pujcovna/ycf_f150_bigy.webp') }}" loading="lazy" width="418" height="315" alt="půjčovna YCF F150 Bigy" class="w-full rounded-lg mb-4">
            <h5 class="text-mx-white font-semibold mb-2">YCF F150 Bigy (13-99 let)</h5>
            <p class="text-gray-400 text-sm">Spojka a plný výkon – zážitek pro skutečné nadšence.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- How It Works --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-2">Jak to funguje?</h2>
      </div>
      <p class="text-center text-mx-orange font-medium mb-8">Celoročně od 9:00 do 16:00</p>

      <div class="max-w-xl mx-auto space-y-6" data-aos="fade-up">
        <p class="text-mx-white font-semibold text-center mb-4">Půjčení motorky je jednoduché!</p>

        <div class="border-l-2 border-mx-orange pl-4">
          <p class="text-mx-white"><span class="text-mx-orange font-bold mr-2">1.</span> Vyber si motorku a termín.</p>
        </div>
        <div class="border-l-2 border-mx-orange pl-4">
          <p class="text-mx-white"><span class="text-mx-orange font-bold mr-2">2.</span> Vyplň jednoduchý <a href="#koupit" class="text-mx-gold hover:underline">formulář zde</a>.</p>
        </div>
        <div class="border-l-2 border-mx-orange pl-4">
          <p class="text-mx-white"><span class="text-mx-orange font-bold mr-2">3.</span> Vyčkej na potvrzení rezervace.</p>
        </div>
      </div>

      <div class="mt-8 flex justify-center" data-aos="flip-down" data-aos-duration="700" data-aos-delay="300">
        <div class="bg-mx-orange/10 border border-mx-orange rounded-xl px-8 py-6 text-center max-w-sm">
          <p class="text-mx-white font-bold text-lg">
            Cena: 150 Kč za 10 minut<br>
            Min. doba zapůjčení: 1h<br>
            Vratná kauce: 2000 Kč
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- Equipment sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Co budeš potřebovat?</h2>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div data-aos="fade-right">
          <img src="{{ asset('images/prilba_rukavice.webp') }}" loading="lazy" width="652" height="491" alt="Výbava na pitbike ježdění" class="w-full rounded-xl">
        </div>
        <div data-aos="fade-left">
          <h4 class="text-mx-white text-xl font-semibold mb-4">Základní vybavení</h4>
          <ol class="list-decimal list-inside space-y-2 text-gray-300 mb-4">
            <li>solidní helmu</li>
            <li>chrániče na hrudník, lokty a kolena</li>
            <li>pevné boty a kalhoty</li>
            <li>a nezapomeň na rukavice a brýle</li>
          </ol>
          <p class="text-gray-300 text-sm">
            <span class="text-mx-white font-medium">Pokud nemáš své vlastní vybavení, po telefonické domluvě ti vše potřebné rádi půjčíme.</span>
            Cena zapůjčení kompletní výbavy je 300 Kč (platba na místě). Při zapůjčení helmy je nutná kukla (také můžeme dodat).
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- Testimonials --}}
  @includeif('sections/testimonials', ['background' => ''])

  {{-- Gallery sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Podívej se, jak to u nás vypadá!</h2>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" data-lg-gallery>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_01.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_01.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_02.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_02.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_03.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_03.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_04.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_04.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_05.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_05.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
        <a class="lg-item" href="{{ asset('/images/pujcovna/unas_06.jpg') }}">
          <img src="{{ asset('/images/pujcovna/unas_06.webp') }}" loading="lazy" alt="Pitbike galerie" class="w-full aspect-video object-cover rounded-lg hover:opacity-90 transition-opacity">
        </a>
      </div>
    </div>
  </section>

  {{-- Map sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-2">Kde nás najdeš</h2>
      </div>
      <p class="text-center text-gray-400 mb-6">Pitbike motokrosová trať Pravice, okr. Znojmo</p>
      <div class="rounded-xl overflow-hidden h-64 lg:h-80">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d84007.40796930129!2d16.3276078140407!3d48.8537958036172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4712b58541ef5cd1%3A0xafc9f2294f6d4f41!2sMX%2FENDURO%20PRAVICE!5e0!3m2!1scs!2scz!4v1729510462813!5m2!1scs!2scz" title="mapa" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <p class="text-center text-gray-400 mt-4">Telefon: 704 221 663</p>
      <p class="text-center text-gray-400">Email: standa@pitarena.cz</p>
    </div>
  </section>

  {{-- SimpleShop form --}}
  <section id="koupit" class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Zapůjčení pitbike objednávejte zde</h2>
      </div>
      <!-- www.SimpleShop.cz form#121166 start -->
      <div class="bg-white max-w-3xl mx-auto rounded-lg shadow-2xl px-4 py-6 sm:px-8 sm:py-8">
        <div data-SimpleShopForm="n0BNx"><div>Prodejní formulář je vytvořen v systému <a href="https://www.simpleshop.cz/?utm_source=simpleshop&utm_medium=form&utm_campaign=49201" target="_blank">SimpleShop.cz</a>.</div></div>
      </div>
      <script>
      (function(i, s, o, g, r, a, m){
        i[r] = i[r] || function(){
        (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, "script", "https://form.simpleshop.cz/prj/js/SimpleShopService.js", "sss");
      sss("createForm", "n0BNx");
      </script>
      <!-- www.SimpleShop.cz form#121166 end -->
    </div>
  </section>

  {{-- FAQ --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="text-center" data-aos="fade-up">
        <div class="section-divider section-divider-center mb-4"></div>
        <h2 class="section-title-center mb-8">Nejčastější dotazy</h2>
      </div>

      <div x-data="{ open: null }" class="space-y-2 max-w-3xl mx-auto">

        {{-- FAQ Item 1 --}}
        <div class="card-dark">
          <button @click="open = open === 1 ? null : 1"
                  class="w-full flex items-center justify-between px-5 py-4 text-left text-mx-white font-medium hover:bg-mx-gray transition-colors">
            <span>Je potřeba mít nějaké zkušenosti?</span>
            <i class="fa-solid fa-chevron-down text-mx-orange transition-transform" :class="open === 1 && 'rotate-180'"></i>
          </button>
          <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
               :class="open === 1 ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
            <div class="overflow-hidden">
              <div class="px-5 py-4 text-gray-400 text-sm bg-mx-gray border-t border-mx-gray2">
                <p>
                  Ano. Není to jako půjčit si motokáru - i když většina našich pitbiků má automatickou nebo poloautomatickou převodovku,
                  zvládnutí jízdy vyžaduje cit pro plyn a brzdu. Nelze na ně posadit naprosto nezkušeného jezdce (hlavně malé dítě) a doufat,
                  že bezpečně zastaví nebo nevytáhne plyn na doraz a neskončí ve škarpě.
                  Podmínkou je buď <strong class="text-mx-white">absolvování kurzu MX GO</strong> (více o něm najdete na <a href="{{ route('mx-go') }}" target="_blank" class="text-mx-gold hover:underline">Programy → Poukazy → mx-go</a>),
                  nebo <strong class="text-mx-white">prokázaná schopnost samostatně ovládat motorku</strong>.
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- FAQ Item 2 --}}
        <div class="card-dark">
          <button @click="open = open === 2 ? null : 2"
                  class="w-full flex items-center justify-between px-5 py-4 text-left text-mx-white font-medium hover:bg-mx-gray transition-colors">
            <span>Jaká je minimální doba zapůjčení?</span>
            <i class="fa-solid fa-chevron-down text-mx-orange transition-transform" :class="open === 2 && 'rotate-180'"></i>
          </button>
          <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
               :class="open === 2 ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
            <div class="overflow-hidden">
              <div class="px-5 py-4 text-gray-400 text-sm bg-mx-gray border-t border-mx-gray2">
                <p>
                  I když je základní sazba za 10 minut jízdy, půjčujeme pitbiky vždy <strong class="text-mx-white">minimálně na 1 hodinu</strong>.
                  Chceme nabídnout opravdový závodní zážitek - ne rychlou „pouťovou atrakci".
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- FAQ Item 3 --}}
        <div class="card-dark">
          <button @click="open = open === 3 ? null : 3"
                  class="w-full flex items-center justify-between px-5 py-4 text-left text-mx-white font-medium hover:bg-mx-gray transition-colors">
            <span>Co když motorku pošramotím nebo ji poškodím?</span>
            <i class="fa-solid fa-chevron-down text-mx-orange transition-transform" :class="open === 3 && 'rotate-180'"></i>
          </button>
          <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
               :class="open === 3 ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
            <div class="overflow-hidden">
              <div class="px-5 py-4 text-gray-400 text-sm bg-mx-gray border-t border-mx-gray2">
                <p>
                  Drobné oděrky a škrábance neřešíme - jsou zahrnuty v běžné údržbě. Pokud dojde k vážnějšímu poškození vinou jezdce,
                  domluvíme se na náhradě škody - většinou stačí doplatek ceny náhradních dílů.
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- FAQ Item 4 --}}
        <div class="card-dark">
          <button @click="open = open === 4 ? null : 4"
                  class="w-full flex items-center justify-between px-5 py-4 text-left text-mx-white font-medium hover:bg-mx-gray transition-colors">
            <span>Co když se počasí zhorší nebo začne pršet?</span>
            <i class="fa-solid fa-chevron-down text-mx-orange transition-transform" :class="open === 4 && 'rotate-180'"></i>
          </button>
          <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
               :class="open === 4 ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
            <div class="overflow-hidden">
              <div class="px-5 py-4 text-gray-400 text-sm bg-mx-gray border-t border-mx-gray2">
                <p>
                  Trať je připravená i na lehký déšť - jen upravíme provozní podmínky a případně zkrátíme intervaly mezi jízdami.
                  Při silném lijáku nebo bouřce si vyhrazujeme právo jízdy odložit.
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- FAQ Item 5 --}}
        <div class="card-dark">
          <button @click="open = open === 5 ? null : 5"
                  class="w-full flex items-center justify-between px-5 py-4 text-left text-mx-white font-medium hover:bg-mx-gray transition-colors">
            <span>Jak probíhá rezervace pitbiku?</span>
            <i class="fa-solid fa-chevron-down text-mx-orange transition-transform" :class="open === 5 && 'rotate-180'"></i>
          </button>
          <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
               :class="open === 5 ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
            <div class="overflow-hidden">
              <div class="px-5 py-4 text-gray-400 text-sm bg-mx-gray border-t border-mx-gray2">
                <p class="mb-2">
                  <strong class="text-mx-orange">1.</strong> Rezervaci provedeš jednoduše <strong class="text-mx-white">online zde <a href="#koupit" class="text-mx-gold hover:underline">Rezervace zapůjčení pitbike</a></strong>.
                </p>
                <ul class="list-disc list-inside space-y-1 mb-2">
                  <li>zadej fakturační údaje</li>
                  <li>vyber tam také o jakou motorku máš zájem</li>
                  <li>zvol si preferovaný datum /<strong class="text-mx-white">alespoň 2 dny předem</strong>/ a čas</li>
                  <li>do poznámky můžeš uvést například případný zájem o zapůjčení výstroje (boty, helma, ...), apod.</li>
                </ul>
                <p class="mb-2">
                  <strong class="text-mx-orange">2.</strong> Po přijetí platby ti <strong class="text-mx-white">zavoláme a potvrdíme</strong>, jestli je v daný termín volná trať i motorka.
                  Případně se domluvíme na náhradním termínu.
                </p>
                <p>
                  <strong class="text-mx-orange">3.</strong> V dohodnutý termín se dostavte alespoň 15 min předem.
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- Last CTA sekce --}}
  <section class="section-lg bg-mx-black">
    <div class="page-container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div data-aos="fade-right">
          <img src="{{ asset('/images/pujcovna/nejdostupnejsi_jizda.webp') }}" width="652" height="491"
                    alt="Půjčovna Pitbike pro děti i dospělé" class="w-full rounded-xl" loading="lazy">
        </div>
        <div data-aos="fade-left">
          <div class="section-divider mb-4"></div>
          <h2 class="section-title mb-4">Jezdíme (téměř) za každého počasí</h2>
          <a class="btn-primary" href="#koupit" data-aos="flip-right" data-aos-duration="600">Chci si půjčit závodní pitbike</a>
        </div>
      </div>
    </div>
  </section>

@endsection

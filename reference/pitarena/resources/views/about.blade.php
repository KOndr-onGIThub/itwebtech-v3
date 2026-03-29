@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == 'about')
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@endforelse

@section('og')
  <meta property="og:url" content="https://pitarena.cz">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta property="og:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta property="og:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="pitarena.cz">
  <meta property="twitter:url" content="https://pitarena.cz">
  <meta name="twitter:title" content="Pitbike motokros zábava, sport a adrenalin pro celou rodinu">
  <meta name="twitter:description" content="Zde získáte kompletní zázemí a servis v oblasti MX pro děti i dospělé. Přijďte si vše prohlédnout a vyzkoušet. Půjčíme vám i motorku a vše vysvětlíme.">
  <meta name="twitter:image" content="https://pitarena.cz/images/pitarena_cz_1200x630_2023.jpg">
@endsection

@section('breadcrumbs')
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <div class="page-container">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ route('home') }}"><span itemprop="name">Úvod</span></a>
        <meta itemprop="position" content="1"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">O nás</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Parallax hero --}}
<section
  class="parallax-hero"
  style="--hero-img: url('{{ asset('images/about/pitarena.webp') }}');"
>
  <div class="parallax-hero-content">
    <div class="section-divider section-divider-center"></div>
    <h1 class="text-4xl lg:text-5xl font-semibold text-white">PitAréna</h1>
    <p class="text-gray-300 mt-2">Pitbike motokrosová aréna v Pravicích</p>
  </div>
</section>

{{-- CTA --}}
<div class="bg-mx-dark py-5 text-center">
  <a href="#kontakty" class="btn-primary">
    <i class="fa-solid fa-envelope"></i>
    Kontaktujte nás
  </a>
</div>

{{-- Příběh --}}
<section id="historie" class="section bg-mx-black">
  <div class="page-container">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title mb-1">PITBIKE ARÉNA</h2>
      <h3 class="text-xl font-normal text-gray-400 mb-10">Jak to začalo a jak to bude dál?</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-400 text-sm leading-relaxed">
      <div>
        <p class="mb-4">
          Ahoj, já jsem Standa. Tahle <strong class="text-mx-white">pitbike-motokrosová trať</strong>, to je moje dítě.
          Vzpomínám, jak to bylo v roce 2020, kdy jsem stál před tím zarostlým polem a říkal jsem si: 'Tady to postavím.'
          To byl ten moment, kdy se roky mého snění začaly měnit ve skutečnost.
        </p>
        <p>
          Vždyť to znáte, já nejsem žádnej snob, co by se honil za prachama nebo slávou.
          Vždycky jsem chtěl jen jedno – místo, kde by se pitbike a motokros mohly stát
          <strong class="text-mx-white">domovem pro nás všechny, co jsme do toho zblázněný.</strong>
        </p>
      </div>
      <div>
        <p class="mb-4">
          Přeskáčeme teď do roku 2022. Pamatuju, jak jsem stál na startovní rovince, oči mi hrály a srdce bušilo jako o závod,
          když jsem sledoval start prvních závodů na mé trati.
          To byla bomba!
        </p>
        <p>
          Když se staví trať, musíte si být jistý dvěma věcma - <strong class="text-mx-white">kvalitou a bezpečností</strong>.
          Na tomhle nehodlám šetřit.
          Nikdo mi tu nebude lítat po trati na nějakým šrotu a riskovat zranění.
          Takže kvalita a bezpečnost, to je u mě na prvním místě.
        </p>
      </div>
      <div>
        <p class="mb-4">
          No a teď se tu se mnou ocitáte. Mým cílem je <strong class="text-mx-white">dělat tuhle trať naplno</strong>,
          vytvořit místo, který nebude jen přežívat, ale bude <strong class="text-mx-white">růst</strong>.
          Chci, aby naši mladí i starší svěřenci měli silný klub za zády
          a možná i nějakýho toho <strong class="text-mx-white">sponzora, co by jim pomohl</strong>.
        </p>
        <p class="mb-4">
          Takže, lidi, vítejte na mojí trati! <strong class="text-mx-white">Pojďte se sem projít, prohodit pár slov nebo si dát nějaký to kolo.</strong>
          <br>
          Těším se na vás všechny!
        </p>
        <p class="text-lg font-semibold text-mx-white">Stanislav Holcmann</p>
      </div>
    </div>
  </div>
</section>

{{-- Fotky --}}
<div class="grid grid-cols-1 md:grid-cols-2">
  <img src="{{ asset('images/Standa-Holcmann-pitarena_about.webp') }}" loading="lazy"
       alt="foto Standa Holcmann" width="960" height="713"
       class="w-full h-80 md:h-96 object-cover"/>
  <img src="{{ asset('images/pitbike_motokros_pravice.webp') }}" loading="lazy"
       alt="prostředí pitbike areny v Pravicích" width="960" height="713"
       class="w-full h-80 md:h-96 object-cover"/>
</div>

{{-- Kontakty --}}
<section id="kontakty" class="section bg-mx-dark">
  <div class="page-container max-w-2xl text-center">

    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Kontakty</h2>
    </div>
    <p class="text-gray-400 mt-2">
      Těšíme se na zprávu od vás.<br>
      Použijte například <strong class="text-mx-white">kontaktní formulář níže</strong>.
    </p>

    <div class="mt-6 mb-8">
      <p class="font-semibold text-mx-white mb-1">Stanislav Holcmann: Pravice 671 78</p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="tel:+420704221663" class="flex items-center gap-2 text-mx-gold hover:underline">
          <i class="fa-solid fa-phone"></i> (+420) 704 221 663
        </a>
        <a href="mailto:standa@pitarena.cz" class="flex items-center gap-2 text-mx-gold hover:underline">
          <i class="fa-solid fa-envelope"></i> standa@pitarena.cz
        </a>
      </div>
    </div>

    <h5 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Nejaktivnější jsme:</h5>
    <div class="flex flex-col sm:flex-row justify-center gap-6 mb-10 text-sm">
      <div class="bg-mx-gray rounded-lg px-6 py-4 border border-mx-gray2">
        <p class="font-semibold text-mx-white">Pondělí – Pátek</p>
        <p class="text-mx-orange font-bold mt-1"><i class="fa-regular fa-clock mr-1"></i>09 – 18 h.</p>
      </div>
      <div class="bg-mx-gray rounded-lg px-6 py-4 border border-mx-gray2">
        <p class="font-semibold text-mx-white">Sobota – Neděle</p>
        <p class="text-mx-orange font-bold mt-1"><i class="fa-regular fa-clock mr-1"></i>11 – 20 h.</p>
      </div>
    </div>

    {{-- Kontaktní formulář --}}
    <form id="contact_form" method="post" action="/o-nas" class="space-y-4 text-left">
      @csrf

      <div class="form-field">
        <label class="form-label" for="contact-name"><span class="text-mx-orange">*</span> Jméno</label>
        <input class="form-input" id="contact-name" type="text" name="name"
               placeholder="Vaše jméno" value="{{ old('name') }}" required minlength="3">
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-email"><span class="text-mx-orange">*</span> E-mail</label>
        <input class="form-input" id="contact-email" type="email" name="email"
               placeholder="Vaše emailová adresa" value="{{ old('email') }}" required>
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-phone"><span class="text-mx-orange">*</span> Telefon</label>
        <input class="form-input" id="contact-phone" type="text" name="phone"
               placeholder="Vaše telefonní číslo" value="{{ old('phone') }}" required>
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-message"><span class="text-mx-orange">*</span> Vzkaz</label>
        <textarea class="form-textarea" id="contact-message" name="message"
                  placeholder="Jak vám můžeme pomoct?" required minlength="20">{{ old('message') }}</textarea>
      </div>

      <div class="flex items-center justify-between pt-2">
        <span class="text-xs text-gray-400"><span class="text-mx-orange">*</span> vyžadované pole</span>
        <div>
          <input id="loading_contact_form"
                 class="btn-primary cursor-pointer"
                 type="submit" value="Odeslat" />
          <div class="g-recaptcha"
               data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
               data-size="invisible"
               data-callback="onSubmitContactForm">
          </div>
        </div>
      </div>
    </form>

  </div>
</section>

{{-- Poloha --}}
<section class="bg-mx-black py-20 relative overflow-hidden bg-grid">

  <div class="relative z-10 flex justify-center items-center px-4">
    <div class="bg-mx-dark border border-mx-gray3 rounded-lg p-10 max-w-sm w-full text-center shadow-card">

      {{-- Ikona --}}
      <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-lg bg-mx-gray2 border border-mx-gray3 flex items-center justify-center">
          <i class="fa-solid fa-location-dot text-2xl text-mx-orange"></i>
        </div>
      </div>

      <h3 class="text-mx-light font-bold text-xl uppercase tracking-wide mb-1">PitAréna</h3>
      <p class="text-gray-400 text-sm mb-2">Stanislav Holcmann</p>
      <p class="text-gray-500 text-sm mb-8">Pravice 671 78</p>

      <a href="https://maps.app.goo.gl/CYCKw4MyKTBf2HVY6" target="_blank" rel="noopener noreferrer"
         class="btn-primary">
        <i class="fa-solid fa-map-location-dot"></i>
        Otevřít Google mapu
      </a>

    </div>
  </div>
</section>

@includeIf('sections.testimonials')

{{-- Kudy z nudy --}}
<section class="section bg-mx-black">
  <div class="page-container text-center">
    <div data-aos="fade-up">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">U nás se nudit nebudete</h2>
    </div>
    <p class="text-gray-400 mt-3 mb-6">
      Pokud byste ale hledali jiné aktivity v okolí, tak nejen
      <a href="https://www.kudyznudy.cz/aktivity/pitbike-motokros" target="_blank" class="text-mx-gold hover:underline">pitbike motokros</a>
      najdete na portálu <a href="https://www.kudyznudy.cz/" target="_blank" class="text-mx-gold hover:underline">Kudy z nudy</a>.
    </p>
    <a href="https://www.kudyznudy.cz/?utm_source=kzn&utm_medium=partneri_kzn&utm_campaign=banner" title="Kudyznudy.cz - tipy na výlet" target="_blank">
      <img src="https://www.kudyznudy.cz/getmedia/fdf21e56-ca54-497f-9f83-8f9722a8d129/kzn_bannery-2014_sanoma-970x210.jpg.aspx"
           loading="lazy" width="970" height="210"
           alt="Kudyznudy.cz - tipy na výlet"
           class="max-w-full mx-auto rounded-lg opacity-80 hover:opacity-100 transition-opacity">
    </a>
  </div>
</section>

@endsection

@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

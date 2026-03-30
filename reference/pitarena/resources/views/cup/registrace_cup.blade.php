@extends('layout')
<?php
    $title = null;
    foreach ($sitemaps as $sitemap) {
        if ($sitemap['slug'] == request()->path()) {
            $title = $sitemap['title'];
            $description = $sitemap['description'];
        }
    }
    if (!$title) {
        $title = 'Závody Pitbike-motokros pro profesionály, nadšence i amatéry';
        $description = 'Zaregistruj se do závodů a zažij svět vzrušení, adrenalinu a zábavy. Registrace do YCF cup je spuštěna. Těšíme se na další sezónu plnou nových výzev.';
    }
?>

@section('title', $title)
@section('meta_description', $description)

@section('og')
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $title }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:image" content="https://pitarena.cz/images/YCF_cup_registrace_do_zavodu.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="{{ $title }}">
  <meta name="twitter:description" content="{{ $description }}">
  <meta name="twitter:image" content="https://pitarena.cz/images/YCF_cup_registrace_do_zavodu.jpg">
@endsection

@section('extra_css')
<style>
  html { scroll-behavior: auto; }

  /* ─── Dark theme form overrides ─── */

  /* Category: vertical flex instead of original 2-column grid */
  form .category {
    display: flex; flex-direction: column; gap: 0.5rem;
    width: 100%; margin: 1rem 0 0;
  }
  form .category label {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 0.75rem 1rem; cursor: pointer;
    background: rgba(20,20,32,0.9);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 0.5rem; font-size: 0.875rem; color: #d1d5db;
    transition: border-color 0.2s, background 0.2s;
  }
  form .category label:hover {
    border-color: rgba(206,18,18,0.4);
    background: rgba(206,18,18,0.05);
  }

  /* Radio dot indicator */
  .reg-radio-dot {
    display: inline-block; flex-shrink: 0;
    width: 18px; height: 18px; border-radius: 50%; margin-top: 1px;
    background: rgba(255,255,255,0.12);
    border: 2px solid rgba(255,255,255,0.2);
    transition: background 0.2s, border-color 0.2s;
  }

  /* Dot fill when radio is checked */
  #dot-1:checked ~ .category label[for="dot-1"] .one,
  #dot-2:checked ~ .category label[for="dot-2"] .two,
  #dot-5:checked ~ .category label[for="dot-5"] .five,
  #dot-3:checked ~ .category label[for="dot-3"] .three,
  #dot-4:checked ~ .category label[for="dot-4"] .four {
    background: #ce1212; border-color: rgba(255,255,255,0.45);
  }
  /* Label highlight when radio is checked */
  #dot-1:checked ~ .category label[for="dot-1"],
  #dot-2:checked ~ .category label[for="dot-2"],
  #dot-5:checked ~ .category label[for="dot-5"],
  #dot-3:checked ~ .category label[for="dot-3"],
  #dot-4:checked ~ .category label[for="dot-4"] {
    border-color: rgba(206,18,18,0.5);
    background: rgba(206,18,18,0.07);
    color: #f2f2fa;
  }

  /* Section headings inside the form */
  .reg-section-title {
    font-size: 1.2rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: #f2f2fa;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(206,18,18,0.3);
  }

  /* File input dark styling */
  .form-input.input-file { padding: 0.6rem 1rem; cursor: pointer; }

  /* Disabled state for number input (startovní číslo) */
  .disablingOposite:disabled { opacity: 0.35; cursor: not-allowed; }

  /* ── Custom dark checkboxes (override registration_cup.css grid) ── */
  /* .checkboxes div is the card; input is styled directly via appearance:none */
  .checkboxes {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 0.625rem 1rem; border-radius: 0.5rem;
    border: 1px solid rgba(255,255,255,0.08); background: rgba(20,20,32,0.9);
    transition: border-color 0.15s, background 0.15s; cursor: pointer;
  }
  .checkboxes:hover {
    border-color: rgba(206,18,18,0.35); background: rgba(206,18,18,0.04);
  }
  /* Input IS the visual checkbox square – no hiding tricks */
  .checkboxes input[type="checkbox"] {
    -webkit-appearance: none; appearance: none;
    flex-shrink: 0; width: 20px; height: 20px; margin-top: 2px;
    border-radius: 4px; border: 2px solid rgba(255,255,255,0.25);
    background: transparent no-repeat center / 12px;
    cursor: pointer; transition: background-color 0.15s, border-color 0.15s;
  }
  /* Checked state – red fill + white SVG checkmark */
  .checkboxes input[type="checkbox"]:checked {
    background-color: #ce1212; border-color: #ce1212;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' d='M2 8l4 4 8-8'/%3E%3C/svg%3E");
  }
  /* Card highlight when checked (:has = Chrome 105+, FF 121+, Safari 15.4+) */
  .checkboxes:has(input:checked) {
    border-color: rgba(206,18,18,0.5); background: rgba(206,18,18,0.08);
  }
  /* Label text */
  .checkboxes label {
    color: #d1d5db; font-size: 0.875rem; line-height: 1.5;
    cursor: pointer; user-select: none; flex: 1;
  }
  .checkboxes:has(input:checked) label { color: #f2f2fa; }
  /* Disabled */
  .checkboxes input[type="checkbox"]:disabled { opacity: 0.4; cursor: not-allowed; }
  .checkboxes:has(input:disabled) { opacity: 0.35; cursor: not-allowed; }

  /* Dim submit button while required fields are not filled */
  #registrationCup_form:invalid #loading_cup_registration_form {
    opacity: 0.35;
  }
</style>
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
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ route('cup') }}"><span itemprop="name">Závody</span></a>
        <meta itemprop="position" content="2"/>
      </li>
      <span class="separator"><i class="fa-solid fa-chevron-right text-[9px]"></i></span>
      <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">Registrace</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection


@section('content')

    {{-- errors from form --}}
    @if ($errors->any())
    <div>
        @php
            $writeErrors = implode("\\n", $errors->all());
            echo('<script>alert("Opravte následující chyby ve formuláři:\\n' . $writeErrors . '\\n\\n ------\\nFormulář si z bezpečnostních důvodů neukládá nahranou fotku jezdce, tzn., že ji budete muset znovu nahrát.")<\/script>');
        @endphp
    </div>
    @endif

    {{-- success alert --}}
    @if (session('status'))
    <div x-data="{ show: true }" x-show="show"
         class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-xl px-4">
      <div class="bg-green-900 border border-green-600 text-green-100 rounded-lg shadow-lg p-4 flex justify-between items-start gap-4">
        <div class="text-sm leading-relaxed">{!! session('status') !!}</div>
        <button @click="show = false" class="text-green-300 hover:text-white flex-shrink-0 text-xs border border-green-600 px-2 py-1 rounded">zavřít</button>
      </div>
    </div>
    @endif

    <section class="section-sm bg-mx-dark">
        <div class="page-container max-w-3xl">
          <div class="text-center mb-6">
            <img class="big-logo-ycfcup inline-block" src="{{ asset('images/logo_ycf_cup.png') }}" alt="logo ycf cup">
          </div>

          <div class="text-center" data-aos="fade-up">
            <div class="section-divider section-divider-center mb-4"></div>
            <h1 class="text-3xl font-bold text-mx-white mb-3">Registrace do YCF&nbsp;CUP</h1>
          </div>
          <h2 class="text-xl font-semibold text-gray-300 text-center mb-10" data-aos="fade-up" data-aos-delay="100">
            Pitbike-motokros pro profesionály, nadšence i amatéry
          </h2>

          <div class="max-w-lg mx-auto" data-aos="fade-up" data-aos-delay="200">

            {{-- Závodné --}}
            <p class="section-subtitle mb-3">Závodné</p>
            <div class="grid grid-cols-2 gap-3 mb-1">
              <div class="rounded-xl p-5" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09);">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Jeden závodní den</p>
                <p class="text-3xl font-black text-mx-white">1 200 <span class="text-base font-semibold text-gray-400">Kč</span></p>
              </div>
              <div class="rounded-xl p-5 relative" style="background: rgba(206,18,18,0.08); border: 1px solid rgba(206,18,18,0.35);">
                <span class="absolute -top-2.5 right-4 text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full bg-mx-orange text-white">Doporučujeme</span>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Celá sezóna · 5 závodů</p>
                <p class="text-3xl font-black text-mx-white">5 000 <span class="text-base font-semibold text-gray-400">Kč</span></p>
              </div>
            </div>
            <p class="text-xs text-gray-400 mb-6">* Kategorie "MINI 50": 650 Kč / závod</p>

            {{-- Výhody celé sezóny --}}
            <div class="rounded-xl p-5 mb-8" style="background: rgba(206,18,18,0.05); border: 1px solid rgba(206,18,18,0.18);">
              <p class="text-xs font-semibold uppercase tracking-[0.13em] text-mx-orange mb-3">Při zakoupení celé sezóny</p>
              <ul class="space-y-2">
                <li class="flex items-start gap-2.5 text-sm text-gray-300">
                  <i class="fa-solid fa-check text-mx-orange text-xs mt-1 flex-shrink-0"></i>
                  <span>Ušetříte <strong class="text-mx-white">1 000 Kč</strong></span>
                </li>
                <li class="flex items-start gap-2.5 text-sm text-gray-300">
                  <i class="fa-solid fa-check text-mx-orange text-xs mt-1 flex-shrink-0"></i>
                  <span>Platba na 2 splátky — <strong class="text-mx-white">půlku teď / půlku v létě</strong></span>
                </li>
                <li class="flex items-start gap-2.5 text-sm text-gray-300">
                  <i class="fa-solid fa-check text-mx-orange text-xs mt-1 flex-shrink-0"></i>
                  <span>Zamluvíte si startovní číslo online — <strong class="text-mx-white">dříve než ostatní</strong></span>
                </li>
              </ul>
            </div>

            {{-- Závodní licence --}}
            <p class="section-subtitle mb-3">Závodní licence</p>
            <div class="grid grid-cols-3 gap-3 mb-4">
              <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09);">
                <p class="text-xs text-gray-400 mb-2">Jeden závodní den</p>
                <p class="text-xl font-black text-mx-white">100 <span class="text-sm font-semibold text-gray-400">Kč</span></p>
              </div>
              <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09);">
                <p class="text-xs text-gray-400 mb-2">Celá sezóna · 5 záv.</p>
                <p class="text-xl font-black text-mx-white">500 <span class="text-sm font-semibold text-gray-400">Kč</span></p>
              </div>
              <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09);">
                <p class="text-xs text-gray-400 mb-2">S licencí Moravia cup</p>
                <p class="text-xl font-black text-mx-white">0 <span class="text-sm font-semibold text-gray-400">Kč</span></p>
              </div>
            </div>
            <ul class="space-y-1.5">
              <li class="text-xs text-gray-400 flex items-start gap-2">
                <i class="fa-solid fa-arrows-left-right text-xs mt-0.5 flex-shrink-0"></i>
                Uznáváme celoroční licence Moravia cup a Moravia cup uznává naše licence
              </li>
              <li class="text-xs text-gray-400 flex items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation text-mx-orange text-xs mt-0.5 flex-shrink-0"></i>
                Bez licence nelze nastoupit do závodu
              </li>
            </ul>

          </div>
        </div>
    </section>

      {{-- registrace ycf cup --}}
      <section class="section bg-mx-black">
        <div class="page-container max-w-2xl">

          <div class="text-center mb-10">
            <div data-aos="fade-up">
              <div class="section-divider section-divider-center mb-6"></div>
              <h2 class="text-2xl font-bold text-mx-white mb-3">Registrace 2026</h2>
            </div>
            <p class="text-sm text-gray-400 mb-1" data-aos="fade-up" data-aos-delay="100">
              Povinná pole jsou označena <span class="text-mx-orange font-bold">*</span>
            </p>
            <p class="text-sm text-gray-400 mt-3" data-aos="fade-up" data-aos-delay="150">
              Pokud jste již prošli touto registrací v aktuální sezóně a chcete pouze dokoupit závod nebo licenci, přejděte na
              <a href="{{ route('get.objednavka_cup') }}" class="text-mx-gold underline">Vaše objednávka</a>.
            </p>
          </div>

          <form id="registrationCup_form" action="{{ route('registrace-cup.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- SEKCE: Údaje o jezdci --}}
            <div class="mb-10">
              <h3 class="reg-section-title">Údaje o jezdci</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
                <div class="form-field">
                  <label class="form-label" for="jmeno_jezdce"><span class="text-mx-orange">*</span> Celé jméno jezdce</label>
                  <input id="jmeno_jezdce" class="form-input" type="text" name="jmeno_jezdce" placeholder="např. Bob Brňák" value="{{ old('jmeno_jezdce') }}" required minlength="3" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="datum_narozeni"><span class="text-mx-orange">*</span> Datum narození jezdce</label>
                  <input id="datum_narozeni" class="form-input" type="date" name="datum_narozeni" value="{{ old('datum_narozeni') ? old('datum_narozeni') : '2000-01-01' }}" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="email_jezdce"><span class="text-mx-orange">*</span> Emailová adresa</label>
                  <input id="email_jezdce" class="form-input" type="email" name="email_jezdce" placeholder="např. bob@brno.cz" value="{{ old('email_jezdce') }}" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="telefon_jezdce"><span class="text-mx-orange">*</span> Telefonní číslo</label>
                  <input id="telefon_jezdce" class="form-input" type="tel" name="telefon_jezdce" placeholder="např. 777 123 456" value="{{ old('telefon_jezdce') }}" required minlength="9" maxlength="25" />
                </div>
                <div class="form-field sm:col-span-2">
                  <label class="form-label" for="foto_jezdce">Foto jezdce <span class="normal-case font-normal text-gray-400">(nepovinné – max 50 MB, v závodním oblečení)</span></label>
                  <input id="foto_jezdce" class="form-input input-file" type="file" name="foto_jezdce" accept="image/*" />
                </div>
              </div>
            </div>

            {{-- SEKCE: Fakturační údaje --}}
            <div class="mb-10">
              <h3 class="reg-section-title">Fakturační údaje</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
                <div class="form-field sm:col-span-2">
                  <label class="form-label" for="fakturacni_jmeno">Fakturační jméno <span class="normal-case font-normal text-gray-400">(je-li jiné než jméno jezdce)</span></label>
                  <input id="fakturacni_jmeno" class="form-input" type="text" name="fakturacni_jmeno" placeholder="-" value="{{ old('fakturacni_jmeno') }}" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="ulice"><span class="text-mx-orange">*</span> Ulice a č. popisné</label>
                  <input id="ulice" class="form-input" type="text" name="ulice" placeholder="např. Brněnská 1" value="{{ old('ulice') }}" required minlength="3" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="obec"><span class="text-mx-orange">*</span> Obec</label>
                  <input id="obec" class="form-input" type="text" name="obec" placeholder="např. Brno" value="{{ old('obec') }}" required minlength="2" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="psc"><span class="text-mx-orange">*</span> PSČ</label>
                  <input id="psc" class="form-input" type="text" name="psc" placeholder="např. 664 81" value="{{ old('psc') }}" required minlength="5" maxlength="10" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="firm_name">Název firmy</label>
                  <input id="firm_name" class="form-input" type="text" name="firm_name" value="{{ old('firm_name') }}" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="firm_ico">IČO</label>
                  <input id="firm_ico" class="form-input" type="text" name="firm_ico" value="{{ old('firm_ico') }}" maxlength="100" />
                </div>
              </div>
            </div>

            {{-- SEKCE: Licence --}}
            <div class="licence-details mb-10">
              <input type="radio" name="licence" id="dot-1" value="licence-YCF" {{ old('licence') == 'licence-YCF' ? 'checked' : '' }} required />
              <input type="radio" name="licence" id="dot-2" value="licence-Moravia" {{ old('licence') == 'licence-Moravia' ? 'checked' : '' }} />
              <input type="radio" name="licence" id="dot-5" value="licence-jednodenni" {{ old('licence') == 'licence-jednodenni' ? 'checked' : '' }} />
              <h3 class="reg-section-title">Licence <span class="text-mx-orange">*</span></h3>
              <span id="info-akce" class="highlighted block text-sm text-yellow-400 mt-3 mb-1"></span>
              <div class="category">
                <label for="dot-1">
                  <span class="dot one reg-radio-dot"></span>
                  <span class="licence">Roční licence YCF-cup za 500 Kč</span>
                </label>
                <label for="dot-2">
                  <span class="dot two reg-radio-dot"></span>
                  <span class="licence">Mám již licenci {{ date('Y') }} (Moravia-cup nebo YCF-cup)</span>
                </label>
                <label for="dot-5">
                  <span class="dot five reg-radio-dot"></span>
                  <span class="licence">Jednodenní licence 100 Kč /závod</span>
                  <i class="details required-symbol invalidCombination_1 text-sm" hidden>&nbsp;Neplatná kombinace!!</i>
                </label>
              </div>
            </div>

            {{-- SEKCE: Kategorie --}}
            <div class="mb-10">
              <h3 class="reg-section-title">Kategorie <span class="text-mx-orange">*</span></h3>
              <div class="user-details mt-5">
                <div class="form-field">
                  <label class="form-label" for="kategorie">Závodní kategorie</label>
                  <select name="rider_category_id" id="kategorie" class="form-select" required>
                    <option value="">-- Vyberte kategorii --</option>
                    @isset($riderCategories)
                      @forelse ($riderCategories as $riderCategory)
                        @if ($riderCategory->is_active)
                          <option value="{{ $riderCategory->id }}" {{ old('rider_category_id') == $riderCategory->id ? 'selected' : '' }}>{{ $riderCategory->name }}</option>
                        @endif
                      @empty
                        <option value="1">Obecná kategorie</option>
                      @endforelse
                    @endisset
                  </select>
                  <p class="text-sm text-gray-400 mt-1.5">
                    Rozdělení určuje věk jezdce, typ motoru a velikost kol —
                    <a href="{{ route('cup.kategorie') }}" target="_blank" class="text-mx-gold underline">závodní kategorie</a>
                  </p>
                </div>
              </div>
            </div>

            {{-- SEKCE: Sezóna --}}
            <div class="sezona-details mb-10">
              <input type="radio" name="sezona" id="dot-3" value="cela-sezona-ANO" {{ old('sezona') == 'cela-sezona-ANO' ? 'checked' : '' }} required />
              <input type="radio" name="sezona" id="dot-4" value="cela-sezona-NE" {{ old('sezona') == 'cela-sezona-NE' ? 'checked' : '' }} />
              <h3 class="reg-section-title">Sezóna <span class="text-mx-orange">*</span></h3>
              <div class="category">
                <label for="dot-3">
                  <span class="dot three reg-radio-dot"></span>
                  <span class="sezona">
                    Celá sezóna &nbsp;<strong id="season-price-label" class="text-mx-orange">—</strong>
                    <small class="text-gray-400"> <strong id="season-installments-label"></strong></small>
                    <i class="details required-symbol invalidCombination_1 text-sm" hidden>&nbsp;Neplatná kombinace!!</i>
                  </span>
                </label>
                <label for="dot-4">
                  <span class="dot four reg-radio-dot"></span>
                  <span class="sezona" id="cenaZaZavodSlovne">Jednotlivé závodní dny</span>
                </label>
              </div>
            </div>

            {{-- SEKCE: Splátky + Závody --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-8 mb-10">
              <div>
                <h3 class="reg-section-title mb-5">Splátky</h3>
                <div class="user-details splatky-detail">
                  <div class="checkboxes splatky-detail">
                    <input class="dve_splatky" id="dve_splatky" type="checkbox" name="dve_splatky" value="1" disabled {{ old('dve_splatky') == '1' ? 'checked' : '' }} />
                    <label for="dve_splatky">Budu platit na <strong class="text-mx-white">2 splátky</strong></label>
                  </div>
                  <p class="text-sm text-gray-400 mt-2">Aktivní pouze při výběru celé sezóny.</p>
                </div>
              </div>
              <div>
                <h3 class="reg-section-title mb-5">Vyberte závody</h3>
                <?php
                  if (null !== old('zavod')) {
                    foreach (old('zavod') as $item) {
                      ($item == '11.04.2026 Pravice') ? $zavod1 = 'checked' : '';
                      ($item == '30.05.2026 Smrk') ? $zavod2 = 'checked' : '';
                      ($item == '27.06.2026 Vranov') ? $zavod3 = 'checked' : '';
                      ($item == '26.09.2026 Miroslav') ? $zavod4 = 'checked' : '';
                      ($item == '03.10.2026 Pravice') ? $zavod5 = 'checked' : '';
                    }
                  }
                ?>
                <div class="space-y-3">
                  <div class="checkboxes">
                    <input class="disabling" id="check_1" type="checkbox" name="zavod[]" value="11.04.2026 Pravice" {{ isset($zavod1) ? $zavod1 : '' }} />
                    <label for="check_1">11.04.2026 Pravice</label>
                  </div>
                  <div class="checkboxes">
                    <input class="disabling" id="check_2" type="checkbox" name="zavod[]" value="30.05.2026 Smrk" {{ isset($zavod2) ? $zavod2 : '' }} />
                    <label for="check_2">30.05.2026 Smrk</label>
                  </div>
                  <div class="checkboxes">
                    <input class="disabling" id="check_3" type="checkbox" name="zavod[]" value="27.06.2026 Vranov" {{ isset($zavod3) ? $zavod3 : '' }} />
                    <label for="check_3">27.06.2026 Vranov</label>
                  </div>
                  <div class="checkboxes">
                    <input class="disabling" id="check_4" type="checkbox" name="zavod[]" value="26.09.2026 Miroslav" {{ isset($zavod4) ? $zavod4 : '' }} />
                    <label for="check_4">26.09.2026 Miroslav</label>
                  </div>
                  <div class="checkboxes">
                    <input class="disabling" id="check_5" type="checkbox" name="zavod[]" value="03.10.2026 Pravice" {{ isset($zavod5) ? $zavod5 : '' }} />
                    <label for="check_5">03.10.2026 Pravice</label>
                  </div>
                </div>
              </div>
            </div>

            {{-- SEKCE: Startovní číslo --}}
            <div class="mb-10">
              <h3 class="reg-section-title">Startovní číslo</h3>
              <div class="max-w-xs mt-5">
                <div class="form-field">
                  <label class="form-label" for="startovni_cislo">Číslo (0 – 999)</label>
                  <input class="disablingOposite form-input" id="startovni_cislo" type="number" name="startovni_cislo" value="{{ old('startovni_cislo') }}" min="0" max="999" />
                </div>
                <p class="text-sm text-gray-400 mt-2">Lze zvolit při zakoupení celé sezóny nebo při akční nabídce.</p>
                <p class="text-sm text-mx-orange mt-1">
                  <a href="#cisla" class="underline">Obsazená čísla</a> jsou v tabulce níže.
                </p>
              </div>
            </div>

            {{-- SEKCE: Tým a Motocykl --}}
            <div class="mb-10">
              <h3 class="reg-section-title">Tým a Motocykl</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
                <div class="form-field">
                  <label class="form-label" for="moto_team">Tým</label>
                  <input id="moto_team" class="form-input" type="text" name="moto_team" placeholder="např. YRP" value="{{ old('moto_team') }}" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="moto_brand">Značka motocyklu</label>
                  <input id="moto_brand" class="form-input" type="text" name="moto_brand" placeholder="např. YCF PILOT F125" value="{{ old('moto_brand') }}" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="moto_volume">Obsah motoru</label>
                  <input id="moto_volume" class="form-input" type="text" name="moto_volume" placeholder="např. 160 ccm" value="{{ old('moto_volume') }}" maxlength="100" />
                </div>
                <div class="form-field">
                  <label class="form-label" for="moto_kola">Velikost kol</label>
                  <input id="moto_kola" class="form-input" type="text" name="moto_kola" placeholder="např. 14/12" value="{{ old('moto_kola') }}" maxlength="100" />
                </div>
              </div>
            </div>

            {{-- SEKCE: Poznámka --}}
            <div class="registration-note mb-10">
              <h3 class="reg-section-title">Poznámka</h3>
              <div class="form-field mt-5">
                <label class="form-label" for="poznamka">Poznámka k objednávce</label>
                <textarea class="form-textarea" id="poznamka" name="poznamka" rows="3">{{ old('poznamka') }}</textarea>
              </div>
            </div>

            {{-- GDPR --}}
            <div class="user-details mb-6">
              <div class="checkboxes">
                <input id="check_gdpr" type="checkbox" name="gdpr" value="gdpr" required />
                <label for="check_gdpr">
                  <span class="text-mx-orange font-bold">*</span> Souhlasím se zpracováním osobních údajů podle
                  <a href="{{ asset('files/GDPR.pdf') }}" target="_blank" class="text-mx-gold underline">GDPR</a> a s
                  <a href="{{ route('cup.pravidla') }}" target="_blank" class="text-mx-gold underline">pravidly závodů</a>.
                </label>
              </div>
            </div>

            <div hidden><input id="zaplatit" type="text" name="zaplatit" value="0" /></div>

            {{-- Sticky price + submit bar --}}
            <div class="sticky bottom-4 z-20 mt-6">
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 px-5 py-4 rounded-xl"
                   style="background: rgba(12,12,20,0.97); border: 1px solid rgba(255,255,255,0.13); box-shadow: 0 8px 40px rgba(0,0,0,0.7); backdrop-filter: blur(10px);">
                <div>
                  <p class="text-xs text-gray-400 uppercase tracking-widest mb-0.5">Celková cena</p>
                  <p class="text-sm text-gray-400">Vyberte kategorii, licenci a sezónu</p>
                </div>
                <input id="loading_cup_registration_form"
                       class="btn-primary cursor-pointer text-sm font-bold px-8 py-3 w-full sm:w-auto whitespace-nowrap transition-opacity"
                       type="submit" value="Objednat" />
              </div>
            </div>

          </form>

          <p class="mt-8 text-sm text-gray-400 text-center">
            🆘 Pokud potřebujete s registrací pomoct, zavolejte na
            <a href="tel:+420728697712" class="text-mx-gold underline">728 697 712</a>
          </p>

        </div>
      </section>

      @includeIf('sections.service')

      @includeIf('sections.tableStartNumbers', ['background' => ''])

      @includeIf('sections.akce')

@endsection

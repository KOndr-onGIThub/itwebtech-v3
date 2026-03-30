@extends('layout')

@forelse ($sitemaps as $sitemap)
@if ($sitemap['slug'] == request()->path())
@section('title', $sitemap['title'])
@section('meta_description', $sitemap['description'])
@endif
@empty
@section('title', 'Závody Pitbike-motokros pro profesionály, nadšence i amatéry')
@section('meta_description', 'Jsi již registrovaný do pitbike závodů na aktuální sezónu? Zde si můžeš registraci ověřit a případně si objednat další závody.')
@endforelse

@section('og')
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Vaše Objednávka YCF CUP">
  <meta property="og:description" content="Jsi již registrovaný do pitbike závodů na aktuální sezónu? Zde si můžeš registraci ověřit a případně si objednat další závody.">
  <meta property="og:image" content="https://pitarena.cz/images/YCF_cup_registrace_do_zavodu.jpg">
  <meta property="og:locale" content="cs_CZ">
  <meta property="fb:app_id" content="966242223397117">
  <meta name="twitter:card" content="summary_large_image">
  <meta property="twitter:domain" content="https://pitarena.cz">
  <meta property="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="Vaše Objednávka YCF CUP">
  <meta name="twitter:description" content="Jsi již registrovaný do pitbike závodů na aktuální sezónu? Zde si můžeš registraci ověřit a případně si objednat další závody.">
  <meta name="twitter:image" content="https://pitarena.cz/images/YCF_cup_registrace_do_zavodu.jpg">
@endsection

@section('extra_css')
<style>
  html { scroll-behavior: auto; }

  .reg-section-title {
    font-size: 1.2rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: #f2f2fa;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(206,18,18,0.3);
  }

  /* Disabled inputs in dark theme — subtle opacity, no pointer */
  .form-input:disabled,
  .form-textarea:disabled {
    opacity: 0.65;
    cursor: default;
  }

  /* ─── Dark category / radio labels (licence, sezóna) ─── */
  #registrationCup_form_update .category {
    display: flex; flex-direction: column; gap: 0.5rem;
    width: 100%; margin: 1rem 0 0;
  }
  #registrationCup_form_update .category label {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 0.75rem 1rem; cursor: pointer;
    background: rgba(20,20,32,0.9);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 0.5rem; font-size: 0.875rem; color: #d1d5db;
    transition: border-color 0.2s, background 0.2s;
  }
  #registrationCup_form_update .category label:hover {
    border-color: rgba(206,18,18,0.4);
    background: rgba(206,18,18,0.05);
  }
  #registrationCup_form_update .reg-radio-dot {
    display: inline-block; flex-shrink: 0;
    width: 18px; height: 18px; border-radius: 50%; margin-top: 1px;
    background: rgba(255,255,255,0.12);
    border: 2px solid rgba(255,255,255,0.2);
    transition: background 0.2s, border-color 0.2s;
  }
  #registrationCup_form_update #dot-1:checked ~ .category label[for="dot-1"] .one,
  #registrationCup_form_update #dot-2:checked ~ .category label[for="dot-2"] .two,
  #registrationCup_form_update #dot-5:checked ~ .category label[for="dot-5"] .five,
  #registrationCup_form_update #dot-3:checked ~ .category label[for="dot-3"] .three,
  #registrationCup_form_update #dot-4:checked ~ .category label[for="dot-4"] .four {
    background: #ce1212; border-color: rgba(255,255,255,0.45);
  }
  #registrationCup_form_update #dot-1:checked ~ .category label[for="dot-1"],
  #registrationCup_form_update #dot-2:checked ~ .category label[for="dot-2"],
  #registrationCup_form_update #dot-5:checked ~ .category label[for="dot-5"],
  #registrationCup_form_update #dot-3:checked ~ .category label[for="dot-3"],
  #registrationCup_form_update #dot-4:checked ~ .category label[for="dot-4"] {
    border-color: rgba(206,18,18,0.5);
    background: rgba(206,18,18,0.07);
    color: #f2f2fa;
  }

  /* ─── Dark checkboxes ─── */
  #registrationCup_form_update .checkboxes {
    display: flex; align-items: flex-start; gap: 0.75rem;
    padding: 0.625rem 1rem; border-radius: 0.5rem;
    border: 1px solid rgba(255,255,255,0.08); background: rgba(20,20,32,0.9);
    transition: border-color 0.15s, background 0.15s; cursor: pointer;
  }
  #registrationCup_form_update .checkboxes:hover {
    border-color: rgba(206,18,18,0.35); background: rgba(206,18,18,0.04);
  }
  #registrationCup_form_update .checkboxes input[type="checkbox"] {
    -webkit-appearance: none; appearance: none;
    flex-shrink: 0; width: 20px; height: 20px; margin-top: 2px;
    border-radius: 4px; border: 2px solid rgba(255,255,255,0.25);
    background: transparent no-repeat center / 12px;
    cursor: pointer; transition: background-color 0.15s, border-color 0.15s;
  }
  #registrationCup_form_update .checkboxes input[type="checkbox"]:checked {
    background-color: #ce1212; border-color: #ce1212;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' d='M2 8l4 4 8-8'/%3E%3C/svg%3E");
  }
  #registrationCup_form_update .checkboxes:has(input:checked) {
    border-color: rgba(206,18,18,0.5); background: rgba(206,18,18,0.08);
  }
  #registrationCup_form_update .checkboxes label {
    color: #d1d5db; font-size: 0.875rem; line-height: 1.5;
    cursor: pointer; user-select: none; flex: 1;
  }
  #registrationCup_form_update .checkboxes:has(input:checked) label { color: #f2f2fa; }
  #registrationCup_form_update .checkboxes input[type="checkbox"]:disabled { opacity: 0.4; cursor: not-allowed; }
  #registrationCup_form_update .checkboxes:has(input:disabled) { opacity: 0.35; cursor: not-allowed; }
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
        <span itemprop="name">Objednávka</span>
        <meta itemprop="position" content="3"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

{{-- Errors from form --}}
@if ($errors->any())
<div class="NG_valid_list">
  @php
    $writeErrors = implode("\\n", $errors->all());
    echo('<script>alert("' . $writeErrors) .'")</script>';
  @endphp
</div>
@endif

{{-- Success alert --}}
@if (session('status'))
<div x-data="{ show: true }" x-show="show" x-transition
     class="fixed top-4 right-4 z-50 max-w-sm bg-green-800 border border-green-600 rounded-lg shadow-lg p-4">
  <div class="flex items-start gap-3">
    <i class="fa-solid fa-circle-check text-green-400 mt-0.5 flex-shrink-0"></i>
    <div class="text-sm text-green-100 flex-1">{!! session('status') !!}</div>
    <button @click="show = false" class="text-green-400 hover:text-white ml-2 flex-shrink-0">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>
</div>
@endif

{{-- Heading --}}
<section class="section-sm bg-mx-dark text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h1 class="text-3xl font-bold text-mx-white mb-4">Vaše Objednávka YCF&nbsp;CUP</h1>
    </div>
    <p class="text-gray-300 text-lg">
      Tato stránka vám umožňuje zkontrolovat stav vaší registrace a případně si přidat k vaší registraci další části seriálu.
    </p>
  </div>
</section>

{{-- Návod --}}
<section class="section-sm bg-mx-black">
  <div class="page-container max-w-3xl">
    <div data-aos="fade-up">
      <div class="section-divider mb-4"></div>
      <h2 class="text-xl font-bold text-mx-white mb-4">Návod jak na to:</h2>
    </div>
    <ol class="list-decimal list-inside space-y-3 text-sm text-gray-300">
      <li>Pro přístup k vaší objednávce, budete potřebovat zadat ověřovací kód v části "Zadejte váš ověřovací kód".</li>
      <li>
        Pokud ještě nemáte ověřovací kód, nebo chcete vygenerovat nový kód, vyberte své jméno (ročník) v části "Seznam registrovaných jezdců"
        a stiskněte tlačítko pro vygenerování kódu.
        <br class="mb-1">
        <span class="text-gray-400">– Vygenerovaný kód vám přijde na email, který byl pro daného jezdce zadán při registraci.</span>
        <br>
        <span class="text-gray-400">– Pokud v seznamu nejste, pravděpodobně ještě nejste registrovaný pro danou sezónu. Proveďte registraci nového jezdce
          <a href="{{ route('registrace-cup.index') }}" class="text-mx-gold underline"><span class="required-symbol">klikněte sem</span></a>.
        </span>
      </li>
      <li>Po zadání ověřovacího kódu klikněte na tlačítko "NAČÍST DATA JEZDCE"</li>
      <li>Nyní by se měly načíst informace o vaší registraci na této stránce v části "Přehled aktuálního stavu objednávky".</li>
      <li>Pokud součástí vaší registrace není zatím vše co v letošní sezóně k závodům nabízíme, můžete si cokoli z toho přidat níže v části "Možnost dodatečné objednávky".</li>
    </ol>

    <h3 class="text-base font-semibold text-mx-white mt-8 mb-3">Seznam registrovaných jezdců</h3>
    <div class="list-registrated-riders">
      <div>
        <select name="select_rider" id="select_rider">
          <option value="">-- Vyberte jezdce --</option>
          @if (isset($riders))
            @foreach ($riders as $rider)
              <option value="{{ $rider->id }}">{{ $rider->jmeno_jezdce . ' (' . substr($rider->datum_narozeni, 0, 4) . ')' }}</option>
            @endforeach
          @endif
        </select>
      </div>
      <div>
        <button id="get_verification_cup_code" class="btn-primary" type="button">Vygenerovat kód</button>
      </div>
    </div>

    <h3 class="text-base font-semibold text-mx-white mt-8 mb-3">Zadejte váš ověřovací kód</h3>
    <div class="put-your-code-here">
      <div>
        <input id="verification_code" name="verification_code" type="text" value="" placeholder="-- Ověřovací Kód --">
      </div>
      <div>
        <button id="get_rider_data" name="get-rider-data" class="btn-primary" type="button">Načíst data jezdce</button>
      </div>
    </div>
  </div>
</section>

{{-- Přehled aktuálního stavu objednávky --}}
<section class="section-sm bg-mx-dark text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-4"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-2">Přehled aktuálního stavu objednávky</h2>
    </div>
    <p class="text-gray-400 text-sm mb-8">Pro načtení vašich dat postupujte podle pokynů výše.</p>

    <div class="max-w-2xl mx-auto text-left">
      <form id="" action="">

        {{-- SEKCE: Údaje o jezdci --}}
        <div class="mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Údaje o jezdci</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
            <div class="form-field">
              <label class="form-label" for="jmeno_jezdce_fix">Celé jméno jezdce</label>
              <input class="form-input" type="text" id="jmeno_jezdce_fix" name="jmeno_jezdce_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="datum_narozeni_fix">Datum narození jezdce</label>
              <input class="form-input" type="date" id="datum_narozeni_fix" name="datum_narozeni_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="email_jezdce_fix">Emailová adresa</label>
              <input class="form-input" type="email" id="email_jezdce_fix" name="email_jezdce_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="telefon_jezdce_fix">Telefonní číslo</label>
              <input class="form-input" type="tel" id="telefon_jezdce_fix" name="telefon_jezdce_fix" value="" disabled>
            </div>
            <div class="form-field sm:col-span-2">
              <label class="form-label">Foto jezdce</label>
              <img id="foto_jezdce_fix" name="foto_jezdce_fix" src="" loading="lazy" alt="" class="mt-1 max-h-40 rounded opacity-80">
            </div>
          </div>
        </div>

        {{-- SEKCE: Fakturační údaje --}}
        <div class="mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Fakturační údaje</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
            <div class="form-field">
              <label class="form-label" for="fakturacni_jmeno_fix">Jméno</label>
              <input class="form-input" type="text" id="fakturacni_jmeno_fix" name="fakturacni_jmeno_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="ulice_fix">Ulice a č. popisné</label>
              <input class="form-input" type="text" id="ulice_fix" name="ulice_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="obec_fix">Obec</label>
              <input class="form-input" type="text" id="obec_fix" name="obec_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="psc_fix">PSČ</label>
              <input class="form-input" type="text" id="psc_fix" name="psc_fix" value="{{ old('psc_fix') }}" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="firm_name_fix">Název firmy</label>
              <input class="form-input" type="text" id="firm_name_fix" name="firm_name_fix" value="{{ old('firm_name_fix') }}" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="firm_ico_fix">IČO</label>
              <input class="form-input" type="text" id="firm_ico_fix" name="firm_ico_fix" value="{{ old('firm_ico_fix') }}" disabled>
            </div>
          </div>
        </div>

        {{-- SEKCE: Licence --}}
        <div class="licence-details mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Licence</h3>
          <div class="form-field mt-5">
            <label class="form-label" for="licence_fix">Zvolená licence</label>
            <input class="form-input" type="text" id="licence_fix" name="licence_fix" value="{{ old('licence_fix') }}" disabled>
          </div>
        </div>

        {{-- SEKCE: Sezóna --}}
        <div class="sezona-details mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Sezóna</h3>
          <div class="form-field mt-5">
            <label class="form-label" for="sezona_fix">Zvolená sezóna / závody</label>
            <textarea class="form-textarea" id="sezona_fix" name="sezona_fix" rows="6" disabled>{{ old('sezona_fix') }}</textarea>
          </div>
          <div class="form-field mt-4">
            <label class="form-label" for="dve_splatky_fix"><i>Zvolena platba na splátky?</i></label>
            <input class="form-input" type="text" id="dve_splatky_fix" name="dve_splatky_fix" value="{{ old('dve_splatky_fix') }}" disabled>
          </div>
        </div>

        {{-- SEKCE: Kategorie a číslo --}}
        <div class="mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Kategorie a číslo</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
            <div class="form-field">
              <label class="form-label">Kategorie</label>
              @isset($riderCategories)
                @forelse ($riderCategories as $riderCategory)
                  @if ($riderCategory->is_active)
                    <input hidden class="rider_category_name" name="categoryId_{{ $riderCategory->id }}" value="{{ $riderCategory->name }}" {{ old('rider_category_name') ? old('rider_category_name') : $riderCategory->name }} disabled>
                  @endif
                @empty
                  <span class="text-sm text-gray-400">Kategorie nebyly načteny správně</span>
                @endforelse
              @endisset
            </div>
            <div class="form-field">
              <label class="form-label" for="startovni_cislo_fix">Startovní číslo</label>
              <input class="form-input" type="number" id="startovni_cislo_fix" name="startovni_cislo_fix" value="" disabled>
            </div>
          </div>
        </div>

        {{-- SEKCE: Tým a Motocykl --}}
        <div class="mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Tým a Motocykl</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
            <div class="form-field">
              <label class="form-label" for="moto_team_fix">Tým</label>
              <input class="form-input" type="text" id="moto_team_fix" name="moto_team_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="moto_brand_fix">Značka</label>
              <input class="form-input" type="text" id="moto_brand_fix" name="moto_brand_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="moto_volume_fix">Obsah motoru</label>
              <input class="form-input" type="text" id="moto_volume_fix" name="moto_volume_fix" value="" disabled>
            </div>
            <div class="form-field">
              <label class="form-label" for="moto_kola_fix">Kola</label>
              <input class="form-input" type="text" id="moto_kola_fix" name="moto_kola_fix" value="" disabled>
            </div>
          </div>
        </div>

        {{-- SEKCE: Poznámka --}}
        <div class="registration-note mb-10">
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Poznámka</h3>
          <div class="form-field mt-5">
            <label class="form-label" for="poznamka_fix">Poznámka k objednávce</label>
            <textarea class="form-textarea" id="poznamka_fix" name="poznamka_fix" disabled></textarea>
          </div>
        </div>

        {{-- SEKCE: Stav platby a schválení --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-10">
          <div class="form-field">
            <label class="form-label" for="zaplaceno_fix"><i>Zaplaceno?</i></label>
            <input class="form-input" type="text" id="zaplaceno_fix" name="zaplaceno_fix" value="" disabled>
            <p class="text-xs text-gray-400 mt-1"><i>Může se vyskytnout prodleva několik dní mezi provedením platby a jejím označením jako zaplacené.</i></p>
          </div>
          <div class="form-field">
            <label class="form-label" for="schvalen_fix"><i>Schváleno?</i></label>
            <input class="form-input" type="text" id="schvalen_fix" name="schvalen_fix" value="" disabled>
            <p class="text-xs text-gray-400 mt-1"><i>Kontroluje se například zda jezdec spadá do dané kategorie podle věku, zda proběhla platba, podepsané čestné prohlášení apod.</i></p>
          </div>
        </div>

      </form>
    </div>
  </div>
</section>

{{-- Možnost dodatečné objednávky --}}
<section class="section-sm bg-mx-black text-center">
  <div class="page-container max-w-3xl">
    <div class="text-center" data-aos="fade-up">
      <div class="section-divider section-divider-center mb-6"></div>
      <h2 class="text-2xl font-bold text-mx-white mb-8">Možnost dodatečné objednávky</h2>
    </div>

    <div class="max-w-2xl mx-auto text-left">
      <form id="registrationCup_form_update" action="" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <p class="no-data-loaded-yet text-sm text-gray-400 mb-4">Zatím nebyla načtena data. Postupujte podle pokynů výše.</p>
        <p hidden class="no-more-items-available text-sm text-gray-300">Nemáte zde již nic, co byste mohl/a doobjednat k vaší registraci.<br><br>Děkujeme za vaši účast v závodech YCF CUP.</p>

        {{-- SEKCE: Licence --}}
        <div hidden id="licence_details" class="licence-details mb-10">
          <input type="radio" name="licence" id="dot-1" value="licence-YCF" {{ old('licence')=="licence-YCF" ? 'checked="checked"' : '' }}>
          <input class="licence-moravia" type="radio" name="licence" id="dot-2" value="licence-Moravia" {{ old('licence')=="licence-Moravia" ? 'checked="checked"' : '' }}>
          <input class="licence-jednodenni" type="radio" name="licence" id="dot-5" value="licence-jednodenni" {{ old('licence')=="licence-jednodenni" ? 'checked="checked"' : '' }}>
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Licence</h3>
          <div class="category">
            <label for="dot-1">
              <span class="dot one reg-radio-dot"></span>
              <span class="licence">Chci licenci YCF-cup za 500,-Kč /rok</span>
            </label>
            <label class="licence-moravia" for="dot-2">
              <span class="dot two reg-radio-dot"></span>
              <span class="licence">Mám již licenci {{ date('Y') }} (Moravia-cup nebo YCF-cup)</span>
            </label>
            <label class="licence-jednodenni" for="dot-5">
              <span class="dot five reg-radio-dot"></span>
              <span class="licence">Jednodenní licence 100,- Kč /závod</span>
              <i class="details required-symbol invalidCombination_1 text-sm" hidden>&nbsp;Neplatná kombinace!!</i>
            </label>
          </div>
        </div>

        {{-- SEKCE: Sezóna --}}
        <div hidden id="sezona_details" class="sezona-details mb-10">
          <input type="radio" name="sezona" id="dot-3" value="cela-sezona-ANO" {{ old('sezona')=="cela-sezona-ANO" ? 'checked="checked"' : '' }}>
          <input type="radio" name="sezona" id="dot-4" value="cela-sezona-NE" {{ old('sezona')=="cela-sezona-NE" ? 'checked="checked"' : '' }}>
          <h3 class="reg-section-title" data-aos="fade-left" data-aos-duration="2000">Sezóna</h3>
          <div class="category">
            <label for="dot-3">
              <span class="dot three reg-radio-dot"></span>
              <span class="sezona">Celá sezóna 5.000,-Kč <small class="text-gray-400">(nebo 2x&nbsp;2.500&nbsp;Kč při volbě na splátky níže)</small>
                <i class="details required-symbol invalidCombination_1 text-sm" hidden>&nbsp;Neplatná kombinace!!</i>
              </span>
            </label>
            <label for="dot-4">
              <span class="dot four reg-radio-dot"></span>
              <span class="sezona">Jednotlivé závodní dny</span>
            </label>
          </div>
        </div>

        {{-- SEKCE: Splátky + Závody --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-8 mb-10">
          <div>
            <div hidden id="splatky_detail">
              <h3 class="reg-section-title mb-5">Splátky</h3>
              <div class="checkboxes splatky-detail">
                <input class="dve_splatky" id="dve_splatky" type="checkbox" name="dve_splatky" value="1" disabled {{ old('dve_splatky')=="1" ? 'checked' : '' }}>
                <label for="dve_splatky">Budu platit na <strong class="text-mx-white">2 splátky.</strong></label>
              </div>
              <p class="text-sm text-gray-400 mt-2"><i>Tato možnost je aktivní pouze pokud zvolíte celou sezónu.</i></p>
            </div>
          </div>

          <div>
            @php
              $zavod1 = $zavod2 = $zavod3 = $zavod4 = $zavod5 = '';
              if (null !== old('zavod')) {
                foreach (old('zavod') as $item) {
                  if ($item == '11.04.2026 Pravice') $zavod1 = 'checked';
                  if ($item == '30.05.2026 Smrk')    $zavod2 = 'checked';
                  if ($item == '27.06.2026 Vranov')  $zavod3 = 'checked';
                  if ($item == '26.09.2026 Miroslav') $zavod4 = 'checked';
                  if ($item == '03.10.2026 Pravice') $zavod5 = 'checked';
                }
              }
            @endphp
            <div hidden id="zavody_detail">
              <h3 class="reg-section-title mb-5">Vyberte závody</h3>
              <div class="space-y-3">
                <div class="checkboxes">
                  <input class="disabling check_1" id="check_1" type="checkbox" name="zavod[]" value="11.04.2026 Pravice" {{ $zavod1 }}>
                  <label class="check_1" for="check_1">11.04.2026 Pravice</label>
                </div>
                <div class="checkboxes">
                  <input class="disabling check_2" id="check_2" type="checkbox" name="zavod[]" value="30.05.2026 Smrk" {{ $zavod2 }}>
                  <label class="check_2" for="check_2">30.05.2026 Smrk</label>
                </div>
                <div class="checkboxes">
                  <input class="disabling check_3" id="check_3" type="checkbox" name="zavod[]" value="27.06.2026 Vranov" {{ $zavod3 }}>
                  <label class="check_3" for="check_3">27.06.2026 Vranov</label>
                </div>
                <div class="checkboxes">
                  <input class="disabling check_4" id="check_4" type="checkbox" name="zavod[]" value="26.09.2026 Miroslav" {{ $zavod4 }}>
                  <label class="check_4" for="check_4">26.09.2026 Miroslav</label>
                </div>
                <div class="checkboxes">
                  <input class="disabling check_5" id="check_5" type="checkbox" name="zavod[]" value="03.10.2026 Pravice" {{ $zavod5 }}>
                  <label class="check_5" for="check_5">03.10.2026 Pravice</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- SEKCE: Startovní číslo --}}
        <h3 hidden id="startovni_cislo_title" class="reg-section-title mb-5" data-aos="fade-left" data-aos-duration="2000">Startovní číslo</h3>
        <div hidden id="startovni_cislo_section" class="max-w-xs mb-10">
          <div class="form-field">
            <label class="form-label" for="startovni_cislo_update">Číslo (0 – 999)</label>
            <input class="disablingOposite form-input" id="startovni_cislo_update" type="number" name="startovni_cislo" value="{{ old('startovni_cislo') }}" min="0" max="999">
          </div>
          <p class="text-sm text-gray-400 mt-2"><i>Registrovat si startovní číslo lze pouze při zakoupení celé sezóny.</i></p>
          <p class="text-sm text-gray-400"><i>V tabulce níže na této stránce najdete seznam obsazených startovních čísel.</i></p>
        </div>

        <div hidden><input id="zaplatit" type="text" name="zaplatit" value="0"></div>
        <input hidden id="akce-activated" type="text" value="" />

        {{-- Submit button (shown by JS when there is something to order) --}}
        <input hidden id="loading_cup_registration_form_update"
               class="btn-primary cursor-pointer text-sm font-bold px-8 py-3 w-full mt-4"
               type="submit" value="Objednat" />

      </form>
      <p class="text-sm text-gray-400 mt-6">
        🆘 Pokud potřebujete s objednávkou pomoct, zavolejte na
        <a href="tel:+420728697712" class="text-mx-gold underline">728 697 712</a>
      </p>
    </div>
  </div>
</section>

@includeIf('sections.tableStartNumbers', ['background' => ''])

@includeIf('sections.service')

@endsection

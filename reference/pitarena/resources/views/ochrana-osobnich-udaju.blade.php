@extends('layout')

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
        <span itemprop="name">Ochrana osobních údajů</span>
        <meta itemprop="position" content="2"/>
      </li>
    </ol>
  </div>
</nav>
@endsection

@section('content')

<section class="section bg-mx-black">
  <div class="page-container max-w-3xl">

    <div data-aos="fade-up">
      <div class="section-divider"></div>
      <h1 class="section-title mb-8">Zásady ochrany osobních údajů</h1>
    </div>

    <div class="space-y-8 text-gray-400 text-sm leading-relaxed">

      <div>
        <h5 class="text-mx-white font-semibold text-base mb-2">1. Kdo jsme</h5>
        <p class="mb-2">Správcem osobních údajů je:</p>
        <p class="text-mx-white font-semibold">PITBIKE YCF CUP, z. s.</p>
        <p>Komenského 240, 671 68 Šanov<br>
        IČO: 19227019<br>
        E-mail: <a href="mailto:standa@pitarena.cz" class="text-mx-gold hover:underline">standa@pitarena.cz</a></p>
        <p class="mt-2">Zpracováváme pouze údaje, které nám sami poskytnete – například při objednávce, registraci nebo komunikaci s námi.</p>
      </div>

      <div>
        <h5 class="text-mx-white font-semibold text-base mb-2">2. Jaké údaje zpracováváme</h5>
        <ul class="space-y-1 mb-3">
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> Jméno a příjmení</li>
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> E-mail, telefon</li>
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> Fakturační údaje</li>
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> Údaje z formulářů na webu (například datum narození u závodníků, fotografie, apod.)</li>
        </ul>
        <p>Vaše údaje jsou použity pouze pro účely vyřízení objednávky, komunikaci, evidenci (např. evidence jezdců) a v nezbytných případech také pro marketing (například upozornění na závody nebo změny termínů, apod.).</p>
      </div>

      <div>
        <h5 class="text-mx-white font-semibold text-base mb-2">3. Jak s údaji nakládáme</h5>
        <ul class="space-y-1">
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> <span><strong class="text-mx-white">Neprodáváme</strong> ani <strong class="text-mx-white">nepředáváme</strong> vaše údaje třetím stranám, s výjimkou nezbytných služeb (např. platební brána Comgate, poskytovatel hostingu, e-mailové systémy).</span></li>
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> Údaje uchováváme pouze po dobu nezbytnou k vyřízení objednávky, vedení účetnictví nebo podle zákonné povinnosti.</li>
          <li class="flex gap-2"><i class="fa-solid fa-circle-dot text-mx-orange mt-1 flex-shrink-0"></i> Vaše data chráníme zabezpečeným připojením (https) a přístup k nim mají pouze oprávněné osoby.</li>
        </ul>
      </div>

      <div>
        <h5 class="text-mx-white font-semibold text-base mb-2">4. Vaše práva</h5>
        <ol class="space-y-1 list-decimal list-inside">
          <li>Požádat o přístup ke svým údajům</li>
          <li>Nechat je opravit nebo vymazat</li>
          <li>Omezit jejich zpracování</li>
          <li>Vznášet námitky proti zpracování</li>
          <li>Podat stížnost u <a href="https://www.uoou.cz" target="_blank" class="text-mx-gold hover:underline">Úřadu pro ochranu osobních údajů</a>.</li>
        </ol>
        <p class="mt-2">V případě potřeby nás kontaktujte na <a href="mailto:standa@pitarena.cz" class="text-mx-gold hover:underline">standa@pitarena.cz</a>.</p>
      </div>

      <div>
        <h5 class="text-mx-white font-semibold text-base mb-2">5. Závěrečná ustanovení</h5>
        <p>S těmito podmínkami souhlasíte při odeslání objednávky nebo registraci. Nové verze zásad zveřejníme vždy na této stránce.</p>
        <p class="mt-2 text-mx-white font-semibold">Účinnost od: 16. 6. 2025</p>
      </div>

    </div>
  </div>
</section>

@endsection

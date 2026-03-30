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
        <span itemprop="name">Obchodní podmínky</span>
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
      <h1 class="section-title mb-8">Všeobecné obchodní podmínky</h1>
    </div>

    <div class="space-y-2" x-data="{ open: null }">

      {{-- 1 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '1' ? null : '1'">
          <span class="font-semibold text-mx-white text-sm">1. Úvodní ustanovení</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '1' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '1' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>
                Tento dokument obsahuje všeobecné obchodní podmínky spolku PITBIKE YCF CUP, z. s., IČO: 19227019,
                se sídlem Komenského 240, 671 68 Šanov, zapsaného ve spolkovém rejstříku. Tyto podmínky upravují vzájemná práva a povinnosti
                mezi námi jako poskytovatelem služeb a vámi jako zákazníkem – ať už si od nás kupujete závod, volnou jízdu, kemp, dárkový poukaz a další.
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 2 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '2' ? null : '2'">
          <span class="font-semibold text-mx-white text-sm">2. Objednávka a uzavření smlouvy</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '2' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '2' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>
                Objednávku služeb provádíte přes náš web nebo přes nákupní formulář (např. systém Simpleshop).
                Smlouva je uzavřena okamžikem, kdy obdržíte potvrzení objednávky e-mailem.
                Ceny jsou konečné, včetně všech poplatků.
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 3 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '3' ? null : '3'">
          <span class="font-semibold text-mx-white text-sm">3. Způsoby platby a platební podmínky</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '3' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '3' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>
                Platba probíhá online prostřednictvím platební brány Comgate nebo převodem na účet.
              </p>
              <p class="mt-2 italic text-xs">
                „Online platby pro nás zajišťuje platební brána Comgate. Poskytovatel služby, společnost Comgate a.s. je licencovaná Platební instituce působící pod dohledem České národní banky.
                Platby probíhající skrze platební bránu jsou plně zabezpečeny a veškeré informace jsou šifrovány. Další informace a kontakty na www.comgate.cz."
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 4 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '4' ? null : '4'">
          <span class="font-semibold text-mx-white text-sm">4. Dodací podmínky a termíny plnění</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '4' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '4' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Vstupenky, poukazy a potvrzení o rezervaci zasíláme na váš e-mail. V případě fyzického poukazu se dodání řeší individuálně dle dohody.</p>
              <p class="mt-2">Služby (např. účast na závodě nebo hromadné plánované akce) jsou poskytnuty v termínu uvedeném v popisu služby nebo v objednávce.</p>
              <p class="mt-2">Vyhrazujeme si právo na změnu termínu v případech, kdy je to nezbytné pro zajištění bezpečnosti nebo kvality poskytované služby.
                Jedná se zejména o situace způsobené vyšší mocí (např. extrémní počasí),
                technickými okolnostmi nebo zásahem třetích osob.
                O případné změně budete vždy informováni e-mailem nebo telefonicky v nejkratší možné době po rozhodnutí o změně.
                Pokud vám nebude vyhovovat změněný termín, dohodneme se individuálně na jiném termínu, nebo vám vrátíme vámi zaplacenou částku.
              </p>
              <p class="mt-2">
                Pokud u služby není uvedený přesný datum konání (např. dárkové poukazy k jízdě), znamená to, že termín je potřeba domluvit individuálně <strong class="text-mx-white">nejméně 1 týden předem</strong>
                na emailu standa@pitarena.cz. Termín se stává platným vzájemným odsouhlasením „náš tým a vás jako zákazníka".
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 5 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '5' ? null : '5'">
          <span class="font-semibold text-mx-white text-sm">5. Storno podmínky</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '5' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '5' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Cílem těchto storno podmínek je nastavit férová pravidla, která pomáhají minimalizovat finanční ztráty na straně zákazníků i pořadatele.</p>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Obecné storno podmínky</h6>
              <p class="mb-2">Obecné storno podmínky jsou platné pro všechny služby pokud nejsou pro danou službu specifikovány konkrétněji níže.</p>
              <ul class="space-y-2">
                <li><strong class="text-mx-white">Vážné zdravotní problémy/úraz</strong><br>
                  Při doložení nemožnosti zúčastnit se zaplacené akce z důvodu vážné nemoci nebo úrazu, vracíme 100&nbsp;% ze zaplacené částky. Odůvodnění je nutné zaslat nejpozději v den konání akce.
                  <br><small>Lze doložit potvrzením od lékaře, fotografií, nebo nám prostě upřímně napište co se přihodilo.</small>
                </li>
                <li><strong class="text-mx-white">Postup při stornu</strong><br>
                  Storno žádost musí být zaslána písemně na emailovou adresu standa@pitarena.cz.<br>
                  Datum přijetí emailu se považuje za rozhodující pro určení výše vrácené částky.<br>
                  V žádosti uveďte jednoznačně předmět storna, způsob vaší platby, datum provedení a částku.
                </li>
                <li><strong class="text-mx-white">Další podmínky</strong><br>
                  Případné změny účasti (např. převedení účasti na jinou osobu, nebo jiný termín) jsou možné po dohodě s pořadatelem.<br>
                  Všechny vrácené částky budou zaslány na účet, ze kterého byla platba provedena, do 30 dní od přijetí storno žádosti.
                </li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Závody zakoupené jednotlivě</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">více než 7 dní před konáním</strong> – vracíme 100&nbsp;% po odečtení administrativního poplatku 300 Kč.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">3 až 7 dní před závodem</strong> – vracíme 50&nbsp;% zaplacené částky.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">méně než 3 dny před závodem</strong> nebo při neúčasti bez omluvy – částka se nevrací.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Balíček závodů</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">více než 7 dní před konáním</strong> – vracíme 100&nbsp;% po odečtení poplatku 300 Kč.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Odstoupení od zbývajících závodů – vrácená částka se vypočte poměrně za zbývající závody, od které se odečítá poskytnutá sleva za balíček.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Akademie – tréninkové programy (Fighter, MX‑Start, MX‑Mini)</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Více než 14 dní před začátkem kurzu</strong> – vracíme 50&nbsp;% uhrazené částky.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">5 až 14 dní před zahájením</strong> – vracíme 25&nbsp;%.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Méně než 5 dní před zahájením nebo v průběhu programu</strong> – storno není možné.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Poukazy – individuální programy (MX‑Solo, MX‑Go, MX‑Hobby)</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Více než 7 dní před domluveným termínem</strong> – vracíme 100&nbsp;% po odečtení poplatku 150 Kč.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">3 až 7 dní před termínem</strong> – vracíme 50&nbsp;%.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Méně než 3 dny před termínem</strong> – částka je nevratná.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Pokud ještě nebyl termín stanoven a zákazník žádá o zrušení – vracíme 100&nbsp;% po odečtení poplatku 150 Kč.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Zájmový kroužek „PIT & GO"</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Více než 14 dní před zahájením</strong> – vracíme 75&nbsp;%.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">5 až 14 dní před zahájením</strong> – vracíme 50&nbsp;%.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Méně než 5 dní před zahájením</strong> nebo v průběhu kroužku – storno není možné.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Půjčovna pitbike</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Více než 48 hodin před plánovaným zapůjčením</strong> – vracíme 100&nbsp;% po odečtení poplatku 300 Kč.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">24 až 48 hodin před termínem</strong> – vracíme 50&nbsp;%.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> <strong class="text-mx-white">Méně než 24 hodin před termínem</strong> – platba se nevrací.</li>
              </ul>

              <h6 class="text-mx-white font-semibold mt-4 mb-2">Táborové soustředění - Pitbike Kemp</h6>
              <ul class="space-y-1">
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">více než 60 dní</strong> před začátkem – vracíme celou částku po odečtení poplatku 300 Kč.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">30–59 dní</strong> před začátkem – vracíme 50&nbsp;% z rezervačního voucheru.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">méně než 30 dní</strong> – rezervační voucher je nevratný.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">méně než 7 dní</strong> nebo neúčast bez zrušení – veškeré uhrazené částky jsou nevratné.</li>
                <li><i class="fa-solid fa-chevron-right text-mx-orange mr-1"></i> Zrušení <strong class="text-mx-white">ze strany pořadatele</strong> – vracíme 100&nbsp;% všech uhrazených částek.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {{-- 6 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '6' ? null : '6'">
          <span class="font-semibold text-mx-white text-sm">6. Reklamace zboží a servisu</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '6' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '6' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>
                Na nové a použité motorky, náhradní díly a provedené servisní práce se vztahuje záruka v délce trvání podle platných právních předpisů
                (zpravidla 24 měsíců / 6 měsíců bazarové zboží, není-li uvedeno jinak).<br>
                V případě závady nás kontaktujte bez zbytečného odkladu e-mailem na standa@pitarena.cz.<br>
                Reklamace bude vyřízena nejpozději do 30 dnů od jejího uplatnění.<br>
                Záruka se nevztahuje na vady vzniklé nevhodným používáním, mechanickým poškozením nebo neodborným zásahem.
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 7 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '7' ? null : '7'">
          <span class="font-semibold text-mx-white text-sm">7. Odstoupení od smlouvy</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '7' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '7' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Jako spotřebitel máte právo odstoupit od smlouvy do 14 dnů od uzavření, pokud služba ještě nebyla poskytnuta. Pokud již proběhla (např. jste využili jízdu), nelze od smlouvy odstoupit.</p>
            </div>
          </div>
        </div>
      </div>

      {{-- 8 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '8' ? null : '8'">
          <span class="font-semibold text-mx-white text-sm">8. Ochrana osobních údajů (GDPR)</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '8' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '8' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>Vaše osobní údaje zpracováváme za účelem plnění objednávky, evidence a případné komunikace. Správcem údajů je PITBIKE YCF CUP, z.s., kontaktní e-mail: standa@pitarena.cz.</p>
              <p class="mt-2">Údaje uchováváme jen po dobu nezbytně nutnou, nepředáváme je dalším subjektům (kromě poskytovatelů platebních a technických služeb).</p>
              <p class="mt-2">Máte právo požádat o přístup ke svým údajům, jejich opravu, omezení zpracování nebo výmaz.<br>
                Více informací naleznete na stránce <a href="{{ route('ochrana-osobnich-udaju') }}" class="text-mx-gold hover:underline">Ochrana osobních údajů</a>.
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- 9 --}}
      <div class="card-dark">
        <button class="w-full text-left py-4 px-5 flex justify-between items-center"
          @click="open = open === '9' ? null : '9'">
          <span class="font-semibold text-mx-white text-sm">9. Závěrečná ustanovení</span>
          <i class="fa-solid fa-chevron-down text-mx-orange transition-transform duration-300"
             :class="{ 'rotate-180': open === '9' }"></i>
        </button>
        <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
             :class="open === '9' ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
          <div class="overflow-hidden">
            <div class="px-5 pb-5 text-gray-400 text-sm leading-relaxed border-t border-mx-gray2 pt-4">
              <p>
                Tyto obchodní podmínky jsou účinné od 16.6.2025. Vyhrazujeme si právo je měnit – nové znění vždy zveřejníme na webu.
                V případě dotazů se na nás neváhejte obrátit: standa@pitarena.cz
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection

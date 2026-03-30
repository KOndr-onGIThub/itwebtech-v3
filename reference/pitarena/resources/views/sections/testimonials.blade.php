@php
/**
 * Parametry:
 *   $background  — CSS třída pozadí (např. 'bg-mx-dark' nebo '')
 *   $only        — pole slug klíčů recenzí k zobrazení, nebo null/vynecháno = všechny
 *                  příklad: ['dorota_havlickova', 'sofiya_kudinova', 'jiri_suchna']
 *
 * Přidání nové recenze: vložte blok kamkoli do pole — pořadí v poli = pořadí v carouselu.
 * Smazání recenze: odstraňte celý blok — žádné přečíslovávání.
 * Filtrování: v $only uveďte slug klíče; ty jsou stabilní bez ohledu na přidání/smazání jiných recenzí.
 */
$allTestimonials = [
    'petr_janicek' => [
        'image'    => asset('/images/recenze/Petr_Janicek.png'),
        'alt'      => 'Petr Janíček - avatar autora recenze ziskané z Google maps',
        'text'     => "Syn zde absolvoval kemp. Oba dva jsme nadšeni, přístup trenérů, celkový koncept byl skvělý...<br>I když to máme téměř 200km daleko, plánujeme další trénování právě zde.",
        'cite'     => 'Petr Janíček',
        'link'     => 'https://maps.app.goo.gl/LDZcVmcYp4G3Av1A9',
        'linkText' => 'Celá recenze zde',
    ],
    'roman_psenicka' => [
        'image'    => asset('/images/recenze/Roman_Psenicka.png'),
        'alt'      => 'Roman Pšenička - avatar autora recenze ziskané z Google maps',
        'text'     => "Syn byl na táborovém soustředění a moc si ho pochvaloval. Udělal velký pokrok v technice jízdy, získal návyky a začal se i starat o svojí motorku... <br>Určitě znovu přijedeme na další akce.",
        'cite'     => 'Roman Pšenička',
        'link'     => 'https://maps.app.goo.gl/SzWLXiXWheeAuH2U8',
        'linkText' => 'Celá recenze zde',
    ],
    'dorota_havlickova' => [
        'image'    => 'images/recenze/dorota_havlickova.png',
        'alt'      => 'recenze Pitarény od Dorota Havlíčková',
        'text'     => 'Tuto sobotu jsem absolvovala kurz  MX-GO. Byla to moje první zkušenost s jízdou v terénu a musím říct, že lepší přístup jsem snad nikde nezažila... Maximální spokojenost – můžu opravdu jen doporučit. 👍',
        'cite'     => 'Dorota Havlíčková',
        'link'     => 'https://maps.app.goo.gl/6XEbJEEEGuFs8CQz9',
        'linkText' => 'Celá recenze',
    ],
    'sarka_lahodova' => [
        'image'    => 'images/recenze/sarka_lahodova.png',
        'alt'      => 'recenze Pitarény od Šárka Lahodová',
        'text'     => 'Děkujeme Standovi za skvělý přístup. Dcera Adéla absolvovala MX-GO a byla nadšená! Rozhodně si to chce zopakovat! Za dva dny se naučila víc, než očekávala.',
        'cite'     => 'Šárka Lahodová',
        'link'     => 'https://maps.app.goo.gl/LRd2q2ZCh8vzWvzMA',
        'linkText' => 'Recenze je na Google',
    ],
    'jiri_suchna' => [
        'image'    => asset('/images/recenze/Jiri_Suchna.png'),
        'alt'      => 'Jiřī Suchna - avatar autora recenze ziskané z Google maps',
        'text'     => 'S panem Holcmanem jsem jednal ohledně nákupu motorek poprvé, ale připadalo mi, že ho znám už dlouhou dobu. Všechno mi vysvětlil a dal dobré rady. Zkrátka radost s ním jednat...',
        'cite'     => 'Jiřī Suchna',
        'link'     => 'https://maps.app.goo.gl/AqjSHuWhhBHTGvrx7',
        'linkText' => 'Celá recenze',
    ],
    'jaroslav_vdolecek' => [
        'image'    => asset('/images/recenze/Jaroslav_Vdolecek.png'),
        'alt'      => 'Jaroslav Vdoleček - avatar autora recenze ziskané z Google maps',
        'text'     => '',
        'cite'     => 'Jaroslav Vdoleček',
        'link'     => 'https://maps.app.goo.gl/X2B8dy8ME7K76tmZ7',
        'linkText' => 'recenze je na Google',
    ],
    'veronika_vejtasova' => [
        'image'    => asset('/images/recenze/Veronika_Vejtasova.png'),
        'alt'      => 'Veronika Vejtasová - avatar autora recenze ziskané z Google maps',
        'text'     => 'Můj nejvíc nejlepší moto zážitek. Děkuji Stando.',
        'cite'     => 'Veronika Vejtasová',
        'link'     => 'https://maps.app.goo.gl/cdbirmpmCQcjTpHe6',
        'linkText' => 'recenze je na Google',
    ],
    'tomas_bastl' => [
        'image'    => 'images/recenze/tomas_bastl.png',
        'alt'      => 'recenze Pitarény od Tomáš Bastl',
        'text'     => 'Vždy maximální spokojenost v pitarena 👌 Určitě doporučuji tento areál …',
        'cite'     => 'Tomáš Bastl',
        'link'     => 'https://maps.app.goo.gl/f139PpkfEjsHgffk7',
        'linkText' => 'Recenze je na Google',
    ],
    'bretislav_vejtasa' => [
        'image'    => asset('/images/recenze/Bretislav_Vejtasa.png'),
        'alt'      => 'Břetislav Vejtasa - avatar autora recenze ziskané z Google maps',
        'text'     => 'Prostě super, nádherná trať, skvělý instruktor a majitel. Tady se plní sny a přání děti i rodičů. Jen tak dál.',
        'cite'     => 'Břetislav Vejtasa',
        'link'     => 'https://maps.app.goo.gl/WY3vELuSxpXLbvvx7',
        'linkText' => 'recenze je na Google',
    ],
    'petr_kyjovsky' => [
        'image'    => asset('/images/recenze/Petr_Kyjovsky.png'),
        'alt'      => 'Petr Kyjovsky - avatar autora recenze ziskané z Google maps',
        'text'     => 'Krásná trať,super přístup k lidem který s ježděním teprve začínají,mohu vřele doporučit za mě palec👍 …',
        'cite'     => 'Petr Kyjovsky',
        'link'     => 'https://maps.app.goo.gl/rv9zFfTRVqRaP3XV6',
        'linkText' => 'recenze je na Google',
    ],
    'daniel_martinek' => [
        'image'    => 'images/recenze/daniel_martinek.png',
        'alt'      => 'recenze Pitarény od Daniel Martinek',
        'text'     => 'Naprosto super, všem doporučuji 🤟 …',
        'cite'     => 'Daniel Martinek',
        'link'     => 'https://maps.app.goo.gl/Q3cBCkekBbD6ehL98',
        'linkText' => 'Recenze je na Google',
    ],
    'tomas_trejbal' => [
        'image'    => asset('/images/recenze/Tomas_Trejbal.png'),
        'alt'      => 'Tomáš Trejbal - avatar autora recenze ziskané z Google maps',
        'text'     => "Luxusní trať. Skvělé zázemí. Standa je člověk na svém místě a dělá všechno naplno. <br>Jedno z mála míst, kde svět je ještě v pořádku. <br>Rozhodně jsem tu nebyl naposledy.",
        'cite'     => 'Tomáš Trejbal',
        'link'     => 'https://maps.app.goo.gl/19PhyLBaiDKs1TY37',
        'linkText' => 'recenze je na Google',
    ],
    'milan_sindelar' => [
        'image'    => asset('/images/recenze/Milan_Sindelar.png'),
        'alt'      => 'Milan Sindelar - avatar autora recenze ziskané z Google maps',
        'text'     => "Standa je velký borec se srdíčkem na správném místě. To jak se o nás se svou paní postaral, připravil zázemí na našem soustředění, budeme dlouho vstřebávat. Rád jsem Vás oba poznal, jste skvělí. <br>Šindy.",
        'cite'     => 'Milan Sindelar',
        'link'     => 'https://maps.app.goo.gl/JEzV2fSfEKNc6EQVA',
        'linkText' => 'recenze je na Google',
    ],
    'arnost_chrobak' => [
        'image'    => asset('/images/recenze/Arnost_Chrobak.png'),
        'alt'      => 'Arnošt Chrobák - avatar autora recenze ziskané z Google maps',
        'text'     => 'Skvělý areal který stojí za trenink. 💪 …',
        'cite'     => 'Arnošt Chrobák',
        'link'     => 'https://maps.app.goo.gl/e8dWZFfebMRWD2Uo6',
        'linkText' => 'recenze je na Google',
    ],
    'michal_zavisek' => [
        'image'    => asset('/images/recenze/Michal_Zavisek.png'),
        'alt'      => 'Michal Zavisek - avatar autora recenze ziskané z Google maps',
        'text'     => 'Tři kluci + tři motorky + zapálený trenér Standa = skvěle strávená sobota. Synové si chtěli pitbike vyzkoušet, Standa nám vše půjčil a celý den se nám věnoval...',
        'cite'     => 'Michal Zavisek',
        'link'     => 'https://maps.app.goo.gl/JKrpcrcUXkPCwPKm9',
        'linkText' => 'recenze je na Google',
    ],
    'patrik_krejci' => [
        'image'    => asset('/images/recenze/Patrik_Krejci.png'),
        'alt'      => 'Patrik Krejčí - avatar autora recenze ziskané z Google maps',
        'text'     => 'Paráda,super upravená trať dá se půjčit stroj i vybavení pecka💥 …',
        'cite'     => 'Patrik Krejčí',
        'link'     => 'https://maps.app.goo.gl/tZ3fgVr4pNtw3d5H8',
        'linkText' => 'recenze je na Google',
    ],
    'miroslava_slampova' => [
        'image'    => asset('/images/recenze/Miroslava_Slampova.png'),
        'alt'      => 'Miroslava Šlampová - avatar autora recenze ziskané z Google maps',
        'text'     => "Syn měl domluvený úplně první trening na motorce. Užili jsme si to všichni.😊 <br>Jsou tu super, tratě, pohoda, úžasná příroda a v neposlední řadě lidičky, kteří to dělají srdcem ❣️ …",
        'cite'     => 'Miroslava Šlampová',
        'link'     => 'https://maps.app.goo.gl/9r8ZiN4PZF5TWzmTA',
        'linkText' => 'Celá recenze',
    ],
    'sofiya_kudinova' => [
        'image'    => 'images/recenze/sofiya_kudinova.png',
        'alt'      => 'recenze od Sofiya Kudinova',
        'text'     => 'Lepší zákaznický přístup jsem nezažila👍🏻 Od domlouvání termínu, přes samotnou lekci...<br>Do budoucna si určitě domluvím další lekce a těším se na ně! Můžu jen doporučit👍🏻',
        'cite'     => 'Sofiya Kudinova',
        'link'     => 'https://maps.app.goo.gl/Fk1dpYiFRbsiTRxKA',
        'linkText' => 'Celá recenze zde',
    ],
    'michal_crha' => [
        'image'    => asset('/images/recenze/Michal_Crha.png'),
        'alt'      => 'Michal Crha - avatar autora recenze ziskané z Google maps',
        'text'     => 'Maximální spokojenost.',
        'cite'     => 'Michal Crha',
        'link'     => 'https://maps.app.goo.gl/Q8ERcG2VgYNMJeC38',
        'linkText' => 'recenze je na Google',
    ],
    'ales_hron' => [
        'image'    => asset('/images/recenze/Ales_Hron.png'),
        'alt'      => 'Aleš Hron - fotografie k recenzi ziskaná z Google maps',
        'text'     => "Krásná trať,super ochotni a profesionslni kluci na servise a prodeji! prvotřídní přístup k zakaznikum!!!<br>v akademii je syn i my nadstandardně spokojeni!!!!<br>Když Pit tak jedině sem!!!",
        'cite'     => 'Aleš Hron',
        'link'     => 'https://maps.app.goo.gl/cWFMVL3yJPyk4E236',
        'linkText' => 'recenze je na Google',
    ],
    'lukas_cihal' => [
        'image'    => 'images/recenze/Lukas_Cihal.webp',
        'alt'      => 'Lukas Cihal',
        'text'     => "Pan Holcmann je opravdový profesionál se skvělým přístupem k dětem. S kompletním zázemím servisu, dráhy, vybavením, školením a jeho zkušenostmi je určitě skvělou volbou, pokud začínáte s motokrosem...",
        'cite'     => 'Lukas Cihal',
        'link'     => 'https://goo.gl/maps/GuUUKkAcoJxfKqnr5',
        'linkText' => 'recenze je na Google',
    ],
    'pavel_hrezik' => [
        'image'    => 'images/recenze/pavel_hrezik.png',
        'alt'      => 'recenze od Pavla Hrežíka',
        'text'     => 'Jak 14letá dcera, tak i já s mojí paní jsme zažili báječné dny se Staňou. Odborný lidský a kamarádský přístup a cenné rady nám daly mnoho. Jeho nadšení a energie jsou nakažlivé. Přístup k dětským zájemcům úžasná...',
        'cite'     => 'Pavel Hrežík',
        'link'     => 'https://maps.app.goo.gl/V7YALf55bxywoHir6',
        'linkText' => 'Celá recenze zde',
    ],
    'petra_velebova' => [
        'image'    => 'images/recenze/Petra_Velebova.jpg',
        'alt'      => 'inicialy PV, Petra Velebová',
        'title'    => 'Fotku nemáme. Recenzi jsme dostali emailem.',
        'text'     => "Ještě jednou Vám za kluky moc děkuji!<br>Strašně si to užili a Váš přístup včetně času, který jste nám věnoval byl prostě 🔝 ...",
        'cite'     => 'Petra Velebová',
        'link'     => url('images/recenze/nahled_emailu_od_Petry_Velebpve.jpg'),
        'linkText' => 'Celá recenze zde',
    ],
    'jiri_bergman' => [
        'image'    => 'images/recenze/jiri_bergman.png',
        'alt'      => 'recenze od Jiřího Bergmana',
        'text'     => 'Tuto sobotu premiéra Pitarény v Pravicích. Před jízdou rychlý kurz základní techniky jízdy v terénu s panem Holcmannem a pak hurá kroužit na skvěle upravené trati. I s velkou motorkou jsme si to náramně užili...',
        'cite'     => 'Jiří Bergman',
        'link'     => 'https://maps.app.goo.gl/qf2F8T47LHU53Q2Q8',
        'linkText' => 'Celá recenze zde',
    ],
    'jiri_kohout' => [
        'image'    => 'images/recenze/jiri_kohout.png',
        'alt'      => 'recenze Pitarény od Jiří Kohout',
        'text'     => 'Super trať, skvěle zázemí, majitel borec s duší pro motorky. Se skupinou si u něj děláme soustředění vždy je o nás postaráno se vším všudy. Díky Stáňo a těšíme se na další soustředko',
        'cite'     => 'Jiří Kohout',
        'link'     => 'https://maps.app.goo.gl/TcH43kWeCcEQyoT1A',
        'linkText' => 'Recenze je na Google',
    ],
    'ondrej_kriska' => [
        'image'    => 'images/recenze/ondrej_kriska.jpg',
        'alt'      => 'Ondra Kriška',
        'text'     => 'Jezdíme sem pravidelně a rádi. Super pro děti od 5 let a zařádit můžeme i my starší. Pokud nemáte vlastní stroj, po domluvě vám půjčí.',
        'cite'     => 'Ondřej Kriška',
        'link'     => 'https://goo.gl/maps/CmMiNku2iEuZLHuQ8',
        'linkText' => 'recenze je na Google',
    ],
    'tomas_dostalik' => [
        'image'    => 'images/recenze/tomas_dostalik.jpg',
        'alt'      => 'tomas dostalik',
        'text'     => 'Byl jsem u vás mimo závody jen jednou a musím říct že trénink to byl fakt skvělej hlavně díky panu majiteli se kterým jsme se tam skamarádili. Máte fakt skvělou trať a ještě lepší kolektiv, trať je rychlá a zábavná...',
        'cite'     => 'Tomáš Dostálík',
        'link'     => 'https://www.facebook.com/groups/3073115752907632/permalink/3299489166936955/',
        'linkText' => 'recenze je na FB',
    ],
    'jiri_suchna_2' => [
        'image'    => 'images/recenze/jiri_kohout.png',
        'alt'      => 'recenze',
        'text'     => 'super lidi',
        'cite'     => 'Filding',
        'link'     => 'https://maps.app.goo.gl/RJTCsgwKTP2daQXx8',
        'linkText' => 'Recenze je na Google',
    ],
];

// Filtrování: pokud je předán parametr $only (pole slug klíčů), zobraz jen ty recenze
$testimonials = (isset($only) && is_array($only) && count($only) > 0)
    ? array_intersect_key($allTestimonials, array_flip($only))
    : $allTestimonials;
@endphp

{{-- Testimonials — Swiper --}}
<section class="section {{ isset($background) ? $background : 'bg-mx-black' }}">
  <div class="page-container">

    <div class="text-center mb-8">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Recenze</h2>
      <p class="text-gray-400 mt-1">
        <strong class="text-3xl text-mx-white">4,9 / 5</strong>
        <span class="ml-2 text-sm">(40+ recenzí)</span>
      </p>
    </div>

    <div class="relative">
      <div class="swiper testimonials-swiper" data-autoplay="4000" data-loop="true"
           data-slides-per-view-sm="1.5" data-slides-per-view-md="2" data-slides-per-view-lg="2.5" data-slides-per-view-xl="3">
        <div class="swiper-wrapper pb-10">

          @foreach($testimonials as $t)
          <div class="swiper-slide h-auto">
            <div class="h-full bg-mx-gray border border-mx-gray2 rounded-xl p-5 flex flex-col gap-3">
              {{-- Hvězdičky --}}
              <div class="flex gap-0.5 text-mx-orange text-sm">
                @for($s=0; $s<5; $s++)<i class="fa-solid fa-star"></i>@endfor
              </div>
              {{-- Text --}}
              @if(!empty($t['text']))
              <p class="text-gray-400 text-sm leading-relaxed flex-1">{!! $t['text'] !!}</p>
              @endif
              {{-- Autor --}}
              <div class="flex items-center gap-3 mt-auto pt-3 border-t border-mx-gray2">
                <img
                  src="{{ $t['image'] }}"
                  loading="lazy"
                  alt="{{ $t['alt'] }}"
                  @isset($t['title']) title="{{ $t['title'] }}" @endisset
                  width="44" height="44"
                  class="w-11 h-11 rounded-full object-cover flex-shrink-0"
                />
                <div>
                  <p class="text-sm font-semibold text-mx-white">{{ $t['cite'] }}</p>
                  <a href="{{ $t['link'] }}" target="_blank" rel="noopener"
                     class="text-xs text-mx-orange hover:underline">{{ $t['linkText'] }}</a>
                </div>
              </div>
            </div>
          </div>
          @endforeach

        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="swiper-button-prev !text-mx-orange !-left-2 lg:!-left-4"></div>
      <div class="swiper-button-next !text-mx-orange !-right-2 lg:!-right-4"></div>
    </div>

    @if(empty($hideLeaveReview))
    <p class="text-center text-sm text-gray-400 mt-8">
      Zanechte nám prosím recenzi na
      <a href="https://www.google.com/maps/place/MX%2FENDURO+PRAVICE/@48.8497787,16.372114,17z" target="_blank" class="text-mx-gold underline hover:no-underline">Google</a>,
      nebo <a href="https://www.facebook.com/groups/3073115752907632" target="_blank" class="text-mx-gold underline hover:no-underline">Facebooku</a>.
      Nebo využijte formulář v sekci <a href="{{ route('o-nas') }}" class="text-mx-gold underline hover:no-underline">O NÁS</a>. Děkujeme.
    </p>
    @endif

  </div>
</section>

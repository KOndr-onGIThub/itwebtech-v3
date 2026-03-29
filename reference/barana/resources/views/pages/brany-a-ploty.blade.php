@extends('layouts.app')

@section('title', 'Hliníkové brány a ploty — Jihomoravský kraj | BARANA')
@section('description', 'Prémiové hliníkové brány a ploty na míru — samostatně i v kombinaci s pergolou. Posuvné i dvoukřídlé brány, lamelové a deskové ploty. Montáž v Jihomoravském kraji.')

@section('content')

{{-- BP-01 Hero --}}
<x-sections.hero
    eyebrow="Brány a ploty"
    title="Jednotný styl pro celý váš pozemek"
    subtitle="Prémiové hliníkové brány a ploty — samostatně nebo v dokonalém souladu s pergolou. Vyrábíme na míru pro každý pozemek v Jihomoravském kraji."
    cta-primary-label="Poptejte nezávazně"
    cta-primary-route="kontakt"
    cta-secondary-label="Zobrazit realizace"
    cta-secondary-route="realizace"
    image-path="hero/brany-hero.jpg"
    image-alt="Hliníková brána a plot BARANA"
/>

{{-- BP-01b Standalone callout --}}
<section class="bg-sage-light border-y border-sage/20">
    <div class="container-site py-5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-9 h-9 rounded-full bg-sage flex items-center justify-center shrink-0">
                <x-icon.check class="w-5 h-5 text-white" />
            </div>
            <p class="font-bold text-heading">Bránu nebo plot bez pergoly? Samozřejmě.</p>
        </div>
        <p class="text-body text-sm leading-relaxed">
            Brány a ploty dodáváme jako zcela samostatné řešení — pergola není podmínka, jen možnost. Stačí vědět, co chcete.
        </p>
    </div>
</section>

{{-- BP-02 Úvod --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Proč volit BARANA</span>
                <h2 class="section-title mb-6">Prémiový hliník,<br>nulová údržba, váš styl</h2>
                <p class="text-body leading-relaxed mb-4">
                    Hliníkové brány a ploty BARANA vznikají na míru — ať už jako samostatné řešení pro váš pozemek, nebo v dokonalém souladu s pergolou.
                </p>
                <p class="text-body leading-relaxed mb-4">
                    Žádná rez, žádné natírání, žádná kompromisní kvalita. Korozivzdorný hliník s práškovým lakováním vydrží desítky let bez údržby — bez ohledu na to, jestli k bráně pergolu chcete, nebo ne.
                </p>
                <p class="text-body leading-relaxed">
                    Pokud pergolu plánujete, je výhodou, že vše vychází ze stejného materiálu a barevné palety. Jeden projekt, jeden člověk zodpovědný za výsledek.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-5" data-reveal="fade-right" data-reveal-group>
                @foreach ([
                    ['icon' => 'shield',  'title' => 'Prémiový hliník', 'desc' => 'Korozivzdorný, lehký, pevný. Vydrží generace.'],
                    ['icon' => 'palette', 'title' => 'Váš styl, vaše barvy', 'desc' => 'Široká paleta RAL barev na míru. Sladíme s pergolou, nebo navrhneme samostatně.'],
                    ['icon' => 'remote',  'title' => 'Automatika',      'desc' => 'Pohony NICE, CAME a dalších prémiových výrobců.'],
                    ['icon' => 'check',   'title' => 'Nulová údržba',   'desc' => 'Žádné natírání, žádná rez. Jen příležitostné umytí.'],
                ] as $item)
                    <div class="card p-5 flex flex-col gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage">
                            <x-dynamic-component :component="'icon.' . $item['icon']" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-heading text-sm">{{ $item['title'] }}</h3>
                            <p class="text-muted text-xs mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- BP-03 Kategorie --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Co vyrábíme</span>
            <h2 class="section-title">Brány i ploty — vše na míru</h2>
        </div>
        <div class="grid lg:grid-cols-2 gap-8 mt-12" data-reveal-group>

            {{-- Brány --}}
            <div id="brany" class="card overflow-hidden flex flex-col">
                <x-responsive-image path="brany/brana-2.jpg" alt="Hliníkové brány BARANA — posuvné a dvoukřídlé" class-picture="block w-full" class-img="w-full aspect-video object-cover" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                <div class="p-8 flex-1 flex flex-col">
                    <span class="section-eyebrow">Hliníkové brány</span>
                    <h3 class="text-2xl font-bold text-heading mb-4">Posuvné a dvoukřídlé</h3>
                    <p class="text-body text-sm leading-relaxed mb-5">
                        Posuvná brána šetří místo a pohybuje se tiše i při větru. Dvoukřídlá je elegantní řešení pro reprezentativní vjezdy. Obě provedení dodáváme s prémiovými pohony a dálkovým ovládáním.
                    </p>
                    <ul class="space-y-2.5 mb-6">
                        @foreach (['Posuvné brány — šetří prostor, tiché', 'Dvoukřídlé brány — elegantní vjezd', 'Automatické pohony NICE / CAME', 'Výška a šířka na míru', 'Kódový panel, interkom, videodveřník'] as $item)
                            <li class="flex items-center gap-2.5 text-body text-sm">
                                <x-icon.check class="w-4 h-4 text-sage shrink-0" />
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('kontakt') }}" class="btn btn-outline-sage mt-auto self-start">
                        Poptat bránu
                        <x-icon.arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>

            {{-- Ploty --}}
            <div id="ploty" class="card overflow-hidden flex flex-col">
                <x-responsive-image path="brany/plot-2.jpg" alt="Hliníkové ploty BARANA — lamelové a deskové" class-picture="block w-full" class-img="w-full aspect-video object-cover" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                <div class="p-8 flex-1 flex flex-col">
                    <span class="section-eyebrow">Hliníkové ploty</span>
                    <h3 class="text-2xl font-bold text-heading mb-4">Lamelové a deskové</h3>
                    <p class="text-body text-sm leading-relaxed mb-5">
                        Lamelový plot nabízí soukromí i vzdušnost — lze nastavit hustotu a úhel lamel. Deskový plot je výrazný a moderní. Oba typy vyrobíme v libovolné délce a výšce, ve shodné barvě s branou a pergolou.
                    </p>
                    <ul class="space-y-2.5 mb-6">
                        @foreach (['Lamelový plot — variabilní soukromí', 'Deskový plot — čistý moderní výraz', 'Výška 1,0–2,0 m dle přání', 'Libovolná délka, rohové sloupky', 'Barva dle výběru (sladíme s branou i pergolou)'] as $item)
                            <li class="flex items-center gap-2.5 text-body text-sm">
                                <x-icon.check class="w-4 h-4 text-sage shrink-0" />
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('kontakt') }}" class="btn btn-outline-sage mt-auto self-start">
                        Poptat plot
                        <x-icon.arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- BP-05 Galerie --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Galerie</span>
            <h2 class="section-title">Naše realizace bran a plotů</h2>
        </div>
        <x-sections.gallery-grid
            :images="[
                ['path' => 'brany/brana-1.jpg', 'alt' => 'Posuvná brána antracit, Hodonín'],
                ['path' => 'brany/brana-2.jpg', 'alt' => 'Dvoukřídlá brána bílá, Vyškov'],
                ['path' => 'brany/plot-1.jpg', 'alt' => 'Lamelový plot antracit'],
                ['path' => 'brany/plot-2.jpg', 'alt' => 'Deskový plot moderní design'],
                ['path' => 'realizace/brana-1a.jpg', 'alt' => 'Brána + plot kompletní řešení, Hodonín'],
                ['path' => 'realizace/brana-2a.jpg', 'alt' => 'Brána + lamelový plot, Vyškov'],
                ['path' => 'realizace/kombinace-1b.jpg', 'alt' => 'Pergola + brána + plot, Mikulov'],
                ['path' => 'realizace/kombinace-1c.jpg', 'alt' => 'Kompletní hliníkové řešení, Mikulov'],
            ]"
            :cols="4"
            :cols-md="3"
            :cols-mobile="2"
            gallery="brany-ploty"
        />
    </div>
</section>

{{-- BP-05c Investice --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Orientační ceny</span>
            <h2 class="section-title">Kolik stojí brána nebo plot?</h2>
            <p class="section-sub">Cena závisí na rozměrech, provedení a způsobu ukotvení. Přesnou nabídku zpracujeme po bezplatném zaměření — vždy zdarma a bez závazků.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12" data-reveal-group>

            {{-- Posuvná brána --}}
            <div class="card p-6 flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage mb-4 shrink-0">
                    <x-icon.door-open class="w-5 h-5" />
                </div>
                <p class="font-bold text-heading mb-1">Posuvná brána</p>
                <p class="text-xs text-muted mb-5">Úspora místa, tichý provoz</p>
                <div class="flex items-baseline gap-1 mb-5">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-3xl font-extrabold text-heading tabular-nums leading-none">38 000</span>
                    <span class="text-sm font-semibold text-muted leading-none">Kč</span>
                </div>
                <ul class="space-y-2 flex-1 mb-6 text-sm text-body">
                    @foreach (['Šířka dle přání', 'Pohon NICE / CAME', 'Dálkové ovládání'] as $f)
                        <li class="flex items-start gap-2">
                            <x-icon.check class="w-3.5 h-3.5 text-sage shrink-0 mt-0.5" />{{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto text-sm">Poptat</a>
            </div>

            {{-- Dvoukřídlá brána --}}
            <div class="card p-6 flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage mb-4 shrink-0">
                    <x-icon.door-closed class="w-5 h-5" />
                </div>
                <p class="font-bold text-heading mb-1">Dvoukřídlá brána</p>
                <p class="text-xs text-muted mb-5">Elegantní reprezentativní vjezd</p>
                <div class="flex items-baseline gap-1 mb-5">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-3xl font-extrabold text-heading tabular-nums leading-none">45 000</span>
                    <span class="text-sm font-semibold text-muted leading-none">Kč</span>
                </div>
                <ul class="space-y-2 flex-1 mb-6 text-sm text-body">
                    @foreach (['Šířka dle přání', 'Pohon NICE / CAME', 'Dálkové ovládání'] as $f)
                        <li class="flex items-start gap-2">
                            <x-icon.check class="w-3.5 h-3.5 text-sage shrink-0 mt-0.5" />{{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto text-sm">Poptat</a>
            </div>

            {{-- Lamelový plot --}}
            <div class="card p-6 flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage mb-4 shrink-0">
                    <x-icon.align-justify class="w-5 h-5" />
                </div>
                <p class="font-bold text-heading mb-1">Lamelový plot</p>
                <p class="text-xs text-muted mb-5">Soukromí i vzdušnost</p>
                <div class="flex items-baseline gap-1 mb-5">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-3xl font-extrabold text-heading tabular-nums leading-none">4 500</span>
                    <span class="text-sm font-semibold text-muted leading-none">Kč/m</span>
                </div>
                <ul class="space-y-2 flex-1 mb-6 text-sm text-body">
                    @foreach (['Výška 1,0 – 2,0 m', 'Libovolná délka', 'Barva dle výběru RAL'] as $f)
                        <li class="flex items-start gap-2">
                            <x-icon.check class="w-3.5 h-3.5 text-sage shrink-0 mt-0.5" />{{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto text-sm">Poptat</a>
            </div>

            {{-- Deskový plot --}}
            <div class="card p-6 flex flex-col">
                <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage mb-4 shrink-0">
                    <x-icon.columns-3 class="w-5 h-5" />
                </div>
                <p class="font-bold text-heading mb-1">Deskový plot</p>
                <p class="text-xs text-muted mb-5">Čistý moderní výraz</p>
                <div class="flex items-baseline gap-1 mb-5">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-3xl font-extrabold text-heading tabular-nums leading-none">5 200</span>
                    <span class="text-sm font-semibold text-muted leading-none">Kč/m</span>
                </div>
                <ul class="space-y-2 flex-1 mb-6 text-sm text-body">
                    @foreach (['Výška 1,0 – 2,0 m', 'Libovolná délka', 'Barva dle výběru RAL'] as $f)
                        <li class="flex items-start gap-2">
                            <x-icon.check class="w-3.5 h-3.5 text-sage shrink-0 mt-0.5" />{{ $f }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto text-sm">Poptat</a>
            </div>

        </div>

        <p class="text-center text-xs text-muted mt-8" data-reveal>
            Orientační ceny jsou uvedeny bez DPH a zahrnují výrobu, dopravu a montáž. Cena brány nezahrnuje základy a přípravu elektroinstalace. Přesná nabídka vždy po bezplatném zaměření.
        </p>
    </div>
</section>

{{-- BP-06 CTA --}}
<x-sections.cta-band
    title="Váš pozemek si zaslouží prémiové řešení."
    subtitle="Hliníkové brány a ploty na míru — trvanlivé, bez údržby, přesně podle vašich představ. Připravíme nabídku zcela nezávazně a zdarma."
    btn-label="Poptat bránu nebo plot"
    btn-route="kontakt"
/>

{{-- BP-07 Mini-proces --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Transparentní proces</span>
            <h2 class="section-title">Čtyři kroky k nové bráně nebo plotu</h2>
            <p class="section-sub">Přesně víte, co vás čeká — žádná překvapení, žádné skryté poplatky.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12" data-reveal-group>
            @foreach ([
                [
                    'num'   => '1',
                    'icon'  => 'phone',
                    'title' => 'Napište nebo zavolejte',
                    'desc'  => 'Stačí říct, co chcete — bránu, plot, nebo obojí. Pár fotek je bonus, ne podmínka. Odpovídáme do 24 hodin.',
                    'time'  => 'Do 24 hodin',
                ],
                [
                    'num'   => '2',
                    'icon'  => 'ruler',
                    'title' => 'Zaměříme zdarma',
                    'desc'  => 'Přijedeme na místo, zaměříme prostor a promluvíme si o vašich představách. Bez poplatků za konzultaci.',
                    'time'  => 'Do 5 dní',
                ],
                [
                    'num'   => '3',
                    'icon'  => 'factory',
                    'title' => 'Vyrobíme na míru',
                    'desc'  => 'Vlastní výroba — žádné katalogové polotovary. Každá brána a každý plot vzniká přesně pro vaše místo.',
                    'time'  => '3–6 týdnů',
                ],
                [
                    'num'   => '4',
                    'icon'  => 'truck',
                    'title' => 'Namontujeme a předáme',
                    'desc'  => 'Vlastní tým, práce na klíč. Doba montáže závisí na rozsahu — brána zpravidla jeden den, delší plot i více. Uklidíme po sobě a předáme záruční list.',
                    'time'  => 'Dle rozsahu',
                ],
            ] as $step)
                <div class="card p-6 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="w-10 h-10 rounded-full bg-gold flex items-center justify-center text-white font-extrabold text-base shrink-0">{{ $step['num'] }}</span>
                        <span class="text-xs font-semibold text-sage bg-sage/10 px-3 py-1 rounded-full">{{ $step['time'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-heading text-base mb-1.5">{{ $step['title'] }}</h3>
                        <p class="text-body text-sm leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10" data-reveal>
            <a href="{{ route('kontakt') }}" class="btn btn-primary">
                Poptejte nezávazně
                <x-icon.arrow-right class="w-4 h-4" />
            </a>
            <a href="{{ route('jak-to-probiha') }}" class="btn btn-outline-sage">
                Celý proces podrobně
                <x-icon.arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</section>

{{-- BP-08 FAQ --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site max-w-3xl">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Nejčastější dotazy</span>
            <h2 class="section-title">Co zákazníci řeší nejčastěji</h2>
        </div>
        <x-faq-accordion :items="[
            [
                'question' => 'Jaká je dodací lhůta na bránu nebo plot?',
                'answer'   => 'Standardně 3–6 týdnů od podpisu smlouvy a složení zálohy. Přesný termín závisí na rozsahu projektu a aktuální vytíženosti výroby. Termín vždy uvádíme v závazné nabídce — žádné přibližné odhady.',
            ],
            [
                'question' => 'Musím si připravit základy nebo základ pro sloupky?',
                'answer'   => 'Záleží na situaci. Posuvná brána potřebuje betonový základ pro kolejnici a sloupky. Pokud nemáte přípravy, zajistíme je v rámci montáže. Ploty kotvíme buď do stávající dlažby (chemickými kotvami) nebo do betonových patek. Vše vyhodnotíme při bezplatném zaměření.',
            ],
            [
                'question' => 'Jak funguje automatický pohon — je spolehlivý?',
                'answer'   => 'Používáme pohony NICE a CAME — světové jedničky v oboru automatiky bran. Pohony jsou navrženy na stovky tisíc cyklů, odolají mrazu i dešti a pracují tiše. Každý pohon dodáváme s dálkovým ovladačem a možností přidání kódového panelu nebo interkomu.',
            ],
            [
                'question' => 'Co se stane, když vypadne proud?',
                'answer'   => 'Posuvné i dvoukřídlé brány s pohonem NICE nebo CAME mají nouzový ruční odblokovač — bránu lze otevřít mechanicky i bez elektřiny. Volitelně lze dodat záložní akumulátor pro automatický provoz i při výpadku napájení.',
            ],
            [
                'question' => 'Lze bránu a plot barevně sladit s pergolou?',
                'answer'   => 'Ano, a je to jedna z hlavních výhod BARANA. Vše vyrábíme ze stejného hliníkového profilu, práškujeme stejnou barvou z RAL nebo RAL EFFECT palety. Výsledkem je dokonale sladěný pozemek — bez rozdílných odstínů nebo textury.',
            ],
            [
                'question' => 'Jaká je údržba brány a plotu?',
                'answer'   => 'Minimální. Hliník s práškovým lakem nevyžaduje natírání ani ošetřování. Jednou ročně doporučujeme namazat posuvné části pohonu a odvodnit odtoky v profilech. Pohony doporučujeme servisovat jednou za 3–5 let — podobně jako servis auta.',
            ],
            [
                'question' => 'Potřebuji na bránu nebo plot stavební povolení?',
                'answer'   => 'Ve většině případů ne — oplocení a brána na rodinném pozemku zpravidla nevyžadují povolení ani ohlášení. Výjimkou jsou lokality v CHKO, památkové zóně nebo pozemky v blízkosti komunikace. Při zaměření vám poradíme konkrétně pro váš pozemek.',
            ],
        ]" />
    </div>
</section>

{{-- BP-05b Cross-sell pergola --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Doplňte bránu</span>
                <h2 class="section-title mb-5">Jeden pozemek,<br>jeden dodavatel, jeden styl</h2>
                <p class="text-body leading-relaxed mb-4">
                    Brána a pergola ze stejného hliníku, stejné barvy, od stejného výrobce. Žádné nesladěné detaily, žádné hledání dalšího dodavatele, žádné rozdílné záruční podmínky.
                </p>
                <p class="text-body leading-relaxed mb-6">
                    Pokud pergolu plánujete — nebo jen zvažujete — řeknete nám to při zaměření. Vše navrhneme jako celek.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach ([
                        'Identická barva i povrchová úprava jako vaše brána',
                        'Jeden projekt, jedna smlouva, jedna záruční lhůta',
                        'Kompletní hliníkové řešení celého pozemku',
                        'Bioklimatické lamely — terasa využitelná od dubna do října',
                    ] as $item)
                        <li class="flex items-center gap-3">
                            <x-icon.circle-check-big class="w-5 h-5 text-sage shrink-0" />
                            <span class="text-body text-sm">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('pergoly') }}" class="btn btn-outline-sage">
                    Více o bioklimatických pergolách
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>
            <a href="{{ route('pergoly') }}" class="relative overflow-hidden rounded-2xl group block min-h-80 lg:min-h-100" data-reveal="fade-right">
                <x-responsive-image path="pergoly/nalada-vecer-sklo.jpg" alt="Bioklimatická pergola BARANA sladěná s branou" class-picture="block absolute inset-0 w-full h-full" class-img="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                <div class="absolute inset-0 bg-linear-to-t from-anthracite/90 via-anthracite/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <span class="badge badge--gold mb-3 block w-fit">Nejpopulárnější produkt</span>
                    <h3 class="text-xl font-bold text-white">Bioklimatické pergoly</h3>
                    <p class="text-white/75 text-sm mt-1 mb-3">Ranní káva za deště, letní večeře s přáteli.</p>
                    <span class="inline-flex items-center gap-2 text-gold font-semibold text-sm group-hover:gap-3 transition-all">
                        Zjistit více
                        <x-icon.arrow-right class="w-4 h-4" />
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

@endsection

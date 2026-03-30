@extends('layouts.app')

@section('title', 'Jak to probíhá — od poptávky po montáž | BARANA')
@section('description', 'Transparentní proces bez překvapení. Zjistěte, co vás čeká od první poptávky až po předání hotové pergoly nebo brány. BARANA s.r.o., Jihomoravský kraj.')

@section('content')

{{-- HP-01 Intro hero --}}
<section class="hero hero--sm bg-anthracite">
    <div class="absolute inset-0 bg-linear-to-br from-anthracite to-anthracite-soft opacity-95"></div>
    <div class="hero__content">
        <span class="hero__eyebrow">Transparentní proces</span>
        <h1 class="hero__title">U nás žádná překvapení.</h1>
        <p class="hero__sub">Přesně víte, co vás čeká — od prvního mailu po předání hotového díla. Žádné skryté poplatky, žádné výmluvy, žádné čekání bez odpovědi.</p>
    </div>
</section>

{{-- HP-02 Timeline --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">5 kroků</span>
            <h2 class="section-title">Jak to celé funguje</h2>
            <p class="section-sub">Od prvního kontaktu po hotovou pergolu nebo bránu na vaší zahradě.</p>
        </div>

        <div class="max-w-2xl mx-auto mt-14">
            <x-step-item
                :number="1"
                icon="phone"
                title="Poptávka — do 24 hodin zpět"
                desc="Napište nám nebo zavolejte. Pošlete pár fotek terasy a přibližné rozměry — nic víc nepotřebujeme. Odpovíme do 24 hodin v pracovní dny, obvykle dřív."
                timeframe="Do 24 hodin"
            />
            <x-step-item
                :number="2"
                icon="ruler"
                title="Bezplatné zaměření na místě"
                desc="Přijedeme na místo, zaměříme prostor, promluvíme si o vašich představách. Žádný hodinový honorář, žádné poradenské poplatky — zaměření je vždy zdarma."
                timeframe="Do 5 pracovních dní"
            />
            <x-step-item
                :number="3"
                icon="file-text"
                title="Návrh a cenová nabídka"
                desc="Na základě zaměření připravíme návrh a závaznou cenovou nabídku. Dostanete přesnou cenu — ne rozsah ani orientační odhad. Co je v nabídce, to zaplatíte."
                timeframe="Do 7 pracovních dní"
            />
            <x-step-item
                :number="4"
                icon="factory"
                title="Výroba na míru"
                desc="Po odsouhlasení nabídky a složení zálohy spouštíme výrobu. Pergolu nebo bránu vyrábíme přímo u nás — žádné polotovary, žádné katalogové modely. Průběžně vás informujeme o postupu."
                timeframe="4–8 týdnů"
            />
            <x-step-item
                :number="5"
                icon="truck"
                title="Montáž a předání"
                desc="Montáž provádí náš vlastní tým — ne subdodavatelé. Pracujeme čistě, prostor uklidíme po sobě. Na konci provedeme předávku, ukážeme vám ovládání a předáme záruční list."
                timeframe="1–2 dny"
                :last="true"
            />
        </div>
    </div>
</section>

{{-- HP-03 Co potřebujeme od vás --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="max-w-3xl mx-auto">
            <div data-reveal class="text-center mb-10">
                <span class="section-eyebrow">Než nás kontaktujete</span>
                <h2 class="section-title">Co potřebujeme od vás</h2>
                <p class="section-sub">Méně, než si myslíte. Stačí opravdu málo — zbytek zjistíme při zaměření.</p>
            </div>
            <div class="card p-8 border-l-4 border-gold" data-reveal>
                <ul class="space-y-4">
                    @foreach ([
                        ['Přibližné rozměry prostoru', 'Stačí kroky nebo odhad. Přesné zaměření uděláme my.'],
                        ['Pár fotek místa', 'Z mobilu stačí. Chceme vidět fasádu, okolí a prostor pro pergolu.'],
                        ['Hrubá představa o využití', 'Posezení? Grilování? Krytý vjezd? Pomůže nám navrhnout správné řešení.'],
                        ['Přibližný rozpočet (nepovinné)', 'Pomůže nám nabídnout to nejvhodnější řešení v daném rozsahu.'],
                    ] as [$title, $desc])
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-sage-light flex items-center justify-center text-sage shrink-0 mt-0.5">
                                <x-icon.check class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="font-semibold text-heading text-sm">{{ $title }}</p>
                                <p class="text-muted text-sm mt-0.5">{{ $desc }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- HP-04 Záruky a servis --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Záruky a servis</span>
                <h2 class="section-title mb-6">Po montáži nekončíme</h2>
                <p class="text-body leading-relaxed mb-4">
                    Na každé dílo poskytujeme 5 let záruky. To znamená, že v případě jakéhokoli problému s konstrukcí, pohony nebo povrchem přijedeme a vyřešíme to — bez zbytečných průtahů.
                </p>
                <p class="text-body leading-relaxed mb-8">
                    Na servisní výjezd reagujeme do 48 hodin. Jsme z Jihomoravského kraje — nejezdíme přes půl republiky a čekání netrvá týdny.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="card--sage rounded-2xl p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage">5 let</p>
                        <p class="text-sm text-sage/80 mt-1">Záruky na konstrukci</p>
                    </div>
                    <div class="card--sage rounded-2xl p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage">48 h</p>
                        <p class="text-sm text-sage/80 mt-1">Servisní výjezd</p>
                    </div>
                </div>
            </div>
            <div data-reveal="fade-right">
                <div class="bg-anthracite rounded-2xl p-8 text-white">
                    <x-icon.shield class="w-10 h-10 text-gold mb-5" />
                    <h3 class="text-xl font-bold mb-4">Co záruka pokrývá</h3>
                    <ul class="space-y-3">
                        @foreach ([
                            'Hliníková konstrukce a svary',
                            'Prášková barva (odolnost, přilnavost)',
                            'Elektromotory a pohony lamel',
                            'Automatické pohony bran',
                            'Těsnost při dešti a sněhu',
                        ] as $item)
                            <li class="flex items-center gap-3 text-white/80 text-sm">
                                <x-icon.check class="w-4 h-4 text-gold shrink-0" />
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-6 text-white/50 text-xs">Záruka se nevztahuje na běžné opotřebení, nesprávné použití nebo poškození způsobené třetí stranou.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HP-05 FAQ --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Nejčastější otázky</span>
            <h2 class="section-title">Ptají se nejčastěji</h2>
        </div>
        <div class="max-w-3xl mx-auto mt-10" data-reveal>
            <x-faq-accordion :items="[
                [
                    'question' => 'Jak dlouho trvá celý proces od poptávky po montáž?',
                    'answer'   => 'Průměrně 6–10 týdnů. Zaměření provedeme do 5 pracovních dní, nabídku připravíme do týdne. Samotná výroba trvá 4–8 týdnů podle rozsahu projektu. Montáž pak zabere 1–2 dny.',
                ],
                [
                    'question' => 'Je zaměření a návrh zdarma?',
                    'answer'   => 'Ano, zcela zdarma a bez závazků. Přijedeme, zaměříme, navrhneme a připravíme cenovou nabídku — a to vše bez jakéhokoli poplatku. Pokud se rozhodnete jinak, nic nám nedlužíte.',
                ],
                [
                    'question' => 'Musím být přítomen při zaměření?',
                    'answer'   => 'Ideálně ano — pomůže nám lépe porozumět vašim představám. Pokud to není možné, domluvíme se. V takovém případě nám stačí pár fotek a přibližné rozměry. Podrobnosti dořešíme při návrhu online nebo po telefonu.',
                ],
                [
                    'question' => 'Lze pergolu přidat k existující terase nebo zdi?',
                    'answer'   => 'Ano, pracujeme s oběma variantami — přiléhající pergola (k fasádě) i volně stojící. Při zaměření posoudíme, která varianta je pro váš pozemek vhodná a jak ji nejlépe ukotvit.',
                ],
                [
                    'question' => 'Provádíte montáž i mimo Jihomoravský kraj?',
                    'answer'   => 'Primárně pracujeme v Jihomoravském kraji a přilehlých oblastech. Pro větší projekty nebo pokud jde o kombinaci pergola + brána + plot se domluvíme i na vzdálenější lokality. Napište nám a zjistíme, zda to lze zařídit.',
                ],
                [
                    'question' => 'Jaké barvy jsou dostupné?',
                    'answer'   => 'Ve standardu nabízíme 12 barev ze vzorníku RAL. Nejoblíbenější jsou antracit RAL 7016, bílá RAL 9010 a světlá šedá RAL 7037. Další odstíny jsou dostupné na vyžádání. Nabízíme také strukturované povrchy (mat, písek) a dvoubarevné provedení. Vzorek barvy vám zašleme před objednávkou.',
                ],
                [
                    'question' => 'Co se stane, když mi pergola při bouřce povolí?',
                    'answer'   => 'Nezačne pršet „dovnitř“ — pokud jsou lamely zavřené, pergola je vodotěsná. Při silném větru doporučujeme lamely nechat otevřené, aby vítr prošel. Motorické pohony mají ochranu před přetížením. A pokud přeci jen dojde k problému v záruční době, přijedeme do 48 hodin.',
                ],
                [
                    'question' => 'Potřebuji stavební povolení?',
                    'answer'   => 'Závisí na konkrétní konstrukci a lokalitě. Volně stojící pergola do určité velikosti obvykle povolení nevyžaduje, přiléhající k domu může. Poradíme vám při zaměření a v případě potřeby vám pomůžeme s potřebnou dokumentací.',
                ],
            ]" />
        </div>
    </div>
</section>

{{-- HP-06 CTA --}}
<x-sections.cta-band
    eyebrow="První krok"
    title="Začít je jednodušší, než si myslíte"
    subtitle="Stačí napsat nebo zavolat. Odpovídáme do 24 hodin — bez závazků, bez prodeje po telefonu."
    btn-label="Napsat nebo zavolat"
    btn-route="kontakt"
/>

@endsection

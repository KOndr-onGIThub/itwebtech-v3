@extends('layouts.app')

@section('title', 'O nás — BARANA s.r.o. | Hliníkové pergoly a brány z Jihomoravského kraje')
@section('description', 'Jsme parta řemeslníků z Jihomoravského kraje, kteří věří, že venkovní prostor si zaslouží stejnou péči jako interiér. Navrhujeme, vyrábíme a montujeme vlastními rukami.')

@section('content')

{{-- ON-01 Hero --}}
<section class="hero hero--sm bg-anthracite">
    <div class="absolute inset-0 bg-linear-to-br from-anthracite to-anthracite-soft opacity-95"></div>
    <div class="hero__content">
        <span class="hero__eyebrow">O nás</span>
        <h1 class="hero__title">Děláme to, protože<br>nás to baví.</h1>
        <p class="hero__sub">Jsme malá firma z Jihomoravského kraje. Nesnášíme průměrnou práci, nekupujeme polotovary a nepošleme na váš pozemek cizí montážníky. Každý projekt děláme, jako by byl pro nás.</p>
    </div>
</section>

{{-- ON-02 Příběh --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <div data-reveal="fade-left">
                <div class="relative">
                    <x-responsive-image
                        path="o-nas/team.jpg"
                        alt="Tým BARANA při práci"
                        class-picture="block w-full"
                        class-img="w-full aspect-[4/5] object-cover rounded-2xl shadow-lg"
                        sizes="(min-width: 1024px) 50vw, 100vw"
                        loading="lazy"
                    />
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gold rounded-2xl -z-10 hidden lg:block"></div>
                </div>
            </div>

            <div data-reveal="fade-right">
                <span class="section-eyebrow">Náš příběh</span>
                <h2 class="section-title mb-6">BARANA vznikla z přesvědčení</h2>
                <p class="text-body leading-relaxed mb-4">
                    Venkovní prostor je přirozené prodloužení domu — a zaslouží si stejnou kvalitu, stejnou péči a stejnou pozornost k detailu jako interiér. Jenže na trhu bylo tehdy plno levných řešení ze skladu a drahých firem, kde nevíte, kdo vám opravdu klade pergolu.
                </p>
                <p class="text-body leading-relaxed mb-4">
                    Proto jsme BARANA postavili na třech zásadách: vlastní výroba, vlastní montáž, osobní zodpovědnost. Žádní subdodavatelé, žádné katalogové modely, žádné schování za anonymní zákaznický servis. Víte, kdo za vaším projektem stojí — a koho zavolat, kdyby cokoliv bylo potřeba.
                </p>
                <p class="text-body leading-relaxed">
                    Pracujeme v Jihomoravském kraji a jsme hrdí na to, že se v našich projektech poznáváme. Ne protože bychom dělali vše stejně — ale proto, že každý kus nese rukopis pečlivé práce.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ON-03 Čísla --}}
<section class="section-wrapper--sm bg-warm-white">
    <div class="container-site">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5" data-reveal-group>
            <div class="card p-6 text-center">
                <p class="text-4xl font-extrabold text-sage mb-1">120+</p>
                <p class="text-sm text-muted leading-snug">Dokončených projektů<br>v JMK a okolí</p>
            </div>
            <div class="card p-6 text-center">
                <p class="text-4xl font-extrabold text-sage mb-1">4.9</p>
                <p class="text-sm text-muted leading-snug">★★★★★ hodnocení<br>na Google</p>
            </div>
            <div class="card p-6 text-center">
                <p class="text-4xl font-extrabold text-sage mb-1">5 let</p>
                <p class="text-sm text-muted leading-snug">Záruky na každý<br>projekt</p>
            </div>
            <div class="card p-6 text-center">
                <p class="text-4xl font-extrabold text-sage mb-1">48 h</p>
                <p class="text-sm text-muted leading-snug">Servisní výjezd<br>v záruční době</p>
            </div>
        </div>
    </div>
</section>

{{-- ON-04 Jak pracujeme --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Jak pracujeme</span>
            <h2 class="section-title">Čtyři zásady, které nikdy neporušíme</h2>
        </div>
        <div class="grid sm:grid-cols-2 gap-5 mt-12" data-reveal-group>
            <div class="card p-8 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-2xl bg-sage-light flex items-center justify-center text-sage shrink-0">
                    <x-icon.factory class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-heading text-lg mb-2">Vlastní výroba</h3>
                    <p class="text-body text-sm leading-relaxed">Nekupujeme polotovary ze skladu ani katalogové modely. Každá pergola, každá brána vzniká v naší výrobě — přesně pro váš pozemek, ve vašich rozměrech, ve vaší barvě. Výsledek je prostě jiný.</p>
                </div>
            </div>
            <div class="card p-8 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-2xl bg-sage-light flex items-center justify-center text-sage shrink-0">
                    <x-icon.wrench class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-heading text-lg mb-2">Vlastní montáž</h3>
                    <p class="text-body text-sm leading-relaxed">Montáž provádí výhradně náš vlastní tým — ne subdodavatelé, ne brigádníci. Víme, jak jsme každý kus vyrobili, a montujeme ho odpovídajícím způsobem. Zodpovědnost nekončí ve výrobě.</p>
                </div>
            </div>
            <div class="card p-8 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-2xl bg-sage-light flex items-center justify-center text-sage shrink-0">
                    <x-icon.ruler class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-heading text-lg mb-2">Vždy na míru</h3>
                    <p class="text-body text-sm leading-relaxed">Každý pozemek je jiný — jiný sklon, jiné rozměry, jiná fasáda. Proto k vám přijdeme osobně, zaměříme a navrhneme řešení, které tam skutečně patří. Nikdy nepřizpůsobujeme pozemek naší pergole.</p>
                </div>
            </div>
            <div class="card p-8 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-2xl bg-sage-light flex items-center justify-center text-sage shrink-0">
                    <x-icon.handshake class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-heading text-lg mb-2">Osobní přístup</h3>
                    <p class="text-body text-sm leading-relaxed">Znáte nás jménem. Víte, kdo u vás bude na zaměření, kdo navrhuje a kdo montuje. Jsme dosažitelní, odpovídáme rychle a po montáži nekončíme — jsme tu pět let záruky.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ON-05 Záruky a servis --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Záruky a servis</span>
                <h2 class="section-title mb-6">Po montáži teprve začínáme</h2>
                <p class="text-body leading-relaxed mb-4">
                    Prodávat pergolu není těžké. Udržet zákazníka spokojeného dalších pět let — to je to, na čem nám záleží. Proto poskytujeme pětiletou záruku na celé dílo a servisní výjezd garantujeme do 48 hodin.
                </p>
                <p class="text-body leading-relaxed mb-8">
                    Jsme z Jihomoravského kraje. Nejedeme přes půl republiky a neposíláme na vás cizí servisní firmu. Přijede ten samý tým, který vám pergolu stavěl.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach ([
                        '5 let záruky na konstrukci, pohony i prášková barva',
                        'Servisní výjezd do 48 hodin v záruční době',
                        'Znáte nás — přijede stejný tým',
                        'Servis i po záruční době za transparentní ceny',
                    ] as $item)
                        <li class="flex items-start gap-3">
                            <x-icon.circle-check-big class="w-5 h-5 text-sage shrink-0 mt-0.5" />
                            <span class="text-body text-sm">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div data-reveal="fade-right">
                <div class="bg-anthracite rounded-2xl p-8 text-white">
                    <x-icon.shield class="w-10 h-10 text-gold mb-5" />
                    <h3 class="text-xl font-bold mb-2">Co záruka pokrývá</h3>
                    <p class="text-white/60 text-sm mb-5">Na každý projekt vydáváme záruční list. Záruka se vztahuje na:</p>
                    <ul class="space-y-3">
                        @foreach ([
                            'Hliníková konstrukce a svary',
                            'Prášková barva — odolnost i přilnavost',
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
                    <p class="mt-6 text-white/40 text-xs">Záruka se nevztahuje na běžné opotřebení, nesprávné použití nebo poškození způsobené třetí stranou.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ON-06 Realizace teaser --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10" data-reveal>
            <div>
                <span class="section-eyebrow">Naše práce mluví za nás</span>
                <h2 class="section-title">Podívejte se na realizace</h2>
            </div>
            <a href="{{ route('realizace') }}" class="btn btn-outline-sage shrink-0">
                Zobrazit všechny realizace
                <x-icon.arrow-right class="w-4 h-4" />
            </a>
        </div>
        <x-sections.gallery-grid
            :images="[
                ['path' => 'realizace/kombinace-1a.jpg', 'alt' => 'Pergola + brána sladěné, Mikulov'],
                ['path' => 'realizace/pergola-1a.jpg',   'alt' => 'Bioklimatická pergola Brno-Líšeň'],
                ['path' => 'realizace/brana-1a.jpg',     'alt' => 'Hliníková brána + plot, Hodonín'],
                ['path' => 'realizace/pergola-3a.jpg',   'alt' => 'Pergola u bazénu, Břeclav'],
            ]"
            :cols="4"
            :cols-md="4"
            :cols-mobile="2"
            gallery="o-nas-preview"
        />
    </div>
</section>

{{-- ON-07 CTA --}}
<x-sections.cta-band
    eyebrow="Poznejte nás osobně"
    title="Přijedeme se podívat. Zdarma."
    subtitle="Nejlepší způsob, jak nás poznat, je zavolat nebo napsat. Přijdeme na zaměření, ukážeme vzorky a promluvíme si — bez závazků, bez prodeje po telefonu."
    btn-label="Kontaktovat BARANA"
    btn-route="kontakt"
/>

@endsection

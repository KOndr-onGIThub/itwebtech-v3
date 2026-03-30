@extends('layouts.app')

@section('title', 'BARANA — Bioklimatické pergoly, brány a ploty | Jihomoravský kraj')
@section('description', 'Vytvořte si místo, kde budete chtít trávit každý večer. Prémiové hliníkové pergoly, brány a ploty na míru v Jihomoravském kraji. Navrhujeme, vyrábíme, montujeme.')

@section('content')

{{-- W-HP02 Hero --}}
<x-sections.hero
    title="Místo, kde budete chtít trávit každý večer"
    subtitle="Bioklimatické pergoly, brány a ploty z hliníku na míru. Jihomoravský kraj — navrhujeme, vyrábíme, montujeme."
    cta-primary-label="Poptejte nezávazně"
    cta-primary-route="kontakt"
    cta-secondary-label="Prohlédnout realizace"
    cta-secondary-route="realizace"
    image-path="hero/pergola-hero.jpg"
    image-alt="Bioklimatická pergola BARANA — terasa s výhledem"
/>

{{-- W-HP03 Trust bar --}}
<section class="trust-bar">
    <div class="trust-bar__inner container-site">
        <div class="trust-bar__grid" data-reveal-group>
            <div>
                <p class="trust-bar__item-value" data-counter>4.9</p>
                <p class="trust-bar__item-label">★★★★★ Google hodnocení</p>
            </div>
            <div>
                <p class="trust-bar__item-value" data-counter>120+</p>
                <p class="trust-bar__item-label">Spokojených zákazníků</p>
            </div>
            <div>
                <p class="trust-bar__item-value">JMK</p>
                <p class="trust-bar__item-label">Jihomoravský kraj<br>a okolí</p>
            </div>
            <div>
                <p class="trust-bar__item-value">5 let</p>
                <p class="trust-bar__item-label">Záruky na každý projekt</p>
            </div>
        </div>
    </div>
</section>

{{-- W-HP03b Urgency strip --}}
@php
    $month = (int) date('n');
    $year  = $month >= 9 ? (int) date('Y') + 1 : (int) date('Y');
    $urgencyMap = [
        1  => 'Plánujete na jaro? Jarní termíny ' . $year . ' se začínají obsazovat — rezervujte s předstihem.',
        2  => 'Jaro ' . $year . ' — volné termíny se rychle plní. Poptejte nyní.',
        3  => 'Jaro ' . $year . ' — volné termíny se rychle plní. Poptejte nyní.',
        4  => 'Jaro ' . $year . ' — zbývají poslední volné termíny montáže.',
        5  => 'Jaro ' . $year . ' — zbývají poslední volné termíny montáže.',
        6  => 'Letní termíny se plní — poptejte ještě dnes a zajistěte si místo.',
        7  => 'Letní termíny se plní — poptejte ještě dnes a zajistěte si místo.',
        8  => 'Plánujete na příští sezónu? Zajistěte si termín jako první.',
        9  => 'Plánujete na jaro ' . $year . '? Termíny se začínají obsazovat.',
        10 => 'Plánujete na jaro ' . $year . '? Termíny se začínají obsazovat.',
        11 => 'Jarní termíny ' . $year . ' se začínají obsazovat — rezervujte s předstihem.',
        12 => 'Jarní termíny ' . $year . ' se začínají obsazovat — rezervujte s předstihem.',
    ];
@endphp
<div class="urgency-strip">
    <div class="urgency-strip__inner container-site">
        <span class="urgency-strip__dot" aria-hidden="true"></span>
        {{ $urgencyMap[$month] }}
        <a href="{{ route('kontakt') }}" class="urgency-strip__cta">Zjistit volné termíny →</a>
    </div>
</div>

{{-- W-HP04 Přehled produktů --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Naše řešení</span>
            <h2 class="section-title">Hliník pro celý váš pozemek</h2>
            <p class="section-sub">Jeden materiál, jeden dodavatel, jeden jednotný styl — od pergoly přes bránu až po plot.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-5 mt-12" data-reveal-group>

            {{-- Pergoly — dominantní karta --}}
            <a href="{{ route('pergoly') }}" class="md:col-span-2 relative overflow-hidden rounded-2xl group block min-h-[280px] md:min-h-[420px]">
                <x-responsive-image path="hero/pergola-pohori.webp" alt="Bioklimatická pergola BARANA" class-picture="block absolute inset-0 w-full h-full" class-img="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" sizes="(min-width: 1024px) 66vw, 100vw" loading="lazy" />
                <div class="absolute inset-0 bg-gradient-to-t from-anthracite/95 via-anthracite/60 to-anthracite/10"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-6 lg:p-8">
                    <span class="badge badge--gold mb-3 self-start">Nejpopulárnější</span>
                    <h3 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight text-shadow-sm">Bioklimatické pergoly</h3>
                    <p class="text-white/90 mt-2 text-sm leading-relaxed max-w-md text-shadow-sm">Ranní káva za deště, letní večeře s přáteli, odpočinek bez kompromisů. Pergola, která se přizpůsobí vašemu počasí i náladě.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-gold font-semibold text-sm group-hover:gap-3 transition-all">
                        Více o pergolách
                        <x-icon.arrow-right class="w-4 h-4" />
                    </span>
                </div>
            </a>

            {{-- Brány a ploty --}}
            <a href="{{ route('brany-a-ploty') }}" class="relative overflow-hidden rounded-2xl group block min-h-[220px]">
                <x-responsive-image path="hero/brany-hero.jpg" alt="Hliníkové brány a ploty BARANA" class-picture="block absolute inset-0 w-full h-full" class-img="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" sizes="(min-width: 1024px) 33vw, 100vw" loading="lazy" />
                <div class="absolute inset-0 bg-gradient-to-t from-anthracite/95 via-anthracite/60 to-anthracite/10"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-6">
                    <h3 class="text-xl font-extrabold text-white tracking-tight text-shadow-sm">Brány a ploty</h3>
                    <p class="text-white/90 mt-1.5 text-sm leading-relaxed text-shadow-sm">Sladěné s pergolou, bez údržby, na celý život.</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-gold font-semibold text-sm group-hover:gap-3 transition-all">
                        Více →
                    </span>
                </div>
            </a>

        </div>
    </div>
</section>

{{-- W-HP05 Benefity spotlight --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Proč BARANA</span>
            <h2 class="section-title">Tři věci, které oceníte hned</h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-5 mt-12" data-reveal-group>
            <x-benefit-card
                icon="layers"
                title="Hliník bez starostí"
                desc="Žádné natírání, žádná rez, žádná údržba. Hliník vypadá po deseti letech stejně jako první den — zatímco dřevo a plast boj s časem prohrávají."
            />
            <x-benefit-card
                icon="wrench"
                title="Montáž na klíč"
                desc="Přijdeme, zaměříme, navrhneme, vyrobíme a namontujeme. Vy se nestaráte o nic — jen se těšíte na výsledek."
            />
            <x-benefit-card
                icon="ruler"
                title="Navrhujeme na míru"
                desc="Každý pozemek je jiný. Proto nikdy nepoužíváme katalogové rozměry — každá pergola, každá brána je navržena přesně pro vás."
            />
        </div>
    </div>
</section>

{{-- W-HP06 Ukázka realizací --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10" data-reveal>
            <div>
                <span class="section-eyebrow">Realizace</span>
                <h2 class="section-title">Podívejte se na naši práci</h2>
            </div>
            <a href="{{ route('realizace') }}" class="btn btn-outline-sage shrink-0">
                Zobrazit všechny realizace
                <x-icon.arrow-right class="w-4 h-4" />
            </a>
        </div>

        <x-sections.gallery-grid
            :images="[
                ['path' => 'realizace/pergola-1a.jpg', 'alt' => 'Bioklimatická pergola Brno'],
                ['path' => 'realizace/kombinace-1a.jpg', 'alt' => 'Pergola + brána Mikulov'],
                ['path' => 'realizace/brana-1a.jpg', 'alt' => 'Hliníková brána Hodonín'],
                ['path' => 'realizace/pergola-2a.jpg', 'alt' => 'Volně stojící pergola Znojmo'],
            ]"
            :cols="4"
            :cols-md="4"
            :cols-mobile="2"
            gallery="homepage-preview"
        />
    </div>
</section>

{{-- W-HP07 O nás --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <div data-reveal="fade-left">
                <div class="relative">
                    <x-responsive-image path="o-nas/team.jpg" alt="Tým BARANA při práci" class-picture="block w-full" class-img="w-full aspect-[4/5] object-cover rounded-2xl shadow-lg" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gold rounded-2xl -z-10 hidden lg:block"></div>
                </div>
            </div>

            <div data-reveal="fade-right">
                <span class="section-eyebrow">O nás</span>
                <h2 class="section-title mb-6">Jsme parta řemeslníků,<br>kteří milují dobrou práci</h2>
                <p class="text-body leading-relaxed mb-4">
                    BARANA vznikla z přesvědčení, že venkovní prostor je přirozené prodloužení domu — a zaslouží si stejnou péči a kvalitu. Pracujeme s prémiovým hliníkem, navrhujeme každý projekt od začátku a montujeme vlastními rukami.
                </p>
                <p class="text-body leading-relaxed mb-8">
                    Jsme z Jihomoravského kraje a pracujeme napříč celým regionem. Naši zákazníci vědí, kdo za jejich pergolou stojí — a koho zavolat, kdyby cokoliv bylo potřeba.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach ([
                        'Každý projekt navrhujeme osobně a na míru',
                        'Vlastní výroba — žádné prostředníky',
                        'Montáž vlastním týmem, ne subdodavateli',
                        '5 let záruky, servis do 48 hodin',
                    ] as $item)
                        <li class="flex items-start gap-3">
                            <x-icon.circle-check-big class="w-5 h-5 text-sage shrink-0 mt-0.5" />
                            <span class="text-body text-sm">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('jak-to-probiha') }}" class="btn btn-outline-sage">
                    Jak to u nás probíhá
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>

        </div>
    </div>
</section>

{{-- W-HP07b Partneři a certifikace --}}
<div class="partners-bar">
    <div class="partners-bar__inner container-site">
        <p class="partners-bar__label">Pracujeme s prémiovými materiály a pohony</p>
        <div class="partners-bar__logos grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-x-8 w-full" data-reveal-group>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
                NICE pohony
            </span>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                CAME automatika
            </span>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/></svg>
                RAL barevná paleta
            </span>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Prémiový hliník
            </span>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Záruka 5 let
            </span>
            <span class="partners-bar__item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Vlastní výroba CZ
            </span>
        </div>
    </div>
</div>

{{-- W-HP08 Recenze + CTA --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Recenze</span>
            <h2 class="section-title">Co říkají naši zákazníci</h2>
            <p class="section-sub">
                Hodnocení 4.9/5 na Google na základě desítek ověřených recenzí.
                {{-- Nahraďte URL níže přímým odkazem na váš Google Business profil / recenze --}}
                <a href="https://www.google.com/maps/search/?api=1&query=BARANA+s.r.o.+Březí" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sage font-semibold underline underline-offset-2 hover:text-gold transition-colors ml-1">
                    Číst všechny recenze
                    <x-icon.arrow-right class="w-3.5 h-3.5" />
                </a>
            </p>
        </div>

        <div class="grid sm:grid-cols-3 gap-5 mt-12" data-reveal-group>
            <x-review-card
                name="Jana Horáková"
                :rating="5"
                text="Pergola nám doslova změnila způsob trávení volného času. Celé léto jsme jedli večeře venku — i při dešti. Práce proběhla přesně podle dohody, bez jediného překvapení."
                date="září 2024"
            />
            <x-review-card
                name="Tomáš Řezníček"
                :rating="5"
                text="Objednal jsem pergolu i sladěnou bránu. Výsledek je úžasný — celý pozemek dostál úplně jiný charakter. Oceňuji, že se přišel osobně podívat na místo."
                date="červen 2024"
            />
            <x-review-card
                name="Petra Vlčková"
                :rating="5"
                text="Kvalitní práce, rychlá komunikace a výsledek, který předčil naše očekávání. Po třech letech na pergole neshledáváme jediný nedostatek."
                date="říjen 2023"
            />
        </div>

        <div class="text-center mt-14" data-reveal>
            <p class="text-muted text-sm mb-6">Poptávka je nezávazná a zdarma. Odpovídáme do 24 hodin.</p>
            <a href="{{ route('kontakt') }}" class="btn btn-primary btn-lg">
                Poptejte nezávazně
                <x-icon.arrow-right class="w-5 h-5 shrink-0" />
            </a>
        </div>
    </div>
</section>

{{-- W-HP09 Jak to probíhá (mini) --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Transparentní proces</span>
            <h2 class="section-title">Od poptávky po hotovou pergolu ve 4 krocích</h2>
            <p class="section-sub">Přesně víte, co vás čeká — žádná překvapení, žádné skryté poplatky.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12" data-reveal-group>
            @foreach ([
                [
                    'num'   => '1',
                    'icon'  => 'phone',
                    'title' => 'Napište nebo zavolejte',
                    'desc'  => 'Pošlete pár fotek a přibližné rozměry. Odpovídáme do 24 hodin v pracovní dny.',
                    'time'  => 'Do 24 hodin',
                ],
                [
                    'num'   => '2',
                    'icon'  => 'ruler',
                    'title' => 'Zaměříme zdarma',
                    'desc'  => 'Přijedeme na místo, zaměříme prostor a promluvíme si o vašich představách. Bez poplatků.',
                    'time'  => 'Do 5 dní',
                ],
                [
                    'num'   => '3',
                    'icon'  => 'factory',
                    'title' => 'Vyrobíme na míru',
                    'desc'  => 'Vlastní výroba — žádné katalogové modely ani polotovary. Průběžně vás informujeme o postupu.',
                    'time'  => '4–8 týdnů',
                ],
                [
                    'num'   => '4',
                    'icon'  => 'truck',
                    'title' => 'Namontujeme a předáme',
                    'desc'  => 'Vlastní tým, práce na klíč. Uklidíme po sobě a předáme se záručním listem.',
                    'time'  => '1–2 dny',
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

@endsection

@extends('layouts.app')

@section('title', __('price.meta.title'))
@section('description', __('price.meta.description'))

{{-- OND-137 P4 §SEO: Service JSON-LD per tier + BreadcrumbList.
     JSON-LD pole se sestavují v PHP bloku a echují přes
     předvypočtenou stringovou proměnnou. Inline schema-context klíč
     uvnitř json_encode echo bloku naráží na Blade direktivu
     (Laravel 12 CompilesContexts) — token by se přepsal na PHP kód
     a JSON klíč by byl zničený. PHP blok Blade neparsuje na direktivy. --}}
@push('jsonld')
@php
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('layout.nav.home'),  'item' => lroute('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('layout.nav.price'), 'item' => lroute('price')],
        ],
    ];
    $breadcrumbJson = json_encode($breadcrumbLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // OND-354: `offers` z JSON-LD odešlo spolu s klíčem `price`. Úrovně už
    // cenu nenesou — jediná cena na stránce je prahové číslo a rozpětí ve
    // větě `price.intro`, a to není nabídka jedné úrovně. Vymýšlet číslo, aby
    // structured data měla co hlásit, by znamenalo publikovat cenu, která na
    // stránce nestojí. Rozsah se hlásí přes `areaServed` a `serviceType`.
    $serviceJsons = [];
    foreach (__('price.tiers') as $tier) {
        $serviceLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'serviceType' => 'Web development',
            'name' => $tier['name'],
            'description' => $tier['desc'],
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url'  => url('/'),
            ],
            'areaServed' => ['CZ', 'SK', 'DE', 'AT'],
        ];
        $serviceJsons[] = json_encode($serviceLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
@endphp
<script type="application/ld+json">
{!! $breadcrumbJson !!}
</script>
@foreach ($serviceJsons as $serviceJson)
<script type="application/ld+json">
{!! $serviceJson !!}
</script>
@endforeach
@endpush

@section('content')

{{-- OND-393 (předloha OND-391) — /cenik v jazyce nové homepage, 3. z 9 podstránek.
     Obal `.pd` nese tokeny ACID (základ OND-379 §0), `.pd--depth-sub`
     vypíná vrstvu B (OND-386). Vrstva A zůstává: kužely v hloubka.css §E
     jsou psané `.pd--depth-sub > section[data-pdd="price-*"]`, takže každá
     sekce s `data-pdd` musí zůstat PŘÍMÝM dítětem tohohle obalu. --}}

{{-- OND-354 — POŘADÍ SEKCÍ JE TU SDĚLENÍ, NE ROZVRŽENÍ.
     Důkazy a hodnota stojí NAD cenami. Kdo tyhle sekce přehazuje, mění tím
     argument stránky, ne její vzhled. OND-391 pořadí nemění. --}}
<div class="pd pd--depth pd--depth-sub">

{{-- Hlava — `.pd-page-head`, ne `.pd-hero` (základ §1c: jinak se zapne
     náboj podtržení i přejezd po tlačítku). Dva řádky nad titulkem jsou jeden
     `.pd-eyebrow`, oddělené vlasovou čárkou — stejně jako /kontakt. --}}
<section class="pd-section pd-page-head">
    <div class="container-site">
        <p class="pd-eyebrow">{{ __('price.hero.page_mark_label') }} <span class="pd-eyebrow__sep" aria-hidden="true"></span> {{ __('price.hero.upline') }}</p>
        <h1 class="pd-heading pd-heading--sub">{!! __('price.hero.heading_html') !!}</h1>
        <p class="pd-sub">{{ __('price.hero.subline') }}</p>
    </div>
</section>

{{-- Důkazní pás — OND-354. Čísla jsou `home.social_proof` (lang/*/home.php
     se čte napříč webem). `response` z homepage pruhu tu VĚDOMĚ není: pás má
     nést doklady, a slib doby odpovědi je slib, ne doklad.

     OND-391: pruh je `.pd-strip__list` z homepage (sekce 03) a recenze jsou
     `.pd-testi` z homepage (sekce 09) — týž člověk (Toman) teď vypadá na obou
     stránkách stejně. Podpis kreslí sdílená `<x-testimonial-by>`. --}}
@php
    // Ty dvě recenze jsou vybrané, ne první dvě v poli: ze šestnácti jsou to
    // jediné dvě, které mluví k ceně. Štěpánek je jediný, kdo Ondřeje srovnává
    // s předchozím dodavatelem (na „je drahý" odpovídá člověk, který už
    // někomu jinému zaplatil), Toman říká, že dostal víc, než čekal.
    // Výběr je odůvodněný v dokumentu na OND-347, oddíl 4.2 — neměnit za jiné.
    // Jména jsou v lang/{cs,en,de}/testimonials.php shodná, liší se jen text.
    $proofTestimonials = collect(['Ing. Ivo Štěpánek', 'Rostislav Toman'])
        ->map(fn ($name) => collect(__('testimonials.items'))->firstWhere('name', $name))
        ->filter()
        ->values();

    $quoteOpen  = __('home.quote_marks.open');
    $quoteClose = __('home.quote_marks.close');
@endphp
<section class="pd-section pd-section--band" data-pdd="price-proof">
    <div class="container-site">

        {{-- OND-315 (platí i tady): viditelné „5,0" je pro čtečku schované a
             nahrazuje ho úplné „Hodnocení 5 z 5". --}}
        <ul class="pd-strip__list" aria-label="{{ __('home.social_proof.strip_aria') }}">
            <li><strong aria-hidden="true">{{ __('home.social_proof.rating_value') }}</strong><span class="sr-only">{{ __('home.social_proof.rating_aria') }}</span> {{ __('home.social_proof.reviews') }}</li>
            <li><strong>{{ __('home.social_proof.scope') }}</strong></li>
            <li><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li>{{ __('home.social_proof.award') }}</li>
        </ul>

        @if ($proofTestimonials->isNotEmpty())
        <div class="pd-testi">
            @foreach ($proofTestimonials as $review)
            <article class="pd-testi__item">
                <x-testimonial-by :person="$review" :size="56" />
                <p class="pd-testi__text">{{ $quoteOpen }}{{ $review['text'] }}{{ $quoteClose }}</p>
            </article>
            @endforeach
        </div>
        @endif

    </div>
</section>

{{-- Co je součástí každého projektu — OND-354: posunuté NAD ceny.
     OND-391: `.pd-split` (hlava vlevo, obsah vpravo — táž osa jako formulář
     na /kontakt) + nová sdílená `.pd-points`: body bez pořadí, bez ikon,
     oddělené vlasovou linkou. Věta o době odpovědi v „Podpora i po
     spuštění" patří OND-345 — nesahat. --}}
<section class="pd-section" data-pdd="price-guarantees">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('price.guarantees.heading') }}</h2>
            </header>

            <div class="pd-points">
                @foreach (__('price.guarantees.items') as $g)
                <div class="pd-point">
                    <h3 class="pd-point__title">{{ $g['title'] }}</h3>
                    <p class="pd-point__text">{{ $g['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Úrovně — `.pd-price` z homepage (sekce 08), rozšířená modifikátorem
     `--full` o to, co homepage kotva nemá: případovku, výčet a tlačítko.

     OND-198 (nález 5.4): očekávací věta musí padnout dřív, než čtenář
     uvidí první číslo. OND-354: tahle věta je jediné místo na stránce, kde
     stojí cena. OND-391: proto je to `.pd-lead` — největší text pod
     titulkem. Úrovně pod ní nesou rozsah, ne cenovku. --}}
<section class="pd-section" data-pdd="price-tiers">
    <div class="container-site">

        <p class="pd-lead pd-lead--wide pd-price__lead">{{ __('price.intro') }}</p>

        <div class="pd-price pd-price--full">
            @foreach (__('price.tiers') as $tier)
            {{-- Doporučená úroveň je označená dvakrát, jako na homepage:
                 acidová linka nahoře a acidové slovo u názvu. Karta, rámeček,
                 stín a acidové tlačítko odešly (hloubka.css §E4). --}}
            <article class="pd-price__col {{ $tier['popular'] ? 'pd-price__col--featured' : '' }}"
                     data-analytics-view="pricing_tier_view"
                     data-analytics-props='{"pricing_tier_shown":"{{ $tier['key'] }}"}'>

                <h2 class="pd-price__title">{{ $tier['name'] }}@if ($tier['popular']) <em>{{ __('price.popular') }}</em>@endif</h2>
                {{-- OND-354: rozsah je to, čím se úrovně reálně liší — proto
                     stojí hned pod názvem ve velikosti, kterou homepage dává
                     rozsahu. Popis je až pod ním (pořadí homepage kotvy).
                     OND-448 (B-08): název je malý štítek, `scope` je claim
                     („Aby si vás zákazník ověřil"), `desc` jeden tlumený
                     podtitul — počet stránek z karet zmizel. --}}
                <p class="pd-price__scope">{{ $tier['scope'] }}</p>
                <p class="pd-price__desc">{{ $tier['desc'] }}</p>

                {{-- OND-359: důkaz místo výčtu funkcí. `$tierProofs` drží jen
                     publikované projekty, takže odkaz na 404 nevznikne. --}}
                @php $proof = $tierProofs->get($tier['proof']['slug'] ?? null); @endphp
                @if ($proof)
                <p class="pd-price__proof">
                    <a href="{{ $proof->detailUrl() }}" class="pd-case__live"
                       data-analytics="pricing_tier_proof_click"
                       data-analytics-props='{"pricing_tier_shown":"{{ $tier['key'] }}","project_slug":"{{ $proof->slug }}"}'>{{ __('price.proof_intro') }}: {{ $tier['proof']['label'] }}</a>
                </p>
                @endif

                <ul class="pd-service__bullets pd-price__features">
                    @foreach ($tier['features'] as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>

                {{-- OND-391: tři stejná tlačítka (od OND-448 „Napsat poptávku")
                     jsou tichý odkaz s acidovou linkou (`.pd-case__cta`),
                     ne tři tlačítka. Analytika zůstává po úrovních. --}}
                <p class="pd-price__action">
                    <a href="{{ lroute('contact') }}" class="pd-case__cta"
                       data-analytics="pricing_tier_cta_primary_click"
                       data-analytics-props='{"pricing_tier_shown":"{{ $tier['key'] }}"}'>{{ $tier['cta'] }}</a>
                </p>

            </article>
            @endforeach
        </div>

        {{-- OND-448 (B-08): cenu neurčuje počet stránek — tichá věta hned pod
             balíčky, jen tady (homepage kotva nese jen dlaždice). --}}
        <p class="pd-note pd-price__pages">{{ __('price.pages_note') }}</p>

        {{-- OND-354: `entry_note` stojí pod mřížkou, ne v kartě. --}}
        <p class="pd-intro pd-price__entry">{{ __('price.entry_note') }}</p>

        <p class="pd-note">{{ __('price.note') }}</p>

    </div>
</section>

{{-- Co cenu zvedá a co snižuje — OND-354. OND-391: nová sdílená `.pd-duo`
     (dva protilehlé sloupce s vlasovou linkou mezi nimi) a odrážka ACID
     `.pd-service__bullets` z homepage. Jediné, co sloupce odlišuje beze slov,
     je šipka; dolní sloupec ji v CSS překlápí. Je dekorace. --}}
<section class="pd-section" data-pdd="price-compare">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('price.compare.heading') }}</h2>
            </header>

            <div class="pd-duo">
                @foreach (['up', 'down'] as $direction)
                @php $group = __('price.compare.' . $direction); @endphp
                <div class="pd-duo__col pd-duo__col--{{ $direction }}">
                    <h3 class="pd-duo__label">
                        <x-icon.arrow-up class="pd-duo__mark" aria-hidden="true" focusable="false" />
                        {{ $group['label'] }}
                    </h3>
                    <ul class="pd-service__bullets">
                        @foreach ($group['items'] as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Doplňky — nová sdílená `.pd-rates`: řádkový ceník, název a popis vlevo,
     částka vpravo na jedné svislé ose. OND-391: čtyři stejná tlačítka
     „Nezávazná poptávka" odešla — rozhodnutí o výzvách je v dokumentu
     na OND-391, oddíl 5. Částky jsou lang řetězce beze změny. --}}
<section class="pd-section" data-pdd="price-addons">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('price.addons.heading') }}</h2>
                <p class="pd-intro">{{ __('price.addons.desc') }}</p>
            </header>

            <div class="pd-rates">
                @foreach (__('price.addons.items') as $addon)
                <div class="pd-rate">
                    <h3 class="pd-rate__name">{{ $addon['name'] }}</h3>
                    <p class="pd-rate__price">{{ $addon['price'] }}</p>
                    <p class="pd-rate__desc">{{ $addon['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Závěr — jediná acidová výzva na stránce. Věta je otázka pro toho, kdo
     se nerozhodl mezi úrovněmi; tlačítko `.pd-cta` z hera homepage.
     Plovoucí `.price-sticky-cta` odešla: na mobilu ležela přes spodní lištu
     (i přes telefon) a na desktopu opakovala tlačítko v navigaci. --}}
<section class="pd-section pd-close" data-pdd="price-cta">
    <div class="container-site">
        <div class="pd-split">
            <header>
                <h2 class="pd-head__title">{{ __('price.cta.heading') }}</h2>
            </header>

            <div>
                <p class="pd-intro">{{ __('price.cta.desc') }}</p>
                <a href="{{ lroute('contact') }}" class="pd-cta">
                    {{ __('price.cta.btn') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                </a>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

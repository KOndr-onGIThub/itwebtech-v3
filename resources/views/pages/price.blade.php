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

{{-- OND-251 — vrstva hloubky ZAPNUTÁ: světlo ano, pohyb ne.
     Rozhodovací stránka, takže pásma dostávají nejjasnější nasvícení a všechno
     pod nimi se propadá do stínu. Uzavřená smyčka z homepage (sekce 06) se sem
     VĚDOMĚ nepřenáší — doporučené pásmo je už označené čtyřikrát; rozbor je
     v §E4 hloubka.css. --}}

{{-- OND-354 — POŘADÍ SEKCÍ JE TU SDĚLENÍ, NE ROZVRŽENÍ.
     Do 26. 9. 2026 stálo na stránce: hero → ceny → srovnávací tabulka →
     „Co je součástí každého projektu" → doplňky → výzva. Změřeno na živé
     stránce: první číslo bylo 533 znaků od začátku obsahu, první hodnotový
     argument o 2 753 znaků dál. Člověk tedy dostal cenu dřív než jediný důvod,
     proč ji platit — a vedle ceny nestál ani jeden důkaz (nula recenzí, nula
     případovek).

     Dnešní pořadí to obrací: důkazy a hodnota stojí NAD cenami. Kdo tyhle
     sekce přehazuje, mění tím argument stránky, ne její vzhled. --}}
<div class="pd--depth pd--depth-sub">

{{-- Page hero — OND-135 iter 5: plán §3.1 design DNA (page-mark + display + amber accent) --}}
<div class="page-hero page-hero--price">
    <div class="container-site">
        {{-- OND-135 cleanup (2026-05-14): page_mark_index span odebrán jako
             agency-portfolio artefakt (itwebtech nemá „pages" hierarchii) —
             aplikováno per CEO PR #78 precedent (home) + PR #80 (kontakt). --}}
        <p class="page-hero__page-mark">
            <span class="page-hero__page-mark-label">{{ __('price.hero.page_mark_label') }}</span>
        </p>
        <p class="page-hero__upline">{{ __('price.hero.upline') }}</p>
        <h1 class="page-hero__heading">
            {!! __('price.hero.heading_html') !!}
        </h1>
        <p class="page-hero__subline">{{ __('price.hero.subline') }}</p>
    </div>
</div>

{{-- Důkazní pás — OND-354.
     Do téhle karty stálo na `/cenik` nula recenzí, nula případovek a nula jmen
     klientů: cena bez jediného dokladu, že ji někdo zaplatil a byl rád. Nic tu
     není nově napsané — čísla i citace už na webu jsou, jen dosud nestály
     tam, kde se rozhoduje o ceně.

     Čísla jsou `home.social_proof` (lang/*/home.php se čte napříč webem).
     `response` z homepage pruhu tu VĚDOMĚ není: pás má nést doklady, a slib
     doby odpovědi je slib, ne doklad. Znění toho slibu navíc mění OND-345
     napříč webem — tady by z něj vznikla druhá kopie. --}}
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

    // Stejná mapa jako na homepage — zdroj recenze je součást důkazu.
    $sourceLabels = [
        'google'   => 'Google',
        'facebook' => 'Facebook',
        'firmy_cz' => 'Firmy.cz',
    ];
@endphp
<section class="section-wrapper section-alt" data-reveal data-pdd="price-proof">
    <div class="container-site">

        {{-- OND-315 (platí i tady): viditelné „5,0" je pro čtečku schované a
             nahrazuje ho úplné „Hodnocení 5 z 5", aby nevidomý slyšel i tu
             stupnici. Popis nese seznam, ne sekce — sekce drží i recenze. --}}
        <ul class="pricing-proof__figures" aria-label="{{ __('home.social_proof.strip_aria') }}">
            <li>
                <strong aria-hidden="true">{{ __('home.social_proof.rating_value') }}</strong>
                <span class="sr-only">{{ __('home.social_proof.rating_aria') }}</span>
                {{ __('home.social_proof.reviews') }}
            </li>
            <li><strong>{{ __('home.social_proof.projects') }}</strong></li>
            <li><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li>{{ __('home.social_proof.award') }}</li>
        </ul>

        @if ($proofTestimonials->isNotEmpty())
        <div class="pricing-proof__reviews" data-reveal-group>
            @foreach ($proofTestimonials as $review)
            <figure class="pricing-proof__review">
                <blockquote>
                    <p>{{ $review['text'] }}</p>
                </blockquote>
                <figcaption>
                    {{ $review['name'] }} — {{ $review['company'] }}@if (filled($review['role'] ?? null)), {{ $review['role'] }}@endif
                    @if (!empty($sourceLabels[$review['source'] ?? '']))
                    <span class="pricing-proof__source">{{ $sourceLabels[$review['source']] }}</span>
                    @endif
                </figcaption>
            </figure>
            @endforeach
        </div>
        @endif

    </div>
</section>

{{-- What's included — OND-354: text beze změny, posunuté NAD ceny.
     Tohle je ten hodnotový argument, který byl dřív 2 753 znaků za prvním
     číslem. Věta o době odpovědi v položce „Podpora i po spuštění" patří
     OND-345, ne téhle kartě — nesahat na ni tady. --}}
<section class="section-wrapper" data-reveal data-pdd="price-guarantees">
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.guarantees.heading') }}</h2>
        </header>

        <div class="pricing-guarantees" data-reveal-group>
            @foreach (__('price.guarantees.items') as $g)
            <div class="pricing-guarantee">
                <h3>{{ $g['title'] }}</h3>
                <p>{{ $g['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing tiers --}}
<section class="section-wrapper" data-reveal data-pdd="price-tiers">
    <div class="container-site">

        {{-- OND-198 (nález 5.4): očekávací věta musí padnout dřív, než čtenář
             uvidí první číslo. OND-354: tahle věta je dnes jediné místo na
             stránce, kde stojí cena — prahové číslo a rozpětí. Úrovně pod ní
             nesou rozsah, ne cenovku. --}}
        <p class="pricing-expectation">{{ __('price.intro') }}</p>

        <div class="pricing-tiers" data-reveal-group>
            @foreach (__('price.tiers') as $tier)
            <article class="pricing-tier {{ $tier['popular'] ? 'pricing-tier--featured' : '' }}"
                     data-analytics-view="pricing_tier_view"
                     data-analytics-props='{"pricing_tier_shown":"{{ $tier['key'] }}"}'>

                @if ($tier['popular'])
                <span class="pricing-tier__badge">{{ __('price.popular') }}</span>
                @endif

                <header class="pricing-tier__header">
                    <h2 class="pricing-tier__name">{{ $tier['name'] }}</h2>
                    <p class="pricing-tier__desc">{{ $tier['desc'] }}</p>
                    {{-- OND-354: dřív tady stála cena a pod ní „orientační cena".
                         Dnes rozsah — úroveň se jmenuje podle toho, co vzniká,
                         a rozsah je to, čím se od sebe úrovně reálně liší. --}}
                    <div class="pricing-tier__scope">{{ $tier['scope'] }}</div>
                </header>

                <ul class="pricing-tier__features">
                    @foreach ($tier['features'] as $feature)
                    <li>
                        <x-icon.circle-check-big class="w-4 h-4 shrink-0" />
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <a href="{{ lroute('contact') }}"
                   class="btn {{ $tier['popular'] ? 'btn-primary' : 'btn-secondary' }} pricing-tier__cta"
                   data-analytics="pricing_tier_cta_primary_click"
                   data-analytics-props='{"pricing_tier_shown":"{{ $tier['key'] }}"}'>
                    {{ $tier['cta'] }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>

            </article>
            @endforeach
        </div>

        {{-- OND-354: `entry_note` nahradilo omluvné „Výjimka, ne standardní
             vstup." u nejnižší úrovně. Stojí pod mřížkou, ne v kartě: jsou to
             čtyři věty a v kartě by rozhodily výšku všech tří sloupců. --}}
        <p class="pricing-entry-note">{{ __('price.entry_note') }}</p>

        <p class="pricing-note">{{ __('price.note') }}</p>

    </div>
</section>

{{-- Co cenu zvedá a co snižuje — OND-354.
     Tady stála srovnávací tabulka tří pojmenovaných pásem (desktop tabulka +
     mobilní taby s cenou v hlavičce). Pásma zmizela, takže se tabulka neměla
     o co opřít. Nová osa vysvětluje cenu bez cenovky. --}}
<section class="section-wrapper section-alt" data-reveal data-pdd="price-compare">
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.compare.heading') }}</h2>
        </header>

        <div class="pricing-factors" data-reveal-group>
            @foreach (['up', 'down'] as $direction)
            @php $group = __('price.compare.' . $direction); @endphp
            <div class="pricing-factors__col pricing-factors__col--{{ $direction }}">
                {{-- Jedna ikona pro obojí, dolní sloupec ji v CSS překlápí —
                     šipka nahoru/dolů je jediné, co ty dva sloupce odlišuje
                     beze slov. Je dekorace: směr říká i ten popisek vedle. --}}
                <h3 class="pricing-factors__label">
                    <x-icon.arrow-up class="w-4 h-4 shrink-0" aria-hidden="true" focusable="false" />
                    {{ $group['label'] }}
                </h3>
                <ul class="pricing-factors__list">
                    @foreach ($group['items'] as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Addons --}}
<section class="section-wrapper" data-reveal data-pdd="price-addons">
    <div class="container-site">
        <header class="section-header">
            <h2>{{ __('price.addons.heading') }}</h2>
            <p class="section-header__desc">{{ __('price.addons.desc') }}</p>
        </header>

        <div class="pricing-addons" data-reveal-group>
            @foreach (__('price.addons.items') as $addon)
            <div class="pricing-addon">
                <div class="pricing-addon__info">
                    <h3>{{ $addon['name'] }}</h3>
                    <p>{{ $addon['desc'] }}</p>
                </div>
                <div class="pricing-addon__price">{{ $addon['price'] }}</div>
                <a href="{{ lroute('contact') }}" class="btn btn-secondary pricing-addon__cta">
                    {{ __('price.quotation') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Sticky CTA — plán „cena nikdy nezmizí" (OND-135 iter 5).
     Zobrazí se po prvním scroll-passu hero, skryje se v final CTA sekci. --}}
<aside class="price-sticky-cta"
       x-data="{ visible: false }"
       x-init="
         const trigger = () => { visible = window.scrollY > 480 && window.scrollY < (document.body.scrollHeight - window.innerHeight - 320); };
         trigger();
         window.addEventListener('scroll', trigger, { passive: true });
         window.addEventListener('resize', trigger, { passive: true });
       "
       x-show="visible"
       x-transition.opacity.duration.300ms
       x-cloak
       aria-label="{{ __('price.sticky_cta.label') }}">
    <a href="{{ lroute('contact') }}" class="price-sticky-cta__btn">
        <span class="price-sticky-cta__label">{{ __('price.sticky_cta.cta') }}</span>
        <x-icon.arrow-right class="w-4 h-4 shrink-0" />
    </a>
</aside>

{{-- CTA --}}
<section class="section-wrapper section-cta price-cta" data-reveal data-pdd="price-cta">
    <div class="container-site">
        <div class="price-cta__inner">
            <h2 class="final-cta-heading">{{ __('price.cta.heading') }}</h2>
            <p class="price-cta__desc">{{ __('price.cta.desc') }}</p>
            <a href="{{ lroute('contact') }}" class="btn btn-primary">
                {{ __('price.cta.btn') }}
                <x-icon.arrow-right class="w-4 h-4 shrink-0 -rotate-45" />
            </a>
        </div>
    </div>
</section>

</div>
@endsection

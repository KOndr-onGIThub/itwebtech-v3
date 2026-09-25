{{-- ===================================================
     HOMEPAGE — devět sekcí + formulář (OND-308, balík S3 plánu OND-305)

     Text pochází z dokumentu `text-nova-homepage` na OND-307, vizuální
     jazyk je pořád ACID z OND-231 (prefix tříd `pd-`, styly
     v resources/css/podpis.css). Původní verze stránky žije zmrazená
     na /puvodni-homepage — pages/home-legacy.blade.php, vlastní
     lang/{cs,en,de}/home_legacy.php. Sem se z ní nic netahá a naopak.

     Gramatika ACID (beze změny):
       - barva je signální inkoust: existuje jen tam, kde je akce nebo důraz
       - vlasové linky místo karet a boxů, hodně negativního prostoru
       - žádný obsah nestartuje v opacity: 0 (tj. žádné `data-reveal`)
       - pohyb je přesný a účelový, ne dekorativní

     Pořadí sekcí:
       01 hero · 02 situace klienta · 03 18 let v Toyotě · 04 pruh čísel
       05 tři projekty · 06 co stavím · 07 jak to probíhá · 08 ceny
       09 reference a námitky · 10 poptávka

     DVĚ ODESÍLACÍ VÝZVY NA STRÁNCE, ne víc: hero → kotva formuláře,
     formulář → odeslat. Tlačítko pod kroky procesu i druhá výzva v heru
     jsou zrušené, telefon zůstal v liště a ve spodní mobilní liště.

     Zaniklé sekce: „Každý projekt začínám pochopením", „Čemu se tím
     vyhnete", „Šablona je hotová rychle", „Proč já" (čtyři výhody),
     technická sekce „Pod kapotou". Jejich klíče jsou pryč z lang,
     zůstal jen naměřený čas načtení — přesunul se do pruhu čísel.
     =================================================== --}}
@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('description', __('home.meta.description'))
{{-- Závěrečná výzva je poslední sekce stránky — generický prefooter
     by za ní byl další CTA v řadě (nález OND-201/5.8). --}}
@section('hide_prefooter', 'true')

{{-- OND-145 P2 — preload display fontu (IBM Plex Sans Variable wght axis)
     pro hero LCP. Variable woff2 nese weights 100–700 v jednom souboru.
     latin + latin-ext kvůli CS diakritice; DE umlauty jsou v latin subsetu. --}}
@push('preloads')
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2') }}">
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2') }}">
@endpush

@php
    // OND-308: pořadí referencí. Baudyš z téhle řady zmizel úplně —
    // jeho citace stojí nově v sekci 03 u Toyoty a dvakrát na jedné
    // stránce být nemá. Feature flag `show_toyota_testimonial` tím
    // pro homepage ztratil smysl.
    $allTestimonials = collect(__('testimonials.items'));
    $homeTestimonials = collect(['Stanislav Holcmann', 'Rostislav Toman', 'Hana Jaskmanická'])
        ->map(fn ($name) => $allTestimonials->firstWhere('name', $name))
        ->filter()
        ->values();

    // Tři texty jsou na nové stránce nové a zatím existují jen česky —
    // překlady jsou samostatná karta (S4). Dokud klíč v lang/{en,de}
    // není, blok se nevykreslí; jinak by stránka vypsala holý klíč.
    $situationText = \Illuminate\Support\Facades\Lang::has('home.situation.text')
        ? __('home.situation.text')
        : null;
    $servicesSubheading = \Illuminate\Support\Facades\Lang::has('home.services.subheading')
        ? __('home.services.subheading')
        : null;
    $toyotaExample = \Illuminate\Support\Facades\Lang::has('home.toyota.example')
        ? __('home.toyota.example')
        : null;
@endphp

@section('content')
{{-- OND-246 — `pd--depth` zapíná vrstvu hloubky (resources/css/hloubka.css
     + resources/js/hloubka.js); sundání téhle jedné třídy vrátí stránku
     do původního stavu.

     OND-308 — `pd--depth-home` tady schválně NENÍ. Ten scope drží pravidla
     navázaná na POŘADÍ sekcí (`> section:nth-child(N)`) a dvakrát už se
     stalo, že je po přeskládání stránky nikdo nepřečísloval a nasvícení
     mířilo vedle. Nové sekce se adresují přes `id` — stejně jako ceník,
     postup, reference a poptávka, které to tak měly vždycky. `pd--depth-home`
     zůstává v CSS jen pro zmrazenou /puvodni-homepage a zmizí s ní. --}}
<div class="pd pd--depth">

{{-- ===================================================
     01 — HERO
     Fotka „tak jak je" přes pravou část, text v negativním
     prostoru vlevo, autogram. Tilt jen na hover zařízeních.
     =================================================== --}}
{{-- OND-336: `pd-hero--hp` drží stažený svislý rytmus na mobilu jen tady.
     Hero markup je totožný se zmrazenou kopií na /puvodni-homepage
     (home-legacy.blade.php) — uvnitř sekce není na co scopovat, takže
     rozdíl nese modifikátor na `<section>`. --}}
<section class="pd-hero pd-hero--hp" id="pd-hero-tilt">
    <div class="pd-hero__photo" data-tilt>
        <x-responsive-image
            path="hero/hero-uvod.webp"
            alt="Ondřej Kriška — weby a aplikace na míru"
            sizes="(min-width: 1024px) 54vw, 100vw"
            loading="eager"
            fetchpriority="high"
        />
    </div>
    <div class="container-site">
        <div class="pd-hero__content">
            <p class="pd-eyebrow">{{ __('home.hero.page_mark_label') }} — {{ __('home.hero.upline') }}</p>

            {{-- OND-333: `pd-heading--hp` drží zmenšení (52/33 px) jen tady.
                 Stará homepage na /puvodni-homepage má stejný hero markup
                 a stejnou třídu `pd-heading` — modifikátor je jediné, co je
                 od sebe odlišuje. --}}
            <h1 class="pd-heading pd-heading--hp">{!! __('home.hero.heading_html') !!}</h1>

            <p class="pd-sub">{{ __('home.hero.subline') }}</p>

            <p class="pd-sign">
                &mdash; Ondřej Kriška
                <svg class="pd-sign__mark" viewBox="0 0 220 60" fill="none" aria-hidden="true">
                    <path d="M4 40C16 12 28 8 34 26C40 44 46 20 54 18C62 16 60 38 70 38C82 38 84 10 96 10C110 10 104 44 118 44C136 44 132 14 150 14C166 14 158 34 172 30C182 27 184 16 194 16C202 16 200 26 210 24"
                          stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </p>

            {{-- OND-308: jediné tlačítko v heru. Druhá výzva („Domluvit
                 30min konzultaci") vedla na rezervace zrušené v OND-303,
                 telefon pod ní tříštil rozhodnutí hned pod hlavní výzvou —
                 v liště i ve spodní mobilní liště je pořád. --}}
            <div class="pd-actions">
                <a href="#{{ __('home.anchors.poptavka') }}" class="pd-cta" data-analytics="hero_cta_primary_click">
                    {{ __('home.hero.cta_primary') }}
                    <x-icon.arrow-right class="w-4 h-4 shrink-0 pd-cta__arrow" />
                </a>
            </div>
            <p class="pd-note">{{ __('home.hero.note') }}</p>
        </div>
    </div>
</section>

{{-- ===================================================
     02 — SITUACE KLIENTA
     Odstavec, ne sekce: bez nadpisu, bez tlačítka, tři věty.
     Popis stavu, u kterého člověk kývne hlavou — proto tu není
     ani jedna věta o tom, co tím ztrácí.
     =================================================== --}}
@if ($situationText)
<section class="pd-section pd-situation">
    <div class="container-site">
        <p class="pd-lead pd-lead--wide">{{ $situationText }}</p>
    </div>
</section>
@endif

{{-- ===================================================
     03 — 18 LET V TOYOTĚ
     Tady si klient ověřuje řemeslo. Nese ho citace ředitele
     z Toyoty — ta stojí jen tady, v řadě referencí níž už
     Baudyš není. Video se přestěhovalo do sekce 07 (OND-314).
     =================================================== --}}
<section class="pd-section" id="section-toyota">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.toyota.heading') }}</h2>
        </header>

        {{-- OND-314: video se odsud odstěhovalo do sekce 07 „Jak to probíhá".
             Stálo 464 px pod hero fotkou — stejná bunda, stejné focení, dva
             portréty pod sebou. Sekce zůstává textová, důkaz řemesla nese
             citace Pavla Baudyše. Wrapper `.pd-toyota` tím zanikl, text má
             vlastní max-width, takže se neroztekl. --}}
        <div class="pd-origin__text">
            <p>{{ __('home.toyota.text') }}</p>
            <p>{{ __('home.toyota.text_2') }}</p>
            @if ($toyotaExample)
                <p>{{ $toyotaExample }}</p>
            @endif
            <blockquote class="pd-origin__quote">
                {{ __('home.toyota.quote_text') }}
                <footer>— {{ __('home.toyota.quote_author') }}</footer>
            </blockquote>
        </div>
    </div>
</section>

{{-- ===================================================
     04 — PRUH ČÍSEL
     OND-308: naměřený čas načtení se sem přestěhoval ze zrušené
     technické sekce „Pod kapotou". Je to jediné její tvrzení, které
     si návštěvník ověří sám na sobě — mezi ostatní čísla patří.
     Bez JS zůstane řádek skrytý: nikdy neukazujeme číslo, které
     jsme nenaměřili.
     =================================================== --}}
{{-- OND-315 (nález z OND-311): popis celé sekce byl „Hodnocení 5 z 5“, což
     platilo jen pro první z pěti údajů — realizace, roky praxe, doba odpovědi
     ani ocenění hodnocení nejsou. Sekce má teď popis na celý pruh a hodnocení
     se popisuje u toho údaje, ke kterému patří: viditelné „5,0“ je pro čtečku
     schované a nahrazuje ho úplné „Hodnocení 5 z 5“, takže nevidomý slyší
     totéž, co vidí vidící, a navíc i tu stupnici. --}}
<section class="pd-strip" aria-label="{{ __('home.social_proof.strip_aria') }}">
    <div class="container-site">
        <ul class="pd-strip__list">
            <li><strong aria-hidden="true">{{ __('home.social_proof.rating_value') }}</strong><span class="sr-only">{{ __('home.social_proof.rating_aria') }}</span> {{ __('home.social_proof.reviews') }}</li>
            <li><strong>{{ __('home.social_proof.projects') }}</strong></li>
            <li><strong>{{ __('home.social_proof.experience') }}</strong></li>
            <li>{{ __('home.social_proof.response') }}</li>
            <li>{{ __('home.social_proof.award') }}</li>
        </ul>

        <p class="pd-strip__perf" id="pd-perf" hidden>
            {{ __('home.craft.perf_prefix') }}
            <strong id="pd-perf-value"></strong>
            {{ __('home.craft.perf_suffix') }}
        </p>
    </div>
</section>

{{-- ===================================================
     05 — TŘI PROJEKTY
     Případovky z DB: `hero` snímek projektu (kurátorovaný preview
     banner), ne `portfolio_card_thumbnail()` — ten vybírá náhledovku
     do malé karty a na 58vw široké ploše z toho vycházely slabé
     záběry (nález 19 auditu OND-254).
     =================================================== --}}
@if (config('site.features.show_portfolio_section') && ($featuredHomeProjects ?? collect())->isNotEmpty())
<section class="pd-section" id="section-projects">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.portfolio.heading') }}</h2>
        </header>
        <p class="pd-intro">{{ __('home.portfolio.intro') }}</p>

        <div class="pd-cases">
            @foreach ($featuredHomeProjects as $i => $project)
                @php
                    $t = $project->translation();
                    $cardCopy = __('home.portfolio.cards.' . $project->slug);
                    $clientLabel = is_array($cardCopy) && !empty($cardCopy['client'])
                        ? $cardCopy['client']
                        : ($project->client_name ?: ($t?->title ?? $project->slug));
                    $outcome = is_array($cardCopy) && !empty($cardCopy['outcome'])
                        ? $cardCopy['outcome']
                        : ($t?->subtitle ?? '');
                    $screens = $project->screenshots ?? collect();
                    $hero = $screens->firstWhere('type', 'hero') ?? portfolio_card_thumbnail($screens);
                    $detailHref = $project->detailUrl();
                @endphp
                <article class="pd-case">
                    <a
                        href="{{ $detailHref }}"
                        class="pd-case__visual"
                        aria-label="{{ $clientLabel }} — {{ __('home.portfolio.detail_cta') }}"
                        data-analytics="project_card_click"
                        data-analytics-props='{"slug":"{{ $project->slug }}"}'
                    >
                        @if ($hero)
                            <x-portfolio.screenshot
                                :path="$hero->path"
                                :alt="$clientLabel"
                                sizes="(min-width: 1024px) 58vw, 100vw"
                                loading="lazy"
                            />
                        @endif
                    </a>
                    <div class="pd-case__body">
                        <span class="pd-case__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="pd-case__client">{{ $clientLabel }}</h3>
                        @if ($outcome)
                        <p class="pd-case__outcome">{{ $outcome }}</p>
                        @endif
                        <p class="pd-case__links">
                            <a
                                href="{{ $detailHref }}"
                                class="pd-case__cta"
                                data-analytics="project_card_click"
                                data-analytics-props='{"slug":"{{ $project->slug }}"}'
                            >{{ __('home.portfolio.detail_cta') }} &rarr;</a>
                            @if ($project->live_url)
                            <a
                                href="{{ $project->live_url }}"
                                class="pd-case__live"
                                target="_blank"
                                rel="noopener"
                                aria-label="{{ __('home.portfolio.live_aria', ['client' => $clientLabel]) }}"
                                data-analytics="showcase_site_click"
                                data-analytics-props='{"site":"{{ $project->slug }}"}'
                            >{{ __('home.portfolio.live_cta') }} &nearr;</a>
                            @endif
                        </p>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="pd-more"><a href="{{ lroute('projects') }}" class="pd-more__link">{{ __('home.portfolio.cta') }}</a></p>
    </div>
</section>
@endif

{{-- ===================================================
     06 — CO STAVÍM
     Tři sloupce s vlasovými linkami, bez ikon a boxů —
     ikony jsou slovník šablon, sloupec unese titulek sám.
     =================================================== --}}
<section class="pd-section" id="section-services">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.services.heading_primary') }}</h2>
        </header>
        @if ($servicesSubheading)
        <p class="pd-lead">{{ $servicesSubheading }}</p>
        @endif

        <div class="pd-services">
            @foreach (['weby', 'aplikace', 'eshop'] as $i => $key)
            <article class="pd-service">
                <span class="pd-service__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="pd-service__title">{{ __("home.services.primary.{$key}.title") }}</h3>
                <p class="pd-service__desc">{{ __("home.services.primary.{$key}.description") }}</p>
                <ul class="pd-service__bullets">
                    {{-- OND-308: odrážka je dvojice [hlavní věta, doplněk].
                         Holý řetězec snese taky — en/de drží starý text,
                         dokud ho nepřepíše překladová karta (S4). --}}
                    @foreach (__("home.services.primary.{$key}.bullets") as $bullet)
                    @php
                        $main   = is_array($bullet) ? ($bullet[0] ?? '') : $bullet;
                        $detail = is_array($bullet) ? ($bullet[1] ?? null) : null;
                    @endphp
                    <li>
                        <span class="pd-service__bullet-main">{{ $main }}</span>
                        @if ($detail)
                        <span class="pd-service__bullet-note">{{ $detail }}</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>

        <p class="pd-services__secondary">
            {!! __('home.services.secondary_inline', [
                'pricing_link' => '<a href="' . lroute('price') . '">' . e(__('home.services.secondary_inline_pricing')) . '</a>',
                'contact_link' => '<a href="#' . __('home.anchors.poptavka') . '">' . e(__('home.services.secondary_inline_contact')) . '</a>',
            ]) !!}
        </p>
    </div>
</section>

{{-- ===================================================
     07 — JAK TO PROBÍHÁ
     Čtyři kroky pod sebou, čas jako acid datový štítek;
     citace klientů zůstávají u kroků, kde vznikly.

     OND-308: tlačítko pod kroky („Domluvit konzultaci") je pryč —
     vedlo na zrušené rezervace a bylo to třetí odesílací tlačítko
     na stránce. Věta nad ním zůstává, cesta ke kroku 1 je popsaná
     přímo v kroku 1.
     =================================================== --}}
<section class="pd-section" id="{{ __('home.anchors.how_i_work') }}">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.how_i_work.heading') }}</h2>
        </header>

        {{-- OND-314: video stojí vedle kroků, ne pod nimi — vpravo od textu
             zela na 1440 px díra 518 px široká. `<div>` nesmí dovnitř `<ol>`,
             proto společný wrapper nad oběma. Dvousloupcová sazba až od
             1200 px, níž se video staví pod seznam. --}}
        <div class="pd-steps-layout">
            <ol class="pd-steps">
                @foreach (__('home.how_i_work.steps') as $i => $step)
                <li class="pd-step">
                    <span class="pd-step__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="pd-step__body">
                        <h3 class="pd-step__title">{{ $step['heading'] }}@if (!empty($step['time'])) <em>{{ $step['time'] }}</em>@endif</h3>
                        <p class="pd-step__text">{{ $step['text'] }}</p>
                        @if (!empty($step['quote_text']))
                        <blockquote>
                            {{ $step['quote_text'] }}
                            <footer>— {{ $step['quote_author'] }}</footer>
                        </blockquote>
                        @endif
                        @if (!empty($step['note']))
                        <p class="pd-step__note">{{ $step['note'] }}</p>
                        @endif
                    </div>
                </li>
                @endforeach
            </ol>

            <div class="pd-steps__media">
                <x-video-intro :ariaLabel="__('home.why_me.video_aria')" />
            </div>
        </div>

        <p class="pd-steps__cta-intro">{{ __('home.how_i_work.cta_intro') }}</p>
    </div>
</section>

{{-- ===================================================
     08 — CENY
     Pořadí pásem je dané lang souborem, zvýrazněné pásmo
     se řídí klíčem `featured`, ne pozicí v poli (OND-198/5.4).
     =================================================== --}}
<section class="pd-section" id="section-price" data-analytics-view="price_anchor_view">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.price_anchor.heading') }}</h2>
        </header>
        <p class="pd-intro">{{ __('home.price_anchor.intro') }}</p>

        <div class="pd-price">
            @foreach (__('home.price_anchor.items') as $item)
            <div class="pd-price__col {{ ($item['featured'] ?? false) ? 'pd-price__col--featured' : '' }}">
                <h3 class="pd-price__title">{{ $item['title'] }}@if ($item['featured'] ?? false) <em>{{ __('home.price_anchor.featured_label') }}</em>@endif</h3>
                <p class="pd-price__value">{{ $item['price'] }}</p>
                <p class="pd-price__desc">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- OND-231: odkaz na detailní ceník se v prototypu ztratil —
             je to jediná cesta z kotvy na rozpad cen. --}}
        <p class="pd-more">
            <a href="{{ lroute('price') }}" class="pd-more__link" data-analytics="price_anchor_cta_click">
                {{ __('home.price_anchor.cta') }}
            </a>
        </p>
    </div>
</section>

{{-- ===================================================
     09 — REFERENCE A NÁMITKY
     Jedna sekce ze dvou dnešních: nahoře citace klientů, pod nimi
     odpovědi na to, co se lidé ptají. Dva poctivé nadpisy místo
     jednoho, který by pokrýval obojí jen napůl.

     FAQPage rich snippet se generuje ze stejných lang klíčů jako
     accordion (single source of truth).
     =================================================== --}}
@php
    $faqItems = __('home.faq.items');
@endphp
<section class="pd-section" id="section-testimonials" data-analytics-view="testimonial_view">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.testimonials.heading') }}</h2>
        </header>

        {{-- Recenze jsou v EN/DE překlad českých originálů — poznámka drží
             dohledatelnost zdroje. Česky je klíč prázdný (viz lang/cs/home.php). --}}
        @if (filled(__('home.testimonials.note')))
        <p class="pd-testi__note">{{ __('home.testimonials.note') }}</p>
        @endif

        <div class="pd-testi">
            @foreach ($homeTestimonials as $i => $review)
            <article class="pd-testi__item">
                <span class="pd-testi__num" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <p class="pd-testi__text">{{ $review['text'] }}</p>
                    <p class="pd-testi__meta">
                        {{ $review['name'] }} — {{ $review['company'] }}@if ($review['role']), {{ $review['role'] }}@endif
                        @php
                            $sourceLabels = [
                                'google'   => 'Google',
                                'facebook' => 'Facebook',
                                'firmy_cz' => 'Firmy.cz',
                            ];
                        @endphp
                        @if (!empty($sourceLabels[$review['source'] ?? '']))
                        <span class="pd-testi__source">{{ $sourceLabels[$review['source']] }}</span>
                        @endif
                    </p>
                </div>
            </article>
            @endforeach
        </div>

        <div class="pd-subsection" id="faq">
            <h3 class="pd-subsection__title">{{ __('home.faq.heading') }}</h3>

            <div class="pd-faq">
                @foreach ($faqItems as $i => $item)
                <details class="pd-faq__item" data-q-id="{{ $i }}">
                    <summary
                        class="pd-faq__q"
                        data-analytics="faq_item_open"
                        data-faq-key="{{ $item['key'] ?? 'item-' . $i }}"
                    >
                        <span>{{ $item['question'] }}</span>
                        <span class="pd-faq__mark" aria-hidden="true"></span>
                    </summary>
                    <p class="pd-faq__a">{{ $item['answer'] }}</p>
                </details>
                @endforeach
            </div>

            <script type="application/ld+json">
            @php
                $faqLd = [
                    '@context'   => 'https://schema.org',
                    '@type'      => 'FAQPage',
                    'mainEntity' => array_map(static function (array $item): array {
                        return [
                            '@type'          => 'Question',
                            'name'           => $item['question'],
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text'  => $item['answer'],
                            ],
                        ];
                    }, $faqItems),
                ];
            @endphp
            {!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
            </script>
        </div>
    </div>
</section>

{{-- ===================================================
     10 — POPTÁVKA
     Druhá a poslední výzva na stránce. Stejný endpoint, pole
     i session handling jako dřív.

     OND-308: větve `session('faq_lead_success')` jsou pryč spolu
     s blokem `faq_form`, který žádná šablona nevykreslovala.
     Endpoint `source=home.faq` v HomeLeadController zůstává — dá
     se trefit zvenčí a hlídá ho past na boty v HoneypotTest.
     =================================================== --}}
<section class="pd-section" id="{{ __('home.anchors.poptavka') }}">
    <div class="container-site">
        <header class="pd-head">
            <h2 class="pd-head__title">{{ __('home.inline_form.heading') }}</h2>
        </header>

        <div class="pd-form">
            <div class="pd-form__intro">
                <p class="pd-intro">{{ __('home.inline_form.description') }}</p>
                <blockquote class="pd-form__quote">
                    {{ __('home.inline_form.quote_text') }}
                    <footer>— {{ __('home.inline_form.quote_author') }}</footer>
                </blockquote>
            </div>

            <div class="pd-form__panel">
                @if (session('home_lead_success'))
                    <div class="pd-alert pd-alert--success" role="status">
                        {{ __('home.inline_form.success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="pd-alert pd-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('home.lead.store') }}"
                    novalidate
                    x-data="{ submitting: false }"
                    @submit="submitting = true; window.dispatchEvent(new CustomEvent('inline-form-submit-attempt'))"
                >
                    @csrf

                    <x-form.honeypot id="lead-website-url" />

                    <div class="pd-form__grid">
                        <div class="pd-field">
                            <label for="pd-lead-name">{{ __('home.inline_form.name') }} <span aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                id="pd-lead-name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.name') }}"
                                autocomplete="name"
                            >
                            @error('name') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field">
                            <label for="pd-lead-email">{{ __('home.inline_form.email') }} <span aria-hidden="true">*</span></label>
                            <input
                                type="email"
                                id="pd-lead-email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.email') }}"
                                autocomplete="email"
                            >
                            @error('email') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-phone">{{ __('home.inline_form.phone') }}</label>
                            <input
                                type="tel"
                                id="pd-lead-phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="{{ __('home.inline_form.placeholders.phone') }}"
                                autocomplete="tel"
                                aria-describedby="pd-lead-phone-hint"
                            >
                            {{-- OND-256/4: pošťouchnutí — proč číslo vyplnit, když je nepovinné. --}}
                            <p class="pd-field__hint" id="pd-lead-phone-hint">{{ __('home.inline_form.phone_hint') }}</p>
                            @error('phone') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>

                        <div class="pd-field pd-field--full">
                            <label for="pd-lead-message">{{ __('home.inline_form.message') }} <span aria-hidden="true">*</span></label>
                            <textarea
                                id="pd-lead-message"
                                name="message"
                                rows="5"
                                required
                                placeholder="{{ __('home.inline_form.placeholders.message') }}"
                            >{{ old('message') }}</textarea>
                            @error('message') <p class="pd-field__error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="pd-cta pd-form__submit"
                        :disabled="submitting"
                        data-analytics="inline_form_submit_attempt"
                    >
                        <span x-show="!submitting">{{ __('home.inline_form.submit') }}</span>
                        <span x-show="submitting" x-cloak>{{ __('home.inline_form.submitting') }}</span>
                    </button>

                    <p class="pd-form__note">{{ __('home.inline_form.note') }}</p>

                    <p class="pd-form__privacy">
                        {{ __('home.inline_form.privacy_prefix') }}<a href="{{ lroute('privacy') }}">{{ __('home.inline_form.privacy_link') }}</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
    // Tilt portrétu — vanilla, no-op na touch a reduced-motion.
    (function () {
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var hasHover = window.matchMedia('(hover: hover)').matches;
        if (reduceMotion || !hasHover) return;

        var section = document.getElementById('pd-hero-tilt');
        var photo = section && section.querySelector('[data-tilt]');
        if (!photo) return;

        section.addEventListener('mousemove', function (e) {
            var rect = section.getBoundingClientRect();
            var px = (e.clientX - rect.left) / rect.width - 0.5;
            var py = (e.clientY - rect.top) / rect.height - 0.5;
            photo.style.transform = 'scale(1.02) rotate(' + (px * -0.6) + 'deg) translate(' + (px * -8) + 'px, ' + (py * -6) + 'px)';
        });
        section.addEventListener('mouseleave', function () {
            photo.style.transform = '';
        });
    })();

    // OND-229 — skutečný čas načtení z Performance API (nově v pruhu čísel).
    // Bez podpory API zůstane odstavec `hidden` — žádné vymyšlené číslo.
    // OND-234 — Věta je chlouba, ne přiznání. Když číslo není dobré nebo
    // není důvěryhodné, odstavec zůstane `hidden`. Radši nic než alibi.
    (function () {
        // Strop 2,0 s. Měříme `load`, tedy okamžik po dotažení všech
        // zdrojů — metriku pozdější než LCP. Core Web Vitals má hranici
        // „dobrého“ LCP na 2,5 s; kdybychom stejné číslo dali na `load`,
        // chlubili bychom se i návštěvami, jejichž LCP bylo hluboko za
        // hranicí. 2,0 s je zároveň poslední hodnota, která se při jednom
        // desetinném místě ještě čte jako „pod dvě sekundy“ — od „2,3 s“
        // výš věta přestává být důkaz a začíná být výmluva.
        var MAX_SECONDS = 2;
        // Pod 0,05 s by se vypsalo „0,0 s“ — to vypadá jako rozbité měření,
        // ne jako rychlost.
        var MIN_SECONDS = 0.05;

        // Načtení na pozadí (otevřeno do nového panelu, obnovená session)
        // má throttlované časovače a vyjde nesmyslně velké. Stačí, že byla
        // stránka schovaná kdykoli před změřením.
        var wasHidden = document.visibilityState === 'hidden';
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') { wasHidden = true; }
        });

        function show() {
            if (wasHidden) return;
            if (typeof performance === 'undefined' || !performance.getEntriesByType) return;
            var nav = performance.getEntriesByType('navigation')[0];
            if (!nav) return;
            var s = nav.loadEventEnd / 1000;
            // Chytí i NaN, undefined, zápor a nulu (load ještě nedoběhl).
            if (!(s >= MIN_SECONDS) || s > MAX_SECONDS) return;
            var value = document.getElementById('pd-perf-value');
            var wrap = document.getElementById('pd-perf');
            if (!value || !wrap) return;
            value.textContent = s.toLocaleString(document.documentElement.lang || 'cs', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            }) + ' s';
            wrap.hidden = false;
        }
        if (document.readyState === 'complete') { setTimeout(show, 0); }
        else { window.addEventListener('load', function () { setTimeout(show, 0); }); }
    })();

    // Po submitu formuláře (redirect back()) doskrolovat k výsledku
    // a ohlásit konverzi analytics vrstvě (viz resources/js/analytics.js).
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any() || session('home_lead_success'))
        document.getElementById('{{ __('home.anchors.poptavka') }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        @endif

        @if (session('home_lead_success'))
        window.dispatchEvent(new CustomEvent('inline-form-submit-success'));
        @endif
    });
</script>
@endpush

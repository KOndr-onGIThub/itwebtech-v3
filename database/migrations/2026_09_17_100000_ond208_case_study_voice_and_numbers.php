<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-208 (odštěpeno z OND-201, nález 5.2) — texty případovek na detailových
 * stránkách projektů žijí v `portfolio_project_translations`, ne v lang
 * souborech. `PortfolioSeeder` se po prvním naplnění DB přeskakuje (chrání
 * ruční úpravy z Filamentu), takže úprava `docs/portfolio-data.yaml` se na
 * produkci neprojeví. Data migrace je jediná cesta — precedent:
 * 2026_09_16_100000_rename_jargon_portfolio_tags.php.
 *
 * Migrace řeší tři věci:
 *
 * 1) MNOŽNÉ ČÍSLO V ONDROVĚ HLASE. Detaily mluvily „Postavili jsme",
 *    „We built", „Wir haben" — portfolio je ale web jednoho člověka
 *    (pravidlo 1. osoby j. č.). Dotčeno: pitarena, cyklocentrum,
 *    vp-industry, strechy-zajic.
 *    Záměrně NEtknuto: `yolk` cs.result (množné číslo je uvnitř citace
 *    klienta) a `animace-delejme` de.result („lass uns Video machen" je
 *    citovaný hovorový obrat), `barana` a `zubni-provazek` (ty už 1. osobu
 *    j. č. drží — premisa zadání, že je potřeba opravit, neplatila).
 *
 * 2) NEDOLOŽITELNÁ ČÍSLA. `pitarena` tvrdila „stovky návštěv týdně" a
 *    „obsazené měsíce dopředu" — nález 5.2 zakazuje čísla, která nejde
 *    doložit. Nahrazeno tím, co je vidět na živém webu (programy, poukazy
 *    na jízdu, registrace na závody, e-shop, půjčovna, servis, blog).
 *
 * 3) NOVÝ PROJEKT shop.pitarena.cz. Dokument `pripadovky` (OND-186) popisuje
 *    PitArénu jako e-shop, kdežto projekt se slugem `pitarena` je
 *    motokrosové centrum pitarena.cz — dva různé projekty. Board rozhodl
 *    (interakce 94e41f8f): založit nový projekt, `pitarena` nechat být.
 *    Čísla v textu jsou výhradně z dokumentu `namerena-cisla` (OND-206):
 *    4 551 produktů, 695 kategorií, 19 modelů YCF, desktop 99/100.
 *    Mobilní skóre 87/100 (rozptyl 81–91) se dle omezení QA NEPUBLIKUJE.
 *
 * Aktualizace jsou PODMÍNĚNÉ — přepíšou hodnotu jen tehdy, když v DB stále
 * stojí přesně ten původní text. Když text mezitím někdo změnil ve Filamentu,
 * migrace ho nechá být (stejná ochrana, jakou má PortfolioSeeder).
 */
return new class extends Migration
{
    /** slug => locale => field => ['from' => původní text, 'to' => nový text] */
    private const TEXT_CHANGES = [
        'pitarena' => [
            'cs' => [
                'subtitle' => [
                    'from' => 'Z nuly k rezervacím obsazeným měsíce dopředu',
                    'to'   => 'Od nuly ke značce, webu a online prodeji programů',
                ],
                'summary' => [
                    'from' => 'Brand, web a online rezervační systém pro nové motokrosové centrum. Hned po spuštění začaly chodit objednávky tréninků z Googlu.',
                    'to'   => 'Značka, web a online prodej pro nové motokrosové centrum. Web dnes nese programy, poukazy na jízdu i registrace na závody.',
                ],
                'description' => [
                    'from' => 'PitArena startovala bez webu, brandu i bez první zakázky. Postavili jsme kompletní digitální základ — značku, web s online rezervací tréninků, prodej voucherů a registraci na akce. Od roku 2023 web dál rozvíjím spolu s majitelem.',
                    'to'   => 'PitArena startovala bez webu, brandu i bez první zakázky. Postavil jsem kompletní digitální základ — značku, web s nabídkou programů, online prodejem poukazů a registracemi na závody. Od roku 2023 web dál rozvíjím spolu s majitelem.',
                ],
                'solution' => [
                    'from' => 'Navrhli jsme značku a postavili na míru web s integrovaným rezervačním systémem, online platbami, prodejem dárkových voucherů a registracemi na akce. SEO a obsahové články cílí přesně na lidi, kteří hledají „motokrosový kemp" nebo „pitbike trénink". Doplňuje to správa sociálních sítí a propojení s merchem.',
                    'to'   => 'Navrhl jsem značku a postavil na míru web s online prodejem poukazů na jízdu, nabídkou programů (akademie, zájmový kroužek, kempy a MX soustředění) a registracemi na závody YCF Cup. SEO a obsahové články cílí na lidi, kteří hledají „motokrosový kemp" nebo „pitbike trénink". Doplňuje to správa sociálních sítí a propojení s merchem.',
                ],
                'result' => [
                    'from' => 'Web už v prvním roce přivádí stovky návštěv týdně a generuje rezervace bez zásahu majitele. Tréninky bývají obsazené měsíce dopředu, vouchery se prodávají před Vánoci samy a registrace na motokrosové kempy běží přes web. Dlouhodobá spolupráce pokračuje dodnes.',
                    'to'   => 'Web dnes stojí na vlastních nohách — zájemce si sám najde program, koupí poukaz na jízdu nebo se přihlásí na závod, bez telefonátu majiteli. Vedle toho web nese e-shop, půjčovnu, servis a blog. Dlouhodobá spolupráce pokračuje dodnes.',
                ],
                'meta_description' => [
                    'from' => 'Případová studie: brand, web a online rezervační systém pro PitArenu. Plné kapacity tréninků a stovky návštěv týdně už od prvního roku.',
                    'to'   => 'Případová studie: značka, web a online prodej pro PitArenu — programy, poukazy na jízdu a registrace na závody na jednom webu.',
                ],
            ],
            'en' => [
                'subtitle' => [
                    'from' => 'From zero to bookings filled months ahead',
                    'to'   => 'From zero to a brand, a website and online sales',
                ],
                'summary' => [
                    'from' => 'Brand, website, and online booking for a brand-new motocross centre. Training sessions started selling from Google straight after launch.',
                    'to'   => 'Brand, website and online sales for a brand-new motocross centre. The site now carries programmes, ride vouchers and race registrations.',
                ],
                'description' => [
                    'from' => 'PitArena launched with no website, no brand, and no customers. We built the full digital foundation — identity, a custom website with online training booking, voucher sales, and event sign-ups. The site has been growing alongside the owner since 2023.',
                    'to'   => 'PitArena launched with no website, no brand, and no customers. I built the full digital foundation — identity, a custom website with programme listings, online voucher sales and race registrations. The site has been growing alongside the owner since 2023.',
                ],
                'challenge' => [
                    'from' => 'The client was opening a new motocross training centre in Czechia. No web presence, no brand, zero occupancy. We needed to build trust before PitArena physically opened, and let people book training 24/7 — without phone calls or e-mails.',
                    'to'   => 'The client was opening a new motocross training centre in Czechia. No web presence, no brand, zero occupancy. Trust had to be built before PitArena physically opened, and let people book training 24/7 — without phone calls or e-mails.',
                ],
                'solution' => [
                    'from' => 'We designed the brand and built a custom website with an integrated booking system, online payments, gift voucher sales, and event registrations. SEO and content articles target people searching for "motocross camp" or "pitbike training". Social media management and merch integration round it out.',
                    'to'   => 'I designed the brand and built a custom website with online ride-voucher sales, programme listings (academy, youth club, camps and MX training weeks) and race registrations for the YCF Cup. SEO and content articles target people searching for "motocross camp" or "pitbike training". Social media management and merch integration round it out.',
                ],
                'result' => [
                    'from' => 'The site brings hundreds of weekly visits and generates bookings without owner intervention. Training slots fill months in advance, vouchers sell themselves before Christmas, and motocross camps register through the web. The long-term partnership continues today.',
                    'to'   => 'The site now stands on its own — a visitor finds a programme, buys a ride voucher or signs up for a race without calling the owner. Alongside that it carries the online shop, rentals, service and a blog. The long-term partnership continues today.',
                ],
                'meta_description' => [
                    'from' => 'Case study: brand, website and online booking for PitArena. Full training capacity and hundreds of weekly visits from year one.',
                    'to'   => 'Case study: brand, website and online sales for PitArena — programmes, ride vouchers and race registrations on one site.',
                ],
            ],
            'de' => [
                'subtitle' => [
                    'from' => 'Von Null zu Buchungen, die Monate im Voraus voll sind',
                    'to'   => 'Von Null zu Marke, Website und Online-Verkauf',
                ],
                'summary' => [
                    'from' => 'Marke, Website und Online-Buchung für ein neues Motocross-Zentrum. Trainingsbuchungen kamen direkt nach dem Launch über Google.',
                    'to'   => 'Marke, Website und Online-Verkauf für ein neues Motocross-Zentrum. Die Site trägt heute Programme, Fahrgutscheine und Rennanmeldungen.',
                ],
                'description' => [
                    'from' => 'PitArena startete ohne Website, ohne Marke und ohne Kunden. Wir haben die gesamte digitale Grundlage gebaut — Identität, eine maßgeschneiderte Website mit Online-Buchung von Trainings, Gutscheinverkauf und Event-Anmeldungen. Seit 2023 entwickle ich die Site gemeinsam mit dem Inhaber weiter.',
                    'to'   => 'PitArena startete ohne Website, ohne Marke und ohne Kunden. Ich habe die gesamte digitale Grundlage gebaut — Identität, eine maßgeschneiderte Website mit Programmübersicht, Online-Gutscheinverkauf und Rennanmeldungen. Seit 2023 entwickle ich die Site gemeinsam mit dem Inhaber weiter.',
                ],
                'solution' => [
                    'from' => 'Wir entwarfen die Marke und bauten eine individuelle Website mit integriertem Buchungssystem, Online-Zahlungen, Geschenkgutschein- Verkauf und Event-Registrierungen. SEO- und Content-Artikel zielen auf Suchen wie „Motocross-Camp" oder „Pitbike-Training". Social-Media-Betreuung und Merch-Anbindung runden alles ab.',
                    'to'   => 'Ich habe die Marke entworfen und eine individuelle Website gebaut — mit Online-Verkauf von Fahrgutscheinen, Programmübersicht (Akademie, Jugendclub, Camps und MX-Trainingswochen) und Anmeldungen zum YCF Cup. SEO- und Content-Artikel zielen auf Suchen wie „Motocross-Camp" oder „Pitbike-Training". Social-Media-Betreuung und Merch-Anbindung runden alles ab.',
                ],
                'result' => [
                    'from' => 'Die Site bringt schon im ersten Jahr Hunderte Besuche pro Woche und Buchungen laufen ohne Zutun des Inhabers. Trainingsplätze sind Monate im Voraus ausgebucht, Gutscheine verkaufen sich vor Weihnachten von selbst, Camps werden über das Web registriert. Die langfristige Zusammenarbeit läuft bis heute.',
                    'to'   => 'Die Site steht heute auf eigenen Beinen — Interessenten finden ein Programm, kaufen einen Fahrgutschein oder melden sich zum Rennen an, ohne beim Inhaber anzurufen. Daneben trägt sie Onlineshop, Verleih, Service und Blog. Die langfristige Zusammenarbeit läuft bis heute.',
                ],
                'meta_description' => [
                    'from' => 'Case Study: Marke, Website und Online-Buchung für PitArena. Volle Trainingskapazität und Hunderte Besuche pro Woche ab dem ersten Jahr.',
                    'to'   => 'Case Study: Marke, Website und Online-Verkauf für PitArena — Programme, Fahrgutscheine und Rennanmeldungen auf einer Site.',
                ],
            ],
        ],
        'cyklocentrum' => [
            'cs' => [
                'solution' => [
                    'from' => 'Začali jsme analýzou klientova podnikání a pohledem na domácí i zahraniční konkurenci. Navrhl jsem strukturu, copy a vlastní design, vše ručně kódované kvůli robustnosti a rychlosti. Doplnil jsem správu produktů (klient si sám aktualizuje sortiment), Google Maps profil a propagační článek na Kudy z nudy. Web teď odpovídá kvalitě servisu, který nabízí.',
                    'to'   => 'Začal jsem analýzou klientova podnikání a pohledem na domácí i zahraniční konkurenci. Navrhl jsem strukturu, copy a vlastní design, vše ručně kódované kvůli robustnosti a rychlosti. Doplnil jsem správu produktů (klient si sám aktualizuje sortiment), Google Maps profil a propagační článek na Kudy z nudy. Web teď odpovídá kvalitě servisu, který nabízí.',
                ],
                'result' => [
                    'from' => 'Nový web přivedl první nové zákazníky během pár dní od spuštění. Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat". Návštěvník rychle najde, co umíme servisovat, co se dá půjčit a jak nás kontaktovat — tedy přesně to, kvůli čemu na web přichází.',
                    'to'   => 'Nový web přivedl první nové zákazníky během pár dní od spuštění. Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat". Návštěvník rychle najde, co se dá nechat opravit, co se dá půjčit a jak se s klientem spojit — tedy přesně to, kvůli čemu na web přichází.',
                ],
            ],
            'en' => [
                'solution' => [
                    'from' => 'We started with a deep look at the business and at Czech and foreign competitors. I designed the structure, wrote the copy, and hand-coded the site for robustness and speed. Added a product management module (the client updates inventory themselves), a Google Maps profile, and a promo article on Kudy z nudy. The site now matches the quality of the service behind it.',
                    'to'   => 'I started with a deep look at the business and at Czech and foreign competitors. I designed the structure, wrote the copy, and hand-coded the site for robustness and speed. Added a product management module (the client updates inventory themselves), a Google Maps profile, and a promo article on Kudy z nudy. The site now matches the quality of the service behind it.',
                ],
                'result' => [
                    'from' => 'The new site brought the first new customers within days of launch. The client confirms they finally have "a presentation they aren\'t ashamed of". Visitors quickly find what we service, what\'s available to rent, and how to get in touch — exactly what they came for.',
                    'to'   => 'The new site brought the first new customers within days of launch. The client confirms they finally have "a presentation they aren\'t ashamed of". Visitors quickly find what the workshop services, what\'s available to rent, and how to get in touch — exactly what they came for.',
                ],
            ],
            'de' => [
                'solution' => [
                    'from' => 'Wir begannen mit einer Analyse des Geschäfts sowie tschechischer und internationaler Wettbewerber. Ich habe Struktur, Texte und eigenes Design entworfen, alles handgecodet für Robustheit und Tempo. Dazu Produktverwaltung (der Kunde aktualisiert das Sortiment selbst), Google-Maps-Profil und ein Werbeartikel auf Kudy z nudy. Die Website entspricht jetzt der Qualität des Service.',
                    'to'   => 'Ich begann mit einer Analyse des Geschäfts sowie tschechischer und internationaler Wettbewerber. Ich habe Struktur, Texte und eigenes Design entworfen, alles handgecodet für Robustheit und Tempo. Dazu Produktverwaltung (der Kunde aktualisiert das Sortiment selbst), Google-Maps-Profil und ein Werbeartikel auf Kudy z nudy. Die Website entspricht jetzt der Qualität des Service.',
                ],
                'result' => [
                    'from' => 'Die neue Site brachte die ersten neuen Kunden innerhalb weniger Tage. Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss". Besucher finden schnell, was wir servicen, was zu mieten ist und wie man uns erreicht — genau dafür sind sie da.',
                    'to'   => 'Die neue Site brachte die ersten neuen Kunden innerhalb weniger Tage. Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss". Besucher finden schnell, was der Betrieb serviciert, was zu mieten ist und wie man Kontakt aufnimmt — genau dafür sind sie da.',
                ],
            ],
        ],
        'vp-industry' => [
            'cs' => [
                'solution' => [
                    'from' => 'Začali jsme analýzou konkurence a mapováním klíčových slov. Web má detailní produktové stránky s technickými specifikacemi, ukázkovými videi a případovými studiemi. SEO je technicky správně, analytika napojená a struktura počítá s budoucími články. Vše připravené, aby kampaň jen sedla na hotový základ.',
                    'to'   => 'Začal jsem analýzou konkurence a mapováním klíčových slov. Web má detailní produktové stránky s technickými specifikacemi, ukázkovými videi a případovými studiemi. SEO je technicky správně, analytika napojená a struktura počítá s budoucími články. Vše připravené, aby kampaň jen sedla na hotový základ.',
                ],
            ],
            'en' => [
                'challenge' => [
                    'from' => 'The client wanted a site set up for long-term organic growth, not a few-month marketing burst. We had to understand the competitive landscape, define keywords, and build content and architecture that would not block that growth.',
                    'to'   => 'The client wanted a site set up for long-term organic growth, not a few-month marketing burst. The job was to understand the competitive landscape, define keywords, and build content and architecture that would not block that growth.',
                ],
                'solution' => [
                    'from' => 'We started with competitor research and keyword mapping. The site has detailed product pages with technical specs, sample videos and case studies. SEO is technically clean, analytics is in place, and the structure assumes future articles. The infrastructure is ready — a content campaign can drop straight onto it.',
                    'to'   => 'I started with competitor research and keyword mapping. The site has detailed product pages with technical specs, sample videos and case studies. SEO is technically clean, analytics is in place, and the structure assumes future articles. The infrastructure is ready — a content campaign can drop straight onto it.',
                ],
            ],
            'de' => [
                'solution' => [
                    'from' => 'Wir starteten mit Wettbewerbs- und Keyword-Recherche. Die Site hat detaillierte Produktseiten mit technischen Specs, Videos und Case Studies. SEO ist technisch sauber, Analytics läuft, und die Struktur rechnet mit zukünftigen Artikeln. Die Infrastruktur ist bereit — eine Content-Kampagne kann direkt aufsetzen.',
                    'to'   => 'Ich startete mit Wettbewerbs- und Keyword-Recherche. Die Site hat detaillierte Produktseiten mit technischen Specs, Videos und Case Studies. SEO ist technisch sauber, Analytics läuft, und die Struktur rechnet mit zukünftigen Artikeln. Die Infrastruktur ist bereit — eine Content-Kampagne kann direkt aufsetzen.',
                ],
            ],
        ],
        'strechy-zajic' => [
            'en' => [
                'challenge' => [
                    'from' => 'The client needed a presentation that proves quality of work more convincingly than a few Facebook photos. We had to structure the services (roof reconstruction, carpentry, building work), set up a reference gallery, and add trade articles that support SEO and trust.',
                    'to'   => 'The client needed a presentation that proves quality of work more convincingly than a few Facebook photos. The job was to structure the services (roof reconstruction, carpentry, building work), set up a reference gallery, and add trade articles that support SEO and trust.',
                ],
            ],
        ],
    ];

    /** slug => locale => sort_order => ['from' => ..., 'to' => ...] */
    private const OUTCOME_CHANGES = [
        'pitarena' => [
            'cs' => [
                1 => ['from' => 'Online rezervace tréninků, voucherů i akcí bez ručního zásahu', 'to' => 'Online prodej poukazů a registrace na závody bez ručního zásahu'],
                2 => ['from' => 'Stovky návštěv týdně, plné kapacity měsíce dopředu', 'to' => 'Programy, půjčovna, servis a e-shop na jednom webu'],
                3 => ['from' => 'SEO + obsahové články přivádějí poptávky z Googlu', 'to' => 'SEO a obsahové články cílené na motokrosové kempy a pitbike tréninky'],
            ],
            'en' => [
                1 => ['from' => 'Online booking for training, vouchers and events runs hands-off', 'to' => 'Online voucher sales and race registrations run hands-off'],
                2 => ['from' => 'Hundreds of weekly visits, capacity booked months ahead', 'to' => 'Programmes, rentals, service and online shop on one site'],
                3 => ['from' => 'SEO and content drive enquiries straight from Google', 'to' => 'SEO and content aimed at motocross camps and pitbike training'],
            ],
            'de' => [
                1 => ['from' => 'Online-Buchung von Training, Gutscheinen und Events ohne Handarbeit', 'to' => 'Online-Gutscheinverkauf und Rennanmeldungen ohne Handarbeit'],
                2 => ['from' => 'Hunderte Besuche pro Woche, Kapazität Monate im Voraus voll', 'to' => 'Programme, Verleih, Service und Onlineshop auf einer Site'],
                3 => ['from' => 'SEO und Content liefern Anfragen direkt aus Google', 'to' => 'SEO und Content für Motocross-Camps und Pitbike-Training'],
            ],
        ],
    ];

    /** Nový projekt shop.pitarena.cz — texty z `pripadovky`, čísla z `namerena-cisla`. */
    private const NEW_PROJECT = [
        'slug'        => 'pitarena-eshop',
        'category'    => 'website',
        'client_name' => 'PitArena',
        'live_url'    => 'https://shop.pitarena.cz',
        'year'        => null,  // rok spuštění e-shopu není doložený
        'duration'    => null,
        'featured'    => true,
        'sort_order'  => 15,  // hned za `pitarena` (10), před `hcms` (20)
        // Jen štítky, které v DB už existují — nový slug by `PortfolioSeeder`
        // při čerstvém seedu pojmenoval přes humanizeSlug („E shop"), tedy
        // přesně ten žargon, který opravovala migrace z OND-198. Díky tomu
        // dává re-seed z YAMLu i tato migrace shodný výsledek.
        'tags'        => ['web', 'motocykly'],
        'translations' => [
            'cs' => [
                'title' => 'PitAréna — e-shop',
                'subtitle' => 'E-shop, kde zákazník najde díl na svůj model a ročník',
                'summary' => 'E-shop s pitbike motorkami YCF a náhradními díly. Díly roztříděné podle modelů a skupin, filtrování podle modelu a ročníku.',
                'description' => 'Klient prodává pitbike motorky YCF a náhradní díly. Potřeboval prodávat online — a u dílů je klíčové poskládat správný kus pro daný model a ročník. Postavil jsem e-shop s katalogem motorek i dílů, roztříděných podle modelů a skupin.',
                'challenge' => 'U náhradních dílů nestačí seznam produktů. Zákazník potřebuje jistotu, že díl sedne na jeho model a ročník — jinak nakupuje naslepo a vrací. Katalog o tisícových počtech položek musel zůstat průchodný, ne se z něj stát nekonečný seznam.',
                'solution' => 'Postavil jsem e-shop s katalogem motorek i dílů. Díly jsou roztříděné podle modelů (LITE 125, PILOT 125, Factory 190) a skupin — brzdy, motory, tlumiče, elektrika. K tomu filtrování podle modelu a ročníku, košík a zákaznický účet, oblíbené položky a porovnání produktů.',
                'result' => 'E-shop dnes nabízí 4 551 produktů v 695 kategoriích a katalog náhradních dílů rozpadnutý podle 19 modelů motocyklů YCF — zákazník klikne na svůj model a vidí jen díly, které na něj sedí. I s tímhle rozsahem drží na desktopu 99/100 v Lighthouse Performance a hlavní obsah se vykreslí do 0,9 s (měřeno lokálním Lighthouse 13.4.1, 16. 9. 2026).',
                'meta_title' => 'PitAréna — e-shop s motorkami YCF a náhradními díly',
                'meta_description' => 'Případová studie: e-shop se 4 551 produkty v 695 kategoriích. Náhradní díly roztříděné podle 19 modelů YCF, filtrování podle modelu a ročníku.',
                'outcomes' => [
                    '4 551 produktů v 695 kategoriích',
                    'Katalog náhradních dílů podle 19 modelů motocyklů YCF',
                    'Filtrování podle modelu a ročníku',
                    'Košík, zákaznický účet, oblíbené položky a porovnání produktů',
                    '99/100 v Lighthouse Performance na desktopu (měřeno 16. 9. 2026)',
                ],
            ],
            'en' => [
                'title' => 'PitArena — online shop',
                'subtitle' => 'An online shop where customers find the part for their model and year',
                'summary' => 'Online shop for YCF pitbikes and spare parts. Parts sorted by model and group, with filtering by model and year.',
                'description' => 'The client sells YCF pitbikes and spare parts and needed to sell online — and with parts, the crucial thing is matching the right piece to the model and year. I built an online shop with a catalogue of both bikes and parts, sorted by model and group.',
                'challenge' => 'With spare parts, a product list is not enough. The customer needs certainty that a part fits their model and year — otherwise they buy blind and send it back. A catalogue running into thousands of items had to stay navigable instead of turning into an endless list.',
                'solution' => 'I built an online shop with a catalogue of bikes and parts. Parts are sorted by model (LITE 125, PILOT 125, Factory 190) and by group — brakes, engines, suspension, electrics. Plus filtering by model and year, a cart and customer account, favourites and product comparison.',
                'result' => 'The shop now offers 4,551 products in 695 categories, with the spare-parts catalogue broken down across 19 YCF motorcycle models — the customer clicks their model and sees only parts that fit. Even at that scale it holds 99/100 in Lighthouse Performance on desktop, with the main content rendering in under 0.9 s (measured with local Lighthouse 13.4.1 on 16 September 2026).',
                'meta_title' => 'PitArena — online shop for YCF pitbikes and spare parts',
                'meta_description' => 'Case study: an online shop with 4,551 products in 695 categories. Spare parts split across 19 YCF models, filtering by model and year.',
                'outcomes' => [
                    '4,551 products in 695 categories',
                    'Spare-parts catalogue split across 19 YCF motorcycle models',
                    'Filtering by model and year',
                    'Cart, customer account, favourites and product comparison',
                    '99/100 in Lighthouse Performance on desktop (measured 16 September 2026)',
                ],
            ],
            'de' => [
                'title' => 'PitArena — Onlineshop',
                'subtitle' => 'Ein Shop, in dem Kunden das Teil für Modell und Baujahr finden',
                'summary' => 'Onlineshop für YCF-Pitbikes und Ersatzteile. Teile nach Modellen und Gruppen sortiert, mit Filter nach Modell und Baujahr.',
                'description' => 'Der Kunde verkauft YCF-Pitbikes und Ersatzteile und wollte online verkaufen — bei Teilen ist entscheidend, das richtige Stück für Modell und Baujahr zu treffen. Ich habe einen Onlineshop mit Katalog für Motorräder und Teile gebaut, sortiert nach Modellen und Gruppen.',
                'challenge' => 'Bei Ersatzteilen genügt eine Produktliste nicht. Kunden brauchen die Sicherheit, dass ein Teil auf ihr Modell und Baujahr passt — sonst kaufen sie blind und schicken zurück. Ein Katalog mit Tausenden Positionen musste begehbar bleiben, statt zur endlosen Liste zu werden.',
                'solution' => 'Ich habe einen Onlineshop mit Katalog für Motorräder und Teile gebaut. Die Teile sind nach Modellen (LITE 125, PILOT 125, Factory 190) und Gruppen sortiert — Bremsen, Motoren, Federung, Elektrik. Dazu Filter nach Modell und Baujahr, Warenkorb und Kundenkonto, Favoriten und Produktvergleich.',
                'result' => 'Der Shop bietet heute 4.551 Produkte in 695 Kategorien, der Ersatzteilkatalog ist nach 19 YCF-Motorradmodellen aufgeteilt — Kunden klicken ihr Modell an und sehen nur passende Teile. Auch in dieser Größe hält er auf dem Desktop 99/100 in Lighthouse Performance, der Hauptinhalt erscheint in unter 0,9 s (gemessen mit lokalem Lighthouse 13.4.1 am 16.09.2026).',
                'meta_title' => 'PitArena — Onlineshop für YCF-Pitbikes und Ersatzteile',
                'meta_description' => 'Case Study: Onlineshop mit 4.551 Produkten in 695 Kategorien. Ersatzteile nach 19 YCF-Modellen aufgeteilt, Filter nach Modell und Baujahr.',
                'outcomes' => [
                    '4.551 Produkte in 695 Kategorien',
                    'Ersatzteilkatalog nach 19 YCF-Motorradmodellen aufgeteilt',
                    'Filter nach Modell und Baujahr',
                    'Warenkorb, Kundenkonto, Favoriten und Produktvergleich',
                    '99/100 in Lighthouse Performance auf dem Desktop (gemessen 16.09.2026)',
                ],
            ],
        ],
    ];

    public function up(): void
    {
        // Na dosud nenaplněné DB (čerstvá instalace, `RefreshDatabase` v testech)
        // není co opravovat — zdrojem pravdy je `docs/portfolio-data.yaml`
        // a PortfolioSeeder, který má stejné texty včetně `pitarena-eshop`.
        // Kdyby migrace projekt založila sama, rozbila by to seeder: ten se při
        // jakémkoli existujícím projektu přeskakuje, takže by zbylých 23
        // projektů nikdy nevzniklo.
        if (! $this->portfolioIsPopulated()) {
            return;
        }

        $this->applyTexts('to');
        $this->applyOutcomes('to');
        $this->createEshopProject();
    }

    public function down(): void
    {
        if (! $this->portfolioIsPopulated()) {
            return;
        }

        $this->removeEshopProject();
        $this->applyOutcomes('from');
        $this->applyTexts('from');
    }

    private function portfolioIsPopulated(): bool
    {
        return DB::table('portfolio_projects')->exists();
    }

    /**
     * $direction 'to' = nasadit nový text, 'from' = vrátit původní.
     * Aktualizuje jen řádky, které stále obsahují očekávanou výchozí hodnotu.
     */
    private function applyTexts(string $direction): void
    {
        $expected = $direction === 'to' ? 'from' : 'to';

        foreach (self::TEXT_CHANGES as $slug => $locales) {
            $projectId = DB::table('portfolio_projects')->where('slug', $slug)->value('id');
            if (! $projectId) {
                continue;
            }

            foreach ($locales as $locale => $fields) {
                foreach ($fields as $field => $texts) {
                    DB::table('portfolio_project_translations')
                        ->where('project_id', $projectId)
                        ->where('locale', $locale)
                        ->where($field, $texts[$expected])
                        ->update([$field => $texts[$direction], 'updated_at' => now()]);
                }
            }
        }
    }

    private function applyOutcomes(string $direction): void
    {
        $expected = $direction === 'to' ? 'from' : 'to';

        foreach (self::OUTCOME_CHANGES as $slug => $locales) {
            $projectId = DB::table('portfolio_projects')->where('slug', $slug)->value('id');
            if (! $projectId) {
                continue;
            }

            foreach ($locales as $locale => $rows) {
                foreach ($rows as $sortOrder => $texts) {
                    $outcomeId = DB::table('portfolio_project_outcomes')
                        ->where('project_id', $projectId)
                        ->where('sort_order', $sortOrder)
                        ->value('id');
                    if (! $outcomeId) {
                        continue;
                    }

                    DB::table('portfolio_project_outcome_translations')
                        ->where('outcome_id', $outcomeId)
                        ->where('locale', $locale)
                        ->where('label', $texts[$expected])
                        ->update(['label' => $texts[$direction], 'updated_at' => now()]);
                }
            }
        }
    }

    private function createEshopProject(): void
    {
        $row = self::NEW_PROJECT;

        // Idempotence: když projekt už existuje, nezasahuj do něj.
        if (DB::table('portfolio_projects')->where('slug', $row['slug'])->exists()) {
            return;
        }

        $now = now();

        $projectId = DB::table('portfolio_projects')->insertGetId([
            'category'     => $row['category'],
            'slug'         => $row['slug'],
            'client_name'  => $row['client_name'],
            'live_url'     => $row['live_url'],
            'year'         => $row['year'],
            'duration'     => $row['duration'],
            'featured'     => $row['featured'],
            'sort_order'   => $row['sort_order'],
            'published_at' => $now,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        foreach ($row['translations'] as $locale => $t) {
            DB::table('portfolio_project_translations')->insert([
                'project_id'       => $projectId,
                'locale'           => $locale,
                'title'            => $t['title'],
                'subtitle'         => $t['subtitle'],
                'summary'          => $t['summary'],
                'description'      => $t['description'],
                'challenge'        => $t['challenge'],
                'solution'         => $t['solution'],
                'result'           => $t['result'],
                'meta_title'       => $t['meta_title'],
                'meta_description' => $t['meta_description'],
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // Outcomes: jeden řádek na odrážku, překlady v `label` (stejně jako
        // PortfolioSeeder — `value` zůstává prázdný string).
        $outcomeCount = count($row['translations']['cs']['outcomes']);
        for ($i = 0; $i < $outcomeCount; $i++) {
            $outcomeId = DB::table('portfolio_project_outcomes')->insertGetId([
                'project_id' => $projectId,
                'sort_order' => $i,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($row['translations'] as $locale => $t) {
                DB::table('portfolio_project_outcome_translations')->insert([
                    'outcome_id'  => $outcomeId,
                    'locale'      => $locale,
                    'label'       => $t['outcomes'][$i],
                    'value'       => '',
                    'description' => null,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // Štítky `web` i `motocykly` v DB existují, nové se nezakládají.
        foreach ($row['tags'] as $tagSlug) {
            $id = DB::table('portfolio_tags')->where('slug', $tagSlug)->value('id');
            if (! $id) {
                continue;
            }
            DB::table('portfolio_project_tag')->insertOrIgnore([
                'project_id' => $projectId,
                'tag_id'     => $id,
            ]);
        }
    }

    private function removeEshopProject(): void
    {
        $projectId = DB::table('portfolio_projects')
            ->where('slug', self::NEW_PROJECT['slug'])
            ->value('id');
        if (! $projectId) {
            return;
        }

        // Překlady, outcomes i jejich překlady odejdou přes cascadeOnDelete;
        // pivot tagů cizí klíč nemá, mažu ho ručně.
        DB::table('portfolio_project_tag')->where('project_id', $projectId)->delete();
        DB::table('portfolio_projects')->where('id', $projectId)->delete();

        // Štítky se nemažou — `web` i `motocykly` používají i jiné projekty.
    }
};

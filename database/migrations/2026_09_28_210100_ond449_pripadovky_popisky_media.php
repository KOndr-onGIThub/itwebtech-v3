<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-449 — případovky podle plánu OND-441 (dokument `planovane-zmeny`),
 * texty cs/en/de z dokumentu `texty-en-de` na OND-446.
 *
 *   B-03  PitArena: texty, výstupy, štítek „Rezervace“ pryč.
 *   B-04  BARANA: texty, výstupy, štítek „Placená reklama“ pryč, 5 týdnů zůstává.
 *   B-05  `live_hint`: věta „co si na živém webu vyzkoušet“ u 13 projektů s `live_url`.
 *   B-06  choccoboard: lead (a tím i karta) je `gallery-1` (přehled s KPI), ne přihlášení.
 *   B-07  popisky (alt = titulek v lightboxu) místo „sekce N“; z galerie pryč
 *         `excel-tools/gallery-3` („#VALUE!“).
 *   B-07b BARANA: nové snímky mají stejné cesty jako staré (`hero-1`, `gallery-1..3`),
 *         plakát je teď `gallery-4.png`, `gallery-5` z galerie pryč. Tady se mění jen
 *         popisky a mazání řádku; video zapíná sloupec `demo_video` (BARANA
 *         a Kemp Veselka, galerie Veselky beze změny).
 *   B-09  PitArena: `result_as_of` + `result_source` (řádek „Stav k …“ pod výsledkem).
 *
 * Každá změna se provede, jen když v DB stojí přesně původní hodnota z produkce
 * (28. 9. 2026). Druhý běh je proto no-op a ruční úprava z Filamentu se nepřepíše.
 * Nové sloupce se plní jen, když jsou prázdné. `down()` vrací totéž zpátky za
 * stejných podmínek.
 *
 * Na prázdné DB je migrace inertní: projekty tam zakládá PortfolioSeeder
 * z `docs/portfolio-data.yaml`, který je srovnaný na stejný výsledek.
 */
return new class extends Migration
{
    private const LOCALES = ['cs', 'en', 'de'];

    /** Štítky k odebrání: slug projektu => slug štítku */
    private const DETACH_TAGS = [
        'pitarena' => 'rezervace',
        'barana'   => 'kampane',
    ];

    /** Video pod prvním blokem galerie (B-07b): slug => základ jména v public/videos/portfolio */
    private const DEMO_VIDEOS = [
        'barana'       => 'barana-demo',
        'kemp-veselka' => 'kemp-veselka-demo',   // případovka balíčku „Prezentační web“ na /cenik (B-08)
    ];

    /** B-06: choccoboard — lead přehodit z přihlášení (`hero-1`) na přehled (`gallery-1`). */
    private const LEAD_SWAP = [
        'slug' => 'choccoboard',
        'from' => 'projects/choccoboard/hero-1.webp',
        'to'   => 'projects/choccoboard/gallery-1.webp',
    ];

    private const TEXTS = [
        'pitarena' => [
            'cs' => [
                'meta_title' => [
                    'PitArena — web, rezervace a brand pro motokrosové centrum',
                    'PitArena — web, závodní systém a značka pro motokrosové centrum',
                ],
                'subtitle' => [
                    'Od nuly ke značce, webu a online prodeji programů',
                    'Od nuly ke značce, webu a vlastnímu systému pro závody',
                ],
                'description' => [
                    'PitArena startovala bez webu, brandu i bez první zakázky. Postavil jsem kompletní digitální základ — značku, web s nabídkou programů, online prodejem poukazů a registracemi na závody. Od roku 2023 web dál rozvíjím spolu s majitelem.',
                    'PitArena startovala bez webu, značky i bez prvního zákazníka. Postavil jsem značku a web s programy, půjčovnou, servisem a vlastním systémem pro závody YCF Cup: registrace, platby, výsledky a profily jezdců. Od roku 2023 web dál rozvíjím spolu s majitelem.',
                ],
                'summary' => [
                    'Značka, web a online prodej pro nové motokrosové centrum. Web dnes nese programy, poukazy na jízdu i registrace na závody.',
                    'PitArena startovala bez webu, značky i bez prvního zákazníka. Postavil jsem značku a web s programy, půjčovnou, servisem a vlastním systémem pro závody YCF Cup: registrace, platby, výsledky a profily jezdců. Od roku 2023 web dál rozvíjím spolu s majitelem.',
                ],
                'challenge' => [
                    'Klient otevíral nové motokrosové centrum v Česku. Žádný web, žádná značka, nula obsazenosti. Bylo potřeba vybudovat důvěru ještě dřív, než se PitArena fyzicky otevřela, a zařídit, aby si lidé tréninky mohli objednat 24/7 bez telefonátů a e-mailů.',
                    'Klient otevíral nové motokrosové centrum. Žádný web, žádná značka, nula zákazníků. Důvěru bylo potřeba vybudovat dřív, než se areál otevřel, a rodičům srozumitelně vysvětlit, jak s motokrosem u dětí začít.',
                ],
                'solution' => [
                    'Navrhl jsem značku a postavil na míru web s online prodejem poukazů na jízdu, nabídkou programů (akademie, zájmový kroužek, kempy a MX soustředění) a registracemi na závody YCF Cup. SEO a obsahové články cílí na lidi, kteří hledají „motokrosový kemp“ nebo „pitbike trénink“. Doplňuje to správa sociálních sítí a propojení s merchem.',
                    'Navrhl jsem značku a postavil na míru web s nabídkou programů (akademie, zájmový kroužek, kempy a MX soustředění), půjčovnou, servisem a průvodci pro rodiče. Poukazy na jízdu vedou do e-shopu PitArena, který jsem postavil zvlášť. Když se web chytil, převedli jsme na něj i závody YCF Cup ze samostatného webu na Webnode. Tam byla přihláška a platba dva nepropojené kroky, výsledky jen v PDF a pronájem vycházel zhruba na 5 000 Kč ročně. Dnes má seriál vlastní systém: registrace s výpočtem ceny a platbou online, rezervace startovních čísel, správa objednávky přes odkaz v e-mailu, průběžné pořadí po každém závodě a profily více než 150 jezdců.',
                ],
                'result' => [
                    'Z Googlu si web řekne o víc než 15 000 návštěv za 16 měsíců (Search Console, 16. 5. 2025 – 13. 9. 2026: 15 472 prokliků při 288 925 zobrazeních) a průměrná pozice ve vyhledávání se za tu dobu zvedla z 10,3 na 7,6. Zájemce si sám najde program, koupí poukaz na jízdu nebo se přihlásí na závod, bez telefonátu majiteli. Vedle toho web nese e-shop, půjčovnu, servis a blog. Dlouhodobá spolupráce pokračuje dodnes.',
                    'Na hledání „motokros pro děti“ a „motocross pro děti“ je web v Google první už od prvního roku a drží se tam dodnes. První je i na „ycf cup“ a „závody pitbike“, v Google i na Seznamu. Z Googlu na web přišlo přes 15 000 návštěv za 16 měsíců. Přihlášky, platby i výsledky závodů jsou na jednom místě pod značkou PitArena. Spolupráce pokračuje dodnes.',
                ],
                'meta_description' => [
                    'Případová studie: značka, web a online prodej pro PitArenu — programy, poukazy na jízdu a registrace na závody na jednom webu.',
                    'Případová studie: značka a web pro motokrosové centrum PitArena včetně vlastního systému pro závody YCF Cup — registrace, platby a výsledky.',
                ],
            ],
            'en' => [
                'meta_title' => [
                    'PitArena — website, booking & brand for a motocross centre',
                    'PitArena — website, race system and brand for a motocross centre',
                ],
                'subtitle' => [
                    'From zero to a brand, a website and online sales',
                    'From zero to a brand, a website and its own race system',
                ],
                'description' => [
                    'PitArena launched with no website, no brand, and no customers. I built the full digital foundation — identity, a custom website with programme listings, online voucher sales and race registrations. The site has been growing alongside the owner since 2023.',
                    'PitArena launched with no website, no brand and not a single customer. I built the brand and a website with programmes, rentals, service and its own system for the YCF Cup races: registrations, payments, results and rider profiles. Since 2023 I have kept developing the site together with the owner.',
                ],
                'summary' => [
                    'Brand, website and online sales for a brand-new motocross centre. The site now carries programmes, ride vouchers and race registrations.',
                    'PitArena launched with no website, no brand and not a single customer. I built the brand and a website with programmes, rentals, service and its own system for the YCF Cup races: registrations, payments, results and rider profiles. Since 2023 I have kept developing the site together with the owner.',
                ],
                'challenge' => [
                    'The client was opening a new motocross training centre in Czechia. No web presence, no brand, zero occupancy. Trust had to be built before PitArena physically opened, and let people book training 24/7 — without phone calls or e-mails.',
                    'The client was opening a new motocross centre. No website, no brand, zero customers. Trust had to be built before the grounds opened, and parents needed a clear explanation of how children get started in motocross.',
                ],
                'solution' => [
                    'I designed the brand and built a custom website with online ride-voucher sales, programme listings (academy, youth club, camps and MX training weeks) and race registrations for the YCF Cup. SEO and content articles target people searching for "motocross camp" or "pitbike training". Social media management and merch integration round it out.',
                    'I designed the brand and built a custom website with the programmes (academy, youth club, camps and MX training weeks), rentals, service and guides for parents. Ride vouchers lead to the PitArena online shop, which I built separately. Once the site took off, we also moved the YCF Cup races onto it from a separate website on Webnode. There, registration and payment were two unconnected steps, results were only available as PDFs and the subscription came to roughly CZK 5,000 a year. Today the series has its own system: registration with price calculation and online payment, start number reservations, order management through a link in the e-mail, standings updated after every race and profiles of more than 150 riders.',
                ],
                'result' => [
                    'The site pulls more than 15,000 visits from Google over 16 months (Search Console, 16 May 2025 – 13 Sep 2026: 15,472 clicks on 288,925 impressions), and its average search position rose from 10.3 to 7.6 over that period. A visitor finds a programme, buys a ride voucher or signs up for a race without calling the owner. Alongside that it carries the online shop, rentals, service and a blog. The long-term partnership continues today.',
                    'For the searches “motokros pro děti” and “motocross pro děti” (Czech for “motocross for kids”), the site has been first on Google since its first year and still is today. It is also first for “ycf cup” and “závody pitbike” (pitbike races), on Google and on the Czech search engine Seznam. Google sent over 15,000 visits to the site in 16 months. Registrations, payments and race results are all in one place under the PitArena brand. The partnership continues today.',
                ],
                'meta_description' => [
                    'Case study: brand, website and online sales for PitArena — programmes, ride vouchers and race registrations on one site.',
                    'Case study: brand and website for the PitArena motocross centre, including its own system for the YCF Cup races — registration, payments and results.',
                ],
            ],
            'de' => [
                'meta_title' => [
                    'PitArena — Website, Buchung & Marke für ein Motocross-Zentrum',
                    'PitArena — Website, Rennsystem und Marke für ein Motocross-Zentrum',
                ],
                'subtitle' => [
                    'Von Null zu Marke, Website und Online-Verkauf',
                    'Von null zu Marke, Website und eigenem Rennsystem',
                ],
                'description' => [
                    'PitArena startete ohne Website, ohne Marke und ohne Kunden. Ich habe die gesamte digitale Grundlage gebaut — Identität, eine maßgeschneiderte Website mit Programmübersicht, Online-Gutscheinverkauf und Rennanmeldungen. Seit 2023 entwickle ich die Site gemeinsam mit dem Inhaber weiter.',
                    'PitArena startete ohne Website, ohne Marke und ohne einen einzigen Kunden. Ich habe die Marke und eine Website mit Programmen, Verleih, Service und einem eigenen System für die Rennen des YCF Cup gebaut: Anmeldungen, Zahlungen, Ergebnisse und Fahrerprofile. Seit 2023 entwickle ich die Website gemeinsam mit dem Inhaber weiter.',
                ],
                'summary' => [
                    'Marke, Website und Online-Verkauf für ein neues Motocross-Zentrum. Die Site trägt heute Programme, Fahrgutscheine und Rennanmeldungen.',
                    'PitArena startete ohne Website, ohne Marke und ohne einen einzigen Kunden. Ich habe die Marke und eine Website mit Programmen, Verleih, Service und einem eigenen System für die Rennen des YCF Cup gebaut: Anmeldungen, Zahlungen, Ergebnisse und Fahrerprofile. Seit 2023 entwickle ich die Website gemeinsam mit dem Inhaber weiter.',
                ],
                'challenge' => [
                    'Der Kunde eröffnete ein neues Motocross-Trainingszentrum in Tschechien. Keine Website, keine Marke, null Auslastung. Vertrauen musste entstehen, bevor PitArena physisch öffnete — und Menschen mussten 24/7 buchen können, ohne Anrufe oder E-Mails.',
                    'Der Kunde eröffnete ein neues Motocross-Zentrum. Keine Website, keine Marke, null Kunden. Vertrauen musste entstehen, bevor das Gelände öffnete, und Eltern sollten verständlich erfahren, wie Kinder mit Motocross anfangen.',
                ],
                'solution' => [
                    'Ich habe die Marke entworfen und eine individuelle Website gebaut — mit Online-Verkauf von Fahrgutscheinen, Programmübersicht (Akademie, Jugendclub, Camps und MX-Trainingswochen) und Anmeldungen zum YCF Cup. SEO- und Content-Artikel zielen auf Suchen wie „Motocross-Camp“ oder „Pitbike-Training“. Social-Media-Betreuung und Merch-Anbindung runden alles ab.',
                    'Ich habe die Marke entworfen und eine individuelle Website gebaut, mit den Programmen (Akademie, Jugendclub, Camps und MX-Trainingswochen), Verleih, Service und Ratgebern für Eltern. Fahrgutscheine führen in den PitArena-Onlineshop, den ich separat gebaut habe. Als die Website Fuß gefasst hatte, haben wir auch die Rennen des YCF Cup von einer eigenen Website auf Webnode hierher geholt. Dort waren Anmeldung und Zahlung zwei getrennte Schritte, die Ergebnisse gab es nur als PDF, und das Abo kostete rund 5.000 CZK im Jahr. Heute hat die Serie ein eigenes System: Anmeldung mit Preisberechnung und Online-Zahlung, Reservierung der Startnummern, Verwaltung der Bestellung über einen Link in der E-Mail, Zwischenstand nach jedem Rennen und Profile von mehr als 150 Fahrern.',
                ],
                'result' => [
                    'Aus Google holt die Site in 16 Monaten mehr als 15.000 Besuche (Search Console, 16.5.2025 – 13.9.2026: 15.472 Klicks bei 288.925 Impressionen), und die durchschnittliche Suchposition verbesserte sich in dieser Zeit von 10,3 auf 7,6. Interessenten finden ein Programm, kaufen einen Fahrgutschein oder melden sich zum Rennen an, ohne beim Inhaber anzurufen. Daneben trägt sie Onlineshop, Verleih, Service und Blog. Die langfristige Zusammenarbeit läuft bis heute.',
                    'Bei den Suchen „motokros pro děti“ und „motocross pro děti“ (tschechisch für „Motocross für Kinder“) steht die Website bei Google seit ihrem ersten Jahr auf Platz eins und hält sich dort bis heute. Auf Platz eins ist sie auch bei „ycf cup“ und „závody pitbike“ (Pitbike-Rennen), bei Google und bei der tschechischen Suchmaschine Seznam. Aus Google kamen in 16 Monaten über 15.000 Besuche auf die Website. Anmeldungen, Zahlungen und Rennergebnisse liegen an einem Ort unter der Marke PitArena. Die Zusammenarbeit läuft bis heute.',
                ],
                'meta_description' => [
                    'Case Study: Marke, Website und Online-Verkauf für PitArena — Programme, Fahrgutscheine und Rennanmeldungen auf einer Site.',
                    'Case Study: Marke und Website für das Motocross-Zentrum PitArena, samt eigenem System für die Rennen des YCF Cup — Anmeldung, Zahlung und Ergebnisse.',
                ],
            ],
        ],
        'barana' => [
            'cs' => [
                'meta_title' => [
                    'BARANA — prémiový web pro pergoly, ploty a brány',
                    'BARANA — prémiový web pro bioklimatické pergoly, brány a ploty',
                ],
                'subtitle' => [
                    'Prémiový web pro bioklimatické pergoly připravený na kampaně',
                    'Prémiový web, na kterém si návštěvník pergolu vyzkouší dřív, než zavolá',
                ],
                'description' => [
                    'BARANA prodává bioklimatické pergoly, hliníkové ploty a brány — tedy produkt, který si zákazník v hlavě staví dlouho. Web musí tu úvahu zkrátit a převést do poptávky. Postavil jsem prémiové prezentační stránky se silnou hierarchií a landing page přímo pro placené kampaně.',
                    'BARANA prodává bioklimatické pergoly za stovky tisíc korun. Postavil jsem web o devíti stránkách, který takovou věc srozumitelně vysvětlí a působí stejně prémiově jako výrobek. K tomu samostatnou stránku, na kterou vede placená reklama.',
                ],
                'summary' => [
                    'Premium prezentace pro firmu s bioklimatickými pergolami a hliníkovými ploty. Kampaň-ready landing page s jasnou cestou ke kontaktu.',
                    'BARANA prodává bioklimatické pergoly za stovky tisíc korun. Postavil jsem web o devíti stránkách, který takovou věc srozumitelně vysvětlí a působí stejně prémiově jako výrobek. K tomu samostatnou stránku, na kterou vede placená reklama.',
                ],
                'challenge' => [
                    'Klient potřeboval prezentaci, která se vizuálně postaví prémiové konkurenci a zároveň konvertuje. Web musel jasně rozdělit jednotlivé produktové linie, držet rychlost a fungovat přesně jako vstupní brána pro reklamní kampaně na Meta Ads a Google Ads — bez kompromisů na mobilu.',
                    'Bioklimatická pergola je drahá a pro většinu lidí neznámá věc. Kdo ji nikdy neviděl, nechápe, proč stojí 300 tisíc. Web musel během pár vteřin ukázat, co pergola umí, vzbudit důvěru a působit prémiově, protože prémiový je i výrobek.',
                ],
                'solution' => [
                    'Návrh stojí na minimalistické typografii, kontrastu a vizuální hierarchii „titulek → benefit → akce“. Hlavní landing je zaměřený přímo na bioklimatické pergoly s konverzním cílem, servisní stránky drží stejný rytmus a strukturu. Připraveno pro budoucí přidání referencí, recenzí a růst obsahu, aniž by se rozbila kompozice.',
                    'Místo dlouhého textu si návštěvník pergolu vyzkouší. V interaktivní ukázce natáčí lamely a vidí, co udělají v dešti, na slunci i ve větru. Při posouvání stránky projde stejnou terasu od ranního deště po zimní večer. Klikací schéma vysvětlí osm částí pergoly a průvodce výběrem doporučí sestavu podle světových stran. Ceny, technické parametry a odpovědi na stavební povolení nebo základy jsou otevřeně na webu. Pro placenou reklamu jsem přidal samostatnou stránku bez menu, se stejnou ukázkou lamel a krátkým formulářem.',
                ],
                'result' => [
                    'BARANA má prezentaci, kterou se nestydí pustit do placené inzerce. Návštěvník chápe nabídku bez nutnosti volat, mobil se chová stejně rychle jako desktop a struktura je připravená na další produktové sekce. Web je živý spuštěný nástroj pro získávání zakázek, ne jen vizitka.',
                    'Za pět intenzivních týdnů vznikl web, který se načítá okamžitě (server odpoví zhruba za 0,2 s, fotky v moderních formátech) a na mobilu drží stejnou kvalitu. Každá poptávka se měří podle toho, odkud přišla, takže klient i jeho reklamní partner vidí, která cesta funguje.',
                ],
                'meta_description' => [
                    'Případová studie: prémiová prezentace BARANA se samostatnou stránkou pro Meta Ads a Google Ads. Postaveno za 5 týdnů.',
                    'Případová studie: prémiový web pro bioklimatické pergoly BARANA za pět týdnů — interaktivní ukázka lamel, průvodce výběrem, otevřené ceny a stránka pro placenou reklamu.',
                ],
            ],
            'en' => [
                'meta_title' => [
                    'BARANA — premium website for pergolas, fences & gates',
                    'BARANA — premium website for bioclimatic pergolas, gates and fences',
                ],
                'subtitle' => [
                    'Premium website for bioclimatic pergolas, ready for ad campaigns',
                    'A premium website where visitors try out the pergola before they call',
                ],
                'description' => [
                    'BARANA sells bioclimatic pergolas, aluminium fences and gates — a considered purchase that takes time. The website\'s job is to shorten that thinking and convert it into an enquiry. I built a premium presentation site with a strong hierarchy plus a landing page wired up directly for paid campaigns.',
                    'BARANA sells bioclimatic pergolas that cost hundreds of thousands of Czech crowns. I built a nine-page website that explains a product like that clearly and feels as premium as the product itself. On top of that, a separate page that paid ads lead to.',
                ],
                'summary' => [
                    'Premium presentation for a maker of bioclimatic pergolas and aluminium fences. Campaign-ready landing page with a clear path to enquiry.',
                    'BARANA sells bioclimatic pergolas that cost hundreds of thousands of Czech crowns. I built a nine-page website that explains a product like that clearly and feels as premium as the product itself. On top of that, a separate page that paid ads lead to.',
                ],
                'challenge' => [
                    'The client needed a site that visually stands up to premium competitors and still converts. It had to separate the product lines clearly, stay fast, and work as an entry point for Meta Ads and Google Ads — with no compromise on mobile.',
                    'A bioclimatic pergola is expensive and unfamiliar to most people. Anyone who has never seen one does not understand why it costs CZK 300,000. Within a few seconds the website had to show what the pergola can do, build trust and feel premium, because the product is premium too.',
                ],
                'solution' => [
                    'The design uses minimalist typography, contrast, and a "headline → benefit → action" hierarchy. The main landing focuses on bioclimatic pergolas with a single conversion goal; service pages keep the same rhythm. Built so future reviews, references, and new content can scale in without breaking composition.',
                    'Instead of reading long text, visitors try the pergola out. In an interactive demo they tilt the louvres and see what they do in rain, sun and wind. As they scroll, they walk through the same terrace from morning rain to a winter evening. A clickable diagram explains eight parts of the pergola, and a selection guide recommends a set-up based on which way the terrace faces. Prices, technical specifications and answers on building permits and foundations are openly on the website. For paid advertising I added a separate page without a menu, with the same louvre demo and a short form.',
                ],
                'result' => [
                    'BARANA now has a site they\'re confident pushing paid traffic to. Visitors understand the offer without picking up the phone, mobile is as fast as desktop, and the structure is ready for new product sections. It\'s a live tool for winning orders — not a digital business card.',
                    'In five intensive weeks a website came together that loads instantly (the server responds in about 0.2 s, photos in modern formats) and keeps the same quality on a phone. Every enquiry is tracked by where it came from, so the client and the advertising partner can see which route works.',
                ],
                'meta_description' => [
                    'Case study: BARANA premium presentation with a landing page for Meta Ads and Google Ads. Built in 5 weeks, ready for paid campaigns.',
                    'Case study: a premium website for BARANA bioclimatic pergolas in five weeks — interactive louvre demo, selection guide, open prices and a page for paid ads.',
                ],
            ],
            'de' => [
                'meta_title' => [
                    'BARANA — Premium-Website für Pergolen, Zäune und Tore',
                    'BARANA — Premium-Website für bioklimatische Pergolen, Tore und Zäune',
                ],
                'subtitle' => [
                    'Premium-Website für bioklimatische Pergolen, kampagnenbereit',
                    'Eine Premium-Website, auf der Besucher die Pergola ausprobieren, bevor sie anrufen',
                ],
                'description' => [
                    'BARANA verkauft bioklimatische Pergolen, Aluminiumzäune und Tore — eine bedachte Anschaffung, die lange reift. Die Website muss diese Überlegung verkürzen und in eine Anfrage überführen. Entstanden ist eine Premium-Präsentation mit starker Hierarchie plus Landingpage, direkt für bezahlte Kampagnen verdrahtet.',
                    'BARANA verkauft bioklimatische Pergolen für Hunderttausende Kronen. Ich habe eine Website mit neun Seiten gebaut, die so ein Produkt verständlich erklärt und so hochwertig wirkt wie das Produkt selbst. Dazu eine eigene Seite, auf die bezahlte Werbung führt.',
                ],
                'summary' => [
                    'Premium-Präsentation für einen Hersteller bioklimatischer Pergolen und Aluminiumzäune. Kampagnenbereite Landingpage mit klarem Weg zur Anfrage.',
                    'BARANA verkauft bioklimatische Pergolen für Hunderttausende Kronen. Ich habe eine Website mit neun Seiten gebaut, die so ein Produkt verständlich erklärt und so hochwertig wirkt wie das Produkt selbst. Dazu eine eigene Seite, auf die bezahlte Werbung führt.',
                ],
                'challenge' => [
                    'Der Kunde brauchte einen Auftritt, der visuell mit Premium- Wettbewerbern mithält und gleichzeitig konvertiert. Die Site musste die Produktlinien klar trennen, schnell bleiben und als Einstiegspunkt für Meta Ads und Google Ads funktionieren — ohne Kompromisse auf dem Handy.',
                    'Eine bioklimatische Pergola ist teuer und den meisten Menschen unbekannt. Wer nie eine gesehen hat, versteht nicht, warum sie 300.000 CZK kostet. Die Website musste in wenigen Sekunden zeigen, was die Pergola kann, Vertrauen wecken und hochwertig wirken, denn hochwertig ist auch das Produkt.',
                ],
                'solution' => [
                    'Das Design baut auf minimalistischer Typografie, Kontrast und der Hierarchie „Headline → Benefit → Aktion“. Die Haupt- Landingpage fokussiert auf bioklimatische Pergolen mit einem klaren Konversionsziel, Service-Seiten halten den gleichen Rhythmus. Vorbereitet, damit Referenzen, Bewertungen und neuer Content wachsen können, ohne die Komposition zu sprengen.',
                    'Statt langer Texte probieren Besucher die Pergola aus. In einer interaktiven Vorführung stellen sie die Lamellen ein und sehen, was sie bei Regen, Sonne und Wind bewirken. Beim Scrollen erleben sie dieselbe Terrasse vom Morgenregen bis zum Winterabend. Ein anklickbares Schema erklärt acht Teile der Pergola, und eine Auswahlhilfe empfiehlt die Ausführung je nach Himmelsrichtung. Preise, technische Daten und Antworten zu Baugenehmigung und Fundament stehen offen auf der Website. Für bezahlte Werbung habe ich eine eigene Seite ohne Menü ergänzt, mit derselben Lamellen-Vorführung und einem kurzen Formular.',
                ],
                'result' => [
                    'BARANA hat jetzt eine Präsentation, die ohne Bedenken bezahlten Traffic empfängt. Besucher verstehen das Angebot ohne Anruf, Mobil ist genauso schnell wie Desktop, und die Struktur ist bereit für weitere Produktsektionen. Ein laufendes Akquise- Werkzeug — keine digitale Visitenkarte.',
                    'In fünf intensiven Wochen entstand eine Website, die sofort lädt (der Server antwortet in rund 0,2 s, Fotos in modernen Formaten) und auf dem Handy dieselbe Qualität hält. Jede Anfrage wird danach gemessen, woher sie kommt, sodass Kunde und Werbepartner sehen, welcher Weg funktioniert.',
                ],
                'meta_description' => [
                    'Case Study: Premium-Präsentation BARANA mit Landingpage für Meta Ads und Google Ads. In 5 Wochen, kampagnenbereit.',
                    'Case Study: Premium-Website für bioklimatische Pergolen von BARANA in fünf Wochen — interaktive Lamellen-Vorführung, Auswahlhilfe, offene Preise und eine Seite für bezahlte Werbung.',
                ],
            ],
        ],
    ];

    private const OUTCOMES = [
        'pitarena' => [
            'old' => [
                [
                    'key' => null,
                    'sort_order' => 0,
                    'tr' => [
                        'cs' => [
                            'label' => 'Spuštění značky i webu od nuly za jeden ucelený projekt',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Brand and website launched from scratch as a single project',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Marke und Website von Null als ein einziges Projekt',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 1,
                    'tr' => [
                        'cs' => [
                            'label' => 'Online prodej poukazů a registrace na závody bez ručního zásahu',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Online voucher sales and race registrations run hands-off',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Online-Gutscheinverkauf und Rennanmeldungen ohne Handarbeit',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 2,
                    'tr' => [
                        'cs' => [
                            'label' => 'Programy, půjčovna, servis a e-shop na jednom webu',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Programmes, rentals, service and online shop on one site',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Programme, Verleih, Service und Onlineshop auf einer Site',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 3,
                    'tr' => [
                        'cs' => [
                            'label' => 'SEO a obsahové články cílené na motokrosové kempy a pitbike tréninky',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'SEO and content aimed at motocross camps and pitbike training',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'SEO und Content für Motocross-Camps und Pitbike-Training',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 4,
                    'tr' => [
                        'cs' => [
                            'label' => 'Web a brand drží konzistentní tvář napříč webem, sociálními sítěmi a merchem',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Consistent identity across website, social media and merch',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Konsistente Identität auf Website, Social Media und Merch',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
            ],
            'new' => [
                [
                    'key' => null,
                    'sort_order' => 0,
                    'tr' => [
                        'cs' => [
                            'label' => 'Vlastní registrace na závody s platbou online',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Its own race registration with online payment',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Eigene Rennanmeldung mit Online-Zahlung',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 1,
                    'tr' => [
                        'cs' => [
                            'label' => 'Programy, půjčovna, servis a průvodci pro rodiče na jednom webu',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Programmes, rentals, service and guides for parents on one website',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Programme, Verleih, Service und Ratgeber für Eltern auf einer Website',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 2,
                    'tr' => [
                        'cs' => [
                            'label' => 'Závody YCF Cup převedené z Webnode pod jednu značku',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'YCF Cup races moved from Webnode under one brand',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'YCF-Cup-Rennen von Webnode unter eine Marke geholt',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
            ],
        ],
        'barana' => [
            'old' => [
                [
                    'key' => null,
                    'sort_order' => 0,
                    'tr' => [
                        'cs' => [
                            'label' => 'Premium vizuál stojící proti prémiové konkurenci',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Premium look that stands up to premium competitors',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Premium-Optik auf Augenhöhe mit Premium-Wettbewerb',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 1,
                    'tr' => [
                        'cs' => [
                            'label' => 'Landing page přímo pro Meta Ads a Google Ads',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Landing page wired for Meta Ads and Google Ads',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Landingpage direkt für Meta Ads und Google Ads verdrahtet',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 2,
                    'tr' => [
                        'cs' => [
                            'label' => 'Hierarchie titulek → benefit → akce na každé sekci',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Headline → benefit → action hierarchy on every section',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Hierarchie Headline → Benefit → Aktion auf jeder Sektion',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 3,
                    'tr' => [
                        'cs' => [
                            'label' => 'Mobile-first chování, rychlé načítání',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Mobile-first behaviour and fast load',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Mobile-First, schnelle Ladezeiten',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 4,
                    'tr' => [
                        'cs' => [
                            'label' => 'Struktura připravená na růst obsahu bez ztráty čistoty',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Structure ready to grow without losing visual clarity',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Struktur bereit für inhaltliches Wachstum ohne Stilbruch',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
            ],
            'new' => [
                [
                    'key' => null,
                    'sort_order' => 0,
                    'tr' => [
                        'cs' => [
                            'label' => 'Interaktivní ukázka lamel a terasa od rána do zimy',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Interactive louvre demo and the terrace from morning to winter',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Interaktive Lamellen-Vorführung und die Terrasse vom Morgen bis zum Winter',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 1,
                    'tr' => [
                        'cs' => [
                            'label' => 'Průvodce výběrem sestavy a otevřené ceny na webu',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'Set-up selection guide and open prices on the website',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Auswahlhilfe für die Ausführung und offene Preise auf der Website',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
                [
                    'key' => null,
                    'sort_order' => 2,
                    'tr' => [
                        'cs' => [
                            'label' => 'Samostatná stránka pro placenou reklamu se stejnou ukázkou',
                            'value' => '',
                            'description' => null,
                        ],
                        'en' => [
                            'label' => 'A separate page for paid ads with the same demo',
                            'value' => '',
                            'description' => null,
                        ],
                        'de' => [
                            'label' => 'Eigene Seite für bezahlte Werbung mit derselben Vorführung',
                            'value' => '',
                            'description' => null,
                        ],
                    ],
                ],
            ],
        ],
    ];

    private const ALTS = [
        [
            'barana',
            'projects/barana/hero-1.png',
            'hero',
            [
                'cs' => 'BARANA – domovská stránka prémiového webu pro bioklimatické pergoly',
                'en' => 'BARANA – homepage of the premium website for bioclimatic pergolas',
                'de' => 'BARANA – Startseite der Premium-Website für bioklimatische Pergolen',
            ],
            [
                'cs' => 'Web na monitoru, tabletu i mobilu: úvod a poptávkový formulář',
                'en' => 'The website on a monitor, tablet and phone: homepage and enquiry form',
                'de' => 'Die Website auf Monitor, Tablet und Handy: Startseite und Anfrageformular',
            ],
        ],
        [
            'barana',
            'projects/barana/gallery-4.png',
            'gallery',
            [
                'cs' => 'BARANA – sekce 4',
                'en' => 'BARANA – section 4',
                'de' => 'BARANA – Abschnitt 4',
            ],
            [
                'cs' => 'Kampaň mimo web: plakát ve stejném vizuálním stylu',
                'en' => 'The campaign beyond the website: a poster in the same visual style',
                'de' => 'Kampagne außerhalb der Website: ein Plakat im gleichen visuellen Stil',
            ],
        ],
        [
            'barana',
            'projects/barana/gallery-1.png',
            'gallery',
            [
                'cs' => 'BARANA – sekce 1',
                'en' => 'BARANA – section 1',
                'de' => 'BARANA – Abschnitt 1',
            ],
            [
                'cs' => 'Interaktivní ukázka: návštěvník si natočí lamely podle počasí',
                'en' => 'Interactive demo: visitors tilt the louvres to suit the weather',
                'de' => 'Interaktive Vorführung: Besucher stellen die Lamellen je nach Wetter ein',
            ],
        ],
        [
            'barana',
            'projects/barana/gallery-2.png',
            'gallery',
            [
                'cs' => 'BARANA – sekce 2',
                'en' => 'BARANA – section 2',
                'de' => 'BARANA – Abschnitt 2',
            ],
            [
                'cs' => 'Jedna terasa od ranního deště po zimní večer',
                'en' => 'One terrace from morning rain to a winter evening',
                'de' => 'Eine Terrasse vom Morgenregen bis zum Winterabend',
            ],
        ],
        [
            'barana',
            'projects/barana/gallery-3.png',
            'gallery',
            [
                'cs' => 'BARANA – sekce 3',
                'en' => 'BARANA – section 3',
                'de' => 'BARANA – Abschnitt 3',
            ],
            [
                'cs' => 'Průvodce výběrem sestavy s doporučením BARANA',
                'en' => 'Guide to choosing a set-up, with recommendations from BARANA',
                'de' => 'Auswahlhilfe für die passende Ausführung mit Empfehlung von BARANA',
            ],
        ],
        [
            'choccoboard',
            'projects/choccoboard/hero-1.webp',
            'hero',
            [
                'cs' => 'Choccoboard – BI dashboard se strategickými výrobními metrikami',
                'en' => 'Choccoboard – BI dashboard with strategic production metrics',
                'de' => 'Choccoboard – BI-Dashboard mit strategischen Produktionsmetriken',
            ],
            [
                'cs' => 'Chráněné přihlášení do dashboardu z počítače i mobilu',
                'en' => 'Secure dashboard login on desktop and phone',
                'de' => 'Geschützte Anmeldung zum Dashboard am Computer und am Handy',
            ],
        ],
        [
            'choccoboard',
            'projects/choccoboard/gallery-3.webp',
            'gallery',
            [
                'cs' => 'Choccoboard — strategický dashboard – sekce 3',
                'en' => 'Choccoboard — strategic dashboard – section 3',
                'de' => 'Choccoboard — strategisches Dashboard – Abschnitt 3',
            ],
            [
                'cs' => 'Výběr sledovaných produktů zvlášť pro každou směnu',
                'en' => 'Choosing the tracked products separately for each shift',
                'de' => 'Auswahl der beobachteten Produkte für jede Schicht einzeln',
            ],
        ],
        [
            'clanek-motorkari-cz',
            'projects/clanek-motorkari-cz/gallery-2.jpg',
            'gallery',
            [
                'cs' => 'Článek na Motorkáři.cz – sekce 2',
                'en' => 'Article on Motorkáři.cz – section 2',
                'de' => 'Artikel auf Motorkáři.cz – Abschnitt 2',
            ],
            [
                'cs' => 'Rozšířená verze článku na blogu PitArena',
                'en' => 'The extended version of the article on the PitArena blog',
                'de' => 'Die erweiterte Fassung des Artikels im PitArena-Blog',
            ],
        ],
        [
            'cyklocentrum',
            'projects/cyklocentrum/hero-1.png',
            'hero',
            [
                'cs' => 'Cyklocentrum Březí – domovská stránka cyklistického obchodu a servisu',
                'en' => 'Cyklocentrum Březí – homepage of the bicycle shop and service',
                'de' => 'Cyklocentrum Březí – Startseite des Fahrradgeschäfts und Service',
            ],
            [
                'cs' => 'Rozcestník služeb: půjčovna, nová kola, servis, bazar',
                'en' => 'Service overview: rentals, new bikes, repairs, second-hand',
                'de' => 'Wegweiser zu den Leistungen: Verleih, neue Räder, Service, Gebrauchtmarkt',
            ],
        ],
        [
            'cyklocentrum',
            'projects/cyklocentrum/gallery-3.png',
            'gallery',
            [
                'cs' => 'Cyklocentrum Březí – sekce 3',
                'en' => 'Cyklocentrum Březí – section 3',
                'de' => 'Cyklocentrum Březí – Abschnitt 3',
            ],
            [
                'cs' => 'Prodej kol s cenami, slevami a tlačítky Zavolat a Navigovat',
                'en' => 'Bikes for sale with prices, discounts and Call and Navigate buttons',
                'de' => 'Fahrradverkauf mit Preisen, Rabatten und den Buttons Anrufen und Navigieren',
            ],
        ],
        [
            'elektro-srnak',
            'projects/elektro-srnak/hero-1.jpg',
            'hero',
            [
                'cs' => 'Elektro Srnák – domovská stránka webu elektrikářské firmy',
                'en' => 'Elektro Srnák – homepage of the electrician\'s website',
                'de' => 'Elektro Srnák – Startseite der Website der Elektrikerfirma',
            ],
            [
                'cs' => 'Ukázky zakázek, kontakt a mapa na mobilu',
                'en' => 'Sample jobs, contact details and a map on a phone',
                'de' => 'Beispielaufträge, Kontakt und Karte auf dem Handy',
            ],
        ],
        [
            'elektro-srnak',
            'projects/elektro-srnak/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'Elektro Srnák – sekce 1',
                'en' => 'Elektro Srnák – section 1',
                'de' => 'Elektro Srnák – Abschnitt 1',
            ],
            [
                'cs' => 'Přehled elektroinstalačních služeb na počítači i mobilu',
                'en' => 'Overview of electrical installation services on desktop and phone',
                'de' => 'Übersicht der Elektroinstallationsleistungen am Computer und am Handy',
            ],
        ],
        [
            'excel-tools',
            'projects/excel-tools/hero-1.jpg',
            'hero',
            [
                'cs' => 'Excel Tools – ukázka VBA nástroje pro výrobní data',
                'en' => 'Excel Tools – preview of a VBA tool for production data',
                'de' => 'Excel Tools – Vorschau eines VBA-Tools für Produktionsdaten',
            ],
            [
                'cs' => 'Jednoduché zadání: číslo objednávky, cyklus a Spustit',
                'en' => 'Simple input: order number, cycle and Run',
                'de' => 'Einfache Eingabe: Bestellnummer, Zyklus und Starten',
            ],
        ],
        [
            'excel-tools',
            'projects/excel-tools/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'Excel Tools (VBA) – sekce 1',
                'en' => 'Excel Tools (VBA) – section 1',
                'de' => 'Excel Tools (VBA) – Abschnitt 1',
            ],
            [
                'cs' => 'Záložní postup krok za krokem, když hlavní systém nejede',
                'en' => 'Step-by-step backup procedure for when the main system is down',
                'de' => 'Notfallablauf Schritt für Schritt, wenn das Hauptsystem ausfällt',
            ],
        ],
        [
            'frl-creator',
            'projects/frl-creator/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'FRL Creator – sekce 1',
                'en' => 'FRL Creator – section 1',
                'de' => 'FRL Creator – Abschnitt 1',
            ],
            [
                'cs' => 'Hlavní panel: období, výroba, typ seznamu a kontrola chyb',
                'en' => 'Main panel: period, production, list type and error check',
                'de' => 'Hauptansicht: Zeitraum, Produktion, Listentyp und Fehlerprüfung',
            ],
        ],
        [
            'frl-creator',
            'projects/frl-creator/gallery-2.jpg',
            'gallery',
            [
                'cs' => 'FRL Creator – sekce 2',
                'en' => 'FRL Creator – section 2',
                'de' => 'FRL Creator – Abschnitt 2',
            ],
            [
                'cs' => 'Hotový tiskový seznam regálu s kapacitami a chybějícími kusy',
                'en' => 'Finished print-ready rack list with capacities and missing parts',
                'de' => 'Fertige Regalliste zum Drucken mit Kapazitäten und fehlenden Teilen',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-2.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 2',
                'en' => 'HCMS — production information system – section 2',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 2',
            ],
            [
                'cs' => 'Tablet pro zásobovače: fronta volání se Start a Konec',
                'en' => 'Tablet for material handlers: call queue with Start and End',
                'de' => 'Tablet für die Materialversorgung: Rufwarteschlange mit Start und Ende',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-3.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 3',
                'en' => 'HCMS — production information system – section 3',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 3',
            ],
            [
                'cs' => 'Návrh datového modelu před vývojem',
                'en' => 'Data model designed before development',
                'de' => 'Datenmodell, entworfen vor der Entwicklung',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-5.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 5',
                'en' => 'HCMS — production information system – section 5',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 5',
            ],
            [
                'cs' => 'Kdo co v systému dělá: případy užití podle rolí',
                'en' => 'Who does what in the system: use cases by role',
                'de' => 'Wer was im System tut: Anwendungsfälle nach Rollen',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-6.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 6',
                'en' => 'HCMS — production information system – section 6',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 6',
            ],
            [
                'cs' => 'Historie přes 126 000 volání s filtrem a exportem',
                'en' => 'History of over 126,000 calls with filter and export',
                'de' => 'Verlauf von über 126.000 Rufen mit Filter und Export',
            ],
        ],
        [
            'josefopa',
            'projects/josefopa/gallery-1.webp',
            'gallery',
            [
                'cs' => 'Josef Opa – sekce 1',
                'en' => 'Josef Opa – section 1',
                'de' => 'Josef Opa – Abschnitt 1',
            ],
            [
                'cs' => 'Stránka služby v němčině na mobilu',
                'en' => 'A service page in German on a phone',
                'de' => 'Leistungsseite auf Deutsch auf dem Handy',
            ],
        ],
        [
            'josefopa',
            'projects/josefopa/gallery-4.webp',
            'gallery',
            [
                'cs' => 'Josef Opa – sekce 4',
                'en' => 'Josef Opa – section 4',
                'de' => 'Josef Opa – Abschnitt 4',
            ],
            [
                'cs' => 'Vizitky ve stejné grafice jako web',
                'en' => 'Business cards in the same design as the website',
                'de' => 'Visitenkarten im gleichen Design wie die Website',
            ],
        ],
        [
            'kemp-veselka',
            'projects/kemp-veselka/gallery-1.webp',
            'gallery',
            [
                'cs' => 'Autokemp Veselka – sekce 1',
                'en' => 'Autokemp Veselka – section 1',
                'de' => 'Autokemp Veselka – Abschnitt 1',
            ],
            [
                'cs' => 'Tipy na výlety v okolí kempu',
                'en' => 'Trip ideas around the campsite',
                'de' => 'Ausflugstipps rund um den Campingplatz',
            ],
        ],
        [
            'kemp-veselka',
            'projects/kemp-veselka/gallery-2.webp',
            'gallery',
            [
                'cs' => 'Autokemp Veselka – sekce 2',
                'en' => 'Autokemp Veselka – section 2',
                'de' => 'Autokemp Veselka – Abschnitt 2',
            ],
            [
                'cs' => 'Návrh webu: drátěné modely a varianty úvodní sekce',
                'en' => 'Website design: wireframes and variants of the opening section',
                'de' => 'Website-Entwurf: Wireframes und Varianten des Einstiegsbereichs',
            ],
        ],
        [
            'kemp-veselka',
            'projects/kemp-veselka/gallery-3.webp',
            'gallery',
            [
                'cs' => 'Autokemp Veselka – sekce 3',
                'en' => 'Autokemp Veselka – section 3',
                'de' => 'Autokemp Veselka – Abschnitt 3',
            ],
            [
                'cs' => 'Nové logo autokempu',
                'en' => 'New logo for the campsite',
                'de' => 'Neues Logo des Campingplatzes',
            ],
        ],
        [
            'nove-interiery',
            'projects/nove-interiery/gallery-1.png',
            'gallery',
            [
                'cs' => 'Nové interiéry – sekce 1',
                'en' => 'Nové interiéry – section 1',
                'de' => 'Nové interiéry – Abschnitt 1',
            ],
            [
                'cs' => 'Galerie inspirace z realizací',
                'en' => 'Inspiration gallery from completed projects',
                'de' => 'Inspirationsgalerie aus umgesetzten Projekten',
            ],
        ],
        [
            'nove-interiery',
            'projects/nove-interiery/gallery-2.png',
            'gallery',
            [
                'cs' => 'Nové interiéry – sekce 2',
                'en' => 'Nové interiéry – section 2',
                'de' => 'Nové interiéry – Abschnitt 2',
            ],
            [
                'cs' => 'Časově omezené akční nabídky',
                'en' => 'Time-limited special offers',
                'de' => 'Zeitlich begrenzte Aktionsangebote',
            ],
        ],
        [
            'nove-interiery',
            'projects/nove-interiery/gallery-3.png',
            'gallery',
            [
                'cs' => 'Nové interiéry – sekce 3',
                'en' => 'Nové interiéry – section 3',
                'de' => 'Nové interiéry – Abschnitt 3',
            ],
            [
                'cs' => 'Patička s kontaktem, otevírací dobou a výzvou k návštěvě',
                'en' => 'Footer with contact details, opening hours and an invitation to visit',
                'de' => 'Fußbereich mit Kontakt, Öffnungszeiten und Einladung zum Besuch',
            ],
        ],
        [
            'nove-interiery',
            'projects/nove-interiery/gallery-5.png',
            'gallery',
            [
                'cs' => 'Nové interiéry – sekce 5',
                'en' => 'Nové interiéry – section 5',
                'de' => 'Nové interiéry – Abschnitt 5',
            ],
            [
                'cs' => 'Kategorie dřevěných podlah s filtrem',
                'en' => 'Wooden flooring category with a filter',
                'de' => 'Kategorie Holzböden mit Filter',
            ],
        ],
        [
            'picker',
            'projects/picker/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'Picker — řídicí logistický systém – sekce 1',
                'en' => 'Picker — logistics control system – section 1',
                'de' => 'Picker — Logistik-Steuerungssystem – Abschnitt 1',
            ],
            [
                'cs' => 'Obrazovka skladníka a rychlé hlášení problému',
                'en' => 'Warehouse operator screen with quick problem reporting',
                'de' => 'Bildschirm für Lagermitarbeiter mit schneller Problemmeldung',
            ],
        ],
        [
            'picker',
            'projects/picker/gallery-3.jpg',
            'gallery',
            [
                'cs' => 'Picker — řídicí logistický systém – sekce 3',
                'en' => 'Picker — logistics control system – section 3',
                'de' => 'Picker — Logistik-Steuerungssystem – Abschnitt 3',
            ],
            [
                'cs' => 'Přehled pro vedoucího: kdo pracuje a kde je problém',
                'en' => 'Supervisor overview: who is working and where the problem is',
                'de' => 'Übersicht für Teamleiter: wer arbeitet und wo es hakt',
            ],
        ],
        [
            'picker',
            'projects/picker/gallery-5.jpg',
            'gallery',
            [
                'cs' => 'Picker — řídicí logistický systém – sekce 5',
                'en' => 'Picker — logistics control system – section 5',
                'de' => 'Picker — Logistik-Steuerungssystem – Abschnitt 5',
            ],
            [
                'cs' => 'Nastavení procesů v administraci',
                'en' => 'Process settings in the admin area',
                'de' => 'Prozesseinstellungen in der Verwaltung',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/gallery-1.webp',
            'gallery',
            [
                'cs' => 'PitArena – sekce 1',
                'en' => 'PitArena – section 1',
                'de' => 'PitArena – Abschnitt 1',
            ],
            [
                'cs' => 'Stránka dětského kroužku s věkem a náplní',
                'en' => 'Youth club page with age range and content',
                'de' => 'Seite des Jugendclubs mit Alter und Inhalten',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/gallery-2.webp',
            'gallery',
            [
                'cs' => 'PitArena – sekce 2',
                'en' => 'PitArena – section 2',
                'de' => 'PitArena – Abschnitt 2',
            ],
            [
                'cs' => 'Prodej motorek s cenami a bonusem k nákupu',
                'en' => 'Bikes for sale with prices and a purchase bonus',
                'de' => 'Motorradverkauf mit Preisen und Kaufbonus',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/gallery-3.webp',
            'gallery',
            [
                'cs' => 'PitArena – sekce 3',
                'en' => 'PitArena – section 3',
                'de' => 'PitArena – Abschnitt 3',
            ],
            [
                'cs' => 'Blog pro rodiče a začínající jezdce',
                'en' => 'Blog for parents and beginner riders',
                'de' => 'Blog für Eltern und Einsteiger',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/gallery-4.webp',
            'gallery',
            [
                'cs' => 'PitArena – sekce 4',
                'en' => 'PitArena – section 4',
                'de' => 'PitArena – Abschnitt 4',
            ],
            [
                'cs' => 'Nejvíc nových návštěvníků přichází z vyhledávání',
                'en' => 'Most new visitors come from search',
                'de' => 'Die meisten neuen Besucher kommen über die Suche',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/gallery-5.webp',
            'gallery',
            [
                'cs' => 'PitArena – sekce 5',
                'en' => 'PitArena – section 5',
                'de' => 'PitArena – Abschnitt 5',
            ],
            [
                'cs' => 'Merch ve stylu značky',
                'en' => 'Merchandise in the brand style',
                'de' => 'Merch im Stil der Marke',
            ],
        ],
        [
            'realitacky-v-akci',
            'projects/realitacky-v-akci/gallery-1.webp',
            'gallery',
            [
                'cs' => 'Realiťačky v Akci – sekce 1',
                'en' => 'Realiťačky v Akci – section 1',
                'de' => 'Realiťačky v Akci – Abschnitt 1',
            ],
            [
                'cs' => 'Detail nabídky s galerií a parametry',
                'en' => 'Property listing with gallery and key details',
                'de' => 'Immobilienangebot mit Galerie und Eckdaten',
            ],
        ],
        [
            'realitacky-v-akci',
            'projects/realitacky-v-akci/gallery-2.webp',
            'gallery',
            [
                'cs' => 'Realiťačky v Akci – sekce 2',
                'en' => 'Realiťačky v Akci – section 2',
                'de' => 'Realiťačky v Akci – Abschnitt 2',
            ],
            [
                'cs' => 'Formulář u každé nabídky: prohlídka nebo splátky',
                'en' => 'A form on every listing: book a viewing or ask about financing',
                'de' => 'Formular bei jedem Angebot: Besichtigung oder Finanzierung',
            ],
        ],
        [
            'realitacky-v-akci',
            'projects/realitacky-v-akci/gallery-6.webp',
            'gallery',
            [
                'cs' => 'Realiťačky v Akci – sekce 6',
                'en' => 'Realiťačky v Akci – section 6',
                'de' => 'Realiťačky v Akci – Abschnitt 6',
            ],
            [
                'cs' => 'Finanční poradenství a nabídky se stavem na mobilu',
                'en' => 'Help with financing and listings with their status on a phone',
                'de' => 'Hilfe bei der Finanzierung und Angebote mit Status auf dem Handy',
            ],
        ],
        [
            'strechy-zajic',
            'projects/strechy-zajic/gallery-1.webp',
            'gallery',
            [
                'cs' => 'Střechy Zajíc – sekce 1',
                'en' => 'Střechy Zajíc – section 1',
                'de' => 'Střechy Zajíc – Abschnitt 1',
            ],
            [
                'cs' => 'Web na monitoru, tabletu i mobilu',
                'en' => 'The website on a monitor, tablet and phone',
                'de' => 'Die Website auf Monitor, Tablet und Handy',
            ],
        ],
        [
            'strechy-zajic',
            'projects/strechy-zajic/gallery-2.webp',
            'gallery',
            [
                'cs' => 'Střechy Zajíc – sekce 2',
                'en' => 'Střechy Zajíc – section 2',
                'de' => 'Střechy Zajíc – Abschnitt 2',
            ],
            [
                'cs' => 'Reference postavených a opravených střech',
                'en' => 'References: new and repaired roofs',
                'de' => 'Referenzen: neu gebaute und reparierte Dächer',
            ],
        ],
        [
            'strechy-zajic',
            'projects/strechy-zajic/gallery-3.webp',
            'gallery',
            [
                'cs' => 'Střechy Zajíc – sekce 3',
                'en' => 'Střechy Zajíc – section 3',
                'de' => 'Střechy Zajíc – Abschnitt 3',
            ],
            [
                'cs' => 'Samostatná stránka pro přístřešky, altány a pergoly',
                'en' => 'A separate page for shelters, gazebos and pergolas',
                'de' => 'Eigene Seite für Überdachungen, Gartenlauben und Pergolen',
            ],
        ],
        [
            'strechy-zajic',
            'projects/strechy-zajic/gallery-4.webp',
            'gallery',
            [
                'cs' => 'Střechy Zajíc – sekce 4',
                'en' => 'Střechy Zajíc – section 4',
                'de' => 'Střechy Zajíc – Abschnitt 4',
            ],
            [
                'cs' => 'Fotogalerie z realizací',
                'en' => 'Photo gallery of completed jobs',
                'de' => 'Fotogalerie der Aufträge',
            ],
        ],
        [
            'strechy-zajic',
            'projects/strechy-zajic/gallery-5.webp',
            'gallery',
            [
                'cs' => 'Střechy Zajíc – sekce 5',
                'en' => 'Střechy Zajíc – section 5',
                'de' => 'Střechy Zajíc – Abschnitt 5',
            ],
            [
                'cs' => 'Odborný článek o trendech v tesařství',
                'en' => 'Expert article on trends in carpentry',
                'de' => 'Fachartikel über Trends im Zimmererhandwerk',
            ],
        ],
        [
            'vanspedition',
            'projects/vanspedition/gallery-1.webp',
            'gallery',
            [
                'cs' => 'VAN spedition – sekce 1',
                'en' => 'VAN spedition – section 1',
                'de' => 'VAN spedition – Abschnitt 1',
            ],
            [
                'cs' => 'Nový web na počítači i mobilu',
                'en' => 'The new website on desktop and phone',
                'de' => 'Die neue Website am Computer und am Handy',
            ],
        ],
        [
            'vanspedition',
            'projects/vanspedition/gallery-2.webp',
            'gallery',
            [
                'cs' => 'VAN spedition – sekce 2',
                'en' => 'VAN spedition – section 2',
                'de' => 'VAN spedition – Abschnitt 2',
            ],
            [
                'cs' => 'Původní web na Wixu, stav před úpravou',
                'en' => 'The original Wix website, before the redesign',
                'de' => 'Die ursprüngliche Wix-Website vor der Überarbeitung',
            ],
        ],
        [
            'vp-industry',
            'projects/vp-industry/gallery-3.webp',
            'gallery',
            [
                'cs' => 'VP Industry – sekce 3',
                'en' => 'VP Industry – section 3',
                'de' => 'VP Industry – Abschnitt 3',
            ],
            [
                'cs' => 'Katalog značení inkoustem a historie firmy',
                'en' => 'Inkjet marking catalogue and company history',
                'de' => 'Katalog Tintenstrahlkennzeichnung und Firmengeschichte',
            ],
        ],
        [
            'yolk',
            'projects/yolk/gallery-1.webp',
            'gallery',
            [
                'cs' => 'YOLK — vývoj na klinických portálech – sekce 1',
                'en' => 'YOLK — agency development on clinic portals – section 1',
                'de' => 'YOLK — Agentur-Entwicklung auf Klinikportalen – Abschnitt 1',
            ],
            [
                'cs' => 'Pacientský portál: dotazník, platby a léčba',
                'en' => 'Patient portal: questionnaire, payments and treatment',
                'de' => 'Patientenportal: Fragebogen, Zahlungen und Behandlung',
            ],
        ],
        [
            'yolk',
            'projects/yolk/gallery-2.webp',
            'gallery',
            [
                'cs' => 'YOLK — vývoj na klinických portálech – sekce 2',
                'en' => 'YOLK — agency development on clinic portals – section 2',
                'de' => 'YOLK — Agentur-Entwicklung auf Klinikportalen – Abschnitt 2',
            ],
            [
                'cs' => 'Sezónní kampaň a mobilní stránky kliniky',
                'en' => 'Seasonal campaign and mobile pages of the clinic',
                'de' => 'Saisonkampagne und mobile Seiten der Klinik',
            ],
        ],
        [
            'pitarena',
            'projects/pitarena/hero-1.webp',
            'hero',
            [
                'cs' => 'PitArena – domovská stránka motokrosového centra s online rezervací tréninku',
                'en' => 'PitArena – homepage of the motocross centre with online training booking',
                'de' => 'PitArena – Startseite des Motocross-Zentrums mit Online-Trainingsbuchung',
            ],
            [
                'cs' => 'PitArena – domovská stránka motokrosového centra',
                'en' => 'PitArena – homepage of the motocross centre',
                'de' => 'PitArena – Startseite des Motocross-Zentrums',
            ],
        ],
    ];

    private const REMOVE = [
        [
            'excel-tools',
            'projects/excel-tools/gallery-3.jpg',
            'gallery',
            2,
            [
                'cs' => [
                    'alt' => 'Excel Tools (VBA) – sekce 3',
                    'caption' => null,
                ],
                'en' => [
                    'alt' => 'Excel Tools (VBA) – section 3',
                    'caption' => null,
                ],
                'de' => [
                    'alt' => 'Excel Tools (VBA) – Abschnitt 3',
                    'caption' => null,
                ],
            ],
        ],
        [
            'barana',
            'projects/barana/gallery-5.png',
            'gallery',
            5,
            [
                'cs' => [
                    'alt' => 'BARANA – sekce 5',
                    'caption' => null,
                ],
                'en' => [
                    'alt' => 'BARANA – section 5',
                    'caption' => null,
                ],
                'de' => [
                    'alt' => 'BARANA – Abschnitt 5',
                    'caption' => null,
                ],
            ],
        ],
    ];

    private const LIVE_HINTS = [
        'pitarena' => [
            'cs' => 'Projděte si výsledky a profily jezdců YCF Cup.',
            'en' => 'Browse the YCF Cup results and rider profiles. The site is in Czech.',
            'de' => 'Sehen Sie sich die Ergebnisse und Fahrerprofile des YCF Cup an. Die Website ist auf Tschechisch.',
        ],
        'pitarena-eshop' => [
            'cs' => 'V katalogu náhradních dílů klikněte na model YCF a uvidíte jen díly, které na něj sedí.',
            'en' => 'In the spare parts catalogue, click a YCF model and you only see the parts that fit it. The site is in Czech.',
            'de' => 'Klicken Sie im Ersatzteilkatalog auf ein YCF-Modell, dann sehen Sie nur die Teile, die passen. Die Website ist auf Tschechisch.',
        ],
        'barana' => [
            'cs' => 'Natočte si lamely v ukázce na stránce Bioklimatické pergoly.',
            'en' => 'Tilt the louvres yourself in the demo on the bioclimatic pergolas page. The site is in Czech.',
            'de' => 'Stellen Sie in der Vorführung auf der Seite zu bioklimatischen Pergolen die Lamellen selbst ein. Die Website ist auf Tschechisch.',
        ],
        'nove-interiery' => [
            'cs' => 'Projděte si podlahy podle druhu a inspiraci z hotových realizací.',
            'en' => 'Browse the floors by type and the inspiration from finished projects. The site is in Czech.',
            'de' => 'Stöbern Sie in den Böden nach Art und in der Inspiration aus umgesetzten Projekten. Die Website ist auf Tschechisch.',
        ],
        'cyklocentrum' => [
            'cs' => 'Otevřete si na mobilu kolo z nabídky: cena i tlačítka pro zavolání a navigaci jsou hned po ruce.',
            'en' => 'Open a bike from the range on your phone: the price and the buttons to call or get directions are right at hand. The site is in Czech.',
            'de' => 'Öffnen Sie auf dem Handy ein Rad aus dem Angebot: Preis und die Buttons zum Anrufen und Navigieren sind sofort zur Hand. Die Website ist auf Tschechisch.',
        ],
        'realitacky-v-akci' => [
            'cs' => 'Otevřete si kteroukoli nabídku nemovitosti: galerie, parametry a formulář pro prohlídku nebo splátky jsou na jednom místě.',
            'en' => 'Open any property listing: gallery, key details and a form for a viewing or financing, all in one place. The site is in Czech.',
            'de' => 'Öffnen Sie ein beliebiges Immobilienangebot: Galerie, Eckdaten und das Formular für Besichtigung oder Finanzierung an einem Ort. Die Website ist auf Tschechisch.',
        ],
        'zubni-provazek' => [
            'cs' => 'Podívejte se, jak web odpovídá pacientům: ceník ošetření, co hradí pojišťovna a rady rodičům.',
            'en' => 'See how the site answers patients: treatment prices, what insurance covers and advice for parents. The site is in Czech.',
            'de' => 'Sehen Sie, wie die Website Patienten antwortet: Behandlungspreise, was die Krankenkasse zahlt, und Tipps für Eltern. Die Website ist auf Tschechisch.',
        ],
        'vp-industry' => [
            'cs' => 'Otevřete produktovou stránku Značení laserem s parametry a ukázkovými videi.',
            'en' => 'Open the laser marking product page with its specifications and demo videos. The site is in Czech.',
            'de' => 'Öffnen Sie die Produktseite zur Laserkennzeichnung mit Parametern und Demovideos. Die Website ist auf Tschechisch.',
        ],
        'kemp-veselka' => [
            'cs' => 'Projděte si chatky, místa pro karavany i stanování a tipy na výlety v okolí.',
            'en' => 'Browse the cabins, the pitches for caravans and tents, and trip ideas nearby. The site is in Czech.',
            'de' => 'Sehen Sie sich Hütten, Stellplätze für Wohnwagen und Zelte sowie Ausflugstipps in der Umgebung an. Die Website ist auf Tschechisch.',
        ],
        'strechy-zajic' => [
            'cs' => 'Projděte si galerie realizací podle typu práce: střechy, přístřešky a stavby.',
            'en' => 'Browse the galleries of completed jobs by type: roofs, shelters and buildings. The site is in Czech.',
            'de' => 'Sehen Sie sich die Galerien der Aufträge nach Art an: Dächer, Überdachungen und Bauten. Die Website ist auf Tschechisch.',
        ],
        'josefopa' => [
            'cs' => 'Přepněte web mezi němčinou a angličtinou a projděte si nejnovější projekty.',
            'en' => 'Switch the site between German and English and browse the latest projects.',
            'de' => 'Schalten Sie die Website zwischen Deutsch und Englisch um und sehen Sie sich die neuesten Projekte an.',
        ],
        'vanspedition' => [
            'cs' => 'Celý web je jedna stránka: zkuste, jak rychle najdete cenu zásilky a kontakt.',
            'en' => 'The whole site is one page: see how quickly you find the shipping price and the contact details. The site is in Czech.',
            'de' => 'Die ganze Website ist eine Seite: Probieren Sie, wie schnell Sie den Versandpreis und den Kontakt finden. Die Website ist auf Tschechisch.',
        ],
        'clanek-motorkari-cz' => [
            'cs' => 'Přečtěte si článek tak, jak vyšel na Motorkáři.cz.',
            'en' => 'Read the article as it was published on Motorkáři.cz. The article is in Czech.',
            'de' => 'Lesen Sie den Artikel so, wie er auf Motorkáři.cz erschienen ist. Der Artikel ist auf Tschechisch.',
        ],
    ];

    private const RESULT_SOURCE = [
        'pitarena' => [
            'as_of' => '2026-09-28',
            'source' => [
                'cs' => 'Collabim (pozice), Google Search Console (návštěvy 16. 5. 2025 – 13. 9. 2026)',
                'en' => 'Collabim (rankings), Google Search Console (visits 16 May 2025 – 13 September 2026)',
                'de' => 'Collabim (Positionen), Google Search Console (Besuche 16.05.2025 – 13.09.2026)',
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        DB::transaction(function () {
            $this->applyTexts(0, 1);
            $this->applyOutcomes('old', 'new');
            $this->applyAlts(3, 4);
            $this->applyLeadSwap(false);

            foreach (self::REMOVE as [$slug, $path, $type]) {
                $projectId = $this->projectId($slug);
                $ids = DB::table('portfolio_project_screenshots')
                    ->where('project_id', $projectId)->where('path', $path)->where('type', $type)
                    ->pluck('id');
                DB::table('portfolio_project_screenshot_translations')->whereIn('screenshot_id', $ids)->delete();
                DB::table('portfolio_project_screenshots')->whereIn('id', $ids)->delete();
            }

            foreach (self::DETACH_TAGS as $slug => $tagSlug) {
                $tagId = DB::table('portfolio_tags')->where('slug', $tagSlug)->value('id');
                if ($tagId && ($projectId = $this->projectId($slug))) {
                    DB::table('portfolio_project_tag')->where('project_id', $projectId)->where('tag_id', $tagId)->delete();
                }
            }

            foreach (self::LIVE_HINTS as $slug => $hints) {
                foreach ($hints as $locale => $hint) {
                    $this->translations($slug, $locale)->whereNull('live_hint')->update(['live_hint' => $hint]);
                }
            }

            foreach (self::RESULT_SOURCE as $slug => $row) {
                DB::table('portfolio_projects')->where('slug', $slug)->whereNull('result_as_of')
                    ->update(['result_as_of' => $row['as_of']]);
                foreach ($row['source'] as $locale => $source) {
                    $this->translations($slug, $locale)->whereNull('result_source')->update(['result_source' => $source]);
                }
            }

            foreach (self::DEMO_VIDEOS as $slug => $video) {
                DB::table('portfolio_projects')->where('slug', $slug)->whereNull('demo_video')
                    ->update(['demo_video' => $video]);
            }
        });
    }

    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        DB::transaction(function () {
            $this->applyTexts(1, 0);
            $this->applyOutcomes('new', 'old');
            // Nejdřív vrátit typy, popisky se hledají podle (cesta, typ) ve výchozím stavu.
            $this->applyLeadSwap(true);
            $this->applyAlts(4, 3);

            foreach (self::REMOVE as [$slug, $path, $type, $sortOrder, $translations]) {
                $projectId = $this->projectId($slug);
                $exists = DB::table('portfolio_project_screenshots')
                    ->where('project_id', $projectId)->where('path', $path)->where('type', $type)->exists();
                if (! $projectId || $exists) {
                    continue;
                }
                $id = DB::table('portfolio_project_screenshots')->insertGetId([
                    'project_id' => $projectId, 'path' => $path, 'type' => $type,
                    'sort_order' => $sortOrder, 'created_at' => now(), 'updated_at' => now(),
                ]);
                foreach ($translations as $locale => $t) {
                    DB::table('portfolio_project_screenshot_translations')->insert([
                        'screenshot_id' => $id, 'locale' => $locale, 'alt' => $t['alt'], 'caption' => $t['caption'],
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }

            foreach (self::DETACH_TAGS as $slug => $tagSlug) {
                $tagId = DB::table('portfolio_tags')->where('slug', $tagSlug)->value('id');
                $projectId = $this->projectId($slug);
                if ($tagId && $projectId) {
                    DB::table('portfolio_project_tag')->insertOrIgnore(['project_id' => $projectId, 'tag_id' => $tagId]);
                }
            }

            foreach (self::LIVE_HINTS as $slug => $hints) {
                foreach ($hints as $locale => $hint) {
                    $this->translations($slug, $locale)->where('live_hint', $hint)->update(['live_hint' => null]);
                }
            }

            foreach (self::RESULT_SOURCE as $slug => $row) {
                DB::table('portfolio_projects')->where('slug', $slug)->whereDate('result_as_of', $row['as_of'])
                    ->update(['result_as_of' => null]);
                foreach ($row['source'] as $locale => $source) {
                    $this->translations($slug, $locale)->where('result_source', $source)->update(['result_source' => null]);
                }
            }

            foreach (self::DEMO_VIDEOS as $slug => $video) {
                DB::table('portfolio_projects')->where('slug', $slug)->where('demo_video', $video)
                    ->update(['demo_video' => null]);
            }
        });
    }

    /** Texty překladů: přepiš pole jen tam, kde stojí přesně výchozí hodnota. */
    private function applyTexts(int $from, int $to): void
    {
        foreach (self::TEXTS as $slug => $locales) {
            foreach ($locales as $locale => $fields) {
                $row = $this->translations($slug, $locale)->first();
                if (! $row) {
                    continue;
                }
                $changes = [];
                foreach ($fields as $field => $pair) {
                    if ($row->{$field} === $pair[$from]) {
                        $changes[$field] = $pair[$to];
                    }
                }
                if ($changes) {
                    DB::table('portfolio_project_translations')->where('id', $row->id)
                        ->update($changes + ['updated_at' => now()]);
                }
            }
        }
    }

    /** Výstupy: vyměň celou sadu, jen když česká sada přesně odpovídá výchozí. */
    private function applyOutcomes(string $from, string $to): void
    {
        foreach (self::OUTCOMES as $slug => $sets) {
            $projectId = $this->projectId($slug);
            if (! $projectId) {
                continue;
            }
            $current = DB::table('portfolio_project_outcomes as o')
                ->join('portfolio_project_outcome_translations as t', 't.outcome_id', '=', 'o.id')
                ->where('o.project_id', $projectId)->where('t.locale', 'cs')
                ->orderBy('o.sort_order')->pluck('t.label')->all();
            $expected = array_map(fn ($o) => $o['tr']['cs']['label'], $sets[$from]);
            if ($current !== $expected) {
                continue;
            }

            $ids = DB::table('portfolio_project_outcomes')->where('project_id', $projectId)->pluck('id');
            DB::table('portfolio_project_outcome_translations')->whereIn('outcome_id', $ids)->delete();
            DB::table('portfolio_project_outcomes')->whereIn('id', $ids)->delete();

            foreach ($sets[$to] as $outcome) {
                $id = DB::table('portfolio_project_outcomes')->insertGetId([
                    'project_id' => $projectId, 'key' => $outcome['key'], 'sort_order' => $outcome['sort_order'],
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                foreach ($outcome['tr'] as $locale => $t) {
                    DB::table('portfolio_project_outcome_translations')->insert([
                        'outcome_id' => $id, 'locale' => $locale, 'label' => $t['label'],
                        'value' => $t['value'], 'description' => $t['description'],
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /** Popisky snímků (klíč projekt + cesta + typ): přepiš jen výchozí znění. */
    private function applyAlts(int $from, int $to): void
    {
        foreach (self::ALTS as $row) {
            [$slug, $path, $type] = $row;
            $screenshotId = DB::table('portfolio_project_screenshots')
                ->where('project_id', $this->projectId($slug))->where('path', $path)->where('type', $type)
                ->value('id');
            if (! $screenshotId) {
                continue;
            }
            foreach ($row[$to] as $locale => $alt) {
                DB::table('portfolio_project_screenshot_translations')
                    ->where('screenshot_id', $screenshotId)->where('locale', $locale)->where('alt', $row[$from][$locale])
                    ->update(['alt' => $alt, 'updated_at' => now()]);
            }
        }
    }

    /** B-06 choccoboard: prohodit typ a pořadí `hero` ↔ `gallery`, jen ve výchozím stavu. */
    private function applyLeadSwap(bool $reverse): void
    {
        $projectId = $this->projectId(self::LEAD_SWAP['slug']);
        $shots = DB::table('portfolio_project_screenshots')->where('project_id', $projectId)
            ->whereIn('path', [self::LEAD_SWAP['from'], self::LEAD_SWAP['to']])
            ->whereIn('type', ['hero', 'gallery'])->get()->keyBy('path');
        $hero = $shots[$reverse ? self::LEAD_SWAP['to'] : self::LEAD_SWAP['from']] ?? null;
        $gallery = $shots[$reverse ? self::LEAD_SWAP['from'] : self::LEAD_SWAP['to']] ?? null;
        if (! $hero || ! $gallery || $hero->type !== 'hero' || $gallery->type !== 'gallery') {
            return;
        }
        DB::table('portfolio_project_screenshots')->where('id', $hero->id)
            ->update(['type' => 'gallery', 'sort_order' => $gallery->sort_order, 'updated_at' => now()]);
        DB::table('portfolio_project_screenshots')->where('id', $gallery->id)
            ->update(['type' => 'hero', 'sort_order' => $hero->sort_order, 'updated_at' => now()]);
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    private function translations(string $slug, string $locale)
    {
        return DB::table('portfolio_project_translations')
            ->where('project_id', $this->projectId($slug))->where('locale', $locale);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-454 — opravy případovek po QA OND-450 (rozhodnutí CEO na OND-452),
 * texty cs/en/de z dokumentu `texty` na OND-453.
 *
 *   V1  cyklocentrum: z výsledku pryč průměrná pozice (B-09 bod 3).
 *   V2  11 snímků s popiskem „sekce N“ dostane konkrétní popisek (B-07).
 *       Žádný snímek se nevyřazuje.
 *   V3  barana: z výsledku pryč „server odpoví zhruba za 0,2 s“.
 *   V4  cyklocentrum, zubni-provazek, pitarena-eshop: zdroj a datum ze závorek
 *       v textu do řádku „Stav k … Zdroj: …“ (`result_as_of` + `result_source`).
 *   V6  barana: popisek hlavního snímku (notebook, ne monitor).
 *   Kemp Veselka: rezervace jde po telefonu, ne online (challenge, solution,
 *       result, meta_description, summary).
 *
 * Stejný vzor jako OND-449: každá změna se provede, jen když v DB stojí přesně
 * původní hodnota z produkce (28. 9. 2026, `9a88c86`). Druhý běh je no-op
 * a ruční úprava z Filamentu se nepřepíše. Datum a zdroj se plní jen do
 * prázdných sloupců. `down()` vrací totéž zpátky za stejných podmínek.
 *
 * Na prázdné DB je migrace inertní: projekty zakládá PortfolioSeeder
 * z `docs/portfolio-data.yaml`, který je srovnaný na stejný výsledek.
 */
return new class extends Migration
{
    /** Texty překladů: slug => locale => pole => [výchozí znění, nové znění] */
    private const TEXTS = [
        'cyklocentrum' => [
            'cs' => [
                'result' => [
                    'Na dotazy jako „cyklocentrum březí“ nebo „půjčovna kol březí“ je web v Googlu na první pozici a průměrná pozice napříč dotazy se zvedla z 16,8 na 8,6 (Search Console, 16. 5. 2025 – 13. 9. 2026). Z Googlu tak přichází víc než polovina veškeré návštěvnosti — 1 780 z 3 149 návštěv (Google Analytics, 1. 1. – 16. 9. 2026). Klient potvrzuje, že má konečně prezentaci, za kterou se nemusí stydět. Návštěvník rychle najde, co se dá nechat opravit, co se dá půjčit a jak se s klientem spojit.',
                    'Na dotazy jako „cyklocentrum březí“ nebo „půjčovna kol březí“ je web v Googlu na první pozici. Z Googlu přichází víc než polovina veškeré návštěvnosti — 1 780 z 3 149 návštěv. Klient potvrzuje, že má konečně prezentaci, za kterou se nemusí stydět. Návštěvník rychle najde, co se dá nechat opravit, co se dá půjčit a jak se s klientem spojit.',
                ],
            ],
            'en' => [
                'result' => [
                    'For searches like "cyklocentrum březí" or "půjčovna kol březí" the site ranks first in Google, and its average position across queries rose from 16.8 to 8.6 (Search Console, 16 May 2025 – 13 Sep 2026). Google therefore brings more than half of all traffic — 1,780 of 3,149 visits (Google Analytics, 1 Jan – 16 Sep 2026). The client confirms they finally have "a presentation they aren\'t ashamed of". Visitors quickly find what the workshop services, what\'s available to rent, and how to get in touch.',
                    'For searches like “cyklocentrum březí” or “půjčovna kol březí” the site ranks first in Google. Google brings more than half of all traffic — 1,780 of 3,149 visits. The client confirms they finally have “a presentation they aren’t ashamed of”. Visitors quickly find what the workshop services, what’s available to rent, and how to get in touch.',
                ],
            ],
            'de' => [
                'result' => [
                    'Bei Suchen wie „cyklocentrum březí“ oder „půjčovna kol březí“ steht die Site in Google auf Platz eins, und die durchschnittliche Position über alle Suchanfragen verbesserte sich von 16,8 auf 8,6 (Search Console, 16.5.2025 – 13.9.2026). Aus Google kommt damit mehr als die Hälfte des gesamten Traffics — 1.780 von 3.149 Besuchen (Google Analytics, 1.1. – 16.9.2026). Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss“. Besucher finden schnell, was der Betrieb serviciert, was zu mieten ist und wie man Kontakt aufnimmt.',
                    'Bei Suchen wie „cyklocentrum březí“ oder „půjčovna kol březí“ steht die Site in Google auf Platz eins. Aus Google kommt mehr als die Hälfte des gesamten Traffics — 1.780 von 3.149 Besuchen. Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss“. Besucher finden schnell, was der Betrieb serviciert, was zu mieten ist und wie man Kontakt aufnimmt.',
                ],
            ],
        ],
        'zubni-provazek' => [
            'cs' => [
                'result' => [
                    'Na klíčový lokální dotaz „zubař Hrabětice“ je ordinace v Googlu na první pozici a z Googlu přichází většina veškeré návštěvnosti — 460 z 524 návštěv (Google Analytics, 1. 1. – 16. 9. 2026). Web pacientům odpovídá na to, na co se dřív museli ptát telefonem: ceny, přehled ošetření, otevírací doba a umístění.',
                    'Na klíčový lokální dotaz „zubař Hrabětice“ je ordinace v Googlu na první pozici a z Googlu přichází většina veškeré návštěvnosti — 460 z 524 návštěv. Web pacientům odpovídá na to, na co se dřív museli ptát telefonem: ceny, přehled ošetření, otevírací doba a umístění.',
                ],
            ],
            'en' => [
                'result' => [
                    'The practice ranks first in Google for its key local search, "zubař Hrabětice", and Google brings most of all traffic — 460 of 524 visits (Google Analytics, 1 Jan – 16 Sep 2026). The site answers what patients used to have to ask over the phone: prices, treatment overview, opening hours and location.',
                    'The practice ranks first in Google for its key local search, “zubař Hrabětice”, and Google brings most of all traffic — 460 of 524 visits. The site answers what patients used to have to ask over the phone: prices, treatment overview, opening hours and location.',
                ],
            ],
            'de' => [
                'result' => [
                    'Bei der zentralen lokalen Suche „zubař Hrabětice“ steht die Praxis in Google auf Platz eins, und aus Google kommt der Großteil des gesamten Traffics — 460 von 524 Besuchen (Google Analytics, 1.1. – 16.9.2026). Die Site beantwortet, was Patienten früher telefonisch fragen mussten: Preise, Behandlungsübersicht, Öffnungszeiten und Anfahrt.',
                    'Bei der zentralen lokalen Suche „zubař Hrabětice“ steht die Praxis in Google auf Platz eins, und aus Google kommt der Großteil des gesamten Traffics — 460 von 524 Besuchen. Die Site beantwortet, was Patienten früher telefonisch fragen mussten: Preise, Behandlungsübersicht, Öffnungszeiten und Anfahrt.',
                ],
            ],
        ],
        'pitarena-eshop' => [
            'cs' => [
                'result' => [
                    'E-shop dnes nabízí 4 551 produktů v 695 kategoriích a katalog náhradních dílů rozpadnutý podle 19 modelů motocyklů YCF — zákazník klikne na svůj model a vidí jen díly, které na něj sedí. I s tímhle rozsahem drží na desktopu 99/100 v Lighthouse Performance a hlavní obsah se vykreslí do 0,9 s (měřeno lokálním Lighthouse 13.4.1, 16. 9. 2026). Za první dva a půl měsíce provozu (1. 7. – 17. 9. 2026) jím prošlo 116 objednávek za 468 202 Kč, průměrná objednávka 4 036 Kč (údaje z administrace e-shopu).',
                    'E-shop nabízí tisíce produktů ve stovkách kategorií a katalog náhradních dílů rozpadnutý podle 19 modelů motocyklů YCF — zákazník klikne na svůj model a vidí jen díly, které na něj sedí. I s tímhle rozsahem drží na desktopu 99/100 v Lighthouse Performance a hlavní obsah se vykreslí do 0,9 s. Za první dva a půl měsíce provozu jím prošlo 116 objednávek za 468 202 Kč, průměrná objednávka 4 036 Kč.',
                ],
            ],
            'en' => [
                'result' => [
                    'The shop now offers 4,551 products in 695 categories, with the spare-parts catalogue broken down across 19 YCF motorcycle models — the customer clicks their model and sees only parts that fit. Even at that scale it holds 99/100 in Lighthouse Performance on desktop, with the main content rendering in under 0.9 s (measured with local Lighthouse 13.4.1 on 16 September 2026). In its first two and a half months (1 July – 17 September 2026) it handled 116 orders worth CZK 468,202 in total, averaging CZK 4,036 per order (figures from the shop administration).',
                    'The shop offers thousands of products in hundreds of categories, with the spare-parts catalogue broken down across 19 YCF motorcycle models — the customer clicks their model and sees only parts that fit. Even at that scale it holds 99/100 in Lighthouse Performance on desktop, with the main content rendering in under 0.9 s. In its first two and a half months it handled 116 orders worth CZK 468,202 in total, averaging CZK 4,036 per order.',
                ],
            ],
            'de' => [
                'result' => [
                    'Der Shop bietet heute 4.551 Produkte in 695 Kategorien, der Ersatzteilkatalog ist nach 19 YCF-Motorradmodellen aufgeteilt — Kunden klicken ihr Modell an und sehen nur passende Teile. Auch in dieser Größe hält er auf dem Desktop 99/100 in Lighthouse Performance, der Hauptinhalt erscheint in unter 0,9 s (gemessen mit lokalem Lighthouse 13.4.1 am 16.09.2026). In den ersten zweieinhalb Monaten (1.7. – 17.9.2026) wickelte er 116 Bestellungen im Gesamtwert von 468.202 CZK ab, im Schnitt 4.036 CZK pro Bestellung (Daten aus der Shop-Administration).',
                    'Der Shop bietet Tausende Produkte in Hunderten Kategorien, der Ersatzteilkatalog ist nach 19 YCF-Motorradmodellen aufgeteilt — Kunden klicken ihr Modell an und sehen nur passende Teile. Auch in dieser Größe hält er auf dem Desktop 99/100 in Lighthouse Performance, der Hauptinhalt erscheint in unter 0,9 s. In den ersten zweieinhalb Monaten wickelte er 116 Bestellungen im Gesamtwert von 468.202 CZK ab, im Schnitt 4.036 CZK pro Bestellung.',
                ],
            ],
        ],
        'barana' => [
            'cs' => [
                'result' => [
                    'Za pět intenzivních týdnů vznikl web, který se načítá okamžitě (server odpoví zhruba za 0,2 s, fotky v moderních formátech) a na mobilu drží stejnou kvalitu. Každá poptávka se měří podle toho, odkud přišla, takže klient i jeho reklamní partner vidí, která cesta funguje.',
                    'Za pět intenzivních týdnů vznikl web, který se načítá okamžitě, fotky má v moderních formátech a na mobilu drží stejnou kvalitu. Každá poptávka se měří podle toho, odkud přišla, takže klient i jeho reklamní partner vidí, která cesta funguje.',
                ],
            ],
            'en' => [
                'result' => [
                    'In five intensive weeks a website came together that loads instantly (the server responds in about 0.2 s, photos in modern formats) and keeps the same quality on a phone. Every enquiry is tracked by where it came from, so the client and the advertising partner can see which route works.',
                    'In five intensive weeks a website came together that loads instantly, serves its photos in modern formats and keeps the same quality on a phone. Every enquiry is tracked by where it came from, so the client and the advertising partner can see which route works.',
                ],
            ],
            'de' => [
                'result' => [
                    'In fünf intensiven Wochen entstand eine Website, die sofort lädt (der Server antwortet in rund 0,2 s, Fotos in modernen Formaten) und auf dem Handy dieselbe Qualität hält. Jede Anfrage wird danach gemessen, woher sie kommt, sodass Kunde und Werbepartner sehen, welcher Weg funktioniert.',
                    'In fünf intensiven Wochen entstand eine Website, die sofort lädt, Fotos in modernen Formaten ausliefert und auf dem Handy dieselbe Qualität hält. Jede Anfrage wird danach gemessen, woher sie kommt, sodass Kunde und Werbepartner sehen, welcher Weg funktioniert.',
                ],
            ],
        ],
        'kemp-veselka' => [
            'cs' => [
                'challenge' => [
                    'Původní web byl zastaralý, špatně organizovaný a nevhodný pro mobil. Pro kemp, kam se lidé typicky rozhodují cestou nebo na mobilu, to znamenalo zbytečnou ztrátu rezervací. Bylo potřeba jasně ukázat ubytování, dostat rezervaci na pár tapnutí a udržet atmosféru rodinného kempu.',
                    'Původní web byl zastaralý, špatně organizovaný a nevhodný pro mobil. Pro kemp, kam se lidé typicky rozhodují cestou nebo na mobilu, to znamenalo zbytečnou ztrátu rezervací. Bylo potřeba jasně ukázat ubytování, dovést člověka bez hledání k telefonu pro rezervaci a udržet atmosféru rodinného kempu.',
                ],
                'solution' => [
                    'Jednostránkový moderní web s prioritou na mobil. Jasná informace o ubytování, jednoduchý rezervační proces, fotky v atmosféře místa. K tomu logo, které kempu předtím chybělo. Rychlost načítání jsem hlídal kvůli prvotnímu dojmu na mobilní data.',
                    'Jednostránkový moderní web s prioritou na mobil. Jasná informace o ubytování a fotky v atmosféře místa. U chatek, karavanů i stanování je tlačítko Rezervovat, které vede rovnou k telefonnímu číslu, a na mobilu stačí na číslo klepnout. K tomu logo, které kempu předtím chybělo. Rychlost načítání jsem hlídal kvůli prvotnímu dojmu na mobilní data.',
                ],
                'result' => [
                    'Klientka mluví o vyšším zájmu od spuštění a o tom, že „moderní vzhled webu budí důvěru a profesionální dojem“. Web teď funguje jako tichý prodejce ubytování — člověk najde, co potřebuje, vidí, jak to vypadá, a může rovnou rezervovat.',
                    'Klientka mluví o vyšším zájmu od spuštění a o tom, že „moderní vzhled webu budí důvěru a profesionální dojem“. Web teď funguje jako tichý prodejce ubytování — člověk najde, co potřebuje, vidí, jak to vypadá, a tlačítko Rezervovat ho dovede rovnou k telefonu.',
                ],
                'meta_description' => [
                    'Případová studie: redesign webu Autokempu Veselka s logem a jednoduchou rezervací. Vyšší zájem o ubytování od spuštění.',
                    'Případová studie: redesign webu Autokempu Veselka s logem a tlačítky Rezervovat, která vedou rovnou k telefonu. Vyšší zájem o ubytování od spuštění.',
                ],
                'summary' => [
                    'Redesign rodinného autokempu — jednostránkový web s jasným ubytováním, rezervací a atmosférou klidného místa, kam se chce člověk vracet.',
                    'Redesign rodinného autokempu — jednostránkový web s jasnou nabídkou ubytování, rezervací po telefonu a atmosférou klidného místa, kam se chce člověk vracet.',
                ],
            ],
            'en' => [
                'challenge' => [
                    'The original site was outdated, poorly organised, and not mobile-ready. For a campsite where people typically decide while travelling or on a phone, that meant lost bookings. The site needed to show accommodation clearly, make booking a few taps away, and preserve the family-camp atmosphere.',
                    'The original site was outdated, poorly organised, and not mobile-ready. For a campsite where people typically decide while travelling or on a phone, that meant lost bookings. The site needed to show accommodation clearly, lead people straight to the phone number for booking, and preserve the family-camp atmosphere.',
                ],
                'solution' => [
                    'A single-page modern website with mobile first in mind. Clear accommodation information, a simple booking flow, photos that match the atmosphere. Plus a logo the campsite didn\'t have before. Loading speed was a priority for the first impression on mobile data.',
                    'A single-page modern website with mobile first in mind. Clear accommodation information and photos that match the atmosphere. Cabins, caravans and tent pitches each have a Book button that leads straight to the phone number, which visitors on a phone can simply tap to call. Plus a logo the campsite didn’t have before. Loading speed was a priority for the first impression on mobile data.',
                ],
                'result' => [
                    'The client reports higher interest since launch and says "the modern look inspires trust and a professional feel". The site now works as a quiet sales tool — visitors find what they need, see what it looks like, and can book on the spot.',
                    'The client reports higher interest since launch and says “the modern look inspires trust and a professional feel”. The site now works as a quiet sales tool — visitors find what they need, see what it looks like, and the Book button takes them straight to the phone number.',
                ],
                'meta_description' => [
                    'Case study: Autokemp Veselka redesign with logo and simple booking. Higher accommodation interest since launch.',
                    'Case study: Autokemp Veselka redesign with a new logo and booking buttons that lead straight to the phone. Higher accommodation interest since launch.',
                ],
                'summary' => [
                    'Redesign for a family campsite — a single-page website with clear accommodation, booking and the atmosphere of a place worth returning to.',
                    'Redesign for a family campsite — a single-page website with clear accommodation, booking by phone and the atmosphere of a place worth returning to.',
                ],
            ],
            'de' => [
                'challenge' => [
                    'Die alte Site war veraltet, schlecht organisiert und nicht mobiltauglich. Für einen Campingplatz, bei dem unterwegs und am Handy entschieden wird, bedeutete das verlorene Buchungen. Die neue Site musste die Unterkünfte klar zeigen, Buchung in wenige Taps bringen und die Familien-Atmosphäre bewahren.',
                    'Die alte Site war veraltet, schlecht organisiert und nicht mobiltauglich. Für einen Campingplatz, bei dem unterwegs und am Handy entschieden wird, bedeutete das verlorene Buchungen. Die neue Site musste die Unterkünfte klar zeigen, ohne Suchen zur Telefonnummer für die Buchung führen und die Familien-Atmosphäre bewahren.',
                ],
                'solution' => [
                    'Eine moderne One-Page-Site mit Mobile-First-Denken. Klare Unterkunftsinformationen, einfacher Buchungsablauf, Fotos passend zur Atmosphäre. Dazu ein Logo, das es vorher nicht gab. Ladezeiten waren wichtig für den ersten Eindruck am Handy.',
                    'Eine moderne One-Page-Site mit Mobile-First-Denken. Klare Unterkunftsinformationen und Fotos passend zur Atmosphäre. Hütten, Wohnmobile und Zeltplätze haben jeweils einen Buchen-Button, der direkt zur Telefonnummer führt, und am Handy genügt ein Tippen auf die Nummer. Dazu ein Logo, das es vorher nicht gab. Ladezeiten waren wichtig für den ersten Eindruck am Handy.',
                ],
                'result' => [
                    'Die Inhaberin berichtet seit dem Launch von höherem Interesse und sagt: „Der moderne Auftritt schafft Vertrauen und einen professionellen Eindruck.“ Die Site arbeitet als stiller Verkäufer — Besucher finden, was sie brauchen, sehen, wie es aussieht, und können direkt buchen.',
                    'Die Inhaberin berichtet seit dem Launch von höherem Interesse und sagt: „Der moderne Auftritt schafft Vertrauen und einen professionellen Eindruck.“ Die Site arbeitet als stiller Verkäufer — Besucher finden, was sie brauchen, sehen, wie es aussieht, und der Buchen-Button führt sie direkt zur Telefonnummer.',
                ],
                'meta_description' => [
                    'Case Study: Redesign Autokemp Veselka mit Logo und einfacher Buchung. Höheres Interesse seit Launch.',
                    'Case Study: Redesign Autokemp Veselka mit neuem Logo und Buchungs-Buttons, die direkt zum Telefon führen. Höheres Interesse seit Launch.',
                ],
                'summary' => [
                    'Redesign für einen Familien-Campingplatz — eine One-Page-Site mit klarer Unterkunftsinfo, Buchung und der Atmosphäre eines Ortes, an den man zurückkehren möchte.',
                    'Redesign für einen Familien-Campingplatz — eine One-Page-Site mit klarer Unterkunftsinfo, Buchung per Telefon und der Atmosphäre eines Ortes, an den man zurückkehren möchte.',
                ],
            ],
        ],
    ];

    /** Popisky snímků: [slug, cesta, typ, výchozí znění, nové znění] */
    private const ALTS = [
        [
            'cyklocentrum',
            'projects/cyklocentrum/gallery-1.png',
            'gallery',
            [
                'cs' => 'Cyklocentrum Březí – sekce 1',
                'en' => 'Cyklocentrum Březí – section 1',
                'de' => 'Cyklocentrum Březí – Abschnitt 1',
            ],
            [
                'cs' => '„Proč právě my?“ na mobilu na šířku: tři důvody, proč přijet právě sem',
                'en' => '“Why us?” on a phone in landscape: three reasons to come here',
                'de' => '„Warum gerade wir?“ auf dem Handy im Querformat: drei Gründe, genau hierher zu kommen',
            ],
        ],
        [
            'cyklocentrum',
            'projects/cyklocentrum/gallery-2.png',
            'gallery',
            [
                'cs' => 'Cyklocentrum Březí – sekce 2',
                'en' => 'Cyklocentrum Březí – section 2',
                'de' => 'Cyklocentrum Březí – Abschnitt 2',
            ],
            [
                'cs' => '„Naše hodnoty“ na notebooku: fotka z vyjížďky a tři hodnoty týmu',
                'en' => '“Our values” on a laptop: a photo from a ride and the team’s three values',
                'de' => '„Unsere Werte“ auf dem Notebook: Foto von einer Radtour und drei Werte des Teams',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 1',
                'en' => 'HCMS — production information system – section 1',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 1',
            ],
            [
                'cs' => 'Obrazovka pro zadání volání v plné velikosti: díl, dodavatel, zásoby a dnešní historie',
                'en' => 'The call entry screen at full size: part, supplier, stock and today’s history',
                'de' => 'Die Rufmaske in voller Größe: Teil, Lieferant, Bestände und heutiger Verlauf',
            ],
        ],
        [
            'hcms',
            'projects/hcms/gallery-4.jpg',
            'gallery',
            [
                'cs' => 'HCMS — informační systém pro výrobu – sekce 4',
                'en' => 'HCMS — production information system – section 4',
                'de' => 'HCMS — Produktions-Informationssystem – Abschnitt 4',
            ],
            [
                'cs' => 'Soupis funkčních požadavků na systém',
                'en' => 'List of the system’s functional requirements',
                'de' => 'Liste der funktionalen Anforderungen an das System',
            ],
        ],
        [
            'picker',
            'projects/picker/gallery-2.jpg',
            'gallery',
            [
                'cs' => 'Picker — řídicí logistický systém – sekce 2',
                'en' => 'Picker — logistics control system – section 2',
                'de' => 'Picker — Logistik-Steuerungssystem – Abschnitt 2',
            ],
            [
                'cs' => 'Obrazovka skladníka na výšku: díl, regál, počet kusů a odpočet',
                'en' => 'The warehouse operator screen in portrait: part, rack, quantity and countdown',
                'de' => 'Bildschirm für Lagermitarbeiter im Hochformat: Teil, Regal, Stückzahl und Countdown',
            ],
        ],
        [
            'picker',
            'projects/picker/gallery-4.jpg',
            'gallery',
            [
                'cs' => 'Picker — řídicí logistický systém – sekce 4',
                'en' => 'Picker — logistics control system – section 4',
                'de' => 'Picker — Logistik-Steuerungssystem – Abschnitt 4',
            ],
            [
                'cs' => 'Hlášení problému: díl nenalezen, poškozený díl nebo box, vlastní zpráva',
                'en' => 'Reporting a problem: part not found, damaged part or box, custom message',
                'de' => 'Problem melden: Teil nicht gefunden, Teil oder Box beschädigt, eigene Nachricht',
            ],
        ],
        [
            'kemp-veselka',
            'projects/kemp-veselka/gallery-4.webp',
            'gallery',
            [
                'cs' => 'Autokemp Veselka – sekce 4',
                'en' => 'Autokemp Veselka – section 4',
                'de' => 'Autokemp Veselka – Abschnitt 4',
            ],
            [
                'cs' => 'Rezervace po telefonu: sekce „Pro rezervaci volejte“ s číslem jako tlačítkem',
                'en' => 'Booking by phone: the “Call to book” section with the number as a button',
                'de' => 'Buchung per Telefon: der Bereich „Zum Buchen anrufen“ mit der Nummer als Button',
            ],
        ],
        [
            'kemp-veselka',
            'projects/kemp-veselka/gallery-5.webp',
            'gallery',
            [
                'cs' => 'Autokemp Veselka – sekce 5',
                'en' => 'Autokemp Veselka – section 5',
                'de' => 'Autokemp Veselka – Abschnitt 5',
            ],
            [
                'cs' => 'Nabídka ubytování zabalená jako dárek: chatky, karavany a stanování s tlačítky Rezervovat a Ceník',
                'en' => 'The accommodation offer wrapped as a gift: cabins, caravans and tents with Book and Price list buttons',
                'de' => 'Das Unterkunftsangebot als Geschenk verpackt: Hütten, Wohnmobile und Zelte mit den Buttons Buchen und Preisliste',
            ],
        ],
        [
            'excel-tools',
            'projects/excel-tools/gallery-4.jpg',
            'gallery',
            [
                'cs' => 'Excel Tools (VBA) – sekce 4',
                'en' => 'Excel Tools (VBA) – section 4',
                'de' => 'Excel Tools (VBA) – Abschnitt 4',
            ],
            [
                'cs' => 'Kontrolní přehled linek, který odhalí chybějící kombinaci kurzu a druhu',
                'en' => 'Line-by-line check that reveals a missing route and type combination',
                'de' => 'Prüfübersicht je Linie, die eine fehlende Kombination aus Route und Art aufdeckt',
            ],
        ],
        [
            'clanek-motorkari-cz',
            'projects/clanek-motorkari-cz/gallery-1.jpg',
            'gallery',
            [
                'cs' => 'Článek na Motorkáři.cz – sekce 1',
                'en' => 'Article on Motorkáři.cz – section 1',
                'de' => 'Artikel auf Motorkáři.cz – Abschnitt 1',
            ],
            [
                'cs' => 'Článek na Motorkáři.cz v mobilu: titulek, perex a fotka ze závodu',
                'en' => 'The article on Motorkáři.cz on a phone: headline, lead paragraph and a race photo',
                'de' => 'Der Artikel auf Motorkáři.cz auf dem Handy: Überschrift, Vorspann und ein Rennfoto',
            ],
        ],
        [
            'zubni-provazek',
            'projects/zubni-provazek/gallery-3.png',
            'gallery',
            [
                'cs' => 'Zubní Provázek – sekce 3',
                'en' => 'Zubní Provázek – section 3',
                'de' => 'Zubní Provázek – Abschnitt 3',
            ],
            [
                'cs' => 'Článek „Rady rodičům“ na mobilu: kdy začít chodit s dítětem k zubaři',
                'en' => 'The “Advice for parents” article on a phone: when to start taking a child to the dentist',
                'de' => 'Der Artikel „Tipps für Eltern“ auf dem Handy: ab wann man mit dem Kind zum Zahnarzt geht',
            ],
        ],
        [
            'barana',
            'projects/barana/hero-1.png',
            'hero',
            [
                'cs' => 'Web na monitoru, tabletu i mobilu: úvod a poptávkový formulář',
                'en' => 'The website on a monitor, tablet and phone: homepage and enquiry form',
                'de' => 'Die Website auf Monitor, Tablet und Handy: Startseite und Anfrageformular',
            ],
            [
                'cs' => 'Web na notebooku, tabletu i mobilu: úvod, stránka o pergolách a poptávkový formulář',
                'en' => 'The website on a laptop, tablet and phone: homepage, pergola page and enquiry form',
                'de' => 'Die Website auf Notebook, Tablet und Handy: Startseite, Pergola-Seite und Anfrageformular',
            ],
        ],
    ];

    /** B-09: řádek „Stav k … Zdroj: …“ pod výsledkem (datum projektu + zdroj po jazycích) */
    private const RESULT_SOURCE = [
        'cyklocentrum' => [
            'as_of' => '2026-09-16',
            'source' => [
                'cs' => 'Google Search Console (pozice 16. 5. 2025 – 13. 9. 2026), Google Analytics (návštěvy 1. 1. – 16. 9. 2026)',
                'en' => 'Google Search Console (rankings 16 May 2025 – 13 September 2026), Google Analytics (visits 1 January – 16 September 2026)',
                'de' => 'Google Search Console (Positionen 16.05.2025 – 13.09.2026), Google Analytics (Besuche 01.01. – 16.09.2026)',
            ],
        ],
        'zubni-provazek' => [
            'as_of' => '2026-09-16',
            'source' => [
                'cs' => 'Google Search Console (pozice 16. 5. 2025 – 13. 9. 2026), Google Analytics (návštěvy 1. 1. – 16. 9. 2026)',
                'en' => 'Google Search Console (rankings 16 May 2025 – 13 September 2026), Google Analytics (visits 1 January – 16 September 2026)',
                'de' => 'Google Search Console (Positionen 16.05.2025 – 13.09.2026), Google Analytics (Besuche 01.01. – 16.09.2026)',
            ],
        ],
        'pitarena-eshop' => [
            'as_of' => '2026-09-17',
            'source' => [
                'cs' => 'Lighthouse 13.4.1 (rychlost, měřeno lokálně 16. 9. 2026), administrace e-shopu (objednávky 1. 7. – 17. 9. 2026)',
                'en' => 'Lighthouse 13.4.1 (speed, measured locally on 16 September 2026), shop administration (orders 1 July – 17 September 2026)',
                'de' => 'Lighthouse 13.4.1 (Geschwindigkeit, lokal gemessen am 16.09.2026), Shop-Administration (Bestellungen 01.07. – 17.09.2026)',
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
            $this->applyAlts(3, 4);

            foreach (self::RESULT_SOURCE as $slug => $row) {
                DB::table('portfolio_projects')->where('slug', $slug)->whereNull('result_as_of')
                    ->update(['result_as_of' => $row['as_of']]);
                foreach ($row['source'] as $locale => $source) {
                    $this->translations($slug, $locale)->whereNull('result_source')->update(['result_source' => $source]);
                }
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
            $this->applyAlts(4, 3);

            foreach (self::RESULT_SOURCE as $slug => $row) {
                DB::table('portfolio_projects')->where('slug', $slug)->whereDate('result_as_of', $row['as_of'])
                    ->update(['result_as_of' => null]);
                foreach ($row['source'] as $locale => $source) {
                    $this->translations($slug, $locale)->where('result_source', $source)->update(['result_source' => null]);
                }
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

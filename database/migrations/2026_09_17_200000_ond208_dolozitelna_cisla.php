<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-208, 2. iterace — doložitelná čísla na detailech projektů.
 *
 * První migrace (2026_09_17_100000) nedoložitelná čísla jen ODSTRANILA
 * („stovky návštěv týdně", „obsazené měsíce dopředu"). Ondra pak dodal
 * skutečná data z Google Search Console a Google Analytics, takže se
 * výsledková čísla vracejí — tentokrát se zdrojem i obdobím.
 *
 * Zdroje:
 *   - Search Console, 16. 5. 2025 – 13. 9. 2026, typ „Web" (tj. organika
 *     z Googlu). Nemá mezeru po souhlasu s cookies — jsou to Googlu vlastní
 *     data, takže úplnější zdroj než GA.
 *   - Google Analytics, 1. 1. – 16. 9. 2026. Počítá JEN návštěvníky, kteří
 *     odklikli analytické cookies, takže uvedená čísla jsou spodní hranice.
 *
 * Rozbor všech dat i verdikty po projektech jsou v dokumentu
 * `analyza-cisel-ga-gsc` na OND-208.
 *
 * Co se NEPUBLIKUJE a proč:
 *   - zubni-provazek: trend prokliků je −50 % ve srovnatelných oknech a
 *     průměrná pozice stojí (20,1 → 20,3). Publikuje se proto jen pozice 1
 *     na klíčovém dotazu a podíl organiky, nikdy ne růst.
 *   - barana.cz: 235 návštěv za 8,5 měsíce — na výsledkové tvrzení málo.
 *   - realitackyvakci.cz: celkovou návštěvnost táhnou klientovy sociální sítě
 *     a placená reklama (organika jen 12,8 %), nepřipisuje se.
 *   - shop.pitarena.cz: tržby 185 531 Kč čekají na potvrzení, že je
 *     e-commerce měření důvěryhodné (1 705 návštěv za 3,7 roku je podezřele
 *     málo na e-shop se 4 551 produkty).
 *
 * Nedoložitelná tvrzení, která tím zároveň mizí:
 *   - cyklocentrum: „Nové zákazníky web přivedl během pár dní" — data ze
 *     Search Console začínají 16. 5. 2025, dlouho po spuštění webu.
 *   - zubni-provazek: „více poptávek od pacientů" / „vyšší zájem o ošetření".
 *
 * Aktualizace jsou PODMÍNĚNÉ — přepíšou hodnotu jen tehdy, když v DB stále
 * stojí přesně ten původní text (ochrana ručních úprav z Filamentu).
 */
return new class extends Migration
{
    /** slug => locale => field => ['from' => ..., 'to' => ...] */
    private const TEXT_CHANGES = [
        'pitarena' => [
            'cs' => [
                'result' => [
                    'from' => 'Web dnes stojí na vlastních nohách — zájemce si sám najde program, koupí poukaz na jízdu nebo se přihlásí na závod, bez telefonátu majiteli. Vedle toho web nese e-shop, půjčovnu, servis a blog. Dlouhodobá spolupráce pokračuje dodnes.',
                    'to'   => 'Z Googlu si web řekne o víc než 15 000 návštěv za 16 měsíců (Search Console, 16. 5. 2025 – 13. 9. 2026: 15 472 prokliků při 288 925 zobrazeních) a průměrná pozice ve vyhledávání se za tu dobu zvedla z 10,3 na 7,6. Zájemce si sám najde program, koupí poukaz na jízdu nebo se přihlásí na závod, bez telefonátu majiteli. Vedle toho web nese e-shop, půjčovnu, servis a blog. Dlouhodobá spolupráce pokračuje dodnes.',
                ],
            ],
            'en' => [
                'result' => [
                    'from' => 'The site now stands on its own — a visitor finds a programme, buys a ride voucher or signs up for a race without calling the owner. Alongside that it carries the online shop, rentals, service and a blog. The long-term partnership continues today.',
                    'to'   => 'The site pulls more than 15,000 visits from Google over 16 months (Search Console, 16 May 2025 – 13 Sep 2026: 15,472 clicks on 288,925 impressions), and its average search position rose from 10.3 to 7.6 over that period. A visitor finds a programme, buys a ride voucher or signs up for a race without calling the owner. Alongside that it carries the online shop, rentals, service and a blog. The long-term partnership continues today.',
                ],
            ],
            'de' => [
                'result' => [
                    'from' => 'Die Site steht heute auf eigenen Beinen — Interessenten finden ein Programm, kaufen einen Fahrgutschein oder melden sich zum Rennen an, ohne beim Inhaber anzurufen. Daneben trägt sie Onlineshop, Verleih, Service und Blog. Die langfristige Zusammenarbeit läuft bis heute.',
                    'to'   => 'Aus Google holt die Site in 16 Monaten mehr als 15.000 Besuche (Search Console, 16.5.2025 – 13.9.2026: 15.472 Klicks bei 288.925 Impressionen), und die durchschnittliche Suchposition verbesserte sich in dieser Zeit von 10,3 auf 7,6. Interessenten finden ein Programm, kaufen einen Fahrgutschein oder melden sich zum Rennen an, ohne beim Inhaber anzurufen. Daneben trägt sie Onlineshop, Verleih, Service und Blog. Die langfristige Zusammenarbeit läuft bis heute.',
                ],
            ],
        ],
        'cyklocentrum' => [
            'cs' => [
                'summary' => [
                    'from' => 'Web cykloservisu a půjčovny, který klient potřeboval znovu — předchozí dodavatel rok pracoval a zanechal po sobě polotovar. Nové zákazníky web přivedl během pár dní.',
                    'to'   => 'Web cykloservisu a půjčovny, který klient potřeboval znovu — předchozí dodavatel rok pracoval a zanechal po sobě polotovar. Na klíčové lokální dotazy je dnes web v Googlu první.',
                ],
                'result' => [
                    'from' => 'Nový web přivedl první nové zákazníky během pár dní od spuštění. Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat". Návštěvník rychle najde, co se dá nechat opravit, co se dá půjčit a jak se s klientem spojit — tedy přesně to, kvůli čemu na web přichází.',
                    'to'   => 'Na dotazy jako „cyklocentrum březí" nebo „půjčovna kol březí" je web v Googlu na první pozici a průměrná pozice napříč dotazy se zvedla z 16,8 na 8,6 (Search Console, 16. 5. 2025 – 13. 9. 2026). Z Googlu tak přichází víc než polovina veškeré návštěvnosti — 1 780 z 3 149 návštěv (Google Analytics, 1. 1. – 16. 9. 2026). Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat". Návštěvník rychle najde, co se dá nechat opravit, co se dá půjčit a jak se s klientem spojit.',
                ],
            ],
            'en' => [
                'summary' => [
                    'from' => 'Bike service and rental website the client needed redone — the previous vendor left them with an unfinished mess. New customers started arriving within days.',
                    'to'   => 'Bike service and rental website the client needed redone — the previous vendor left them with an unfinished mess. Today the site ranks first in Google for its key local searches.',
                ],
                'result' => [
                    'from' => 'The new site brought the first new customers within days of launch. The client confirms they finally have "a presentation they aren\'t ashamed of". Visitors quickly find what the workshop services, what\'s available to rent, and how to get in touch — exactly what they came for.',
                    'to'   => 'For searches like "cyklocentrum březí" or "půjčovna kol březí" the site ranks first in Google, and its average position across queries rose from 16.8 to 8.6 (Search Console, 16 May 2025 – 13 Sep 2026). Google therefore brings more than half of all traffic — 1,780 of 3,149 visits (Google Analytics, 1 Jan – 16 Sep 2026). The client confirms they finally have "a presentation they aren\'t ashamed of". Visitors quickly find what the workshop services, what\'s available to rent, and how to get in touch.',
                ],
            ],
            'de' => [
                'summary' => [
                    'from' => 'Website für Fahrradservice und Verleih, die der Kunde neu brauchte — der vorherige Anbieter hinterließ ein halbfertiges Chaos. Neue Kunden kamen innerhalb weniger Tage.',
                    'to'   => 'Website für Fahrradservice und Verleih, die der Kunde neu brauchte — der vorherige Anbieter hinterließ ein halbfertiges Chaos. Heute steht die Site bei den wichtigsten lokalen Suchen in Google auf Platz eins.',
                ],
                'result' => [
                    'from' => 'Die neue Site brachte die ersten neuen Kunden innerhalb weniger Tage. Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss". Besucher finden schnell, was der Betrieb serviciert, was zu mieten ist und wie man Kontakt aufnimmt — genau dafür sind sie da.',
                    'to'   => 'Bei Suchen wie „cyklocentrum březí" oder „půjčovna kol březí" steht die Site in Google auf Platz eins, und die durchschnittliche Position über alle Suchanfragen verbesserte sich von 16,8 auf 8,6 (Search Console, 16.5.2025 – 13.9.2026). Aus Google kommt damit mehr als die Hälfte des gesamten Traffics — 1.780 von 3.149 Besuchen (Google Analytics, 1.1. – 16.9.2026). Der Kunde bestätigt: endlich „eine Präsentation, für die man sich nicht schämen muss". Besucher finden schnell, was der Betrieb serviciert, was zu mieten ist und wie man Kontakt aufnimmt.',
                ],
            ],
        ],
        'zubni-provazek' => [
            'cs' => [
                'summary' => [
                    'from' => 'Redesign webu zubní ordinace v Hraběticích — moderní vzhled, kompletní informace o ošetřeních, blog a lokální SEO. Po spuštění více poptávek od pacientů.',
                    'to'   => 'Redesign webu zubní ordinace v Hraběticích — moderní vzhled, kompletní informace o ošetřeních, blog a lokální SEO. Na dotaz „zubař Hrabětice" je ordinace v Googlu první.',
                ],
                'result' => [
                    'from' => 'Klient po spuštění zaznamenal vyšší zájem o ošetření a pozitivní reakce na úplnost informací a profesionální dojem. Web teď konkrétně přesvědčuje pacienty, že ordinace funguje moderně a transparentně — což je v lokální zdravotnické péči rozhodující.',
                    'to'   => 'Na klíčový lokální dotaz „zubař Hrabětice" je ordinace v Googlu na první pozici a z Googlu přichází většina veškeré návštěvnosti — 460 z 524 návštěv (Google Analytics, 1. 1. – 16. 9. 2026). Web pacientům odpovídá na to, na co se dřív museli ptát telefonem: ceny, přehled ošetření, otevírací doba a umístění.',
                ],
            ],
            'en' => [
                'summary' => [
                    'from' => 'Redesign for a dental practice in Hrabětice — modern look, full treatment information, a blog, and local SEO. More patient enquiries after launch.',
                    'to'   => 'Redesign for a dental practice in Hrabětice — modern look, full treatment information, a blog, and local SEO. The practice ranks first in Google for "zubař Hrabětice".',
                ],
                'result' => [
                    'from' => 'Post-launch the client recorded higher interest in treatments and positive feedback on the completeness of the information and the professional impression. The site now actively convinces patients that the practice operates transparently and modernly — decisive for local healthcare.',
                    'to'   => 'The practice ranks first in Google for its key local search, "zubař Hrabětice", and Google brings most of all traffic — 460 of 524 visits (Google Analytics, 1 Jan – 16 Sep 2026). The site answers what patients used to have to ask over the phone: prices, treatment overview, opening hours and location.',
                ],
            ],
            'de' => [
                'summary' => [
                    'from' => 'Redesign für eine Zahnarztpraxis in Hrabětice — moderne Optik, vollständige Behandlungsinfos, Blog und lokales SEO. Mehr Patientenanfragen nach dem Launch.',
                    'to'   => 'Redesign für eine Zahnarztpraxis in Hrabětice — moderne Optik, vollständige Behandlungsinfos, Blog und lokales SEO. Bei der Suche „zubař Hrabětice" steht die Praxis in Google auf Platz eins.',
                ],
                'result' => [
                    'from' => 'Nach dem Launch verzeichnete der Kunde höheres Interesse an Behandlungen und positive Reaktionen auf die Vollständigkeit der Informationen und den professionellen Eindruck. Die Site überzeugt Patienten aktiv davon, dass die Praxis modern und transparent arbeitet — in der lokalen Gesundheitsversorgung entscheidend.',
                    'to'   => 'Bei der zentralen lokalen Suche „zubař Hrabětice" steht die Praxis in Google auf Platz eins, und aus Google kommt der Großteil des gesamten Traffics — 460 von 524 Besuchen (Google Analytics, 1.1. – 16.9.2026). Die Site beantwortet, was Patienten früher telefonisch fragen mussten: Preise, Behandlungsübersicht, Öffnungszeiten und Anfahrt.',
                ],
            ],
        ],
    ];

    public function up(): void
    {
        $this->apply('to');
    }

    public function down(): void
    {
        $this->apply('from');
    }

    /**
     * Na nenaplněné DB (čerstvá instalace, `RefreshDatabase` v testech) není
     * co opravovat — zdrojem pravdy je `docs/portfolio-data.yaml`, který má
     * stejné texty. Viz komentář v 2026_09_17_100000.
     */
    private function apply(string $direction): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

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
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-501 — nový projekt „Vinařství Antoš“ (případovka z OND-494).
 *
 * Texty cs/en/de jsou doslova z dokumentu `pripadovka` v OND-494, verze 4
 * (oddíly A–E), schválené Ondřejem. Dva odstavce v jednom poli dělí
 * prázdný řádek; šablona detailu je vypisuje přes `nl2br()`.
 *
 * Snímky jsou vlastní záběry z 1. 10. 2026 v `resources/img/projects/
 * vinarstvi-antos/`: čtyři „po“ z živého webu a e-shopu klienta a dva
 * „před“ ze snímků starého webu (21. 8. 2026, přílohy OND-494). Úvodní
 * stránka „před“ je oříznutá nad formulářem s Ondřejovým testem, Ubytování
 * nad blokem s e-mailem. Volitelný snímek Vín vynechán (lichý by se v galerii
 * roztáhl přes celou šířku). Cesty jsou natvrdo (`path`), takže
 * je PortfolioSeeder nepřečísluje (past `gallery-N`).
 *
 * Inertní na prázdné DB — tam projekt založí PortfolioSeeder z
 * `docs/portfolio-data.yaml`, kde je tentýž záznam. Idempotentní: když
 * projekt se slugem už existuje (druhý běh, ruční založení ve Filamentu),
 * nic nedělá. `down()` projekt smaže; překlady, snímky a štítky odejdou
 * přes FK cascade.
 */
return new class extends Migration
{
    private const SLUG = 'vinarstvi-antos';

    private const PROJECT = [
        'category'    => 'website',
        'client_name' => 'Vinařství Antoš — Roman Antoš',
        'live_url'    => 'https://www.vinarstviantos.cz/',
        'year'        => 2026,
        'duration'    => 'několik týdnů',
        'featured'    => false,
        'sort_order'  => 55,
    ];

    private const TAGS = ['web', 'redesign', 'copywriting', 'logo', 'ubytovani'];

    private const TRANSLATIONS = [
        'cs' => [
            'title' => 'Vinařství Antoš',
            'subtitle' => 'Víno, degustace a penzion na jednom webu',
            'summary' => 'Předělaný web rodinného vinařství z Pavlova a e-shop sladěný s webem. Klientovi začaly chodit poptávky na degustace.',
            'description' => 'Vinařství Antoš z Pavlova pod Pálavou dělá víno třetí generaci. Ve sklepě ze 17. století pořádá degustace a nad sklepem pronajímá Penzion Mája. Web jsem předělal celý a e-shop jsem k němu sladil vzhledem i nabídkou.',
            'challenge' => 'Klientovi se na starém webu něco nezdálo, ale nedokázal říct co. Viděl hlavně, že web působí zastarale a vůbec neladí s e-shopem. Jiné logo, jiné písmo, jiný vzhled. Když jsem web i e-shop prošel, sepsal jsem 49 konkrétních nálezů.'
                ."\n\n".'Systém webu byl několik let bez bezpečnostních aktualizací. Na webu nebyla jediná recenze, přestože jich vinařství má na Googlu spoustu a penzion má na Bookingu hodnocení 9,8. Degustace ve sklepě zmiňovala jedna věta na stránce O nás, bez ceny a bez rezervace. Stránka penzionu měla ceník a kalendář, ale neříkala, pro koho je a proč přijet. Víno, degustace i penzion stály každé zvlášť. V e-shopu vedlo menu na Červená vína se dvěma lahvemi a na Akční vína, kde dlouho stálo jediné víno.',
            'solution' => 'Nejdřív jsem aktualizoval systém webu, aby zase dostával bezpečnostní opravy. Pak jsem web předělal celý. Má logickou strukturu a stránky na sebe navazují. Úvodní stránka hned ukazuje tři důvody, proč do Pavlova přijet: víno, degustaci a penzion. Hned pod nadpisem je hodnocení z Googlu, níž recenze hostů. Degustace dostaly vlastní stránku s průběhem, cenou a formulářem pro rezervaci. Stránka penzionu říká, pro koho je, co v něm najdete, co dělat v okolí a jak spojit pobyt s degustací. Texty jsem napsal já a logo dostalo novou podobu.'
                ."\n\n".'V e-shopu jsem sjednotil barvy, písmo a logo s webem a předělal úvodní stránku. Nabídka je teď na jedné stránce Vína a zákazník si podle chuti vyfiltruje bílá, červená nebo perlivá. Skoro prázdnou stránku Akční vína jsem zrušil, víno ve slevě je normálně v nabídce. Přibyla pálenka a v menu odkazy na degustace a ubytování.',
            'result' => 'Klient říká, že mu začaly chodit poptávky na degustace, které předtím nechodily vůbec. Prvních několik přišlo, ještě když jsem dolaďoval e-shop. Roman Antoš o spolupráci napsal: „Velice profesionální a zároveň lidský přístup.“',
            'live_hint' => 'Zkuste z webu přejít do e-shopu. Vypadá jako jeho součást.',
            'meta_title' => 'Vinařství Antoš — web a e-shop rodinného vinařství',
            'meta_description' => 'Případová studie: předělaný web Vinařství Antoš z Pavlova a e-shop sladěný s webem. Klientovi začaly chodit poptávky na degustace.',
        ],
        'en' => [
            'title' => 'Vinařství Antoš',
            'subtitle' => 'Wine, tastings and a guesthouse on one website',
            'summary' => 'A rebuilt website for a family winery in Pavlov and an online shop brought in line with it. Tasting enquiries started coming in.',
            'description' => 'Vinařství Antoš is a family winery in Pavlov, below the Pálava hills, run by the third generation. They hold tastings in a 17th-century cellar and let out Penzion Mája right above it. I rebuilt the whole website and brought the online shop in line with it, both in look and in how the range is organised.',
            'challenge' => 'The client felt something was off with the old site but couldn\'t say what. Mostly he saw that it looked dated and didn\'t match the online shop at all: a different logo, different fonts, a different look. When I went through the site and the shop, I wrote up 49 specific findings.'
                ."\n\n".'The website\'s system had gone several years without security updates. There wasn\'t a single review on the site, even though the winery has plenty on Google and the guesthouse is rated 9.8 on Booking.com. Cellar tastings got one sentence on the About page, with no price and no way to book. The guesthouse page had prices and a calendar, but didn\'t say who it suits or why to come. Wine, tastings and the guesthouse each stood on their own. In the shop, the menu led to a Red wines page with two bottles and a Special offers page that held a single wine for a long time.',
            'solution' => 'First I updated the website\'s system so it gets security fixes again. Then I rebuilt the whole site. It now has a logical structure and the pages lead into each other. The homepage shows three reasons to come to Pavlov straight away: the wine, the tastings and the guesthouse. The Google rating sits right under the headline, with guest reviews further down. Tastings got their own page with what to expect, the price and a booking form. The guesthouse page says who it suits, what\'s inside, what to do nearby and how to combine a stay with a tasting. I wrote the copy, and the logo got a new look.'
                ."\n\n".'In the shop I matched the colours, fonts and logo to the website and redid the front page. The whole range now sits on one Wines page, where customers can filter white, red or sparkling wines. I dropped the near-empty Special offers page; the discounted wine is simply part of the range. A grape brandy was added, and the menu now links to tastings and accommodation.',
            'result' => 'The client says tasting enquiries have started coming in, where before there were none. The first few arrived while I was still fine-tuning the shop. Roman Antoš on working together: “A very professional and at the same time personal approach.”',
            'live_hint' => 'Click through from the website to the shop. It feels like part of the same site. The site is in Czech.',
            'meta_title' => 'Vinařství Antoš — website and online shop for a family winery',
            'meta_description' => 'Case study: a rebuilt website for Vinařství Antoš in Pavlov and an online shop brought in line with it. Tasting enquiries started coming in.',
        ],
        'de' => [
            'title' => 'Vinařství Antoš',
            'subtitle' => 'Wein, Verkostungen und Pension auf einer Website',
            'summary' => 'Neu aufgebaute Website für ein Familienweingut in Pavlov und ein daran angeglichener Onlineshop. Seitdem kommen Anfragen für Verkostungen.',
            'description' => 'Vinařství Antoš ist ein Familienweingut in Pavlov unter der Pálava, inzwischen in dritter Generation. Im Keller aus dem 17. Jahrhundert finden Verkostungen statt, direkt darüber wird die Pension Mája vermietet. Die Website habe ich komplett neu aufgebaut und den Onlineshop in Optik und Sortimentsaufbau daran angeglichen.',
            'challenge' => 'Dem Kunden kam an der alten Website etwas nicht stimmig vor, er konnte aber nicht sagen, was. Vor allem wirkte sie veraltet und passte überhaupt nicht zum Onlineshop: anderes Logo, andere Schrift, anderer Look. Bei der Durchsicht von Website und Shop habe ich 49 konkrete Befunde notiert.'
                ."\n\n".'Das System der Website hatte seit Jahren keine Sicherheitsupdates bekommen. Auf der Website stand keine einzige Bewertung, obwohl das Weingut bei Google viele hat und die Pension auf Booking.com mit 9,8 bewertet ist. Die Kellerverkostungen erwähnte ein einziger Satz auf der Seite Über uns, ohne Preis und ohne Buchung. Die Pensionsseite hatte Preise und einen Kalender, sagte aber nicht, für wen sie passt und warum man kommen sollte. Wein, Verkostung und Pension standen jeweils für sich. Im Shop führte das Menü zu einer Seite Rotweine mit zwei Flaschen und zu einer Seite Aktionsweine, auf der lange nur ein einziger Wein stand.',
            'solution' => 'Zuerst habe ich das System der Website aktualisiert, damit es wieder Sicherheitsupdates bekommt. Dann habe ich die ganze Website neu aufgebaut. Sie hat jetzt eine logische Struktur, die Seiten führen ineinander. Die Startseite zeigt gleich drei Gründe, nach Pavlov zu kommen: Wein, Verkostung und Pension. Direkt unter der Überschrift steht die Google-Bewertung, weiter unten Stimmen von Gästen. Die Verkostungen haben eine eigene Seite mit Ablauf, Preis und Buchungsformular. Die Pensionsseite sagt, für wen sie passt, was drin ist, was man in der Umgebung unternehmen kann und wie sich Aufenthalt und Verkostung verbinden lassen. Die Texte habe ich geschrieben, das Logo hat eine neue Form bekommen.'
                ."\n\n".'Im Shop habe ich Farben, Schriften und Logo an die Website angeglichen und die Startseite neu gestaltet. Das Sortiment steht jetzt auf einer Seite Weine, dort lassen sich Weiß-, Rot- und Schaumweine filtern. Die fast leere Seite Aktionsweine habe ich abgeschafft, der reduzierte Wein steht ganz normal im Sortiment. Dazu kam ein Tresterbrand, und im Menü gibt es Links zu Verkostungen und Unterkunft.',
            'result' => 'Der Kunde berichtet, dass jetzt Anfragen für Verkostungen kommen, vorher kam keine einzige. Die ersten trafen ein, während ich noch am Shop feilte. Roman Antoš über die Zusammenarbeit: „Sehr professionell und zugleich menschlich.“',
            'live_hint' => 'Wechseln Sie von der Website in den Shop. Er wirkt wie ein Teil davon. Die Website ist auf Tschechisch.',
            'meta_title' => 'Vinařství Antoš — Website und Onlineshop für ein Familienweingut',
            'meta_description' => 'Case Study: neu aufgebaute Website für Vinařství Antoš in Pavlov und ein daran angeglichener Onlineshop. Seitdem kommen Anfragen für Verkostungen.',
        ],
    ];

    /** [soubor v resources/img/projects/vinarstvi-antos, type, alt cs/en/de] */
    private const SCREENSHOTS = [
        ['hero-1.webp', 'hero', [
            'cs' => 'Vinařství Antoš – úvodní stránka webu rodinného vinařství v Pavlově',
            'en' => 'Vinařství Antoš – homepage of the family winery in Pavlov',
            'de' => 'Vinařství Antoš – Startseite des Familienweinguts in Pavlov',
        ]],
        ['gallery-1.webp', 'gallery', [
            'cs' => 'Stránka Degustace s průběhem, cenou a formulářem pro rezervaci',
            'en' => 'Tastings page with what to expect, the price and a booking form',
            'de' => 'Seite Verkostungen mit Ablauf, Preis und Buchungsformular',
        ]],
        ['gallery-2.webp', 'gallery', [
            'cs' => 'Penzion Mája – pro koho je, hodnocení hostů a pobyt s degustací',
            'en' => 'Penzion Mája – who it suits, guest reviews and a stay with a tasting',
            'de' => 'Pension Mája – für wen sie passt, Gästebewertungen und Aufenthalt mit Verkostung',
        ]],
        ['gallery-3.webp', 'gallery', [
            'cs' => 'E-shop sladěný s webem – stejné barvy, písmo a logo',
            'en' => 'Online shop matched to the website – same colours, fonts and logo',
            'de' => 'Onlineshop passend zur Website – gleiche Farben, Schriften und Logo',
        ]],
        ['gallery-4.webp', 'gallery', [
            'cs' => 'Původní úvodní stránka – fotka, krátký text a odkaz na e-shop, bez recenzí',
            'en' => 'The original homepage – a photo, a short text and a shop link, no reviews',
            'de' => 'Die ursprüngliche Startseite – Foto, kurzer Text und Shop-Link, keine Bewertungen',
        ]],
        ['gallery-5.webp', 'gallery', [
            'cs' => 'Původní stránka penzionu – ceník a kalendář, bez recenzí a bez vazby na víno',
            'en' => 'The original guesthouse page – prices and a calendar, no reviews, no link to the wine',
            'de' => 'Die ursprüngliche Pensionsseite – Preise und Kalender, keine Bewertungen, kein Bezug zum Wein',
        ]],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        if (DB::table('portfolio_projects')->where('slug', self::SLUG)->exists()) {
            return;
        }

        DB::transaction(function () {
            $now = now();

            $projectId = DB::table('portfolio_projects')->insertGetId(self::PROJECT + [
                'slug'         => self::SLUG,
                'published_at' => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            foreach (self::TRANSLATIONS as $locale => $fields) {
                DB::table('portfolio_project_translations')->insert($fields + [
                    'project_id' => $projectId,
                    'locale'     => $locale,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach (self::SCREENSHOTS as $order => [$file, $type, $alts]) {
                $screenshotId = DB::table('portfolio_project_screenshots')->insertGetId([
                    'project_id' => $projectId,
                    'path'       => 'projects/'.self::SLUG.'/'.$file,
                    'type'       => $type,
                    'sort_order' => $order,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($alts as $locale => $alt) {
                    DB::table('portfolio_project_screenshot_translations')->insert([
                        'screenshot_id' => $screenshotId,
                        'locale'        => $locale,
                        'alt'           => $alt,
                        'caption'       => null,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }

            // Všech pět štítků v DB je (i s názvy z OND-223); chybějící by se
            // jen přeskočil, nezakládá se tu.
            $tagIds = DB::table('portfolio_tags')->whereIn('slug', self::TAGS)->pluck('id');
            foreach ($tagIds as $tagId) {
                DB::table('portfolio_project_tag')->insert([
                    'project_id' => $projectId,
                    'tag_id'     => $tagId,
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::table('portfolio_projects')->where('slug', self::SLUG)->delete();
    }
};

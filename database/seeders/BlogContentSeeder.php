<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-204 (OND-197 bod 11b) — přepis blogu do Ondrova hlasu.
 *
 * Zdroj textů: dokument `blog-texty` na OND-203 (Content Writer).
 * Rozhodnutí: 5 článků přepsat, 7 stáhnout z výpisu, 1 testovací už je smazaný.
 *
 * Proč seeder a ne úprava `database/sql/article_translations.sql`:
 * SQL dumpy v `database/sql/` jsou jednorázový import staré databáze a
 * `EnsureArticlesSeededSeeder` je spouští jen do prázdné tabulky. Na stagingu
 * i produkci články už existují, takže změna dumpu by se nikdy neprojevila
 * (stejný případ jako portfolio v OND-198). Texty proto žijí tady a aplikují
 * se dvěma cestami:
 *   - migrace 2026_09_16_110000_rewrite_blog_content.php → existující DB,
 *   - `EnsureArticlesSeededSeeder` po importu dumpů → čerstvá DB.
 *
 * Seeder je idempotentní: updaty jsou absolutní (ne inkrementální), slug se
 * zakládá přes updateOrInsert. Opakované spuštění nic nerozbije.
 *
 * Pozn.: EN a DE překlady článků zůstávají beze změny — Content Writer
 * doporučil je nepublikovat, dokud nebudou pořádně přeložené. Rozhodnutí
 * je na boardu, proto se jich tato změna nedotýká.
 */
class BlogContentSeeder extends Seeder
{
    /**
     * Články stažené z výpisu (published = 0).
     * Adresy zůstávají funkční, 301 řeší PageController::article().
     *
     * 1 Výběr doménového jména · 5 Co je SEO · 7 Design nebo obsah
     * 8 Web, který převádí · 9 Analýza klíčových slov
     * 11 Jak vytvořit úspěšnou webovou stránku · 12 Analýza konkurence
     */
    public const UNPUBLISHED_ARTICLE_IDS = [1, 5, 7, 8, 9, 11, 12];

    /**
     * Nové CS slugy. Starý slug zůstane v `article_slugs` jako neaktivní
     * (audit + 301 lookup), nový se stane kanonickým.
     *
     * article_id => [starý cs slug, nový cs slug]
     */
    private const NEW_CS_SLUGS = [
        4 => ['jak-definovat-pozadavky-na-vyvoj-webove-stranky', 'jak-se-pripravit-na-novy-web'],
        6 => ['jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony', 'kdy-se-vyplati-aplikace-na-miru'],
    ];

    public function run(): void
    {
        $this->note('[blog-content] aplikuji přepsané texty blogu (OND-204)...');

        foreach ($this->articles() as $articleId => $content) {
            $updated = DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where('locale', 'cs')
                ->update($content + [
                    'bonus'      => null,
                    'extra'      => null,
                    'active'     => 1,
                    'updated_at' => now(),
                ]);

            if ($updated === 0) {
                $this->note("[blog-content] POZOR: CS překlad článku {$articleId} nenalezen, přeskakuji.");
            }
        }

        foreach (self::NEW_CS_SLUGS as $articleId => [$oldSlug, $newSlug]) {
            if (! DB::table('articles')->where('id', $articleId)->exists()) {
                continue;
            }

            DB::table('article_slugs')
                ->where('article_id', $articleId)
                ->where('locale', 'cs')
                ->where('slug', '!=', $newSlug)
                ->update(['active' => 0, 'updated_at' => now()]);

            DB::table('article_slugs')->updateOrInsert(
                ['slug' => $newSlug, 'locale' => 'cs'],
                [
                    'article_id' => $articleId,
                    'active'     => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $unpublished = DB::table('articles')
            ->whereIn('id', self::UNPUBLISHED_ARTICLE_IDS)
            ->update(['published' => 0, 'updated_at' => now()]);

        $this->note("[blog-content] hotovo: 5 článků přepsáno, {$unpublished} staženo z výpisu.");
    }

    private function note(string $message): void
    {
        $this->command?->info($message);
    }

    /**
     * Finální texty (dokument `blog-texty`, OND-203).
     *
     * Cenová pásma v článku „Kolik stojí web" jsou srovnaná s `lang/cs/price.php`
     * (Standard 55 000 · Custom od 95 000 · Startovní 25 000, nejlevnější poslední
     * per OND-198 nález 5.4). Dokument uváděl jiná čísla; ceník je zdroj pravdy.
     *
     * Výzva k akci na konci článku se nepřidává do textu — detail článku už
     * renderuje jednu CTA sekci z lang souborů (`blog.cta.*`).
     *
     * @return array<int, array<string, string>>
     */
    private function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web (slug kolik-stoji-webove-stranky, beze změny)
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'Kolik stojí web na míru a z čeho se ta cena skládá',
                'description' => 'V jakých cenových pásmech weby dělám, co v nich je a co cenu zvedá. Ať víte předem, jestli se vejdu do vašeho rozpočtu.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Na cenu se mě lidé ptají jako na první věc a je to správná otázka. Jedno číslo vám ale nikdo poctivě říct nemůže. Web za pětadvacet tisíc a web za dvě stě tisíc jsou dvě různé věci. Napsal jsem proto na rovinu, v jakých pásmech dělám, co je v nich obsažené a co cenu posouvá nahoru.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Proč nemám jedno číslo</h2>
                    <p>Web není zboží ze skladu. Když mi napíšete, že chcete web, vím zatím jen to, že chcete web. Nevím, kolik má mít stránek. Nevím, jestli si obsah budete chtít spravovat sami. Nevím, jestli přes něj potřebujete prodávat nebo jestli má fungovat i anglicky. Každá z těch věcí s cenou pohne.</p>
                    <p>Dělám to tak, že si nejdřív projdeme, co potřebujete. Pak vám napíšu specifikaci, kde je černé na bílém, co postavím a za kolik. Ta cena platí. Faktura na konci odpovídá specifikaci na začátku. Když v průběhu zjistíte, že chcete něco navíc, řeknu vám cenu dopředu a rozhodnete se vy.</p>
                    <h2>Za co vlastně platíte</h2>
                    <p>Platíte můj čas a to, co s ním umím udělat. Nekupujete licenci k šabloně ani hodiny obchodníka, který vám web prodal a pak zmizel. Pracuju sám, takže v ceně není agenturní režie ani koordinátor, který mi přeposílá vaše e-maily.</p>
                    <p>Weby píšu vlastním kódem. Nestavím je z hotových stavebnic a cizích doplňků, které se musí pořád aktualizovat a časem se rozbijí. Je to dražší na začátku a levnější v čase, protože nemáte co opravovat.</p>
                    <h2>Tři pásma, ve kterých dělám</h2>
                    <p><strong>Standard — 55 000 Kč.</strong> Web do dvanácti stránek na míru. Máte v něm jednoduchou správu obsahu, takže si texty, fotky nebo reference měníte sami. Zvládne i další jazykovou verzi. Tohle si objednává většina firem.</p>
                    <p><strong>Custom — od 95 000 Kč.</strong> E-shop, rezervační systém nebo aplikace na míru. Rozsah není daný dopředu, cena vychází z toho, co má web umět a na jaké systémy se napojuje.</p>
                    <p><strong>Startovní — 25 000 Kč.</strong> Výjimka, ne standardní vstup. Prezentace do pěti stránek pro živnostníka, kterému větší rozsah nedává smysl.</p>
                    <p>Nejsem plátce DPH. Cena, kterou vám řeknu, je konečná. Co přesně v jednotlivých pásmech je, máte rozepsané v <a href="/cenik">ceníku</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Cenu znáte předtím, než začnu pracovat. Ne až na faktuře.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Co cenu zvedne</h2>
                    <ul>
                    <li><strong>Napojení na systém, který už ve firmě používáte.</strong> Sklad, účetnictví, rezervace. Čím víc si dva systémy musí rozumět, tím víc práce to je.</li>
                    <li><strong>Další jazyky.</strong> Není to jen překlad textu. Je to další verze celého webu, kterou někdo musí spravovat.</li>
                    <li><strong>Obsah, který ještě neexistuje.</strong> Když nemáte fotky ani texty, musí se vyrobit. Domluvíme se předem, co zajistíte vy a co já, ať to není překvapení na faktuře.</li>
                    <li><strong>Rozsah, který roste za pochodu.</strong> Proto píšu specifikaci. Ať oba víme, kde je hranice.</li>
                    </ul>
                    <h2>Proč nejsem nejlevnější</h2>
                    <p>Protože nechci být. Web v šabloně za pár tisíc dává smysl, když potřebujete jen vizitku na internetu. Za tu cenu ho klidně mějte, řeknu vám to rovnou a nebudu vás přemlouvat.</p>
                    <p>Já stavím weby firmám, které web reálně používají v obchodu a chtějí ho mít pořádně. Za ten rozdíl dostanete řešení postavené na to, jak vaše firma funguje, a kód, který patří vám. Není zamčený u mě ani u žádné platformy, ze které byste nemohli odejít.</p>
                    <h2>Kdy ode mě web nekupujte</h2>
                    <p>Když máte rozpočet do dvaceti tisíc. Když potřebujete web do týdne. Když chcete jen opravit existující WordPress. Nic z toho nedělám a je lepší, když to víte teď, než po dvou schůzkách.</p>
                    <h2>Jak se dostanete k přesné ceně</h2>
                    <p>Napište mi, co potřebujete. Klidně stručně. Ozvu se do 24 hodin v pracovní dny a probereme to. Když z toho vyjde, že vám můžu pomoct, dostanete specifikaci s konkrétní cenou. Když ne, řeknu vám to a nebudu vám nic tlačit.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 4 — Příprava na nový web (nový slug jak-se-pripravit-na-novy-web)
            // ----------------------------------------------------------------
            4 => [
                'title'       => 'Co si připravit, než oslovíte vývojáře webu',
                'description' => 'Devět otázek, které si stejně budeme muset zodpovědět. Když si je projdete předem, ušetříme oba čas a dostanete přesnější cenu.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Skoro nikdo mi nenapíše s hotovým zadáním a je to v pořádku. Od toho jsem tu já, abych se doptal. Když si ale projdete otázky níž předem, zkrátíme celé kolečko o pár týdnů a dostanete přesnější cenu hned napoprvé.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Nemusíte mít odpovědi na všechno</h2>
                    <p>Tenhle článek není test. Je to seznam věcí, na které se stejně budu ptát. Když víte odpověď, napište mi ji rovnou. Když nevíte, napište „nevím" a probereme to. To je úplně legitimní odpověď a slyším ji často.</p>
                    <h2>1. Co má web pro vaši firmu dělat</h2>
                    <p>Má přivádět poptávky? Šetřit vám telefonování tím, že si lidé přečtou odpovědi sami? Prodávat? Nebo jen existovat, aby vás při výběrovém řízení nikdo nevyřadil? Všechno jsou platné cíle, ale vedou ke třem různým webům.</p>
                    <h2>2. Komu je určený</h2>
                    <p>Majitel malé firmy, nákupčí velké firmy a koncový zákazník čtou úplně jinak. Čím konkrétněji mi popíšete, kdo vám volá dnes, tím líp umím napsat strukturu stránek.</p>
                    <h2>3. Co má člověk na webu udělat</h2>
                    <p>Jedna hlavní věc na stránku. Zavolat, vyplnit formulář, stáhnout si ceník, objednat. Když má web dělat pět věcí najednou, nedělá pořádně žádnou.</p>
                    <h2>4. Co o vás lidé nevědí a měli by</h2>
                    <p>Tohle je nejcennější věc, kterou mi můžete dát. Většinou je to něco, co říkáte na schůzkách pořád dokola a na webu to chybí.</p>
                    <h2>5. Co dnes na webu nefunguje</h2>
                    <p>Když už web máte, napište mi konkrétně, co vás na něm štve. Nejde to upravit? Nedá se najít? Vypadá staře? Nechodí přes něj nic?</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Nejhorší zadání je „udělejte to hezky". Nejlepší je „tohle konkrétně mě štve".</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>6. Kdo bude obsah spravovat</h2>
                    <p>Když si budete chtít měnit texty a fotky sami, přidám vám jednoduchou správu obsahu a ukážu vám, jak na to. Když nechcete, nemusíme ji stavět a ušetříte. Obojí je v pořádku, jen to potřebuju vědět předem.</p>
                    <h2>7. Co už máte</h2>
                    <p>Logo, fotky, texty, přístupy k doméně a hostingu, e-shop s produkty v nějakém systému. Čím víc toho je, tím míň se toho musí vyrábět.</p>
                    <h2>8. Jaký máte rozpočet</h2>
                    <p>Vím, že tuhle otázku nikdo nemá rád. Ptám se proto, abych vám rovnou řekl, jestli to za tu cenu umím. Když ne, řeknu to hned a nebudeme oba ztrácet čas.</p>
                    <h2>9. Do kdy to potřebujete</h2>
                    <p>Jestli máte pevný termín kvůli veletrhu nebo otevírání provozovny, řekněte mi ho hned. Podle toho poznám, jestli to stihnu.</p>
                    <h2>Co se stane potom</h2>
                    <p>Z vašich odpovědí napíšu specifikaci. Je v ní popsané, co postavím, a cena, která platí. Teprve pak se rozhodujete, jestli do toho jdeme. Nic nepodepisujete dopředu.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 6 — Aplikace na míru (nový slug kdy-se-vyplati-aplikace-na-miru)
            // ----------------------------------------------------------------
            6 => [
                'title'       => 'Kdy se firmě vyplatí aplikace na míru místo tabulky v Excelu',
                'description' => 'Excel firmě stačí, dokud v něm nepracuje víc lidí a dokud chyba nestojí peníze. Pět signálů, že tabulka došla na hranici, a výpočet, jestli se aplikace vyplatí.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Osmnáct let jsem pracoval v Toyotě. Začínal jsem jako dělník v logistice a skončil jako senior specialista v projektovém týmu. Za tu dobu jsem viděl spoustu procesů, které běžely na tabulkách a papírech, a pár z nich jsem nahradil aplikací. Píšu, podle čeho poznáte, že jste v tom bodě taky.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Tabulka není nepřítel</h2>
                    <p>Excel je výborný nástroj a spousta firem na něm může fungovat roky bez problému. Nechci vám namluvit, že potřebujete aplikaci. Většinou nepotřebujete.</p>
                    <p>Problém nastává v okamžiku, kdy tabulku používá víc lidí najednou, kdy se z ní tiskne něco, podle čeho se pracuje, a kdy chyba v ní stojí peníze. Tam začíná mít smysl se ptát.</p>
                    <h2>Pět signálů, že tabulka došla na hranici</h2>
                    <ol>
                    <li><strong>Existuje víc verzí té samé tabulky</strong> a nikdo přesně neví, která je ta platná.</li>
                    <li><strong>Někdo přepisuje data z jednoho systému do druhého.</strong> Ručně, každý den, pořád dokola.</li>
                    <li><strong>Když je ten člověk nemocný, práce stojí.</strong> Proces drží na jednom člověku a jeho tabulce.</li>
                    <li><strong>Chyba se najde pozdě.</strong> Překlep v buňce se projeví až u zákazníka.</li>
                    <li><strong>Nikdo neumí říct, jak na tom teď jste.</strong> Číslo se musí složit z pěti souborů.</li>
                    </ol>
                    <p>Když z toho sedí jeden bod, nic neřešte. Když sedí tři a víc, vyplatí se to spočítat.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Aplikaci nepotřebujete proto, že je moderní. Potřebujete ji, když vás ruční práce stojí víc než její vývoj.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Co jsem dělal v Toyotě</h2>
                    <p>Mým úkolem bylo zefektivňovat logistiku v součinnosti s montáží. Ve výrobě si nemůžete dovolit, aby se linka zastavila, takže každá změna se musí promyslet dopředu a odzkoušet.</p>
                    <p>Naprogramoval jsem tam webovou aplikaci TSM, která nahradila část ruční práce v logistice. Postupně jsem do provozu zavedl i několik dalších aplikací.</p>
                    <p>Nešlo o efektní technologii. Šlo o to najít místo, kde se plýtvá časem, a to místo odstranit. Stejně přemýšlím i dnes, když pro firmu stavím aplikaci na míru.</p>
                    <h2>Jak si to spočítat sami</h2>
                    <p>Vezměte činnost, která se dělá ručně. Kolik minut denně zabere? Kolikrát za měsíc se u ní stane chyba a co ta chyba stojí? Vynásobte to dvanácti měsíci. Když vám vyjde číslo v řádu desítek tisíc ročně, aplikace na míru se vrátí za pár let a pak už jen šetří. Když vyjde pár tisíc, nechte to být a zůstaňte u tabulky.</p>
                    <p>Tenhle výpočet vám udělám zdarma při prvním hovoru. Když z něj vyjde, že se to nevyplatí, řeknu vám to.</p>
                    <h2>Co aplikace na míru je a co není</h2>
                    <p>Je to program postavený přesně na to, jak vaše firma pracuje. Evidence, objednávky, plánování, výkazy. Běží v prohlížeči, takže nic neinstalujete a dostanete se k ní i z telefonu.</p>
                    <p>Není to hotový systém, kterému se musíte přizpůsobit. To je ten hlavní rozdíl a taky důvod, proč to stojí víc než měsíční předplatné nějaké krabice.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 10 — Potřebuje firma web (slug beze změny)
            // ----------------------------------------------------------------
            10 => [
                'title'       => 'Potřebuje vaše firma web? Někdy ne, a řeknu vám kdy',
                'description' => 'Nejsem nestranný, weby dělám. Přesto existují situace, kdy vám web nepomůže. Píšu, které to jsou a co dělat místo toho.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Živím se stavěním webů, takže tenhle článek nepíšu nestranně a nebudu předstírat, že ano. Přesto znám případy, kdy web firmě nepomůže a peníze se dají utratit líp. Píšu na rovinu, které to jsou.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Kdy web opravdu nepotřebujete</h2>
                    <p><strong>Máte plno a zakázky chodí z doporučení.</strong> Když máte práci na měsíce dopředu a nové poptávky byste stejně odmítali, web vám teď nic nepřinese. Vraťte se k tomu, až budete chtít růst nebo měnit typ zakázek.</p>
                    <p><strong>Prodáváte jednomu velkému odběrateli.</strong> Když vaše firma stojí na dvou dlouhodobých smlouvách, web je vizitka, ne obchodní kanál. Stačí jednoduchá stránka s kontakty a nemusíte za ni dát sto tisíc.</p>
                    <p><strong>Nemáte, kdo by zvedal telefon.</strong> Web, který přivede poptávky, na které nikdo neodpoví, je horší než žádný web. Zákazník si zapamatuje, že jste se neozvali.</p>
                    <p><strong>Hledáte zázrak.</strong> Web je nástroj, ne řešení. Když firma nemá jasno, co prodává a komu, web to nespraví. Jen to napíše větším písmem.</p>
                    <h2>Kdy web smysl má</h2>
                    <p><strong>Lidé si vás ověřují, než zavolají.</strong> Tohle dnes dělá skoro každý. Když najdou jen profil na Firmy.cz z roku 2019, hraje to proti vám.</p>
                    <p><strong>Vysvětlujete pořád to samé.</strong> Když na každé schůzce opakujete, jak probíhá spolupráce a co je v ceně, web to vysvětlí za vás. Vy pak mluvíte s lidmi, kteří už to vědí.</p>
                    <p><strong>Konkurence vypadá líp, než pracuje.</strong> To je nepříjemné, ale rozhoduje to.</p>
                    <p><strong>Chcete jiné zakázky, než máte.</strong> Web je nejlevnější způsob, jak dát najevo, že děláte i větší a náročnější věci.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Web nepřinese zakázky za vás. Ale umí říct dopředu to, co jinak vysvětlujete na každé schůzce znovu.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Co web nedokáže</h2>
                    <p>Neslíbím vám, kolik poptávek přinese. Nemám jak ovlivnit, co je ve vašem oboru za poptávku, jakou máte cenu a jak rychle odpovídáte. Kdokoli vám tohle číslo slíbí, hádá.</p>
                    <p>Co ovlivnit můžu, je práce, kterou odvedu. Že web bude rychlý, srozumitelný, bude dobře vypadat na telefonu, a že se v něm za dva roky nebude nic rozpadat.</p>
                    <h2>Než se rozhodnete</h2>
                    <p>Zkuste si odpovědět na jednu otázku. Kdyby na váš web přišel zítra člověk, který o vás nikdy neslyšel, pochopil by do deseti vteřin, co děláte a jestli je to pro něj? Když ne, tam je ten problém, a je jedno, jestli web máte nebo ne.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 13 — Redesign (slug beze změny)
            // ----------------------------------------------------------------
            13 => [
                'title'       => 'Kdy má smysl předělat web a kdy je to vyhozený výdaj',
                'description' => 'Pět důvodů, kdy redesign webu dává smysl, a tři, kdy je to zbytečné. Plus co si pohlídat, aby vám po předělání nespadla návštěvnost.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Redesign se většinou řeší ve chvíli, kdy se web někomu přestane líbit. To je ten nejslabší důvod, jaký znám. Píšu, kdy má předělání webu smysl, kdy nemá, a co se u něj nejčastěji pokazí.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Pět důvodů, kdy redesign dává smysl</h2>
                    <p><strong>1. Web nejde spravovat.</strong> Změna telefonního čísla znamená napsat někomu, kdo se ozve za týden. Tohle samo o sobě stojí za předělání.</p>
                    <p><strong>2. Na telefonu je to k nepoužití.</strong> Většina lidí se dnes dívá na web z telefonu. Když se na něm musí zvětšovat a posouvat do stran, odejdou.</p>
                    <p><strong>3. Web se rozpadá nebo padá.</strong> Typicky u stavebnic poskládaných z doplňků od různých autorů. Jedna aktualizace a nefunguje objednávkový formulář.</p>
                    <p><strong>4. Změnila se firma.</strong> Děláte něco jiného, něco jiného chcete prodávat, máte jinou cenovou hladinu. Web zůstal tam, kde jste byli před pěti lety.</p>
                    <p><strong>5. Weby lidí, se kterými soutěžíte, vypadají o třídu líp.</strong> Zákazník vás srovnává vedle sebe, ať chcete nebo ne.</p>
                    <h2>Tři situace, kdy peníze nechat v kapse</h2>
                    <p><strong>Web je starý dva roky a funguje.</strong> Stáří samo o sobě není důvod. Když se dá spravovat, je rychlý a lidé po něm najdou, co potřebují, nechte ho být.</p>
                    <p><strong>Nelíbí se vám, ale zákazníkům nevadí.</strong> Váš vkus a vkus vašeho zákazníka není totéž. Než do toho dáte sto tisíc, zeptejte se pěti klientů, co jim na webu chybělo.</p>
                    <p><strong>Skutečný problém je jinde.</strong> Když poptávky nechodí, protože jste o tři třídy dražší než okolí a nikde to nevysvětlujete, nový vzhled to nespraví.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Redesign je práce s obsahem a se strukturou. Vzhled je až důsledek.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Co se u redesignu nejčastěji pokazí</h2>
                    <p><strong>Zahodí se adresy stránek.</strong> Nový web má jinou strukturu a staré adresy přestanou fungovat. Vyhledávače i odkazy z cizích webů najednou vedou do prázdna. Řešení je jednoduché a dělá se před spuštěním: stará adresa musí trvale přesměrovávat na tu novou. Chci, abyste to po komkoli, kdo vám web dělá, vyžadovali.</p>
                    <p><strong>Předělá se jen vzhled.</strong> Texty se překopírují jedna ku jedné, včetně těch, kterým nikdo nerozuměl. Web pak vypadá nově a funguje stejně špatně.</p>
                    <p><strong>Zmizí věci, které fungovaly.</strong> Někdy má starý web stránku, na kterou chodí půlka návštěvnosti. Než se něco maže, je potřeba se podívat do statistik.</p>
                    <p><strong>Nikdo nepřevezme obsah.</strong> Reference, fotky z realizací, dokumenty ke stažení. Bývá toho víc, než se čeká.</p>
                    <h2>Jak k redesignu přistupuju já</h2>
                    <p>Nejdřív se podívám, co na starém webu funguje, a to zachovám. Pak projdeme, co má web dělat a komu to má říct. Teprve potom se řeší, jak to bude vypadat. Adresy stránek řeším před spuštěním, ne po něm.</p>
                    <p>Kód píšu vlastní, bez hotových doplňků od cizích autorů. Právě ty bývají důvod, proč se web po čase rozpadne a předělává se znovu.</p>
                    HTML,
            ],

        ];
    }
}

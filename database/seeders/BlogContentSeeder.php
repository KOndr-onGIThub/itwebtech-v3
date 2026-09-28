<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-204 (OND-197 bod 11b) — přepis blogu do Ondrova hlasu.
 *
 * Zdroj textů: dokument `blog-texty` na OND-203 (Content Writer).
 * Rozhodnutí: 5 článků přepsat, 7 stáhnout z výpisu, 1 testovací už je smazaný.
 * OND-432: ze sedmi stažených se 1, 5, 7, 9, 11 a 12 vrátily přepsané, stažený
 * zůstává jen 8. Na existující DB je přenáší migrace
 * 2026_09_28_100000_ond432_clanky_texty_publikace.
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
     * 8 Web, který převádí — sloučený do článku 11 (OND-421), adresa vede na 11.
     *
     * Do OND-432 tu byly i 1, 5, 7, 9, 11 a 12. Ty jsou od té doby přepsané
     * v `articles()` a publikované; kdyby tu zůstaly, `run()` by je na čerstvé
     * DB odpublikoval.
     */
    public const UNPUBLISHED_ARTICLE_IDS = [8];

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

        $count = count($this->articles());
        $this->note("[blog-content] hotovo: {$count} článků přepsáno, {$unpublished} staženo z výpisu.");
    }

    private function note(string $message): void
    {
        $this->command?->info($message);
    }

    /**
     * Finální texty (dokument `blog-texty`, OND-203). Články 1, 5, 7, 9, 11 a 12
     * a věty s odkazy na ně ve 3, 4, 10 a 13 přidalo OND-432 (dokument
     * `clanky-final-cs` na OND-430).
     *
     * Čísla v článku „Kolik stojí web" jsou převzatá z ceníku (`lang/cs/price.php`):
     * rozpětí 55–150 tisíc a vstupní cena od 20 000 Kč, úrovně bez ceny
     * (OND-347, nasazeno OND-389). Ceník je zdroj pravdy — když se změní,
     * srovnat i tenhle text.
     *
     * Výzva k akci na konci článku se nepřidává do textu — detail článku už
     * renderuje jednu CTA sekci z lang souborů (`blog.cta.*`).
     *
     * @return array<int, array<string, string|null>>
     */
    public function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web (slug kolik-stoji-webove-stranky, beze změny)
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'Kolik stojí web na míru a z čeho se ta cena skládá',
                'description' => 'Kolik u mě stojí web, co v té ceně je a co ji zvedá nebo snižuje. Ať víte předem, jestli se vejdu do vašeho rozpočtu.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Na cenu se mě lidé ptají jako na první věc a je to správná otázka. Jedno číslo vám ale nikdo poctivě říct nemůže. Web za dvacet tisíc a web za sto padesát tisíc jsou dvě různé věci. Napsal jsem proto na rovinu, kolik u mě web stojí, co v té ceně je a co ji posouvá nahoru nebo dolů.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Proč nemám jedno číslo</h2>
                    <p>Web není zboží ze skladu. Když mi napíšete, že chcete web, vím zatím jen to, že chcete web. Nevím, kolik má mít stránek. Nevím, jestli si obsah budete chtít spravovat sami. Nevím, jestli přes něj potřebujete prodávat nebo jestli má fungovat i anglicky. Každá z těch věcí s cenou pohne.</p>
                    <p>Dělám to tak, že si nejdřív projdeme, co potřebujete. Pak vám napíšu specifikaci, kde je černé na bílém, co postavím a za kolik. Ta cena platí. Faktura na konci odpovídá specifikaci na začátku. Když v průběhu zjistíte, že chcete něco navíc, řeknu vám cenu dopředu a rozhodnete se vy.</p>
                    <h2>Za co vlastně platíte</h2>
                    <p>Platíte můj čas a to, co s ním umím udělat. Nekupujete licenci k šabloně ani hodiny obchodníka, který vám web prodal a pak zmizel. Pracuju sám, takže v ceně není agenturní režie ani koordinátor, který mi přeposílá vaše e-maily.</p>
                    <p>Weby píšu vlastním kódem. Nestavím je z hotových stavebnic a cizích doplňků, které se musí pořád aktualizovat a časem se rozbijí. Je to dražší na začátku a levnější v čase, protože nemáte co opravovat.</p>
                    <h2>Kolik to u mě vychází</h2>
                    <p>Většina projektů vychází mezi 55 a 150 tisíci korunami. Nejmenší web, který stavím, je prezentace do pěti stránek od 20 000 Kč. Kolik bude stát ten váš, určuje hlavně rozsah.</p>
                    <p><strong>Prezentační web — do pěti stránek.</strong> Pro živnostníky a malé firmy, kterým větší rozsah nedává smysl. Bude rychlý, na telefonu se bude ovládat dobře a nebude na něm rozbitý odkaz na poptávku. Nečekejte od něj, že vám sám začne vozit zakázky — na to je potřeba víc práce, než se za tu cenu dá odvést. Ale hotový bude poctivě.</p>
                    <p><strong>Firemní web — do dvanácti stránek.</strong> Web na míru s jednoduchou správou obsahu, takže si texty, fotky nebo reference měníte sami. Zvládne i další jazykovou verzi. Tohle si objednává většina firem.</p>
                    <p><strong>Na míru — bez omezení rozsahu.</strong> E-shop, rezervační systém nebo aplikace na míru. Rozsah není daný dopředu, cena vychází z toho, co má web umět a na jaké systémy se napojuje.</p>
                    <p>Nejsem plátce DPH. Cena, kterou vám řeknu, je konečná. Co přesně je v jednotlivých úrovních, máte rozepsané v <a href="/cenik">ceníku</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Cenu znáte předtím, než začnu pracovat. Ne až na faktuře.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Co cenu zvedne</h2>
                    <ul>
                    <li><strong>Napojení na systém, který už ve firmě používáte.</strong> Sklad, účetnictví, rezervace. Čím víc si dva systémy musí rozumět, tím víc práce to je.</li>
                    <li><strong>Další jazyky.</strong> Není to jen překlad textu. Je to další verze celého webu, kterou někdo musí spravovat.</li>
                    <li><strong>Obsah, který ještě neexistuje.</strong> Když nemáte fotky ani texty, musí se vyrobit. Domluvíme se předem, co zajistíte vy a co já, ať to není překvapení na faktuře. Proč na obsahu záleží víc než na vzhledu, píšu v článku <a href="/zapisky/co-je-dulezitejsi-design-nebo-obsah-webovych-stranek">Design, nebo obsah?</a></li>
                    <li><strong>Rozsah, který roste za pochodu.</strong> Proto píšu specifikaci. Ať oba víme, kde je hranice.</li>
                    </ul>
                    <h2>Co cenu sníží</h2>
                    <ul>
                    <li><strong>Texty a fotky máte připravené.</strong> Nic se nemusí vyrábět a můžu rovnou stavět.</li>
                    <li><strong>Menší počet stránek.</strong> Méně práce, nižší cena.</li>
                    <li><strong>Jeden jazyk.</strong> Jedna verze webu, kterou stačí postavit a spravovat.</li>
                    <li><strong>Obsah si plníte sami.</strong> Ukážu vám, jak na to, a texty a fotky do webu vkládáte vy, ne já.</li>
                    </ul>
                    <h2>Proč nejsem nejlevnější</h2>
                    <p>Protože nechci být. Web v šabloně za pár tisíc dává smysl, když potřebujete jen vizitku na internetu. Za tu cenu ho klidně mějte, řeknu vám to rovnou a nebudu vás přemlouvat.</p>
                    <p>Já stavím weby firmám, které web reálně používají v obchodu a chtějí ho mít pořádně. Za ten rozdíl dostanete řešení postavené na to, jak vaše firma funguje, a kód, který patří vám. Není zamčený u mě ani u žádné platformy, ze které byste nemohli odejít.</p>
                    <h2>Kdy ode mě web nekupujte</h2>
                    <p>Když potřebujete web do týdne. Když chcete jen opravit existující WordPress. Ani jedno nedělám a je lepší, když to víte teď, než po dvou schůzkách.</p>
                    <h2>Jak se dostanete k přesné ceně</h2>
                    <p>Napište mi, co potřebujete. Klidně stručně. Ozvu se nejpozději následující pracovní den a probereme to. Když z toho vyjde, že vám můžu pomoct, dostanete specifikaci s konkrétní cenou. Když ne, řeknu vám to a nebudu vám nic tlačit.</p>
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
                    <p>Jedna hlavní věc na stránku. Zavolat, vyplnit formulář, stáhnout si ceník, objednat. Když má web dělat pět věcí najednou, nedělá pořádně žádnou. Co dalšího rozhoduje o tom, jestli web funguje, jsem sepsal v článku <a href="/zapisky/jak-vytvorit-uspesnou-webovou-stranku">Jak vytvořit úspěšnou webovou stránku</a>.</p>
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
                    <p>Logo, fotky, texty, přístupy k doméně a hostingu, e-shop s produkty v nějakém systému. Čím víc toho je, tím míň se toho musí vyrábět. Když máte hotovou <a href="/zapisky/zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">analýzu konkurence</a> nebo <a href="/zapisky/jak-na-analyzu-klicovych-slov-krok-za-krokem">klíčových slov</a>, pošlete mi ji taky.</p>
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
                    <p>Zkuste si odpovědět na jednu otázku. Kdyby na váš web přišel zítra člověk, který o vás nikdy neslyšel, pochopil by do deseti vteřin, co děláte a jestli je to pro něj? Když ne, tam je ten problém, a je jedno, jestli web máte nebo ne. Co dalšího musí web splnit, aby fungoval, píšu v článku <a href="/zapisky/jak-vytvorit-uspesnou-webovou-stranku">Jak vytvořit úspěšnou webovou stránku</a>.</p>
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
                    <p><strong>5. Weby lidí, se kterými soutěžíte, vypadají o třídu líp.</strong> Zákazník vás srovnává vedle sebe, ať chcete nebo ne. Jak se na ně podívat pořádně, popisuju v článku o <a href="/zapisky/zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">analýze konkurence</a>.</p>
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
                    <p><strong>Zahodí se adresy stránek.</strong> Nový web má jinou strukturu a staré adresy přestanou fungovat. Vyhledávače i odkazy z cizích webů najednou vedou do prázdna. Řešení je jednoduché a dělá se před spuštěním: stará adresa musí trvale přesměrovávat na tu novou. Chci, abyste to po komkoli, kdo vám web dělá, vyžadovali. Proč jsou pozice ve vyhledávání tak cenné, vysvětluju v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO</a>.</p>
                    <p><strong>Předělá se jen vzhled.</strong> Texty se překopírují jedna ku jedné, včetně těch, kterým nikdo nerozuměl. Web pak vypadá nově a funguje stejně špatně. Proč je obsah důležitější než vzhled, píšu v článku <a href="/zapisky/co-je-dulezitejsi-design-nebo-obsah-webovych-stranek">Design, nebo obsah?</a></p>
                    <p><strong>Zmizí věci, které fungovaly.</strong> Někdy má starý web stránku, na kterou chodí půlka návštěvnosti. Než se něco maže, je potřeba se podívat do statistik.</p>
                    <p><strong>Nikdo nepřevezme obsah.</strong> Reference, fotky z realizací, dokumenty ke stažení. Bývá toho víc, než se čeká.</p>
                    <h2>Jak k redesignu přistupuju já</h2>
                    <p>Nejdřív se podívám, co na starém webu funguje, a to zachovám. Pak projdeme, co má web dělat a komu to má říct. Teprve potom se řeší, jak to bude vypadat. Adresy stránek řeším před spuštěním, ne po něm.</p>
                    <p>Kód píšu vlastní, bez hotových doplňků od cizích autorů. Právě ty bývají důvod, proč se web po čase rozpadne a předělává se znovu.</p>
                    HTML,
            ],

            // ================================================================
            // OND-432 — články 1, 5, 7, 9, 11, 12 (dokument `clanky-final-cs` na OND-430
            // + redliny `review-preklady` z OND-431). Na rozdíl od článků výš nesou
            // i obrázková pole a `bonus`/`extra` = null: starý import v nich měl
            // bloky, které by se vykreslily pod novým textem.
            // Obrázky s anglickým textem (SEO.webp u pětky, update.webp u dvanáctky) jsou pryč
            // (rozhodnutí CEO, OND-430). img_preview a img_main se nemění, proto tu nejsou.
            // ================================================================

            // ----------------------------------------------------------------
            // 1 — Jak vybrat doménové jméno (slug jak-vybrat-perfektni-domenove-jmeno, beze změny)
            // ----------------------------------------------------------------
            1 => [
                'title'       => 'Jak vybrat doménové jméno, které si lidé zapamatují',
                'description' => 'Jak vybrat doménu, která se dobře píše i pamatuje. Deset pravidel pro výběr doménového jména, cena domény a co ověřit před registrací.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Doménu vybíráte jednou a pak ji roky píšete na faktury, na vizitky a pod každý e-mail. Změnit ji později jde, ale stojí to práci a část lidí, kteří vás už znají. Sepsal jsem, podle čeho postupovat při výběru doménového jména, co si ověřit před registrací a na co jsem u klientů narazil sám.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Stručně: jak vybrat doménu</h2>
                    <ol>
                    <li><strong>Ať sedí k tomu, kdo jste.</strong> Název firmy, vaše jméno, nebo to, co děláte.</li>
                    <li><strong>Krátká.</strong> Ideálně 8 až 12 znaků, bez číslic a pomlček.</li>
                    <li><strong>Zapamatovatelná napoprvé.</strong> Žádné zkratky, které nikomu nic neřeknou.</li>
                    <li><strong>Napíše se po poslechu.</strong> Řeknete ji jednou a druhý ji napíše správně.</li>
                    <li><strong>Slovo z oboru jen tehdy, když sedí.</strong> Pozice ve vyhledávání vám nepřinese.</li>
                    <li><strong>S rezervou do budoucna.</strong> Nesvazujte se jedním výrobkem ani jedním městem.</li>
                    <li><strong>Volná a bez nepříjemných souvislostí.</strong> Ověřte obsazenost a zadejte ji do vyhledávače.</li>
                    <li><strong>Bez špatné minulosti.</strong> Podívejte se, co na ní dřív běželo.</li>
                    <li><strong>Na koncovce .cz</strong>, když prodáváte v Česku.</li>
                    <li><strong>Bez cizí ochranné známky.</strong> Ušetříte si spor.</li>
                    </ol>
                    <p>Každý bod níž rozepisuju i s příklady z praxe.</p>
                    <h2>Co je doménové jméno a potřebujete ho vůbec?</h2>
                    <p>Doménové jméno, zkráceně doména, je adresa, kterou člověk napíše do prohlížeče, aby se dostal na váš web. Počítače se na internetu hledají podle číselných IP adres. Doména je jméno, které si člověk zapamatuje místo nich. Třeba <em>mojefirma.cz</em>.</p>
                    <p>Doménu se vyplatí mít, i když web nemáte. Stačí kvůli e-mailu. Adresa <em>info@mojefirma.cz</em> působí jinak než <em>mojefirma@seznam.cz</em>. Část před zavináčem si volíte sami, takže můžete mít schránku pro každého zaměstnance a všechny ponesou jméno firmy.</p>
                    <p>Jestli potřebujete web, nebo vám zatím stačí doména a e-mail, jsem rozebral v článku <a href="/zapisky/potrebuje-vase-firma-webovou-stranku">Potřebuje vaše firma web?</a></p>
                    <h2>1. Jak zvolit doménové jméno, které sedí k vašemu podnikání</h2>
                    <p><strong>Malá místní firma</strong> si může dovolit doménu, která říká, co dělá. Když provozujete půjčovnu lodí, <em>navode.cz</em> cizímu člověku na první pohled řekne, o co jde.</p>
                    <p><strong>Firma, která chce růst,</strong> na tom je jinak. Doména <em>postele.cz</em> je skvělá, dokud neprodáváte i stoly a zahradní nábytek. Pak začne svazovat. Větší firmy proto většinou staví na značce, ne na popisu sortimentu.</p>
                    <p><strong>Když podnikáte sami</strong>, zvažte vlastní jméno, třeba <em>petrnovak.cz</em>. Značka postavená na jméně se buduje mnohem snáz než na neosobním názvu firmy. Jen to jméno musí být volné a musí se dát napsat. K tomu se dostanu v bodě 4.</p>
                    <p>U každé varianty si udělejte aspoň rychlý <a href="/zapisky/zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">průzkum konkurence</a>. Nechcete, aby si vás lidé pletli s někým, kdo dělá totéž o dvě ulice dál.</p>
                    <p>Když firmu teprve zakládáte, vybírejte název a doménu zároveň. Ověřte si, že je doména volná, dřív, než název necháte zapsat do rejstříku a vyrobit logo. Jak postupovat u samotného názvu, dobře popisuje článek <a href="https://www.mojesidlo.cz/jak-vybrat-nazev-firmy/" target="_blank" rel="noopener">jak vybrat název firmy</a>.</p>
                    <h2>2. Délka domény a povolené znaky</h2>
                    <p>Čím kratší, tím lepší. Technicky smí mít doména až 63 znaků, ale ideální je vejít se do 8 až 12. Takovou doménu napíšete na vizitku i nadiktujete do telefonu.</p>
                    <p>V doméně .cz můžete použít <strong>písmena a–z, číslice 0–9 a pomlčku</strong>. Háčky, čárky, mezery ani jiné znaky povolené nejsou. Pomlčka nesmí být na začátku ani na konci a nesmí být dvě za sebou. Velká písmena napsat můžete, ale nic neznamenají. <em>ONDRAWEB.cz</em> i <em>ondraweb.cz</em> vás zavedou na stejné místo.</p>
                    <p><strong>Číslicím se vyhněte</strong>, pokud nejsou součástí značky. U <em>3firma.cz</em> nikdo neví, jestli psát trojku, nebo <em>tretifirma.cz</em>.</p>
                    <p><strong>Pomlčky jsou pozůstatek.</strong> Dřív se jimi oddělovala slova, protože mezera v doméně být nemůže. Dnes se slova píšou dohromady a pomlčka je jen další věc, kterou musíte do telefonu hláskovat.</p>
                    <h2>3. Výběr snadno zapamatovatelného doménového jména</h2>
                    <p>Doména má člověku utkvět po prvním slyšení. Bez složitých slov a bez pravopisných chytáků.</p>
                    <p>Setkávám se s tím u zkratek. Firma má dlouhý název, často anglický, a chce doménu složenou z počátečních písmen. Pro ni ta písmena něco znamenají. Pro zákazníka je to šest náhodných znaků.</p>
                    <p>Na jednom webu, který jsem nestavěl, ale dělal jsem na něm aktualizace a kontroly zabezpečení, je doména přesně taková zkratka. Pracoval jsem na něm opakovaně, a stejně jsem měl problém si ji zapamatovat.</p>
                    <h2>4. Doménové jméno, které je snadné napsat</h2>
                    <p>Doménu lidé často neuvidí napsanou, ale uslyší ji. Do telefonu, na veletrhu, od známého. Musí se dát napsat po poslechu.</p>
                    <ul>
                    <li><strong>Písmena, která zní jinak, než se píšou.</strong> G na konci slova slyšíme jako K. U cizích slov lidé nevědí, jestli psát <em>w</em>, nebo <em>v</em>.</li>
                    <li><strong>Znaky, které se pletou.</strong> Nula a písmeno O, velké I a malé L. Na vizitce vypadají skoro stejně.</li>
                    <li><strong>Zdvojená písmena na styku dvou slov.</strong> V <em>autoopravy.cz</em> část lidí jedno O vynechá.</li>
                    </ul>
                    <p><strong>Udělejte si test.</strong> Řekněte doménu jednou nahlas někomu, kdo ji nezná, a nechte ho ji napsat. Když se musí doptávat nebo ji napíše jinak, doména testem neprošla. Je lepší to zjistit teď než po tisíci vytištěných letáků.</p>
                    <h2>5. Zvažte použití klíčových slov v doméně</h2>
                    <p>Slovo z oboru v doméně může pomoct. Ne ve vyhledávači, tam vám dnes pozice nepřinese. Pomůže člověku, který doménu vidí ve výsledcích hledání a hned ví, co na webu najde. Co ve vyhledávači opravdu rozhoduje, píšu v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO</a>.</p>
                    <p>Realitní makléř v Brně může mít <em>realitybrno.cz</em>. Jen s tím, že z Brna se pak těžko dostane. O tom je další bod.</p>
                    <p>Nepřehánějte to. Tři klíčová slova za sebou působí jako spam. A takové jednoduché domény jsou stejně většinou dávno obsazené.</p>
                    <h2>6. Myslete na budoucnost</h2>
                    <p>Firma se může rozrůst o nový sortiment nebo do dalšího města. Doména by jí v tom neměla stát v cestě. Když k půjčovně lodí časem přidáte vodní lyžování, <em>navode.cz</em> pořád sedí. <em>pujcovnalodi.cz</em> už ne.</p>
                    <p>Doménu jde změnit i později. Stará adresa ale musí trvale přesměrovat na novou, jinak přijdete o odkazy i o pozice ve vyhledávání. Co u takové změny hlídat, píšu v článku o <a href="/zapisky/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">předělání webu</a>.</p>
                    <h2>7. Zkontrolujte dostupnost</h2>
                    <p>Jestli je doména .cz volná, ověříte zdarma ve <a href="https://www.nic.cz/whois/" target="_blank" rel="noopener">vyhledávání na webu CZ.NIC</a>, který českou koncovku spravuje. Stejné hledání mají na webu i registrátoři, u kterých doménu kupujete.</p>
                    <p><strong>Když je obsazená</strong>, podívejte se, co na ní je. Běží na ní cizí web? Hledejte jiné jméno. Je prázdná nebo na prodej? Můžete zkusit držitele oslovit, jen cenu pak určuje on. K obsazenému jménu jde přidat slovo nebo město, ale ověřte si, že si vás lidé nespletou s tím, kdo má původní doménu.</p>
                    <p>Když už hledáte, podívejte se rovnou, jestli je stejné jméno volné i na sociálních sítích, které používáte.</p>
                    <p><strong>Pak doménu zadejte do vyhledávače.</strong> Občas zjistíte, že s tím slovem má internet spojené něco, co nechcete. Když jsem vymýšlel značku pro klientky z oboru financí a realit, dostal se do užšího výběru název <em>findom</em>. Pak jsem ho zadal do Googlu. Je to označení pro fetiš.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Dobrou doménu poznáte tak, že ji nikomu nemusíte hláskovat.</p></blockquote>
                    HTML,
                'img_mid'     => 'findom.webp',
                'img_mid_alt' => 'Google u slova findom neukazuje finance, ale fetiš',
                'content_2'   => <<<'HTML'
                    <h2>8. Zkontrolujte historii domény</h2>
                    <p>Doména, která je dnes volná, mohla mít minulost. Když na ní dřív běžel spam nebo podvodný obchod, vyhledávače si to můžou pamatovat. Co na ní bylo, zjistíte zdarma v internetovém archivu <a href="https://web.archive.org/" target="_blank" rel="noopener">web.archive.org</a>. Stačí do něj doménu zadat.</p>
                    <h2>9. Zvažte registraci více koncovek</h2>
                    <p>Koncovka je část za poslední tečkou. Jsou dva druhy:</p>
                    <ul>
                    <li><strong>národní</strong>, vázané na zemi nebo region: <em>.cz</em>, <em>.sk</em>, <em>.de</em>, <em>.eu</em>,</li>
                    <li><strong>obecné</strong>, bez vazby na zemi: <em>.com</em>, <em>.net</em>, <em>.info</em>.</li>
                    </ul>
                    <p>Když prodáváte v Česku, berte <strong>.cz</strong>. Český zákazník ji zná a věří jí. Když chystáte slovenskou nebo německou verzi webu, zaregistrujte si i <em>.sk</em> nebo <em>.de</em>. A když na jménu stavíte značku, stojí za to koupit i <em>.com</em> a <em>.eu</em>, aby je nezabral konkurent nebo spekulant. Jsou to stovky korun ročně za klid.</p>
                    <h2>10. Ověřte, že jméno není chráněno jako ochranná známka</h2>
                    <p>Když doména obsahuje cizí ochrannou známku, můžete se s jejím majitelem dostat do sporu o doménu i o název. Než doménu koupíte, zadejte jméno do <a href="https://upv.gov.cz/informacni-zdroje/narodni-databaze/databaze-ochrannych-znamek" target="_blank" rel="noopener">databáze ochranných známek Úřadu průmyslového vlastnictví</a>. Známky platné v celé Evropské unii najdete v databázi <a href="https://www.tmdn.org/tmview/" target="_blank" rel="noopener">TMview</a>.</p>
                    <h2>Kolik stojí doména?</h2>
                    <p>Doména .cz vyjde u běžných registrátorů zhruba na 200 až 450 Kč ročně s DPH. Doména .com zhruba na 400 až 550 Kč. První rok bývá v akci levnější a prodloužení pak stojí víc. Porovnávejte proto cenu za prodloužení, ne za registraci. Dopředu se doména .cz dá zaplatit až na deset let.</p>
                    <p>V úrovni Firemní web je doména i hosting na první rok v ceně. Co je v které úrovni, najdete v <a href="/cenik">ceníku</a>.</p>
                    <p>Úplně jiný svět jsou aukce. Když držitel doménu neprodlouží, CZ.NIC ji po zrušení nejdřív nabídne v aukci. V roce 2024 se tak prodala <em>virtualnisidlo.cz</em> za tehdy rekordních 152 077 Kč. V lednu 2026 ji překonala <em>gol.cz</em> za 198 000 Kč. Dobré jméno má pro obor svou cenu.</p>
                    <h2>Na koho má být doména napsaná</h2>
                    <p>Doména patří tomu, kdo je v registru zapsaný jako <strong>držitel</strong>. Ne tomu, kdo ji zaplatil, a ne tomu, kdo vám postavil web. Když ji za vás registruje vývojář nebo agentura, trvejte na tom, aby držitelem byla vaše firma. Jinak potřebujete jeho souhlas při každé změně, a hlavně když od něj budete chtít odejít. Kdo je držitelem vaší domény, zjistíte ve stejném <a href="https://www.nic.cz/whois/" target="_blank" rel="noopener">vyhledávání na webu CZ.NIC</a>.</p>
                    <p>Hlídejte si i platnost. Když doménu .cz včas neprodloužíte, třicet dní ještě funguje. Potom přestane fungovat web i e-maily. Po šedesáti dnech doménu CZ.NIC zruší a pošle do aukce, kde ji může koupit kdokoli.</p>
                    <p>Přístupy k doméně patří mezi věci, které se hodí mít po ruce, než se pustíte do nového webu. Celý seznam je v článku <a href="/zapisky/jak-se-pripravit-na-novy-web">Co si připravit, než oslovíte vývojáře webu</a>.</p>
                    <h2>Části domény a jak poznat falešný odkaz</h2>
                    <p>Na závěr pro ty, kdo chtějí rozumět tomu, co čtou v adresním řádku. Nejvíc se to hodí, když chcete poznat podvodný odkaz.</p>
                    <p>Doména se čte zprava doleva. Vezměme <code>blog.mujweb.cz</code>:</p>
                    <ul>
                    <li><strong>Doména nejvyššího řádu</strong> (TLD) je koncovka, tady <code>cz</code>.</li>
                    <li><strong>Doména druhého řádu</strong> je jméno, které si registrujete a platíte, tady <code>mujweb</code>. Právě tohle vybíráte.</li>
                    <li><strong>Subdoména</strong> je všechno nalevo, tady <code>blog</code>. Subdomény si držitel vytváří sám, bez další registrace. Třeba <code>eshop.mujweb.cz</code> pro e-shop.</li>
                    </ul>
                    <p><strong>Komu doména patří, poznáte podle posledních dvou částí.</strong> Adresa <code>google.cokoli.com</code> nepatří Googlu, ale tomu, kdo vlastní <code>cokoli.com</code>. Právě na tomhle stojí většina podvodných e-mailů.</p>
                    <p>Celá webová adresa (URL) má ještě další části. Na příkladu <code>https://www.blog.mujweb.cz/clanek-o-domenach?strana=2#sekceB</code>:</p>
                    <ul>
                    <li><strong>Protokol</strong> <code>https</code>. Písmeno S znamená, že spojení je šifrované. Na web bez něj nezadávejte hesla ani platební údaje. Psát ho nemusíte, prohlížeč ho dnes zkusí sám.</li>
                    <li><strong><code>www</code></strong> je jen subdoména, kterou si weby kdysi dávaly podle World Wide Web. Psát ji nemusíte. Web by ale měl běžet jen na jedné variantě a druhou na ni přesměrovat, jinak ho vyhledávač může vidět dvakrát.</li>
                    <li><strong>Cesta</strong> <code>/clanek-o-domenach</code> vede ke konkrétní stránce nebo souboru na webu.</li>
                    <li><strong>Parametr</strong> <code>?strana=2</code> předává webu doplňující informaci, tady číslo strany.</li>
                    <li><strong>Kotva</strong> <code>#sekceB</code> odkazuje na konkrétní místo na stránce.</li>
                    </ul>
                    HTML,
                'img_end'     => 'casti_domeny.webp',
                'img_end_alt' => 'Adresa https://www.blog.mujweb.cz pod lupou',
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 5 — Co je SEO (slug co-je-seo-a-proc-je-tak-dulezite, beze změny)
            // ----------------------------------------------------------------
            5 => [
                'title'       => 'Co je SEO a proč je důležité: vysvětlení bez žargonu',
                'description' => 'Co je SEO, jak funguje a co z něj zvládnete sami. Technické SEO, obsah a zpětné odkazy vysvětlené pro majitele firmy, bez slibů prvního místa.',
                'perex'       => <<<'HTML'
                    <blockquote><p>SEO je zkratka pro optimalizaci pro vyhledávače. Jde o to, aby vás lidé našli na Googlu a na Seznamu ve chvíli, kdy hledají to, co nabízíte. Sepsal jsem, z čeho se SEO skládá, co z toho zvládnete sami a kdy nevěřit tomu, kdo vám ho prodává.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Co je SEO</h2>
                    <p>SEO (z anglického <em>Search Engine Optimization</em>) je práce na webu a kolem něj, díky které se stránky ukazují výš v neplacených výsledcích vyhledávání. Neplacených proto, že za návštěvu z nich neplatíte za každé kliknutí jako u reklamy.</p>
                    <p>V Česku to znamená dva vyhledávače, Google a Seznam. Hodnotí weby podobně, ale ne stejně. Stránka může být na Seznamu na první straně a na Googlu o dvě strany níž.</p>
                    <h2>Proč je SEO důležité</h2>
                    <p>Kdo k vám přijde z vyhledávače, něco hledal. Nepřišel omylem z reklamy, kterou chtěl přeskočit. Když napíše „truhlář Brno“ a najde vás, je o krok blíž k tomu, aby vám zavolal.</p>
                    <p>Návštěvnost z vyhledávání se navíc nevypne, když přestanete platit. Reklama skončí s posledním zaplaceným kliknutím. Dobře napsaná stránka může nosit návštěvy roky.</p>
                    <p>Neslíbím vám ale, že SEO přivede zákazníky. Záleží na tom, kolik lidí vaši službu hledá, jakou máte konkurenci a co jim web řekne, když na něj přijdou. A kdo vám slibuje první místo na Googlu, slibuje něco, co nemá v rukou. Pořadí výsledků určuje vyhledávač, nikdo jiný.</p>
                    <h2>Tři části SEO</h2>
                    <p>SEO se obvykle dělí na tři oblasti. Technickou, obsahovou (on-page) a vnější (off-page). Každá odpovídá na jinou otázku.</p>
                    <h2>1. Technické SEO: umí vyhledávač váš web přečíst?</h2>
                    <p>Vyhledávač web nečte očima, ale programem, kterému se říká robot. Technické SEO zajišťuje, že se robot dostane ke všem stránkám a pochopí je.</p>
                    <ul>
                    <li><strong>Rychlost načítání.</strong> Pomalý web opouštějí lidé a vyhledávač to pozná. Jak na tom jste, ukáže zdarma <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a> od Googlu.</li>
                    <li><strong>Telefon.</strong> Google hodnotí web podle toho, jak vypadá a funguje na telefonu. Když se na něm špatně ovládá, ztrácíte i ve výsledcích.</li>
                    <li><strong>Zabezpečení.</strong> Web musí běžet na šifrovaném <code>https</code>.</li>
                    <li><strong>Adresy stránek.</strong> Každá stránka má jednu adresu. Když se adresa změní, stará musí trvale přesměrovat na novou.</li>
                    <li><strong>Nástroje vyhledávačů.</strong> Přihlaste web do <a href="https://search.google.com/search-console/about" target="_blank" rel="noopener">Google Search Console</a> a do <a href="https://reporter.seznam.cz/wm" target="_blank" rel="noopener">Seznam Webmaster</a>. Oba jsou zdarma. Ukážou, jestli vyhledávač hlásí na webu chyby a na jaké dotazy se zobrazujete.</li>
                    </ul>
                    <p>Technickou část musí udělat ten, kdo web staví. Když chybí, ostatní práce na SEO jde do ztracena. U mě je technické SEO v ceně už u nejmenšího webu.</p>
                    <h2>2. On-page SEO: odpovídá stránka na to, co lidé hledají?</h2>
                    <p>On-page je všechno, co je přímo na stránce. Text, nadpisy, titulek, popis ve výsledcích hledání, obrázky a odkazy mezi vašimi stránkami.</p>
                    <ul>
                    <li><strong>Jedna stránka, jedno téma.</strong> Když vás lidé mají najít na „rekonstrukce koupelny“ i na „pokládka dlažby“, potřebujete dvě stránky. Ne jednu, kde je všechno.</li>
                    <li><strong>Slova zákazníků, ne vaše.</strong> Firma napíše „sanitární instalace“, zákazník hledá „výměna záchodu“. Která slova lidé opravdu používají, zjistíte z <a href="/zapisky/jak-na-analyzu-klicovych-slov-krok-za-krokem">analýzy klíčových slov</a>.</li>
                    <li><strong>Téma patří do nadpisu, ale hlavně do textu pod ním.</strong> Vyhledávači nestačí slovo v nadpisu. Chce vidět, že text téma opravdu rozebírá.</li>
                    <li><strong>Titulek a popis stránky.</strong> To je to, co člověk vidí ve výsledcích hledání. Rozhoduje to, jestli klikne na vás, nebo na souseda.</li>
                    <li><strong>Odkazy mezi vašimi stránkami.</strong> Robotovi i čtenáři ukážou, co k sobě patří.</li>
                    </ul>
                    <p>Slovo z oboru v doméně vám pozice nepřinese. Víc o tom píšu v článku o <a href="/zapisky/jak-vybrat-perfektni-domenove-jmeno">výběru doménového jména</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>SEO není trik, jak obelstít vyhledávač, ale práce na tom, aby stránka opravdu odpověděla na to, co člověk hledá.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Off-page SEO: věří vašemu webu ostatní?</h2>
                    <p>Off-page je všechno mimo váš web. Hlavně <strong>zpětné odkazy</strong>, tedy odkazy z jiných webů na ten váš. Vyhledávač je bere jako doporučení. Jeden odkaz z oborového webu nebo z místních novin váží víc než sto odkazů z katalogů, které nikdo nečte.</p>
                    <p>Patří sem i <strong>firemní profily a recenze</strong>. Když podnikáte v konkrétním místě, založte si zdarma <a href="https://www.google.com/intl/cs_cz/business/" target="_blank" rel="noopener">Firemní profil na Googlu</a> a profil na <a href="https://www.firmy.cz/" target="_blank" rel="noopener">Firmy.cz</a>. U dotazů typu „instalatér Kolín“ bývají vidět dřív než samotné weby.</p>
                    <p>Sociální sítě pozice přímo neovlivňují. Pomáhají jen nepřímo. Když se obsah sdílí, uvidí ho víc lidí a někdo z nich na něj může odkázat.</p>
                    <h2>Jak získat zpětné odkazy poctivě</h2>
                    <ul>
                    <li><strong>Napište něco, na co se vyplatí odkázat.</strong> Návod, přehled cen, odpověď na otázku, kterou ve vašem oboru nikdo pořádně nevysvětlil.</li>
                    <li><strong>Partneři a dodavatelé.</strong> Firmy, se kterými spolupracujete, vás můžou uvést na svém webu. Často stačí požádat.</li>
                    <li><strong>Oborové a místní weby.</strong> Svazy, komory, akce, které podporujete, obecní zpravodaj.</li>
                    <li><strong>Článek na cizím webu.</strong> Oborový magazín nebo blog často rád otiskne užitečný text od odborníka i s odkazem na autora.</li>
                    <li><strong>Odkazy konkurence.</strong> Podívejte se, kdo odkazuje na vaše konkurenty. Často najdete katalog nebo svaz, kde chybíte jen vy. Jak se na konkurenci dívat, rozepisuju v článku o <a href="/zapisky/zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">analýze konkurence</a>.</li>
                    </ul>
                    <p>Balíčky typu „100 odkazů za tisícovku“ vám nepomůžou. Vyhledávače takové odkazy poznají a v horším případě za ně web potrestají. Google to má výslovně ve svých <a href="https://developers.google.com/search/docs/essentials/spam-policies" target="_blank" rel="noopener">zásadách proti spamu</a>.</p>
                    <h2>SEO v praxi: kde začít</h2>
                    <ol>
                    <li><strong>Přihlaste web do Search Console a Seznam Webmaster.</strong> Zjistíte, na co se zobrazujete a jestli vyhledávač nehlásí chyby.</li>
                    <li><strong>Zjistěte, co lidé hledají.</strong> Sepište dotazy, jejich hledanost a jak těžké bude se na ně dostat.</li>
                    <li><strong>Každému důležitému dotazu dejte jednu stránku.</strong> A napište ji tak, aby na něj opravdu odpověděla.</li>
                    <li><strong>Přidávejte obsah, který zákazníci hledají.</strong> Otázky, které slyšíte do telefonu pořád dokola, bývají nejlepší témata na články.</li>
                    <li><strong>Sledujte, co se děje.</strong> Pozice se hýbou pomalu. Výsledky uvidíte v řádu měsíců, ne týdnů.</li>
                    </ol>
                    <p>Pozor při předělávání webu. Když se změní adresy stránek a staré se nepřesměrují, přijdete o pozice, které jste roky budovali. Co u toho hlídat, píšu v článku o <a href="/zapisky/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">předělání webu</a>.</p>
                    <h2>Zvládnete to sami?</h2>
                    <p>Základy ano. Přihlásit web do nástrojů vyhledávačů, napsat pořádné titulky a odpovídat v textech na otázky zákazníků zvládne každý, kdo zná svůj obor. Ucelený úvod nabízí sám Google ve své <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide" target="_blank" rel="noopener">příručce SEO pro začátečníky</a>.</p>
                    <p>Analýza klíčových slov, obsahová strategie a sledování výkonu už chtějí čas a placené nástroje. Tohle dělám jako doplňkovou službu. Cenu najdete v <a href="/cenik">ceníku</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 7 — Design, nebo obsah? (slug co-je-dulezitejsi-design-nebo-obsah-webovych-stranek, beze změny)
            // ----------------------------------------------------------------
            7 => [
                'title'       => 'Design, nebo obsah? Co je na webu důležitější',
                'description' => 'Co je na webu důležitější, design, nebo obsah? Proč začínám obsahem, co dělá design a co z toho plyne pro váš nový web.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Na tuhle otázku se obvykle odpovídá „obojí je důležité“. Je to pravda, ale nic vám to neřekne. Moje odpověď je konkrétnější: obsah je první a design mu slouží. Píšu proč a co to znamená, když chystáte nový web.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Krátká odpověď: obsah</h2>
                    <p>Design je způsob, jak obsah ukázat. Bez obsahu nemá co ukazovat. Když nevíte, co má web říct, můžete ho mít sebehezčí a návštěvník stejně nepozná, jestli jste pro něj ti praví.</p>
                    <p>Obsahem nemyslím jen text. Je to všechno, co web sděluje. Co nabízíte, komu, za kolik, jak probíhá spolupráce, ukázky práce, reference, fotky.</p>
                    <h2>Co dělá obsah</h2>
                    <ul>
                    <li><strong>Odpovídá na otázky.</strong> Návštěvník přišel s otázkou. Když na webu najde odpověď, ozve se. Když ne, jde o dům dál.</li>
                    <li><strong>Buduje důvěru.</strong> Konkrétní reference a ukázky práce přesvědčí víc než sebelepší grafika.</li>
                    <li><strong>Přivádí lidi z vyhledávače.</strong> Vyhledávač čte text, ne barvy. Hezký design vás na první stranu Googlu nedostane. Víc o tom v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO a proč je důležité</a>.</li>
                    </ul>
                    <h2>Co dělá design</h2>
                    <p>Design ale není ozdoba, bez které se dá žít. Má svou práci.</p>
                    <ul>
                    <li><strong>První dojem.</strong> Než člověk začne číst, během pár vteřin se rozhodne, jestli web působí důvěryhodně. Zastaralý nebo rozbitý vzhled ho odradí dřív, než se k obsahu dostane.</li>
                    <li><strong>Čitelnost.</strong> Nadpisy, odstavce a mezery rozhodují o tom, jestli se text dá přečíst, nebo jen přeskočit.</li>
                    <li><strong>Vedení.</strong> Dobrý design ukáže, co je důležité a kam kliknout dál.</li>
                    <li><strong>Telefon.</strong> Zhruba polovina lidí se dnes dívá na web z telefonu. Design musí fungovat i tam, ne jen na velkém monitoru.</li>
                    </ul>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Design dělá obsah čitelným, ale neřekne za vás, co nabízíte a proč si vybrat právě vás.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>Proč začínám obsahem</h2>
                    <p>Když stavím nový web, nejdřív si s klientem projdeme, co má web dělat a komu to má říct. Teprve potom řeším, jak bude vypadat. Obráceně to nefunguje. Design nakreslený bez obsahu počítá s nadpisem na dva řádky a odstavcem na tři. Pak přijde skutečný text a nevejde se.</p>
                    <p>Stejné je to u předělávání webu. Nový vzhled se starými texty vypadá nově, ale funguje stejně špatně. Víc o tom v článku o <a href="/zapisky/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">předělání webu</a>.</p>
                    <h2>Co to znamená pro váš nový web</h2>
                    <ul>
                    <li><strong>O obsahu začněte přemýšlet hned.</strong> Co o vás lidé nevědí a měli by? Na co se ptají pořád dokola? To je základ textů. Další otázky najdete v článku <a href="/zapisky/jak-se-pripravit-na-novy-web">Co si připravit, než oslovíte vývojáře webu</a>.</li>
                    <li><strong>Počítejte s texty a fotkami v rozpočtu.</strong> Když je nemáte, musí se vyrobit. I to rozebírám v článku <a href="/zapisky/kolik-stoji-webove-stranky">Kolik stojí web na míru</a>.</li>
                    <li><strong>Design vybírejte podle obsahu, ne naopak.</strong> Web, který se vám líbí u cizí firmy, byl navržený pro její texty. Ne pro ty vaše.</li>
                    </ul>
                    <p>Hezký web, který nic neřekne, nikomu nepomůže. Užitečný web, který vypadá staře, přichází o lidi dřív, než si ho přečtou. Potřebujete obojí, jen ve správném pořadí. Co dalšího rozhoduje o tom, jestli web funguje, píšu v článku <a href="/zapisky/jak-vytvorit-uspesnou-webovou-stranku">Jak vytvořit úspěšnou webovou stránku</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 9 — Analýza klíčových slov (slug jak-na-analyzu-klicovych-slov-krok-za-krokem, beze změny)
            // ----------------------------------------------------------------
            9 => [
                'title'       => 'Jak udělat analýzu klíčových slov krok za krokem',
                'description' => 'Jak udělat analýzu klíčových slov: kde sbírat dotazy, které nástroje jsou zdarma, jak slova roztřídit a jak z nich poskládat stránky webu.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Analýza klíčových slov je seznam toho, co lidé píšou do vyhledávače, když hledají to, co nabízíte. Bez ní vzniká web podle toho, jak o oboru mluvíte vy, ne vaši zákazníci. Popisuju postup, který zvládnete sami s obyčejnou tabulkou a nástroji zdarma.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Co jsou klíčová slova a k čemu je analýza</h2>
                    <p>Klíčová slova jsou slova a fráze, které lidé zadávají do Googlu nebo Seznamu. „Kuchyně na míru“, „kuchyně na míru cena“, „kuchyňská linka Olomouc“. Každá z nich prozrazuje, co člověk chce a jak blízko je k nákupu.</p>
                    <p>Analýza vám řekne tři věci:</p>
                    <ul>
                    <li><strong>Jak o vašem oboru mluví zákazníci.</strong> Často jinak než vy.</li>
                    <li><strong>Co hledají nejčastěji.</strong> A co naopak nehledá skoro nikdo, i když vám to přijde důležité.</li>
                    <li><strong>Jaké stránky má mít váš web.</strong> Každá skupina dotazů potřebuje svou stránku.</li>
                    </ul>
                    <p>Na konci máte tabulku. Podle ní se rozhoduje, jaké stránky na webu budou, jak se budou jmenovat a o čem budou články. Proto se analýza vyplatí udělat před stavbou nového webu, ne až po ní.</p>
                    <p>Celý postup ukážu na jednom příkladu, na výrobci kuchyní na míru.</p>
                    <h2>1. Sesbírejte dotazy</h2>
                    <p>Sepište všechno, co by zákazník mohl hledat. Zatím nic netřiďte a nic nevyhazujte.</p>
                    <ul>
                    <li><strong>Vaše služby a výrobky.</strong> Tak, jak jim říkáte vy, i tak, jak jim říkají zákazníci.</li>
                    <li><strong>Otázky, které slyšíte do telefonu.</strong> „Kolik to stojí?“, „Jak dlouho to trvá?“, „Uděláte to i do paneláku?“</li>
                    <li><strong>Weby konkurence.</strong> Jak pojmenovávají služby a nadpisy stránek.</li>
                    <li><strong>Diskuze a sociální sítě</strong>, kde se vaši zákazníci ptají. Hlídejte si stáří. Diskuze stará deset let vám o dnešku moc neřekne.</li>
                    </ul>
                    <p>Výstupem je obyčejný seznam, co řádek, to jedna fráze. Stačí na to jakákoli tabulka, já používám Excel. Kolik frází to bude, záleží na oboru. Obecný obor jako kosmetika jich dá stovky, firma s jedním výrobkem pár desítek.</p>
                    <h2>2. Rozšiřte seznam v nástrojích a doplňte hledanost</h2>
                    <p>Teď seznam rozšíříte o fráze, na které jste sami nepřišli, a ke každé doplníte <strong>hledanost</strong>. Tedy kolikrát za měsíc ji lidé zhruba hledají.</p>
                    <p><strong>Plánovač klíčových slov od Googlu.</strong> Je součástí <a href="https://ads.google.com/intl/cs_cz/home/tools/keyword-planner/" target="_blank" rel="noopener">Google Ads</a> a je zdarma, jen potřebujete účet. Z vašich frází navrhne další a ukáže hledanost na Googlu. Když v Google Ads zrovna neplatíte reklamu, uvidíte hledanost jen v rozpětí, třeba 100 až 1 000. Na první orientaci to stačí.</p>
                    <p><strong>Návrh klíčových slov v Skliku.</strong> Totéž pro Seznam. <a href="https://napoveda.sklik.cz/cileni/klicova-slova/navrh-klicovych-slov/" target="_blank" rel="noopener">Nástroj</a> je zdarma s účtem na Seznamu. U každé fráze ukáže i vývoj hledanosti po měsících. To se hodí u sezónních věcí. Hledají lidé letní dovolenou nejvíc v dubnu, nebo už v lednu? Podle toho víte, kdy má být článek hotový.</p>
                    <p><strong>Našeptávač.</strong> Začněte psát do vyhledávače a on sám nabídne, jak lidé větu dokončují. Napíšete „kuchyně na míru“ a uvidíte, co k tomu lidé přidávají. Ručně je to pomalé, ale hned při tom vyřazujete, co k vám nepatří. Stovky návrhů najednou umí vytáhnout nástroje jako <a href="https://www.semor.cz/administrace/nastroje/nks" target="_blank" rel="noopener">SEMOR</a>.</p>
                    <p>V tabulce si udělejte zvlášť sloupec pro hledanost na Googlu, zvlášť pro Seznam a třetí pro jejich součet. U některých frází hledanost nenajdete. Když vám dávají smysl, nechte si je, jen si je označte.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Web pište podle toho, co do vyhledávače zadávají vaši zákazníci, ne podle toho, jak o oboru mluvíte vy.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Vyčistěte a roztřiďte</h2>
                    <p>Seznam je teď dlouhý a nepořádný. Nejdřív z něj vyhoďte duplicity, překlepy a všechno, co s vámi nesouvisí. Výrobce kuchyní na míru nepotřebuje „návod na montáž kuchyně IKEA“.</p>
                    <p>Pak fráze roztřiďte podle toho, <strong>co člověk chce</strong>:</p>
                    <ul>
                    <li><strong>Informační dotazy.</strong> Chce se něco dozvědět. „Jak vybrat pracovní desku“. Na ty odpovídají články.</li>
                    <li><strong>Nákupní dotazy.</strong> Chce koupit nebo objednat. „Kuchyně na míru cena“. Na ty odpovídají stránky služeb a výrobků.</li>
                    <li><strong>Místní dotazy.</strong> Hledá někoho blízko. „Kuchyně na míru Olomouc“. Na ty odpovídá stránka s kontaktem a firemní profil na Googlu a na Firmy.cz.</li>
                    </ul>
                    <p>Nakonec seskupte fráze, které znamenají totéž. „Kuchyně na míru cena“ a „kolik stojí kuchyně na míru“ jsou jedna otázka. Patří na jednu stránku.</p>
                    <h2>4. Určete priority</h2>
                    <p>Ne každé slovo stojí za práci. U každé skupiny si zapište:</p>
                    <ul>
                    <li><strong>Hledanost.</strong> Kolik lidí to hledá.</li>
                    <li><strong>Souvislost s tím, co prodáváte.</strong> Sto lidí, kteří hledají přesně vaši službu, má větší cenu než deset tisíc lidí, kteří hledají něco podobného.</li>
                    <li><strong>Konkurenci.</strong> Zadejte dotaz do Googlu i Seznamu a podívejte se, kdo je na první straně. Velké portály a e-shopy se přeskakují těžko. Slabé nebo zastaralé stránky jsou vaše šance.</li>
                    <li><strong>Sezónu.</strong> Kdy v roce se dotaz hledá nejvíc.</li>
                    </ul>
                    <p>Prioritu stačí zapsat jako vysokou, střední a nízkou. Nejvíc se vyplatí dotazy, které souvisí přímo s tím, co prodáváte, a přitom na ně zatím nikdo nemá pořádnou stránku.</p>
                    <h2>5. Přiřaďte skupiny ke stránkám</h2>
                    <p>Tenhle krok je nejdůležitější. Každé skupině dotazů přiřaďte jednu stránku webu. Buď takovou, která už existuje, nebo takovou, která vznikne.</p>
                    <p>Když na jednu skupinu vyjdou dvě stránky, budou si konkurovat a vysoko se nedostane ani jedna. Když na skupinu nevyjde žádná, víte, co na webu chybí. Z téhle tabulky se pak skládá struktura nového webu. Patří k věcem, které se vyplatí mít po ruce, než začnete psát <a href="/zapisky/jak-se-pripravit-na-novy-web">zadání pro vývojáře</a>.</p>
                    <h2>Co s analýzou dál</h2>
                    <ul>
                    <li><strong>Upravte stávající stránky.</strong> Titulek, nadpis a text podle slov, která lidé opravdu používají.</li>
                    <li><strong>Doplňte chybějící stránky a články.</strong> Nejdřív ty s vysokou prioritou.</li>
                    <li><strong>Sledujte výsledky.</strong> V Google Search Console a v Seznam Webmaster uvidíte, na jaké dotazy se web zobrazuje a kolik lidí z nich klikne.</li>
                    <li><strong>Jednou za čas analýzu zopakujte.</strong> Mění se to, co lidé hledají, i konkurence.</li>
                    </ul>
                    <p>Klíčová slova jsou jen jedna část SEO. Co dalšího rozhoduje o tom, jestli vás vyhledávač ukáže, vysvětluju v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO a proč je důležité</a>. Kdo jsou ti, se kterými se na první straně přetahujete, rozebírám v článku o <a href="/zapisky/zakladni-krok-pro-uspesny-webdesign-analyza-konkurence">analýze konkurence</a>.</p>
                    <p>Analýzu klíčových slov dělám i jako součást SEO služby. Kolik stojí, najdete v <a href="/cenik">ceníku</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 11 — Jak vytvořit úspěšnou webovou stránku (slug jak-vytvorit-uspesnou-webovou-stranku, beze změny; sloučený článek 8)
            // ----------------------------------------------------------------
            11 => [
                'title'       => 'Jak vytvořit úspěšnou webovou stránku: co opravdu rozhoduje',
                'description' => 'Jak vytvořit úspěšný web: jasné sdělení, důvěra, jedna výzva k akci, telefon, vyhledávače i údržba. Deset věcí, které rozhodují, jestli web funguje.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Úspěšný web není ten nejhezčí. Je to web, na kterém člověk rychle pochopí, co děláte, uvěří vám a ví, jak se ozvat. Neslíbím vám, kolik zakázek web přinese. Sepsal jsem ale deset věcí, které na webu musí fungovat, aby vůbec měl šanci.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Nejdřív si řekněte, co má web dělat</h2>
                    <p>Úspěch webu se nedá měřit, dokud nevíte, co od něj chcete. Má přivádět poptávky? Prodávat? Ušetřit vám telefonování, protože si lidé odpovědi přečtou sami? Nebo stačí, aby vás při výběru dodavatele nikdo nevyřadil? Každý z těch cílů vede k jinému webu. Společné mají jedno: web má převádět návštěvníky na zákazníky.</p>
                    <p>Stejně důležité je, pro koho web je. Na obě otázky se ptám každého klienta jako první.</p>
                    <h2>1. Do deseti vteřin musí být jasné, co děláte</h2>
                    <p>Když někdo přijde na váš web poprvé, během pár vteřin se rozhodne, jestli zůstane. Nadpis „Vítejte na stránkách firmy XY“ mu neřekne nic. Nadpis „Stavíme dřevostavby na Vysočině, na klíč do osmi měsíců“ mu řekne, jestli je na správném místě.</p>
                    <p>Konkrétní věta je vždycky lepší než obecná.</p>
                    <h2>2. Pište slovy zákazníka</h2>
                    <p>Web napsaný oborovým jazykem zákazník nepochopí a vyhledávač neukáže. Firma napíše „komplexní řešení vytápění“, zákazník hledá „výměna kotle“. Jak zjistit, jakými slovy lidé váš obor hledají, popisuju v článku <a href="/zapisky/jak-na-analyzu-klicovych-slov-krok-za-krokem">Jak udělat analýzu klíčových slov</a>.</p>
                    <p>Pište krátce a věcně. Odstavec na tři věty, nadpis, který říká, co je pod ním. Lidé na webu nečtou, ale přelétají očima. Zastaví se u toho, co je zajímá.</p>
                    <h2>3. Jedna hlavní výzva na stránku</h2>
                    <p>Každá stránka má mít jednu hlavní věc, kterou po návštěvníkovi chcete. Zavolat, vyplnit formulář, objednat. Když mu nabídnete čtyři tlačítka se stejnou vahou, často nevybere žádné.</p>
                    <p>Formulář chtějte co nejkratší. Jméno, kontakt a zpráva většinou stačí. Každé pole navíc je důvod ho nevyplnit. U e-shopu to platí pro pokladnu dvojnásob.</p>
                    <h2>4. Důvěra dřív než otázka na peníze</h2>
                    <p>Člověk, který vás nezná, potřebuje důvod vám věřit. Nejvíc pomáhá:</p>
                    <ul>
                    <li><strong>reference se jménem a firmou</strong>, ne anonymní „spokojený zákazník“,</li>
                    <li><strong>ukázky práce</strong> s popisem, co jste řešili a jak,</li>
                    <li><strong>vaše tvář a jméno</strong>, aby bylo jasné, s kým bude jednat,</li>
                    <li><strong>recenze mimo váš web</strong>, na Googlu nebo na Firmy.cz.</li>
                    </ul>
                    <p>Obecné věty jako „jsme spolehliví a profesionální“ nepřesvědčí nikoho. Napíše je každý.</p>
                    <h2>5. Design, který obsah nese</h2>
                    <p>Design má dvě úlohy. Udělat dobrý první dojem a usnadnit čtení. Čitelné písmo, dost kontrastu, dost prostoru kolem textu, kvalitní vlastní fotky místo fotek z fotobanky. A všude stejný styl, aby web působil jako jeden celek.</p>
                    <p>Na otázku, jestli je důležitější design, nebo obsah, jsem odpověděl v samostatném článku <a href="/zapisky/co-je-dulezitejsi-design-nebo-obsah-webovych-stranek">Design, nebo obsah?</a></p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Úspěšný web není ten nejhezčí, ale ten, na kterém člověk rychle pochopí, co děláte, a ví, jak se vám ozvat.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>6. Navigace, ve které se nikdo neztratí</h2>
                    <ul>
                    <li><strong>Málo položek v menu</strong> a pojmenovaných tak, jak jim rozumí zákazník. „Ceník“, ne „Investice“.</li>
                    <li><strong>Kontakt z každé stránky.</strong> Nikdo ho nemá hledat.</li>
                    <li><strong>Všude stejné ovládání.</strong> Menu a tlačítka na každé stránce na stejném místě.</li>
                    <li><strong>Odezva na každou akci.</strong> Po odeslání formuláře musí být jasné, že se odeslal a co bude dál.</li>
                    </ul>
                    <p>Vyhledávání na webu potřebují jen velké weby a e-shopy. Menší web má být tak přehledný, aby ho nepotřeboval.</p>
                    <h2>7. Telefon a rychlost</h2>
                    <p>Zhruba polovina lidí se dnes na web dívá z telefonu. Nestačí, že se na něm web zobrazí. Tlačítka musí jít trefit palcem, formulář musí jít vyplnit bez zvětšování a stránka se musí načíst rychle i na mobilních datech. Jak na tom váš web je, ukáže zdarma <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a>.</p>
                    <h2>8. Aby vás našli</h2>
                    <p>Web, který nikdo nenajde, nemůže být úspěšný. Základ je technicky čistý web, na každé téma jedna stránka a texty, které odpovídají na to, co lidé hledají. Co všechno k tomu patří, vysvětluju v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO a proč je důležité</a>.</p>
                    <h2>9. Bezpečnost</h2>
                    <ul>
                    <li><strong>Šifrované spojení <code>https</code>.</strong> Bez něj prohlížeč web označí jako nezabezpečený.</li>
                    <li><strong>Aktualizace.</strong> Weby poskládané z hotových systémů a cizích doplňků se musí pravidelně aktualizovat, jinak se stanou snadným cílem. Čím méně cizích doplňků, tím méně starostí.</li>
                    <li><strong>Silná hesla</strong> a přístup do administrace jen pro ty, kdo ho opravdu potřebují.</li>
                    <li><strong>Zálohy.</strong> Když se něco pokazí, musí jít web rychle obnovit.</li>
                    </ul>
                    <h2>10. Po spuštění web neopouštějte</h2>
                    <p>Spuštěním práce nekončí. Měřte, odkud lidé přicházejí, na kterých stránkách odcházejí a kolik z nich se ozve. Stačí na to Google Analytics nebo jednodušší nástroj jako Plausible. Bez dat se web vylepšuje naslepo.</p>
                    <p>A obsah udržujte aktuální. Staré ceny, zrušené služby nebo poslední novinka z doby před třemi lety působí, jako by firma skončila. Kdy už údržba nestačí a je čas web předělat, píšu v článku o <a href="/zapisky/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">předělání webu</a>.</p>
                    <h2>Kde začít</h2>
                    <p>Většinu věcí z tohohle seznamu nevyřeší vývojář sám. Potřebuje od vás vědět, co má web dělat, pro koho je a co o vás lidé nevědí. Otázky, na které je dobré znát odpověď předem, jsem sepsal v článku <a href="/zapisky/jak-se-pripravit-na-novy-web">Co si připravit, než oslovíte vývojáře webu</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 12 — Analýza konkurence (slug zakladni-krok-pro-uspesny-webdesign-analyza-konkurence, beze změny)
            // ----------------------------------------------------------------
            12 => [
                'title'       => 'Analýza konkurence webu: jak na ni před novým webem',
                'description' => 'Jak udělat analýzu konkurence webu: koho sledovat, co si na jejich webech zapsat a jak z toho poznat, čím se váš nový web má odlišit.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Zákazník vás na internetu nevidí samotné. Vidí vás vedle tří dalších firem, které dělají totéž, a vybírá mezi vámi. Analýza konkurence je pořádný pohled na ty tři firmy dřív, než začnete stavět nový web. Píšu, na co se dívat, jak si to zapsat a co s tím potom udělat.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Proč dělat analýzu konkurence</h2>
                    <p>Bez ní vzniká web podle vkusu majitele a podle toho, co se mu líbilo jinde. S ní vzniká web, který v očích zákazníka obstojí vedle těch, se kterými vás srovnává. Analýza vám ukáže:</p>
                    <ul>
                    <li><strong>Co zákazník v oboru čeká.</strong> Když ceník na webu mají všichni tři konkurenti, jeho absence u vás bude vidět.</li>
                    <li><strong>Kde je mezera.</strong> Co nikdo pořádně nevysvětluje, na co se lidé ptají a nikde nenajdou odpověď.</li>
                    <li><strong>Jaké chyby nedělat.</strong> Nefunkční formulář, web nepoužitelný na telefonu, nejasné, co firma vlastně dělá.</li>
                    <li><strong>Na co se ve vyhledávači dostanete a na co ne.</strong> Když na váš hlavní dotaz obsadily první stranu velké portály, potřebujete jiný přístup než souboj o stejné slovo.</li>
                    </ul>
                    <p>Analýza neslouží ke kopírování. Když váš web vypadá a mluví stejně jako tři další, zákazník nemá podle čeho vybrat. Hledáte, čím se odlišit.</p>
                    <h2>Koho sledovat</h2>
                    <p>Stačí tři až pět firem. Vybírejte ze dvou zdrojů:</p>
                    <ul>
                    <li><strong>Kdo je ve vyhledávači.</strong> Zadejte do Googlu i Seznamu dotazy, na které chcete být vidět. Třeba „kuchyně na míru Olomouc“. Kdo je na první straně, je vaše konkurence na internetu, i když o něm třeba nevíte.</li>
                    <li><strong>S kým vás srovnávají zákazníci.</strong> Koho zmiňují, když se rozhodují? Komu jste zakázku prohráli naposledy? To je konkurence v obchodě.</li>
                    </ul>
                    <p>Obě skupiny se často liší. Obě jsou důležité.</p>
                    <h2>Co si na jejich webech zapsat</h2>
                    <p><strong>Nabídka a jak ji popisují.</strong> Co přesně nabízejí, jakými slovy, a jestli z úvodní stránky do deseti vteřin pochopíte, co dělají a pro koho.</p>
                    <p><strong>Co zákazníkovi říkají a co ne.</strong> Cena nebo aspoň cenové rozpětí, průběh spolupráce, termíny, záruky. Co u nich chybí, je místo, kde můžete být lepší.</p>
                    <p><strong>Důvěra.</strong> Reference se jménem, ukázky práce, fotky lidí, recenze. Jsou konkrétní, nebo obecné?</p>
                    <p><strong>Struktura webu.</strong> Jaké stránky mají, jak se jmenují položky menu a kolik kliknutí trvá, než najdete kontakt.</p>
                    <p><strong>Výzva k akci.</strong> Co po vás web chce? Zavolat, vyplnit formulář, stáhnout ceník? A jak snadné to je?</p>
                    <p><strong>Telefon a rychlost.</strong> Projděte si jejich weby na telefonu. Rychlost vám změří zdarma <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a>.</p>
                    <p><strong>Na co se zobrazují ve vyhledávači.</strong> Na jaké dotazy jsou vidět a jaké stránky a články na ně mají. Přesná čísla dávají placené nástroje. Pro začátek stačí ručně zkusit dotazy z vaší <a href="/zapisky/jak-na-analyzu-klicovych-slov-krok-za-krokem">analýzy klíčových slov</a>.</p>
                    <p><strong>Recenze mimo jejich web.</strong> Na webu si každý vybere ty nejlepší. Zajímavější je, co o nich lidé píšou na Googlu, na Firmy.cz nebo na sociálních sítích. Co chválí a na co si stěžují.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Analýza konkurence nehledá, co opsat, ale čím se odlišit.</p></blockquote>
                    HTML,
                'img_mid'     => 'analyza_konkurence.webp',
                'img_mid_alt' => 'Grafy a tabulky rozložené na stole',
                'content_2'   => <<<'HTML'
                    <h2>Jak si výsledky zapsat</h2>
                    <p>Nejlíp funguje jedna tabulka. Konkurenti do sloupců, sledované body do řádků. K tomu na konci tři krátké seznamy:</p>
                    <ul>
                    <li><strong>Co mají všichni.</strong> To je standard oboru. Bez toho se neobejdete ani vy.</li>
                    <li><strong>Co dělá někdo dobře.</strong> Inspirace, ne předloha.</li>
                    <li><strong>Co nemá nikdo.</strong> Tady je vaše příležitost.</li>
                    </ul>
                    <p>Víc nepotřebujete. Nejde o sto stran reportu, ale o jednu stránku, podle které se dá rozhodovat.</p>
                    <h2>Jak analýzu využít při návrhu webu</h2>
                    <ul>
                    <li><strong>Obsah.</strong> Na otázky, na které konkurence neodpovídá, odpovězte vy. Ceník, průběh spolupráce, časté otázky.</li>
                    <li><strong>Struktura.</strong> Stránky, které zákazník v oboru čeká, mějte i vy. A navíc ty, které vás odliší.</li>
                    <li><strong>Sdělení.</strong> Když všichni píšou „kvalita a spolehlivost“, napište něco, co si zákazník může ověřit.</li>
                    <li><strong>Vyhledávání.</strong> Dotazy, na které má konkurence slabé nebo zastaralé stránky, jsou ty, kde máte šanci. Proč na tom záleží, vysvětluju v článku <a href="/zapisky/co-je-seo-a-proc-je-tak-dulezite">Co je SEO a proč je důležité</a>.</li>
                    <li><strong>Funkce.</strong> Kalkulačka, rezervace, dokumenty ke stažení. Když je konkurence nemá a zákazníkovi by pomohly, máte náskok.</li>
                    </ul>
                    <p>Hotovou analýzu přiložte k zadání. Vývojáři ušetří spoustu otázek a vy dostanete přesnější nabídku. Co dalšího do zadání patří, najdete v článku <a href="/zapisky/jak-se-pripravit-na-novy-web">Co si připravit, než oslovíte vývojáře webu</a>.</p>
                    <p>Stejně se vyplatí podívat na konkurenci před předěláním webu. Když weby lidí, se kterými soutěžíte, vypadají o třídu líp, je to jeden z dobrých důvodů web předělat. Kdy to smysl má a kdy ne, rozebírám v článku o <a href="/zapisky/redesign-webovych-stranek-duvody-signaly-a-jak-na-to">předělání webu</a>.</p>
                    <h2>Jak často analýzu opakovat</h2>
                    <p>Trh se mění. Přijdou noví konkurenti, staří si předělají web nebo začnou psát články. Stačí se na to jednou za rok podívat znovu a porovnat s minulou tabulkou.</p>
                    <p>A když teprve vybíráte název firmy nebo doménu, udělejte si rychlou verzi analýzy už teď. Nechcete, aby si vás lidé pletli s někým, kdo dělá totéž. Víc o tom píšu v článku o <a href="/zapisky/jak-vybrat-perfektni-domenove-jmeno">výběru doménového jména</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

        ];
    }
}

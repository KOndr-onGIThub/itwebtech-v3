<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 (audit OND-254) — vlna 2 textů případovek podle redlinu OND-261.
 *
 * Texty detailů projektů žijí v `portfolio_project_translations`, ne v lang
 * souborech, a `PortfolioSeeder` se po prvním naplnění DB přeskakuje — úprava
 * `docs/portfolio-data.yaml` se proto na produkci sama neprojeví. Data migrace
 * je jediná cesta ven (precedent 2026_09_22_110000_ond256_opravy_textu_pripadovek).
 * YAML je opravený zároveň, aby čerstvý seed dával stejný výsledek.
 *
 * Řazení: **až za** vlnou 1 (2026_09_22_*), a to schválně. Redline bral stará
 * znění z produkce, na které vlna 1 v době měření ještě nebyla. Jediné znění,
 * které vlna 1 posunula, je `josefopa.challenge` („visačku" → „vizitku") —
 * tady se proto hledá už tvar po vlně 1. Ověřeno proti produkci po nasazení
 * vlny 1: 49 z 50 starých znění sedí doslovně, to padesáté je právě josefopa.
 *
 * Náhrada je podmíněná a slovní: sáhne jen na řádek, kde původní tvar pořád
 * stojí. Druhý běh je no-op a ruční úpravy z Filamentu nepřepíše.
 *
 * `duration` se nastavuje absolutně (je to celá hodnota sloupce, ne kus věty),
 * ale jen když v DB pořád stojí očekávaná stará hodnota. Deset projektů řádek
 * ztrácí úplně (`null`) — podle X2 v redlinu: datum ani hodnotící přívlastek
 * není doba realizace a přesný řád nemáme z čeho doložit.
 *
 * Uvozovky (Q1) řeší samostatná migrace 2026_09_23_100100, protože je to
 * plošné pravidlo přes všechna pole, ne seznam konkrétních vět.
 */
return new class extends Migration
{
    /** [slug, locale, field, from, to] */
    private const FIXES = [
        // X1-1
        ['pitarena-eshop', 'cs', 'title',
            'PitAréna — e-shop',
            'PitArena — e-shop'],
        // X1-2
        ['pitarena-eshop', 'cs', 'meta_title',
            'PitAréna — e-shop s motorkami YCF a náhradními díly',
            'PitArena — e-shop s motorkami YCF a náhradními díly'],
        // hcms-1
        ['hcms', 'cs', 'solution',
            'Po analýze procesů na hale jsem navrhl doménový model, use case mapu a postavil webovou aplikaci s tabletovým rozhraním pro operátory a desktopovým pohledem pro vedoucí.',
            'Po analýze procesů přímo na hale jsem zmapoval, co všechno se při výpadku dílu děje a kdo v tom hraje jakou roli, a z toho postavil webovou aplikaci — tabletové rozhraní pro operátory, desktopový přehled pro vedoucí.'],
        // barana-1
        ['barana', 'cs', 'subtitle',
            'Premium web pro bioklimatické pergoly připravený na kampaně',
            'Prémiový web pro bioklimatické pergoly připravený na kampaně'],
        // barana-2
        ['barana', 'cs', 'meta_title',
            'BARANA — premium web pro pergoly, ploty a brány',
            'BARANA — prémiový web pro pergoly, ploty a brány'],
        // barana-3
        ['barana', 'cs', 'meta_description',
            'Případová studie: premium prezentace BARANA s landingem pro Meta Ads a Google Ads. Postaveno za 5 týdnů, ready pro placené kampaně.',
            'Případová studie: prémiová prezentace BARANA se samostatnou stránkou pro Meta Ads a Google Ads. Postaveno za 5 týdnů.'],
        // ni-1
        ['nove-interiery', 'cs', 'solution',
            'Texty mluví bez jargonu o tom, co klient od spolupráce dostane.',
            'Texty bez žargonu říkají, co klient od spolupráce dostane.'],
        // cyklo-1
        ['cyklocentrum', 'cs', 'result',
            'Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat".',
            'Klient potvrzuje, že má konečně prezentaci, za kterou se nemusí stydět.'],
        // real-1
        ['realitacky-v-akci', 'cs', 'challenge',
            'Měla působit profesionálně, ale lidsky, a měla z webu jít poznat, jak pracuje.',
            'Web měl působit profesionálně, ale lidsky, a mělo z něj jít poznat, jak makléřka pracuje.'],
        // vp-1
        ['vp-industry', 'cs', 'result',
            'Web je technicky připravený na růst — chybí jen aktivní obsahová a kampaňová práce, kterou klient zatím odložil. Potenciál pro organický růst je v infrastruktuře nachystaný a čeká na další fázi. Tohle je férový výsledek: dělám to, co bylo zadáno, a otevřeně říkám, kde leží další kus práce.',
            'Klient dostal web, který je technicky hotový: produktové stránky s parametry a videi, správně nastavené SEO, napojenou analytiku a strukturu, která unese další články bez přestavby. Obsahovou a kampaňovou fázi zatím odložil — až ji zapne, nic se nebude muset předělávat.'],
        // sz-1
        ['strechy-zajic', 'cs', 'solution',
            'Web funguje stejně dobře na desktopu i na chytrém telefonu, kterým si Honzové ze stavby běžně ověřují, „ten chlap jak se jmenuje".',
            'Web funguje stejně dobře na počítači i na telefonu — a právě z telefonu si lidé firmu nejčastěji vyhledají.'],
        // jo-1
        ['josefopa', 'cs', 'challenge',
            'Klient potřeboval vizitku stavební firmy, která bude působit jako solidní lokální partner v Německu.',
            'Klient potřeboval, aby jeho stavební firma v Německu působila jako solidní lokální partner.'],
        // jo-2
        ['josefopa', 'cs', 'subtitle',
            'Vícejazyčná prezentace stavební firmy s vlastním brandem',
            'Dvojjazyčná prezentace stavební firmy s vlastním brandem'],
        // jo-3
        ['josefopa', 'cs', 'challenge',
            'Vícejazyčnost byla zásadní — komunikace s klienty probíhá převážně německy, ale poptávky chodí i v angličtině.',
            'Dvojjazyčnost byla zásadní — komunikace s klienty probíhá převážně německy, ale poptávky chodí i v angličtině.'],
        // jo-4
        ['josefopa', 'cs', 'solution',
            'Postavil jsem responzivní vícejazyčný web, navrhl výrazné a hravé logo',
            'Postavil jsem responzivní dvojjazyčný web v němčině a angličtině, navrhl výrazné a hravé logo'],
        // jo-5
        ['josefopa', 'cs', 'meta_title',
            'Josef Opa — vícejazyčný web stavební firmy v Německu',
            'Josef Opa — dvojjazyčný web stavební firmy v Německu'],
        // vs-1
        ['vanspedition', 'cs', 'challenge',
            'Klienti potřebují rychle vědět, co umíte, kdo to dělá a jak se ozvat.',
            'Zákazníci spedice potřebují rychle vědět, co firma umí, kdo za ní stojí a jak se ozvat.'],
        // yolk-1
        ['yolk', 'cs', 'title',
            'YOLK — vývoj na klinikových portálech',
            'YOLK — vývoj na webech zdravotnických klinik'],
        // yolk-2
        ['yolk', 'cs', 'challenge',
            'Agentura má klienty s rozsáhlými portály, kde každý drobný chybný kus tlačí dolů kvalitu zákaznické zkušenosti pacientů. Bylo potřeba spolehlivě udržovat běh, dělat sezónní změny v akcích a dotazníkách a doplňovat custom funkce, které WordPress sám neumí.',
            'Agentura má klienty s rozsáhlými weby, na kterých si pacienti hledají péči — každá drobná chyba tam shazuje dojem z celé kliniky. Bylo potřeba weby spolehlivě držet v chodu, dělat sezónní změny v akcích a dotaznících a doplňovat funkce, které WordPress sám neumí.'],
        // yolk-3
        ['yolk', 'cs', 'solution',
            'Pracuji jako externí vývojář na konkrétní zakázky — sezónní úpravy, opravy chyb, custom PHP/JS rozšíření a úpravy formulářů. Komunikace probíhá rovnou s týmem agentury, výstup je pro klienty agentury prakticky neviditelný v tom dobrém smyslu — všechno jede.',
            'Pracuji jako externí vývojář na konkrétní zakázky — sezónní úpravy, opravy chyb, rozšíření v PHP a JavaScriptu a úpravy formulářů. WordPress na nových webech nestavím, ale když na něm klient už stojí, umím ho opravit a udržet v chodu. Komunikace jde rovnou s týmem agentury a pacient z mojí práce nepozná nic — a přesně o to jde.'],
        // pick-1
        ['picker', 'cs', 'challenge',
            'Klasické „papír s checklistem" v Toyotě tempo neudržel',
            'Klasický papír s checklistem v Toyotě tempo neudržel'],
        // pick-2
        ['picker', 'cs', 'solution',
            'monitorovacím dashboardem pro vedoucí a admin sekcí pro konfiguraci procesů',
            'přehledovou obrazovkou pro vedoucího a nastavením, ve kterém si firma sama upraví průběh procesů'],
        // choc-1
        ['choccoboard', 'cs', 'subtitle',
            'BI dashboard, který nahradil 30 minut ruční práce denně',
            'Přehled prodejních čísel, který nahradil 30 minut ruční práce denně'],
        // choc-2
        ['choccoboard', 'cs', 'description',
            'Choccoboard je webová aplikace pro vedení firmy, která zpřístupňuje prodejní KPI a klíčové ukazatele v reálném čase z jakéhokoli zařízení.',
            'Choccoboard je webová aplikace pro vedení firmy: ukazuje aktuální prodejní čísla a další klíčové ukazatele z jakéhokoli zařízení.'],
        // choc-3
        ['choccoboard', 'cs', 'solution',
            'jednou přehledovou obrazovkou s KPI a flexibilním filtrováním',
            'jednou přehledovou obrazovkou s hlavními čísly a filtrováním'],
        // choc-4
        ['choccoboard', 'cs', 'meta_description',
            'Případová studie: webový BI dashboard, který eliminoval 30 minut ručního stahování dat denně.',
            'Případová studie: webový přehled prodejních čísel, který ušetřil 30 minut ručního stahování dat denně.'],
        // frl-1
        ['frl-creator', 'cs', 'subtitle',
            'Generátor regálových štítků z proměnných dat',
            'Štítky do regálů, které se samy poskládají z aktuálních dat'],
        // frl-2
        ['frl-creator', 'cs', 'solution',
            'Při testování se rychle odstraňovaly nálezy.',
            'Chyby, které se při testování objevily, jsem opravoval průběžně.'],
        // frl-3
        ['frl-creator', 'cs', 'result',
            'důslednou analýzu procesů a rychlé řešení nálezů během testování (5/5 hvězd)',
            'důslednou analýzu procesů a rychlé opravy chyb během testování (5/5 hvězd)'],
        // frl-4
        ['frl-creator', 'cs', 'meta_description',
            'Analýza procesů, rychlé řešení nálezů, 5/5 hvězd.',
            'Analýza procesů, rychlé opravy během testování, 5/5 hvězd.'],
        // ex-2
        ['excel-tools', 'cs', 'solution',
            'Šest interních aplikací: FLR Backup 1 (CSV generátor podle parametrů), FLR Backup 2 (krok-za-krokem záloha pro dodávku dílů), Address Data (sloučení dat z více zdrojů), Kanban (generátor PDF kanbanů), Kombinace procesů (vizualizace plánovaných dodávek napříč procesy) a Report (Excel→PDF export s automatickým e-mailem a maskováním dat).',
            'Šest nástrojů šitých na konkrétní úkoly. Tři z nich dělají většinu práce: záložní postup pro dodávku dílů na linku, který člověka provede krok za krokem; generátor štítků, který je rovnou vysází do PDF k tisku; a rozesílač reportů, který si data sám vytáhne, skryje citlivé údaje a pošle je e-mailem. Zbylé tři připravují podklady a plánování dodávek.'],
        // logo-1
        ['logo-realitacky', 'cs', 'result',
            'Bylo zdarma součástí webového balíčku, takže celý brand má jednu autorskou ruku a tedy konzistentní pocit.',
            'Vzniklo jako součást webového balíčku, takže celý brand má jednu autorskou ruku a drží pohromadě.'],
        // vid-1
        ['video-pitbike-akademie', 'cs', 'result',
            'Klient má video formát, který sedí na Facebookové publikum a rozšiřuje povědomí o akademii bez nutnosti rozjíždět drahou videoprodukci. Snadno se reuse-uje pro další sezóny.',
            'Klient má formát, který sedí facebookovému publiku a rozšiřuje povědomí o akademii bez drahé videoprodukce. Pro další sezóny se dá použít znovu s minimem úprav.'],
        // ced-1
        ['pitarena-cedule', 'cs', 'result',
            'Cedule je nad servisem fyzicky umístěná a posiluje brand každého, kdo do areálu zajede.',
            'Cedule visí nad servisem a připomíná značku každému, kdo do areálu zajede.'],
        // anim-1
        ['animace-delejme', 'cs', 'title',
            'Animace upoutání pozornosti',
            'Animace pro upoutání pozornosti'],
        // anim-2
        ['animace-delejme', 'cs', 'meta_title',
            'Animace upoutání pozornosti — ukázka pro sociální sítě',
            'Animace pro upoutání pozornosti — ukázka pro sociální sítě'],
        // anim-3
        ['animace-delejme', 'cs', 'challenge',
            'Většina obsahu na sociálních sítích projde kolem člověka aniž by si ho všiml.',
            'Většina obsahu na sociálních sítích projde kolem člověka, aniž by si jí všiml.'],
        // B2 (ond262) — jediná DE položka v datech
        ['nove-interiery', 'de', 'subtitle',
            'Eine Website, die wie ein erstes Geschäftstreffen wirkt',
            'Eine Website, die wie ein erstes Verkaufsgespräch wirkt'],
    ];

    /** slug => [očekávaná stará hodnota, nová hodnota nebo null] */
    private const DURATIONS = [
        // X2-1
        'pitarena' => ['průběžně 2023–dnes', 'průběžně od 2023'],
        // X2-2
        'hcms' => ['několik měsíců (2021), provoz dodnes', 'několik měsíců, aplikace běží dodnes'],
        // X2-3
        'yolk' => ['průběžná spolupráce', 'průběžně od 2025'],
        // X2-4
        'realitacky-v-akci' => ['září 2024', null],
        // X2-5
        'josefopa' => ['říjen 2024', null],
        // X2-6
        'logo-realitacky' => ['leden 2024', null],
        // X2-7
        'vanspedition' => ['kratší realizace', null],
        // X2-8
        'elektro-srnak' => ['kratší realizace', null],
        // X2-9
        'clanek-motorkari-cz' => ['krátká spolupráce', null],
        // X2-10
        'video-pitbike-akademie' => ['krátká produkce', null],
        // X2-11
        'animace-delejme' => ['krátká produkce', null],
        // X2-12
        'pitarena-cedule' => ['krátká zakázka', null],
        // X2-13
        'excel-tools' => ['průběžně', null],
    ];

    /** Štítek visí jen na josefopa (ověřeno v produkčních datech), ale je
     *  sdílený přes `portfolio_tag_translations` — proto podmíněně. */
    private const TAGS = [
        ['cs', 'Web ve více jazycích', 'Web ve dvou jazycích'],
    ];

    public function up(): void
    {
        $this->apply(false);
    }

    public function down(): void
    {
        $this->apply(true);
    }

    /**
     * Na nenaplněné DB (čerstvá instalace, `RefreshDatabase` v testech) není
     * co opravovat — zdrojem pravdy je tam `docs/portfolio-data.yaml`, který
     * má po této změně rovnou správné znění. Migrace proto nesmí nic zakládat,
     * jinak by shodila ochranu `PortfolioSeeder`.
     */
    private function apply(bool $reverse): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::FIXES as [$slug, $locale, $field, $from, $to]) {
            $projectId = DB::table('portfolio_projects')->where('slug', $slug)->value('id');
            if (! $projectId) {
                continue;
            }

            [$search, $replace] = $reverse ? [$to, $from] : [$from, $to];

            $row = DB::table('portfolio_project_translations')
                ->where('project_id', $projectId)
                ->where('locale', $locale)
                ->where($field, 'like', '%'.$search.'%')
                ->first(['id', $field]);

            if (! $row) {
                continue;
            }

            DB::table('portfolio_project_translations')
                ->where('id', $row->id)
                ->update([
                    $field       => str_replace($search, $replace, $row->{$field}),
                    'updated_at' => now(),
                ]);
        }

        foreach (self::DURATIONS as $slug => [$old, $new]) {
            [$expected, $value] = $reverse ? [$new, $old] : [$old, $new];

            DB::table('portfolio_projects')
                ->where('slug', $slug)
                ->when($expected === null,
                    fn ($q) => $q->whereNull('duration'),
                    fn ($q) => $q->where('duration', $expected))
                ->update(['duration' => $value, 'updated_at' => now()]);
        }

        foreach (self::TAGS as [$locale, $from, $to]) {
            [$search, $replace] = $reverse ? [$to, $from] : [$from, $to];

            DB::table('portfolio_tag_translations')
                ->where('locale', $locale)
                ->where('name', $search)
                ->update(['name' => $replace, 'updated_at' => now()]);
        }
    }
};

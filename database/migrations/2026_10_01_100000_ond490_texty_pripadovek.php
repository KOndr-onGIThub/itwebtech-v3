<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-490 / OND-492 — opravy textů případovek z dokumentu `texty` (OND-491,
 * oddíl D a „D-skryté“).
 *
 *   Bod 8   vp-industry (cs/en/de): bez růstu, organiky a odložené 2. fáze.
 *           Celá pole subtitle, description, challenge, solution, result,
 *           meta_description a summary; výstup „SEO … připravená na růst“
 *           přepsaný a výstup „Připraveno pro … fázi, kterou klient zatím
 *           odložil“ smazaný.
 *   Bod 7, 10 a jazyk: úseky v cyklocentrum, choccoboard, frl-creator, hcms,
 *           picker, pitarena, zubni-provazek, kemp-veselka, nove-interiery,
 *           realitacky-v-akci a vanspedition (jen cs).
 *
 * Vzor OND-256/OND-454: změna se provede, jen když v DB stojí přesně původní
 * znění (celé pole = shoda celé hodnoty, úsek = úsek v hodnotě je právě
 * jednou). Druhý běh je no-op a ruční úprava z Filamentu se nepřepíše.
 * `down()` vrací totéž za stejných podmínek. Původní znění ověřena proti DB
 * testovací instance (kopie produkce) 1. 10. 2026.
 *
 * Na prázdné DB je migrace inertní: projekty zakládá PortfolioSeeder
 * z `docs/portfolio-data.yaml`, který je srovnaný na stejný výsledek.
 */
return new class extends Migration
{
    /** Celá pole: [slug, locale, pole, původní znění, nové znění] */
    private const WHOLE = [
        ['vp-industry', 'cs', 'subtitle',
            'Web připravený růst, jakmile klient zapne obsah',
            'Přehledné produktové stránky pro výrobce průmyslového značení'],
        ['vp-industry', 'cs', 'description',
            'VP Industry vyrábí průmyslové značení a automatizace. Cíl redesignu byl jasný: dostat web do stavu, který organicky roste, jakmile firma přidá obsah. Připravil jsem konkurenční analýzu, klíčová slova, copy i strukturu — technický základ je dnes hotový.',
            'VP Industry vyrábí průmyslové značení a automatizace. Postavil jsem jí nový web. Připravil jsem analýzu konkurence a klíčových slov, texty i strukturu a web je technicky hotový.'],
        ['vp-industry', 'cs', 'challenge',
            'Klient chtěl web, který umožní dlouhodobý organický růst — ne marketingovou kampaň na pár měsíců. Bylo potřeba pochopit konkurenci, definovat klíčová slova, postavit obsahovou strukturu a webovou architekturu, která tomuhle růstu neudělá překážku.',
            'Klient chtěl kvalitní web, který se odliší od konkurence. Bylo potřeba pochopit, jak se prezentují ostatní výrobci, zjistit, jakými slovy zákazníci produkty hledají, a podle toho postavit strukturu webu.'],
        ['vp-industry', 'cs', 'solution',
            'Začal jsem analýzou konkurence a mapováním klíčových slov. Web má detailní produktové stránky s technickými specifikacemi, ukázkovými videi a případovými studiemi. SEO je technicky správně, analytika napojená a struktura počítá s budoucími články. Vše připravené, aby kampaň jen sedla na hotový základ.',
            'Začal jsem analýzou konkurence a mapováním klíčových slov. Web má podrobné produktové stránky s technickými parametry, ukázkovými videi a případovými studiemi. Technické SEO je nastavené správně a analytika je napojená.'],
        ['vp-industry', 'cs', 'result',
            'Klient dostal web, který je technicky hotový: produktové stránky s parametry a videi, správně nastavené SEO, napojenou analytiku a strukturu, která unese další články bez přestavby. Obsahovou a kampaňovou fázi zatím odložil — až ji zapne, nic se nebude muset předělávat.',
            'Klient dostal technicky hotový web s produktovými stránkami, parametry a videi, se správně nastaveným SEO a napojenou analytikou. Výkonná ředitelka v referenci píše, že výsledek odpovídá jejich představám.'],
        ['vp-industry', 'cs', 'meta_description',
            'Případová studie: redesign webu VP Industry s konkurenční analýzou, klíčovými slovy a SEO základem připraveným na obsahovou kampaň.',
            'Případová studie: redesign webu VP Industry s analýzou konkurence a klíčových slov, produktovými stránkami a technickým SEO.'],
        ['vp-industry', 'en', 'subtitle',
            'A site built to grow the moment content kicks in',
            'Clear product pages for an industrial marking manufacturer'],
        ['vp-industry', 'en', 'description',
            'VP Industry makes industrial marking and automation systems. The redesign goal was clear: take the site to a state where it grows organically the moment the company starts producing content. Competitor analysis, keywords, copy and structure are now in place — the technical foundation is done.',
            'VP Industry makes industrial marking and automation systems. I built them a new website: competitor and keyword research, copy and structure, and a site that is technically complete.'],
        ['vp-industry', 'en', 'challenge',
            'The client wanted a site set up for long-term organic growth, not a few-month marketing burst. The job was to understand the competitive landscape, define keywords, and build content and architecture that would not block that growth.',
            'The client wanted a high-quality website that stands apart from the competition. The job was to understand how other manufacturers present themselves, find the words customers use to search for the products, and build the site structure around that.'],
        ['vp-industry', 'en', 'solution',
            'I started with competitor research and keyword mapping. The site has detailed product pages with technical specs, sample videos and case studies. SEO is technically clean, analytics is in place, and the structure assumes future articles. The infrastructure is ready — a content campaign can drop straight onto it.',
            'I started with competitor research and keyword mapping. The site has detailed product pages with technical specs, sample videos and case studies. Technical SEO is set up properly and analytics is in place.'],
        ['vp-industry', 'en', 'result',
            'The site is technically ready to grow; what\'s missing is the active content and campaign work the client has paused for now. The organic-growth potential is wired in and waiting for the next phase. That\'s the honest outcome — I delivered what was scoped, and I\'m clear about what remains.',
            'The client got a technically complete website with product pages, specs and videos, properly set-up SEO and connected analytics. In her reference, the managing director writes that the result matches what they had in mind.'],
        ['vp-industry', 'en', 'meta_description',
            'Case study: VP Industry website redesign with competitor analysis, keyword research, and an SEO foundation ready for a content campaign.',
            'Case study: VP Industry website redesign with competitor and keyword research, product pages and technical SEO.'],
        ['vp-industry', 'de', 'subtitle',
            'Eine Website, die zu wachsen beginnt, sobald Content kommt',
            'Übersichtliche Produktseiten für einen Hersteller von Industriekennzeichnung'],
        ['vp-industry', 'de', 'description',
            'VP Industry fertigt industrielle Kennzeichnung und Automatisierung. Ziel des Redesigns: die Site in einen Zustand bringen, in dem sie organisch wächst, sobald das Unternehmen Content produziert. Wettbewerbsanalyse, Keywords, Texte und Struktur stehen — das technische Fundament ist fertig.',
            'VP Industry fertigt industrielle Kennzeichnung und Automatisierung. Ich habe für das Unternehmen eine neue Website gebaut: Wettbewerbs- und Keyword-Analyse, Texte und Struktur, technisch fertig.'],
        ['vp-industry', 'de', 'challenge',
            'Der Kunde wollte eine Site für langfristiges organisches Wachstum, keine kurzfristige Kampagne. Es ging darum, den Wettbewerb zu verstehen, Keywords festzulegen und eine Content-Architektur aufzubauen, die diesem Wachstum nicht im Weg steht.',
            'Der Kunde wollte eine hochwertige Website, die sich von der Konkurrenz abhebt. Es ging darum zu verstehen, wie sich andere Hersteller präsentieren, herauszufinden, mit welchen Begriffen Kunden nach den Produkten suchen, und danach die Struktur der Website aufzubauen.'],
        ['vp-industry', 'de', 'solution',
            'Ich startete mit Wettbewerbs- und Keyword-Recherche. Die Site hat detaillierte Produktseiten mit technischen Specs, Videos und Case Studies. SEO ist technisch sauber, Analytics läuft, und die Struktur rechnet mit zukünftigen Artikeln. Die Infrastruktur ist bereit — eine Content-Kampagne kann direkt aufsetzen.',
            'Ich startete mit Wettbewerbs- und Keyword-Recherche. Die Website hat detaillierte Produktseiten mit technischen Daten, Videos und Case Studies. Das technische SEO ist sauber eingerichtet, Analytics läuft.'],
        ['vp-industry', 'de', 'result',
            'Technisch ist die Site bereit zu wachsen; was fehlt, ist die aktive Content- und Kampagnenarbeit, die der Kunde derzeit ausgesetzt hat. Das organische Wachstumspotenzial ist verdrahtet und wartet auf die nächste Phase. Ein ehrliches Ergebnis: Ich liefere, was beauftragt war, und benenne offen, was noch ansteht.',
            'Der Kunde hat eine technisch fertige Website mit Produktseiten, Parametern und Videos, sauber eingerichtetem SEO und angebundener Analytics. Die Geschäftsführerin schreibt in ihrer Referenz, dass das Ergebnis ihren Vorstellungen entspricht.'],
        ['vp-industry', 'de', 'meta_description',
            'Case Study: Redesign der VP-Industry-Website mit Wettbewerbsanalyse, Keyword-Recherche und SEO-Fundament für eine Content-Kampagne.',
            'Case Study: Redesign der VP-Industry-Website mit Wettbewerbs- und Keyword-Analyse, Produktseiten und technischem SEO.'],
        ['vp-industry', 'cs', 'summary',
            'Redesign webu výrobce průmyslového značení a automatizací — postavený s konkurenční analýzou, SEO základem a strukturou připravenou na obsahovou kampaň.',
            'Redesign webu výrobce průmyslového značení a automatizací — s analýzou konkurence a klíčových slov, produktovými stránkami a technickým SEO.'],
        ['vp-industry', 'en', 'summary',
            'Redesign for a manufacturer of industrial marking and automation — built with competitor analysis, SEO foundations and a content-ready structure.',
            'Redesign for a manufacturer of industrial marking and automation — with competitor and keyword research, product pages and technical SEO.'],
        ['vp-industry', 'de', 'summary',
            'Redesign für einen Hersteller industrieller Kennzeichnung und Automatisierung — mit Wettbewerbsanalyse, SEO-Fundament und content-fähiger Struktur.',
            'Redesign für einen Hersteller industrieller Kennzeichnung und Automatisierung — mit Wettbewerbs- und Keyword-Analyse, Produktseiten und technischem SEO.'],
    ];

    /** Úseky: [slug, locale, pole, původní úsek, nový úsek] */
    private const PARTS = [
        ['cyklocentrum', 'cs', 'challenge',
            'Záchrana starého webu nedávala smysl — bylo levnější a bezpečnější udělat ho znovu pořádně.',
            'Opravovat rozpracovaný web nedávalo smysl — bylo levnější a bezpečnější udělat ho znovu pořádně.'],
        ['cyklocentrum', 'cs', 'live_hint',
            'Otevřete si na mobilu kolo z nabídky: cena i tlačítka',
            'Otevřete si na mobilu kolo z nabídky. Cena i tlačítka'],
        ['choccoboard', 'cs', 'description',
            'Choccoboard je webová aplikace pro vedení firmy: ukazuje aktuální',
            'Choccoboard je webová aplikace pro vedení firmy. Ukazuje aktuální'],
        ['choccoboard', 'cs', 'challenge',
            'Realita: každý den 30 minut manuální práce — stažení, slepení, kontrola. Kromě časové ztráty to limitovalo i to, jak často se vůbec člověk na čísla mohl podívat.',
            'Ve skutečnosti to každý den znamenalo 30 minut ruční práce — stáhnout, slepit, zkontrolovat. Kromě ztráty času to omezovalo i to, jak často se člověk vůbec mohl na čísla podívat.'],
        ['choccoboard', 'cs', 'result',
            'Manuální 30 minutová denní rutina je pryč. Vedení vidí aktuální čísla okamžitě, kdykoli se rozhodují.',
            'Třicetiminutová ruční práce každý den je pryč. Vedení vidí aktuální čísla okamžitě, kdykoli se rozhoduje.'],
        ['frl-creator', 'cs', 'challenge',
            'Bylo potřeba systém, který vstupuje do procesu jako spolehlivý spoluhráč: přijme parametry',
            'Byl potřeba systém, který vstoupí do procesu jako spolehlivý spoluhráč. Přijme parametry'],
        ['frl-creator', 'cs', 'description',
            'výstup je hotový PDF k tisku.',
            'výstupem je hotové PDF k tisku.'],
        ['hcms', 'cs', 'challenge',
            'Bylo potřeba systém, který',
            'Byl potřeba systém, který'],
        ['hcms', 'cs', 'solution',
            'které se z venku nedají odhadnout.',
            'které se zvenku nedají odhadnout.'],
        ['picker', 'cs', 'challenge',
            'Bylo potřeba systém, který',
            'Byl potřeba systém, který'],
        ['pitarena', 'cs', 'result',
            'je web v Google první už od prvního roku',
            'je web v Googlu první už od prvního roku'],
        ['pitarena', 'cs', 'result',
            '„závody pitbike“, v Google i na Seznamu.',
            '„závody pitbike“, v Googlu i na Seznamu.'],
        ['zubni-provazek', 'cs', 'challenge',
            'a v Google se prakticky neobjevoval.',
            'a v Googlu se prakticky neobjevoval.'],
        ['kemp-veselka', 'cs', 'challenge',
            'Pro kemp, kam se lidé typicky rozhodují cestou nebo na mobilu,',
            'Pro kemp, o kterém se lidé typicky rozhodují cestou nebo v mobilu,'],
        ['kemp-veselka', 'cs', 'solution',
            'Rychlost načítání jsem hlídal kvůli prvotnímu dojmu na mobilní data.',
            'Rychlost načítání jsem hlídal, aby web udělal dobrý první dojem i na mobilních datech.'],
        ['nove-interiery', 'cs', 'challenge',
            'klient sám přiznával, že kvalitu jeho služby ten web nepřesvědčivě podává. Bylo potřeba weby ostatních (typicky šablony a katalogové weby) jasně přebít a získat klientův důvěryhodný hlas.',
            'klient sám přiznával, že ten web jeho službu nepodává přesvědčivě. Bylo potřeba jasně se odlišit od webů konkurence, typicky šablon a katalogů, a dát klientovi důvěryhodný hlas.'],
        ['realitacky-v-akci', 'cs', 'description',
            'a brandingem postaveným ze stejného ducha jako její práce.',
            'a brandingem ve stejném duchu jako její práce.'],
        ['realitacky-v-akci', 'cs', 'solution',
            'Klient má admin pro nemovitosti, poptávky a blog, takže si všechno aktualizuje sám.',
            'Klientka má administraci pro nemovitosti, poptávky a blog, takže si všechno aktualizuje sama.'],
        ['realitacky-v-akci', 'cs', 'live_hint',
            'Otevřete si kteroukoli nabídku nemovitosti: galerie, parametry',
            'Otevřete si kteroukoli nabídku nemovitosti. Galerie, parametry'],
        ['vanspedition', 'cs', 'description',
            'bez zbytečného balastu, který by zákazníka zdržoval od jediné akce, která dává smysl: ozvat se.',
            'bez zbytečného balastu. Zákazníka nic nezdržuje od toho, aby se ozval.'],
        ['vanspedition', 'cs', 'live_hint',
            'Celý web je jedna stránka: zkuste, jak rychle',
            'Celý web je jedna stránka. Zkuste, jak rychle'],
    ];

    /** Výstupy vp-industry: locale => [původní, nový] (sort_order 2) */
    private const OUTCOME_RENAME = [
        'cs' => ['SEO struktura a indexace připravená na růst', 'Technické SEO a indexace nastavené správně'],
        'en' => ['SEO structure and indexing ready to grow', 'Technical SEO and indexing set up properly'],
        'de' => ['SEO-Struktur und Indexierung wachstumsbereit', 'Technisches SEO und Indexierung sauber eingerichtet'],
    ];

    /** Výstup vp-industry ke smazání (sort_order 4), locale => label */
    private const OUTCOME_DROP_SORT = 4;
    private const OUTCOME_DROP = [
        'cs' => 'Připraveno pro obsahovou a kampaňovou fázi, kterou klient zatím odložil',
        'en' => 'Ready for the content/campaign phase the client paused',
        'de' => 'Bereit für die pausierte Content-/Kampagnen-Phase',
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        DB::transaction(function () {
            $this->applyTexts(3, 4);
            $this->renameOutcomes(0, 1);
            $this->dropOutcome();
        });
    }

    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        DB::transaction(function () {
            $this->applyTexts(4, 3);
            $this->renameOutcomes(1, 0);
            $this->restoreOutcome();
        });
    }

    private function applyTexts(int $from, int $to): void
    {
        foreach (self::WHOLE as $fix) {
            [$slug, $locale, $field] = $fix;
            $row = $this->translation($slug, $locale);
            if ($row && $row->{$field} === $fix[$from]) {
                $this->update($row->id, $field, $fix[$to]);
            }
        }

        foreach (self::PARTS as $fix) {
            [$slug, $locale, $field] = $fix;
            $row = $this->translation($slug, $locale);
            if ($row && substr_count((string) $row->{$field}, $fix[$from]) === 1) {
                $this->update($row->id, $field, str_replace($fix[$from], $fix[$to], $row->{$field}));
            }
        }
    }

    private function renameOutcomes(int $from, int $to): void
    {
        $projectId = $this->projectId('vp-industry');
        if (! $projectId) {
            return;
        }

        $outcomeIds = DB::table('portfolio_project_outcomes')->where('project_id', $projectId)->pluck('id');

        foreach (self::OUTCOME_RENAME as $locale => $labels) {
            DB::table('portfolio_project_outcome_translations')
                ->whereIn('outcome_id', $outcomeIds)
                ->where('locale', $locale)
                ->where('label', $labels[$from])
                ->update(['label' => $labels[$to], 'updated_at' => now()]);
        }
    }

    /** Smaže výstup jen tehdy, když ve všech třech jazycích nese původní text. */
    private function dropOutcome(): void
    {
        $projectId = $this->projectId('vp-industry');
        if (! $projectId) {
            return;
        }

        foreach (DB::table('portfolio_project_outcomes')->where('project_id', $projectId)->pluck('id') as $outcomeId) {
            $labels = DB::table('portfolio_project_outcome_translations')
                ->where('outcome_id', $outcomeId)
                ->pluck('label', 'locale')
                ->all();

            if ($labels == self::OUTCOME_DROP) {
                DB::table('portfolio_project_outcome_translations')->where('outcome_id', $outcomeId)->delete();
                DB::table('portfolio_project_outcomes')->where('id', $outcomeId)->delete();
            }
        }
    }

    private function restoreOutcome(): void
    {
        $projectId = $this->projectId('vp-industry');
        if (! $projectId) {
            return;
        }

        $exists = DB::table('portfolio_project_outcome_translations as t')
            ->join('portfolio_project_outcomes as o', 'o.id', '=', 't.outcome_id')
            ->where('o.project_id', $projectId)
            ->where('t.locale', 'cs')
            ->where('t.label', self::OUTCOME_DROP['cs'])
            ->exists();
        if ($exists) {
            return;
        }

        $outcomeId = DB::table('portfolio_project_outcomes')->insertGetId([
            'project_id' => $projectId,
            'sort_order' => self::OUTCOME_DROP_SORT,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (self::OUTCOME_DROP as $locale => $label) {
            DB::table('portfolio_project_outcome_translations')->insert([
                'outcome_id' => $outcomeId,
                'locale'     => $locale,
                'label'      => $label,
                'value'      => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function update(int $id, string $field, string $value): void
    {
        DB::table('portfolio_project_translations')->where('id', $id)
            ->update([$field => $value, 'updated_at' => now()]);
    }

    private function projectId(string $slug): ?int
    {
        return DB::table('portfolio_projects')->where('slug', $slug)->value('id');
    }

    private function translation(string $slug, string $locale): ?object
    {
        $projectId = $this->projectId($slug);

        return $projectId
            ? DB::table('portfolio_project_translations')->where('project_id', $projectId)->where('locale', $locale)->first()
            : null;
    }
};

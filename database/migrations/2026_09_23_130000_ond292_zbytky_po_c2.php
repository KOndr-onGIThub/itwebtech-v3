<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-292 — zbytky po závěrečném průchodu auditu (OND-271 / C2).
 *
 * Řeší jen ty body ze zadání, které po ověření proti skutečnému obsahu
 * obstály. Tři ze čtyř „hero/galerie swapů" a chybějící náhled u článku
 * do migrace nepatří — proč, je v komentáři karty OND-292.
 *
 *   1. YOLK — „klinikových" → „klinických" (skloňování). Po OND-267 zbylo
 *      slovo v `meta_title` a ve třech altech snímků; viditelný `title`
 *      byl tehdy přepsán na „weby zdravotnických klinik".
 *
 *   2. FRL Creator — lead pás galerie a první karta pod ním ukazovaly TÝŽ
 *      formulář FLOW RACK LIST CREATOR (`gallery-1.jpg` na notebooku,
 *      `hero-1.jpg` naplocho). Projekt má celkem tři obrázky, takže se
 *      duplicita nedala přehlédnout. Přeřazení `hero-1` na
 *      `type = 'thumbnail'` je stejný nástroj, jakým OND-268 vyřešil HCMS
 *      a VAN spedition: šablona detailu řádky `thumbnail` vynechává
 *      (detail-gallery.blade.php), takže z galerie zmizí duplikát, a
 *      `portfolio_card_thumbnail()` jim naopak dává přednost, takže
 *      miniatura karty v /projekty i v „Dalších projektech" zůstane přesně
 *      taková, jaká je dnes. Žádný obrázek se nemaže a nic nového se
 *      nenačítá — mění se jedno pole.
 *
 *   3. Blog (CS) — šest textových oprav ve třech článcích; podrobnosti
 *      u jednotlivých položek v self::TEXTS.
 *
 * Náhrady jsou podmíněné `LIKE` + `str_replace` (idiom z OND-256): sáhnou
 * jen tam, kde původní znění pořád stojí, druhý běh je no-op a ruční úpravy
 * z Filamentu se nepřepíšou. Na nenaplněné DB je migrace inertní — tam je
 * zdrojem pravdy `docs/portfolio-data.yaml` a `BlogContentSeeder`, které
 * jsou s tímhle souborem srovnané.
 */
return new class extends Migration
{
    /**
     * Textové náhrady: [tabulka, sloupec, locale, from, to].
     *
     * Blog:
     *  - čl. 6 `description` se vykresluje jako podtitulek pod H1 a hned pod
     *    ním stojí perex. Obě věty začínaly „Osmnáct let jsem pracoval…
     *    Toyot…" a obě končily „Píšu, podle čeho poznáte…" — čtenář dostal
     *    tutéž informaci dvakrát po sobě. Nový podtitulek shrnuje, co článek
     *    dává; doklad o Toyotě nese perex hned pod ním (a zůstává beze změny).
     *  - „starší specialista" je doslovný překlad „senior specialist" a čte
     *    se jako údaj o věku, ne o seniornosti.
     *  - „kupte si radši pořádnou tabulku" radí koupit tabulku v článku,
     *    který stojí na tom, že tabulku už firma má.
     *  - čárka v titulku čl. 10 odděluje pauzu mezi „Někdy ne" a dovětkem.
     *  - „srovnávají vás dolů" není česká vazba.
     *  - „Tři situace, kdy peníze nechte v kapse" míchá vztažnou větu
     *    s rozkazovacím způsobem; infinitiv drží i paralelu s nadpisem
     *    „Pět důvodů, kdy redesign dává smysl" o pár řádků výš.
     */
    private const TEXTS = [
        // --- YOLK (bod 1) ---
        [
            'portfolio_project_translations', 'meta_title', 'cs',
            'YOLK — agenturní vývoj na klinikových portálech',
            'YOLK — agenturní vývoj na klinických portálech',
        ],
        [
            'portfolio_project_screenshot_translations', 'alt', 'cs',
            'ukázka klinikového portálu',
            'ukázka klinického portálu',
        ],
        [
            'portfolio_project_screenshot_translations', 'alt', 'cs',
            'vývoj na klinikových portálech',
            'vývoj na klinických portálech',
        ],

        // --- Blog, článek 6 „Kdy se firmě vyplatí aplikace na míru" (bod 3) ---
        [
            'article_translations', 'description', 'cs',
            'Osmnáct let jsem pracoval v logistice Toyoty a psal tam aplikace do provozu. Píšu, podle čeho poznáte, že tabulka firmě přestala stačit.',
            'Excel firmě stačí, dokud v něm nepracuje víc lidí a dokud chyba nestojí peníze. Pět signálů, že tabulka došla na hranici, a výpočet, jestli se aplikace vyplatí.',
        ],
        [
            'article_translations', 'perex', 'cs',
            'skončil jako starší specialista v projektovém týmu',
            'skončil jako senior specialista v projektovém týmu',
        ],
        [
            'article_translations', 'content_2', 'cs',
            'nechte to být a kupte si radši pořádnou tabulku.',
            'nechte to být a zůstaňte u tabulky.',
        ],

        // --- Blog, článek 10 „Potřebuje vaše firma web?" (bod 3) ---
        [
            'article_translations', 'title', 'cs',
            'Potřebuje vaše firma web? Někdy ne a řeknu vám kdy',
            'Potřebuje vaše firma web? Někdy ne, a řeknu vám kdy',
        ],
        [
            'article_translations', 'content_1', 'cs',
            'Když najdou jen profil na Firmy.cz z roku 2019, srovnávají vás dolů.',
            'Když najdou jen profil na Firmy.cz z roku 2019, hraje to proti vám.',
        ],

        // --- Blog, článek 13 „Kdy má smysl předělat web" (bod 3) ---
        [
            'article_translations', 'content_1', 'cs',
            '<h2>Tři situace, kdy peníze nechte v kapse</h2>',
            '<h2>Tři situace, kdy peníze nechat v kapse</h2>',
        ],
    ];

    /** FRL Creator: cesta snímku, který se přesouvá mezi rolemi (bod 2). */
    private const FRL_HERO_PATH = 'projects/frl-creator/hero-1.jpg';

    public function up(): void
    {
        $this->replaceTexts('to');
        $this->moveFrlHero('hero', 'thumbnail');
    }

    public function down(): void
    {
        $this->replaceTexts('from');
        $this->moveFrlHero('thumbnail', 'hero');
    }

    /**
     * @param  string  $direction  'to' = dopředu, 'from' = zpět
     */
    private function replaceTexts(string $direction): void
    {
        $now = now();

        foreach (self::TEXTS as [$table, $column, $locale, $from, $to]) {
            if (! $this->tableReady($table)) {
                continue;
            }

            $needle      = $direction === 'to' ? $from : $to;
            $replacement = $direction === 'to' ? $to : $from;

            $rows = DB::table($table)
                ->where('locale', $locale)
                ->where($column, 'like', '%'.$needle.'%')
                ->get(['id', $column]);

            foreach ($rows as $row) {
                DB::table($table)
                    ->where('id', $row->id)
                    ->update([
                        $column      => str_replace($needle, $replacement, $row->{$column}),
                        'updated_at' => $now,
                    ]);
            }
        }
    }

    private function moveFrlHero(string $expected, string $target): void
    {
        if (! $this->tableReady('portfolio_project_screenshots')) {
            return;
        }

        DB::table('portfolio_project_screenshots')
            ->where('path', self::FRL_HERO_PATH)
            ->where('type', $expected)
            ->update(['type' => $target, 'updated_at' => now()]);
    }

    /**
     * Prázdná tabulka = čerstvá instalace, kde opravený text zaseje seeder.
     */
    private function tableReady(string $table): bool
    {
        return DB::table($table)->exists();
    }
};

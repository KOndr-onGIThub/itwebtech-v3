<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-286 — úklid absolutních odkazů na `itwebtech.cz/jak-na-to/...` v textech článků.
 *
 * V `article_translations` stálo 26 odkazů na starou doménu. Doména sice pořád
 * odpovídá (běží na ní původní web), ale vedou z ondrawebu ven na opuštěnou
 * značku a hlavně na obsah, který OND-204 z webu záměrně stáhlo. Pouhé přepsání
 * domény je proto špatná oprava: jen dva z těch odkazů mají na dnešním webu živý
 * cíl, 19 dalších by čtenáře vysypalo na rozcestník `/zapisky`.
 *
 * Tři různé opravy podle toho, jak je odkaz zasazený do věty:
 *
 *   1. živý cíl (2×)       → přepsat na relativní adresu vlastního webu,
 *   2. mrtvý cíl uvnitř
 *      běžné fráze (21×)   → `<a>` odstranit, text věty ponechat,
 *   3. mrtvý cíl ve vazbě
 *      „přečtěte si článek X" (3×) → přepsat větu (znění z OND-288, Content Writer).
 *
 * Bod 3 se musí provést **před** bodem 2, jinak by po rozbalení zůstalo
 * doporučení přečíst si článek, který na webu není.
 *
 * Seeder je čistá transformace už existujících textů, proto ho volá jak migrace
 * `2026_09_23_110000_ond286_odkazy_na_starou_domenu` (existující DB), tak
 * `EnsureArticlesSeededSeeder` po importu dumpů (čerstvá DB) — dumpy v
 * `database/sql/` ty odkazy pořád obsahují.
 *
 * Opakované spuštění je no-op: po prvním běhu už žádný odkaz na starou doménu
 * pod `/jak-na-to/` v datech není, takže se nemá co chytit.
 */
class BlogLegacyDomainLinksSeeder extends Seeder
{
    /** Textové sloupce `article_translations`, ve kterých odkazy žijí. */
    private const COLUMNS = [
        'title', 'description', 'perex', 'content_1', 'content_mid', 'content_2', 'bonus', 'extra',
    ];

    /**
     * Jediné dva odkazy, jejichž cíl je na dnešním webu publikovaný.
     * Slug se bere z odkazu (u EN článků se liší od CS), locale určuje,
     * pod kterou jazykovou mutací cíl žije.
     */
    private const LIVE_TARGETS = [
        'potrebuje-vase-firma-webovou-stranku'             => 'cs',
        'website-redesign-reasons-signals-and-how-to-do-it' => 'en',
    ];

    /** Odkaz na starou doménu do sekce článků — `jak-na-to` je i koncovka jednoho slugu, proto celý tvar. */
    private const LINK_PATTERN = '~<a\b[^>]*\bhref="https?://(?:www\.)?itwebtech\.cz/jak-na-to/([^"]+)"[^>]*>(.*?)</a>~s';

    public function run(): void
    {
        // Na nenaplněné DB nemá co dělat — obsah tam teprve doputuje importem.
        if (! DB::table('article_translations')->exists()) {
            return;
        }

        foreach (DB::table('article_translations')->get() as $row) {
            $changes = [];

            foreach (self::COLUMNS as $column) {
                $original = (string) ($row->$column ?? '');

                if (! str_contains($original, 'itwebtech.cz/jak-na-to/')) {
                    continue;
                }

                $rewritten = $this->rewriteLinks($this->rewriteSentences($original));

                if ($rewritten !== $original) {
                    $changes[$column] = $rewritten;
                }
            }

            if ($changes !== []) {
                DB::table('article_translations')->where('id', $row->id)->update($changes);
            }
        }
    }

    /**
     * Bod 3 — tři věty v EN mutaci, kde rozbalení odkazu nestačí.
     *
     * Znění dodal Content Writer v OND-288. Vzory jsou kotvené na návětí věty
     * i na slug cíle, takže se nechytnou nikde jinde a po přepsání už nesedí.
     */
    private function rewriteSentences(string $value): string
    {
        // article 4 / en / content_1 — odstavec uvozuje dvě odrážky pod sebou,
        // nesmí zmizet celý, jinak nadpis sekce narazí rovnou do seznamu.
        $value = preg_replace(
            '~<p>\s*Read more about SEO in this article\s*<a\b[^>]*co-je-soe-a-proc-je-tak-dulezite[^>]*>.*?</a>\s*</p>~s',
            '<p>Search engines are where most visitors start, so SEO belongs in the brief from day one. '
                .'Building it into the structure of the site costs a fraction of what it takes to retrofit it later.</p>',
            $value
        );

        // article 10 / en / content_2 — mění se jen poslední věta odstavce.
        $value = preg_replace(
            '~\s*If you want to learn more about SEO[^<]*<a\b[^>]*what-is-seo-and-why-is-it-so-important[^>]*>.*?</a>\s*\.~s',
            ' In practice that means a site that loads fast, content written around the questions your customers '
                .'actually type into a search box, and a structure search engines can read.',
            $value
        );

        // article 13 / en / bonus — poslední odstavec bloku, stojí až za výzvou
        // ke konzultaci. Odkaz po CTA odvádí od akce, věta se maže celá.
        return preg_replace(
            '~<p>\s*If you want to know a little more about the elements of effective web design[^<]*'
                .'<a\b[^>]*how-to-create-a-successful-website[^>]*>.*?</a>\s*\.\s*</p>~s',
            '',
            $value
        );
    }

    /**
     * Body 1 a 2 — živý cíl dostane relativní adresu, mrtvý se rozbalí na holý text.
     */
    private function rewriteLinks(string $value): string
    {
        return preg_replace_callback(self::LINK_PATTERN, function (array $match): string {
            $slug = rtrim($match[1], '/');
            $text = $match[2];

            if (! isset(self::LIVE_TARGETS[$slug])) {
                return $text;
            }

            // Odkaz míří na vlastní web, takže bez domény, bez `target` i bez
            // `nofollow` — to všechno tam zbylo po externím odkazu.
            return '<a href="'.$this->articlePath($slug, self::LIVE_TARGETS[$slug]).'">'.$text.'</a>';
        }, $value);
    }

    /**
     * Adresa článku se bere z routování, ne z konstanty.
     *
     * OND-266 přejmenovává CS sekci `jak-na-to` → `zapisky` a pořadí mergů obou
     * větví není dané. Takhle se do dat zapíše vždycky ta adresa, kterou nasazený
     * kód opravdu obsluhuje; pokud se sekce přejmenuje až potom, drží ji 301
     * z `routes/web.php`.
     */
    private function articlePath(string $slug, string $locale): string
    {
        return route("{$locale}.article", ['slug' => $slug], absolute: false);
    }
}

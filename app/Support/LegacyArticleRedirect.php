<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Http\RedirectResponse;

/**
 * Staré adresy článků → konečná adresa jedním 301.
 *
 * OND-466: `/jak-na-to/{slug}` dřív vedl na `/zapisky/{slug}` a teprve článek
 * přesměroval starý slug na nový — z itwebtech.cz tak vznikly tři skoky.
 * Tady se slug vyhodnotí rovnou: neaktivní slug → dnešní slug článku,
 * stažený článek → článek z REMOVED_ARTICLE_REDIRECTS nebo výpis blogu.
 */
class LegacyArticleRedirect
{
    /**
     * OND-204: mapa přesměrování pro články stažené z blogu (published = 0).
     *
     * Klíč = id staženého článku, hodnota = id článku, na který má stará
     * adresa vést. Co v mapě není, jde na výpis blogu. Mapuje se na id,
     * ne na slug, aby přesměrování sedělo i v EN/DE verzi webu.
     *
     * Zdroj: dokument `clanky-cs` (OND-421). Články 7 a 11 jsou od OND-432
     * znovu publikované, stažený zůstává jen 8 — je sloučený do 11, které
     * řeší stejnou otázku.
     */
    private const REMOVED_ARTICLE_REDIRECTS = [
        8 => 11,  // Web, který převádí návštěvníky → Jak vytvořit úspěšnou webovou stránku
    ];

    /**
     * Slug ze staré adresy → přesměrování na konečnou adresu článku.
     * Null, když slug neznáme nebo článek v téhle locale nemá adresu —
     * volající pak pošle slug dál beze změny.
     */
    public static function for(string $slug, string $locale): ?RedirectResponse
    {
        $slugRecord = ArticleSlug::where('slug', $slug)
            ->orderByDesc('active')
            ->first();

        if (! $slugRecord) {
            return null;
        }

        $article = Article::where('id', $slugRecord->article_id)
            ->where('published', true)
            ->with('slugs')
            ->first();

        if (! $article) {
            return self::removed($slugRecord->article_id, $locale);
        }

        $canonical = $article->slugs
            ->where('locale', $locale)
            ->where('active', true)
            ->first();

        return $canonical
            ? redirect()->route("{$locale}.article", ['slug' => $canonical->slug], 301)
            : null;
    }

    /**
     * Stažený článek: adresa zůstává funkční a 301 vede na nejbližší
     * relevantní stránku. Nikdy 404 — staré adresy mají odkazy zvenčí.
     */
    public static function removed(int $articleId, string $locale): RedirectResponse
    {
        $targetId = self::REMOVED_ARTICLE_REDIRECTS[$articleId] ?? null;

        if ($targetId) {
            $targetSlug = Article::where('id', $targetId)
                ->where('published', true)
                ->with('slugs')
                ->first()
                ?->slug($locale);

            if ($targetSlug) {
                return redirect()->route("{$locale}.article", ['slug' => $targetSlug], 301);
            }
        }

        return redirect()->to(lroute('blog', $locale), 301);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function home()
    {
        // OND-120: 3 favority do sekce „Realizované projekty" (PitArena, BARANA, Nové interiéry).
        // Pořadí drženo whitelistem slugů — sort_order v DB by mohl být jiný.
        $featuredHomeProjects = collect();

        if (config('site.features.show_portfolio_section')) {
            $slugs = ['pitarena', 'barana', 'nove-interiery'];

            $featuredHomeProjects = PortfolioProject::published()
                ->whereIn('slug', $slugs)
                ->with(['translations', 'screenshots'])
                ->get()
                ->sortBy(fn ($p) => array_search($p->slug, $slugs, true))
                ->values();
        }

        return view('pages.home', compact('featuredHomeProjects'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function price()
    {
        return view('pages.price');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * OND-125: cookie policy stránka. Statický text v češtině, link na
     * revokaci souhlasu (volá window.ItwebtechAnalytics.revokeConsent()).
     */
    public function cookies()
    {
        return view('pages.cookies');
    }

    public function projects()
    {
        $locale = App::getLocale();

        $portfolioProjects = PortfolioProject::published()
            ->with(['translations', 'screenshots', 'tags.translations'])
            ->orderBy('sort_order')
            ->orderByDesc('year')
            ->get();

        // Počty pro filtry kategorií
        $counts = [
            'all'         => $portfolioProjects->count(),
            'website'     => $portfolioProjects->where('category', 'website')->count(),
            'application' => $portfolioProjects->where('category', 'application')->count(),
            'other'       => $portfolioProjects->where('category', 'other')->count(),
        ];

        return view('pages.projects', compact('portfolioProjects', 'locale', 'counts'));
    }

    public function project(string $url)
    {
        $locale = App::getLocale();

        $project = PortfolioProject::published()
            ->where('slug', $url)
            ->with([
                'translations',
                'screenshots.translations',
                'tags.translations',
                'outcomes.translations',
            ])
            ->first();

        if (! $project) {
            abort(404);
        }

        $translation = $project->translation($locale);

        // Hreflang — slug je jazyk-neutrální, takže pro každý jazyk
        // vygenerujeme stejný slug v příslušné jazykové routě.
        $hreflangs = [];
        foreach (['cs', 'en', 'de'] as $lang) {
            $hreflangs[$lang] = route("{$lang}.project", ['url' => $project->slug]);
        }

        // Související projekty: 3 kusy, stejná kategorie, vyloučit aktuální.
        // Pokud je < 3 v kategorii, doplníme z ostatních (featured první).
        $sameCategory = PortfolioProject::published()
            ->where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->with(['translations', 'screenshots'])
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('year')
            ->limit(3)
            ->get();

        if ($sameCategory->count() < 3) {
            $needed  = 3 - $sameCategory->count();
            $excluded = $sameCategory->pluck('id')->push($project->id);
            $extras  = PortfolioProject::published()
                ->whereNotIn('id', $excluded)
                ->with(['translations', 'screenshots'])
                ->orderByDesc('featured')
                ->orderBy('sort_order')
                ->orderByDesc('year')
                ->limit($needed)
                ->get();
            $relatedProjects = $sameCategory->concat($extras);
        } else {
            $relatedProjects = $sameCategory;
        }

        return view('pages.project', compact(
            'project',
            'translation',
            'locale',
            'hreflangs',
            'relatedProjects'
        ));
    }

    public function blog()
    {
        $locale   = App::getLocale();
        $articles = Article::where('published', true)
            ->orderBy('position')
            ->with(['translations', 'slugs'])
            ->get();

        return view('pages.blog', compact('articles', 'locale'));
    }

    public function article(string $slug)
    {
        $locale = App::getLocale();

        // OND-160: validate slug per locale (cross-slug duplicate content fix).
        // Slug musí patřit článku v aktuální locale. Pokud slug existuje,
        // ale patří jiné locale, 301 → kanonický slug pro current locale.
        // Bez tohoto check byl každý článek dostupný pod ~3 slug variantami
        // × 3 locale prefixy se self-canonical → duplicate content v Google.
        // OND-204: neaktivní slugy se dohledávají taky — jsou to staré adresy
        // článků, které dostaly nový slug. Aktivní má přednost, pak se níž
        // 301 přesměruje na kanonickou adresu. Bez toho by starý slug 404oval.
        $slugRecord = ArticleSlug::where('slug', $slug)
            ->orderByDesc('active')
            ->first();

        if (! $slugRecord) {
            abort(404);
        }

        $article = Article::where('id', $slugRecord->article_id)
            ->where('published', true)
            ->with(['translations', 'slugs'])
            ->first();

        if (! $article) {
            return $this->redirectRemovedArticle($slugRecord->article_id, $locale);
        }

        // Aktivní slug pro aktuální locale — bez fallbacku na cs, protože
        // pokud článek nemá svou jazykovou variantu, nesmí být dostupný pod
        // cizí locale prefix (jinak by /en/blog/cs-slug renderoval cs obsah).
        $canonical = $article->slugs
            ->where('locale', $locale)
            ->where('active', true)
            ->first();

        if (! $canonical) {
            abort(404);
        }

        if ($canonical->slug !== $slug) {
            return redirect()->route("{$locale}.article", ['slug' => $canonical->slug], 301);
        }

        $translation = $article->translation($locale);

        $hreflangs = [];
        foreach (['cs', 'en', 'de'] as $lang) {
            $localeSlug = $article->slug($lang);
            if ($localeSlug) {
                $hreflangs[$lang] = route("{$lang}.article", ['slug' => $localeSlug]);
            }
        }

        return view('pages.article', compact('article', 'translation', 'locale', 'hreflangs'));
    }

    /**
     * OND-204: mapa přesměrování pro články stažené z blogu (published = 0).
     *
     * Klíč = id staženého článku, hodnota = id článku, na který má stará
     * adresa vést. Co v mapě není, jde na výpis blogu. Mapuje se na id,
     * ne na slug, aby přesměrování sedělo i v EN/DE verzi webu.
     *
     * Zdroj: dokument `blog-texty` (OND-203), tabulka „Mapa přesměrování".
     * 4 = „Co si připravit, než oslovíte vývojáře webu".
     */
    private const REMOVED_ARTICLE_REDIRECTS = [
        7  => 4,  // Design nebo obsah?
        8  => 4,  // Web, který převádí návštěvníky na zákazníky
        11 => 4,  // Jak vytvořit úspěšnou webovou stránku
    ];

    /**
     * Stažený článek: adresa zůstává funkční a 301 vede na nejbližší
     * relevantní stránku. Nikdy 404 — staré adresy mají odkazy zvenčí.
     */
    private function redirectRemovedArticle(int $articleId, string $locale)
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

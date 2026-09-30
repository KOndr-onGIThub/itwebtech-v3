<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Portfolio\PortfolioProject;
use App\Models\Slugs\ArticleSlug;
use App\Support\LegacyArticleRedirect;
use App\Support\LegacyProjectRedirect;
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

        // OND-457 (B-11): tři příklady pod větou o klientech (`home.situation.cases`).
        // Stejný princip jako odkazy u cenových úrovní (OND-359): projekt se
        // načte jedním dotazem, adresa jde z `detailUrl()` a nepublikovaný
        // nebo chybějící projekt se nevykreslí. Pod dva příklady zmizí celý
        // seznam — jeden osamělý příklad by působil jako výjimka, ne vzorek.
        $situationCases = collect();
        $caseItems = __('home.situation.cases');

        if (is_array($caseItems)) {
            $caseProjects = PortfolioProject::published()
                ->whereIn('slug', array_column($caseItems, 'slug'))
                ->with('translations')
                ->get()
                ->keyBy('slug');

            $situationCases = collect($caseItems)
                ->filter(fn (array $case) => $caseProjects->has($case['slug']))
                ->map(fn (array $case) => $case + ['href' => $caseProjects[$case['slug']]->detailUrl()])
                ->values();

            if ($situationCases->count() < 2) {
                $situationCases = collect();
            }
        }

        // OND-231 (F3): prototypové větvení `?podpis=a|b|c|d` z OND-227 je pryč.
        // Vítězná varianta D („Studio" / ACID) je od F3 rovnou `pages.home`.
        return view('pages.home', compact('featuredHomeProjects', 'situationCases'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * OND-359: u každé cenové úrovně stojí odkaz na reálnou případovku.
     *
     * Slugy jsou v `price.tiers[].proof.slug`, ale adresu nestavíme z nich —
     * projekt si načteme a URL vezmeme z `detailUrl()`. Dvě věci se tím řeší
     * naráz: odkaz na nepublikovaný nebo smazaný projekt se nevykreslí vůbec
     * (místo aby vedl na 404 přímo z místa, kde se člověk rozhoduje o ceně)
     * a lokalizovaný slug se použije sám, kdyby ho projekt dostal.
     * Stejný princip jako prázdná mřížka na `/projekty` (OND-351): když data
     * nejsou, prvek zmizí; až budou, vrátí se sám.
     */
    public function price()
    {
        $proofSlugs = array_filter(array_column(
            array_column(__('price.tiers'), 'proof'),
            'slug'
        ));

        $tierProofs = PortfolioProject::published()
            ->whereIn('slug', $proofSlugs)
            ->with('translations')
            ->get()
            ->keyBy('slug');

        return view('pages.price', compact('tierProofs'));
    }

    /**
     * OND-397: /recenze — všechny recenze ve čtyřech skupinách (předloha OND-396).
     *
     * `$groups` = skupina → kartičky, kartička = pole lidí (Cyklocentrum má dva
     * se stejným textem). Seskupení je v `config/reviews.php`, data v
     * `testimonials.php`; id, které v datech chybí, se přeskočí místo 500
     * (úplnost hlídá test).
     *
     * „Více o projektu" vede jen na publikovanou případovku, stejně jako
     * odkazy u cenových úrovní (OND-359): URL z `detailUrl()`, ne ze slugu.
     */
    public function reviews()
    {
        $testimonials = collect(__('testimonials.items'))->keyBy('id');

        $groups = collect(config('reviews.groups'))
            ->map(fn (array $refs) => collect($refs)
                ->map(fn ($ref) => collect((array) $ref)
                    ->map(fn (string $id) => $testimonials->get($id))
                    ->filter()
                    ->values()
                    ->all())
                ->filter()
                ->values()
                ->all());

        $projects = PortfolioProject::published()
            ->whereIn('slug', $testimonials->pluck('project')->filter()->unique()->values())
            ->with('translations')
            ->get()
            ->keyBy('slug');

        return view('pages.reviews', compact('groups', 'projects'));
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

        // Počty pro větu nad přehledem (OND-470): druh s nulou se nenabídne.
        $counts = [
            'all'         => $portfolioProjects->count(),
            'website'     => $portfolioProjects->where('category', 'website')->count(),
            'application' => $portfolioProjects->where('category', 'application')->count(),
            'other'       => $portfolioProjects->where('category', 'other')->count(),
        ];

        // OND-470: obor → publikované slugy (config/portfolio.php). Slug může
        // být ve dvou oborech, obor bez publikovaného projektu vypadne.
        $sectors = collect(config('portfolio.sectors', []))
            ->map(fn (array $slugs) => array_values(array_intersect($slugs, $portfolioProjects->pluck('slug')->all())))
            ->filter()
            ->all();

        return view('pages.projects', compact('portfolioProjects', 'locale', 'counts', 'sectors'));
    }

    public function project(string $url)
    {
        $locale = App::getLocale();

        $relations = [
            'translations',
            'screenshots.translations',
            'tags.translations',
            'outcomes.translations',
        ];

        // OND-209: nejdřív lokalizovaný slug (`/de/projekte/aufmerksamkeits-animation`),
        // pak jazyk-neutrální (`portfolio_projects.slug`). Pořadí je důležité:
        // neutrální slug drží i staré DE/EN adresy, ty se níž přesměrují 301.
        $project = PortfolioProject::published()
            ->whereHas('translations', function ($query) use ($locale, $url) {
                $query->where('locale', $locale)->where('slug', $url);
            })
            ->with($relations)
            ->first()
            ?? PortfolioProject::published()
                ->where('slug', $url)
                ->with($relations)
                ->first();

        if (! $project) {
            // OND-455: starý slug z itwebtech.cz / ondraweb.cz → dnešní
            // případovka, dokud není publikovaná, dočasně na výpis.
            if ($redirect = LegacyProjectRedirect::for($url, $locale)) {
                return $redirect;
            }

            abort(404);
        }

        // 301 na kanonickou adresu pro aktuální locale — staré indexované
        // `/de/projekte/{cs-slug}` tím neztratí sílu odkazů.
        $canonicalSlug = $project->slugFor($locale);
        if ($canonicalSlug !== $url) {
            return redirect()->route("{$locale}.project", ['url' => $canonicalSlug], 301);
        }

        $translation = $project->translation($locale);

        // Hreflang — každý jazyk má vlastní slug (s fallbackem na neutrální).
        $hreflangs = [];
        foreach (['cs', 'en', 'de'] as $lang) {
            $hreflangs[$lang] = $project->detailUrl($lang);
        }

        // Související projekty: 3 kusy, stejná kategorie, vyloučit aktuální.
        // Pokud je < 3 v kategorii, doplníme z ostatních (featured první).
        //
        // OND-265 (audit OND-254): dřív se bralo prvních 2×3 z pevného pořadí,
        // takže všech 14 webových detailů doporučovalo tutéž trojici a zbylých
        // 11 projektů se z doporučení nedalo dostat. Teď se stejné pořadí bere
        // jako kruh a každý detail ukáže tři projekty NÁSLEDUJÍCÍ za sebou —
        // výběr je pořád deterministický (stejná URL = stejné karty, žádný
        // rozjezd cache), ale napříč kategorií se prostřídají všechny.
        $rotate = static function ($pool, $currentId, int $count = 3) {
            $pool = $pool->values();
            if ($pool->isEmpty()) {
                return $pool;
            }
            // Aktuální projekt v poolu = začni hned za ním; když v něm není
            // (doplňování z ostatních kategorií), odvoď start z jeho id, ať
            // se i doplňky střídají místo pořád stejné dvojice.
            $start = $pool->search(fn ($p) => $p->id === $currentId);
            $start = $start === false ? $currentId % $pool->count() : $start + 1;

            return collect(range(0, min($count, $pool->count()) - 1))
                ->map(fn ($i) => $pool[($start + $i) % $pool->count()]);
        };

        $categoryPool = PortfolioProject::published()
            ->where('category', $project->category)
            ->with(['translations', 'screenshots'])
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('year')
            ->get();

        $relatedProjects = $rotate($categoryPool, $project->id)
            ->reject(fn ($p) => $p->id === $project->id)
            ->values();

        if ($relatedProjects->count() < 3) {
            $excluded = $relatedProjects->pluck('id')->push($project->id);
            $extrasPool = PortfolioProject::published()
                ->whereNotIn('id', $excluded)
                ->with(['translations', 'screenshots'])
                ->orderByDesc('featured')
                ->orderBy('sort_order')
                ->orderByDesc('year')
                ->get();
            $extras = $rotate($extrasPool, $project->id, 3 - $relatedProjects->count());
            $relatedProjects = $relatedProjects->concat($extras)->values();
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
        $locale = App::getLocale();

        // OND-217: jen články s aktivním slugem v aktuální locale. Bez filtru
        // sáhne `Article::slug()` po cs fallbacku a výpis odkáže na
        // /de/blog/{cs-slug}, kde article() od OND-160 vrací 404 (kontrola
        // kanonického slugu pro locale). Stejná logika jako
        // SitemapGenerator::collectArticleUrls() — locale bez vlastních slugů
        // vyjde prázdná a zobrazí se `blog.empty` místo mrtvých odkazů.
        $articles = Article::where('published', true)
            ->whereHas('slugs', fn ($query) => $query
                ->where('locale', $locale)
                ->where('active', true))
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
            return LegacyArticleRedirect::removed($slugRecord->article_id, $locale);
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

        // OND-406: závěr článku vede na DALŠÍ článek — následující publikovaný
        // podle `position` (pořadí výpisu /zapisky), za posledním zase první.
        // Stejný filtr jako blog(): jen články s aktivním slugem v této locale,
        // jinak by odkaz vedl na /de/blog/{cs-slug} a 404.
        $siblings = Article::where('published', true)
            ->whereHas('slugs', fn ($query) => $query
                ->where('locale', $locale)
                ->where('active', true))
            ->orderBy('position')
            ->with(['translations', 'slugs'])
            ->get()
            ->values();
        $index = $siblings->search(fn ($a) => $a->id === $article->id);
        $next = ($index !== false && $siblings->count() > 1)
            ? $siblings->get(($index + 1) % $siblings->count())
            : null;

        return view('pages.article', compact('article', 'translation', 'locale', 'hreflangs', 'next'));
    }
}

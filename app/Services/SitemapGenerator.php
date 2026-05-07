<?php

namespace App\Services;

use App\Models\Portfolio\PortfolioProject;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Throwable;

class SitemapGenerator
{
    /**
     * Locales podporované webem. První je default (bez URL prefixu).
     *
     * @var array<int, string>
     */
    protected array $locales = ['cs', 'en', 'de'];

    /**
     * Statické pojmenované routes, které mají {locale}.{page} variantu.
     *
     * @var array<int, string>
     */
    protected array $staticPages = ['home', 'contact', 'price', 'privacy', 'projects', 'blog'];

    /**
     * Vygeneruje sitemap XML jako string.
     */
    public function build(): string
    {
        $sitemap = Sitemap::create();

        $overrides = $this->loadOverrides();
        $excluded  = [];

        foreach ($this->collectStaticUrls() as $entry) {
            $this->addUrl($sitemap, $entry, $overrides, $excluded);
        }

        foreach ($this->collectProjectUrls() as $entry) {
            $this->addUrl($sitemap, $entry, $overrides, $excluded);
        }

        foreach ($this->collectManualEntries() as $entry) {
            $this->addUrl($sitemap, $entry, $overrides, $excluded);
        }

        return $sitemap->render();
    }

    /**
     * Statické routes × všechny locale → URL pole pro sitemap.
     *
     * Vrací prvky se strukturou:
     *  [
     *    'url'        => string absolutní URL,
     *    'page'       => string (např. 'home'),
     *    'lastmod'    => ?Carbon,
     *    'alternates' => array<locale, url>,
     *  ]
     *
     * @return array<int, array<string, mixed>>
     */
    protected function collectStaticUrls(): array
    {
        $entries = [];

        // Pro každou kombinaci locale × page spočítej alternates jednou.
        foreach ($this->staticPages as $page) {
            $alternates = [];
            foreach ($this->locales as $locale) {
                $alternates[$locale] = $this->resolveStaticUrl($page, $locale);
            }

            foreach ($this->locales as $locale) {
                if (! isset($alternates[$locale])) {
                    continue;
                }

                $entries[] = [
                    'url'        => $alternates[$locale],
                    'page'       => $page,
                    'lastmod'    => null,
                    'alternates' => $alternates,
                ];
            }
        }

        return $entries;
    }

    /**
     * Portfolio detail × všechny locale.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function collectProjectUrls(): array
    {
        $entries = [];

        if (! Schema::hasTable('portfolio_projects')) {
            return $entries;
        }

        try {
            $projects = PortfolioProject::published()->get();
        } catch (Throwable $e) {
            return $entries;
        }

        foreach ($projects as $project) {
            $alternates = [];
            foreach ($this->locales as $locale) {
                $alternates[$locale] = route("{$locale}.project", ['url' => $project->slug]);
            }

            $lastmod = $project->updated_at ?? $project->published_at ?? null;

            foreach ($this->locales as $locale) {
                $entries[] = [
                    'url'        => $alternates[$locale],
                    'page'       => 'project',
                    'lastmod'    => $lastmod,
                    'alternates' => $alternates,
                ];
            }
        }

        return $entries;
    }

    /**
     * Ruční záznamy ze SitemapEntry (pokud tabulka/model existuje).
     *
     * @return array<int, array<string, mixed>>
     */
    protected function collectManualEntries(): array
    {
        if (! Schema::hasTable('sitemap_entries')) {
            return [];
        }

        $modelClass = '\\App\\Models\\SitemapEntry';

        if (! class_exists($modelClass)) {
            return [];
        }

        try {
            /** @var \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model> $records */
            $records = $modelClass::query()->where('is_active', true)->get();
        } catch (Throwable $e) {
            return [];
        }

        $entries = [];
        foreach ($records as $record) {
            $url = $record->url ?? null;
            if (! is_string($url) || $url === '') {
                continue;
            }

            $entries[] = [
                'url'        => $url,
                'page'       => $record->page ?? 'fallback',
                'lastmod'    => $record->lastmod ?? $record->updated_at ?? null,
                'priority'   => $record->priority ?? null,
                'changefreq' => $record->changefreq ?? null,
                'alternates' => [],
            ];
        }

        return $entries;
    }

    /**
     * Načti override pipeline mapu (klíčem je URL).
     *
     * Vrací prázdné pole, pokud tabulka/model neexistuje.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function loadOverrides(): array
    {
        if (! Schema::hasTable('sitemap_overrides')) {
            return [];
        }

        $modelClass = '\\App\\Models\\SitemapOverride';

        if (! class_exists($modelClass)) {
            return [];
        }

        try {
            $records = $modelClass::query()->get();
        } catch (Throwable $e) {
            return [];
        }

        $map = [];
        foreach ($records as $record) {
            $url = $record->url ?? null;
            if (! is_string($url) || $url === '') {
                continue;
            }

            $map[$url] = [
                'is_excluded' => (bool) ($record->is_excluded ?? false),
                'priority'    => $record->priority ?? null,
                'changefreq'  => $record->changefreq ?? null,
                'lastmod'     => $record->lastmod ?? null,
            ];
        }

        return $map;
    }

    /**
     * Přidá URL do sitemapy s aplikovaným override + defaulty + hreflang alternates.
     *
     * @param  array<string, array<string, mixed>>  $overrides
     * @param  array<int, string>  $excluded  (referenced by ref pro diagnostiku — nepoužíváno)
     * @param  array<string, mixed>  $entry
     */
    protected function addUrl(Sitemap $sitemap, array $entry, array $overrides, array &$excluded): void
    {
        $url      = $entry['url'];
        $override = $overrides[$url] ?? null;

        if ($override !== null && ($override['is_excluded'] ?? false) === true) {
            $excluded[] = $url;
            return;
        }

        $page = $entry['page'] ?? 'fallback';

        $priority = $override['priority']
            ?? $entry['priority']
            ?? $this->defaultPriority($page);

        $changefreq = $override['changefreq']
            ?? $entry['changefreq']
            ?? $this->defaultChangefreq($page);

        $lastmod = $override['lastmod'] ?? $entry['lastmod'] ?? null;

        $tag = Url::create($url)
            ->setPriority((float) $priority)
            ->setChangeFrequency((string) $changefreq);

        if ($lastmod !== null) {
            $carbon = $this->normalizeDate($lastmod);
            if ($carbon !== null) {
                $tag->setLastModificationDate($carbon);
            }
        }

        // Hreflang alternates (3 jazyky + x-default = cs).
        $alternates = $entry['alternates'] ?? [];
        if (! empty($alternates)) {
            foreach ($alternates as $locale => $altUrl) {
                if (! is_string($altUrl) || $altUrl === '') {
                    continue;
                }
                $tag->addAlternate($altUrl, $locale);
            }

            if (isset($alternates[$this->locales[0]])) {
                $tag->addAlternate($alternates[$this->locales[0]], 'x-default');
            }
        }

        $sitemap->add($tag);
    }

    /**
     * Resolve URL pro statickou stránku v daném locale.
     */
    protected function resolveStaticUrl(string $page, string $locale): string
    {
        return route("{$locale}.{$page}");
    }

    protected function defaultPriority(string $page): float
    {
        $map = config('sitemap.defaults.priority', []);

        return (float) ($map[$page] ?? $map['fallback'] ?? 0.5);
    }

    protected function defaultChangefreq(string $page): string
    {
        $map = config('sitemap.defaults.changefreq', []);

        return (string) ($map[$page] ?? $map['fallback'] ?? 'monthly');
    }

    /**
     * Bezpečně převede vstup na \DateTimeInterface (Carbon).
     */
    protected function normalizeDate(mixed $value): ?\DateTimeInterface
    {
        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            try {
                return \Illuminate\Support\Carbon::parse($value);
            } catch (Throwable $e) {
                return null;
            }
        }

        return null;
    }
}

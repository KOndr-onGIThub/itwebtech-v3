<?php

namespace Database\Seeders;

use App\Models\Portfolio\PortfolioProject;
use App\Models\Portfolio\PortfolioProjectOutcome;
use App\Models\Portfolio\PortfolioProjectOutcomeTranslation;
use App\Models\Portfolio\PortfolioProjectScreenshot;
use App\Models\Portfolio\PortfolioProjectScreenshotTranslation;
use App\Models\Portfolio\PortfolioProjectTranslation;
use App\Models\Portfolio\PortfolioTag;
use App\Models\Portfolio\PortfolioTagTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;

/**
 * Načte `docs/portfolio-data.yaml` a vytvoří všech 24 projektů
 * + překlady (cs/en/de) + screenshoty + outcomes + tagy.
 *
 * Idempotentní: `updateOrCreate` po slug; překlady, screenshoty,
 * outcomes a tagy se synchronizují (smaž & znovu vytvoř pro daný projekt).
 */
class PortfolioSeeder extends Seeder
{
    use WithoutModelEvents;

    private const SUPPORTED_LOCALES = ['cs', 'en', 'de'];

    public function run(): void
    {
        // Ochrana proti omylu: pokud už v DB existují portfolio projekty
        // (typicky upravené ručně přes Filament admin), nikdy nepřepisuj data
        // bez explicitního souhlasu. Re-seed je možný přes
        // PORTFOLIO_SEEDER_FORCE_OVERWRITE=1.
        if (PortfolioProject::query()->exists() && ! env('PORTFOLIO_SEEDER_FORCE_OVERWRITE')) {
            $this->command?->warn(
                'PortfolioSeeder: portfolio už existuje — přeskakuji, aby se nepřepsala '
                .'ručně upravená data. Pro re-seed nastav PORTFOLIO_SEEDER_FORCE_OVERWRITE=1.'
            );
            return;
        }

        $sourcePath = base_path('docs/portfolio-data.yaml');
        if (! is_file($sourcePath)) {
            $this->command?->error("YAML not found: {$sourcePath}");
            return;
        }

        $data = Yaml::parseFile($sourcePath);
        $projects = $data['projects'] ?? [];
        if (empty($projects)) {
            $this->command?->warn('No projects in YAML.');
            return;
        }

        DB::transaction(function () use ($projects) {
            foreach ($projects as $row) {
                $this->seedProject($row);
            }

            // Názvy štítků (OND-223) — až po vytvoření štítků, přepíše
            // humanizované slugy z `seedProject()` a doplní EN/DE.
            (new PortfolioTagNamesSeeder())->run();
        });

        $count = PortfolioProject::count();
        $this->command?->info("Seeded {$count} portfolio projects.");
    }

    private function seedProject(array $row): void
    {
        $slug = $row['slug'] ?? null;
        if (! $slug) {
            return;
        }

        // Hlavní projekt (nepřekládané vlastnosti)
        $project = PortfolioProject::updateOrCreate(
            ['slug' => $slug],
            [
                'category'     => $row['category'] ?? 'other',
                'client_name'  => $row['client_name'] ?? null,
                'live_url'     => $row['live_url'] ?? null,
                'year'         => $row['year'] ?? null,
                'duration'     => $row['duration'] ?? null,
                'featured'     => (bool) ($row['featured'] ?? false),
                'sort_order'   => (int) ($row['sort_order'] ?? 0),
                'published_at' => $row['published_at'] ?? now(),
            ]
        );

        // Překlady — re-sync (smaž + znovu vytvoř)
        $project->translations()->delete();
        foreach (self::SUPPORTED_LOCALES as $locale) {
            $tr = $row['translations'][$locale] ?? null;
            if (! $tr) {
                continue;
            }
            PortfolioProjectTranslation::create([
                'project_id'       => $project->id,
                'locale'           => $locale,
                // OND-209: lokalizovaný slug jen tam, kde je v YAML;
                // jinak NULL = použije se jazyk-neutrální slug projektu.
                'slug'             => $tr['slug'] ?? null,
                'title'            => $tr['title'] ?? '',
                'subtitle'         => $tr['subtitle'] ?? null,
                'summary'          => $tr['summary'] ?? null,
                'description'      => $tr['description'] ?? null,
                'challenge'        => $tr['challenge'] ?? null,
                'solution'         => $tr['solution'] ?? null,
                'result'           => $tr['result'] ?? null,
                'meta_title'       => $tr['meta_title'] ?? null,
                'meta_description' => $tr['meta_description'] ?? null,
            ]);
        }

        // Outcomes (case study odrážky) — re-sync
        $project->outcomes()->delete();
        $perLocaleOutcomes = [];
        foreach (self::SUPPORTED_LOCALES as $locale) {
            $perLocaleOutcomes[$locale] = $row['translations'][$locale]['outcomes'] ?? [];
        }
        // Předpokládáme stejný počet outcomes napříč jazyky (cs jako referenční)
        $referenceOutcomes = $perLocaleOutcomes['cs'] ?? [];
        foreach ($referenceOutcomes as $idx => $_) {
            $outcome = PortfolioProjectOutcome::create([
                'project_id' => $project->id,
                'sort_order' => $idx,
            ]);
            foreach (self::SUPPORTED_LOCALES as $locale) {
                $value = $perLocaleOutcomes[$locale][$idx] ?? null;
                if ($value === null) {
                    continue;
                }
                $text = is_array($value) ? ($value['label'] ?? '') : $value;
                PortfolioProjectOutcomeTranslation::create([
                    'outcome_id'  => $outcome->id,
                    'locale'      => $locale,
                    'label'       => $text,
                    'value'       => '',
                    'description' => is_array($value) ? ($value['description'] ?? null) : null,
                ]);
            }
        }

        // Screenshoty — re-sync, používáme lokální cesty (downloaded přes
        // `php artisan portfolio:fetch-screenshots`).
        $project->screenshots()->delete();
        $screenshotsSource = $row['screenshots_source'] ?? [];
        $typeCounters = [];
        foreach ($screenshotsSource as $idx => $shot) {
            $type = $shot['type'] ?? 'gallery';
            $typeCounters[$type] = ($typeCounters[$type] ?? 0) + 1;
            $n = $typeCounters[$type];
            // OND-256: snímky, které jsme pořídili sami (ne stažené přes
            // `portfolio:fetch-screenshots`), nemají zdrojové `url`, ze kterého
            // se jinak odvozuje přípona. Pro ně je v YAMLu rovnou `path`.
            // `path` je relativní k `resources/img/`, stejně jako sloupec v DB.
            if (! empty($shot['path'])) {
                $relativePath = $shot['path'];
            } else {
                $ext = $this->extensionFromUrl($shot['url'] ?? '');
                $relativePath = "projects/{$slug}/{$type}-{$n}.{$ext}";
            }

            // Pokud lokální soubor neexistuje, screenshot do DB nepřidávej
            // (chrání před tím, aby <x-responsive-image> hodila chybu).
            if (! is_file(resource_path("img/{$relativePath}"))) {
                continue;
            }

            $screenshot = PortfolioProjectScreenshot::create([
                'project_id' => $project->id,
                'path'       => $relativePath,
                'type'       => $type,
                'sort_order' => $idx,
            ]);

            // Překlady screenshotu (alt/caption) — alt MUST být neprázdný (a11y).
            // Hero má v YAMLu specifické popisy; gallery šablonu z titulku projektu.
            $altSource = is_array($shot['alt'] ?? null) ? $shot['alt'] : [];
            $captionSource = is_array($shot['caption'] ?? null) ? $shot['caption'] : [];
            foreach (self::SUPPORTED_LOCALES as $locale) {
                $alt = trim((string) ($altSource[$locale] ?? ''));
                if ($alt === '') {
                    // Fallback z titulku projektu, aby alt nikdy nebyl prázdný.
                    $title = $row['translations'][$locale]['title']
                        ?? $row['translations']['cs']['title']
                        ?? $slug;
                    $label = self::sectionLabel($locale);
                    $alt = $type === 'hero'
                        ? $title
                        : sprintf('%s – %s %d', $title, $label, $n);
                }
                PortfolioProjectScreenshotTranslation::create([
                    'screenshot_id' => $screenshot->id,
                    'locale'        => $locale,
                    'alt'           => $alt,
                    'caption'       => $captionSource[$locale] ?? null,
                ]);
            }
        }

        // Tagy — sync přes pivot
        $tagSlugs = $row['tags'] ?? [];
        $tagIds = [];
        foreach ($tagSlugs as $tagSlug) {
            $tag = PortfolioTag::updateOrCreate(
                ['slug' => $tagSlug],
                ['sort_order' => 0]
            );
            // Pokud tag nemá překlady, doplň cs ze slugu (best-effort default)
            if ($tag->translations()->count() === 0) {
                PortfolioTagTranslation::create([
                    'tag_id' => $tag->id,
                    'locale' => 'cs',
                    'name'   => $this->humanizeSlug($tagSlug),
                ]);
            }
            $tagIds[] = $tag->id;
        }
        $project->tags()->sync($tagIds);
    }

    private function extensionFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true) ? $ext : 'jpg';
    }

    private function humanizeSlug(string $slug): string
    {
        return ucfirst(str_replace('-', ' ', $slug));
    }

    private static function sectionLabel(string $locale): string
    {
        return match ($locale) {
            'en' => 'section',
            'de' => 'Abschnitt',
            default => 'sekce',
        };
    }
}

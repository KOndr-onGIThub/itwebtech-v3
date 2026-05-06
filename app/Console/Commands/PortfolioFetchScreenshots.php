<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Yaml\Yaml;

/**
 * Stáhne všechny screenshoty referencované v `docs/portfolio-data.yaml`
 * do `resources/img/projects/{slug}/{type}-{n}.{ext}`.
 *
 * Idempotentní — soubory, které už existují, přeskočí.
 *
 * Vite imagetools je následně automaticky převede na avif/webp varianty.
 */
class PortfolioFetchScreenshots extends Command
{
    protected $signature = 'portfolio:fetch-screenshots
                            {--source=docs/portfolio-data.yaml : Path to YAML data}
                            {--force : Re-download even if local file exists}';

    protected $description = 'Stáhne portfolio screenshoty z URL v YAMLu do resources/img/projects/{slug}/';

    public function handle(): int
    {
        $sourcePath = base_path($this->option('source'));
        if (! is_file($sourcePath)) {
            $this->error("Source YAML not found: {$sourcePath}");
            return self::FAILURE;
        }

        $data = Yaml::parseFile($sourcePath);
        $projects = $data['projects'] ?? [];
        if (empty($projects)) {
            $this->warn('No projects found in YAML.');
            return self::SUCCESS;
        }

        $force = (bool) $this->option('force');
        $totalDownloaded = 0;
        $totalSkipped = 0;
        $totalFailed = 0;

        foreach ($projects as $project) {
            $slug = $project['slug'] ?? null;
            $screenshots = $project['screenshots_source'] ?? [];
            if (! $slug || empty($screenshots)) {
                continue;
            }

            $dir = resource_path("img/projects/{$slug}");
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            $typeCounters = [];
            foreach ($screenshots as $shot) {
                $url = $shot['url'] ?? null;
                $type = $shot['type'] ?? 'gallery';
                if (! $url) {
                    continue;
                }

                $typeCounters[$type] = ($typeCounters[$type] ?? 0) + 1;
                $n = $typeCounters[$type];

                $ext = $this->extensionFromUrl($url);
                $filename = "{$type}-{$n}.{$ext}";
                $target = "{$dir}/{$filename}";

                if (! $force && is_file($target) && filesize($target) > 0) {
                    $totalSkipped++;
                    continue;
                }

                $this->line("  ↓ {$slug}/{$filename}  ←  {$url}");
                $ok = $this->download($url, $target);
                if ($ok) {
                    $totalDownloaded++;
                } else {
                    $totalFailed++;
                    $this->warn("    failed: {$url}");
                }
            }
        }

        $this->info("Downloaded: {$totalDownloaded}, skipped: {$totalSkipped}, failed: {$totalFailed}");
        return $totalFailed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function extensionFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true) ? $ext : 'jpg';
    }

    private function download(string $url, string $target): bool
    {
        try {
            $response = Http::withOptions(['allow_redirects' => true])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; PortfolioFetcher/1.0)',
                ])
                ->timeout(30)
                ->get($url);

            if (! $response->ok()) {
                return false;
            }

            $body = $response->body();
            if (strlen($body) === 0) {
                return false;
            }

            file_put_contents($target, $body);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

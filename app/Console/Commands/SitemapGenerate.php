<?php

namespace App\Console\Commands;

use App\Services\SitemapGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SitemapGenerate extends Command
{
    protected $signature = 'sitemap:generate {--write : Uloží sitemap také do public/sitemap.xml}';

    protected $description = 'Vygeneruje XML sitemapu (a invaliduje cache).';

    public function handle(SitemapGenerator $generator): int
    {
        $cacheKey = (string) config('sitemap.cache_key', 'sitemap.xml');

        Cache::forget($cacheKey);
        $this->info("Cache key '{$cacheKey}' invalidována.");

        $xml = $generator->build();

        if ($this->option('write')) {
            $path = public_path('sitemap.xml');
            file_put_contents($path, $xml);
            $this->info("Sitemap zapsána do {$path}.");
        }

        $this->info('Sitemap vygenerována (' . strlen($xml) . ' B).');

        return self::SUCCESS;
    }
}

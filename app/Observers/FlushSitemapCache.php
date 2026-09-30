<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * OND-485: po uložení nebo smazání čehokoli, co mění obsah sitemapy, zahodí
 * její cache. Nový článek nebo projekt pak nečeká 10 minut (config/sitemap.php
 * `cache_ttl`), další požadavek na /sitemap.xml ji vygeneruje znovu.
 *
 * Registrace v AppServiceProvider::boot().
 */
class FlushSitemapCache
{
    public function saved(): void
    {
        $this->flush();
    }

    public function deleted(): void
    {
        $this->flush();
    }

    protected function flush(): void
    {
        Cache::forget((string) config('sitemap.cache_key', 'sitemap.xml'));
    }
}

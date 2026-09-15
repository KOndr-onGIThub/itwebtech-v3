<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(SitemapGenerator $generator): Response
    {
        $xml = Cache::remember(
            (string) config('sitemap.cache_key', 'sitemap.xml'),
            (int) config('sitemap.cache_ttl', 600),
            fn () => $generator->build(),
        );

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}

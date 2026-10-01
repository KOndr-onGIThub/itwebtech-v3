<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

/**
 * OND-488 — /projekty přednačítá čtyři soubory písem (IBM Plex a Inter,
 * latin + latin-ext). Bez toho proběhne první layout s náhradním písmem
 * a stránka po příchodu písem poskočí (CLS 0,018, občas 0,11).
 */
class Ond488ProjectsFontPreloadTest extends TestCase
{
    use RefreshDatabase;

    private const PATHS = ['cs' => '/projekty', 'en' => '/en/projects', 'de' => '/de/projekte'];

    private const FONTS = [
        'ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2',
        'ibm-plex-sans/files/ibm-plex-sans-latin-ext-wght-normal.woff2',
        'inter/files/inter-latin-wght-normal.woff2',
        'inter/files/inter-latin-ext-wght-normal.woff2',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_projects_page_preloads_above_the_fold_fonts_in_all_locales(): void
    {
        // Falešný Vite z TestCase vrací prázdné URL; tady potřebujeme vidět,
        // který soubor se přednačítá.
        $this->swap(Vite::class, new class extends Vite
        {
            public function __invoke($entrypoints, $buildDirectory = null)
            {
                return new HtmlString('');
            }

            public function asset($asset, $buildDirectory = null)
            {
                return '/build/' . $asset;
            }
        });

        foreach (self::PATHS as $locale => $path) {
            $body = $this->get($path)->assertOk()->getContent();

            preg_match_all('#<link rel="preload" as="font" type="font/woff2" crossorigin href="/build/node_modules/@fontsource-variable/([^"]+)">#', $body, $m);
            $this->assertSame(self::FONTS, $m[1], "Přednačtená písma ({$locale}).");
        }
    }

    public function test_preloaded_fonts_are_the_ones_the_stylesheets_import(): void
    {
        // Kdyby se písmo v CSS vyměnilo, preload by stahoval zbytečný soubor
        // a posun by se vrátil.
        $this->assertStringContainsString("@import '@fontsource-variable/inter';", file_get_contents(resource_path('css/app.css')));
        $this->assertStringContainsString("@import '@fontsource-variable/ibm-plex-sans/wght.css';", file_get_contents(resource_path('css/foundation/design-system.css')));
    }
}

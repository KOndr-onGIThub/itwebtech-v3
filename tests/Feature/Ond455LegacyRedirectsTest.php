<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * OND-455 — přechod na ondraweb.cz: staré domény 301 na kanonický host
 * a staré adresy z sitemap `itwebtech.cz` a `ondraweb.cz` (Framer) nesmí
 * po skocích skončit na 404.
 */
class Ond455LegacyRedirectsTest extends TestCase
{
    use RefreshDatabase;

    private const CANONICAL = 'ondraweb.cz';

    /** Stará cesta → cíl. Deset adres ze zadání + `delejme-animace` z navigace itwebtech.cz + Framer `/dekuji` + tři z OND-466. */
    private const LEGACY_PATHS = [
        '/sluzby'                            => ['/#section-services', 301],
        // OND-503: `/projekty/zoomorava` je publikovaná případovka (200 bez
        // skoku), hlídá ji test_published_zoomorava_is_served_without_redirect.
        // OND-470: článek a cedule odpublikované → dočasně na PitArenu.
        '/projects/clanek-na-motorkari-cz'   => ['/projekty/pitarena', 302],
        '/projects/FRLcreator'               => ['/projekty/frl-creator', 301],
        '/projects/kempveselka'              => ['/projekty/kemp-veselka', 301],
        '/projects/logo-realitacky'          => ['/projekty', 302],
        '/projects/pitarena-akademie-202308' => ['/projekty/pitarena', 302],
        '/projects/pitarena-reklamni-cedule' => ['/projekty/pitarena', 302],
        // OND-470: odpublikované, stejný slug jako na starém webu → výpis.
        '/projects/yolk'                     => ['/projekty', 302],
        '/projects/elektro-srnak'            => ['/projekty', 302],
        '/projects/strechyzajic'             => ['/projekty/strechy-zajic', 301],
        '/projects/vpindustry'               => ['/projekty/vp-industry', 301],
        '/projects/delejme-animace'          => ['/projekty', 302],
        '/dekuji'                            => ['/', 301],
        // OND-466: zbylé adresy starého itwebtech.cz (QA na OND-465).
        '/about'                             => ['/o-mne', 301],
        '/reference'                         => ['/projekty', 301],
        '/servis'                            => ['/#section-services', 301],
    ];

    /**
     * Celá tabulka stránek Framer webu `ondraweb.cz` (routy z jeho skriptů
     * + CMS slugy ze sitemapy, 28. 9. 2026). Framer web zanikne, žádná
     * z těch adres nesmí na novém webu skončit na 404.
     */
    private const FRAMER_PATHS = [
        '/', '/kontakt', '/sluzby', '/projekty', '/recenze', '/dekuji',
        '/projekty/barana', '/projekty/nove-interiery', '/projekty/zoomorava',
        '/projekty/zubni-provazek', '/projekty/cyklocentrum', '/projekty/kemp-veselka',
        '/projekty/pitarena', '/projekty/vp-industry', '/projekty/strechy-zajic',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    // ------------------------------------------------------------------
    // 1. Staré domény
    // ------------------------------------------------------------------

    public function test_legacy_host_redirects_to_canonical_with_path_and_query(): void
    {
        $this->enableCanonicalHost();

        foreach (['itwebtech.cz', 'www.itwebtech.cz', 'www.ondraweb.cz'] as $host) {
            $this->get("http://{$host}/projekty/kemp-veselka?utm_source=x&a=1")
                ->assertStatus(301)
                ->assertHeader('Location', 'https://ondraweb.cz/projekty/kemp-veselka?utm_source=x&a=1');

            $this->get("https://{$host}/")
                ->assertStatus(301)
                ->assertHeader('Location', 'https://ondraweb.cz/');
        }
    }

    public function test_canonical_host_is_served(): void
    {
        $this->enableCanonicalHost();

        $this->get('https://ondraweb.cz/projekty')->assertOk();
        $this->get('https://ondraweb.cz/')->assertOk();
    }

    public function test_health_check_and_contact_post_are_not_redirected(): void
    {
        $this->enableCanonicalHost();

        // Health check kontejneru jde na localhost/127.0.0.1.
        $this->get('http://localhost/up')->assertOk();
        $this->get('http://127.0.0.1/up')->assertOk();

        // Prázdný formulář na kanonickém hostu = validace, ne přesměrování.
        $this->postJson('https://ondraweb.cz/contact', [])->assertStatus(422);
    }

    public function test_empty_canonical_host_disables_redirect(): void
    {
        config([
            'redirects.canonical_host' => '',
            'redirects.legacy_hosts' => ['itwebtech.cz', 'www.itwebtech.cz', 'www.ondraweb.cz'],
        ]);

        $this->get('https://itwebtech.cz/projekty')->assertOk();
    }

    public function test_unlisted_host_is_not_redirected(): void
    {
        $this->enableCanonicalHost();

        // Subdoménu si CEO přidá do env až po stabilizaci.
        $this->get('https://itwebtech.ondrejkriska.cz/projekty')->assertOk();
    }

    public function test_default_legacy_hosts_do_not_include_subdomain(): void
    {
        $hosts = require base_path('config/redirects.php');

        $this->assertSame(['itwebtech.cz', 'www.itwebtech.cz', 'www.ondraweb.cz'], $hosts['legacy_hosts']);
    }

    // ------------------------------------------------------------------
    // 2. Staré cesty
    // ------------------------------------------------------------------

    public function test_legacy_paths_redirect_in_one_hop_to_existing_page(): void
    {
        foreach (self::LEGACY_PATHS as $old => [$target, $status]) {
            $response = $this->get($old);
            $response->assertStatus($status);
            // `url('/')` vrací host bez koncového lomítka, redirect na homepage s ním.
            $this->assertSame(rtrim(url($target), '/'), rtrim($response->headers->get('Location'), '/'), $old);

            $this->get($target)->assertOk();
        }
    }

    public function test_legacy_paths_on_legacy_host_end_on_200_within_two_hops(): void
    {
        $this->enableCanonicalHost();

        foreach (array_keys(self::LEGACY_PATHS) as $old) {
            [$response, $hops] = $this->follow('https://itwebtech.cz'.$old);

            $response->assertOk();
            $this->assertLessThanOrEqual(2, $hops, $old);
        }
    }

    public function test_every_framer_page_ends_on_200_within_one_hop(): void
    {
        $this->enableCanonicalHost();

        foreach (self::FRAMER_PATHS as $old) {
            [$response, $hops] = $this->follow('https://ondraweb.cz'.$old);

            $response->assertOk();
            $this->assertLessThanOrEqual(1, $hops, $old);
        }
    }

    public function test_old_contact_goes_to_czech_contact(): void
    {
        $this->get('/contact')->assertStatus(301)->assertRedirect(url('/kontakt'));
    }

    /**
     * OND-503: stará Framer adresa `/projekty/zoomorava` je dnes přímo
     * případovka — 200 bez přesměrování, ve všech jazycích, i ze staré
     * domény itwebtech.cz (jeden skok na kanonický host, žádná smyčka).
     */
    public function test_published_zoomorava_is_served_without_redirect(): void
    {
        foreach (['/projekty/zoomorava', '/en/projects/zoomorava', '/de/projekte/zoomorava'] as $path) {
            $this->get($path)->assertOk()->assertSee('ZOOMORAVA');
        }

        $this->get('/projects/zoomorava')->assertStatus(301)->assertRedirect(url('/projekty/zoomorava'));

        $this->enableCanonicalHost();
        [$response, $hops] = $this->follow('https://itwebtech.cz/projekty/zoomorava');
        $response->assertOk();
        $this->assertSame(1, $hops);
    }

    /**
     * Ondřej 28. 9.: případovku může doplnit později. Po publikaci pod slugem
     * z mapy se přesměrování přepne samo na 301 na případovku — bez změny
     * kódu. Do té doby 302, které si prohlížeč nezapamatuje.
     */
    public function test_redirect_switches_to_project_once_it_is_published(): void
    {
        // OND-503: zoomorava publikovaná; po odpublikování (koncept) zase
        // dočasně na výpis, ne 404 a ne smyčka sama na sebe.
        $project = PortfolioProject::where('slug', 'zoomorava')->firstOrFail();
        $project->update(['published_at' => null]);

        $this->get('/projekty/zoomorava')->assertStatus(302)->assertRedirect(url('/projekty'));

        $project->update(['published_at' => now()->subMinute()]);

        $this->get('/projekty/zoomorava')->assertOk();

        // Totéž pro staré adresy itwebtech.cz: zamýšlený cíl má přednost před náhradou.
        PortfolioProject::where('slug', 'video-pitbike-akademie')->update(['published_at' => now()->subMinute()]);
        $this->get('/projects/pitarena-akademie-202308')
            ->assertStatus(301)
            ->assertRedirect(url('/projekty/video-pitbike-akademie'));
    }

    public function test_every_mapped_target_is_a_known_project(): void
    {
        foreach (config('redirects.project_slugs') as $old => $slugs) {
            foreach ((array) $slugs as $slug) {
                // OND-503: i `zoomorava` už v DB je (publikované i ne).
                $this->assertTrue(PortfolioProject::where('slug', $slug)->exists(), "{$old} → {$slug} v DB není");
            }
        }
    }

    public function test_unknown_old_project_slug_still_goes_to_projekty(): void
    {
        $this->get('/projects/josefopa')->assertRedirect(url('/projekty/josefopa'));
        $this->get('/projekty/neexistuje')->assertNotFound();
    }

    private function enableCanonicalHost(): void
    {
        config([
            'redirects.canonical_host' => self::CANONICAL,
            'redirects.legacy_hosts' => ['itwebtech.cz', 'www.itwebtech.cz', 'www.ondraweb.cz'],
        ]);
    }

    /** @return array{TestResponse, int} */
    private function follow(string $url): array
    {
        $hops = 0;
        $response = $this->get($url);

        while ($response->isRedirect() && $hops < 5) {
            $hops++;
            $response = $this->get($response->headers->get('Location'));
        }

        return [$response, $hops];
    }
}

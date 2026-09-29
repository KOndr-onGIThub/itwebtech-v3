<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Slugs\ArticleSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * OND-466 — staré adresy článků z itwebtech.cz (`/jak-na-to/{slug}`) vedou
 * na konečný článek jedním 301, z itwebtech.cz tedy dvěma skoky (host + cesta).
 * Dřív tři: host → `/zapisky/{starý slug}` → `/zapisky/{nový slug}`.
 *
 * Fixture napodobuje produkci: články 4 a 6 mají nový slug a starý neaktivní,
 * článek 8 je stažený a sloučený do 11.
 */
class Ond466LegacyArticleRedirectsTest extends TestCase
{
    use RefreshDatabase;

    /** id => [published, [cs slug => active]] */
    private const FIXTURE = [
        4  => [true, ['jak-definovat-pozadavky-na-vyvoj-webove-stranky' => false, 'jak-se-pripravit-na-novy-web' => true]],
        6  => [true, ['jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony' => false, 'kdy-se-vyplati-aplikace-na-miru' => true]],
        8  => [false, ['web-ktery-prevadi-navstevniky-na-zakazniky' => true]],
        11 => [true, ['jak-vytvorit-uspesnou-webovou-stranku' => true]],
    ];

    /** Stará cesta z itwebtech.cz → konečný článek (zadání OND-466, body 1–3). */
    private const LEGACY_PATHS = [
        '/jak-na-to/jak-definovat-pozadavky-na-vyvoj-webove-stranky'                 => '/zapisky/jak-se-pripravit-na-novy-web',
        '/jak-na-to/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony' => '/zapisky/kdy-se-vyplati-aplikace-na-miru',
        '/jak-na-to/web-ktery-prevadi-navstevniky-na-zakazniky'                      => '/zapisky/jak-vytvorit-uspesnou-webovou-stranku',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (self::FIXTURE as $id => [$published, $slugs]) {
            $article = new Article(['slug' => array_key_last($slugs), 'published' => $published]);
            $article->id = $id;
            $article->save();

            $article->translations()->create([
                'locale'      => 'cs',
                'active'      => true,
                'title'       => "Titulek {$id}",
                'perex'       => '<p>Perex.</p>',
                'content_1'   => '<p>Text.</p>',
                'content_2'   => '<p>Text.</p>',
                'img_preview' => "preview-{$id}.jpg",
                'img_main'    => "main-{$id}.jpg",
                'img_mid'     => 'SEO.webp',
            ]);

            foreach ($slugs as $slug => $active) {
                ArticleSlug::create(['article_id' => $id, 'locale' => 'cs', 'slug' => $slug, 'active' => $active]);
            }
        }
    }

    public function test_old_article_urls_redirect_to_final_article_in_one_hop(): void
    {
        foreach (self::LEGACY_PATHS as $old => $target) {
            $this->get($old)->assertStatus(301)->assertRedirect(url($target));
            $this->get($target)->assertOk();
        }
    }

    public function test_old_article_urls_on_itwebtech_end_on_200_in_two_hops(): void
    {
        config([
            'redirects.canonical_host' => 'ondraweb.cz',
            'redirects.legacy_hosts' => ['itwebtech.cz', 'www.itwebtech.cz', 'www.ondraweb.cz'],
        ]);

        foreach (self::LEGACY_PATHS as $old => $target) {
            [$response, $hops, $last] = $this->follow('https://itwebtech.cz'.$old);

            $response->assertOk();
            $this->assertSame(2, $hops, $old);
            $this->assertSame('https://ondraweb.cz'.$target, $last, $old);
        }
    }

    public function test_old_blog_prefix_also_resolves_in_one_hop(): void
    {
        $this->get('/blog/jak-definovat-pozadavky-na-vyvoj-webove-stranky')
            ->assertStatus(301)
            ->assertRedirect(url('/zapisky/jak-se-pripravit-na-novy-web'));
    }

    public function test_current_and_unknown_slugs_keep_previous_behaviour(): void
    {
        $this->get('/jak-na-to/jak-se-pripravit-na-novy-web')
            ->assertStatus(301)
            ->assertRedirect(url('/zapisky/jak-se-pripravit-na-novy-web'));

        // Neznámý slug jde dál beze změny a skončí na 404 jako dřív.
        $this->get('/jak-na-to/neexistuje')->assertStatus(301)->assertRedirect(url('/zapisky/neexistuje'));
        $this->get('/zapisky/neexistuje')->assertNotFound();
    }

    public function test_removed_article_without_target_goes_to_listing(): void
    {
        Article::whereKey(11)->update(['published' => false]);

        $this->get('/jak-na-to/web-ktery-prevadi-navstevniky-na-zakazniky')
            ->assertStatus(301)
            ->assertRedirect(url('/zapisky'));
    }

    /** @return array{TestResponse, int, string} */
    private function follow(string $url): array
    {
        $hops = 0;
        $response = $this->get($url);

        while ($response->isRedirect() && $hops < 5) {
            $hops++;
            $url = $response->headers->get('Location');
            $response = $this->get($url);
        }

        return [$response, $hops, $url];
    }
}

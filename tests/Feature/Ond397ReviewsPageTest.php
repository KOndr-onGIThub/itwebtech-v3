<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * OND-397 — /recenze (10. stránka). Předloha OND-396, data inventura OND-395.
 *
 * Hlavní tvrzení:
 *  1. Stránka existuje ve třech jazycích a je v sitemapě — `/recenze` je
 *     v indexu Googlu z Framer webu a vracela 404.
 *  2. ŽÁDNÁ RECENZE TIŠE NECHYBÍ: každé `id` z `testimonials.php` je
 *     v `config/reviews.php` právě jednou a každá má odkaz na originál.
 *  3. JEDNO ČÍSLO: H1 stránky, pruh na homepage i součet profilů říkají 26.
 *     Číslo 21 (počet lidí na staré Framer stránce) nikde nezůstalo.
 */
class Ond397ReviewsPageTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = [
        'cs' => '/recenze',
        'en' => '/en/reviews',
        'de' => '/de/bewertungen',
    ];

    /** Pole, která se nepřekládají a musí být ve všech jazycích stejná. */
    private const SHARED_FIELDS = ['id', 'name', 'image', 'initials', 'source', 'url', 'project'];

    private function items(string $locale): array
    {
        return trans('testimonials.items', [], $locale);
    }

    /** @return array<int, string> všechna id z config/reviews.php v pořadí stránky */
    private function groupedIds(): array
    {
        $ids = [];
        foreach (config('reviews.groups') as $refs) {
            foreach ($refs as $ref) {
                array_push($ids, ...(array) $ref);
            }
        }

        return $ids;
    }

    public function test_all_three_locales_render_with_title(): void
    {
        $expected = [
            'cs' => 'Recenze — Ondřej Kriška, ONDRAWEB',
            'en' => 'Reviews — Ondřej Kriška, ONDRAWEB',
            'de' => 'Bewertungen — Ondřej Kriška, ONDRAWEB',
        ];

        foreach (self::PAGES as $locale => $url) {
            $this->assertSame(url($url), route("{$locale}.reviews"));
            $this->get($url)->assertOk()->assertSee('<title>'.$expected[$locale].'</title>', false);
        }
    }

    public function test_sitemap_contains_reviews_page_in_all_locales(): void
    {
        Cache::forget(config('sitemap.cache_key'));

        $body = $this->get('/sitemap.xml')->assertOk()->getContent();

        foreach (array_keys(self::PAGES) as $locale) {
            $this->assertStringContainsString(route("{$locale}.reviews").'<', $body, "V sitemapě chybí /recenze pro {$locale}.");
        }
    }

    public function test_every_review_is_grouped_exactly_once(): void
    {
        $grouped = $this->groupedIds();

        $this->assertSame(array_unique($grouped), $grouped, 'Recenze je v config/reviews.php víckrát.');

        foreach (array_keys(self::PAGES) as $locale) {
            $ids = array_column($this->items($locale), 'id');
            sort($ids);
            $sorted = $grouped;
            sort($sorted);
            $this->assertSame($ids, $sorted, "config/reviews.php a testimonials.php ({$locale}) nemají stejná id.");
        }
    }

    public function test_data_shape_is_shared_across_locales(): void
    {
        $cs = $this->items('cs');
        $this->assertCount(22, $cs);
        $this->assertSame(22, trans('testimonials.meta', [], 'cs')['total']);

        foreach (['en', 'de'] as $locale) {
            $other = $this->items($locale);
            $this->assertCount(count($cs), $other);
            $this->assertSame(22, trans('testimonials.meta', [], $locale)['total']);

            foreach ($cs as $i => $item) {
                foreach (self::SHARED_FIELDS as $field) {
                    $this->assertSame(
                        $item[$field] ?? null,
                        $other[$i][$field] ?? null,
                        "{$item['id']}.{$field} se v {$locale} liší od cs."
                    );
                }
            }
        }

        foreach ($cs as $item) {
            $this->assertStringStartsWith('https://', $item['url'] ?? '', "{$item['id']} nemá odkaz na originál.");
            $this->assertContains($item['source'], ['google', 'firmy_cz', 'facebook']);
        }

        // Holcmann musí být před YCF CUP: citace na případovce `pitarena`
        // bere první recenzi s daným `project` (předloha OND-396 §4, V3).
        $ids = array_column($cs, 'id');
        $this->assertLessThan(array_search('ycf-cup', $ids), array_search('stanislav-holcmann', $ids));
    }

    public function test_every_card_links_to_its_original(): void
    {
        foreach (self::PAGES as $locale => $url) {
            app()->setLocale($locale);
            $html = $this->get($url)->assertOk()->getContent();

            foreach ($this->items($locale) as $item) {
                $this->assertStringContainsString(
                    'href="'.e($item['url']).'" target="_blank" rel="noopener">'.e(__('reviews.original.'.$item['source'])),
                    $html,
                    "{$locale}: {$item['name']} nemá odkaz na originál."
                );
                $this->assertStringContainsString(e($item['name']), $html);
            }

            // 22 lidí, 21 kartiček (Cyklocentrum = jedna kartička, dva odkazy).
            $this->assertSame(21, substr_count($html, '<article class="pd-testi__item"'));
            $this->assertSame(22, substr_count($html, '↗</a>') - count(config('reviews.profiles')));
            $this->assertStringNotContainsString('id="petr-knourek"', $html);

            foreach (config('reviews.profiles') as $profile) {
                $this->assertStringContainsString('href="'.e($profile['url']).'"', $html);
            }

            // Jediná acidová výzva na stránce.
            $this->assertSame(1, substr_count($html, 'class="pd-cta"'));
        }
    }

    public function test_one_number_everywhere(): void
    {
        $total = array_sum(array_column(config('reviews.profiles'), 'count'));
        $this->assertSame(26, $total);

        foreach (array_keys(self::PAGES) as $locale) {
            foreach (['reviews.heading_html', 'reviews.meta.description', 'home.social_proof.reviews', 'home.meta.description'] as $key) {
                $this->assertStringContainsString((string) $total, trans($key, [], $locale), "{$locale}: {$key}");
            }
        }

        foreach (['/', '/en/', '/de/', '/cenik', '/en/price', '/de/preisliste', '/'.config('landing.preview_path')] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertDoesNotMatchRegularExpression('/\b21\s+(recenz|review|Bewert|hodnoc)/iu', $html, "{$url} pořád tvrdí 21.");
        }
    }

    public function test_homepage_links_to_reviews_page(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $home = $locale === 'cs' ? '/' : "/{$locale}/";
            app()->setLocale($locale);
            $html = $this->get($home)->assertOk()->getContent();

            // V1: hodnocení v pruhu čísel je odkaz.
            $this->assertMatchesRegularExpression(
                '#<a href="'.preg_quote(url($url), '#').'" class="pd-strip__link">.*?'.preg_quote(e(__('home.social_proof.reviews')), '#').'</a>#s',
                $html
            );
            // V2: „Všechna hodnocení →" pod trojicí recenzí.
            $this->assertStringContainsString(
                '<a href="'.url($url).'" class="pd-more__link">'.e(__('reviews.all')).'</a>',
                $html
            );
            // Toman s dnešním textem o BARANĚ, ne s textem o pralinkách.
            $toman = collect($this->items($locale))->firstWhere('id', 'rostislav-toman');
            $this->assertSame('BARANA', $toman['company']);
            $this->assertStringContainsString(e($toman['text']), $html);
        }
    }
}

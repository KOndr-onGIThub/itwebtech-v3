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
 *  4. ČÍSLO JDE PŘEPOČÍTAT (OND-444, B-10): kdo hodnotil na Googlu i na
 *     Firmy.cz, má na kartičce oba odkazy na originál. Úvod neříká
 *     „Všechny recenze" a nic se nevysvětluje větou.
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
        $this->assertCount(23, $cs);
        $this->assertSame(23, trans('testimonials.meta', [], 'cs')['total']);

        foreach (['en', 'de'] as $locale) {
            $other = $this->items($locale);
            $this->assertCount(count($cs), $other);
            $this->assertSame(23, trans('testimonials.meta', [], $locale)['total']);

            foreach ($cs as $i => $item) {
                foreach (self::SHARED_FIELDS as $field) {
                    $this->assertSame(
                        $item[$field] ?? null,
                        $other[$i][$field] ?? null,
                        "{$item['id']}.{$field} se v {$locale} liší od cs."
                    );
                }

                // OND-444: druhé odkazy — zdroj, URL i to, jestli nesou
                // vlastní citát, jsou ve všech jazycích stejné.
                $shape = fn (array $entry) => [$entry['source'], $entry['url'], filled($entry['text'] ?? null)];
                $this->assertSame(
                    array_map($shape, $item['also'] ?? []),
                    array_map($shape, $other[$i]['also'] ?? []),
                    "{$item['id']}.also se v {$locale} liší od cs."
                );
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

            // 23 lidí, 22 kartiček (Cyklocentrum = jedna kartička, dva odkazy).
            $this->assertSame(22, substr_count($html, '<article class="pd-testi__item"'));
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

    /**
     * OND-444 (B-10): číslo v nadpisu jde přepočítat z odkazů. Google 13
     * (14. hodnocení je Jaskmanická, Google ji bez přihlášení neukáže),
     * Firmy.cz 12, Facebook 2 (mimo číslo). Toman, Kroulík, Horký a Antoš
     * mají oba odkazy, Jaskmanická jen Firmy.cz.
     */
    public function test_original_links_add_up_per_source(): void
    {
        $expected = ['google' => 13, 'firmy_cz' => 12, 'facebook' => 2];
        $firmy = config('reviews.profiles.firmy_cz.url');

        foreach (self::PAGES as $locale => $url) {
            app()->setLocale($locale);
            $html = $this->get($url)->assertOk()->getContent();

            foreach ($expected as $source => $count) {
                $this->assertSame(
                    $count,
                    substr_count($html, 'target="_blank" rel="noopener">'.e(__('reviews.original.'.$source)).' ↗</a>'),
                    "{$locale}: odkazů „{$source}“ má být {$count}."
                );
            }
            $this->assertSame(array_sum($expected), substr_count($html, '↗</a>') - count(config('reviews.profiles')));

            foreach (['rostislav-toman', 'petr-kroulik', 'ales-horky', 'roman-antos'] as $id) {
                $card = $this->card($html, $id);
                $this->assertStringContainsString('>'.e(__('reviews.original.google')).' ↗</a>', $card, "{$locale}: {$id} bez Googlu.");
                $this->assertStringContainsString('href="'.e($firmy).'" target="_blank" rel="noopener">'.e(__('reviews.original.firmy_cz')).' ↗</a>', $card, "{$locale}: {$id} bez Firmy.cz.");
            }
            $this->assertStringNotContainsString(e(__('reviews.original.google')), $this->card($html, 'hana-jaskmanicka'));
        }
    }

    /** Antoš: dva citáty, novější z Firmy.cz první, pod každým jeho odkaz. */
    public function test_antos_has_two_quotes_each_with_its_own_link(): void
    {
        foreach (self::PAGES as $locale => $url) {
            app()->setLocale($locale);
            $card = $this->card($this->get($url)->assertOk()->getContent(), 'roman-antos');
            $antos = collect($this->items($locale))->firstWhere('id', 'roman-antos');

            $this->assertSame(2, substr_count($card, '<p class="pd-testi__text">'));
            $this->assertSame(2, substr_count($card, '<p class="pd-testi__links">'));

            $firmyQuote = strpos($card, e($antos['also'][0]['text']));
            $firmyLink  = strpos($card, '>'.e(__('reviews.original.firmy_cz')).' ↗</a>');
            $googleQuote = strpos($card, e($antos['text']));
            $googleLink  = strpos($card, '>'.e(__('reviews.original.google')).' ↗</a>');

            $this->assertNotFalse($firmyQuote);
            $this->assertNotFalse($googleQuote);
            $this->assertTrue($firmyQuote < $firmyLink && $firmyLink < $googleQuote && $googleQuote < $googleLink, "{$locale}: pořadí citátů a odkazů u Antoše.");
        }

        $this->assertSame('Velice profesionální a zároveň lidský přístup.', collect($this->items('cs'))->firstWhere('id', 'roman-antos')['also'][0]['text']);
    }

    /** Radka Podaná (Facebook): bez firmy a role — žádný prázdný řádek ani pomlčka. */
    public function test_facebook_review_without_company_has_no_empty_line(): void
    {
        foreach (self::PAGES as $locale => $url) {
            app()->setLocale($locale);
            $card = $this->card($this->get($url)->assertOk()->getContent(), 'radka-podana');

            $this->assertStringContainsString('Radka Podaná', $card);
            $this->assertStringContainsString('href="https://www.facebook.com/ondraweb/reviews"', $card);
            $this->assertStringNotContainsString('pd-by__org', $card);
            $this->assertStringNotContainsString(' — ', $card);
        }

        $working = config('reviews.groups.working');
        $this->assertSame('radka-podana', $working[array_search('roman-antos', $working) + 1]);
    }

    /** Úvod a meta popis bez „Všechny recenze" a bez vysvětlující věty; nadpis beze změny. */
    public function test_intro_does_not_claim_all_reviews(): void
    {
        $gone = ['cs' => 'Všechny recenze', 'en' => 'Every review in one place', 'de' => 'Alle Bewertungen an einem Ort'];
        $heading = [
            'cs' => '5,0 z <em>26 hodnocení</em> na Googlu a Firmy.cz',
            'en' => trans('reviews.heading_html', [], 'en'),
            'de' => trans('reviews.heading_html', [], 'de'),
        ];

        foreach (self::PAGES as $locale => $url) {
            app()->setLocale($locale);
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringNotContainsString($gone[$locale], $html);
            $this->assertStringNotContainsString('Všechny recenze', $html);
            $this->assertStringContainsString(e(__('reviews.intro')), $html);
            $this->assertStringContainsString($heading[$locale], $html);
        }

        $this->assertSame('Co o spolupráci napsali klienti. U každé recenze je odkaz na originál.', trans('reviews.intro', [], 'cs'));
    }

    /** HTML jedné kartičky podle `id`. */
    private function card(string $html, string $id): string
    {
        $this->assertSame(1, preg_match('#<article class="pd-testi__item" id="'.preg_quote($id, '#').'">(.*?)</article>#s', $html, $m), "Kartička {$id} chybí.");

        return $m[1];
    }
}

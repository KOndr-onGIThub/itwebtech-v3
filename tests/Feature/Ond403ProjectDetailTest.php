<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-403 — detail projektu ve slovníku nové homepage (předloha OND-402).
 *
 * Jedna šablona, 21 případovek × 3 jazyky. Tohle se na webu už jednou tiše
 * rozbilo (OND-352), proto se tu prochází každý publikovaný detail.
 *
 * Recenzi klienta vybírá pořadí v `lang/{locale}/testimonials.php`: první
 * položka, jejíž `project` je slug projektu. Cvrček stojí před Kňourkem
 * (Cyklocentrum), Holcmann před YCF CUP (PitArena). Přerovnání souboru by
 * to tiše otočilo — hlídá to test níž.
 */
class Ond403ProjectDetailTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = ['cs', 'en', 'de'];

    private const SIGNATURE = ' — Ondřej Kriška, ONDRAWEB';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_every_published_detail_renders_in_all_locales(): void
    {
        $projects = PortfolioProject::published()->with('translations')->get();
        $this->assertNotEmpty($projects);

        foreach ($projects as $project) {
            foreach (self::LOCALES as $locale) {
                $body = $this->get($project->detailUrl($locale))->assertOk()->getContent();
                $translation = $project->translations->firstWhere('locale', $locale);
                $label = "{$project->slug} ({$locale})";

                $this->assertStringContainsString(
                    '<h1 class="pd-heading pd-heading--sub" style="view-transition-name: ' . project_transition_name($project->slug, 'title') . '">' . e($translation?->title ?? $project->slug) . '</h1>',
                    $body,
                    "H1 {$label}."
                );
                $this->assertStringContainsString('<div class="pd-story">', $body, "Text případovky {$label}.");
                $this->assertMatchesRegularExpression(
                    '/<title>[^<]+' . preg_quote(e(self::SIGNATURE), '/') . '<\/title>/u',
                    $body,
                    "Titulek s podpisem {$label}."
                );
                $this->assertSame(1, substr_count($body, 'class="pd-cta"'), "Jediné acidové tlačítko {$label}.");
                $this->assertStringContainsString(
                    '<a href="' . lroute('projects', $locale) . '" class="pd-more__link">',
                    $body,
                    "Cesta zpět do katalogu {$label}."
                );
            }
        }
    }

    public function test_client_review_is_the_first_testimonial_of_the_project(): void
    {
        $projects = PortfolioProject::published()->with('translations')->get();
        $withReview = 0;

        foreach ($projects as $project) {
            foreach (self::LOCALES as $locale) {
                $expected = collect(trans('testimonials.items', [], $locale))
                    ->first(fn ($t) => ($t['project'] ?? null) === $project->slug);

                $body = $this->get($project->detailUrl($locale))->assertOk()->getContent();
                $review = $this->reviewBlock($body);
                $label = "{$project->slug} ({$locale})";

                if (! $expected) {
                    $this->assertNull($review, "Detail {$label} nemá mít recenzi.");
                    continue;
                }

                $withReview++;
                $this->assertNotNull($review, "Detail {$label} má mít recenzi.");
                $this->assertStringContainsString(e($expected['name']), $review, "Recenzent {$label}.");
                $this->assertStringContainsString(
                    'href="' . lroute('reviews', $locale) . '"',
                    $review,
                    "Odkaz na všechna hodnocení {$label}."
                );
            }
        }

        // Předloha OND-402 §4a: recenzi svého klienta mělo 10 případovek.
        // OND-470 odpublikoval YOLK a Elektro Srnák — veřejných zbývá 8.
        // OND-501 přidal Vinařství Antoš s recenzí Romana Antoše — 9.
        $this->assertSame(9 * count(self::LOCALES), $withReview);
    }

    public function test_review_order_picks_owner_over_second_voice(): void
    {
        $cases = [
            'cyklocentrum' => ['Michal Cvrček', 'Petr Kňourek'],
            'pitarena' => ['Stanislav Holcmann', 'YCF CUP'],
        ];

        foreach ($cases as $slug => [$shown, $hidden]) {
            $project = PortfolioProject::where('slug', $slug)->with('translations')->firstOrFail();

            foreach (self::LOCALES as $locale) {
                $review = $this->reviewBlock(
                    $this->get($project->detailUrl($locale))->assertOk()->getContent()
                );

                $this->assertNotNull($review, "{$slug} ({$locale}) bez recenze.");
                $this->assertStringContainsString($shown, $review, "{$slug} ({$locale}).");
                $this->assertStringNotContainsString($hidden, $review, "{$slug} ({$locale}).");
            }
        }
    }

    public function test_toyota_project_has_no_client_review(): void
    {
        $body = $this->get('/projekty/hcms')->assertOk()->getContent();

        $this->assertStringContainsString('<div class="pd-story">', $body);
        $this->assertNull($this->reviewBlock($body));
    }

    /**
     * Blok recenze od otevření po řádek odkazů. Na `</figure>` skončit nejde:
     * tvář v `<x-testimonial-by>` je vnořená `<figure class="pd-by__face">`.
     */
    private function reviewBlock(string $body): ?string
    {
        return preg_match('/<figure class="pd-testi__item pd-story__review">.*?<\/figcaption>/s', $body, $m)
            ? $m[0]
            : null;
    }
}

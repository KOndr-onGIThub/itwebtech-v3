<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Database\Seeders\PortfolioTagNamesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * OND-223: na stagingu a produkci se `PortfolioSeeder` po prvním naplnění
 * přeskakuje, takže názvy štítků tam musí dostat migrace
 * `2026_09_17_300000_ond223_nazvy_stitku`.
 *
 * Fixture napodobuje ten produkční stav: štítky existují, ale mají jen `cs`
 * název odvozený ze slugu (`PortfolioSeeder::humanizeSlug`), EN/DE nic —
 * kromě tří štítků z OND-198, které už svoje názvy měly.
 */
class PortfolioTagNamesMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** Štítky, které přejmenovala už migrace 2026_09_16_100000 (OND-198). */
    private const OND198_SLUGS = ['landing-page', 'kampane', 'mobilni-prvni'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);

        // Zpět do stavu před touto migrací.
        foreach (DB::table('portfolio_tags')->pluck('slug', 'id') as $tagId => $slug) {
            if (in_array($slug, self::OND198_SLUGS, true)) {
                continue;
            }

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->whereIn('locale', ['en', 'de'])
                ->delete();

            DB::table('portfolio_tag_translations')
                ->where('tag_id', $tagId)
                ->where('locale', 'cs')
                ->update(['name' => ucfirst(str_replace('-', ' ', $slug))]);
        }
    }

    public function test_migration_fills_names_in_all_locales(): void
    {
        $this->assertSame(
            'Dlouhodoba spoluprace',
            $this->tagName('dlouhodoba-spoluprace', 'cs'),
            'Fixture měl nastavit rozbitý výchozí stav.'
        );
        $this->assertNull($this->tagName('dlouhodoba-spoluprace', 'de'));

        $this->migration()->up();

        foreach (PortfolioTagNamesSeeder::NAMES as $slug => $names) {
            foreach ($names as $locale => $name) {
                $this->assertSame($name, $this->tagName($slug, $locale), "Štítek {$slug}/{$locale}");
            }
        }

        // A projeví se to i na stránce, kde to bylo vidět rozbité.
        $this->get('/de/projekte/pitarena')
            ->assertOk()
            ->assertSee('Langfristige Zusammenarbeit', false)
            ->assertDontSee('Dlouhodoba spoluprace', false);
    }

    public function test_migration_down_returns_previous_state(): void
    {
        $migration = $this->migration();
        $migration->up();
        $migration->down();

        // Humanizovaný slug zpět, EN/DE pryč.
        $this->assertSame('Dlouhodoba spoluprace', $this->tagName('dlouhodoba-spoluprace', 'cs'));
        $this->assertNull($this->tagName('dlouhodoba-spoluprace', 'en'));
        $this->assertNull($this->tagName('dlouhodoba-spoluprace', 'de'));

        // Štítky z OND-198 si své názvy nechávají.
        $this->assertSame('Placená reklama', $this->tagName('kampane', 'cs'));
        $this->assertSame('Bezahlte Werbung', $this->tagName('kampane', 'de'));
    }

    private function migration(): Migration
    {
        return require database_path('migrations/2026_09_17_300000_ond223_nazvy_stitku.php');
    }

    private function tagName(string $slug, string $locale): ?string
    {
        $tagId = DB::table('portfolio_tags')->where('slug', $slug)->value('id');

        return DB::table('portfolio_tag_translations')
            ->where('tag_id', $tagId)
            ->where('locale', $locale)
            ->value('name');
    }
}

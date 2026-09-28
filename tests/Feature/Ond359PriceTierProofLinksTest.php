<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-359 — poslední položka zadání z dokumentu ke OND-347, oddíl 4.3:
 * u každé cenové úrovně má stát klikatelný odkaz na reálnou případovku.
 *
 * Na OND-354 to nasadit nešlo, protože `portfolio_projects` byla prázdná
 * a odkaz by vedl na 404 přímo z místa, kde se člověk rozhoduje o ceně.
 * Test proto hlídá obě strany: že odkazy stojí a vracejí 200, a že když
 * projekt v DB není, odkaz se nevykreslí (místo odkazu na 404).
 */
class Ond359PriceTierProofLinksTest extends TestCase
{
    use RefreshDatabase;

    /** Prefix cesty k ceníku a k detailu projektu pro každou locale. */
    private const PRICE_PATHS = [
        'cs' => ['price' => '/cenik',          'project' => '/projekty'],
        'en' => ['price' => '/en/price',       'project' => '/en/projects'],
        'de' => ['price' => '/de/preisliste',  'project' => '/de/projekte'],
    ];

    /**
     * OND-448 (B-08): `pitarena-eshop` (balíček „E-shop a aplikace“) má
     * v en/de lokalizovaný slug (OND-209). Šablona ho skládá přes
     * `detailUrl()`, test ho musí znát taky.
     */
    private const LOCALIZED_SLUGS = [
        'pitarena-eshop' => ['en' => 'pitarena-online-shop', 'de' => 'pitarena-onlineshop'],
    ];

    private function slugFor(string $slug, string $locale): string
    {
        return self::LOCALIZED_SLUGS[$slug][$locale] ?? $slug;
    }

    /**
     * U každé úrovně stojí odkaz s očekávaným popiskem a cílem — ve všech
     * třech jazycích. Adresa se skládá z jazykového prefixu a slugu.
     */
    public function test_every_tier_links_to_a_case_study_in_all_locales(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PRICE_PATHS as $locale => $paths) {
            $tiers = trans('price.tiers', [], $locale);
            $this->assertCount(3, $tiers, "price.tiers v `{$locale}` nemá tři úrovně.");

            $body = $this->get($paths['price'])->assertOk()->getContent();

            foreach ($tiers as $tier) {
                $this->assertArrayHasKey('proof', $tier, "Úroveň `{$tier['key']}` v `{$locale}` nemá `proof`.");

                $href  = $paths['project'] . '/' . $this->slugFor($tier['proof']['slug'], $locale);
                $label = trans('price.proof_intro', [], $locale) . ': ' . $tier['proof']['label'];

                $this->assertStringContainsString('href="' . url($href) . '"', $body,
                    "Na {$paths['price']} chybí odkaz na {$href} (úroveň `{$tier['key']}`).");
                $this->assertStringContainsString(e($label), $body,
                    "Na {$paths['price']} chybí popisek „{$label}".'".');
            }
        }
    }

    /**
     * Hotovo, když odkaz vrací 200, ne 404 — to je celý důvod, proč tahle
     * položka na OND-354 nešla nasadit. Kontrolujeme skutečnou odpověď, ne
     * jen existenci routy.
     */
    public function test_linked_case_studies_return_200_in_all_locales(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::PRICE_PATHS as $locale => $paths) {
            foreach (trans('price.tiers', [], $locale) as $tier) {
                $url = $paths['project'] . '/' . $this->slugFor($tier['proof']['slug'], $locale);

                $this->get($url)->assertOk();
            }
        }
    }

    /**
     * Prázdná `portfolio_projects` → žádný odkaz, ale ceník se vykreslí dál.
     * Stejný princip jako mřížka na /projekty (OND-351): až data budou,
     * odkaz se vrátí sám.
     */
    public function test_no_proof_link_is_rendered_when_project_is_missing(): void
    {
        $body = $this->get('/cenik')->assertOk()->getContent();

        $this->assertStringNotContainsString('pd-price__proof', $body);
        $this->assertStringNotContainsString(__('price.proof_intro') . ':', $body);

        // Zbytek karet zůstává — mizí odkaz, ne úroveň.
        foreach (__('price.tiers') as $tier) {
            $this->assertStringContainsString(e($tier['scope']), $body);
        }
    }
}

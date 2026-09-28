<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * OND-408 — /zasady-ochrany-osobnich-udaju a /cookies ve slovníku nové
 * homepage (předloha OND-406 §4b).
 *
 * Právní text se tiskne beze změny, jen v jiném obalu (`.pd-prose`,
 * shrnutí v desce `.pd-aside`). Vrstva hloubky zůstává vypnutá (OND-251)
 * a v obsahu není žádná prodejní výzva (OND-266/387). Jediné tlačítko je
 * „Odvolat souhlas" na /cookies — ovládání, ne výzva — a jeho `onclick`
 * se nesmí změnit.
 */
class Ond408LegalPagesTest extends TestCase
{
    private const LOCALES = ['cs', 'en', 'de'];

    private const REVOKE_ONCLICK = 'onclick="if (window.ItwebtechAnalytics) { window.ItwebtechAnalytics.revokeConsent(); location.reload(); }"';

    /** Obsah `<main>` — hlavička s tlačítkem poptávky do testu nepatří. */
    private function main(string $page, string $locale): string
    {
        $body = $this->get(lroute($page, $locale))->assertOk()->getContent();
        $this->assertSame(1, preg_match('#<main\b[^>]*>(.*)</main>#s', $body, $m), "{$page} {$locale}: <main>");

        return $m[1];
    }

    public function test_both_pages_render_in_new_vocabulary_without_depth_layer(): void
    {
        foreach (['privacy', 'cookies'] as $page) {
            foreach (self::LOCALES as $locale) {
                $main = $this->main($page, $locale);
                $at = "{$page} {$locale}";

                $this->assertStringContainsString('<div class="pd">', $main, $at);
                $this->assertStringNotContainsString('pd--depth', $main, "OND-251 {$at}");
                $this->assertStringContainsString('<section class="pd-section pd-page-head">', $main, $at);
                $this->assertStringContainsString('<h1 class="pd-heading pd-heading--sub">' . __("{$page}.hero.heading_html", [], $locale) . '</h1>', $main, $at);
                $this->assertStringContainsString('<div class="pd-prose">', $main, $at);
                $this->assertStringContainsString('<aside class="pd-aside">', $main, $at);

                // Starý slovník je pryč.
                foreach (['page-hero', 'legal-tldr', 'prose-content', 'section-wrapper', 'btn btn-primary', 'data-reveal'] as $dead) {
                    $this->assertStringNotContainsString($dead, $main, "{$at}: {$dead}");
                }
                // Žádná prodejní výzva: v obsahu není odkaz ve tvaru tlačítka.
                $this->assertDoesNotMatchRegularExpression('#<a\b[^>]*class="[^"]*pd-cta#', $main, $at);
            }
        }
    }

    public function test_legal_text_is_printed_unchanged(): void
    {
        foreach (self::LOCALES as $locale) {
            $privacy = $this->main('privacy', $locale);
            $this->assertStringContainsString(__('privacy.content', [], $locale), $privacy, "privacy {$locale}");
            foreach (__('privacy.tldr.items', [], $locale) as $item) {
                $this->assertStringContainsString('<li>' . e($item) . '</li>', $privacy, "privacy {$locale}");
            }

            $cookies = $this->main('cookies', $locale);
            foreach (__('cookies.tldr.items', [], $locale) as $item) {
                $this->assertStringContainsString("<li>{$item}</li>", $cookies, "cookies {$locale}");
            }
            $this->assertStringContainsString('<p>' . __('cookies.intro', [], $locale) . '</p>', $cookies, "cookies {$locale}");
            foreach (['what_we_use', 'what_we_measure', 'retention'] as $block) {
                foreach (__("cookies.{$block}.items", [], $locale) as $item) {
                    $this->assertStringContainsString("<li>{$item}</li>", $cookies, "cookies {$locale} {$block}");
                }
            }
            $this->assertStringContainsString('<p>' . __('cookies.revoke.manual', [], $locale) . '</p>', $cookies, "cookies {$locale}");
        }
    }

    public function test_cookies_revoke_button_keeps_its_handler(): void
    {
        foreach (self::LOCALES as $locale) {
            $main = $this->main('cookies', $locale);

            $this->assertSame(1, preg_match_all('#<button\b#', $main), "cookies {$locale}: jedno tlačítko");
            $this->assertMatchesRegularExpression(
                '#<button type="button"\s+class="pd-cta"\s+' . preg_quote(self::REVOKE_ONCLICK, '#') . '>\s*' . preg_quote(e(__('cookies.revoke.button', [], $locale)), '#') . '\s*</button>#',
                $main,
                "cookies {$locale}"
            );
        }
    }

    public function test_title_follows_subpage_pattern(): void
    {
        $expected = [
            'privacy' => ['cs' => 'Zásady ochrany osobních údajů', 'en' => 'Privacy Policy', 'de' => 'Datenschutzerklärung'],
            'cookies' => ['cs' => 'Cookies a souhlas se zpracováním', 'en' => 'Cookies and consent', 'de' => 'Cookies und Einwilligung'],
        ];
        foreach ($expected as $page => $titles) {
            foreach ($titles as $locale => $name) {
                $body = $this->get(lroute($page, $locale))->assertOk()->getContent();
                $this->assertStringContainsString('<title>' . e("{$name} — Ondřej Kriška, ONDRAWEB") . '</title>', $body, "{$page} {$locale}");
            }
        }
    }
}

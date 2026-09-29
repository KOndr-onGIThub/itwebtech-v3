<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * OND-467 — ostrý web i databáze běží od 29. 9. 2026 na hostingu Webglobe
 * v Česku, ne u Hetzneru. Zásady ochrany údajů to musí uvádět ve všech
 * jazycích.
 */
class Ond467PrivacyHostingTest extends TestCase
{
    public function test_privacy_names_webglobe_as_hosting_in_every_locale(): void
    {
        $countries = ['cs' => 'Česko', 'en' => 'Czech Republic', 'de' => 'Tschechien'];

        foreach ($countries as $locale => $country) {
            $body = $this->get(lroute('privacy', $locale))->assertOk()->getContent();

            $this->assertStringContainsString("<strong>Webglobe</strong> ({$country})", $body, $locale);
            $this->assertStringNotContainsString('Hetzner', $body, $locale);
        }
    }
}

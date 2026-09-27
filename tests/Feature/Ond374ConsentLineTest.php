<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-374 — řádek souhlasu se v češtině lámal na dva sloupce.
 *
 * `.form-group--checkbox label` je `display: flex`. Dokud měl `<label>` tři
 * děti (`<input>`, holý text, `<a>`), stal se z holého textu anonymní flex item
 * a z odkazu druhý — dva sloupce vedle sebe, každý se lámal zvlášť a mezi nimi
 * zel `gap`. Angličtina a němčina defekt neukazovaly jen proto, že se jim to
 * náhodou vešlo na jeden řádek.
 *
 * Testuje se struktura, ne vizuál — na tu se to dá zachytit v PHP:
 *  1. Za `<input>` stojí v labelu **právě jeden** element a je to `<span>`,
 *     který drží text i odkaz. Tohle je vlastní oprava.
 *  2. Mezera mezi textem a odkazem zůstala jediná (klíč `contact.agree` končí
 *     mezerou; nový řádek v šabloně by přidal druhou).
 *  3. Chování kliknutí: `<input>` je pořád uvnitř `<label>` (implicitní vazba),
 *     takže klik na text checkbox přepíná.
 *  4. Stejný vzor na landing page — tam byl defekt taky, jen ho nikdo neviděl.
 */
class Ond374ConsentLineTest extends TestCase
{
    use RefreshDatabase;

    private const CONTACT_PAGES = [
        'cs' => '/kontakt',
        'en' => '/en/contact',
        'de' => '/de/kontakt',
    ];

    // ------------------------------------------------------------------
    // 1. + 3. Struktura labelu na /kontakt
    // ------------------------------------------------------------------

    /**
     * Jádro opravy. Obsah labelu za `<input>` musí být jeden jediný `<span>` —
     * jakýkoli holý textový uzel vedle něj by se ve flexu stal dalším sloupcem.
     */
    public function test_consent_label_wraps_text_and_link_in_one_element_in_every_locale(): void
    {
        foreach (self::CONTACT_PAGES as $locale => $path) {
            $inner = $this->consentLabelInner($this->get($path)->assertOk()->getContent(), $path);

            $this->assertMatchesRegularExpression(
                '~^<span>.*</span>$~s',
                $inner,
                "`{$path}` ({$locale}): obsah labelu za checkboxem není jeden `<span>`, ".
                "takže text a odkaz jsou pořád dva flex itemy:\n{$inner}",
            );

            $this->assertStringContainsString(
                e(trans('contact.policy', [], $locale)),
                $inner,
                "`{$path}` ({$locale}): odkaz na zásady ze řádku souhlasu zmizel.",
            );
        }
    }

    /**
     * Protějšek bloku 1: `<span>` sice může existovat, ale s nalámaným
     * whitespace mezi textem a odkazem. `contact.agree` končí mezerou, takže
     * mezi koncem věty a `<a` smí stát právě jedna.
     */
    public function test_exactly_one_space_separates_the_sentence_from_the_link(): void
    {
        foreach (self::CONTACT_PAGES as $locale => $path) {
            $inner = $this->consentLabelInner($this->get($path)->assertOk()->getContent(), $path);

            $this->assertStringContainsString(
                e(rtrim(trans('contact.agree', [], $locale))).' <a',
                $inner,
                "`{$path}` ({$locale}): mezi větou a odkazem není právě jedna mezera ".
                "— v šabloně přibyl nový řádek nebo odsazení:\n{$inner}",
            );
        }
    }

    /**
     * Ze zadání důležitější než samotný zlom: `<input>` zůstává uvnitř
     * `<label>`, takže klik na text checkbox přepíná i po přeobalení.
     */
    public function test_checkbox_stays_inside_the_label_so_clicking_the_text_toggles_it(): void
    {
        foreach (self::CONTACT_PAGES as $locale => $path) {
            $body = $this->get($path)->assertOk()->getContent();

            $this->assertMatchesRegularExpression(
                '~<label>\s*<input[^>]*name="gdpr"[^>]*>~',
                $body,
                "`{$path}` ({$locale}): checkbox už není prvním dítětem `<label>` ".
                'a klik na text ho nemusí přepnout.',
            );
        }
    }

    // ------------------------------------------------------------------
    // 4. Landing page — stejná CSS třída, stejný defekt
    // ------------------------------------------------------------------

    public function test_landing_consent_label_wraps_text_and_link_in_one_element(): void
    {
        $body = $this->get('/'.config('landing.preview_path'))->assertOk()->getContent();

        $inner = $this->consentLabelInner($body, 'landing');

        $this->assertMatchesRegularExpression(
            '~^<span>.*</span>$~s',
            $inner,
            "Landing page: obsah labelu za checkboxem není jeden `<span>`:\n{$inner}",
        );

        $this->assertStringContainsString(
            e(rtrim(trans('landing.form.privacy_prefix'))).' <a',
            $inner,
            'Landing page: mezi větou a odkazem není právě jedna mezera.',
        );
    }

    // ------------------------------------------------------------------

    /**
     * Vrátí obsah GDPR labelu bez `<input>`, s otrimovaným whitespace zvenčí.
     * Ten zvenčí je nezajímavý (HTML ho beztak kolabuje), zajímavé je, kolik
     * dětí po něm zbude.
     */
    private function consentLabelInner(string $html, string $where): string
    {
        $matched = preg_match(
            '~<label>\s*<input[^>]*name="gdpr"[^>]*>(.*?)</label>~s',
            $html,
            $m,
        );

        $this->assertSame(1, $matched, "`{$where}`: GDPR label se na stránce nenašel.");

        return trim($m[1]);
    }
}

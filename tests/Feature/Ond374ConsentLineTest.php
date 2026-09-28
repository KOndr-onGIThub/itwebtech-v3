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
 * OND-448 (B-01): na /kontakt zaškrtávátko odešlo (bloky 1–3 teď hlídají, že se
 * nevrátí). Na landing page zůstává a blok 4 platí dál.
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
    // 1.–3. OND-448 (B-01): zaškrtávací souhlas z poptávkového formuláře
    // (homepage i /kontakt) odešel. Zprávu zpracovávám kvůli jednání
    // o smlouvě (čl. 6 odst. 1 písm. b GDPR), souhlas k tomu není potřeba.
    // Místo něj stojí pod tlačítkem informační věta s odkazem na zásady.
    // ------------------------------------------------------------------

    public function test_lead_form_has_no_consent_checkbox_but_links_the_privacy_policy(): void
    {
        $pages = self::CONTACT_PAGES + ['cs-home' => '/', 'en-home' => '/en/', 'de-home' => '/de/'];

        foreach ($pages as $key => $path) {
            $locale = substr($key, 0, 2);
            $body = $this->get($path)->assertOk()->getContent();
            $panel = substr($body, strpos($body, 'class="pd-form__panel"'));
            $panel = substr($panel, 0, strpos($panel, '</form>'));

            $this->assertStringNotContainsString('name="gdpr"', $panel, "`{$path}`: zaškrtávátko se souhlasem zůstalo.");
            $this->assertStringNotContainsString('type="checkbox"', $panel, "`{$path}`: ve formuláři je checkbox.");
            $this->assertStringContainsString(
                e(trans('home.inline_form.privacy_prefix', [], $locale)).'<a href="'.e($this->privacyUrl($locale)).'">'.e(trans('home.inline_form.privacy_link', [], $locale)).'</a>.',
                $panel,
                "`{$path}` ({$locale}): chybí informační věta s odkazem na zásady.",
            );
        }
    }

    private function privacyUrl(string $locale): string
    {
        return route($locale.'.privacy');
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

<?php

namespace Tests\Feature;

use App\Support\ReplyDate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-392 — /kontakt ve slovníku nové homepage (2. z 9 podstránek).
 *
 * Návrh a měření: OND-390. Vzor je `Ond380AboutAcidTest` (/o-mne), ale
 * /kontakt je jediná stránka webu, přes kterou chodí poptávky — a rozbitý
 * formulář nic nahlas neohlásí, poptávky prostě přestanou chodit. Proto
 * tu navíc stojí háky, na kterých formulář visí:
 *
 *  1. ŠABLONA NESMÍ SÁHNOUT NA OBSAH. Každý viditelný klíč `contact.*`
 *     stojí v `<main>` doslova a ve stejném pořadí, cs/en/de. Hranice
 *     tvrzení je stejná jako u /o-mne: chytí šablonu, která klíč zahodí
 *     nebo prohodí, NE změnu samotného `lang/` (ta je vidět v diffu).
 *  2. FORMULÁŘ MUSÍ FUNGOVAT JAKO DŘÍV. Alpine komponenta, odeslání
 *     `@submit.prevent`, `x-ref="form"`, past `contact-website-url`,
 *     drop zóna příloh, stav po odeslání a jména polí, na kterých stojí
 *     serverová validace. Skutečné odeslání hlídá `ContactFormTest`,
 *     past `HoneypotTest`, přílohy `ContactAttachmentsTest` — tenhle test
 *     hlídá, že je šablona po převodu pořád volá.
 *  3. NASVÍCENÍ A PROUD NESMÍ ZMIZET. Kužely visí na `data-pdd` přímých
 *     dětí obalu `.pd--depth-sub`; náboj formuláře na `.pd-form__panel`
 *     (hloubka.js TARGETS pro podstránky a výjimka 1 v hloubka.css §0).
 *  4. STARÝ SLOVNÍK JE PRYČ.
 */
class Ond392ContactAcidTest extends TestCase
{
    use RefreshDatabase;

    /** locale => cesta */
    private const PAGES = [
        'cs' => '/kontakt',
        'en' => '/en/contact',
        'de' => '/de/kontakt',
    ];

    public function test_all_three_locales_render(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_title_follows_the_about_page_pattern(): void
    {
        $expected = [
            'cs' => 'Kontakt — Ondřej Kriška, ONDRAWEB',
            'en' => 'Contact — Ondřej Kriška, ONDRAWEB',
            'de' => 'Kontakt — Ondřej Kriška, ONDRAWEB',
        ];

        foreach (self::PAGES as $locale => $url) {
            $this->get($url)->assertOk()->assertSee('<title>'.$expected[$locale].'</title>', false);
        }
    }

    private function mainOf(string $html): string
    {
        $from = mb_strpos($html, '<main');
        $to = mb_strpos($html, '</main>');

        $this->assertNotFalse($from, 'stránka nemá <main>');
        $this->assertNotFalse($to, 'stránka nemá </main>');

        return mb_substr($html, $from, $to - $from);
    }

    private function text(string $html): string
    {
        return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Viditelné klíče v pořadí šablony. Atributy (`alt`, `placeholder`)
     * `strip_tags` zahodí, takže tu nejsou; klíče s HTML (`heading_html`,
     * `open_hours`) se porovnávají jako text. Hledá se od kurzoru, ne od
     * začátku — krátké popisky („E-mail") se na stránce opakují.
     */
    public function test_every_language_key_renders_verbatim_and_in_order(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $this->app->setLocale($locale);

            $keys = [
                'hero.page_mark_label', 'hero.upline', 'hero.heading_html', 'hero.subline', 'hero.role_label',
                'address_label', 'address_name', 'address_street', 'address_city', 'address_registration',
                'email_label', 'phone_label', 'hours_label', 'open_hours', 'cta_consultation',
                'form_heading', 'form_subheading',
                // OND-448 (B-01): pole a tlačítko sdíleného `<x-lead-form>`
                // jsou v `home.inline_form` (předmět a souhlas odešly).
                'home.inline_form.name', 'home.inline_form.email', 'home.inline_form.phone',
                'home.inline_form.phone_hint', 'home.inline_form.message',
                'home.inline_form.attach_toggle', 'home.inline_form.submit', 'home.inline_form.submitting',
                'home.inline_form.privacy_prefix', 'home.inline_form.privacy_link',
                'next_steps.eyebrow', 'next_steps.heading',
            ];
            // OND-437: `hero.subline` nese `:date` (App\Support\ReplyDate), ne
            // doslovný text; potvrzení po odeslání vykresluje až odpověď
            // serveru, na stránce před odesláním není.
            $expected = array_map(fn ($key) => $this->text(match (true) {
                $key === 'hero.subline'          => ReplyDate::sentence('contact.hero.subline', ReplyDate::date()),
                str_starts_with($key, 'home.')   => __($key),
                default                          => __('contact.'.$key),
            }), $keys);
            foreach (__('contact.next_steps.steps') as $step) {
                $expected[] = $step['title'];
                $expected[] = $step['text'];
            }

            $text = $this->text($this->mainOf($this->get($url)->assertOk()->getContent()));

            $cursor = 0;
            foreach ($expected as $i => $string) {
                $at = mb_strpos($text, $string, $cursor, 'UTF-8');

                $this->assertNotFalse(
                    $at,
                    "[$locale] klíč #$i chybí nebo stojí dřív, než má: ".mb_substr($string, 0, 60)
                );
                $cursor = $at + mb_strlen($string);
            }
        }
    }

    public function test_form_hooks_survive(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $main = $this->mainOf($this->get($url)->assertOk()->getContent());

            // Alpine komponenta a kotva sedí na desce z homepage.
            $this->assertMatchesRegularExpression(
                '/<div id="kontaktni-formular" class="pd-form__panel"\s+x-data="contactForm\(/',
                $main,
                "[$locale] `contactForm` už nesedí na `#kontaktni-formular.pd-form__panel`"
            );

            // Odeslání a stav po odeslání.
            $this->assertStringContainsString(
                '<form @submit.prevent="submit" novalidate x-show="!submitted" x-ref="form">',
                $main,
                "[$locale] formulář se neodesílá přes Alpine nebo se po odeslání neschová"
            );
            $this->assertStringContainsString('x-show="submitted"', $main, "[$locale] chybí stav po odeslání");

            // Past, přílohy a jména polí musí stát UVNITŘ <form> — jinak je
            // FormData nepošle a server buď past nevidí, nebo validuje prázdno.
            $form = mb_substr($main, mb_strpos($main, '<form '), mb_strpos($main, '</form>') - mb_strpos($main, '<form '));

            $this->assertStringContainsString('id="lead-website-url"', $form, "[$locale] past na boty není ve formuláři");
            $this->assertStringContainsString('name="'.\App\Support\Honeypot::FIELD.'"', $form, "[$locale] past nemá jméno, podle kterého ji server pozná");
            $this->assertStringContainsString('fileDropZone(', $form, "[$locale] drop zóna příloh není ve formuláři");
            $this->assertStringContainsString('name="attachment[]"', $form, "[$locale] pole příloh není ve formuláři");

            // OND-448 (B-01): předmět a souhlas odešly, `source` říká místo.
            foreach (['name', 'email', 'tel', 'message', 'locale', 'source'] as $field) {
                $this->assertMatchesRegularExpression(
                    '/<(input|textarea)[^>]*\sname="'.$field.'"/',
                    $form,
                    "[$locale] pole `$field` zmizelo nebo se přejmenovalo — server ho nedostane"
                );
            }

            $this->assertStringContainsString('x-ref="formError"', $form, "[$locale] chybí souhrnná hláška pro pád bez 422");
        }
    }

    public function test_depth_layer_hooks_survive(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $dom = new \DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " pd ") and contains(@class, "pd--depth-sub")]')->length,
                "[$locale] obal vrstvy hloubky nemá třídu `pd` — komponenty si nepřitáhnou barvu"
            );

            foreach (['contact-form', 'contact-next'] as $pdd) {
                $this->assertSame(
                    1,
                    $xpath->query('//div[contains(@class,"pd--depth-sub")]/section[@data-pdd="'.$pdd.'"]')->length,
                    "[$locale] sekce `$pdd` není přímým dítětem `.pd--depth-sub` — kužel v §E zhasne"
                );
            }

            // Náboj formuláře: jediná pohyblivá věc na podstránce.
            $this->assertSame(
                1,
                $xpath->query('//section[@data-pdd="contact-form"]//div[contains(concat(" ", normalize-space(@class), " "), " pd-form__panel ")]')->length,
                "[$locale] deska `.pd-form__panel` zmizela ze sekce formuláře — náboj nepoběží"
            );
            // OND-448: jméno, e-mail, telefon, zpráva (předmět odešel).
            $this->assertGreaterThanOrEqual(
                4,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " pd-field ")]')->length,
                "[$locale] políčka nejsou `.pd-field` — proud do políčka nepoběží"
            );

            // Kontaktní stín vrstvy C, hloubka.css §E2.
            $this->assertSame(1, $xpath->query('//div[@class="pd-page-head__photo"]')->length, "[$locale] zmizel rám portrétu se stínem");
        }
    }

    public function test_acid_vocabulary_replaced_the_subpage_one(): void
    {
        foreach (self::PAGES as $locale => $url) {
            $main = $this->mainOf($this->get($url)->assertOk()->getContent());

            foreach (['pd-page-head', 'pd-heading--sub', 'pd-form', 'pd-form__panel', 'pd-field', 'pd-steps', 'pd-cta'] as $class) {
                $this->assertStringContainsString($class, $main, "[$locale] chybí komponenta `$class`");
            }

            foreach (['page-hero', 'contact-layout', 'contact-info', 'class="contact-form', 'section-wrapper', 'next-steps', 'btn btn-primary', 'form-row-2col', 'form-alert'] as $dead) {
                $this->assertStringNotContainsString($dead, $main, "[$locale] v obsahu stránky se vrátil starý slovník: `$dead`");
            }
        }
    }
}

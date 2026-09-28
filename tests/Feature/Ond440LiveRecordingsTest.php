<?php

namespace Tests\Feature;

use App\Models\Portfolio\PortfolioProject;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OND-440 (návrh 4, specifikace OND-439) — v případovkách na homepage jsou místo
 * montáží do zařízení tiché záznamy živých klientských webů.
 *
 * Tvrdá podmínka: při načtení homepage se video nestahuje. V HTML proto nesmí
 * být žádný `<source>` záznamu ani `src` na `<video>` — zdroje doplní
 * `live-recordings.js` až při přiblížení.
 */
class Ond440LiveRecordingsTest extends TestCase
{
    use RefreshDatabase;

    private const LOCALES = [
        'cs' => ['/', 'živý web', 'nahráno 28. 9. 2026'],
        'en' => ['/en', 'live site', 'recorded 28 Sep 2026'],
        'de' => ['/de', 'Live-Website', 'aufgenommen am 28.09.2026'],
    ];

    public function test_frames_render_without_video_sources_in_all_locales(): void
    {
        $this->seed(PortfolioSeeder::class);

        foreach (self::LOCALES as $locale => [$url, $kind, $recorded]) {
            $body = $this->get($url)->assertOk()->getContent();

            $this->assertSame(3, substr_count($body, 'class="pd-case__visual pd-live"'), "[$locale] čekám tři rámy se záznamem");
            $this->assertSame(3, substr_count($this->onlyLiveFrames($body), 'preload="none"'), "[$locale] každý záznam má preload=none");
            $this->assertDoesNotMatchRegularExpression('#<source[^>]+video/projekty/[^"]+\.(webm|mp4)#', $body, "[$locale] <source> záznamu v HTML = stažení při načtení");
            $this->assertDoesNotMatchRegularExpression('#<video[^>]+\ssrc=#', $this->onlyLiveFrames($body), "[$locale] <video> nesmí mít src");
            $this->assertStringContainsString($kind.' · ', $body, "[$locale] popisek lišty");
            $this->assertStringContainsString($recorded, $body, "[$locale] datum nahrání ve tvaru jazyka");
        }
    }

    public function test_sources_are_av1_first_then_h264_and_files_exist(): void
    {
        $this->seed(PortfolioSeeder::class);
        $body = $this->get('/')->assertOk()->getContent();

        preg_match_all('#data-live="([^"]+)"#', $body, $m);
        $this->assertCount(3, $m[1]);

        foreach ($m[1] as $raw) {
            $live = json_decode(html_entity_decode($raw), true);

            foreach (['desktop', 'mobile'] as $variant) {
                [[$webm, $webmType], [$mp4, $mp4Type]] = $live[$variant];
                $this->assertStringContainsString('av01', $webmType, 'první zdroj je AV1');
                $this->assertStringContainsString('avc1', $mp4Type, 'záloha pro Safari je H.264');

                foreach ([$webm, $mp4] as $src) {
                    $this->assertStringContainsString("-{$variant}.", $src);
                    $this->assertStringContainsString('?v=', $src, 'bez otisku by po přenahrání zůstala stará verze v immutable cache');
                    $this->assertFileExists(public_path(parse_url($src, PHP_URL_PATH)));
                }
            }
        }

        foreach (array_keys(config('site.live_recordings')) as $slug) {
            foreach (['desktop', 'desktop-768', 'desktop-960', 'mobile'] as $poster) {
                $this->assertFileExists(public_path("video/projekty/{$slug}-{$poster}.webp"));
            }
        }
    }

    public function test_screen_links_to_project_detail_and_pause_is_a_button(): void
    {
        $this->seed(PortfolioSeeder::class);
        $body = $this->get('/')->assertOk()->getContent();

        foreach (array_keys(config('site.live_recordings')) as $slug) {
            $detail = preg_quote(PortfolioProject::where('slug', $slug)->firstOrFail()->detailUrl('cs'), '#');
            $this->assertMatchesRegularExpression(
                '#<a\s+href="'.$detail.'"\s+class="pd-live__screen"[^>]*data-analytics="project_card_click"#',
                $body,
                "klik na záznam {$slug} musí vést na detail a měřit se jako dnes"
            );
        }

        // Pauza je tlačítko v liště, ne uvnitř odkazu — klik na ni nesmí vést na detail.
        $flat = preg_replace('#\s+#', ' ', $this->onlyLiveFrames($body));
        $this->assertSame(3, substr_count($flat, '<button type="button" class="pd-live__toggle" aria-pressed="false"'));
        $this->assertDoesNotMatchRegularExpression('#class="pd-live__screen"[^>]*>(?:(?!</a>).)*pd-live__toggle#s', $body);
    }

    public function test_project_without_recording_keeps_screenshot(): void
    {
        $this->seed(PortfolioSeeder::class);
        config(['site.live_recordings' => array_diff_key(config('site.live_recordings'), ['barana' => true])]);

        $body = $this->get('/')->assertOk()->getContent();

        $this->assertSame(2, substr_count($body, 'class="pd-case__visual pd-live"'));
        $this->assertSame(1, substr_count($body, 'class="pd-case__visual"'), 'BARANA zůstane u screenshotu');
    }

    private function onlyLiveFrames(string $body): string
    {
        preg_match_all('#<div class="pd-case__visual pd-live".*?</video>#s', $body, $m);

        return implode("\n", $m[0]);
    }
}

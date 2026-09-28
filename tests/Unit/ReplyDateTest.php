<?php

namespace Tests\Unit;

use App\Support\ReplyDate;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * OND-437 (návrh 1 z OND-429): den odpovědi = následující pracovní den
 * v Europe/Prague bez víkendů a českých státních svátků (vč. Velikonoc).
 *
 * Potřebuje aplikaci kvůli `__()` ve formátování, proto Tests\TestCase.
 */
class ReplyDateTest extends TestCase
{
    private const NBSP = "\u{00A0}";

    /** Hraniční dny ze zadání OND-437. */
    public static function boundaryDays(): array
    {
        return [
            'po 28. 9. 2026 (svátek) → út 29. 9.'       => ['2026-09-28 10:00', '2026-09-29'],
            'pá 25. 9. 2026 (+ víkend, svátek) → út 29. 9.' => ['2026-09-25 10:00', '2026-09-29'],
            '23. 12. (Vánoce, víkend) → po 28. 12.'     => ['2026-12-23 10:00', '2026-12-28'],
            '31. 12. (Nový rok, víkend) → po 4. 1.'     => ['2026-12-31 10:00', '2027-01-04'],
            'čt 25. 3. 2027 (Velikonoce) → út 30. 3.'   => ['2027-03-25 10:00', '2027-03-30'],
            'obyčejné úterý → středa'                    => ['2026-09-29 10:00', '2026-09-30'],
            'pozdě večer pořád dnešek'                   => ['2026-09-29 23:59', '2026-09-30'],
        ];
    }

    #[DataProvider('boundaryDays')]
    public function test_next_business_day_on_boundary_days(string $nowPrague, string $expected): void
    {
        $now = CarbonImmutable::parse($nowPrague, ReplyDate::TIMEZONE);

        $this->assertSame($expected, ReplyDate::next($now)->toDateString());
    }

    public function test_day_is_counted_in_prague_not_utc(): void
    {
        // 24. 9. 22:30 UTC = pátek 25. 9. 00:30 v Praze → úterý 29. 9.
        // (v UTC by to byl čtvrtek a vyšel by pátek 25. 9.)
        $now = CarbonImmutable::parse('2026-09-24 22:30', 'UTC');

        $this->assertSame('2026-09-29', ReplyDate::next($now)->toDateString());
    }

    public function test_easter_friday_and_monday_are_holidays(): void
    {
        // Velikonoční neděle: 2026-04-05, 2027-03-28, 2028-04-16.
        foreach (['2026-04-03', '2026-04-06', '2027-03-26', '2027-03-29', '2028-04-14', '2028-04-17'] as $day) {
            $this->assertTrue(ReplyDate::isHoliday(CarbonImmutable::parse($day)), "$day má být svátek");
        }

        foreach (['2026-04-02', '2026-04-07', '2027-03-25', '2027-03-30'] as $day) {
            $this->assertFalse(ReplyDate::isHoliday(CarbonImmutable::parse($day)), "$day nemá být svátek");
        }
    }

    public function test_date_format_in_all_three_locales(): void
    {
        $now = CarbonImmutable::parse('2026-09-28 10:00', ReplyDate::TIMEZONE);
        $nb = self::NBSP;

        $this->assertSame("v{$nb}úterý 29.{$nb}9.", ReplyDate::date($now, 'cs'));
        $this->assertSame("Tuesday 29{$nb}September", ReplyDate::date($now, 'en'));
        $this->assertSame('Dienstag, 29.9.', ReplyDate::date($now, 'de'));

        // Předložka se mění podle dne: „ve středu", „ve čtvrtek".
        $wednesday = CarbonImmutable::parse('2026-09-29 10:00', ReplyDate::TIMEZONE);
        $this->assertSame("ve středu 30.{$nb}9.", ReplyDate::date($wednesday, 'cs'));
    }

    public function test_received_is_shown_in_prague_time(): void
    {
        $at = CarbonImmutable::parse('2026-09-28 12:32', 'UTC'); // 14:32 v Praze
        $nb = self::NBSP;

        $this->assertSame("28.{$nb}9., 14:32", ReplyDate::received($at, 'cs'));
        $this->assertSame("28{$nb}September, 14:32 Prague time", ReplyDate::received($at, 'en'));
        $this->assertSame('28.9., 14:32 Uhr', ReplyDate::received($at, 'de'));
    }

    public function test_date_at_sentence_end_does_not_double_the_period(): void
    {
        $now = CarbonImmutable::parse('2026-09-28 10:00', ReplyDate::TIMEZONE);
        $nb = self::NBSP;

        foreach (['cs', 'de'] as $locale) {
            $this->app->setLocale($locale);
            $html = ReplyDate::sentence('home.hero.note', ReplyDate::date($now, $locale));

            $this->assertStringNotContainsString('.</strong>.', $html, "[$locale] dvě tečky za datem");
        }

        $this->app->setLocale('cs');
        $this->assertStringStartsWith(
            "Když mi napíšete dnes, ozvu se nejpozději <strong class=\"pd-date\">v{$nb}úterý 29.{$nb}9.</strong> Nezávazně",
            ReplyDate::sentence('home.hero.note', ReplyDate::date($now, 'cs')),
        );

        // en datum tečkou nekončí, tečka věty zůstává.
        $this->app->setLocale('en');
        $this->assertStringContainsString(
            "<strong class=\"pd-date\">Tuesday 29{$nb}September</strong>. No commitment",
            ReplyDate::sentence('home.hero.note', ReplyDate::date($now, 'en')),
        );
    }

    public function test_sentence_escapes_text_and_wraps_only_the_date(): void
    {
        $this->app->setLocale('cs');

        $html = ReplyDate::sentence('home.inline_form.confirmation.reply', 'v úterý <29>', [
            'email' => 'jan"<b>@example.com',
        ]);

        $this->assertStringContainsString('<strong class="pd-date">v úterý &lt;29&gt;</strong>', $html);
        $this->assertStringContainsString('jan&quot;&lt;b&gt;@example.com', $html);
        $this->assertStringNotContainsString(':date', $html);
        $this->assertStringNotContainsString(':email', $html);
    }
}

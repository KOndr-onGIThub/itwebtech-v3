<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * OND-437 (návrh 1 z OND-429): místo „ozvu se následující pracovní den"
 * web píše konkrétní den — „ozvu se nejpozději v úterý 29. 9.".
 *
 * Den se počítá při vykreslení stránky v Europe/Prague a přeskočí víkendy
 * a české státní svátky včetně Velikonočního pátku a pondělí. Stránky se
 * necachují (`cache-control: no-cache, private`), takže datum nezastará.
 *
 * Znění a tvary dnů jsou v `lang/{locale}/home.php` pod `reply_date`
 * (cs potřebuje v/ve podle dne, to je věc jazyka, ne kódu).
 */
class ReplyDate
{
    public const TIMEZONE = 'Europe/Prague';

    /** Pevné státní svátky ČR (zákon č. 245/2000 Sb.), měsíc-den. */
    private const FIXED_HOLIDAYS = [
        '01-01', '05-01', '05-08', '07-05', '07-06', '09-28',
        '10-28', '11-17', '12-24', '12-25', '12-26',
    ];

    /** Následující pracovní den po `$now` (bez víkendů a svátků). */
    public static function next(?CarbonInterface $now = null): CarbonImmutable
    {
        $day = CarbonImmutable::instance($now ?? now())
            ->setTimezone(self::TIMEZONE)
            ->startOfDay()
            ->addDay();

        while ($day->isWeekend() || self::isHoliday($day)) {
            $day = $day->addDay();
        }

        return $day;
    }

    public static function isHoliday(CarbonInterface $day): bool
    {
        if (in_array($day->format('m-d'), self::FIXED_HOLIDAYS, true)) {
            return true;
        }

        $easter = self::easterSunday($day->year);

        return $day->isSameDay($easter->subDays(2))   // Velký pátek
            || $day->isSameDay($easter->addDay());    // Velikonoční pondělí
    }

    /** Hodnota pro `:date` — „v úterý 29. 9." / „Tuesday 29 September" / „Dienstag, 29.9.". */
    public static function date(?CarbonInterface $now = null, ?string $locale = null): string
    {
        $day = self::next($now);

        return __('home.reply_date.date', [
            'weekday'    => __('home.reply_date.weekdays', [], $locale)[$day->dayOfWeekIso],
            'day'        => $day->day,
            'month'      => $day->month,
            'month_name' => $day->locale('en')->monthName,
        ], $locale);
    }

    /** Hodnota pro `:received` — čas přijetí poptávky v Europe/Prague. */
    public static function received(CarbonInterface $at, ?string $locale = null): string
    {
        $at = CarbonImmutable::instance($at)->setTimezone(self::TIMEZONE);

        return __('home.reply_date.received', [
            'day'        => $at->day,
            'month'      => $at->month,
            'month_name' => $at->locale('en')->monthName,
            'time'       => $at->format('H:i'),
        ], $locale);
    }

    /**
     * Věta z lang s dosazeným datem jako HTML: celý text se escapuje,
     * `:date` se obalí do `<strong class="pd-date">` (bílé, nezlomí se).
     * Ostatní hodnoty (`:email`) se jen escapují.
     *
     * cs a de datum končí tečkou („29. 9.", „29.9."). Na konci věty
     * (`:date.`) je ta tečka zároveň koncem věty — druhá se nepíše.
     */
    public static function sentence(string $key, string $date, array $replace = []): string
    {
        $strong = '<strong class="pd-date">'.e($date).'</strong>';
        $pairs = [':date' => $strong];

        if (str_ends_with($date, '.')) {
            $pairs[':date.'] = $strong;
        }

        foreach ($replace as $name => $value) {
            $pairs[':'.$name] = e($value);
        }

        return strtr(e(__($key)), $pairs);
    }

    /** Velikonoční neděle (anonymní gregoriánský algoritmus, bez ext-calendar). */
    private static function easterSunday(int $year): CarbonImmutable
    {
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $month = intdiv($h + $l - 7 * $m + 114, 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return CarbonImmutable::create($year, $month, $day, 0, 0, 0, self::TIMEZONE);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-345 — slib odpovědi „nejpozději následující pracovní den" i uvnitř článku.
 *
 * Board 26. 9.: „Nikde na webu nechci myšlenku ‚Ozvu se do 24 hodin v pracovní
 * dny‘ ale namísto toho ‚Ozvu se nejpozději následující pracovní den.‘"
 *
 * Zadání OND-345 naměřilo jen `lang/`, ale tatáž věta žije i v DB
 * (`article_translations.content_2`, článek „Kolik stojí web na míru") — ověřeno
 * 26. 9. na produkci: translation id 5 (en), 6 (cs), 248 (de), všechny aktivní
 * a publikované. Texty článků se seederem nepřepisují (`EnsureArticlesSeededSeeder`
 * importuje jen do prázdné tabulky), takže migrace je jediná cesta ven — stejný
 * důvod i stejný tvar jako u 2026_09_22_100000_ond256_reakcni_doba_v_clancich.php.
 *
 * Oproti OND-256 je tu navíc `en`: vlna OND-256 anglickou mutaci neřešila, takže
 * v ní věta zůstala v původním znění z importu (BlogContentEnSeeder).
 *
 * Náhrada je podmíněná — sáhne jen na řádek, kde hledaná věta pořád stojí,
 * takže druhý běh je no-op a pozdější ruční úpravy z Filamentu nepřepíše.
 */
return new class extends Migration
{
    /** locale => ['from' => …, 'to' => …] */
    private const SENTENCES = [
        'cs' => [
            'from' => 'Ozvu se do 24 hodin v pracovní dny a probereme to.',
            'to'   => 'Ozvu se nejpozději následující pracovní den a probereme to.',
        ],
        'de' => [
            'from' => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir gehen es durch.',
            'to'   => 'Ich melde mich spätestens am nächsten Arbeitstag und wir gehen es durch.',
        ],
        'en' => [
            'from' => 'I will get back to you within 24 hours on business days and we will go through it.',
            'to'   => 'I will get back to you by the next business day and we will go through it.',
        ],
    ];

    public function up(): void
    {
        $this->apply('to');
    }

    public function down(): void
    {
        $this->apply('from');
    }

    /**
     * Na nenaplněné DB (čerstvá instalace, `RefreshDatabase` v testech) není
     * co opravovat — zdrojem pravdy je tam BlogContentSeeder / …DeSeeder / …EnSeeder,
     * které už mají nové znění.
     */
    private function apply(string $direction): void
    {
        if (! DB::table('articles')->exists()) {
            return;
        }

        $expected = $direction === 'to' ? 'from' : 'to';

        foreach (self::SENTENCES as $locale => $sentence) {
            $rows = DB::table('article_translations')
                ->where('locale', $locale)
                ->where('content_2', 'like', '%'.$sentence[$expected].'%')
                ->get(['id', 'content_2']);

            foreach ($rows as $row) {
                DB::table('article_translations')
                    ->where('id', $row->id)
                    ->update([
                        'content_2' => str_replace(
                            $sentence[$expected],
                            $sentence[$direction],
                            $row->content_2,
                        ),
                        'updated_at' => now(),
                    ]);
            }
        }
    }
};

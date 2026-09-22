<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-256 bod 3 — sjednocení slibu reakční doby uvnitř textu článku.
 *
 * Board 22. 9. rozhodl jediné znění: „Ozvu se do 24 hodin v pracovní dny."
 * Většina výskytů je v lang souborech, tenhle jeden ale žije v DB
 * (`article_translations.content_2`, článek „Kolik stojí webové stránky").
 * Texty článků se na produkci nepřepisují seederem — `EnsureArticlesSeededSeeder`
 * importuje jen do prázdné tabulky — takže migrace je jediná cesta ven
 * (stejný důvod jako u 2026_09_16_110000_rewrite_blog_content.php).
 *
 * Na rozdíl od té migrace tady NEvoláme celý seeder: měníme jednu větu
 * a přepsat kvůli ní všechny články (včetně pozdějších úprav z Filamentu)
 * by bylo nepřiměřené. Náhrada je proto podmíněná — sáhne jen na řádek,
 * kde původní věta pořád stojí, a druhý běh je no-op.
 *
 * EN mutace článku tuhle větu nemá vůbec (viz BlogContentSeeder:26-28,
 * anglické texty zůstaly z původního importu) — proto tu EN chybí.
 */
return new class extends Migration
{
    /** locale => ['from' => …, 'to' => …] */
    private const SENTENCES = [
        'cs' => [
            'from' => 'Ozvu se do dvou pracovních dnů a probereme to.',
            'to'   => 'Ozvu se do 24 hodin v pracovní dny a probereme to.',
        ],
        'de' => [
            'from' => 'Ich melde mich innerhalb von zwei Werktagen und wir gehen es durch.',
            'to'   => 'Ich melde mich innerhalb von 24 Stunden an Arbeitstagen und wir gehen es durch.',
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
     * co opravovat — zdrojem pravdy je tam BlogContentSeeder / BlogContentDeSeeder,
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

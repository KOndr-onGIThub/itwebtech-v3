<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 / redline OND-262 B3 — „Ich bin kein Umsatzsteuerpflichtiger".
 *
 * Doslovný překlad českého „nejsem plátce DPH". Substantivum se takhle
 * nepoužívá a věta je bez podmětu; tentýž web to o kus dál umí správně
 * (`lang/de/contact.php`). Druhý výskyt téhle vady (ceník) řeší A3-2 v lang.
 *
 * **Řazení je podstatné.** Vlna 1 (2026_09_22_100000_ond256_reakcni_doba_v_clancich)
 * mění v témže odstavci větu o reakční době. Tahle migrace musí běžet až po ní
 * a sahá **jen** na větu o DPH — proto se nehledá celý odstavec, ale jen dvě
 * věty, které vlna 1 nemění. Kdyby se hledal celý odstavec, jedna z migrací by
 * podle pořadí nenašla nic.
 *
 * Text článku žije v DB i v `BlogContentDeSeeder` (článek 3, `content_1`).
 * Seeder je opravený zároveň — jinak by čerstvý seed vrátil staré znění.
 *
 * Podmíněná a idempotentní: druhý běh nenajde staré znění a neudělá nic.
 */
return new class extends Migration
{
    private const ARTICLE_ID = 3;

    private const FROM = 'Ich bin kein Umsatzsteuerpflichtiger. Der Preis, den ich Ihnen nenne, ist endgültig.';

    private const TO = 'Ich bin nicht umsatzsteuerpflichtig. Der Preis, den ich Ihnen nenne, ist ein Endpreis — es kommt keine Mehrwertsteuer hinzu.';

    public function up(): void
    {
        $this->apply(self::FROM, self::TO);
    }

    public function down(): void
    {
        $this->apply(self::TO, self::FROM);
    }

    private function apply(string $search, string $replace): void
    {
        // Čerstvá DB: článek ještě neexistuje, obsah tam dostane
        // EnsureArticlesSeededSeeder → BlogContentDeSeeder už ve správném znění.
        $row = DB::table('article_translations')
            ->where('article_id', self::ARTICLE_ID)
            ->where('locale', 'de')
            ->where('content_1', 'like', '%'.$search.'%')
            ->first(['id', 'content_1']);

        if (! $row) {
            return;
        }

        DB::table('article_translations')
            ->where('id', $row->id)
            ->update([
                'content_1'  => str_replace($search, $replace, $row->content_1),
                'updated_at' => now(),
            ]);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 — zavírací uvozovky v datech (OND-261 Q1, OND-262 B4).
 *
 * Napříč texty stojí správná otevírací uvozovka „ (U+201E), ale zavírá se
 * rovnou palcovou " (U+0022) místo „ typograficky správné “ (U+201C).
 * Stejná vada v češtině i v němčině — obě mutace mají tentýž pár „…“.
 *
 * Plošná záměna všech " by rozbila HTML uvnitř `content_*` (atributy
 * `href="…"`, `class="…"`). Náhrada je proto **podmíněná**: přepíše se jen
 * taková ", které v témže řetězci přímo předchází „ a mezi nimi není žádná
 * další uvozovka ani znak `<`/`>`. Tím se dovnitř HTML tagu nikdy nedostane.
 *
 * `lang/` a šablony tahle migrace **neřeší** — ty vlastní OND-266 (bod 10
 * jeho zadání). Tady jsou jen data.
 *
 * Naměřeno na produkci 22. 9. 2026 před nasazením (suchý běh téhož regexu):
 *   portfolio_project_translations  46 náhrad na 31 řádcích (24 cs + 22 de)
 *   article_translations             6 náhrad na  2 řádcích ( 3 cs +  3 de)
 * Po náhradě nezůstala žádná nespárovaná „. EN mutace nemá ani jednu „,
 * takže se jí migrace nedotkne — anglická typografie je “…” a řeší se jinde.
 *
 * Idempotentní: druhý běh nenajde žádnou " za „ a neudělá nic.
 */
return new class extends Migration
{
    private const PATTERN = '/„([^„“"<>]*)"/u';

    /** tabulka => sloupce */
    private const TARGETS = [
        'portfolio_project_translations' => [
            'title', 'subtitle', 'summary', 'description',
            'challenge', 'solution', 'result',
            'meta_title', 'meta_description',
        ],
        'article_translations' => [
            'title', 'description', 'perex',
            'content_1', 'content_mid', 'content_2', 'bonus', 'extra',
        ],
    ];

    public function up(): void
    {
        $this->apply(self::PATTERN, '„$1“');
    }

    /**
     * Záměrně bez rollbacku. Zpětná náhrada „…“ → „…" by nerozlišila pár,
     * který tahle migrace opravila, od páru, který byl správně už předtím
     * (`article_translations` id 1 má „s“ korektně od začátku) — rollback by
     * tedy vadu nově zavedl. Vrácení typografie by byla nová migrace.
     */
    public function down(): void
    {
        //
    }

    private function apply(string $pattern, string $replacement): void
    {
        foreach (self::TARGETS as $table => $columns) {
            // Čerstvá DB: tabulka je prázdná, není co opravovat. Zdrojem
            // pravdy je tam `docs/portfolio-data.yaml`, resp. seedery blogu.
            if (! DB::table($table)->exists()) {
                continue;
            }

            DB::table($table)->chunkById(200, function ($rows) use ($table, $columns, $pattern, $replacement) {
                foreach ($rows as $row) {
                    $changes = [];

                    foreach ($columns as $column) {
                        $value = $row->{$column} ?? null;
                        if (! is_string($value) || ! str_contains($value, '„')) {
                            continue;
                        }

                        $fixed = preg_replace($pattern, $replacement, $value);
                        if ($fixed !== null && $fixed !== $value) {
                            $changes[$column] = $fixed;
                        }
                    }

                    if ($changes) {
                        DB::table($table)->where('id', $row->id)
                            ->update($changes + ['updated_at' => now()]);
                    }
                }
            });
        }
    }
};

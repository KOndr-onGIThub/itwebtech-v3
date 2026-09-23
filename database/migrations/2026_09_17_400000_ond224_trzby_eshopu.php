<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-224 — obchodní výsledek e-shopu shop.pitarena.cz.
 *
 * Doplňuje `result` a jednu odrážku `outcomes` u projektu `pitarena-eshop`
 * o čísla, která případovce dosud chyběla, a doplňuje `year`.
 *
 * Zdroj čísel: administrace e-shopu (snímek od Ondry, příloha komentáře na
 * OND-224 ze 17. 9. 2026) — 116 objednávek, 468 202 Kč, průměrná objednávka
 * 4 036 Kč. E-shop byl spuštěn 1. 7. 2026, snímek končí 17. 9. 2026, jde tedy
 * o první dva a půl měsíce provozu.
 *
 * Proč NE Google Analytics: GA služba `pitarena-shop` ukazovala za stejné
 * období 25 klíčových událostí a 185 531 Kč. Ondra 17. 9. 2026 potvrdil, že
 * tohle číslo na administraci nesedí, takže se nepoužívá nikde — fakturace je
 * tvrdší zdroj než měření závislé na souhlasu s cookies. Rozbor je
 * v dokumentu `overeni-trzeb-eshopu` na OND-224.
 *
 * Co se z podkladu NEPUBLIKUJE: hrubá marže (91 156 Kč / 23,5 %) — je to
 * citlivý údaj klienta a pro případovku nemá vypovídací hodnotu.
 *
 * Poznámka k období: snímek má filtr „1. 1. – 17. 9.", tedy širší okno než
 * provoz e-shopu. Před spuštěním 1. 7. 2026 e-shop žádné objednávky generovat
 * nemohl, takže jsou obě čísla totožná; formulace uvádí skutečné období
 * provozu, ne rozsah filtru.
 *
 * Aktualizace jsou PODMÍNĚNÉ — přepíšou hodnotu jen tehdy, když v DB stále
 * stojí přesně původní text (ochrana ručních úprav z Filamentu). Na nenaplněné
 * DB (čerstvá instalace, `RefreshDatabase` v testech) migrace nedělá nic:
 * zdrojem pravdy je `docs/portfolio-data.yaml`, který má stejné texty.
 */
return new class extends Migration
{
    private const SLUG = 'pitarena-eshop';

    /** Rok spuštění e-shopu. Do OND-224 nebyl doložený, proto v DB stálo `null`. */
    private const YEAR = 2026;

    /** locale => ['from' => ..., 'to' => ...] pro pole `result` */
    private const RESULT_CHANGES = [
        'cs' => [
            'from' => 'E-shop dnes nabízí 4 551 produktů v 695 kategoriích a katalog náhradních dílů rozpadnutý podle 19 modelů motocyklů YCF — zákazník klikne na svůj model a vidí jen díly, které na něj sedí. I s tímhle rozsahem drží na desktopu 99/100 v Lighthouse Performance a hlavní obsah se vykreslí do 0,9 s (měřeno lokálním Lighthouse 13.4.1, 16. 9. 2026).',
            'to'   => 'E-shop dnes nabízí 4 551 produktů v 695 kategoriích a katalog náhradních dílů rozpadnutý podle 19 modelů motocyklů YCF — zákazník klikne na svůj model a vidí jen díly, které na něj sedí. I s tímhle rozsahem drží na desktopu 99/100 v Lighthouse Performance a hlavní obsah se vykreslí do 0,9 s (měřeno lokálním Lighthouse 13.4.1, 16. 9. 2026). Za první dva a půl měsíce provozu (1. 7. – 17. 9. 2026) jím prošlo 116 objednávek za 468 202 Kč, průměrná objednávka 4 036 Kč (údaje z administrace e-shopu).',
        ],
        'en' => [
            'from' => 'The shop now offers 4,551 products in 695 categories, with the spare-parts catalogue broken down across 19 YCF motorcycle models — the customer clicks their model and sees only parts that fit. Even at that scale it holds 99/100 in Lighthouse Performance on desktop, with the main content rendering in under 0.9 s (measured with local Lighthouse 13.4.1 on 16 September 2026).',
            'to'   => 'The shop now offers 4,551 products in 695 categories, with the spare-parts catalogue broken down across 19 YCF motorcycle models — the customer clicks their model and sees only parts that fit. Even at that scale it holds 99/100 in Lighthouse Performance on desktop, with the main content rendering in under 0.9 s (measured with local Lighthouse 13.4.1 on 16 September 2026). In its first two and a half months (1 July – 17 September 2026) it handled 116 orders worth CZK 468,202 in total, averaging CZK 4,036 per order (figures from the shop administration).',
        ],
        'de' => [
            'from' => 'Der Shop bietet heute 4.551 Produkte in 695 Kategorien, der Ersatzteilkatalog ist nach 19 YCF-Motorradmodellen aufgeteilt — Kunden klicken ihr Modell an und sehen nur passende Teile. Auch in dieser Größe hält er auf dem Desktop 99/100 in Lighthouse Performance, der Hauptinhalt erscheint in unter 0,9 s (gemessen mit lokalem Lighthouse 13.4.1 am 16.09.2026).',
            'to'   => 'Der Shop bietet heute 4.551 Produkte in 695 Kategorien, der Ersatzteilkatalog ist nach 19 YCF-Motorradmodellen aufgeteilt — Kunden klicken ihr Modell an und sehen nur passende Teile. Auch in dieser Größe hält er auf dem Desktop 99/100 in Lighthouse Performance, der Hauptinhalt erscheint in unter 0,9 s (gemessen mit lokalem Lighthouse 13.4.1 am 16.09.2026). In den ersten zweieinhalb Monaten (1.7. – 17.9.2026) wickelte er 116 Bestellungen im Gesamtwert von 468.202 CZK ab, im Schnitt 4.036 CZK pro Bestellung (Daten aus der Shop-Administration).',
        ],
    ];

    /** locale => popisek nové odrážky v `outcomes` (přidává se na konec) */
    private const NEW_OUTCOME = [
        'cs' => '116 objednávek za 468 202 Kč za první dva a půl měsíce provozu (1. 7. – 17. 9. 2026)',
        'en' => '116 orders worth CZK 468,202 in the first two and a half months (1 July – 17 September 2026)',
        'de' => '116 Bestellungen im Wert von 468.202 CZK in den ersten zweieinhalb Monaten (1.7. – 17.9.2026)',
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $this->applyResult('to');
        $this->applyYear('to');
        $this->addOutcome();
    }

    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $this->removeOutcome();
        $this->applyYear('from');
        $this->applyResult('from');
    }

    private function applyResult(string $direction): void
    {
        $projectId = $this->projectId();
        if (! $projectId) {
            return;
        }

        $expected = $direction === 'to' ? 'from' : 'to';

        foreach (self::RESULT_CHANGES as $locale => $texts) {
            DB::table('portfolio_project_translations')
                ->where('project_id', $projectId)
                ->where('locale', $locale)
                ->where('result', $texts[$expected])
                ->update(['result' => $texts[$direction], 'updated_at' => now()]);
        }
    }

    private function applyYear(string $direction): void
    {
        $query = DB::table('portfolio_projects')->where('slug', self::SLUG);

        // `where('year', null)` by se přeložilo na `year = NULL` a nikdy
        // nesedlo — původní stav se proto hledá přes whereNull().
        $direction === 'to'
            ? $query->whereNull('year')
            : $query->where('year', self::YEAR);

        $query->update([
            'year'       => $direction === 'to' ? self::YEAR : null,
            'updated_at' => now(),
        ]);
    }

    /**
     * Odrážka se přidává na konec seznamu — stejné pořadí má
     * `docs/portfolio-data.yaml`, takže re-seed i migrace dají shodný výsledek.
     */
    private function addOutcome(): void
    {
        $projectId = $this->projectId();
        if (! $projectId || $this->newOutcomeId($projectId)) {
            return;
        }

        $now = now();
        $sortOrder = (int) DB::table('portfolio_project_outcomes')
            ->where('project_id', $projectId)
            ->count();

        $outcomeId = DB::table('portfolio_project_outcomes')->insertGetId([
            'project_id' => $projectId,
            'sort_order' => $sortOrder,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (self::NEW_OUTCOME as $locale => $label) {
            DB::table('portfolio_project_outcome_translations')->insert([
                'outcome_id'  => $outcomeId,
                'locale'      => $locale,
                'label'       => $label,
                'value'       => '',
                'description' => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }

    private function removeOutcome(): void
    {
        $projectId = $this->projectId();
        if (! $projectId) {
            return;
        }

        $outcomeId = $this->newOutcomeId($projectId);
        if (! $outcomeId) {
            return;
        }

        // Překlady odejdou přes cascadeOnDelete.
        DB::table('portfolio_project_outcomes')->where('id', $outcomeId)->delete();
    }

    /** Id odrážky založené touhle migrací, poznané podle českého popisku. */
    private function newOutcomeId(int $projectId): ?int
    {
        $id = DB::table('portfolio_project_outcomes as o')
            ->join('portfolio_project_outcome_translations as t', 't.outcome_id', '=', 'o.id')
            ->where('o.project_id', $projectId)
            ->where('t.locale', 'cs')
            ->where('t.label', self::NEW_OUTCOME['cs'])
            ->value('o.id');

        return $id ? (int) $id : null;
    }

    private function projectId(): ?int
    {
        $id = DB::table('portfolio_projects')->where('slug', self::SLUG)->value('id');

        return $id ? (int) $id : null;
    }
};

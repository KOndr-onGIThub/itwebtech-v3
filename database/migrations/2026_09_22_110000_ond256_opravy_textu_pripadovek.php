<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-256 bod 9 — pravopisné a stylistické opravy v textech případovek.
 *
 * Texty detailů projektů žijí v `portfolio_project_translations`, ne v lang
 * souborech, a `PortfolioSeeder` se po prvním naplnění DB přeskakuje — úprava
 * `docs/portfolio-data.yaml` se proto na produkci sama neprojeví. Data migrace
 * je jediná cesta ven (precedent 2026_09_16_100000_rename_jargon_portfolio_tags.php).
 * YAML je opravený zároveň, aby čerstvý seed dával stejný výsledek.
 *
 * Náhrada je podmíněná a slovní: sáhne jen na řádek, kde původní tvar pořád
 * stojí. Druhý běh je no-op a ruční úpravy z Filamentu nepřepíše (pokud text
 * někdo přepsal, `LIKE` nesedne a řádek se přeskočí).
 *
 * Pozor na dva detaily, které tu jsou schválně:
 *  - `chybovo` se nedá nahradit jako holé slovo — v sousedních odstavcích
 *    stojí správné „chybovost". Proto se matchuje i s navazujícím textem.
 *  - Hledané řetězce nesmí přeskakovat konec řádku v YAML: `>-` skládá
 *    odřádkování na mezery, takže v DB je text jednořádkový, ale v YAML ne.
 */
return new class extends Migration
{
    /** slug => locale => field => [from, to] */
    private const FIXES = [
        'clanek-motorkari-cz' => ['cs' => ['result' => [
            'motopotálu',
            'motoportálu',
        ]]],
        'barana' => ['cs' => ['description' => [
            'Postavil jsem premiové',
            'Postavil jsem prémiové',
        ]]],
        'josefopa' => ['cs' => ['challenge' => [
            'visačku stavební firmy',
            'vizitku stavební firmy',
        ]]],
        'frl-creator' => ['cs' => ['challenge' => [
            'chybovo a se zbytečnou',
            'chybově a se zbytečnou',
        ]]],
        'choccoboard' => ['cs' => ['solution' => [
            'se k ní dostaneš odkudkoli',
            'se k ní dostanete odkudkoli',
        ]]],
        'nove-interiery' => ['cs' => ['result' => [
            'si zákazníci dopředu vědomí, jak',
            'zákazníci dopředu vědí, jak',
        ]]],
    ];

    public function up(): void
    {
        $this->apply(false);
    }

    public function down(): void
    {
        $this->apply(true);
    }

    /**
     * Na nenaplněné DB (čerstvá instalace, `RefreshDatabase` v testech) není
     * co opravovat — zdrojem pravdy je tam `docs/portfolio-data.yaml`, který
     * má po této změně rovnou správné znění.
     */
    private function apply(bool $reverse): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::FIXES as $slug => $locales) {
            $projectId = DB::table('portfolio_projects')->where('slug', $slug)->value('id');
            if (! $projectId) {
                continue;
            }

            foreach ($locales as $locale => $fields) {
                foreach ($fields as $field => [$from, $to]) {
                    [$search, $replace] = $reverse ? [$to, $from] : [$from, $to];

                    $row = DB::table('portfolio_project_translations')
                        ->where('project_id', $projectId)
                        ->where('locale', $locale)
                        ->where($field, 'like', '%'.$search.'%')
                        ->first(['id', $field]);

                    if (! $row) {
                        continue;
                    }

                    DB::table('portfolio_project_translations')
                        ->where('id', $row->id)
                        ->update([
                            $field      => str_replace($search, $replace, $row->{$field}),
                            'updated_at' => now(),
                        ]);
                }
            }
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-256 bod 6 — obrázek projektu „PitAréna — e-shop".
 *
 * `pitarena-eshop` byl jediný projekt bez jediného řádku v
 * `portfolio_project_screenshots` (založila ho migrace OND-208, která
 * screenshot neměla). Dopad byl na 12 místech: karta v /projekty,
 * hero detailu i karty „Další projekty" — všude buď šedý placeholder,
 * nebo u detailu vynechaná celá sekce s vizuálem.
 *
 * Snímek je vlastní záběr shop.pitarena.cz z 22. 9. 2026, uložený jako
 * `resources/img/projects/pitarena-eshop/hero-1.webp` (1920×1200, poměr 1,6).
 * Poměr >= 1,5 → `screenshot_gallery_role()` ho bere jako `wide`, takže
 * slouží jako lead band na detailu; do malých karet ho pustí
 * `portfolio_card_thumbnail()` přes větev `type = hero`.
 *
 * Inertní na nenaplněné DB — tam projekt vytváří PortfolioSeeder z YAMLu,
 * který už snímek obsahuje. Idempotentní: druhý běh nic nepřidá.
 */
return new class extends Migration
{
    private const SLUG = 'pitarena-eshop';
    private const PATH = 'projects/pitarena-eshop/hero-1.webp';

    private const ALT = [
        'cs' => 'PitAréna – e-shop s pitbike motorkami YCF a náhradními díly',
        'en' => 'PitArena – e-shop with YCF pit bikes and spare parts',
        'de' => 'PitArena – Onlineshop mit YCF-Pitbikes und Ersatzteilen',
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $projectId = DB::table('portfolio_projects')->where('slug', self::SLUG)->value('id');
        if (! $projectId) {
            return;
        }

        // Projekt už nějaký snímek má (ruční upload z Filamentu) → nesahat.
        $exists = DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->exists();
        if ($exists) {
            return;
        }

        $now = now();

        $screenshotId = DB::table('portfolio_project_screenshots')->insertGetId([
            'project_id' => $projectId,
            'path'       => self::PATH,
            'type'       => 'hero',
            'sort_order' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (self::ALT as $locale => $alt) {
            DB::table('portfolio_project_screenshot_translations')->insert([
                'screenshot_id' => $screenshotId,
                'locale'        => $locale,
                'alt'           => $alt,
                'caption'       => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        $projectId = DB::table('portfolio_projects')->where('slug', self::SLUG)->value('id');
        if (! $projectId) {
            return;
        }

        // Mazat jen ten snímek, který tahle migrace přidala.
        $ids = DB::table('portfolio_project_screenshots')
            ->where('project_id', $projectId)
            ->where('path', self::PATH)
            ->pluck('id');

        if ($ids->isEmpty()) {
            return;
        }

        DB::table('portfolio_project_screenshot_translations')
            ->whereIn('screenshot_id', $ids)
            ->delete();

        DB::table('portfolio_project_screenshots')->whereIn('id', $ids)->delete();
    }
};

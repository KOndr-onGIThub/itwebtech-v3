<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Import starých dat ze zálohy databáze.
 *
 * Spuštění:
 *   php artisan db:seed --class=ImportOldDataSeeder
 *
 * Předpoklady:
 *   - Migrace již proběhly (php artisan migrate)
 *   - SQL soubory jsou v database/sql/
 *
 * Mapování:
 *   articles, article_slugs, article_translations  → přímý import (struktura shodná)
 *   projects, projects_screens                      → transformace přes dočasné tabulky
 */
class ImportOldDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->importArticles();
        $this->importArticleSlugs();
        $this->importArticleTranslations();
        $this->importProjectsViaSql();
    }

    // -------------------------------------------------------------------------
    // ČLÁNKY — struktura shodná se starým webem, přímý import ze SQL souboru
    // -------------------------------------------------------------------------

    private function importArticles(): void
    {
        $this->command->info('Importuji articles...');
        $this->executeSqlFile('articles.sql');
        $count = DB::table('articles')->count();
        $this->command->info("  -> {$count} článků importováno.");
    }

    private function importArticleSlugs(): void
    {
        $this->command->info('Importuji article_slugs...');
        $this->executeSqlFile('article_slugs.sql');
        $count = DB::table('article_slugs')->count();
        $this->command->info("  -> {$count} slugů importováno.");
    }

    private function importArticleTranslations(): void
    {
        $this->command->info('Importuji article_translations...');
        $this->executeSqlFile('article_translations.sql');
        $count = DB::table('article_translations')->count();
        $this->command->info("  -> {$count} překladů článků importováno.");
    }

    // -------------------------------------------------------------------------
    // PROJEKTY — transformace ze staré struktury na novou přes dočasné tabulky
    // -------------------------------------------------------------------------

    private function importProjectsViaSql(): void
    {
        $this->command->info('Importuji projects a project_screens...');

        // 1. Vytvoř dočasné tabulky
        $this->executeSqlFile('import_projects.sql');

        // 2. Naplň dočasné tabulky starými daty
        $this->executeSqlFileIntoTable('projects.sql', '_old_projects');
        $this->executeSqlFileIntoTable('projects_screens.sql', '_old_project_screens');

        // 3. Transformuj do nových tabulek
        DB::statement("
            INSERT IGNORE INTO `projects`
                (id, published, position, kind, price_czk, price_eur, comparison,
                 img_before, img_after, customer, client_photo, client_name, client_role,
                 testimonial_source_img, testimonial_src, created_at, updated_at)
            SELECT
                id, 1 AS published, sorting AS position, kind, price_czk, price_eur, comparison,
                img_before, img_after, customer, client_photo, client_name, client_role,
                testimonial_source_img, testimonial_src, created_at, updated_at
            FROM _old_projects
        ");

        DB::statement("
            INSERT IGNORE INTO `project_translations`
                (project_id, locale, active, title, og_img, description, content,
                 testimonial, client_says, cta, created_at, updated_at)
            SELECT
                id AS project_id, 'cs' AS locale, 1 AS active,
                name AS title, og_img, description, content,
                testimonial, client_says, cta, created_at, updated_at
            FROM _old_projects
        ");

        DB::statement("
            INSERT IGNORE INTO `project_slugs`
                (project_id, slug, locale, active, created_at, updated_at)
            SELECT
                id AS project_id, url AS slug, 'cs' AS locale, 1 AS active,
                created_at, updated_at
            FROM _old_projects
        ");

        DB::statement("
            INSERT IGNORE INTO `project_screens`
                (id, project_id, is_video, video_url, screen_shot, position,
                 title, description, created_at, updated_at)
            SELECT
                id, id_project AS project_id, is_video, video_url, screen_shot,
                sorting AS position, title, description, created_at, updated_at
            FROM _old_project_screens
        ");

        $pCount = DB::table('projects')->count();
        $sCount = DB::table('project_screens')->count();
        $this->command->info("  -> {$pCount} projektů + {$sCount} screenshotů importováno.");
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Spustí SQL soubor. Soubor může obsahovat více INSERT/CREATE příkazů.
     * Příkazy jsou odděleny ;\n (konec příkazu + nový řádek) nebo prázdným řádkem.
     */
    private function executeSqlFile(string $filename): void
    {
        $path = database_path("sql/{$filename}");

        if (! file_exists($path)) {
            $this->command->warn("  ! SQL soubor nenalezen: {$path}");
            return;
        }

        $sql = file_get_contents($path);

        // Spustit celý soubor jako jeden příkaz (pokud je to jediný INSERT)
        // nebo rozdělit na příkazy oddělené ;\n\n
        $statements = $this->splitSqlStatements($sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) {
                continue;
            }
            DB::unprepared($statement);
        }
    }

    /**
     * Načte SQL soubor, změní název cílové tabulky a spustí INSERT.
     * Určeno pro načtení dat ze starého SQL dumpu do dočasné tabulky.
     */
    private function executeSqlFileIntoTable(string $filename, string $targetTable): void
    {
        $path = database_path("sql/{$filename}");

        if (! file_exists($path)) {
            $this->command->warn("  ! SQL soubor nenalezen: {$path}");
            return;
        }

        $sql = file_get_contents($path);

        // Nahradit původní název tabulky názvem dočasné tabulky
        // Původní název je v prvním INSERT INTO `tablename`
        $sql = preg_replace(
            '/INSERT INTO\s+`[^`]+`/',
            "INSERT INTO `{$targetTable}`",
            $sql
        );

        $statements = $this->splitSqlStatements($sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) {
                continue;
            }
            DB::unprepared($statement);
        }
    }

    /**
     * Rozdělí SQL soubor na jednotlivé příkazy.
     * Hledá hranice na konci každého příkazu (;) nebo prázdnou řádkou.
     */
    private function splitSqlStatements(string $sql): array
    {
        // Rozdělit na příkazy oddělené prázdnou řádkou
        $parts = preg_split('/\n\s*\n/', $sql, -1, PREG_SPLIT_NO_EMPTY);

        $statements = [];
        $buffer = '';

        foreach ($parts as $part) {
            $buffer .= ($buffer ? "\n\n" : '') . $part;

            // Pokud buffer končí ; nebo );, jde o ukončený příkaz
            if (preg_match('/;\s*$/', trim($buffer))) {
                $statements[] = $buffer;
                $buffer = '';
            }
        }

        if (! empty(trim($buffer))) {
            $statements[] = $buffer;
        }

        return $statements;
    }
}

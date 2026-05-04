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
 *   - Tabulky articles, article_translations, article_slugs, projects, ... jsou prázdné
 *   - Data ze starého webu jsou k dispozici v poli níže nebo načtena z SQL dumpu
 *
 * Mapování tabulek (stará -> nová):
 *   articles              -> articles (1:1, zachováno _lft/_rgt/parent_id)
 *   article_slugs         -> article_slugs (1:1)
 *   article_translations  -> article_translations (1:1)
 *   projects              -> projects + project_translations + project_slugs
 *   projects_screens      -> project_screens (id_project->project_id, sorting->position)
 */
class ImportOldDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->importArticles();
        $this->importArticleSlugs();
        $this->importArticleTranslations();
        $this->importProjects();
        $this->importProjectScreens();
    }

    // -------------------------------------------------------------------------
    // ČLÁNKY — struktura je identická s Twill, přímý import
    // -------------------------------------------------------------------------

    private function importArticles(): void
    {
        $this->command->info('Importuji articles...');

        // Data ze starého SQL dumpu — tabulka `articles`
        // Sloupce: id, deleted_at, created_at, updated_at, published, position, publish_start_date, publish_end_date, _lft, _rgt, parent_id
        $rows = $this->getArticlesData();

        foreach ($rows as $row) {
            DB::table('articles')->insertOrIgnore([
                'id'          => $row['id'],
                'published'   => $row['published'],
                'position'    => $row['position'],
                '_lft'        => $row['_lft'],
                '_rgt'        => $row['_rgt'],
                'parent_id'   => $row['parent_id'],
                'deleted_at'  => $row['deleted_at'],
                'created_at'  => $row['created_at'],
                'updated_at'  => $row['updated_at'],
            ]);
        }

        $this->command->info('  -> ' . count($rows) . ' článků importováno.');
    }

    private function importArticleSlugs(): void
    {
        $this->command->info('Importuji article_slugs...');

        $rows = $this->getArticleSlugsData();

        foreach ($rows as $row) {
            DB::table('article_slugs')->insertOrIgnore([
                'id'         => $row['id'],
                'article_id' => $row['article_id'],
                'slug'       => $row['slug'],
                'locale'     => $row['locale'],
                'active'     => $row['active'],
                'deleted_at' => $row['deleted_at'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
        }

        $this->command->info('  -> ' . count($rows) . ' slugů importováno.');
    }

    private function importArticleTranslations(): void
    {
        $this->command->info('Importuji article_translations...');

        $rows = $this->getArticleTranslationsData();

        foreach ($rows as $row) {
            DB::table('article_translations')->insertOrIgnore([
                'id'          => $row['id'],
                'article_id'  => $row['article_id'],
                'locale'      => $row['locale'],
                'active'      => $row['active'],
                'title'       => $row['title'],
                'description' => $row['description'],
                'img_preview' => $row['img_preview'],
                'img_main'    => $row['img_main'],
                'perex'       => $row['perex'],
                'content_1'   => $row['content_1'],
                'content_mid' => $row['content_mid'],
                'img_mid'     => $row['img_mid'],
                'content_2'   => $row['content_2'],
                'img_end'     => $row['img_end'],
                'bonus'       => $row['bonus'],
                'extra'       => $row['extra'],
                'deleted_at'  => $row['deleted_at'],
                'created_at'  => $row['created_at'],
                'updated_at'  => $row['updated_at'],
            ]);
        }

        $this->command->info('  -> ' . count($rows) . ' překladů článků importováno.');
    }

    // -------------------------------------------------------------------------
    // PROJEKTY — stará tabulka se rozdělí do 3 nových tabulek
    // -------------------------------------------------------------------------

    private function importProjects(): void
    {
        $this->command->info('Importuji projects...');

        $rows = $this->getProjectsData();

        foreach ($rows as $row) {
            // 1. Základní tabulka projects (nepřekládané sloupce)
            DB::table('projects')->insertOrIgnore([
                'id'                     => $row['id'],
                'published'              => 1, // staré projekty jsou zveřejněné
                'position'               => $row['sorting'],
                'kind'                   => $row['kind'],
                'price_czk'              => $row['price_czk'],
                'price_eur'              => $row['price_eur'],
                'comparison'             => $row['comparison'],
                'img_before'             => $row['img_before'],
                'img_after'              => $row['img_after'],
                'customer'               => $row['customer'],
                'client_photo'           => $row['client_photo'],
                'client_name'            => $row['client_name'],
                'client_role'            => $row['client_role'],
                'testimonial_source_img' => $row['testimonial_source_img'],
                'testimonial_src'        => $row['testimonial_src'],
                'created_at'             => $row['created_at'],
                'updated_at'             => $row['updated_at'],
            ]);

            // 2. Překlad (cs) — textový obsah
            DB::table('project_translations')->insertOrIgnore([
                'project_id'  => $row['id'],
                'locale'      => 'cs',
                'active'      => 1,
                'title'       => $row['name'],
                'og_img'      => $row['og_img'],
                'description' => $row['description'],
                'content'     => $row['content'],
                'testimonial' => $row['testimonial'],
                'client_says' => $row['client_says'],
                'cta'         => $row['cta'],
                'created_at'  => $row['created_at'],
                'updated_at'  => $row['updated_at'],
            ]);

            // 3. Slug (cs) — z původního sloupce 'url'
            DB::table('project_slugs')->insertOrIgnore([
                'project_id' => $row['id'],
                'slug'       => $row['url'],
                'locale'     => 'cs',
                'active'     => 1,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
        }

        $this->command->info('  -> ' . count($rows) . ' projektů importováno.');
    }

    private function importProjectScreens(): void
    {
        $this->command->info('Importuji project_screens...');

        $rows = $this->getProjectScreensData();

        foreach ($rows as $row) {
            DB::table('project_screens')->insertOrIgnore([
                'id'          => $row['id'],
                'project_id'  => $row['id_project'],   // přejmenování
                'is_video'    => $row['is_video'],
                'video_url'   => $row['video_url'],
                'screen_shot' => $row['screen_shot'],
                'position'    => $row['sorting'],       // přejmenování
                'title'       => $row['title'],
                'description' => $row['description'],
                'created_at'  => $row['created_at'],
                'updated_at'  => $row['updated_at'],
            ]);
        }

        $this->command->info('  -> ' . count($rows) . ' screenshotů importováno.');
    }

    // =========================================================================
    // DATA — vložit sem hodnoty z SQL dumpu nebo načíst z externího souboru
    // =========================================================================

    /**
     * INSTRUKCE: Sem vložte data z tabulky `articles` ze starého SQL dumpu.
     *
     * Každý prvek pole musí mít klíče:
     *   id, deleted_at, created_at, updated_at, published, position,
     *   publish_start_date, publish_end_date, _lft, _rgt, parent_id
     */
    private function getArticlesData(): array
    {
        // TODO: Vložit data z SQL dumpu (tabulka `articles`)
        // Příklad:
        // return [
        //     ['id' => 1, 'deleted_at' => null, 'created_at' => '2023-08-31 07:37:55', 'updated_at' => '2025-12-18 16:35:44', 'published' => 1, 'position' => 11, 'publish_start_date' => null, 'publish_end_date' => null, '_lft' => 5, '_rgt' => 6, 'parent_id' => null],
        // ];
        return [];
    }

    /**
     * INSTRUKCE: Sem vložte data z tabulky `article_slugs`.
     *
     * Každý prvek pole musí mít klíče:
     *   id, article_id, deleted_at, created_at, updated_at, slug, locale, active
     */
    private function getArticleSlugsData(): array
    {
        // TODO: Vložit data z SQL dumpu (tabulka `article_slugs`)
        return [];
    }

    /**
     * INSTRUKCE: Sem vložte data z tabulky `article_translations`.
     *
     * Každý prvek pole musí mít klíče:
     *   id, article_id, deleted_at, created_at, updated_at, locale, active,
     *   title, description, img_preview, img_main, perex, content_1, content_mid,
     *   img_mid, content_2, img_end, bonus, extra
     */
    private function getArticleTranslationsData(): array
    {
        // TODO: Vložit data z SQL dumpu (tabulka `article_translations`)
        return [];
    }

    /**
     * INSTRUKCE: Sem vložte data z tabulky `projects`.
     *
     * Každý prvek pole musí mít klíče:
     *   id, url, kind, price_czk, price_eur, comparison, img_before, img_after,
     *   customer, name, sorting, img, og_img, description, content, testimonial,
     *   client_photo, client_name, client_role, client_says, testimonial_source_img,
     *   testimonial_src, cta, created_at, updated_at
     */
    private function getProjectsData(): array
    {
        // TODO: Vložit data z SQL dumpu (tabulka `projects`)
        return [];
    }

    /**
     * INSTRUKCE: Sem vložte data z tabulky `projects_screens`.
     *
     * Každý prvek pole musí mít klíče:
     *   id, id_project, is_video, video_url, screen_shot, sorting,
     *   title, description, created_at, updated_at
     */
    private function getProjectScreensData(): array
    {
        // TODO: Vložit data z SQL dumpu (tabulka `projects_screens`)
        return [];
    }
}

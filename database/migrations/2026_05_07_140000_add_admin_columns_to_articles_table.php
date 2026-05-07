<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Doplnění master sloupců pro Filament admin (OND-87).
     *
     * - `slug`: master jazyk-neutrální slug (synchronizován s aktivním cs záznamem
     *   v `article_slugs`). Public render zůstává na `article_slugs` lookupu kvůli
     *   per-locale aliasům a 301 redirectům.
     * - `published_at`: explicitní datum publikace pro řazení v listu/blogu.
     *   Backfill z `publish_start_date` nebo `created_at`.
     * - `image_url`: master featured image (storage path), používaná napříč locale.
     * - `author`: prostý textový label autora (default ze configu `admin.author`).
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->timestamp('published_at')->nullable()->after('publish_end_date');
            $table->string('image_url')->nullable()->after('published_at');
            $table->string('author')->nullable()->after('image_url');

            $table->unique('slug');
            $table->index('published_at');
        });

        // Backfill `slug` z aktivního cs záznamu v `article_slugs` (pokud existuje).
        $rows = DB::table('article_slugs')
            ->select('article_id', 'slug')
            ->where('locale', 'cs')
            ->where('active', true)
            ->whereNull('deleted_at')
            ->get();
        foreach ($rows as $row) {
            DB::table('articles')->where('id', $row->article_id)->update(['slug' => $row->slug]);
        }

        // Backfill `published_at` z `publish_start_date` nebo `created_at`.
        DB::statement(<<<'SQL'
            UPDATE articles
            SET published_at = COALESCE(publish_start_date, created_at)
            WHERE published_at IS NULL
        SQL);
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['published_at']);
            $table->dropColumn(['slug', 'published_at', 'image_url', 'author']);
        });
    }
};

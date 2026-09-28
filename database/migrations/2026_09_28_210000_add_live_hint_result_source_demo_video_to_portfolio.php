<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-449 — nové nepovinné sloupce případovek.
 *
 *  - `live_hint` (B-05): jedna věta pod „Výsledkem“, co si na živém webu vyzkoušet.
 *    Prázdná = obecná věta `projects.detail.live_hint_default`.
 *  - `result_as_of` + `result_source` (B-09): řádek „Stav k … Zdroj: …“ pod výsledkem.
 *    Datum je jedno pro všechny jazyky, zdroj obsahuje slova, proto je v překladech.
 *  - `demo_video` (B-07b): základ jména videa v `public/videos/portfolio/`
 *    (např. `barana-demo`). Vyplněný = pod prvním blokem galerie se ukáže video.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->date('result_as_of')->nullable()->after('duration');
            $table->string('demo_video', 191)->nullable()->after('result_as_of');
        });

        Schema::table('portfolio_project_translations', function (Blueprint $table) {
            $table->string('result_source', 255)->nullable()->after('result');
            $table->text('live_hint')->nullable()->after('result_source');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_project_translations', function (Blueprint $table) {
            $table->dropColumn(['result_source', 'live_hint']);
        });

        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropColumn(['result_as_of', 'demo_video']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-209: slug per locale pro portfolio projekty.
 *
 * Do teď byl slug jen jeden (`portfolio_projects.slug`) a sdílený všemi
 * jazyky — německé URL tak měly české slugy (`/de/projekte/animace-delejme`).
 *
 * `portfolio_project_translations.slug` je nepovinný override:
 *   - NULL  → jazyk používá jazyk-neutrální `portfolio_projects.slug`
 *             (tak to zůstává u značek: pitarena, barana, strechy-zajic…),
 *   - vyplněno → jazyk má vlastní slug (`/de/projekte/aufmerksamkeits-animation`).
 *
 * Unikátnost je per locale; NULL se v unique indexu neduplikuje ani v MySQL,
 * ani v SQLite, takže většina překladů může zůstat prázdná.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_project_translations', function (Blueprint $table) {
            $table->string('slug', 191)->nullable()->after('locale');

            $table->unique(['locale', 'slug'], 'ppt_locale_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_project_translations', function (Blueprint $table) {
            $table->dropUnique('ppt_locale_slug_unique');
            $table->dropColumn('slug');
        });
    }
};

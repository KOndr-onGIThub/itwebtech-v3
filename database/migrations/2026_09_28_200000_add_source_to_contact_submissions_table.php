<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-448 (B-01): homepage i /kontakt posílají tentýž formulář na
 * `POST /contact`. `source` říká, odkud poptávka přišla (`home` /
 * `contact`). Nullable — starší záznamy zdroj nemají a nic se jim nedopočítává.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table): void {
            $table->string('source', 20)->nullable()->after('locale');
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table): void {
            $table->dropColumn('source');
        });
    }
};

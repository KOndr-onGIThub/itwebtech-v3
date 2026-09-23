<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-264: Přílohy z kontaktního formuláře se do teď tiše zahazovaly.
 * Metadata uložených souborů (jméno, velikost, MIME, cesta na disku,
 * jestli se vešly do notifikačního e-mailu) patří k leadu — DB je
 * zdroj pravdy stejně jako u zbytku formuláře.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table): void {
            $table->json('attachments')->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table): void {
            $table->dropColumn('attachments');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-264: Poptávky z homepage, FAQ a landingu se ukládaly do `landing_leads`
 * a tím to končilo — notifikace neexistovala. Teď se posílá e-mail a stav
 * odeslání se sleduje stejně jako u `contact_submissions`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_leads', function (Blueprint $table): void {
            $table->string('mail_status', 16)->default('pending')->after('source');
            $table->text('mail_error')->nullable()->after('mail_status');
            $table->index('mail_status');
        });
    }

    public function down(): void
    {
        Schema::table('landing_leads', function (Blueprint $table): void {
            $table->dropIndex(['mail_status']);
            $table->dropColumn(['mail_status', 'mail_error']);
        });
    }
};

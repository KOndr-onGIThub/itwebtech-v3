<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-173: Trvalé ukládání leadů z kontaktního formuláře (/kontakt → POST /contact).
 *
 * DB je zdroj pravdy pro leady — e-mail je jen notifikace. Sloupce
 * `mail_status` + `mail_error` drží stav doručení notifikace, aby QA agent
 * mohl ověřit doručení bez čtení schránky (viz `php artisan leads:recent`).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('tel', 50)->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 8)->nullable();
            $table->string('mail_status', 16)->default('pending'); // pending | sent | failed
            $table->text('mail_error')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index('mail_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};

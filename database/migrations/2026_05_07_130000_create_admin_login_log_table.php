<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Audit log úspěšných přihlášení do admin panelu.
 *
 * Záměrně neukládáme nic citlivějšího než IP a user-agent — slouží pro
 * jednoduchý forenzní přehled "kdo se kdy přihlásil odkud".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_login_log', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_login_log');
    }
};

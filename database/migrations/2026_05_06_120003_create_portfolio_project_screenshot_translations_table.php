<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_screenshot_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('screenshot_id');
            $table->string('locale', 7);

            $table->string('alt')->nullable();
            $table->string('caption', 500)->nullable();

            $table->timestamps();

            // Krátké názvy kvůli MySQL limitu 64 znaků na identifikátor.
            $table->foreign('screenshot_id', 'pf_screen_tr_screen_id_fk')
                ->references('id')->on('portfolio_project_screenshots')
                ->cascadeOnDelete();

            $table->unique(['screenshot_id', 'locale'], 'pf_screen_tr_screen_locale_uniq');
            $table->index('locale', 'pf_screen_tr_locale_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_screenshot_translations');
    }
};

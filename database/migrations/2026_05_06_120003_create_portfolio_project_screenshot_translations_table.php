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

            $table->foreign('screenshot_id')
                ->references('id')->on('portfolio_project_screenshots')
                ->cascadeOnDelete();

            $table->unique(['screenshot_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_screenshot_translations');
    }
};

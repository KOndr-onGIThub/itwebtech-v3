<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->string('locale', 7);

            // Hlavní obsah
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();

            // Case study sekce
            $table->longText('challenge')->nullable();
            $table->longText('solution')->nullable();
            $table->longText('result')->nullable();

            // SEO meta
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('portfolio_projects')
                ->cascadeOnDelete();

            $table->unique(['project_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_translations');
    }
};

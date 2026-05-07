<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_outcomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');

            // Volitelná identifikace metriky pro interní použití (např. "conversion-rate")
            $table->string('key')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('portfolio_projects')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_outcomes');
    }
};

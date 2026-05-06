<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_outcome_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('outcome_id');
            $table->string('locale', 7);

            $table->string('label');           // např. "Konverze"
            $table->string('value');           // např. "+25 %"
            $table->string('description')->nullable();

            $table->timestamps();

            $table->foreign('outcome_id')
                ->references('id')->on('portfolio_project_outcomes')
                ->cascadeOnDelete();

            $table->unique(['outcome_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_outcome_translations');
    }
};

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

            // Krátké názvy kvůli MySQL limitu 64 znaků na identifikátor.
            $table->foreign('outcome_id', 'pf_outcome_tr_outcome_id_fk')
                ->references('id')->on('portfolio_project_outcomes')
                ->cascadeOnDelete();

            $table->unique(['outcome_id', 'locale'], 'pf_outcome_tr_outcome_locale_uniq');
            $table->index('locale', 'pf_outcome_tr_locale_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_outcome_translations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_tag_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tag_id');
            $table->string('locale', 7);

            $table->string('name');

            $table->timestamps();

            $table->foreign('tag_id')
                ->references('id')->on('portfolio_tags')
                ->cascadeOnDelete();

            $table->unique(['tag_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_tag_translations');
    }
};

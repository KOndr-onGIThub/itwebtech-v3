<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->string('locale', 7)->index();
            $table->boolean('active')->default(false);

            // Přeložitelný obsah
            $table->string('title')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('img_preview')->nullable();
            $table->string('img_main')->nullable();
            $table->text('perex')->nullable();
            $table->longText('content_1')->nullable();
            $table->longText('content_mid')->nullable();
            $table->string('img_mid')->nullable();
            $table->longText('content_2')->nullable();
            $table->string('img_end')->nullable();
            $table->longText('bonus')->nullable();
            $table->longText('extra')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->unique(['article_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_translations');
    }
};

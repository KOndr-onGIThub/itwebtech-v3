<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->json('payload');
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_revisions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->string('locale', 7)->index();
            $table->boolean('active')->default(false);

            // Přeložitelný obsah projektu
            $table->string('title')->nullable();         // dříve 'name'
            $table->string('og_img')->nullable();        // OG obrázek
            $table->string('description', 500)->nullable();
            $table->longText('content')->nullable();
            $table->text('testimonial')->nullable();     // nadpis/popis reference
            $table->text('client_says')->nullable();     // text reference klienta
            $table->string('cta')->nullable();           // výzva k akci

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->unique(['project_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_translations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Kategorie projektu — website / application / other
            $table->string('category', 32);

            // URL identifikátor (jazyk-neutrální, slug používá detail route)
            $table->string('slug')->unique();

            // Nepřekládané vlastnosti
            $table->string('client_name')->nullable();
            $table->string('live_url')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('duration')->nullable(); // např. "3 měsíce"

            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            // Stav publikace — null = draft, datum = publikováno od
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('featured');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_projects');
    }
};

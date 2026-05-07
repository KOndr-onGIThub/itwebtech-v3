<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sitemap_overrides', function (Blueprint $table): void {
            $table->id();
            $table->string('url')->unique();
            $table->decimal('priority', 2, 1)->nullable();
            $table->enum('changefreq', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])->nullable();
            $table->timestamp('lastmod')->nullable();
            $table->boolean('is_excluded')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sitemap_overrides');
    }
};

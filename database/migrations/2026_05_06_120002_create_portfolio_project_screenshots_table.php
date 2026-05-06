<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_screenshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');

            $table->string('path');
            // hero / gallery / thumbnail
            $table->string('type', 32)->default('gallery');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('portfolio_projects')
                ->cascadeOnDelete();

            $table->index(['project_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_screenshots');
    }
};

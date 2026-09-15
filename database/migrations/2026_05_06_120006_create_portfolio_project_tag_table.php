<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_project_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('tag_id');

            $table->primary(['project_id', 'tag_id']);

            $table->foreign('project_id')
                ->references('id')->on('portfolio_projects')
                ->cascadeOnDelete();

            $table->foreign('tag_id')
                ->references('id')->on('portfolio_tags')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_tag');
    }
};

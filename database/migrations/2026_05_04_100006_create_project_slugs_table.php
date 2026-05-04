<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_slugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->string('slug');          // dříve 'url' v tabulce projects
            $table->string('locale', 7)->index();
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_slugs');
    }
};

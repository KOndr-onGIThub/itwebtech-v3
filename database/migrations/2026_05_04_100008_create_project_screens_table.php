<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Přejmenováno z projects_screens na project_screens (Laravel konvence)
        Schema::create('project_screens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id'); // dříve id_project
            $table->boolean('is_video')->default(false);
            $table->string('video_url')->nullable();
            $table->string('screen_shot')->nullable();
            $table->integer('position')->unsigned()->default(0); // dříve sorting
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_screens');
    }
};

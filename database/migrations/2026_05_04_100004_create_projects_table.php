<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('published')->default(false);
            $table->integer('position')->unsigned()->nullable(); // dříve 'sorting'

            // Nepřeložitelné vlastnosti projektu
            $table->string('kind')->nullable();          // typ projektu (pitem-web-app apod.)
            $table->string('price_czk')->nullable();     // cena v CZK
            $table->string('price_eur')->nullable();     // cena v EUR
            $table->boolean('comparison')->default(false); // porovnání před/po
            $table->string('img_before')->nullable();    // fotka před
            $table->string('img_after')->nullable();     // fotka po
            $table->string('customer')->nullable();      // název zákazníka

            // Testimonial (nepřekládá se)
            $table->string('client_photo')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_role')->nullable();
            $table->string('testimonial_source_img')->nullable();
            $table->string('testimonial_src')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

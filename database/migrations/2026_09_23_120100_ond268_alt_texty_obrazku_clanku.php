<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * OND-268 (B4) — alt texty pro obrázky uvnitř článků.
 *
 * `article.blade.php` renderoval `img_mid` i `img_end` natvrdo s `alt=""`,
 * tedy jako dekoraci. Po výměně stock ilustrací za skutečné snímky realizací
 * (rozhodnutí boardu 22. 9. 21:50) ty obrázky nesou obsah a prázdný alt je
 * pro čtečku ztráta informace. `img_preview` a `img_main` mají alt z titulku
 * článku, ty dva prostřední neměly nic.
 *
 * Text je přeložitelný, proto sloupce patří do `article_translations`
 * (řádek na lokalizaci), ne do `articles`. Pozor: `BlogContentDeSeeder`
 * kopíruje z `cs` do `de` jen NÁZVY SOUBORŮ (`IMAGE_COLUMNS`) — alt se
 * záměrně nekopíruje, český text by v německé mutaci byl chyba.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_translations', function (Blueprint $table) {
            $table->string('img_mid_alt', 191)->nullable()->after('img_mid');
            $table->string('img_end_alt', 191)->nullable()->after('img_end');
        });
    }

    public function down(): void
    {
        Schema::table('article_translations', function (Blueprint $table) {
            $table->dropColumn(['img_mid_alt', 'img_end_alt']);
        });
    }
};

<?php

/*
|--------------------------------------------------------------------------
| OND-352 — testy nesmí sáhnout na skutečnou databázi
|--------------------------------------------------------------------------
|
| 25. 9. 2026 mezi 11:29:03 a 11:32:16 UTC proběhl na produkční DB třikrát
| `DROP TABLE` všech tabulek (doloženo v MySQL binlogu) — to je podpis
| `migrate:fresh`, tedy tří běhů suite s `RefreshDatabase`. Přišly o sebe
| všechny projekty portfolia a všechny uložené poptávky.
|
| Jak to mohlo projít: `phpunit.xml` má `DB_CONNECTION=sqlite`, ale PHPUnit
| hodnotu z `<env>` zapíše jen do `getenv()` a `$_ENV`. **`$_SERVER` nechá být
| — i s `force="true"`.** Laravelí `env()` přitom čte přes
| `Env::getRepository()`, kde je `ServerConstAdapter` (tj. `$_SERVER`) PRVNÍ
| v řadě. V produkčním kontejneru jsou `DB_CONNECTION=mysql` a `DB_HOST=…`
| skutečné proměnné prostředí, takže `$_SERVER` vyhrál a suite si vzala
| produkční databázi. Změřeno 26. 9. 2026:
|
|     getenv('DB_CONNECTION')    = 'sqlite'   ← phpunit.xml
|     $_ENV['DB_CONNECTION']     = 'sqlite'   ← phpunit.xml
|     $_SERVER['DB_CONNECTION']  = 'mysql'    ← prostředí, tenhle rozhoduje
|
| Proto se hodnoty připichují tady, do `$_SERVER`. Funguje to bez ohledu na to,
| jestli PHPUnit svou `<php>` sekci aplikuje před bootstrapem nebo po něm —
| `$_SERVER` nepřepíše ani tak, ani tak. Druhá, fail-closed pojistka je
| v `Tests\TestCase::createApplication()`.
*/

$pinned = [
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => ':memory:',
    'DB_URL' => '',
    'DB_HOST' => '',
    'DB_PORT' => '',
    'DB_USERNAME' => '',
    'DB_PASSWORD' => '',
    'MAIL_MAILER' => 'array',

    // OND-352: feature flagy patří sem, ne do netrackovaného `.env`. Dokud tu
    // nebyly, výsledek suite závisel na tom, jaký `.env` měl worktree po ruce
    // (OND-216 / PR #119). Hodnota se drží shodná s produkcí.
    'SHOW_PORTFOLIO_SECTION' => 'true',
];

foreach ($pinned as $key => $value) {
    $_SERVER[$key] = $value;
    $_ENV[$key] = $value;
    putenv("{$key}={$value}");
}

require __DIR__.'/../vendor/autoload.php';

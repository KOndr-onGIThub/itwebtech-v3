<?php

/*
 * OND-461: kroky nasazení, které potřebují databázi. Na Webglobe je databáze
 * dosažitelná jen z PHP webu, ne ze SSH. scripts/deploy/remote-deploy.sh
 * proto nahraje do web rootu jednorázový soubor s tokenem, ten nastaví
 * $release a načte tenhle skript. Běží tedy v PHP-FPM webu, ale nad NOVÝM
 * release, ještě před přepnutím webu.
 *
 * 1. kontrola rozšíření PHP webu
 * 2. migrate --force a tři seedery (stejné jako na Coolify)
 * 3. zkouška stránek přímo přes HTTP kernel nového release (bez DNS)
 *
 * Výstup je čitelný text pro log GitHub Actions. Poslední řádek
 * `DEPLOY_HOOK_OK` znamená úspěch. Když chybí, remote-deploy.sh web nepřepne.
 */

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Symfony\Component\Console\Output\StreamOutput;

/** @var string $release nastavuje jednorázový soubor z remote-deploy.sh */
$release = rtrim($release ?? dirname(__DIR__, 2), '/');

set_time_limit(0);
// Když curl na druhé straně spadne, migrace se nesmí přerušit v půlce.
ignore_user_abort(true);
while (ob_get_level() > 0) {
    ob_end_flush();
}
ob_implicit_flush();

$say = static function (string $line): void {
    echo $line, "\n";
    flush();
};

try {
    $say(sprintf('PHP %s (%s), release %s', PHP_VERSION, PHP_SAPI, $release));

    $need = ['pdo_mysql', 'mbstring', 'intl', 'gd', 'zip', 'bcmath', 'exif', 'fileinfo',
        'openssl', 'tokenizer', 'xml', 'dom', 'ctype', 'curl', 'iconv'];
    $missing = array_filter($need, fn ($e) => ! extension_loaded($e));
    if ($missing !== []) {
        throw new RuntimeException('PHP webu nemá rozšíření: '.implode(' ', $missing).'. Zapni je v administraci Webglobe (PHP nastavení).');
    }

    require $release.'/vendor/autoload.php';

    // Artisan příkazy ať se chovají jako z příkazové řádky, i když běží ve webu.
    $_SERVER['APP_RUNNING_IN_CONSOLE'] = $_ENV['APP_RUNNING_IN_CONSOLE'] = 'true';
    $app = require $release.'/bootstrap/app.php';
    $console = $app->make(ConsoleKernel::class);
    $console->bootstrap();

    // config:cache běžel přes SSH a uložil absolutní cesty, jak je vidí SSH.
    foreach ([(string) config('view.compiled'), storage_path('logs'), storage_path('framework/cache/data')] as $dir) {
        if (! is_dir($dir) || ! is_writable($dir)) {
            throw new RuntimeException("Web nemůže zapisovat do {$dir} (cesta z config:cache přes SSH). Web a SSH vidí soubory jinak nebo pod jiným uživatelem.");
        }
    }

    $output = new StreamOutput(fopen('php://output', 'w'));
    $artisan = static function (string $command, array $parameters) use ($console, $output, $say): void {
        $say("\n==> php artisan {$command} ".implode(' ', array_map(
            fn ($k, $v) => $v === true ? $k : "{$k}={$v}",
            array_keys($parameters),
            $parameters,
        )));
        $code = $console->call($command, $parameters, $output);
        flush();
        if ($code !== 0) {
            throw new RuntimeException("php artisan {$command} skončil kódem {$code}.");
        }
    };

    $artisan('migrate', ['--force' => true]);
    foreach (['AdminUserSeeder', 'EnsureArticlesSeededSeeder', 'EnsurePortfolioSeededSeeder'] as $seeder) {
        $artisan('db:seed', ['--class' => "Database\\Seeders\\{$seeder}", '--force' => true]);
    }

    // Stránky zkouší čerstvá instance aplikace jako běžný webový požadavek
    // (stejně jako testy, každý test má vlastní instanci).
    unset($_SERVER['APP_RUNNING_IN_CONSOLE'], $_ENV['APP_RUNNING_IN_CONSOLE']);
    $app = require $release.'/bootstrap/app.php';
    $kernel = $app->make(HttpKernel::class);

    $failed = 0;
    $check = static function (string $url) use ($kernel, &$failed, $say): void {
        $request = Request::create($url, 'GET');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
        $status = $response->getStatusCode();
        $detail = '';
        if ($status !== 200) {
            $failed++;
            $exception = $response->exception ?? null;
            $detail = $exception instanceof Throwable
                ? ' ('.get_class($exception).': '.$exception->getMessage().')'
                : '';
        }
        $say(sprintf('%s %d %s%s', $status === 200 ? 'OK   ' : 'CHYBA', $status, $url, $detail));
    };

    $say("\n==> Zkouška stránek nového release");
    // `/up` jde na localhost: ten není v LEGACY_HOSTS, nic nepřesměruje.
    $check('http://localhost/up');
    // Zbytek na hostu z APP_URL (po prvním požadavku je konfigurace načtená).
    $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';
    foreach (['/', '/en/', '/de/', '/projekty', '/zapisky', '/robots.txt', '/sitemap.xml'] as $path) {
        $check("https://{$host}{$path}");
    }
    if ($failed > 0) {
        throw new RuntimeException("{$failed} stránek nevrátilo 200. Detail v shared/storage/logs/.");
    }

    $say("\nDEPLOY_HOOK_OK");
} catch (Throwable $e) {
    $say(sprintf("\nCHYBA: %s: %s (%s:%d)", get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
}

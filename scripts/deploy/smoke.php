<?php

/*
 * OND-459: zkouška nového release PŘED přepnutím webu (volá ji
 * scripts/deploy/remote-deploy.sh). Pošle pár požadavků přímo do HTTP
 * kernelu Laravelu s produkční .env a databází, bez webserveru a bez DNS.
 * Funguje tak i při prvním nasazení, kdy doména ještě míří jinam.
 *
 * Použití: php8.4 scripts/deploy/smoke.php /cesta/k/release
 * Exit 0 = všechny stránky vrátily 200.
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$release = rtrim($argv[1] ?? getcwd(), '/');

require $release.'/vendor/autoload.php';
$app = require $release.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);

$request = static function (string $url) use ($kernel): int {
    $request = Request::create($url, 'GET');
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);

    return $response->getStatusCode();
};

// `/up` jde na localhost: ten není v LEGACY_HOSTS, nic nepřesměruje.
$failed = 0;
$check = static function (string $url) use ($request, &$failed): void {
    $status = $request($url);
    printf("%s %s\n", $status === 200 ? 'OK  ' : 'CHYBA', "{$status} {$url}");
    if ($status !== 200) {
        $failed++;
    }
};

$check('http://localhost/up');

// Zbytek na hostu z APP_URL (po prvním požadavku je konfigurace načtená).
$host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';
foreach (['/', '/en/', '/de/', '/projekty', '/zapisky', '/robots.txt', '/sitemap.xml'] as $path) {
    $check("https://{$host}{$path}");
}

exit($failed === 0 ? 0 : 1);

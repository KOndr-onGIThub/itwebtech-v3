<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Idempotentní guard pro portfolio projekty — protějšek
 * {@see EnsureArticlesSeededSeeder} pro blog.
 *
 * Pokud je tabulka `portfolio_projects` prázdná, naplní ji z
 * `docs/portfolio-data.yaml` (24 projektů + překlady cs/en/de + screenshoty +
 * outcomes + štítky). Pokud projekty existují, neudělá nic.
 *
 * Volá se z `docker/entrypoint.d/50-laravel-deploy.sh` při startu kontejneru.
 *
 * Proč vznikl (OND-352): 25. 9. 2026 mezi 11:29 a 11:32 UTC proběhl na
 * produkční DB třikrát `DROP TABLE` všech tabulek (doloženo v binlogu). Deploy
 * si pak přes `migrate --force` postavil schéma, `AdminUserSeeder` vrátil
 * admina a `EnsureArticlesSeededSeeder` články — ale portfolio se neobnovilo,
 * protože pro něj žádný takový guard neexistoval. Homepage tím přišla o sekci
 * „Weby, které běží v praxi", `/projekty` o mřížku a `/projekty/{slug}` začalo
 * vracet 404. Nikdo to nenahlásil, protože stránka se bez sekce vykreslí
 * normálně — jen bez důkazů odvedené práce.
 *
 * Bezpečné pro opakované spuštění a bezpečné pro ručně editovaná data:
 *   - count check zajistí no-op, jakmile projekty existují,
 *   - `PortfolioSeeder` má nad tím vlastní stejnou ochranu, takže ani při
 *     omylu nepřepíše to, co Ondřej upravil ve Filamentu.
 */
class EnsurePortfolioSeededSeeder extends Seeder
{
    public function run(): void
    {
        $before = DB::table('portfolio_projects')->count();
        $this->command?->info("[ensure-portfolio] portfolio_projects count: {$before}");

        if ($before > 0) {
            $this->command?->info('[ensure-portfolio] OK, projekty existují, seed se přeskakuje.');

            return;
        }

        $this->command?->info('[ensure-portfolio] tabulka portfolio_projects je prázdná → spouštím PortfolioSeeder.');
        $this->call(PortfolioSeeder::class);

        $after = DB::table('portfolio_projects')->count();
        $this->command?->info("[ensure-portfolio] portfolio_projects count po seedu: {$after}");
    }
}

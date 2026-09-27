<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * OND-352 — fail-closed pojistka. `createApplication()` běží v Laravelí
     * `setUp()` ještě PŘED `setUpTraits()`, tedy před tím, než `RefreshDatabase`
     * pustí `migrate:fresh`. Pokud se sem přes jakoukoli cestu (proměnná
     * prostředí, `.env`, `config/database.php`) dostane jiné než in-memory
     * sqlite, suite tady spadne — a NE nad cizí databází.
     *
     * Bez téhle pojistky 25. 9. 2026 tři běhy suite z produkčního kontejneru
     * vymazaly produkční DB. Podrobně v `tests/bootstrap.php`.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $default = $app['config']->get('database.default');
        $database = $app['config']->get("database.connections.{$default}.database");

        if ($default !== 'sqlite' || ! in_array($database, [':memory:', null], true)) {
            throw new RuntimeException(
                "Testy odmítly běžet: database.default = '{$default}', database = '"
                .var_export($database, true)."'. Čekáno sqlite / ':memory:'. "
                .'Suite s RefreshDatabase by tuhle databázi vymazala — '
                .'nespouštěj testy v prostředí s DB_CONNECTION/DB_HOST reálné databáze. '
                .'Viz tests/bootstrap.php (OND-352).'
            );
        }

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}

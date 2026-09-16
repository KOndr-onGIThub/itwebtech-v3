<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * OND-216: `RefreshDatabase` bylo zakomentované, takže test běžel nad
     * sdílenou `:memory:` DB bez migrací. Homepage čte `portfolio_projects`,
     * takže prošel jen tehdy, když nějaký dřívější test v témže procesu
     * schéma zmigroval — tedy podle pořadí souborů, ne podle kódu. V čerstvě
     * založeném worktree padal na `no such table: portfolio_projects`.
     */
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

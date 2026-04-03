<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_landing_preview_page_returns_successful_response(): void
    {
        $response = $this->get('/'.config('landing.preview_path'));

        $response
            ->assertOk()
            ->assertSee('Webové stránky na míru')
            ->assertSee('lead-form');
    }

    public function test_landing_lead_form_requires_core_fields(): void
    {
        $response = $this->from('/'.config('landing.preview_path'))
            ->post('/'.config('landing.preview_path').'/lead', []);

        $response
            ->assertRedirect('/'.config('landing.preview_path'))
            ->assertSessionHasErrors(['name', 'email', 'message', 'gdpr']);
    }

    public function test_landing_lead_form_redirects_back_with_success_flag(): void
    {
        $response = $this->from('/'.config('landing.preview_path'))
            ->post('/'.config('landing.preview_path').'/lead', [
                'name' => 'Jan Novak',
                'email' => 'jan@example.com',
                'message' => 'Potrebujeme novou landing page.',
                'gdpr' => '1',
            ]);

        $response
            ->assertRedirect('/'.config('landing.preview_path'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('landing_lead_success', true);
    }
}

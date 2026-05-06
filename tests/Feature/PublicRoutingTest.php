<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_link_inquire_now_to_apply_page(): void
    {
        $applyHref = 'href="'.route('apply').'"';

        $this->get('/')
            ->assertOk()
            ->assertSee($applyHref, false);

        $this->get('/about')
            ->assertOk()
            ->assertSee($applyHref, false);

        $this->get('/news-events')
            ->assertOk()
            ->assertSee($applyHref, false);
    }

    public function test_inquiry_routes_redirect_to_apply_page(): void
    {
        $this->get('/inquire')->assertRedirect('/apply');
        $this->get('/inquiry')->assertRedirect('/apply');
        $this->get('/application')->assertRedirect('/apply');
        $this->get('/admissions/apply')->assertRedirect('/apply');
    }
}

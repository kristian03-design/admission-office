<?php

namespace Tests\Feature;

use App\Models\Program;
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

    public function test_program_details_show_career_opportunities_when_database_list_is_empty(): void
    {
        $program = Program::create([
            'code' => 'BSIT-T',
            'name' => 'Bachelor of Science in Information Technology',
            'department' => 'Technology',
            'category' => 'technology',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 50,
            'is_active' => true,
        ]);

        $this->get("/programs/{$program->id}")
            ->assertOk()
            ->assertSee('Career Opportunities')
            ->assertSee('Software Developer');
    }
}

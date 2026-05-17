<?php

namespace Tests\Feature;

use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicRoutingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Mail::fake();
    }

    public function test_public_pages_link_inquire_now_to_apply_page(): void
    {
        $applyHref = 'href="'.route('apply');

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

    public function test_public_contact_form_accepts_normal_submission(): void
    {
        $this->postJson('/api/contact', [
            'first_name' => 'Test',
            'last_name' => 'Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Admission question',
            'message' => 'Please send admissions details.',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            '_hp' => '',
        ])->assertCreated()
            ->assertJsonPath('message', 'Thank you for your message. We will get back to you soon.');

        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'visitor@example.com',
            'subject' => 'Admission question',
        ]);
    }

    public function test_public_contact_form_blocks_honeypot_spam(): void
    {
        $this->postJson('/api/contact', [
            'first_name' => 'Bot',
            'last_name' => 'Visitor',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => 'This should be blocked.',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            '_hp' => 'filled by bot',
        ])->assertStatus(429);

        $this->assertDatabaseMissing('contact_inquiries', [
            'email' => 'bot@example.com',
        ]);
    }

    public function test_public_contact_form_blocks_duplicate_payloads(): void
    {
        $payload = [
            'first_name' => 'Repeat',
            'last_name' => 'Visitor',
            'email' => 'repeat@example.com',
            'subject' => 'Same question',
            'message' => 'Please answer this once.',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            '_hp' => '',
        ];

        $this->postJson('/api/contact', $payload)->assertCreated();
        $this->postJson('/api/contact', $payload)
            ->assertStatus(429)
            ->assertJsonPath('message', 'This request was already received. Please wait before trying again.');
    }

    public function test_public_api_gets_send_cache_headers(): void
    {
        $this->getJson('/api/programs')
            ->assertOk()
            ->assertHeader('Cache-Control');
    }
}

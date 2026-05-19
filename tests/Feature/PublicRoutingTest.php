<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Application;
use App\Models\Interview;
use App\Mail\ApplicantPortalOtpMail;
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
        $portalHref = 'href="'.route('application-status').'"';

        $this->get('/')
            ->assertOk()
            ->assertSee($applyHref, false)
            ->assertSee($portalHref, false);

        $this->get('/about')
            ->assertOk()
            ->assertSee($applyHref, false)
            ->assertSee($portalHref, false);

        $this->get('/news-events')
            ->assertOk()
            ->assertSee($applyHref, false)
            ->assertSee($portalHref, false);
    }

    public function test_applicant_portal_otp_opens_status_payload(): void
    {
        $application = Application::create([
            'reference_number' => 'BTECH-2026-000123',
            'email' => 'student@example.com',
            'first_name' => 'Ada',
            'last_name' => 'Student',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $this->postJson('/api/application-status/request-otp', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
        ])->assertOk();

        $otp = null;
        Mail::assertSent(ApplicantPortalOtpMail::class, function (ApplicantPortalOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;
            return true;
        });

        $this->postJson('/api/application-status/verify', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
            'otp' => $otp,
        ])->assertOk()
            ->assertJsonPath('data.application.reference_number', $application->reference_number)
            ->assertJsonMissingPath('data.application.document_upload_token');
    }

    public function test_accepted_applicant_can_update_allowed_details(): void
    {
        $application = Application::create([
            'reference_number' => 'BTECH-2026-000124',
            'email' => 'accepted@example.com',
            'first_name' => 'Accepted',
            'last_name' => 'Student',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        $this->postJson('/api/application-status/request-otp', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
        ])->assertOk();

        $otp = null;
        Mail::assertSent(ApplicantPortalOtpMail::class, function (ApplicantPortalOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;
            return true;
        });

        $token = $this->postJson('/api/application-status/verify', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
            'otp' => $otp,
        ])->assertOk()
            ->assertJsonPath('data.editable', true)
            ->json('portal_token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->patchJson('/api/application-status/data', [
                'contact_number' => '09171234567',
            ])->assertOk()
            ->assertJsonPath('data.application.contact_number', '09171234567');
    }

    public function test_portal_displays_scheduled_when_interview_has_date(): void
    {
        $program = Program::create([
            'code' => 'BSIT',
            'name' => 'Bachelor of Science in Information Technology',
            'department' => 'Technology',
            'category' => 'technology',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 10,
            'is_active' => true,
            'has_board_exam' => false,
        ]);

        $application = Application::create([
            'reference_number' => 'BTECH-2026-000125',
            'email' => 'interview@example.com',
            'first_name' => 'Interview',
            'last_name' => 'Student',
            'status' => 'for_interview',
            'submitted_at' => now(),
        ]);

        Interview::create([
            'program_id' => $program->id,
            'application_id' => $application->id,
            'student_name' => 'Interview Student',
            'reference_number' => $application->reference_number,
            'interview_date' => '2026-05-20',
            'interview_time' => '10:00:00',
            'status' => 'Pending',
        ]);

        $this->postJson('/api/application-status/request-otp', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
        ])->assertOk();

        $otp = null;
        Mail::assertSent(ApplicantPortalOtpMail::class, function (ApplicantPortalOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;
            return true;
        });

        $this->postJson('/api/application-status/verify', [
            'reference_number' => $application->reference_number,
            'email' => $application->email,
            'otp' => $otp,
        ])->assertOk()
            ->assertJsonPath('data.interview.status', 'Pending')
            ->assertJsonPath('data.interview.display_status', 'Scheduled');
    }

    public function test_second_choice_cannot_be_board_exam_program(): void
    {
        Program::create([
            'code' => 'BSIT',
            'name' => 'Bachelor of Science in Information Technology',
            'department' => 'Technology',
            'category' => 'technology',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 10,
            'is_active' => true,
            'has_board_exam' => false,
        ]);

        Program::create([
            'code' => 'BSA',
            'name' => 'BS Accountancy',
            'department' => 'Accountancy',
            'category' => 'accountancy',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 10,
            'is_active' => true,
            'has_board_exam' => true,
        ]);

        $this->postJson('/api/applications/submit-public', [
            'first_choice' => 'Bachelor of Science in Information Technology',
            'second_choice' => 'BS Accountancy',
            'first_name' => 'Ada',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            '_hp' => '',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('second_choice');
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

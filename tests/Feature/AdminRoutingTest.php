<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_can_be_rendered(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Admin Portal');
    }

    public function test_legacy_admin_login_page_redirects_to_admin_login(): void
    {
        $this->get('/admin-login.html')
            ->assertRedirect('/admin/login');
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_render_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('window.ADMIN_LOGIN_URL', false);
    }

    public function test_legacy_admin_news_events_endpoint_can_save(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = $user->createToken('admin-dashboard')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/admin/news-events', [
                'title' => 'Enrollment Opens',
                'type' => 'news',
                'summary' => 'Admissions announcement',
                'is_active' => true,
                'sort_order' => 0,
            ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Enrollment Opens');

        $this->assertDatabaseHas('news_events', [
            'title' => 'Enrollment Opens',
            'type' => 'news',
        ]);
    }

    public function test_legacy_admin_content_endpoints_can_save(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = $user->createToken('admin-dashboard')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/announcements', [
                'message' => 'Admissions office announcement',
                'is_active' => true,
                'sort_order' => 0,
            ])
            ->assertCreated()
            ->assertJsonPath('data.message', 'Admissions office announcement');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/testimonials', [
                'author_name' => 'Student One',
                'author_role' => 'Applicant',
                'message' => 'Helpful admissions process.',
                'is_active' => true,
                'order' => 0,
            ])
            ->assertOk()
            ->assertJsonPath('data.author_name', 'Student One');

        $this->assertDatabaseHas('announcements', [
            'message' => 'Admissions office announcement',
        ]);
        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Student One',
        ]);
    }

    public function test_legacy_interviews_endpoint_can_load_saved_schedules(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = $user->createToken('admin-dashboard')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/interviews?program_id=1')
            ->assertOk()
            ->assertJsonPath('data', []);
    }

    public function test_legacy_program_slots_endpoint_persists_saved_value(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = $user->createToken('admin-dashboard')->plainTextToken;
        $program = Program::create([
            'code' => 'TST',
            'name' => 'Test Program',
            'department' => 'Testing',
            'category' => 'testing',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 3000,
            'is_active' => true,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/programs/{$program->id}/slots-left", [
                'slots_left' => 125,
            ])
            ->assertOk()
            ->assertJsonPath('data.slots_left', 125);

        $this->assertDatabaseHas('programs', [
            'id' => $program->id,
            'slots_left' => 125,
            'is_active' => true,
        ]);
    }

    public function test_program_status_endpoint_persists_disabled_state(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $token = $user->createToken('admin-dashboard')->plainTextToken;
        $program = Program::create([
            'code' => 'CLS',
            'name' => 'Closed Program',
            'department' => 'Testing',
            'category' => 'testing',
            'duration_years' => 4,
            'schedule' => 'Day',
            'slots_left' => 25,
            'is_active' => true,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/programs/{$program->id}/status", [
                'is_active' => false,
            ])
            ->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/programs/{$program->id}/slots-left", [
                'slots_left' => 25,
            ])
            ->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('programs', [
            'id' => $program->id,
            'slots_left' => 25,
            'is_active' => false,
        ]);
    }

    public function test_uploaded_public_storage_files_can_be_served_by_laravel(): void
    {
        Storage::disk('public')->put('applications/1/photo.txt', 'photo-data');

        $this->get('/uploaded-storage/applications/1/photo.txt')
            ->assertOk()
            ->assertSee('photo-data');
    }
}

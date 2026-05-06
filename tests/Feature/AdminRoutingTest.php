<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('window.ADMIN_LOGIN_URL', false);
    }

    public function test_legacy_admin_news_events_endpoint_can_save(): void
    {
        $user = User::factory()->create();
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
        $user = User::factory()->create();
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
}

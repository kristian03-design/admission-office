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
}

<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_photographer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_dashboard_displays_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $photographer = User::factory()->create(['role' => 'photographer']);
        PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $reviewClient = User::factory()->create(['role' => 'client']);
        $reviewPhotographer = User::factory()->create(['role' => 'photographer']);
        PhotographerProfile::factory()->create([
            'id_user' => $reviewPhotographer->id_user,
            'validation_status' => 'approved',
        ]);

        Booking::factory()->create();
        Review::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Total Users');
        $response->assertSee('Total Photographers');
        $response->assertSee('Pending Profiles');
        $response->assertSee('Approved Profiles');
        $response->assertSee('Total Bookings');
        $response->assertSee('Total Reviews');
    }
}
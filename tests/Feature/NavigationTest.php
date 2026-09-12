<?php

namespace Tests\Feature;

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_photographers_page_shows_guest_navigation(): void
    {
        $response = $this->get('/photographers');

        $response->assertOk();
        $response->assertSee('Log in');
        $response->assertSee('Get Started');
        $response->assertDontSee('My Bookings');
        $response->assertDontSee('My Services');
    }

    public function test_client_navigation_shows_client_links(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('My Bookings');
        $response->assertSee('Become a Photographer');
        $response->assertDontSee('My Services');
        $response->assertDontSee('Admin');
    }

    public function test_client_with_application_navigation_shows_application_link(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('My Photographer Application');
        $response->assertDontSee('My Services');
    }

    public function test_photographer_navigation_shows_photographer_links(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('My Bookings');
        $response->assertSee('My Services');
        $response->assertSee('My Portfolio');
        $response->assertSee('My Availability');
    }

    public function test_admin_navigation_shows_admin_links_and_hides_my_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Admin');
        $response->assertDontSee('My Bookings');
        $response->assertDontSee('My Services');
        $response->assertDontSee('Become a Photographer');
    }

    public function test_photographers_page_uses_global_navigation_for_authenticated_users(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($photographer)
            ->get('/photographers');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertDontSee('Get Started');
        $response->assertSee('Find Photographers');
    }
}
<?php

namespace Tests\Feature;

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_open_application_form_without_a_profile(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile');

        $response->assertOk();
    }

    public function test_client_with_profile_is_redirected_from_create(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile');

        $response->assertRedirect('/photographer-profile/' . $profile->id_profile);
        $response->assertSessionHas('info');
    }

    public function test_client_with_profile_cannot_store_a_second_application(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->post('/photographer-profile', [
                'bio' => 'Another application',
            ]);

        $response->assertRedirect('/photographer-profile/' . $profile->id_profile);

        $this->assertSame(1, PhotographerProfile::where('id_user', $client->id_user)->count());
    }

    public function test_client_dashboard_shows_become_a_photographer_without_application(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Become a Photographer');
        $response->assertSee(route('photographer-profile.create'));
    }

    public function test_client_dashboard_shows_pending_application_status(): void
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
        $response->assertSee('Application under review');
        $response->assertSee('My Application');
    }

    public function test_client_dashboard_shows_rejected_application_status(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'rejected',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Application rejected');
        $response->assertSee(route('photographer-profile.show', $profile->id_profile));
    }

    public function test_navbar_shows_become_a_photographer_for_client_without_application(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Become a Photographer');
    }

    public function test_navbar_shows_my_photographer_application_for_client_with_application(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('My Photographer Application');
        $response->assertSee(route('photographer-profile.show', $profile->id_profile));
    }

    public function test_owner_sees_pending_banner_on_own_profile(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee('Pending approval');
    }

    public function test_owner_sees_rejected_banner_on_own_profile(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'rejected',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee('Rejected.');
    }

    public function test_pending_statuses_are_not_shown_to_other_users(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $client->id_user,
            'validation_status' => 'pending',
        ]);
        $other = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($other)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertForbidden();
    }
}
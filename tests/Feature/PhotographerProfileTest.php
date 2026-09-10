<?php

namespace Tests\Feature;

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_photographer_can_create_profile(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => 'Professional photographer',
                'city' => 'New York',
                'experience' => 5,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('photographer_profiles', [
            'bio' => 'Professional photographer',
            'city' => 'New York',
            'experience' => 5,
            'id_user' => $user->id_user,
            'validation_status' => 'pending',
        ]);
    }

    public function test_client_cannot_create_profile(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => 'Professional photographer',
                'city' => 'New York',
                'experience' => 5,
            ]);

        $response->assertForbidden();
    }

    public function test_photographer_cannot_create_duplicate_profile(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);
        PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        $response = $this
            ->actingAs($user)
            ->get('/photographer-profile');

        $response->assertRedirect();
    }

    public function test_profile_validation_required_fields(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => '',
                'city' => '',
                'experience' => '',
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_profile_validation_experience_range(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'experience' => -1,
            ]);

        $response->assertSessionHasErrors('experience');

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'experience' => 101,
            ]);

        $response->assertSessionHasErrors('experience');
    }

    public function test_profile_validation_city_max_length(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'city' => str_repeat('a', 101),
            ]);

        $response->assertSessionHasErrors('city');
    }

    public function test_profile_validation_bio_max_length(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => str_repeat('a', 2001),
            ]);

        $response->assertSessionHasErrors('bio');
    }

    public function test_profile_default_validation_status_pending(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => 'Test bio',
            ]);

        $profile = PhotographerProfile::where('id_user', $user->id_user)->first();
        $this->assertEquals('pending', $profile->validation_status);
    }

    public function test_photographer_can_view_own_profile(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        $response = $this
            ->actingAs($user)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
    }

    public function test_authenticated_user_can_view_approved_profile(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
    }

    public function test_pending_profile_not_visible_to_other_users(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertForbidden();
    }

    public function test_rejected_profile_not_visible_to_other_users(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'rejected',
        ]);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertForbidden();
    }

    public function test_owner_can_view_own_pending_profile(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
    }

    public function test_owner_can_view_own_rejected_profile(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'rejected',
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
    }

    public function test_admin_can_view_any_pending_profile(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
    }

    public function test_photographer_can_update_own_profile(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        $response = $this
            ->actingAs($user)
            ->patch('/photographer-profile/' . $profile->id_profile, [
                'bio' => 'Updated bio',
                'city' => 'Los Angeles',
                'experience' => 10,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $profile->refresh();
        $this->assertEquals('Updated bio', $profile->bio);
        $this->assertEquals('Los Angeles', $profile->city);
        $this->assertEquals(10, $profile->experience);
    }

    public function test_photographer_cannot_update_other_profile(): void
    {
        $photographer1 = User::factory()->create(['role' => 'photographer']);
        $photographer2 = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $photographer2->id_user]);

        $response = $this
            ->actingAs($photographer1)
            ->patch('/photographer-profile/' . $profile->id_profile, [
                'bio' => 'Hacked bio',
            ]);

        $response->assertForbidden();
    }

    public function test_create_redirects_to_show_if_profile_exists(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        $response = $this
            ->actingAs($user)
            ->get('/photographer-profile');

        $response->assertRedirect('/photographer-profile/' . $profile->id_profile);
        $response->assertSessionHas('info');
    }

    public function test_store_redirects_to_show_if_profile_exists(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        $response = $this
            ->actingAs($user)
            ->post('/photographer-profile', [
                'bio' => 'New bio',
            ]);

        $response->assertRedirect('/photographer-profile/' . $profile->id_profile);
        $response->assertSessionHas('info');
    }
}
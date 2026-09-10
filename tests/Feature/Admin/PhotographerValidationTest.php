<?php

namespace Tests\Feature\Admin;

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_photographer_profiles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/photographers');

        $response->assertOk();
        $response->assertSee($photographer->first_name);
        $response->assertSee($profile->id_profile);
    }

    public function test_admin_can_view_profile_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/photographers/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee($photographer->email);
        $response->assertSee('Approve');
        $response->assertSee('Reject');
    }

    public function test_admin_can_approve_pending_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/photographers/' . $profile->id_profile . '/approve');

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $profile->refresh();
        $this->assertEquals('approved', $profile->validation_status);
    }

    public function test_admin_can_reject_pending_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/photographers/' . $profile->id_profile . '/reject');

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $profile->refresh();
        $this->assertEquals('rejected', $profile->validation_status);
    }

    public function test_approved_profile_cannot_be_approved_again(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/photographers/' . $profile->id_profile . '/approve');

        $response->assertSessionHasErrors('status');

        $profile->refresh();
        $this->assertEquals('approved', $profile->validation_status);
    }

    public function test_rejected_profile_cannot_be_rejected_again(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'rejected',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/photographers/' . $profile->id_profile . '/reject');

        $response->assertSessionHasErrors('status');

        $profile->refresh();
        $this->assertEquals('rejected', $profile->validation_status);
    }

    public function test_client_cannot_access_photographer_validation(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/admin/photographers');

        $response->assertForbidden();

        $response = $this
            ->actingAs($client)
            ->patch('/admin/photographers/' . $profile->id_profile . '/approve');

        $response->assertForbidden();
    }
}
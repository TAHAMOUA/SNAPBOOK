<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($admin)
            ->get('/admin/users');

        $response->assertOk();
        $response->assertSee($admin->email);
        $response->assertSee($client->email);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/login');
    }

    public function test_client_cannot_access_user_list(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($user)
            ->get('/admin/users');

        $response->assertForbidden();
    }

    public function test_photographer_cannot_access_user_list(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->get('/admin/users');

        $response->assertForbidden();
    }

    public function test_admin_can_change_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/users/' . $client->id_user . '/role', [
                'role' => 'photographer',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $client->refresh();
        $this->assertEquals('photographer', $client->role);
    }

    public function test_invalid_role_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/users/' . $client->id_user . '/role', [
                'role' => 'superuser',
            ]);

        $response->assertSessionHasErrors('role');

        $client->refresh();
        $this->assertEquals('client', $client->role);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->patch('/admin/users/' . $admin->id_user . '/role', [
                'role' => 'photographer',
            ]);

        $response->assertSessionHasErrors('role');

        $admin->refresh();
        $this->assertEquals('admin', $admin->role);
    }
}
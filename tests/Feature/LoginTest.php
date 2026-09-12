<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_preserved_after_failed_login(): void
    {
        $user = User::factory()->create();

        $this->from('/login')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('value="' . $user->email . '"', false);
    }

    public function test_password_is_not_persisted_after_failed_login(): void
    {
        $user = User::factory()->create();

        $this->from('/login')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect('/login');

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('type="password"', false);
        $response->assertDontSee('value="wrong-password"');
    }

    public function test_register_form_preserves_invalid_field_values_after_validation_error(): void
    {
        $password = 'password';

        $this->from('/register')
            ->post('/register', [
                'first_name' => 'Amina',
                'last_name' => 'Khaldi',
                'phone' => '+212 600 000 000',
                'email' => 'amina@example.com',
                'password' => $password,
                'password_confirmation' => 'different-password',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors('password');

        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('value="Amina"', false);
        $response->assertSee('value="Khaldi"', false);
        $response->assertSee('value="+212 600 000 000"', false);
        $response->assertSee('value="amina@example.com"', false);
    }

    public function test_register_form_does_not_persist_password_after_validation_error(): void
    {
        $this->from('/register')
            ->post('/register', [
                'first_name' => 'Amina',
                'last_name' => 'Khaldi',
                'phone' => '',
                'email' => 'amina@example.com',
                'password' => 'password',
                'password_confirmation' => 'different-password',
            ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors('password');

        $response = $this->get('/register');

        $response->assertOk();
        $response->assertDontSee('value="password"');
    }
}
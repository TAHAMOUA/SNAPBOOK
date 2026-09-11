<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    private function profile(
        array $attributes = [],
        array $userAttributes = []
    ): PhotographerProfile {
        $user = User::factory()->create(array_merge([
            'role' => 'photographer',
        ], $userAttributes));

        return PhotographerProfile::factory()->create(array_merge([
            'id_user' => $user->id_user,
            'validation_status' => 'approved',
        ], $attributes));
    }

    public function test_guest_can_access_home_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_home_page_shows_approved_photographers(): void
    {
        $profile = $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Anna Smith');
    }

    public function test_home_page_shows_categories(): void
    {
        $wedding = Category::factory()->create(['category_name' => 'Wedding']);
        $portrait = Category::factory()->create(['category_name' => 'Portrait']);

        $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);

        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_home_page_has_search_form(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Name or City');
    }
}
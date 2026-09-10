<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerSearchTest extends TestCase
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

    public function test_guest_can_access_photographer_listing(): void
    {
        $this->get('/photographers')->assertOk();
    }

    public function test_approved_photographers_are_visible(): void
    {
        $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);

        $response = $this->get('/photographers');

        $response->assertOk();
        $response->assertSee('Anna Smith');
    }

    public function test_pending_photographers_are_not_visible(): void
    {
        $this->profile([
            'validation_status' => 'pending',
        ], [
            'first_name' => 'Bob',
            'last_name' => 'Jones',
        ]);

        $response = $this->get('/photographers');

        $response->assertOk();
        $response->assertDontSee('Bob Jones');
    }

    public function test_rejected_photographers_are_not_visible(): void
    {
        $this->profile([
            'validation_status' => 'rejected',
        ], [
            'first_name' => 'Carla',
            'last_name' => 'Brown',
        ]);

        $response = $this->get('/photographers');

        $response->assertOk();
        $response->assertDontSee('Carla Brown');
    }

    public function test_search_photographer_by_first_name(): void
    {
        $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);
        $this->profile([], [
            'first_name' => 'Bob',
            'last_name' => 'Jones',
        ]);

        $response = $this->get('/photographers?q=Anna');

        $response->assertOk();
        $response->assertSee('Anna Smith');
        $response->assertDontSee('Bob Jones');
    }

    public function test_search_photographer_by_last_name(): void
    {
        $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);
        $this->profile([], [
            'first_name' => 'Bob',
            'last_name' => 'Jones',
        ]);

        $response = $this->get('/photographers?q=Smith');

        $response->assertOk();
        $response->assertSee('Anna Smith');
        $response->assertDontSee('Bob Jones');
    }

    public function test_search_photographer_by_city(): void
    {
        $this->profile(['city' => 'Paris']);
        $this->profile(['city' => 'Berlin']);

        $response = $this->get('/photographers?q=Paris');

        $response->assertOk();
        $response->assertSee('Paris');
        $response->assertDontSee('Berlin');
    }

    public function test_filter_photographers_by_category(): void
    {
        $wedding = Category::factory()->create(['category_name' => 'Wedding']);
        $portrait = Category::factory()->create(['category_name' => 'Portrait']);

        $inCategory = $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);
        Service::factory()->create([
            'id_profile' => $inCategory->id_profile,
            'id_category' => $wedding->id_category,
        ]);

        $outsideCategory = $this->profile([], [
            'first_name' => 'Bob',
            'last_name' => 'Jones',
        ]);
        Service::factory()->create([
            'id_profile' => $outsideCategory->id_profile,
            'id_category' => $portrait->id_category,
        ]);

        $response = $this->get('/photographers?category=' . $wedding->id_category);

        $response->assertOk();
        $response->assertSee('Anna Smith');
        $response->assertDontSee('Bob Jones');
    }

    public function test_combined_search_and_category_filter(): void
    {
        $wedding = Category::factory()->create(['category_name' => 'Wedding']);
        $portrait = Category::factory()->create(['category_name' => 'Portrait']);

        $anna = $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);
        Service::factory()->create([
            'id_profile' => $anna->id_profile,
            'id_category' => $wedding->id_category,
        ]);

        $annaPortrait = $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Johnson',
        ]);
        Service::factory()->create([
            'id_profile' => $annaPortrait->id_profile,
            'id_category' => $portrait->id_category,
        ]);

        $response = $this->get('/photographers?q=Anna&category=' . $wedding->id_category);

        $response->assertOk();
        $response->assertSee('Anna Smith');
        $response->assertDontSee('Anna Johnson');
    }

    public function test_pending_photographers_do_not_appear_with_category_filter(): void
    {
        $wedding = Category::factory()->create(['category_name' => 'Wedding']);

        $pending = $this->profile([
            'validation_status' => 'pending',
        ], [
            'first_name' => 'Bob',
            'last_name' => 'Jones',
        ]);
        Service::factory()->create([
            'id_profile' => $pending->id_profile,
            'id_category' => $wedding->id_category,
        ]);

        $response = $this->get('/photographers?category=' . $wedding->id_category);

        $response->assertOk();
        $response->assertDontSee('Bob Jones');
    }

    public function test_photographer_with_multiple_services_is_not_duplicated(): void
    {
        $wedding = Category::factory()->create(['category_name' => 'Wedding']);

        $profile = $this->profile([], [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
        ]);

        Service::factory()->count(3)->create([
            'id_profile' => $profile->id_profile,
            'id_category' => $wedding->id_category,
        ]);

        $response = $this->get('/photographers?category=' . $wedding->id_category);

        $response->assertOk();
        $response->assertSee('Anna Smith');
        $this->assertSame(1, $response->viewData('profiles')->total());
    }

    public function test_photographer_listing_is_paginated(): void
    {
        for ($i = 1; $i <= 13; $i++) {
            $this->profile();
        }

        $response = $this->get('/photographers');

        $response->assertOk();
        $this->assertSame(13, $response->viewData('profiles')->total());
        $this->assertSame(12, $response->viewData('profiles')->count());

        $pageTwo = $this->get('/photographers?page=2');

        $pageTwo->assertOk();
        $this->assertSame(1, $pageTwo->viewData('profiles')->count());
    }
}
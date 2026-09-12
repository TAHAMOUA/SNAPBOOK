<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerProfileShowTest extends TestCase
{
    use RefreshDatabase;

    private function approvedProfileWithData(): array
    {
        $photographer = User::factory()->create([
            'role' => 'photographer',
            'first_name' => 'Karim',
            'last_name' => 'Benali',
        ]);

        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
            'city' => 'Casablanca',
            'bio' => 'Documentary-style wedding photography.',
            'experience' => 6,
        ]);

        $category = Category::factory()->create(['category_name' => 'Wedding']);

        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
            'id_category' => $category->id_category,
            'title' => 'Full wedding day',
            'price' => 8500,
        ]);

        Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'description' => 'Golden hour session',
        ]);

        $availability = Availability::factory()->create([
            'id_profile' => $profile->id_profile,
        ]);

        $client = User::factory()->create(['role' => 'client']);

        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'completed',
        ]);

        Review::factory()->create([
            'rating' => 5,
            'comment' => 'Stunning gallery and a calm presence.',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        return [$client, $photographer, $profile, $service];
    }

    public function test_client_sees_profile_details(): void
    {
        [$client, , $profile, $service] = $this->approvedProfileWithData();

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee('Karim Benali');
        $response->assertSee('Casablanca');
        $response->assertSee('Documentary-style wedding photography.');
        $response->assertSee('Wedding');
        $response->assertSee($service->title);
        $response->assertSee('8,500.00 MAD');
        $response->assertSee('Stunning gallery and a calm presence.');
    }

    public function test_owner_sees_edit_profile_controls(): void
    {
        [, $photographer, $profile] = $this->approvedProfileWithData();

        $response = $this
            ->actingAs($photographer)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee('Edit Profile');
        $response->assertSee(route('photographer-profile.edit', $profile->id_profile));
    }

    public function test_client_sees_book_session_cta_for_approved_profile_with_services(): void
    {
        [$client, , $profile, $service] = $this->approvedProfileWithData();

        $response = $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile);

        $response->assertOk();
        $response->assertSee('Book a session');
        $response->assertSee(route('bookings.create', ['service' => $service->id_service]));
    }

    public function test_client_does_not_see_book_cta_when_profile_has_no_services(): void
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
        $response->assertDontSee('Book a session');
    }
}
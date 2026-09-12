<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingUiTest extends TestCase
{
    use RefreshDatabase;

    private function bookableService(): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $photographer = User::factory()->create([
            'role' => 'photographer',
            'first_name' => 'Karim',
            'last_name' => 'Benali',
        ]);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);
        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
            'title' => 'Full wedding day',
        ]);
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        return [$client, $photographer, $service, $availability];
    }

    public function test_client_sees_booking_screen_layout(): void
    {
        [$client, , $service] = $this->bookableService();

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertSee('Book a Session');
        $response->assertSee('Confirm and send request');
        $response->assertSee($service->title);
        $response->assertSee('event_address');
    }

    public function test_client_sees_bookings_list_rows(): void
    {
        [$client, , $service, $availability] = $this->bookableService();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.index'));

        $response->assertOk();
        $response->assertSee('Karim Benali');
        $response->assertSee($service->title);
        $response->assertSee('Pending');
    }

    public function test_photographer_sees_client_name_in_bookings_list(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $client->update(['first_name' => 'Amina', 'last_name' => 'Khaldi']);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get(route('bookings.index'));

        $response->assertOk();
        $response->assertSee('Amina Khaldi');
        $response->assertSee($service->title);
        $response->assertSee('Pending');
    }

    public function test_client_sees_cancel_action_on_pending_booking(): void
    {
        [$client, , $service, $availability] = $this->bookableService();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.show', $booking->id_booking));

        $response->assertOk();
        $response->assertSee('Pending');
        $response->assertSee('Cancel Booking');
    }

    public function test_photographer_sees_accept_and_reject_actions(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get(route('bookings.show', $booking->id_booking));

        $response->assertOk();
        $response->assertSee('Accept');
        $response->assertSee('Reject');
    }

    public function test_pending_booked_slot_is_hidden_from_booking_form(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $booked = Availability::factory()->create(['id_profile' => $photographer->photographerProfile->id_profile]);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $booked->id_availability,
            'status' => 'pending',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertDontSee('value="' . $booked->id_availability . '"', false);
        $response->assertSee('value="' . $availability->id_availability . '"', false);
    }

    public function test_accepted_booked_slot_is_hidden_from_booking_form(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $booked = Availability::factory()->create(['id_profile' => $photographer->photographerProfile->id_profile]);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $booked->id_availability,
            'status' => 'accepted',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertDontSee('value="' . $booked->id_availability . '"', false);
        $response->assertSee('value="' . $availability->id_availability . '"', false);
    }

    public function test_completed_booked_slot_is_hidden_from_booking_form(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $booked = Availability::factory()->create(['id_profile' => $photographer->photographerProfile->id_profile]);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $booked->id_availability,
            'status' => 'completed',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertDontSee('value="' . $booked->id_availability . '"', false);
        $response->assertSee('value="' . $availability->id_availability . '"', false);
    }

    public function test_rejected_slot_is_still_shown_in_booking_form(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $rejected = Availability::factory()->create(['id_profile' => $photographer->photographerProfile->id_profile]);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $rejected->id_availability,
            'status' => 'rejected',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertSee('value="' . $rejected->id_availability . '"', false);
    }

    public function test_cancelled_slot_is_still_shown_in_booking_form(): void
    {
        [$client, $photographer, $service, $availability] = $this->bookableService();
        $cancelled = Availability::factory()->create(['id_profile' => $photographer->photographerProfile->id_profile]);
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $cancelled->id_availability,
            'status' => 'cancelled',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('bookings.create', ['service' => $service->id_service]));

        $response->assertOk();
        $response->assertSee('value="' . $cancelled->id_availability . '"', false);
    }
}

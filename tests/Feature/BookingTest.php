<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function bookableSetup(): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);
        $service = Service::factory()->create(['id_profile' => $profile->id_profile]);
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        return [$client, $photographer, $profile, $service, $availability];
    }

    private function payload(Service $service, Availability $availability, array $overrides = []): array
    {
        return array_merge([
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_address' => '123 Main Street',
        ], $overrides);
    }

    public function test_guest_cannot_access_bookings_index(): void
    {
        $this->get('/bookings')->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_bookings_create(): void
    {
        $this->get('/bookings/create')->assertRedirect(route('login'));
    }

    public function test_guest_cannot_post_a_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $this
            ->post('/bookings', $this->payload($service, $availability))
            ->assertRedirect(route('login'));
    }

    public function test_client_can_create_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $service->update(['price' => 1500.00]);

        $response = $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'event_date' => $availability->available_date->format('Y-m-d'),
            'event_address' => '123 Main Street',
            'total_price' => 1500.00,
            'status' => 'pending',
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ]);
    }

    public function test_photographer_cannot_create_booking(): void
    {
        [, $photographer, , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($photographer)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertForbidden();
    }

    public function test_admin_cannot_create_booking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($admin)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertForbidden();
    }

    public function test_client_can_view_own_booking(): void
    {
        [$client, , $profile, $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this
            ->actingAs($client)
            ->get('/bookings/' . $booking->id_booking)
            ->assertOk();
    }

    public function test_client_cannot_view_another_clients_booking(): void
    {
        [$client1] = $this->bookableSetup();
        [$client2, , , $service2, $availability2] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client2->id_user,
            'id_service' => $service2->id_service,
            'id_availability' => $availability2->id_availability,
            'event_date' => $availability2->available_date->format('Y-m-d'),
            'total_price' => $service2->price,
        ]);

        $this
            ->actingAs($client1)
            ->get('/bookings/' . $booking->id_booking)
            ->assertForbidden();
    }

    public function test_photographer_can_view_bookings_for_own_service(): void
    {
        [$client, $photographer, $profile, $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/bookings');

        $response->assertOk();
        $this->assertEquals(1, $profile->services()->first()->bookings()->count());
    }

    public function test_photographer_cannot_view_another_photographers_booking(): void
    {
        [, , $profile1, $service1, $availability1] = $this->bookableSetup();
        [$client2, $photographer2] = $this->bookableSetup();
        $booking2 = Booking::factory()->create([
            'id_user' => $client2->id_user,
            'id_service' => $service1->id_service,
            'id_availability' => $availability1->id_availability,
            'event_date' => $availability1->available_date->format('Y-m-d'),
            'total_price' => $service1->price,
        ]);

        $photographer1 = $profile1->user;

        $this
            ->actingAs($photographer2)
            ->get('/bookings/' . $booking2->id_booking)
            ->assertForbidden();
    }

    public function test_photographer_can_accept_pending_booking(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/accept')
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'accepted',
        ]);
    }

    public function test_photographer_can_reject_pending_booking(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/reject')
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'rejected',
        ]);
    }

    public function test_photographer_can_complete_accepted_booking(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'accepted',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/complete')
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'completed',
        ]);
    }

    public function test_another_photographer_cannot_accept_reject_or_complete(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $otherPhotographer = User::factory()->create(['role' => 'photographer']);
        PhotographerProfile::factory()->create(['id_user' => $otherPhotographer->id_user]);

        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this
            ->actingAs($otherPhotographer)
            ->patch('/bookings/' . $booking->id_booking . '/accept')
            ->assertForbidden();

        $this
            ->actingAs($otherPhotographer)
            ->patch('/bookings/' . $booking->id_booking . '/reject')
            ->assertForbidden();

        $this
            ->actingAs($otherPhotographer)
            ->patch('/bookings/' . $booking->id_booking . '/complete')
            ->assertForbidden();
    }

    public function test_client_can_cancel_pending_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'cancelled',
        ]);
    }

    public function test_client_can_cancel_accepted_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'accepted',
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'cancelled',
        ]);
    }

    public function test_client_cannot_cancel_another_clients_booking(): void
    {
        [$client1] = $this->bookableSetup();
        [$client2, , , $service2, $availability2] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client2->id_user,
            'id_service' => $service2->id_service,
            'id_availability' => $availability2->id_availability,
            'event_date' => $availability2->available_date->format('Y-m-d'),
            'total_price' => $service2->price,
        ]);

        $this
            ->actingAs($client1)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertForbidden();
    }

    public function test_client_cannot_cancel_completed_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'completed',
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertForbidden();
    }

    public function test_client_cannot_cancel_rejected_booking(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'rejected',
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertForbidden();
    }

    public function test_service_is_required(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'id_service' => '',
            ]))
            ->assertSessionHasErrors('id_service');
    }

    public function test_soft_deleted_service_is_rejected(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $service->delete();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_service');
    }

    public function test_availability_is_required(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'id_availability' => '',
            ]))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_soft_deleted_availability_is_rejected(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $availability->delete();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_event_address_is_required(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'event_address' => '',
            ]))
            ->assertSessionHasErrors('event_address');
    }

    public function test_past_availability_is_rejected(): void
    {
        [$client, , $profile, $service] = $this->bookableSetup();
        $pastAvailability = Availability::factory()->create([
            'id_profile' => $profile->id_profile,
            'available_date' => now()->subDay()->format('Y-m-d'),
        ]);

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $pastAvailability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_availability_must_belong_to_same_photographer_as_service(): void
    {
        [$client, , $profile, $service] = $this->bookableSetup();
        [, , $otherProfile] = $this->bookableSetup();
        $otherAvailability = Availability::factory()->create([
            'id_profile' => $otherProfile->id_profile,
        ]);

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $otherAvailability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_unapproved_photographer_cannot_be_booked(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'pending',
        ]);
        $service = Service::factory()->create(['id_profile' => $profile->id_profile]);
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_service');
    }

    public function test_same_active_slot_cannot_be_booked_twice(): void
    {
        [$client] = $this->bookableSetup();
        [$client2, , , $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($client2)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_rejected_slot_can_be_booked_again(): void
    {
        [$client] = $this->bookableSetup();
        [$client2, , , $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'rejected',
        ]);

        $this
            ->actingAs($client2)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_cancelled_slot_can_be_booked_again(): void
    {
        [$client] = $this->bookableSetup();
        [$client2, , , $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'cancelled',
        ]);

        $this
            ->actingAs($client2)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_completed_slot_cannot_be_booked_again(): void
    {
        [$client] = $this->bookableSetup();
        [$client2, , , $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'completed',
        ]);

        $this
            ->actingAs($client2)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_accepted_slot_cannot_be_booked_twice(): void
    {
        [$client] = $this->bookableSetup();
        [$client2, , , $service, $availability] = $this->bookableSetup();
        Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'accepted',
        ]);

        $this
            ->actingAs($client2)
            ->post('/bookings', $this->payload($service, $availability))
            ->assertSessionHasErrors('id_availability');
    }

    public function test_total_price_equals_service_price(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $service->update(['price' => 899.99]);

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability));

        $this->assertDatabaseHas('bookings', [
            'id_availability' => $availability->id_availability,
            'total_price' => 899.99,
        ]);
    }

    public function test_submitted_fake_total_price_is_ignored(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $service->update(['price' => 899.99]);

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'total_price' => 1.00,
                'status' => 'completed',
            ]));

        $this->assertDatabaseHas('bookings', [
            'id_availability' => $availability->id_availability,
            'total_price' => 899.99,
            'status' => 'pending',
        ]);
    }

    public function test_submitted_id_user_is_ignored(): void
    {
        [$client] = $this->bookableSetup();
        [$otherClient, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'id_user' => $otherClient->id_user,
            ]));

        $this->assertDatabaseHas('bookings', [
            'id_availability' => $availability->id_availability,
            'id_user' => $client->id_user,
        ]);

        $this->assertDatabaseMissing('bookings', [
            'id_user' => $otherClient->id_user,
        ]);
    }

    public function test_submitted_id_profile_is_not_trusted(): void
    {
        [$client] = $this->bookableSetup();
        [, $otherPhotographer, , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability, [
                'id_profile' => $otherPhotographer->photographerProfile->id_profile,
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_availability' => $availability->id_availability,
            'id_service' => $service->id_service,
            'id_user' => $client->id_user,
        ]);
    }

    public function test_pending_booking_can_be_accepted(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/accept')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'accepted',
        ]);
    }

    public function test_pending_booking_can_be_rejected(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/reject')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'rejected',
        ]);
    }

    public function test_accepted_booking_can_be_completed(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'accepted',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/complete')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'completed',
        ]);
    }

    public function test_pending_booking_can_be_cancelled(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'cancelled',
        ]);
    }

    public function test_accepted_booking_can_be_cancelled(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'accepted',
        ]);

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $booking->id_booking,
            'status' => 'cancelled',
        ]);
    }

    public function test_rejected_booking_cannot_be_accepted(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'rejected',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/accept')
            ->assertForbidden();
    }

    public function test_pending_booking_cannot_be_completed(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/complete')
            ->assertForbidden();
    }

    public function test_completed_booking_is_terminal(): void
    {
        [$client, $photographer, , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'completed',
        ]);

        $this
            ->actingAs($photographer)
            ->patch('/bookings/' . $booking->id_booking . '/accept')
            ->assertForbidden();

        $this
            ->actingAs($client)
            ->patch('/bookings/' . $booking->id_booking . '/cancel')
            ->assertForbidden();
    }

    public function test_booking_belongs_to_user(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($client->id_user, $booking->user->id_user);
    }

    public function test_booking_belongs_to_service(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this->assertInstanceOf(Service::class, $booking->service);
        $this->assertEquals($service->id_service, $booking->service->id_service);
    }

    public function test_booking_belongs_to_availability(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();
        $booking = Booking::factory()->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
        ]);

        $this->assertInstanceOf(Availability::class, $booking->availability);
        $this->assertEquals($availability->id_availability, $booking->availability->id_availability);
        $this->assertEquals($booking->id_availability, $availability->bookings()->first()->id_availability);
    }

    public function test_booking_uses_string_id(): void
    {
        [$client, , , $service, $availability] = $this->bookableSetup();

        $this
            ->actingAs($client)
            ->post('/bookings', $this->payload($service, $availability));

        $booking = Booking::where('id_availability', $availability->id_availability)->first();

        $this->assertStringStartsWith('BKG_', $booking->id_booking);
        $this->assertEquals(20, strlen($booking->id_booking));
        $this->assertFalse($booking->getIncrementing());
        $this->assertEquals('string', $booking->getKeyType());
    }
}
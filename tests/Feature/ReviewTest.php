<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function reviewSetup(array $bookingOverrides = []): array
    {
        $client = User::factory()->create(['role' => 'client']);
        $photographer = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);
        $service = Service::factory()->create(['id_profile' => $profile->id_profile]);
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $booking = Booking::factory()->create(array_merge([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_date' => $availability->available_date->format('Y-m-d'),
            'total_price' => $service->price,
            'status' => 'completed',
        ], $bookingOverrides));

        return [$client, $photographer, $profile, $service, $availability, $booking];
    }

    private function payload(Booking $booking, array $overrides = []): array
    {
        return array_merge([
            'id_booking' => $booking->id_booking,
            'rating' => 5,
            'comment' => 'Great work!',
        ], $overrides);
    }

    public function test_guest_cannot_access_review_create(): void
    {
        $this->get('/reviews/create')->assertRedirect(route('login'));
    }

    public function test_guest_cannot_post_review(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->post('/reviews', $this->payload($booking))
            ->assertRedirect(route('login'));
    }

    public function test_client_can_review_own_completed_booking(): void
    {
        [$client, , $profile, , , $booking] = $this->reviewSetup();

        $response = $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('bookings.show', $booking->id_booking))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'rating' => 5,
            'comment' => 'Great work!',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);
    }

    public function test_client_can_access_review_create_for_own_completed_booking(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->get('/reviews/create?booking=' . $booking->id_booking)
            ->assertOk();
    }

    public function test_client_is_redirected_from_create_when_booking_not_completed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup(['status' => 'accepted']);

        $this
            ->actingAs($client)
            ->get('/reviews/create?booking=' . $booking->id_booking)
            ->assertRedirect(route('bookings.show', $booking->id_booking))
            ->assertSessionHas('error');
    }

    public function test_client_cannot_review_another_clients_booking(): void
    {
        [$otherClient, , , , , $booking] = $this->reviewSetup();

        [$client] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_photographer_cannot_create_review(): void
    {
        [, $photographer, , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($photographer)
            ->post('/reviews', $this->payload($booking))
            ->assertForbidden();
    }

    public function test_admin_cannot_create_review(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($admin)
            ->post('/reviews', $this->payload($booking))
            ->assertForbidden();
    }

    public function test_authenticated_user_can_see_reviews_on_photographer_profile(): void
    {
        [$client, , $profile, , , $booking] = $this->reviewSetup();

        Review::factory()->create([
            'rating' => 4,
            'comment' => 'Amazing photos!',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile)
            ->assertOk()
            ->assertSee('Amazing photos!');
    }

    public function test_pending_booking_cannot_be_reviewed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup(['status' => 'pending']);

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_accepted_booking_cannot_be_reviewed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup(['status' => 'accepted']);

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_rejected_booking_cannot_be_reviewed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup(['status' => 'rejected']);

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_cancelled_booking_cannot_be_reviewed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup(['status' => 'cancelled']);

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_completed_booking_can_be_reviewed(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_second_review_for_same_booking_rejected(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        Review::factory()->create([
            'id_user' => $client->id_user,
            'id_profile' => $booking->service->photographerProfile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');

        $this->assertEquals(1, Review::count());
    }

    public function test_soft_deleted_review_still_prevents_second_review(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $review = Review::factory()->create([
            'id_user' => $client->id_user,
            'id_profile' => $booking->service->photographerProfile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        $review->delete();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');

        $this->assertEquals(1, Review::withTrashed()->count());
    }

    public function test_rating_is_required(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['rating' => '']))
            ->assertSessionHasErrors('rating');
    }

    public function test_rating_must_be_integer(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['rating' => 'abc']))
            ->assertSessionHasErrors('rating');
    }

    public function test_rating_minimum_1(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['rating' => 0]))
            ->assertSessionHasErrors('rating');
    }

    public function test_rating_maximum_5(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['rating' => 6]))
            ->assertSessionHasErrors('rating');
    }

    public function test_comment_is_optional(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['comment' => null]))
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_comment_max_2000(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['comment' => str_repeat('a', 2001)]))
            ->assertSessionHasErrors('comment');
    }

    public function test_invalid_booking_rejected(): void
    {
        [$client] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', [
                'id_booking' => 'BKG_UNKNOWN',
                'rating' => 5,
                'comment' => 'Great work!',
            ])
            ->assertSessionHasErrors('id_booking');
    }

    public function test_soft_deleted_booking_rejected(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $booking->delete();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking))
            ->assertSessionHasErrors('id_booking');
    }

    public function test_submitted_id_user_is_ignored(): void
    {
        [$client] = $this->reviewSetup();
        [$otherClient, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($otherClient)
            ->post('/reviews', $this->payload($booking, ['id_user' => $client->id_user]));

        $this->assertDatabaseHas('reviews', [
            'id_booking' => $booking->id_booking,
            'id_user' => $otherClient->id_user,
        ]);

        $this->assertDatabaseMissing('reviews', [
            'id_user' => $client->id_user,
        ]);
    }

    public function test_submitted_id_profile_is_ignored(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();
        [, , $otherProfile] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['id_profile' => $otherProfile->id_profile]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reviews', [
            'id_booking' => $booking->id_booking,
            'id_profile' => $booking->service->photographerProfile->id_profile,
        ]);

        $this->assertDatabaseMissing('reviews', [
            'id_profile' => $otherProfile->id_profile,
        ]);
    }

    public function test_submitted_review_date_is_ignored(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['review_date' => '2020-01-01 00:00:00']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('reviews', [
            'id_booking' => $booking->id_booking,
            'review_date' => '2020-01-01 00:00:00',
        ]);
    }

    public function test_photographer_cannot_be_spoofed(): void
    {
        [$client, , $profile, , , $booking] = $this->reviewSetup();
        [, , $otherProfile] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking, ['id_profile' => $otherProfile->id_profile]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reviews', [
            'id_booking' => $booking->id_booking,
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_profile_is_derived_from_bookings_service(): void
    {
        [$client, , $profile, , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking));

        $this->assertDatabaseHas('reviews', [
            'id_booking' => $booking->id_booking,
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_user_is_derived_from_authenticated_user(): void
    {
        [$client, , , , , $booking] = $this->reviewSetup();

        $this
            ->actingAs($client)
            ->post('/reviews', $this->payload($booking));

        $this->assertDatabaseHas('reviews', [
            'id_booking' => $booking->id_booking,
            'id_user' => $client->id_user,
        ]);
    }

    public function test_review_belongs_to_booking(): void
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(Booking::class, $review->booking);
        $this->assertEquals($review->id_booking, $review->booking->id_booking);
    }

    public function test_review_belongs_to_user(): void
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($review->id_user, $review->user->id_user);
    }

    public function test_review_belongs_to_photographer_profile(): void
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(PhotographerProfile::class, $review->photographerProfile);
        $this->assertEquals($review->id_profile, $review->photographerProfile->id_profile);
    }

    public function test_booking_has_many_reviews(): void
    {
        $review = Review::factory()->create();

        $this->assertTrue($review->booking->reviews->contains($review));
    }

    public function test_photographer_profile_has_many_reviews(): void
    {
        $review = Review::factory()->create();

        $this->assertTrue($review->photographerProfile->reviews->contains($review));
    }

    public function test_user_has_many_reviews(): void
    {
        $review = Review::factory()->create();

        $this->assertTrue($review->user->reviews->contains($review));
    }

    public function test_deleting_review_soft_deletes_it(): void
    {
        $review = Review::factory()->create();

        $review->delete();

        $this->assertSoftDeleted('reviews', ['id_review' => $review->id_review]);
    }

    public function test_deleting_review_does_not_delete_booking(): void
    {
        $review = Review::factory()->create();

        $review->delete();

        $this->assertDatabaseHas('bookings', [
            'id_booking' => $review->id_booking,
            'deleted_at' => null,
        ]);
    }

    public function test_soft_deleted_review_is_not_displayed_on_photographer_profile(): void
    {
        [$client, , $profile, , , $booking] = $this->reviewSetup();

        $review = Review::factory()->create([
            'rating' => 4,
            'comment' => 'Amazing photos!',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        $review->delete();

        $this
            ->actingAs($client)
            ->get('/photographer-profile/' . $profile->id_profile)
            ->assertOk()
            ->assertDontSee('Amazing photos!');
    }

    public function test_review_uses_string_id(): void
    {
        $review = Review::factory()->create();

        $this->assertStringStartsWith('REV_', $review->id_review);
        $this->assertEquals(20, strlen($review->id_review));
        $this->assertFalse($review->getIncrementing());
        $this->assertEquals('string', $review->getKeyType());
    }
}
<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ClientDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createBooking(
        User $client,
        string $status = 'pending',
        string $serviceTitle = 'Wedding Shoot',
        string $eventAddress = '123 Main Street'
    ): Booking {
        $photographer = User::factory()->create([
            'role' => 'photographer',
            'first_name' => 'Pho',
            'last_name' => 'Tog',
        ]);

        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
            'title' => $serviceTitle,
        ]);

        $availability = Availability::factory()->create([
            'id_profile' => $profile->id_profile,
        ]);

        return Booking::factory()->withStatus($status)->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
            'event_address' => $eventAddress,
        ]);
    }

    private function createReviewFor(
        Booking $booking,
        User $client,
        int $rating,
        ?string $comment = null
    ): Review {
        return Review::factory()->create([
            'rating' => $rating,
            'comment' => $comment,
            'id_user' => $client->id_user,
            'id_profile' => $booking->service->photographerProfile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_unverified_client_can_access_dashboard(): void
    {
        $client = User::factory()->unverified()->create(['role' => 'client']);

        $this
            ->actingAs($client)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.client');
    }

    public function test_client_sees_dashboard(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertViewIs('dashboard.client')
            ->assertSee('Welcome back, Jane');
    }

    public function test_client_sees_only_own_bookings(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);

        $ownBooking = $this->createBooking($client, 'pending');
        $otherBooking = $this->createBooking($otherClient, 'pending', 'Portrait Session', '999 Other Street');

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Wedding Shoot');
        $response->assertSee($ownBooking->event_address);
        $response->assertDontSee('Portrait Session');
        $response->assertDontSee('999 Other Street');

        $this->assertSame(1, $response->viewData('bookings')->count());
        $this->assertSame($ownBooking->id_booking, $response->viewData('bookings')->first()->id_booking);
    }

    public function test_client_sees_booking_status_summary(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this->createBooking($client, 'pending');
        $this->createBooking($client, 'accepted');
        $this->createBooking($client, 'completed');
        $this->createBooking($client, 'cancelled');

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Pending');
        $response->assertSee('Confirmed');
        $response->assertSee('Completed');
        $response->assertSee('Cancelled');

        $statusCounts = $response->viewData('statusCounts');
        $this->assertInstanceOf(Collection::class, $statusCounts);
        $this->assertSame(1, $statusCounts->get('pending'));
        $this->assertSame(1, $statusCounts->get('accepted'));
        $this->assertSame(1, $statusCounts->get('completed'));
        $this->assertSame(1, $statusCounts->get('cancelled'));
    }

    public function test_client_sees_recent_reviews_with_rating(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);

        $booking = $this->createBooking($client, 'completed');
        $otherBooking = $this->createBooking($otherClient, 'completed');

        $this->createReviewFor($booking, $client, 5, 'Amazing work.');
        $this->createReviewFor($otherBooking, $otherClient, 2, 'Not great.');

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Amazing work.');
        $response->assertSee('5 / 5');
        $response->assertDontSee('Not great.');
        $response->assertDontSee('2 / 5');

        $this->assertSame(1, $response->viewData('recentReviews')->count());
    }

    public function test_completed_reviewable_booking_shows_review_action(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $completedBooking = $this->createBooking($client, 'completed');
        $reviewedBooking = $this->createBooking($client, 'completed', 'Reviewed Event', '456 Main Street');
        $this->createReviewFor($reviewedBooking, $client, 4, 'Really good.');
        $pendingBooking = $this->createBooking($client, 'pending', 'Pending Event', '789 Main Street');

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee(route('reviews.create', ['booking' => $completedBooking->id_booking]));
        $response->assertDontSee(route('reviews.create', ['booking' => $reviewedBooking->id_booking]));
        $response->assertDontSee(route('reviews.create', ['booking' => $pendingBooking->id_booking]));
    }

    public function test_photographer_does_not_see_client_dashboard(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);

        $this
            ->actingAs($photographer)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard');
    }

    public function test_admin_does_not_see_client_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard');
    }

    public function test_empty_state_when_no_bookings(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertSee('No bookings yet.')
            ->assertSee('No reviews yet.');

        $this->assertSame(0, $response->viewData('bookings')->count());
        $this->assertSame(0, $response->viewData('recentReviews')->count());
    }
}
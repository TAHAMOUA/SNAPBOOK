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

class PhotographerDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function photographerSetup(string $firstName = 'Pho', string $lastName = 'Tog'): array
    {
        $photographer = User::factory()->create([
            'role' => 'photographer',
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        return [$photographer, $profile];
    }

    private function createBookingFor(
        PhotographerProfile $profile,
        User $client,
        string $serviceTitle,
        string $status = 'pending',
        string $eventAddress = '123 Main Street'
    ): Booking {
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

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_photographer_can_access_dashboard(): void
    {
        [$photographer, $profile] = $this->photographerSetup();

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertViewIs('dashboard.photographer')
            ->assertSee('Welcome back, ' . $photographer->first_name);
    }

    public function test_client_dashboard_is_unchanged(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $this
            ->actingAs($client)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard.client');
    }

    public function test_admin_behavior_is_unchanged(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard');
    }

    public function test_photographer_without_profile_gets_create_prompt(): void
    {
        $photographer = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertViewIs('dashboard.photographer')
            ->assertSee("You don't have a photographer profile yet.", false)
            ->assertSee(route('photographer-profile.create'))
            ->assertDontSee('Recent Booking Requests');
    }

    public function test_photographer_sees_only_own_data(): void
    {
        [$photographerA, $profileA] = $this->photographerSetup('Anna', 'Artist');
        [, $profileB] = $this->photographerSetup('Bob', 'Boss');

        $clientA = User::factory()->create(['role' => 'client', 'first_name' => 'Carla']);
        $clientB = User::factory()->create(['role' => 'client', 'first_name' => 'Dan']);

        $ownBooking = $this->createBookingFor($profileA, $clientA, 'Alpha Shoot', 'accepted');
        $this->createBookingFor($profileB, $clientB, 'Beta Shoot', 'accepted');

        $response = $this
            ->actingAs($photographerA)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Alpha Shoot');
        $response->assertSee('Carla');
        $response->assertDontSee('Beta Shoot');
        $response->assertDontSee('Dan');

        $this->assertSame(1, $response->viewData('recentBookings')->count());
        $this->assertSame($ownBooking->id_booking, $response->viewData('recentBookings')->first()->id_booking);
    }

    public function test_statistics_are_correct(): void
    {
        [$photographer, $profile] = $this->photographerSetup();
        $client = User::factory()->create(['role' => 'client']);

        $this->createBookingFor($profile, $client, 'Pending One', 'pending');
        $this->createBookingFor($profile, $client, 'Pending Two', 'pending');
        $this->createBookingFor($profile, $client, 'Accepted One', 'accepted');
        $completedOne = $this->createBookingFor($profile, $client, 'Completed One', 'completed');
        $completedTwo = $this->createBookingFor($profile, $client, 'Completed Two', 'completed');
        $this->createBookingFor($profile, $client, 'Cancelled One', 'cancelled');

        Portfolio::factory()->count(2)->create(['id_profile' => $profile->id_profile]);

        Review::factory()->create([
            'rating' => 5,
            'comment' => 'Amazing.',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $completedOne->id_booking,
        ]);
        Review::factory()->create([
            'rating' => 4,
            'comment' => 'Very good.',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $completedTwo->id_booking,
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Pending Requests');
        $response->assertSee('Accepted');
        $response->assertSee('Completed');
        $response->assertSee('Total Services');
        $response->assertSee('Portfolio Photos');
        $response->assertSee('Reviews');

        $bookingStats = $response->viewData('bookingStats');
        $this->assertSame(2, $bookingStats->get('pending'));
        $this->assertSame(1, $bookingStats->get('accepted'));
        $this->assertSame(2, $bookingStats->get('completed'));
        $this->assertSame(6, $response->viewData('servicesCount'));
        $this->assertSame(2, $response->viewData('portfolioCount'));
        $this->assertSame(2, $response->viewData('reviewsCount'));
        $this->assertSame(4.5, $response->viewData('reviewsAverage'));
    }

    public function test_recent_booking_requests_are_shown(): void
    {
        [$photographer, $profile] = $this->photographerSetup();
        $client = User::factory()->create(['role' => 'client', 'first_name' => 'Carla']);

        $booking = $this->createBookingFor($profile, $client, 'Wedding Shoot', 'pending', '456 Oak Avenue');

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Wedding Shoot');
        $response->assertSee('Carla');
        $response->assertSee('456 Oak Avenue');
        $response->assertSee($booking->event_date->format('M j, Y'));
        $response->assertSee('Pending');
    }

    public function test_pending_booking_shows_accept_and_reject_actions(): void
    {
        [$photographer, $profile] = $this->photographerSetup();
        [, $otherProfile] = $this->photographerSetup('Other', 'Tog');

        $client = User::factory()->create(['role' => 'client']);
        $otherClient = User::factory()->create(['role' => 'client']);

        $ownBooking = $this->createBookingFor($profile, $client, 'Mine');
        $otherBooking = $this->createBookingFor($otherProfile, $otherClient, 'Yours');

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee(route('bookings.accept', $ownBooking->id_booking));
        $response->assertSee(route('bookings.reject', $ownBooking->id_booking));
        $response->assertDontSee(route('bookings.accept', $otherBooking->id_booking));
        $response->assertDontSee(route('bookings.reject', $otherBooking->id_booking));
    }

    public function test_accepted_booking_shows_complete_action(): void
    {
        [$photographer, $profile] = $this->photographerSetup();
        $client = User::factory()->create(['role' => 'client']);

        $acceptedBooking = $this->createBookingFor($profile, $client, 'Accepted Event', 'accepted');
        $pendingBooking = $this->createBookingFor($profile, $client, 'Pending Event', 'pending');

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee(route('bookings.complete', $acceptedBooking->id_booking));
        $response->assertDontSee(route('bookings.complete', $pendingBooking->id_booking));
        $response->assertDontSee(route('bookings.accept', $acceptedBooking->id_booking));
    }

    public function test_previews_show_services_portfolio_and_reviews(): void
    {
        [$photographer, $profile] = $this->photographerSetup();
        $client = User::factory()->create(['role' => 'client']);

        $category = Category::factory()->create(['category_name' => 'Wedding']);
        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
            'title' => 'Golden Hour Shoot',
            'price' => 800.00,
            'id_category' => $category->id_category,
        ]);
        Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'description' => 'Sunset portrait',
        ]);
        $completed = $this->createBookingFor($profile, $client, 'Review Event', 'completed');
        Review::factory()->create([
            'rating' => 5,
            'comment' => 'Fantastic!',
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $completed->id_booking,
        ]);

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Golden Hour Shoot');
        $response->assertSee('Wedding');
        $response->assertSee('Sunset portrait');
        $response->assertSee('Fantastic!');
        $response->assertSee('5 / 5');
    }

    public function test_quick_links_are_present(): void
    {
        [$photographer, $profile] = $this->photographerSetup();

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertSee(route('services.index'));
        $response->assertSee(route('portfolio.index'));
        $response->assertSee(route('availabilities.index'));
        $response->assertSee(route('bookings.index'));
        $response->assertSee(route('photographer-profile.show', $profile->id_profile));
    }

    public function test_empty_states_work(): void
    {
        [$photographer] = $this->photographerSetup();

        $response = $this
            ->actingAs($photographer)
            ->get('/dashboard');

        $response
            ->assertOk()
            ->assertSee('No booking requests yet.')
            ->assertSee('No services yet.')
            ->assertSee('No portfolio photos yet.')
            ->assertSee('No reviews yet.');
    }
}
<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $client = User::factory()->create(['role' => 'client']);

        $photographer = User::factory()->create(['role' => 'photographer']);

        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
        ]);

        $booking = Booking::factory()->withStatus('completed')->create([
            'id_user' => $client->id_user,
            'id_service' => $service->id_service,
        ]);

        return [
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional(0.7)->sentence(),
            'review_date' => now(),
            'id_user' => $client->id_user,
            'id_profile' => $profile->id_profile,
            'id_booking' => $booking->id_booking,
        ];
    }
}
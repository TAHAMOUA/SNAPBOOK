<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $photographer = User::factory()->create(['role' => 'photographer']);

        $profile = PhotographerProfile::factory()->create([
            'id_user' => $photographer->id_user,
            'validation_status' => 'approved',
        ]);

        $service = Service::factory()->create([
            'id_profile' => $profile->id_profile,
        ]);

        $availability = Availability::factory()->create([
            'id_profile' => $profile->id_profile,
        ]);

        return [
            'booking_date' => now(),
            'event_date' => $availability->available_date->format('Y-m-d'),
            'event_address' => fake()->streetAddress(),
            'total_price' => $service->price,
            'status' => 'pending',
            'id_user' => User::factory()->create(['role' => 'client']),
            'id_service' => $service->id_service,
            'id_availability' => $availability->id_availability,
        ];
    }

    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
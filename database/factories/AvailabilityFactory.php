<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\PhotographerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    protected $model = Availability::class;

    public function definition(): array
    {
        return [
            'available_date' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'start_time' => fake()->time('H:00'),
            'end_time' => function (array $attributes) {
                $start = strtotime($attributes['start_time']);

                return date('H:i', $start + fake()->numberBetween(1, 4) * 3600);
            },
            'id_profile' => PhotographerProfile::factory(),
        ];
    }
}
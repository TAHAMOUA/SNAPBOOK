<?php

namespace Database\Factories;

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotographerProfileFactory extends Factory
{
    protected $model = PhotographerProfile::class;

    public function definition(): array
    {
        return [
            'bio' => fake()->paragraph(),
            'city' => fake()->city(),
            'experience' => fake()->numberBetween(0, 30),
            'validation_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'id_user' => User::factory(),
        ];
    }
}
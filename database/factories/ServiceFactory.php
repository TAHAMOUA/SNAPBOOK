<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 50, 2000),
            'duration' => fake()->numberBetween(30, 480),
            'id_profile' => PhotographerProfile::factory(),
            'id_category' => Category::factory(),
        ];
    }
}
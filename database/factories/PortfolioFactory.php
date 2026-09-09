<?php

namespace Database\Factories;

use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class PortfolioFactory extends Factory
{
    protected $model = Portfolio::class;

    public function definition(): array
    {
        $image = new UploadedFile(
            __DIR__.'/../../tests/Fixtures/photo.png',
            'photo.png',
            'image/png',
            null,
            true
        );

        return [
            'image' => $image->store('portfolio', 'public'),
            'description' => fake()->sentence(),
            'id_profile' => PhotographerProfile::factory(),
        ];
    }
}

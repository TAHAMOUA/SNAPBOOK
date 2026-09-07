<?php

namespace App\Providers;

use App\Models\PhotographerProfile;
use App\Policies\PhotographerProfilePolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        PhotographerProfile::class => PhotographerProfilePolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
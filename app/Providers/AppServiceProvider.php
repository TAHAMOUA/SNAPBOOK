<?php

namespace App\Providers;

use App\Models\PhotographerProfile;
use App\Models\Service;
use App\Policies\PhotographerProfilePolicy;
use App\Policies\ServicePolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        PhotographerProfile::class => PhotographerProfilePolicy::class,
        Service::class => ServicePolicy::class,
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
<?php

namespace App\Providers;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use App\Models\Service;
use App\Policies\AvailabilityPolicy;
use App\Policies\BookingPolicy;
use App\Policies\PhotographerProfilePolicy;
use App\Policies\PortfolioPolicy;
use App\Policies\ServicePolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Availability::class => AvailabilityPolicy::class,
        Booking::class => BookingPolicy::class,
        PhotographerProfile::class => PhotographerProfilePolicy::class,
        Portfolio::class => PortfolioPolicy::class,
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
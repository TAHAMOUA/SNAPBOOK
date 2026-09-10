<?php

namespace App\Providers;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use App\Policies\AvailabilityPolicy;
use App\Policies\BookingPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\PhotographerProfilePolicy;
use App\Policies\PortfolioPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Availability::class => AvailabilityPolicy::class,
        Booking::class => BookingPolicy::class,
        Category::class => CategoryPolicy::class,
        PhotographerProfile::class => PhotographerProfilePolicy::class,
        Portfolio::class => PortfolioPolicy::class,
        Review::class => ReviewPolicy::class,
        Service::class => ServicePolicy::class,
        User::class => UserPolicy::class,
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
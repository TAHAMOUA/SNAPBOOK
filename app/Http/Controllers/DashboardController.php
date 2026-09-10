<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role === 'client') {
            $bookings = Booking::with([
                'service.photographerProfile.user',
            ])
                ->withCount(['reviews' => function ($query) {
                    $query->withTrashed();
                }])
                ->where('id_user', $user->id_user)
                ->latest()
                ->limit(5)
                ->get();

            $statusCounts = Booking::where('id_user', $user->id_user)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $recentReviews = Review::with(['photographerProfile.user'])
                ->where('id_user', $user->id_user)
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.client', compact('user', 'bookings', 'statusCounts', 'recentReviews'));
        }

        if ($user->role === 'photographer') {
            $profile = $user->photographerProfile;

            if (! $profile) {
                return view('dashboard.photographer', compact('user', 'profile'));
            }

            $bookingStats = Booking::whereHas('service', function ($query) use ($profile) {
                $query->where('id_profile', $profile->id_profile);
            })
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $servicesCount = Service::where('id_profile', $profile->id_profile)->count();
            $portfolioCount = Portfolio::where('id_profile', $profile->id_profile)->count();
            $reviewsCount = Review::where('id_profile', $profile->id_profile)->count();
            $reviewsAverage = Review::where('id_profile', $profile->id_profile)->avg('rating');

            $recentBookings = Booking::with(['user', 'service'])
                ->whereHas('service', function ($query) use ($profile) {
                    $query->where('id_profile', $profile->id_profile);
                })
                ->latest()
                ->limit(5)
                ->get();

            $recentServices = Service::with('category')
                ->where('id_profile', $profile->id_profile)
                ->latest()
                ->limit(5)
                ->get();

            $recentPortfolio = Portfolio::where('id_profile', $profile->id_profile)
                ->latest()
                ->limit(5)
                ->get();

            $recentReviews = Review::with('user')
                ->where('id_profile', $profile->id_profile)
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.photographer', compact(
                'user',
                'profile',
                'bookingStats',
                'servicesCount',
                'portfolioCount',
                'reviewsCount',
                'reviewsAverage',
                'recentBookings',
                'recentServices',
                'recentPortfolio',
                'recentReviews'
            ));
        }

        return view('dashboard');
    }
}
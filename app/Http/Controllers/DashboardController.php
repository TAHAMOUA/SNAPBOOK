<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role !== 'client') {
            return view('dashboard');
        }

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
}
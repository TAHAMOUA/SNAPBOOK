<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PhotographerProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin', auth()->user());

        $stats = [
            'total_users' => User::count(),
            'total_photographers' => PhotographerProfile::count(),
            'pending_profiles' => PhotographerProfile::where('validation_status', 'pending')->count(),
            'approved_profiles' => PhotographerProfile::where('validation_status', 'approved')->count(),
            'total_bookings' => Booking::count(),
            'total_reviews' => Review::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

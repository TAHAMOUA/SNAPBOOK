<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin', auth()->user());

        $bookings = Booking::with(['user', 'service.photographerProfile.user'])
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'client') {
            abort(403);
        }

        $booking = Booking::with(['service.photographerProfile.user'])
            ->findOrFail($request->query('booking'));

        if (! $this->isReviewable($booking)) {
            return redirect()
                ->route('bookings.show', $booking->id_booking)
                ->with('error', 'This booking is not available for review.');
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(ReviewRequest $request): RedirectResponse
    {
        $this->authorize('create', Review::class);

        $user = auth()->user();

        $booking = Booking::with('service.photographerProfile')->findOrFail($request->id_booking);

        $this->ensureReviewable($booking);

        Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'review_date' => now(),
            'id_user' => $user->id_user,
            'id_profile' => $booking->service->photographerProfile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);

        return redirect()
            ->route('bookings.show', $booking->id_booking)
            ->with('success', 'Review submitted successfully.');
    }

    private function isReviewable(Booking $booking): bool
    {
        if ($booking->id_user !== auth()->user()->id_user) {
            return false;
        }

        if ($booking->status !== 'completed') {
            return false;
        }

        if (! $booking->service || ! $booking->service->photographerProfile) {
            return false;
        }

        if (Review::withTrashed()->where('id_booking', $booking->id_booking)->exists()) {
            return false;
        }

        return true;
    }

    private function ensureReviewable(Booking $booking): void
    {
        if ($booking->id_user !== auth()->user()->id_user) {
            throw ValidationException::withMessages([
                'id_booking' => 'You can only review your own bookings.',
            ]);
        }

        if ($booking->status !== 'completed') {
            throw ValidationException::withMessages([
                'id_booking' => 'Only completed bookings can be reviewed.',
            ]);
        }

        if (! $booking->service || ! $booking->service->photographerProfile) {
            throw ValidationException::withMessages([
                'id_booking' => 'This booking cannot be reviewed.',
            ]);
        }

        if (Review::withTrashed()->where('id_booking', $booking->id_booking)->exists()) {
            throw ValidationException::withMessages([
                'id_booking' => 'This booking has already been reviewed.',
            ]);
        }
    }
}
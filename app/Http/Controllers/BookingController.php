<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role === 'client') {
            $bookings = Booking::with(['user', 'service.photographerProfile.user', 'availability'])
                ->where('id_user', $user->id_user)
                ->latest()
                ->paginate(15);
        } elseif ($user->role === 'photographer' && $user->photographerProfile) {
            $bookings = Booking::with(['user', 'service.photographerProfile.user', 'availability'])
                ->whereHas('service', fn ($query) => $query->where('id_profile', $user->photographerProfile->id_profile))
                ->latest()
                ->paginate(15);
        } else {
            abort(403);
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'client') {
            abort(403);
        }

        $service = Service::with(['photographerProfile.user', 'category'])->findOrFail($request->query('service'));

        $profile = $service->photographerProfile;

        if (! $profile || $profile->validation_status !== 'approved') {
            abort(403);
        }

        $availabilities = Availability::where('id_profile', $profile->id_profile)
            ->where('available_date', '>=', now()->toDateString())
            ->whereDoesntHave('bookings', fn ($query) => $query->whereIn('status', ['pending', 'accepted', 'completed']))
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get();

        return view('bookings.create', compact('service', 'availabilities'));
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $this->authorize('create', Booking::class);

        $user = auth()->user();

        $service = Service::with('photographerProfile')->findOrFail($request->id_service);
        $availability = Availability::findOrFail($request->id_availability);

        $this->ensureServiceBookable($service, $availability);

        $booking = DB::transaction(function () use ($request, $service, $availability, $user) {
            Availability::whereKey($availability->id_availability)->lockForUpdate()->first();

            $conflict = Booking::where('id_availability', $availability->id_availability)
                ->whereIn('status', ['pending', 'accepted', 'completed'])
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'id_availability' => 'This time slot has already been booked.',
                ]);
            }

            return Booking::create([
                'booking_date' => now(),
                'event_date' => $availability->available_date->format('Y-m-d'),
                'event_address' => $request->event_address,
                'total_price' => $service->price,
                'status' => 'pending',
                'id_user' => $user->id_user,
                'id_service' => $service->id_service,
                'id_availability' => $availability->id_availability,
            ]);
        });

        return redirect()
            ->route('bookings.show', $booking->id_booking)
            ->with('success', 'Booking created successfully.');
    }

    public function show(string $id_booking): View
    {
        $booking = Booking::with(['user', 'service.photographerProfile.user', 'availability', 'reviews'])
            ->findOrFail($id_booking);

        $this->authorize('view', $booking);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(string $id_booking): RedirectResponse
    {
        $booking = Booking::findOrFail($id_booking);

        $this->authorize('cancel', $booking);

        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    public function accept(string $id_booking): RedirectResponse
    {
        $booking = Booking::findOrFail($id_booking);

        $this->authorize('accept', $booking);

        $booking->update(['status' => 'accepted']);

        return redirect()
            ->route('bookings.show', $booking->id_booking)
            ->with('success', 'Booking accepted successfully.');
    }

    public function reject(string $id_booking): RedirectResponse
    {
        $booking = Booking::findOrFail($id_booking);

        $this->authorize('reject', $booking);

        $booking->update(['status' => 'rejected']);

        return redirect()
            ->route('bookings.show', $booking->id_booking)
            ->with('success', 'Booking rejected.');
    }

    public function complete(string $id_booking): RedirectResponse
    {
        $booking = Booking::findOrFail($id_booking);

        $this->authorize('complete', $booking);

        $booking->update(['status' => 'completed']);

        return redirect()
            ->route('bookings.show', $booking->id_booking)
            ->with('success', 'Booking marked as completed.');
    }

    private function ensureServiceBookable(Service $service, Availability $availability): void
    {
        $profile = $service->photographerProfile;

        if (! $profile || $profile->validation_status !== 'approved') {
            throw ValidationException::withMessages([
                'id_service' => 'This service cannot be booked right now.',
            ]);
        }

        if ($availability->id_profile !== $service->id_profile) {
            throw ValidationException::withMessages([
                'id_availability' => 'This time slot does not belong to the selected service\'s photographer.',
            ]);
        }

        if ($availability->available_date->lt(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'id_availability' => 'Past availability slots cannot be booked.',
            ]);
        }
    }
}
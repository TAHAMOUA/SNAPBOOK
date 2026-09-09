<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        if ($booking->id_user === $user->id_user) {
            return true;
        }

        return $user->id_user === $booking->service->photographerProfile->id_user;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->id_user === $user->id_user
            && in_array($booking->status, ['pending', 'accepted']);
    }

    public function accept(User $user, Booking $booking): bool
    {
        return $this->isPhotographerOf($user, $booking)
            && $booking->status === 'pending';
    }

    public function reject(User $user, Booking $booking): bool
    {
        return $this->isPhotographerOf($user, $booking)
            && $booking->status === 'pending';
    }

    public function complete(User $user, Booking $booking): bool
    {
        return $this->isPhotographerOf($user, $booking)
            && $booking->status === 'accepted';
    }

    private function isPhotographerOf(User $user, Booking $booking): bool
    {
        return $user->id_user === $booking->service->photographerProfile->id_user;
    }
}
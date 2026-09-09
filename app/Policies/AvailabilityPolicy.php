<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
{
    public function view(User $user, Availability $availability): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'photographer' && $user->photographerProfile;
    }

    public function update(User $user, Availability $availability): bool
    {
        return $user->id_user === $availability->photographerProfile->id_user;
    }

    public function delete(User $user, Availability $availability): bool
    {
        return $user->id_user === $availability->photographerProfile->id_user;
    }
}
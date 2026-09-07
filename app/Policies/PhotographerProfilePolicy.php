<?php

namespace App\Policies;

use App\Models\PhotographerProfile;
use App\Models\User;

class PhotographerProfilePolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'photographer' && !$user->photographerProfile;
    }

    public function view(User $user, PhotographerProfile $profile): bool
    {
        return true;
    }

    public function update(User $user, PhotographerProfile $profile): bool
    {
        return $user->id_user === $profile->id_user;
    }
}
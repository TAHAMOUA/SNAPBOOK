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
        if ($profile->validation_status === 'approved') {
            return true;
        }

        return $user->id_user === $profile->id_user || $user->role === 'admin';
    }

    public function update(User $user, PhotographerProfile $profile): bool
    {
        return $user->id_user === $profile->id_user;
    }
}
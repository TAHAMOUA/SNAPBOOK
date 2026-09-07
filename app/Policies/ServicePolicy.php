<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function view(User $user, Service $service): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'photographer' && $user->photographerProfile;
    }

    public function update(User $user, Service $service): bool
    {
        return $user->id_user === $service->photographerProfile->id_user;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->id_user === $service->photographerProfile->id_user;
    }
}
<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;

class PortfolioPolicy
{
    public function view(User $user, Portfolio $portfolio): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'photographer' && $user->photographerProfile;
    }

    public function update(User $user, Portfolio $portfolio): bool
    {
        return $user->id_user === $portfolio->photographerProfile->id_user;
    }

    public function delete(User $user, Portfolio $portfolio): bool
    {
        return $user->id_user === $portfolio->photographerProfile->id_user;
    }
}

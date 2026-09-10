<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function view(User $user, Review $review): bool
    {
        return true;
    }
}
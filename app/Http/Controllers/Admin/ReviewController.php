<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin', auth()->user());

        $reviews = Review::with(['user', 'photographerProfile.user'])
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }
}

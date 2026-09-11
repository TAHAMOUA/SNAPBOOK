<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PhotographerProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController
{
    public function index(): View
    {
        $profiles = PhotographerProfile::query()
            ->where('validation_status', 'approved')
            ->with(['user', 'services'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withMin('services', 'price')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::orderBy('category_name')->get();

        return view('home', compact('profiles', 'categories'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PhotographerProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotographerSearchController extends Controller
{
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));
        $categoryId = trim((string) $request->query('category', ''));

        $profiles = PhotographerProfile::query()
            ->where('validation_status', 'approved')
            ->with(['user', 'services'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withMin('services', 'price');

        if ($term !== '') {
            $profiles->where(function ($query) use ($term) {
                $query->where('city', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%");
                    });
            });
        }

        if ($categoryId !== '') {
            $profiles->whereHas('services', function ($serviceQuery) use ($categoryId) {
                $serviceQuery->where('id_category', $categoryId);
            });
        }

        $profiles = $profiles->latest()->paginate(12)->withQueryString();

        $categories = Category::orderBy('category_name')->get();

        return view('photographers.index', compact('profiles', 'categories'));
    }
}
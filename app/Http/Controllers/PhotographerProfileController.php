<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotographerProfileRequest;
use App\Models\PhotographerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PhotographerProfileController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer') {
            abort(403);
        }

        if ($user->photographerProfile) {
            return redirect()
                ->route('photographer-profile.show', $user->photographerProfile->id_profile)
                ->with('info', 'You already have a photographer profile.');
        }

        return view('photographer-profiles.create');
    }

    public function store(PhotographerProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer') {
            abort(403);
        }

        if ($user->photographerProfile) {
            return redirect()
                ->route('photographer-profile.show', $user->photographerProfile->id_profile)
                ->with('info', 'You already have a photographer profile.');
        }

        $profile = PhotographerProfile::create([
            'bio' => $request->bio,
            'city' => $request->city,
            'experience' => $request->experience,
            'id_user' => $user->id_user,
        ]);

        return redirect()
            ->route('photographer-profile.show', $profile->id_profile)
            ->with('success', 'Photographer profile created successfully.');
    }

    public function show(string $id_profile): View
    {
        $profile = PhotographerProfile::with([
            'user',
            'services.category',
            'portfolios',
            'availabilities',
            'reviews.user',
        ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->withMin('services', 'price')
            ->findOrFail($id_profile);

        $this->authorize('view', $profile);

        return view('photographer-profiles.show', compact('profile'));
    }

    public function edit(string $id_profile): View
    {
        $profile = PhotographerProfile::findOrFail($id_profile);

        $this->authorize('update', $profile);

        return view('photographer-profiles.edit', compact('profile'));
    }

    public function update(PhotographerProfileRequest $request, string $id_profile): RedirectResponse
    {
        $profile = PhotographerProfile::findOrFail($id_profile);

        $this->authorize('update', $profile);

        $profile->update($request->validated());

        return redirect()
            ->route('photographer-profile.show', $profile->id_profile)
            ->with('success', 'Photographer profile updated successfully.');
    }
}
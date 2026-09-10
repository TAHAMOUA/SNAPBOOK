<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotographerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PhotographerProfileController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin', auth()->user());

        $profiles = PhotographerProfile::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.photographer-profiles.index', compact('profiles'));
    }

    public function show(string $id_profile): View
    {
        $this->authorize('admin', auth()->user());

        $profile = PhotographerProfile::with(['user', 'reviews.user'])
            ->findOrFail($id_profile);

        return view('admin.photographer-profiles.show', compact('profile'));
    }

    public function approve(string $id_profile): RedirectResponse
    {
        $this->authorize('admin', auth()->user());

        $profile = PhotographerProfile::findOrFail($id_profile);

        if ($profile->validation_status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending profiles can be approved.']);
        }

        $profile->update(['validation_status' => 'approved']);

        return back()->with('success', 'Profile approved successfully.');
    }

    public function reject(string $id_profile): RedirectResponse
    {
        $this->authorize('admin', auth()->user());

        $profile = PhotographerProfile::findOrFail($id_profile);

        if ($profile->validation_status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending profiles can be rejected.']);
        }

        $profile->update(['validation_status' => 'rejected']);

        return back()->with('success', 'Profile rejected successfully.');
    }
}

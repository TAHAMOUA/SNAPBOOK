<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailabilityRequest;
use App\Models\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        $availabilities = Availability::where('id_profile', $user->photographerProfile->id_profile)
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->paginate(15);

        return view('availabilities.index', compact('availabilities'));
    }

    public function create(): View
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        return view('availabilities.create');
    }

    public function store(AvailabilityRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        $conflict = $this->findConflict(
            $request,
            $user->photographerProfile->id_profile
        );

        if ($conflict) {
            return back()
                ->withErrors(['start_time' => $conflict])
                ->withInput();
        }

        Availability::create([
            'available_date' => $request->available_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'id_profile' => $user->photographerProfile->id_profile,
        ]);

        return redirect()
            ->route('availabilities.index')
            ->with('success', 'Availability created successfully.');
    }

    public function edit(string $id_availability): View
    {
        $availability = Availability::findOrFail($id_availability);

        $this->authorize('update', $availability);

        return view('availabilities.edit', compact('availability'));
    }

    public function update(AvailabilityRequest $request, string $id_availability): RedirectResponse
    {
        $availability = Availability::findOrFail($id_availability);

        $this->authorize('update', $availability);

        $conflict = $this->findConflict(
            $request,
            $availability->id_profile,
            $availability->id_availability
        );

        if ($conflict) {
            return back()
                ->withErrors(['start_time' => $conflict])
                ->withInput();
        }

        $availability->update([
            'available_date' => $request->available_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()
            ->route('availabilities.index')
            ->with('success', 'Availability updated successfully.');
    }

    public function destroy(string $id_availability): RedirectResponse
    {
        $availability = Availability::findOrFail($id_availability);

        $this->authorize('delete', $availability);

        $availability->delete();

        return redirect()
            ->route('availabilities.index')
            ->with('success', 'Availability deleted successfully.');
    }

    private function findConflict(
        AvailabilityRequest $request,
        string $profileId,
        ?string $excludeId = null
    ): ?string
    {
        $query = Availability::where('id_profile', $profileId)
            ->where('available_date', $request->available_date)
            ->when($excludeId, fn ($query) => $query->where('id_availability', '!=', $excludeId));

        $exactDuplicate = (clone $query)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->exists();

        if ($exactDuplicate) {
            return 'A slot with the same date and times already exists.';
        }

        $overlap = (clone $query)
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->exists();

        if ($overlap) {
            return 'This slot overlaps with an existing availability.';
        }

        return null;
    }
}
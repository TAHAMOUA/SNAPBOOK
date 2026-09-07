<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || !$user->photographerProfile) {
            abort(403);
        }

        $services = Service::where('id_profile', $user->photographerProfile->id_profile)
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('services.index', compact('services'));
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || !$user->photographerProfile) {
            abort(403);
        }

        $categories = Category::orderBy('category_name')->get();

        return view('services.create', compact('categories'));
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || !$user->photographerProfile) {
            abort(403);
        }

        $service = Service::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'id_profile' => $user->photographerProfile->id_profile,
            'id_category' => $request->id_category,
        ]);

        return redirect()
            ->route('services.show', $service->id_service)
            ->with('success', 'Service created successfully.');
    }

    public function show(string $id_service): View
    {
        $service = Service::with(['photographerProfile.user', 'category'])->findOrFail($id_service);

        $this->authorize('view', $service);

        return view('services.show', compact('service'));
    }

    public function edit(string $id_service): View
    {
        $service = Service::findOrFail($id_service);

        $this->authorize('update', $service);

        $categories = Category::orderBy('category_name')->get();

        return view('services.edit', compact('service', 'categories'));
    }

    public function update(ServiceRequest $request, string $id_service): RedirectResponse
    {
        $service = Service::findOrFail($id_service);

        $this->authorize('update', $service);

        $service->update($request->validated());

        return redirect()
            ->route('services.show', $service->id_service)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(string $id_service): RedirectResponse
    {
        $service = Service::findOrFail($id_service);

        $this->authorize('delete', $service);

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
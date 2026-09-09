<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        $portfolios = Portfolio::where('id_profile', $user->photographerProfile->id_profile)
            ->latest()
            ->paginate(15);

        return view('portfolio.index', compact('portfolios'));
    }

    public function create(): View
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        return view('portfolio.create');
    }

    public function store(PortfolioRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'photographer' || ! $user->photographerProfile) {
            abort(403);
        }

        $imagePath = $request->file('image')->store('portfolio', 'public');

        $portfolio = Portfolio::create([
            'image' => $imagePath,
            'description' => $request->description,
            'id_profile' => $user->photographerProfile->id_profile,
        ]);

        return redirect()
            ->route('portfolio.show', $portfolio->id_photo)
            ->with('success', 'Portfolio image added successfully.');
    }

    public function show(string $id_photo): View
    {
        $portfolio = Portfolio::with('photographerProfile.user')->findOrFail($id_photo);

        $this->authorize('view', $portfolio);

        return view('portfolio.show', compact('portfolio'));
    }

    public function edit(string $id_photo): View
    {
        $portfolio = Portfolio::findOrFail($id_photo);

        $this->authorize('update', $portfolio);

        return view('portfolio.edit', compact('portfolio'));
    }

    public function update(PortfolioRequest $request, string $id_photo): RedirectResponse
    {
        $portfolio = Portfolio::findOrFail($id_photo);

        $this->authorize('update', $portfolio);

        $data = [
            'description' => $request->description,
        ];

        // A new image is optional on update. Only replace it when one is provided.
        if ($request->hasFile('image')) {
            $oldImage = $portfolio->image;

            $data['image'] = $request->file('image')->store('portfolio', 'public');

            // Delete the old physical image after the new one is stored.
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $portfolio->update($data);

        return redirect()
            ->route('portfolio.show', $portfolio->id_photo)
            ->with('success', 'Portfolio image updated successfully.');
    }

    public function destroy(string $id_photo): RedirectResponse
    {
        $portfolio = Portfolio::findOrFail($id_photo);

        $this->authorize('delete', $portfolio);

        // Normal soft delete: the physical image file is kept.
        $portfolio->delete();

        return redirect()
            ->route('portfolio.index')
            ->with('success', 'Portfolio image deleted successfully.');
    }
}

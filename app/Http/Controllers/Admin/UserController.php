<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin', auth()->user());

        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, string $id_user): RedirectResponse
    {
        $this->authorize('admin', auth()->user());

        $request->validate([
            'role' => ['required', 'in:client,photographer,admin'],
        ]);

        $user = User::findOrFail($id_user);

        if ($user->id_user === auth()->id()) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }
}

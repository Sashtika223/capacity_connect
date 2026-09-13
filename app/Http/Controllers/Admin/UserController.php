<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('dashboards.admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('dashboards.admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent changing own role or modifying another admin's role
        if ($user->id === auth()->id() || $user->role === 'admin') {
            return back()->with('error', 'You cannot modify this user\'s role.');
        }

        $request->validate(['role' => 'required|in:trainee,trainer']);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        // Prevent changing own status or modifying another admin's status
        if ($user->id === auth()->id() || $user->role === 'admin') {
            return back()->with('error', 'You cannot modify this user\'s status.');
        }

        $request->validate(['status' => 'required|in:active,inactive']);
        $user->update(['status' => $request->status]);

        $message = $request->status === 'active' ? 'User activated successfully.' : 'User deactivated successfully.';

        return back()->with('success', $message);
    }
}

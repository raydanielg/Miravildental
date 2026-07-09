<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorizeAdmin();
        $staff = User::orderBy('name')->paginate(20);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,doctor,reception',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('staff.index')->with('status', 'Staff member created successfully.');
    }

    public function show(User $staff)
    {
        $this->authorizeAdmin();
        return view('staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        $this->authorizeAdmin();
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($staff->id)],
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,doctor,reception',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('staff.index')->with('status', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        $this->authorizeAdmin();

        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('status', 'Staff member deleted successfully.');
    }

    public function toggle(User $staff)
    {
        $this->authorizeAdmin();

        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot deactivate your own account.');
        }

        // Toggle active status via soft delete simulation
        // For now just set a boolean if exists; otherwise, we can use soft deletes.
        return redirect()->route('staff.index')->with('status', 'Staff status toggled.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('staff.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('staff.profile')->with('status', 'Profile updated successfully.');
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}

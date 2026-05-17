<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.users.index', [
            'users' => User::with('branch')->latest()->get(),
            'pendingUsers' => User::with('branch')
                ->where('role', 'staff')
                ->where(function ($query) {
                    $query->where('staff_signup_status', 'pending')
                        ->orWhere(function ($inner) {
                            $inner->where('staff_signup_status', 'approved')
                                ->where('is_active', false)
                                ->whereNull('approved_at');
                        });
                })
                ->latest()
                ->get(),
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ]);

        User::create($data + [
            'is_active' => true,
            'staff_signup_status' => 'approved',
            'approved_at' => now(),
            'must_change_password' => $data['role'] === 'staff',
        ]);
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'Created staff account', 'details' => $data['email']]);

        return back()->with('success', 'User account created.');
    }

    public function update(Request $request, User $user)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $user->update($data);

        return back()->with('success', 'User account updated.');
    }

    public function approve(User $user)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        abort_unless($user->role === 'staff' && $user->staff_signup_status !== 'denied', 403);

        $temporaryPassword = Str::password(10);

        $user->update([
            'password' => $temporaryPassword,
            'role' => 'staff',
            'is_active' => true,
            'staff_signup_status' => 'approved',
            'must_change_password' => true,
            'approved_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Approved staff signup',
            'details' => $user->email,
        ]);

        return back()->with('success', 'Staff signup approved. Temporary password: ' . $temporaryPassword . ' Give this to the staff member. They must change it on first login.');
    }

    public function deny(User $user)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        abort_unless($user->role === 'staff' && $user->staff_signup_status === 'pending', 403);

        $user->update([
            'is_active' => false,
            'staff_signup_status' => 'denied',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Denied staff signup',
            'details' => $user->email,
        ]);

        return back()->with('success', 'Staff signup denied.');
    }

    public function resetPassword(User $user)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        abort_unless($user->role === 'staff', 403);

        $temporaryPassword = Str::password(10);

        $user->update([
            'password' => $temporaryPassword,
            'is_active' => true,
            'must_change_password' => true,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Reset staff password',
            'details' => $user->email,
        ]);

        return back()->with('success', 'Password reset. Temporary password: ' . $temporaryPassword . ' Give this to the staff member. They must change it on first login.');
    }

    public function destroy(User $user)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if ($user->id === Auth::id()) {
            return back()->withErrors([
                'user' => 'You cannot delete your own admin account while logged in.',
            ]);
        }

        $email = $user->email;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted user account',
            'details' => $email,
        ]);

        return back()->with('success', 'User account deleted. The email can now be used again.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showStaffSignup()
    {
        return view('auth.staff-signup', [
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['is_active'] = true;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->must_change_password) {
                return redirect()->route('password.change');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials or inactive account.',
            ])->onlyInput('email');
    }

    public function staffSignup(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'branch_id' => $data['branch_id'],
            'password' => bin2hex(random_bytes(12)),
            'role' => 'staff',
            'is_active' => false,
            'staff_signup_status' => 'pending',
        ]);

        ActivityLog::create([
            'action' => 'Staff signup request submitted',
            'details' => $data['email'],
        ]);

        return redirect()->route('login')->with('success', 'Staff signup submitted. Please wait for admin approval.');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'password' => ['required', 'confirmed', 'min:8'],
        ];

        if (! $request->user()->must_change_password) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $data = $request->validate($rules);

        $request->user()->update([
            'password' => $data['password'],
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
            ]);
    }
}

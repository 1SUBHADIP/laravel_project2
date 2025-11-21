<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        // Redirect to dashboard if already logged in
        if (session()->has('admin_id')) {
            return redirect()->route('dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        try {
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                return back()
                    ->withErrors(['email' => 'No account found with this email address.'])
                    ->withInput($request->only('email', 'remember'));
            }

            if (!$user->is_admin) {
                return back()
                    ->withErrors(['email' => 'This account does not have administrator privileges.'])
                    ->withInput($request->only('email', 'remember'));
            }

            // Check if password exists
            if (!$user->password) {
                return back()
                    ->withErrors(['email' => 'Account setup incomplete. Please contact system administrator.'])
                    ->withInput($request->only('email', 'remember'));
            }

            // Verify password
            if (!Hash::check($data['password'], $user->password)) {
                return back()
                    ->withErrors(['password' => 'The password you entered is incorrect.'])
                    ->withInput($request->only('email', 'remember'));
            }

            // Set session data
            $request->session()->regenerate();
            $request->session()->put([
                'admin_id' => $user->id,
                'admin_name' => $user->name,
                'admin_email' => $user->email,
                'login_time' => now(),
            ]);

            // Handle remember me functionality
            if ($data['remember'] ?? false) {
                $request->session()->put('admin_remember', true);
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()
                ->withErrors(['email' => 'A system error occurred. Please try again or contact support.'])
                ->withInput($request->only('email', 'remember'));
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $adminName = $request->session()->get('admin_name', 'Admin');

        $request->session()->forget(['admin_id', 'admin_name', 'admin_remember']);
        $request->session()->flush();

        return redirect()->route('admin.login')
            ->with('success', 'You have been successfully logged out. See you next time, ' . $adminName . '!');
    }
}


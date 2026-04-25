<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;
use Throwable;

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

    public function showForgotPassword(): View|RedirectResponse
    {
        if (session()->has('admin_id')) {
            return redirect()->route('dashboard');
        }

        return view('admin.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $defaultMailer = (string) config('mail.default');
        $smtpUsername = (string) config('mail.mailers.smtp.username');
        $smtpPassword = (string) config('mail.mailers.smtp.password');

        // Prevent confusing runtime failures and throttling when SMTP is incomplete.
        if ($defaultMailer === 'smtp' && ($smtpUsername === '' || $smtpPassword === '')) {
            return back()->withErrors([
                'email' => 'Mail is not configured. Set MAIL_USERNAME and MAIL_PASSWORD (Google App Password) in .env, then retry.',
            ])->withInput($request->only('email'));
        }

        $user = User::where('email', $data['email'])->first();

        // Keep response generic, but only send links for admin accounts.
        if (!$user || !$user->is_admin) {
            return back()->with('status', 'If your administrator account exists, a password reset link has been sent.');
        }

        $token = Password::createToken($user);
        $resetLink = url('/admin/reset-password/' . $token . '?email=' . urlencode($user->email));

        try {
            $status = Password::broker()->sendResetLink([
                'email' => $data['email'],
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to send admin password reset link', [
                'email' => $data['email'],
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'Unable to send password reset email right now. Please verify mail settings and try again.',
            ])->withInput($request->only('email'));
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => 'Password reset link prepared successfully.',
                'reset_link' => $resetLink,
            ]);
        }

        if ($status === Password::RESET_THROTTLED) {
            $waitSeconds = (int) config('auth.passwords.users.throttle', 60);

            return back()->withErrors([
                'email' => "Please wait {$waitSeconds} seconds before retrying password reset.",
            ])->withInput($request->only('email'));
        }

        return back()->withErrors([
            'email' => __($status),
        ])->with('reset_link', $resetLink);
    }

    public function showResetPassword(string $token, Request $request): View|RedirectResponse
    {
        if (session()->has('admin_id')) {
            return redirect()->route('dashboard');
        }

        return view('admin.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !$user->is_admin) {
            return back()->withErrors([
                'email' => 'This email is not registered as an administrator account.',
            ])->withInput($request->only('email'));
        }

        $status = Password::broker()->reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $data['token'],
            ],
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('success', 'Password has been reset successfully. You can now sign in.');
        }

        return back()->withErrors([
            'email' => __($status),
        ])->withInput($request->only('email'));
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

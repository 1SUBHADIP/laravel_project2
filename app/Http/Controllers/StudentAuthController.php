<?php

namespace App\Http\Controllers;

use App\Helpers\StudentSessionManager;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Notifications\StudentOtpNotification;

class StudentAuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if (StudentSessionManager::restoreRememberedStudent($request)) {
            return redirect()->route('student.dashboard');
        }

        return view('student.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
            'remember_me' => ['nullable', 'boolean'],
        ]);

        $member = Member::with('department')
            ->where('student_id', $data['student_id'])
            ->first();

        if (!$member) {
            return back()->withErrors([
                'student_id' => 'No student account was found for that ID card number.',
            ])->withInput($request->only('student_id'));
        }

        if (!$member->phone) {
            return back()->withErrors([
                'password' => 'Your account is missing a mobile number. Please contact the library staff.',
            ])->withInput($request->only('student_id'));
        }

        // If member has a password set, verify using Hash; otherwise fallback to mobile-based check
        if ($member->password) {
            if (!\Illuminate\Support\Facades\Hash::check($data['password'], $member->password)) {
                return back()->withErrors([
                    'password' => 'Invalid password. If you forgot it, use Forgot Password.',
                ])->withInput($request->only('student_id'));
            }
        } else {
            $inputPassword = preg_replace('/\\D+/', '', trim((string) $data['password']));
            $storedMobile = preg_replace('/\\D+/', '', (string) $member->phone);

            if ($storedMobile === '' || !hash_equals($storedMobile, (string) $inputPassword)) {
                return back()->withErrors([
                    'password' => 'The password must match the mobile number on your ID card.',
                ])->withInput($request->only('student_id'));
            }
        }

        $request->session()->regenerate();

        $request->session()->put([
            'student_id' => $member->id,
            'student_member_no' => $member->student_id,
            'student_name' => $member->name,
            'student_department_id' => $member->department_id,
            'student_department_name' => $member->department?->name,
            'student_login_time' => now(),
        ]);

        if ($request->boolean('remember_me')) {
            StudentSessionManager::rememberStudent($member);
        } else {
            StudentSessionManager::forgetRememberedStudent();
        }

        return redirect()->intended(route('student.dashboard'))
            ->with('success', 'Welcome, ' . $member->name . '!');
    }

    public function dashboard(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $member = Member::with('department')->findOrFail($studentId);
        $departmentBooks = collect();

        if ($member->department_id) {
            $departmentBooks = Book::with(['category', 'department'])
                ->where('department_id', $member->department_id)
                ->orderBy('title')
                ->get();
        }

        $currentLoans = $member->loans()->with('book')->whereNull('returned_date')->orderBy('due_date')->get();
        $loanHistory = $member->loans()->with('book')->whereNotNull('returned_date')->orderByDesc('returned_date')->limit(10)->get();

        // Build admin-like counts for student dashboard
        $counts = [
            'books' => DB::table('books')->count(),
            'members' => DB::table('members')->count(),
            'active_loans' => DB::table('loans')->whereNull('returned_date')->count(),
            'overdue_loans' => DB::table('loans')->whereNull('returned_date')
                ->whereDate('due_date', '<', now()->toDateString())->count(),
            'kindle_books' => DB::table('books')->where('has_kindle_version', true)->count(),
        ];

        return view('student.dashboard', [
            'member' => $member,
            'counts' => $counts,
            'libraryBooksCount' => $counts['books'],
            'departmentBooksCount' => $departmentBooks->count(),
            'kindleBooksCount' => $counts['kindle_books'],
            'departmentBooks' => $departmentBooks,
            'currentLoans' => $currentLoans,
            'loanHistory' => $loanHistory,
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $studentName = $request->session()->get('student_name', 'Student');

        $request->session()->forget([
            'student_id',
            'student_member_no',
            'student_name',
            'student_department_id',
            'student_department_name',
            'student_login_time',
        ]);
        $request->session()->regenerateToken();
        StudentSessionManager::forgetRememberedStudent();

        return redirect()->route('student.login')
            ->with('success', 'You have been logged out, ' . $studentName . '.');
    }

    public function requestRenewal(Request $request, \App\Models\Loan $loan)
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('student.login');
        }

        // Ensure the loan belongs to the logged in student
        if ($loan->member_id !== $studentId) {
            return back()->withErrors(['loan' => 'You are not authorized to request a renewal for this loan.']);
        }

        if ($loan->returned_date !== null) {
            return back()->withErrors(['loan' => 'This loan is already returned.']);
        }

        $notificationId = 'renew_request_loan_' . $loan->id . '_member_' . $studentId;

        \Illuminate\Support\Facades\DB::table('notifications')->insertOrIgnore([
            'notification_id' => $notificationId,
            'type' => 'info',
            'title' => 'Renewal request',
            'message' => 'Renewal requested for "' . $loan->book->title . '" by ' . session('student_name'),
            'icon' => 'fas fa-sync-alt',
            'color' => 'text-blue-400',
            'action_url' => route('loans.index') . '?loan=' . $loan->id,
            'is_read' => false,
            'is_dismissed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Renewal request sent. The library staff will review it shortly.');
    }

    // --- Password reset (OTP) flow for students ---
    public function showForgotPassword(): View
    {
        return view('student.password.request');
    }

    public function sendResetOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'string'],
        ]);

        $member = \App\Models\Member::where('student_id', '=', $data['student_id'], 'and')->first();
        if (!$member) {
            return back()->withErrors(['student_id' => 'No student found with that ID.']);
        }

        if (empty($member->email)) {
            return back()->withErrors(['student_id' => 'No email address on file for this student.']);
        }

        // generate 6-digit otp
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        $resetId = DB::table('student_password_resets')->insertGetId([
            'member_id' => $member->id,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // send OTP via email
        $member->notify(new StudentOtpNotification($otp));

        // store reset id in session for verification
        session(['student_password_reset_id' => $resetId]);

        return redirect()->route('student.password.verify')->with('success', 'OTP sent to the registered email address.');
    }

    public function showVerifyOtp(): View
    {
        return view('student.password.verify');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $resetId = session('student_password_reset_id');
        if (!$resetId) {
            return redirect()->route('student.password.request')->withErrors(['student_id' => 'Start password reset first.']);
        }

        $record = DB::table('student_password_resets')->where('id', $resetId)->first();
        if (!$record) {
            return redirect()->route('student.password.request')->withErrors(['student_id' => 'Reset request not found.']);
        }

        if ($record->used) {
            return redirect()->route('student.password.request')->withErrors(['otp' => 'This code has already been used.']);
        }

        if (now()->greaterThan($record->expires_at)) {
            return redirect()->route('student.password.request')->withErrors(['otp' => 'The OTP has expired. Please request a new one.']);
        }

        if (!hash_equals((string) $record->otp, (string) $data['otp'])) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        // mark verified in session
        session(['student_reset_verified' => $record->member_id, 'student_password_reset_id' => $resetId]);

        return redirect()->route('student.password.reset');
    }

    public function showResetForm(): View|RedirectResponse
    {
        if (!session('student_reset_verified')) {
            return redirect()->route('student.password.request');
        }

        return view('student.password.reset');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $memberId = session('student_reset_verified');
        $resetId = session('student_password_reset_id');
        if (!$memberId || !$resetId) {
            return redirect()->route('student.password.request')->withErrors(['student_id' => 'Reset session missing.']);
        }

        $member = \App\Models\Member::find($memberId, ['*']);
        if (!$member) {
            return redirect()->route('student.password.request')->withErrors(['student_id' => 'Member not found.']);
        }

        $member->password = Hash::make($data['password']);
        $member->save();

        DB::table('student_password_resets')->where('id', $resetId)->update(['used' => true, 'updated_at' => now()]);

        // clear session keys
        session()->forget(['student_reset_verified', 'student_password_reset_id']);

        return redirect()->route('student.login')->with('success', 'Password updated. You can now login with your new password.');
    }

    // Change password when logged in
    public function showChangePassword(): View
    {
        return view('student.password.change');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $member = \App\Models\Member::findOrFail($studentId);

        // If member has a password set, verify
        if ($member->password && !Hash::check($data['old_password'], $member->password)) {
            return back()->withErrors(['old_password' => 'Old password does not match.']);
        }

        $member->password = Hash::make($data['password']);
        $member->save();

        return back()->with('success', 'Your password has been updated.');
    }
}

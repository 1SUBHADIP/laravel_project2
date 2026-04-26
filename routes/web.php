<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Support\Facades\Schema;




// Redirect root to admin login if not authenticated, otherwise to dashboard
Route::get('/', function () {
    if (session()->has('admin_id')) {
        return redirect()->route('dashboard');
    }

    $stats = [
        'totalBooks' => 0,
        'totalMembers' => 0,
        'activeLoans' => 0,
        'overdueLoans' => 0,
    ];

    if (Schema::hasTable('books')) {
        $stats['totalBooks'] = Book::count();
    }

    if (Schema::hasTable('members')) {
        $stats['totalMembers'] = Member::count();
    }

    if (Schema::hasTable('loans')) {
        $stats['activeLoans'] = Loan::whereNull('returned_date')->count();
        $stats['overdueLoans'] = Loan::whereNull('returned_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();
    }

    return view()->file(resource_path('views/welcome.blade.php'), $stats);
});

// Admin authentication routes (accessible when not logged in)
Route::group([], function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    // Admin password reset routes
    Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetLink'])->name('admin.password.email');
    Route::get('/admin/reset-password/{token}', [AdminAuthController::class, 'showResetPassword'])
        ->name('admin.password.reset');
    Route::get('/admin/reset-password/{token}', [AdminAuthController::class, 'showResetPassword'])
        ->name('password.reset');
    Route::post('/admin/reset-password', [AdminAuthController::class, 'resetPassword'])
        ->name('admin.password.update');
});

// Admin logout (accessible when logged in)
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('admin');

// Protected application routes with admin middleware
Route::middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Resource routes
    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'destroy']);

    // Loan routes
    Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('loans', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::delete('loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');

    // Search route
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/view-all', [\App\Http\Controllers\NotificationController::class, 'viewAll'])->name('notifications.view-all');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');

    // Additional utility routes
    Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/overdue', [\App\Http\Controllers\ReportsController::class, 'overdueItems'])->name('reports.overdue');
    Route::get('/reports/analytics', [\App\Http\Controllers\ReportsController::class, 'analytics'])->name('reports.analytics');
    Route::get('/reports/export', [\App\Http\Controllers\ReportsController::class, 'export'])->name('reports.export');

    // Reminder routes
    Route::post('/reminders/send/{loan}', [\App\Http\Controllers\ReportsController::class, 'sendReminder'])->name('reminders.send');
    Route::post('/reminders/send-all', [\App\Http\Controllers\ReportsController::class, 'sendAllReminders'])->name('reminders.send-all');

    // Test route for debugging reminders
    Route::get('/test-reminder/{loan}', function (\App\Models\Loan $loan) {
        try {
            // Test basic mail sending
            \Illuminate\Support\Facades\Log::info('Testing reminder for loan ID: ' . $loan->id);

            // Get member email
            $member = $loan->member;
            if (!$member->email) {
                return response()->json(['error' => 'Member has no email address']);
            }

            // Try to send the email
            \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\OverdueReminder($loan));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully to ' . $member->email,
                'loan_id' => $loan->id,
                'member' => $member->name
            ]);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email sending error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // Basic mail test route
    Route::get('/test-basic-mail', function () {
        try {
            \Illuminate\Support\Facades\Log::info('Testing basic mail functionality');

            \Illuminate\Support\Facades\Mail::raw('This is a basic test email from Laravel', function ($message) {
                $message->to('test@example.com')
                    ->subject('Test Email from CCLMS Library');
            });

            \Illuminate\Support\Facades\Log::info('Basic mail sent successfully');

            return response()->json([
                'success' => true,
                'message' => 'Basic test email sent successfully!'
            ]);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Basic mail error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/system', [\App\Http\Controllers\SettingsController::class, 'updateSystemSettings'])->name('settings.system');
    Route::post('/settings/cache', [\App\Http\Controllers\SettingsController::class, 'clearCache'])->name('settings.cache');
    Route::post('/settings/backup', [\App\Http\Controllers\SettingsController::class, 'generateBackup'])->name('settings.backup');
    Route::post('/settings/database', [\App\Http\Controllers\SettingsController::class, 'testDatabase'])->name('settings.database');
    Route::get('/settings/logs', [\App\Http\Controllers\SettingsController::class, 'getSystemLogs'])->name('settings.logs');
});

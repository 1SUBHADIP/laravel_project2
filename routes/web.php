<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\DB;
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
        $stats['totalBooks'] = DB::table('books')->count('id');
    }

    if (Schema::hasTable('members')) {
        $stats['totalMembers'] = DB::table('members')->count('id');
    }

    if (Schema::hasTable('loans')) {
        $stats['activeLoans'] = DB::table('loans')->whereNull('returned_date')->count('id');
        $stats['overdueLoans'] = DB::table('loans')->whereNull('returned_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count('id');
    }

    return view()->file(resource_path('views/welcome.blade.php'), $stats);
});

Route::view('/about', 'pages.info', [
    'pageTitle' => 'About CCLMS',
    'pageHeading' => 'About CCLMS',
    'pageDescription' => 'CCLMS is a focused library management system built for colleges and institutions that need faster circulation, clearer tracking, and better reporting.',
    'pageKeywords' => 'about CCLMS, library management software, college library portal, Laravel book circulation system, library analytics',
    'sections' => [
        ['title' => 'Purpose-built for libraries', 'text' => 'The platform is designed around the real workflow of issuing books, returning them, managing members, and following overdue activity without unnecessary complexity.'],
        ['title' => 'Operational visibility', 'text' => 'Dashboards and reports help staff identify active loans, popular books, member activity, and system trends quickly.'],
        ['title' => 'Simple, fast admin workflow', 'text' => 'The interface focuses on quick search, low-friction forms, and concise actions so administrators can spend less time navigating and more time managing.'],
    ],
])->name('public.about');

Route::view('/library-rules', 'pages.info', [
    'pageTitle' => 'Library Rules',
    'pageHeading' => 'Library Rules',
    'pageDescription' => 'Review the lending rules, return expectations, and usage guidelines that keep CCLMS library operations consistent and fair.',
    'pageKeywords' => 'library rules, book return policy, lending policy, overdue rules, college library regulations',
    'sections' => [
        ['title' => 'Borrowing period', 'text' => 'Loan durations should follow the institution policy for the specific member type and item category.'],
        ['title' => 'Late returns', 'text' => 'Overdue items should be returned promptly so reminders, penalties, and circulation records remain accurate.'],
        ['title' => 'Care of materials', 'text' => 'Members are responsible for keeping borrowed books clean, dry, and undamaged until they are checked in.'],
    ],
])->name('public.rules');

Route::view('/contact', 'pages.info', [
    'pageTitle' => 'Contact Support',
    'pageHeading' => 'Contact Support',
    'pageDescription' => 'Reach the CCLMS support team for setup questions, feature guidance, or library workflow assistance.',
    'pageKeywords' => 'contact CCLMS, library support, admin help, Laravel project support, library software assistance',
    'sections' => [
        ['title' => 'Email support', 'text' => 'Use your institution support mailbox for configuration requests, data corrections, and escalation items.'],
        ['title' => 'Implementation help', 'text' => 'If you are rolling CCLMS out for a department or college, prepare your staff roles, categories, and member groups first.'],
        ['title' => 'Response focus', 'text' => 'The fastest support requests include a short description, the affected screen, and any screenshot or error message.'],
    ],
])->name('public.contact');

Route::view('/privacy', 'pages.info', [
    'pageTitle' => 'Privacy Policy',
    'pageHeading' => 'Privacy Policy',
    'pageDescription' => 'Understand how CCLMS handles institutional data, session access, and administrative records in the library portal.',
    'pageKeywords' => 'privacy policy, library system data protection, admin records, college portal privacy, Laravel privacy policy',
    'sections' => [
        ['title' => 'Access control', 'text' => 'Administrative actions should remain limited to authorized staff accounts and logged sessions.'],
        ['title' => 'Data retention', 'text' => 'Loan history and system records should be retained according to institutional policy and operational needs.'],
        ['title' => 'Security posture', 'text' => 'Use strong passwords, limit admin access, and keep environment settings configured securely.'],
    ],
])->name('public.privacy');

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

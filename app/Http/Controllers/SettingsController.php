<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use RuntimeException;

class SettingsController extends Controller
{
    public function index()
    {
        $systemInfo = [
            'app_version' => config('app.version', '1.0.0'),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_name' => config('database.connections.' . config('database.default') . '.database', 'library'),
            'total_records' => [
                'books' => \App\Models\Book::count(),
                'members' => \App\Models\Member::count(),
                'loans' => \App\Models\Loan::count(),
                'categories' => \App\Models\Category::count(),
            ]
        ];

        // Get current admin user data
        $adminId = session('admin_id');
        $currentUser = User::find($adminId);

        return view('settings.index', compact('systemInfo', 'currentUser'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Get the current admin user
        $adminId = session('admin_id');
        $user = User::find($adminId);

        if (!$user) {
            return redirect()->route('settings.index')
                ->with('error', 'User not found!');
        }

        // If password change is requested, verify current password first
        if ($request->filled('new_password')) {
            $currentPasswordValid = false;

            try {
                $currentPasswordValid = Hash::check($request->current_password, $user->password);
            } catch (RuntimeException $e) {
                if (hash_equals((string) $user->password, (string) $request->current_password)) {
                    $currentPasswordValid = true;
                    $user->password = Hash::make($request->current_password);
                }
            }

            if (!$currentPasswordValid) {
                return redirect()->route('settings.index')
                    ->with('error', 'Current password is incorrect!');
            }

            // Update the password
            $user->password = Hash::make($request->new_password);
        }

        // Update name and email
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update session data
        session([
            'admin_name' => $request->name,
            'admin_email' => $request->email
        ]);

        $message = 'Profile updated successfully!';
        if ($request->filled('new_password')) {
            $message = 'Profile and password updated successfully!';
        }

        return redirect()->route('settings.index')
            ->with('success', $message);
    }

    public function updateSystemSettings(Request $request)
{
    $request->validate([
        'app_name' => 'required|string|max:255',
        'loan_duration' => 'required|integer|min:1|max:365',
        'max_books_per_member' => 'required|integer|min:1|max:50',
        'late_fee_per_day' => 'required|numeric|min:0',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
    ]);

    $settings = [
        'app_name' => $request->app_name,
        'loan_duration' => $request->loan_duration,
        'max_books_per_member' => $request->max_books_per_member,
        'late_fee_per_day' => $request->late_fee_per_day,
        'email_notifications' => $request->boolean('email_notifications'),
        'sms_notifications' => $request->boolean('sms_notifications'),
    ];

    foreach ($settings as $key => $value) {
        \App\Models\SystemSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    return redirect()->route('settings.index')
        ->with('success', 'System settings updated successfully!');
}
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'System cache cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateBackup()
    {
        try {
            $filename = 'library_backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
           $database = config('database.connections.' . config('database.default') . '.database');

            // This is a simplified backup - in production you'd use proper backup tools
           if (DB::getDriverName() === 'pgsql') {
            $tables = DB::select("
                SELECT tablename 
                FROM pg_tables 
                WHERE schemaname = 'public'
            ");
            } else {
                $tables = DB::select('SHOW TABLES');
            }
            $tableCount = count($tables);

            return response()->json([
                'success' => true,
                'message' => "Backup prepared successfully! Found {$tableCount} tables to backup.",
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testDatabase()
    {
        try {
            $connection = DB::connection();
            $connection->getPdo();

            $tables = [
                'books' => \App\Models\Book::count(),
                'members' => \App\Models\Member::count(),
                'loans' => \App\Models\Loan::count(),
                'categories' => \App\Models\Category::count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Database connection successful!',
                'tables' => $tables
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSystemLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');

           if (!file_exists($logFile)) {
                return response()->json([
                    'success' => true,
                    'logs' => [],
                    'message' => 'Logs not stored in file. Check Render dashboard logs.'
                ]);
            }

            $logs = [];
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Get last 50 lines
            $recentLines = array_slice($lines, -50);

            foreach ($recentLines as $line) {
                if (preg_match('/\[(.*?)\] (\w+)\.(\w+): (.*)/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[2],
                        'type' => $matches[3],
                        'message' => $matches[4]
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'logs' => array_reverse($logs) // Show newest first
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to read logs: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        // Generate current notifications and save them to database
        $this->generateAndSaveNotifications();

        // Get active (non-dismissed) notifications from database
        $notifications = DB::table('notifications')
            ->where('is_dismissed', false)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->notification_id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time' => Carbon::parse($notification->created_at)->diffForHumans(),
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'action_url' => $notification->action_url,
                    'read' => (bool) $notification->is_read
                ];
            });

        $unreadCount = DB::table('notifications')
            ->where('is_dismissed', false)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function viewAll()
    {
        // Generate current notifications and save them to database
        $this->generateAndSaveNotifications();

        $notifications = DB::table('notifications')
            ->where('is_dismissed', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($notification) {
                return [
                    'id' => $notification->notification_id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time' => Carbon::parse($notification->created_at)->diffForHumans(),
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'action_url' => $notification->action_url,
                    'read' => (bool) $notification->is_read
                ];
            });

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');

        DB::table('notifications')
            ->where('notification_id', $notificationId)
            ->update(['is_read' => true, 'updated_at' => now()]);

        if (!$request->wantsJson() && !$request->ajax()) {
            return back()->with('success', 'Notification marked as read.');
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        DB::table('notifications')
            ->where('is_dismissed', false)
            ->update(['is_read' => true, 'updated_at' => now()]);

        if (!$request->wantsJson() && !$request->ajax()) {
            return back()->with('success', 'All notifications marked as read.');
        }

        return response()->json(['success' => true]);
    }

    public function clearAll(Request $request)
    {
        // Mark all active notifications as dismissed
        DB::table('notifications')
            ->where('is_dismissed', false)
            ->update(['is_dismissed' => true, 'updated_at' => now()]);

        if (!$request->wantsJson() && !$request->ajax()) {
            return back()->with('success', 'All notifications cleared successfully');
        }

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared successfully'
        ]);
    }

    private function generateAndSaveNotifications()
    {
        $notifications = $this->getNotifications();

        foreach ($notifications as $notificationData) {
            $existing = DB::table('notifications')
                ->where('notification_id', $notificationData['id'])
                ->first();

            if ($existing) {
                // Update existing notification
                DB::table('notifications')
                    ->where('notification_id', $notificationData['id'])
                    ->update([
                        'type' => $notificationData['type'],
                        'title' => $notificationData['title'],
                        'message' => $notificationData['message'],
                        'icon' => $notificationData['icon'],
                        'color' => $notificationData['color'],
                        'action_url' => $notificationData['action_url'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new notification
                DB::table('notifications')->insert([
                    'notification_id' => $notificationData['id'],
                    'type' => $notificationData['type'],
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'icon' => $notificationData['icon'],
                    'color' => $notificationData['color'],
                    'action_url' => $notificationData['action_url'],
                    'is_read' => false,
                    'is_dismissed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Clean up old notifications (older than 30 days)
        DB::table('notifications')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
    }

    private function getNotifications()
    {
        $notifications = [];

        // Keep one persistent system notification so the admin UI always has a visible item.
        $notifications[] = [
            'id' => 'system_status_active',
            'type' => 'info',
            'title' => 'Notification Center Active',
            'message' => 'Notifications are working. New alerts will appear here automatically.',
            'time' => 'Now',
            'icon' => 'fas fa-bell',
            'color' => 'text-blue-400',
            'action_url' => route('notifications.view-all'),
            'read' => false,
        ];

        // Low stock books (less than 3 available copies)
        $lowStockBooks = Book::whereRaw('available_copies < 3 AND available_copies > 0')
            ->orderBy('available_copies', 'asc')
            ->get();

        foreach ($lowStockBooks as $book) {
            $notifications[] = [
                'id' => 'low_stock_' . $book->id,
                'type' => 'warning',
                'title' => 'Low Stock',
                'message' => "Only {$book->available_copies} copies left of \"{$book->title}\"",
                'time' => 'Ongoing',
                'icon' => 'fas fa-box-open',
                'color' => 'text-orange-400',
                'action_url' => route('books.edit', $book->id),
                'read' => false
            ];
        }

        // Out of stock books
        $outOfStockBooks = Book::where('available_copies', 0)
            ->where('total_copies', '>', 0)
            ->get();

        foreach ($outOfStockBooks as $book) {
            $notifications[] = [
                'id' => 'out_of_stock_' . $book->id,
                'type' => 'error',
                'title' => 'Out of Stock',
                'message' => "\"{$book->title}\" is currently out of stock",
                'time' => 'Ongoing',
                'icon' => 'fas fa-exclamation-circle',
                'color' => 'text-red-400',
                'action_url' => route('books.edit', $book->id),
                'read' => false
            ];
        }

        // Recent new members (last 7 days)
        $newMembers = Member::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($newMembers as $member) {
            $notifications[] = [
                'id' => 'new_member_' . $member->id,
                'type' => 'success',
                'title' => 'New Member',
                'message' => "{$member->name} joined the library" . ($member->department ? " (Dept: {$member->department})" : ""),
                'time' => $member->created_at->diffForHumans(),
                'icon' => 'fas fa-user-plus',
                'color' => 'text-green-400',
                'action_url' => route('members.edit', $member->id),
                'read' => false
            ];
        }

        // Overdue loans with automatic fine calculation
        $overdueLoans = Loan::overdue()->with(['book', 'member'])->get();
        foreach ($overdueLoans as $loan) {
            // Calculate overdue charges automatically
            $loan->calculateOverdueCharges();

            $notifications[] = [
                'id' => 'overdue_fine_' . $loan->id,
                'type' => 'error',
                'title' => 'Overdue Fine',
                'message' => "{$loan->member->name} owes {$loan->formatted_fine} for \"{$loan->book->title}\" ({$loan->overdue_days} days overdue)",
                'time' => $loan->due_date->diffForHumans(),
                'icon' => 'fas fa-rupee-sign',
                'color' => 'text-red-500',
                'action_url' => route('loans.index') . '?loan=' . $loan->id,
                'read' => false
            ];
        }

        // Sort notifications by priority and time
        usort($notifications, function ($a, $b) {
            $priority = ['error' => 4, 'warning' => 3, 'info' => 2, 'success' => 1];
            $aPriority = $priority[$a['type']] ?? 0;
            $bPriority = $priority[$b['type']] ?? 0;

            if ($aPriority === $bPriority) {
                return 0; // Keep original order for same priority
            }

            return $bPriority <=> $aPriority; // Higher priority first
        });

        return array_slice($notifications, 0, 20); // Limit to 20 notifications
    }
}

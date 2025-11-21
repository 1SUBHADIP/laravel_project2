<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueReminder;
use App\Services\SmsService;

class ReportsController extends Controller
{
    public function index()
    {
        $data = [
            'totalBooks' => Book::count(),
            'totalMembers' => Member::count(),
            'activeLoans' => Loan::whereNull('returned_date')->count(),
            'overdueItems' => Loan::whereNull('returned_date')->where('due_date', '<', now())->count(),
            'newBooksThisMonth' => Book::whereDate('created_at', '>=', now()->subDays(30))->count(),
            'newMembersThisMonth' => Member::whereDate('created_at', '>=', now()->subDays(30))->count(),
            'loansToday' => Loan::whereDate('loan_date', today())->count(),
        ];

        return view('reports.index', $data);
    }

    public function overdueItems(Request $request)
    {
        $query = Loan::whereNull('returned_date')
            ->where('due_date', '<', now())
            ->with(['book', 'member']);

        // Apply filters if provided
        if ($request->filled('category_id')) {
            $query->whereHas('book', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('days_overdue')) {
            $daysAgo = now()->subDays($request->days_overdue);
            $query->where('due_date', '<', $daysAgo);
        }

        $overdueLoans = $query->orderBy('due_date', 'asc')->paginate(15);

        // Calculate days overdue for each loan
        $overdueLoans->getCollection()->transform(function ($loan) {
            $loan->days_overdue = now()->diffInDays($loan->due_date);
            return $loan;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $overdueLoans->items(),
                'pagination' => [
                    'current_page' => $overdueLoans->currentPage(),
                    'total_pages' => $overdueLoans->lastPage(),
                    'total_items' => $overdueLoans->total()
                ]
            ]);
        }

        return view('reports.overdue', compact('overdueLoans'));
    }

    public function analytics(Request $request)
    {
        // If it's an AJAX request, return JSON data
        if ($request->expectsJson() || $request->ajax()) {
            return $this->getAnalyticsData($request);
        }

        // Otherwise, return the analytics view
        return view('reports.analytics');
    }

    private function getAnalyticsData(Request $request)
    {
        $period = $request->get('period', 'month');

        // Calculate date range based on period
        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                break;
            case 'quarter':
                $startDate = now()->subDays(90);
                break;
            case 'year':
                $startDate = now()->subYear();
                break;
            default:
                $startDate = now()->subMonth();
        }

        // Loan trends
        $loanTrends = [];
        $days = $period === 'week' ? 7 : ($period === 'year' ? 12 : 30);

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $period === 'year'
                ? now()->subMonths($i)->startOfMonth()
                : now()->subDays($i);

            $loanTrends[] = [
                'date' => $period === 'year'
                    ? $date->format('M Y')
                    : $date->format('M d'),
                'loans' => Loan::whereDate('loan_date', $date)->count(),
                'returns' => Loan::whereDate('returned_date', $date)->count()
            ];
        }

        // Popular books
        $popularBooks = Book::withCount(['loans' => function ($query) use ($startDate) {
            $query->where('loan_date', '>=', $startDate);
        }])
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->get();

        // Category distribution
        $categoryStats = Category::withCount(['books', 'loans' => function ($query) use ($startDate) {
            $query->where('loan_date', '>=', $startDate);
        }])
            ->orderBy('loans_count', 'desc')
            ->get();

        // Member activity
        $activeMembers = Member::withCount(['loans' => function ($query) use ($startDate) {
            $query->where('loan_date', '>=', $startDate);
        }])
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->get();

        // Monthly statistics
        $monthlyStats = [
            'total_loans' => Loan::where('loan_date', '>=', $startDate)->count(),
            'total_returns' => Loan::where('returned_date', '>=', $startDate)->count(),
            'average_loan_duration' => $this->getAverageLoanDuration($startDate),
            'most_active_day' => $this->getMostActiveDay($startDate)
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'loan_trends' => $loanTrends,
                'popular_books' => $popularBooks,
                'category_stats' => $categoryStats,
                'active_members' => $activeMembers,
                'monthly_stats' => $monthlyStats
            ]
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'loans');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'overdue':
                return $this->exportOverdueItems($format);
            case 'popular_books':
                return $this->exportPopularBooks($format);
            case 'member_activity':
                return $this->exportMemberActivity($format);
            default:
                return $this->exportLoanHistory($format);
        }
    }

    private function getAverageLoanDuration($startDate)
    {
        $averageDuration = Loan::whereNotNull('returned_date')
            ->where('loan_date', '>=', $startDate)
            ->selectRaw('AVG(DATEDIFF(returned_date, loan_date)) as avg_duration')
            ->value('avg_duration');

        return round($averageDuration ?? 0, 1);
    }

    private function getMostActiveDay($startDate)
    {
        $mostActiveDay = Loan::where('loan_date', '>=', $startDate)
            ->selectRaw('DAYNAME(loan_date) as day_name, COUNT(*) as loan_count')
            ->groupBy('day_name')
            ->orderBy('loan_count', 'desc')
            ->first();

        return $mostActiveDay->day_name ?? 'N/A';
    }

    private function exportOverdueItems($format)
    {
        $overdueLoans = Loan::whereNull('returned_date')
            ->where('due_date', '<', now())
            ->with(['book', 'member'])
            ->orderBy('due_date', 'asc')
            ->get();

        $data = $overdueLoans->map(function ($loan) {
            return [
                'Member Name' => $loan->member->name,
                'Member Email' => $loan->member->email,
                'Book Title' => $loan->book->title,
                'Book Author' => $loan->book->author,
                'Loan Date' => $loan->loan_date->format('Y-m-d'),
                'Due Date' => $loan->due_date->format('Y-m-d'),
                'Days Overdue' => now()->diffInDays($loan->due_date)
            ];
        });

        return $this->downloadData($data, 'overdue_items', $format);
    }

    private function exportPopularBooks($format)
    {
        $books = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->get();

        $data = $books->map(function ($book) {
            return [
                'Title' => $book->title,
                'Author' => $book->author,
                'ISBN' => $book->isbn,
                'Total Copies' => $book->total_copies,
                'Available Copies' => $book->available_copies,
                'Total Loans' => $book->loans_count
            ];
        });

        return $this->downloadData($data, 'popular_books', $format);
    }

    private function exportMemberActivity($format)
    {
        $members = Member::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->get();

        $data = $members->map(function ($member) {
            return [
                'Name' => $member->name,
                'Email' => $member->email,
                'Phone' => $member->phone ?? 'N/A',
                'Member Since' => $member->created_at->format('Y-m-d'),
                'Total Loans' => $member->loans_count
            ];
        });

        return $this->downloadData($data, 'member_activity', $format);
    }

    private function exportLoanHistory($format)
    {
        $loans = Loan::with(['book', 'member'])
            ->orderBy('loan_date', 'desc')
            ->get();

        $data = $loans->map(function ($loan) {
            return [
                'Member Name' => $loan->member->name,
                'Book Title' => $loan->book->title,
                'Loan Date' => $loan->loan_date->format('Y-m-d'),
                'Due Date' => $loan->due_date->format('Y-m-d'),
                'Return Date' => $loan->returned_date ? $loan->returned_date->format('Y-m-d') : 'Not Returned',
                'Status' => $loan->returned_date ? 'Returned' : ($loan->due_date < now() ? 'Overdue' : 'Active')
            ];
        });

        return $this->downloadData($data, 'loan_history', $format);
    }

    private function downloadData($data, $filename, $format)
    {
        $filename = $filename . '_' . now()->format('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ];

            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');

                if (!empty($data)) {
                    fputcsv($file, array_keys($data->first()));
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // For now, return JSON for other formats
        return response()->json([
            'success' => true,
            'message' => 'Export functionality will be implemented for ' . strtoupper($format) . ' format',
            'data' => $data
        ]);
    }

    /**
     * Send reminder for a specific overdue loan
     */
    public function sendReminder(Loan $loan)
    {
        try {
            $member = $loan->member;

            // Send email reminder
            $emailSent = false;
            if (!empty($member->email)) {
                Mail::to($member->email)->send(new OverdueReminder($loan));
                $emailSent = true;
            }

            // Send SMS reminder
            $smsSent = false;
            if (!empty($member->phone)) {
                $smsService = new SmsService();
                $smsSent = $smsService->sendOverdueReminder($loan);
            }

            // Update loan with reminder sent timestamp
            $loan->update([
                'last_reminder_sent' => now(),
                'reminder_count' => ($loan->reminder_count ?? 0) + 1
            ]);

            $message = 'Reminder sent successfully!';
            if ($emailSent && $smsSent) {
                $message = 'Email and SMS reminders sent successfully!';
            } elseif ($emailSent) {
                $message = 'Email reminder sent successfully!';
            } elseif ($smsSent) {
                $message = 'SMS reminder sent successfully!';
            } else {
                $message = 'No valid contact information found for member.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'email_sent' => $emailSent,
                'sms_sent' => $smsSent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send reminders to all members with overdue items
     */
    public function sendAllReminders()
    {
        try {
            $overdueLoans = Loan::whereNull('returned_date')
                ->where('due_date', '<', now())
                ->with(['book', 'member'])
                ->get();

            if ($overdueLoans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No overdue items found.'
                ]);
            }

            $emailCount = 0;
            $smsCount = 0;
            $errors = [];

            foreach ($overdueLoans as $loan) {
                try {
                    $member = $loan->member;

                    // Send email reminder
                    if (!empty($member->email)) {
                        Mail::to($member->email)->send(new OverdueReminder($loan));
                        $emailCount++;
                    }

                    // Send SMS reminder
                    if (!empty($member->phone)) {
                        $smsService = new SmsService();
                        if ($smsService->sendOverdueReminder($loan)) {
                            $smsCount++;
                        }
                    }

                    // Update loan with reminder sent timestamp
                    $loan->update([
                        'last_reminder_sent' => now(),
                        'reminder_count' => ($loan->reminder_count ?? 0) + 1
                    ]);
                } catch (\Exception $e) {
                    $errors[] = "Failed to send reminder for loan {$loan->id}: " . $e->getMessage();
                }
            }

            $message = "Reminders sent successfully! ";
            $message .= "Emails: {$emailCount}, SMS: {$smsCount}";

            if (!empty($errors)) {
                $message .= " (Some reminders failed - check logs)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'total_loans' => $overdueLoans->count(),
                'emails_sent' => $emailCount,
                'sms_sent' => $smsCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminders: ' . $e->getMessage()
            ], 500);
        }
    }
}

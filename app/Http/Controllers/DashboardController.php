<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [
            'books' => DB::table('books')->count(),
            'members' => DB::table('members')->count(),
            'active_loans' => DB::table('loans')->whereNull('returned_date')->count(),
            'overdue_loans' => DB::table('loans')->whereNull('returned_date')->whereDate('due_date', '<', now()->toDateString())->count(),
            'kindle_books' => DB::table('books')->where('has_kindle_version', true)->count(),
        ];
        return view('dashboard', compact('counts'));
    }
}

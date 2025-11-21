<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [
            'books' => Book::count(),
            'members' => Member::count(),
            'active_loans' => Loan::whereNull('returned_date')->count(),
            'overdue_loans' => Loan::whereNull('returned_date')->where('due_date', '<', now()->toDateString())->count(),
        ];
        return view('dashboard', compact('counts'));
    }
}



<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(): View
    {
        $loans = Loan::with(['book', 'member'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        $books = Book::orderBy('title')->get();
        $members = Member::orderBy('name')->get();
        return view('loans.create', compact('books', 'members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'member_id' => ['required', 'exists:members,id'],
            'loan_days' => ['nullable', 'integer', 'min:1', 'max:60'],
        ]);

        $book = Book::findOrFail($data['book_id']);
        if ($book->available_copies <= 0) {
            return back()->withErrors(['book_id' => 'Selected book has no available copies']);
        }

        // Enforce per-member active loan limit (e.g., max 3 not returned)
        $activeLoans = Loan::where('member_id', $data['member_id'])
            ->whereNull('returned_date')
            ->count();
        $maxActiveLoans = 3;
        if ($activeLoans >= $maxActiveLoans) {
            return back()->withErrors(['member_id' => "Member already has {$activeLoans} active loans (max {$maxActiveLoans})."]);
        }

        $loanDays = (int) ($data['loan_days'] ?? 14); // Ensure integer type
        $loan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $data['member_id'],
            'loan_date' => Carbon::today(),
            'due_date' => Carbon::today()->copy()->addDays($loanDays),
            'returned_date' => null,
        ]);

        $book->decrement('available_copies');

        return redirect()->route('loans.index')->with('status', 'Loan created');
    }

    public function return(Loan $loan): RedirectResponse
    {
        if ($loan->returned_date !== null) {
            return back()->with('status', 'Loan already returned');
        }

        $loan->returned_date = Carbon::today();
        $loan->save();

        $loan->book->increment('available_copies');

        return redirect()->route('loans.index')->with('status', 'Book returned');
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        if ($loan->returned_date === null) {
            return back()->withErrors([
                'loan' => 'Only completed (returned) loans can be deleted.',
            ]);
        }

        $loan->delete();

        return redirect()->route('loans.index')->with('status', 'Completed loan deleted');
    }
}

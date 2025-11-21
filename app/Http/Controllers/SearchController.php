<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) >= 2) {
            // Search books
            $books = Book::with('category')
                ->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('isbn', 'like', "%{$query}%")
                ->limit(10)
                ->get();

            // Search members
            $members = Member::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(10)
                ->get();

            // Search categories
            $categories = Category::where('name', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            // Search loans (by book title or member name)
            $loans = Loan::with(['book', 'member'])
                ->whereHas('book', function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%");
                })
                ->orWhereHas('member', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();

            $results = [
                'books' => $books,
                'members' => $members,
                'categories' => $categories,
                'loans' => $loans,
                'total' => $books->count() + $members->count() + $categories->count() + $loans->count()
            ];
        }

        return view('search.index', compact('query', 'results'));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $books = Book::all();
        $members = Member::where('status', 'Active')->get();

        if ($books->isEmpty() || $members->isEmpty()) {
            return;
        }

        $loans = [
            // Active loans
            [
                'book_id' => $books->where('title', 'To Kill a Mockingbird')->first()?->id ?? $books->first()->id,
                'member_id' => $members->first()->id,
                'loan_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(9),
                'return_date' => null,
                'status' => 'Active',
                'fine_amount' => 0.00,
                'notes' => 'Regular loan'
            ],
            [
                'book_id' => $books->where('title', '1984')->first()?->id ?? $books->skip(1)->first()->id,
                'member_id' => $members->skip(1)->first()->id,
                'loan_date' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->addDays(4),
                'return_date' => null,
                'status' => 'Active',
                'fine_amount' => 0.00,
                'notes' => 'Requested renewal'
            ],
            [
                'book_id' => $books->where('title', 'Clean Code')->first()?->id ?? $books->skip(2)->first()->id,
                'member_id' => $members->skip(2)->first()->id,
                'loan_date' => Carbon::now()->subDays(3),
                'due_date' => Carbon::now()->addDays(11),
                'return_date' => null,
                'status' => 'Active',
                'fine_amount' => 0.00,
                'notes' => 'Programming reference'
            ],
            [
                'book_id' => $books->where('title', 'Sapiens')->first()?->id ?? $books->skip(3)->first()->id,
                'member_id' => $members->skip(3)->first()->id,
                'loan_date' => Carbon::now()->subDays(7),
                'due_date' => Carbon::now()->addDays(7),
                'return_date' => null,
                'status' => 'Active',
                'fine_amount' => 0.00,
                'notes' => 'Book club selection'
            ],
            [
                'book_id' => $books->where('title', 'Steve Jobs')->first()?->id ?? $books->skip(4)->first()->id,
                'member_id' => $members->skip(4)->first()->id,
                'loan_date' => Carbon::now()->subDays(12),
                'due_date' => Carbon::now()->addDays(2),
                'return_date' => null,
                'status' => 'Active',
                'fine_amount' => 0.00,
                'notes' => 'Research project'
            ],

            // Overdue loans
            [
                'book_id' => $books->where('title', 'The Pragmatic Programmer')->first()?->id ?? $books->skip(5)->first()->id,
                'member_id' => $members->skip(5)->first()->id,
                'loan_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(6),
                'return_date' => null,
                'status' => 'Overdue',
                'fine_amount' => 3.00,
                'notes' => 'Late fee applied'
            ],
            [
                'book_id' => $books->where('title', 'A Brief History of Time')->first()?->id ?? $books->skip(6)->first()->id,
                'member_id' => $members->skip(6)->first()->id,
                'loan_date' => Carbon::now()->subDays(25),
                'due_date' => Carbon::now()->subDays(11),
                'return_date' => null,
                'status' => 'Overdue',
                'fine_amount' => 5.50,
                'notes' => 'Member contacted'
            ],
            [
                'book_id' => $books->where('title', 'The Origin of Species')->first()?->id ?? $books->skip(7)->first()->id,
                'member_id' => $members->skip(7)->first()->id,
                'loan_date' => Carbon::now()->subDays(18),
                'due_date' => Carbon::now()->subDays(4),
                'return_date' => null,
                'status' => 'Overdue',
                'fine_amount' => 2.00,
                'notes' => 'Reminder sent'
            ],

            // Returned loans (recent history)
            [
                'book_id' => $books->where('title', 'Pride and Prejudice')->first()?->id ?? $books->skip(8)->first()->id,
                'member_id' => $members->skip(8)->first()->id,
                'loan_date' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(16),
                'return_date' => Carbon::now()->subDays(17),
                'status' => 'Returned',
                'fine_amount' => 0.00,
                'notes' => 'Returned early'
            ],
            [
                'book_id' => $books->where('title', 'The Great Gatsby')->first()?->id ?? $books->skip(9)->first()->id,
                'member_id' => $members->skip(9)->first()->id,
                'loan_date' => Carbon::now()->subDays(25),
                'due_date' => Carbon::now()->subDays(11),
                'return_date' => Carbon::now()->subDays(11),
                'status' => 'Returned',
                'fine_amount' => 0.00,
                'notes' => 'Returned on time'
            ],
            [
                'book_id' => $books->where('title', 'Cosmos')->first()?->id ?? $books->skip(10)->first()->id,
                'member_id' => $members->skip(10)->first()->id,
                'loan_date' => Carbon::now()->subDays(35),
                'due_date' => Carbon::now()->subDays(21),
                'return_date' => Carbon::now()->subDays(19),
                'status' => 'Returned',
                'fine_amount' => 1.00,
                'notes' => 'Late return, fine paid'
            ],
            [
                'book_id' => $books->where('title', 'How to Win Friends and Influence People')->first()?->id ?? $books->skip(11)->first()->id,
                'member_id' => $members->skip(11)->first()->id,
                'loan_date' => Carbon::now()->subDays(22),
                'due_date' => Carbon::now()->subDays(8),
                'return_date' => Carbon::now()->subDays(8),
                'status' => 'Returned',
                'fine_amount' => 0.00,
                'notes' => 'Good condition'
            ],
            [
                'book_id' => $books->where('title', 'The 7 Habits of Highly Effective People')->first()?->id ?? $books->skip(12)->first()->id,
                'member_id' => $members->skip(12)->first()->id,
                'loan_date' => Carbon::now()->subDays(40),
                'due_date' => Carbon::now()->subDays(26),
                'return_date' => Carbon::now()->subDays(24),
                'status' => 'Returned',
                'fine_amount' => 1.00,
                'notes' => 'Slight damage noted'
            ],
            [
                'book_id' => $books->where('title', 'Design Patterns')->first()?->id ?? $books->skip(13)->first()->id,
                'member_id' => $members->skip(13)->first()->id,
                'loan_date' => Carbon::now()->subDays(28),
                'due_date' => Carbon::now()->subDays(14),
                'return_date' => Carbon::now()->subDays(15),
                'status' => 'Returned',
                'fine_amount' => 0.00,
                'notes' => 'Excellent condition'
            ],
            [
                'book_id' => $books->where('title', 'The Guns of August')->first()?->id ?? $books->skip(14)->first()->id,
                'member_id' => $members->skip(14)->first()->id,
                'loan_date' => Carbon::now()->subDays(50),
                'due_date' => Carbon::now()->subDays(36),
                'return_date' => Carbon::now()->subDays(35),
                'status' => 'Returned',
                'fine_amount' => 0.50,
                'notes' => 'Research completed'
            ]
        ];

        foreach ($loans as $loanData) {
            // Ensure the book and member exist
            if (Book::find($loanData['book_id']) && Member::find($loanData['member_id'])) {
                Loan::create($loanData);

                // Update book's available copies for active loans
                if ($loanData['status'] === 'Active' || $loanData['status'] === 'Overdue') {
                    $book = Book::find($loanData['book_id']);
                    if ($book && $book->available_copies > 0) {
                        $book->decrement('available_copies');
                    }
                }
            }
        }
    }
}

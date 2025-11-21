<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run()
    {
        // Get all categories
        $categories = Category::all();

        $books = [
            // Fiction
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780061120084',
                'publication_year' => 1960,
                'publisher' => 'J.B. Lippincott & Co.',
                'pages' => 376,
                'description' => 'A gripping tale of racial injustice and childhood innocence in the American South.',
                'total_copies' => 5,
                'available_copies' => 3,
                'category' => 'Fiction'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'publication_year' => 1949,
                'publisher' => 'Secker & Warburg',
                'pages' => 328,
                'description' => 'A dystopian social science fiction novel about totalitarian control.',
                'total_copies' => 4,
                'available_copies' => 2,
                'category' => 'Fiction'
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '9780141439518',
                'publication_year' => 1813,
                'publisher' => 'T. Egerton',
                'pages' => 432,
                'description' => 'A romantic novel of manners written during the Regency period.',
                'total_copies' => 3,
                'available_copies' => 3,
                'category' => 'Fiction'
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '9780743273565',
                'publication_year' => 1925,
                'publisher' => 'Charles Scribner\'s Sons',
                'pages' => 180,
                'description' => 'The story of Jay Gatsby and his pursuit of the American Dream.',
                'total_copies' => 4,
                'available_copies' => 4,
                'category' => 'Fiction'
            ],

            // Science
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'isbn' => '9780553380163',
                'publication_year' => 1988,
                'publisher' => 'Bantam Dell Publishing Group',
                'pages' => 256,
                'description' => 'A landmark volume in science writing that explores the nature of time and the universe.',
                'total_copies' => 3,
                'available_copies' => 2,
                'category' => 'Science'
            ],
            [
                'title' => 'The Origin of Species',
                'author' => 'Charles Darwin',
                'isbn' => '9780140436747',
                'publication_year' => 1859,
                'publisher' => 'John Murray',
                'pages' => 502,
                'description' => 'Darwin\'s theory of evolution through natural selection.',
                'total_copies' => 2,
                'available_copies' => 1,
                'category' => 'Science'
            ],
            [
                'title' => 'Cosmos',
                'author' => 'Carl Sagan',
                'isbn' => '9780345331359',
                'publication_year' => 1980,
                'publisher' => 'Random House',
                'pages' => 365,
                'description' => 'A journey through the universe and our place within it.',
                'total_copies' => 3,
                'available_copies' => 3,
                'category' => 'Science'
            ],

            // Technology
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'publication_year' => 2008,
                'publisher' => 'Prentice Hall',
                'pages' => 464,
                'description' => 'A handbook of agile software craftsmanship.',
                'total_copies' => 4,
                'available_copies' => 2,
                'category' => 'Technology'
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'isbn' => '9780135957059',
                'publication_year' => 1999,
                'publisher' => 'Addison-Wesley',
                'pages' => 352,
                'description' => 'From journeyman to master - a guide to software development.',
                'total_copies' => 3,
                'available_copies' => 1,
                'category' => 'Technology'
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'isbn' => '9780201633612',
                'publication_year' => 1994,
                'publisher' => 'Addison-Wesley',
                'pages' => 395,
                'description' => 'Elements of reusable object-oriented software.',
                'total_copies' => 2,
                'available_copies' => 2,
                'category' => 'Technology'
            ],

            // History
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'publication_year' => 2014,
                'publisher' => 'Harper',
                'pages' => 443,
                'description' => 'A brief history of humankind.',
                'total_copies' => 5,
                'available_copies' => 3,
                'category' => 'History'
            ],
            [
                'title' => 'The Guns of August',
                'author' => 'Barbara Tuchman',
                'isbn' => '9780345476098',
                'publication_year' => 1962,
                'publisher' => 'Macmillan',
                'pages' => 511,
                'description' => 'The outbreak of World War I and the first month of the war.',
                'total_copies' => 2,
                'available_copies' => 2,
                'category' => 'History'
            ],

            // Biography
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'isbn' => '9781451648539',
                'publication_year' => 2011,
                'publisher' => 'Simon & Schuster',
                'pages' => 656,
                'description' => 'The exclusive biography of Apple\'s co-founder.',
                'total_copies' => 3,
                'available_copies' => 1,
                'category' => 'Biography'
            ],
            [
                'title' => 'The Diary of a Young Girl',
                'author' => 'Anne Frank',
                'isbn' => '9780553296983',
                'publication_year' => 1947,
                'publisher' => 'Contact Publishing',
                'pages' => 283,
                'description' => 'The diary of Anne Frank during the Nazi occupation.',
                'total_copies' => 4,
                'available_copies' => 4,
                'category' => 'Biography'
            ],

            // Self-Help
            [
                'title' => 'How to Win Friends and Influence People',
                'author' => 'Dale Carnegie',
                'isbn' => '9780671027032',
                'publication_year' => 1936,
                'publisher' => 'Simon & Schuster',
                'pages' => 291,
                'description' => 'Timeless advice on building relationships and influencing others.',
                'total_copies' => 3,
                'available_copies' => 2,
                'category' => 'Self-Help'
            ],
            [
                'title' => 'The 7 Habits of Highly Effective People',
                'author' => 'Stephen R. Covey',
                'isbn' => '9780743269513',
                'publication_year' => 1989,
                'publisher' => 'Free Press',
                'pages' => 372,
                'description' => 'A principle-centered approach for solving personal and professional problems.',
                'total_copies' => 4,
                'available_copies' => 3,
                'category' => 'Self-Help'
            ]
        ];

        foreach ($books as $bookData) {
            $categoryName = $bookData['category'];
            unset($bookData['category']);

            $category = $categories->where('name', $categoryName)->first();
            if ($category) {
                $bookData['category_id'] = $category->id;
            }

            Book::create($bookData);
        }
    }
}

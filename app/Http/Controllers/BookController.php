<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Department;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with(['category', 'department'])->orderBy('title');
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }
        $books = $query->paginate(10)->withQueryString();
        $categories = Category::query()->orderBy('name', 'asc')->get();
        return view('books.index', compact('books', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();
        $departments = Department::query()->orderBy('name', 'asc')->get();
        return view('books.create', compact('categories', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:64', 'unique:books,isbn'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'has_kindle_version' => ['nullable', 'boolean'],
            'kindle_link' => ['nullable', 'url', 'max:2048'],
        ]);

        $data['available_copies'] = $data['total_copies'];
        $data['has_kindle_version'] = $request->boolean('has_kindle_version');
        $book = Book::create($data);

        // Log admin activity
        AdminActivityLogger::log(
            'create',
            'Book Added',
            "New book \"{$book->title}\" by {$book->author} was added to the library",
            route('books.edit', $book)
        );

        return redirect()->route('books.index')->with('status', 'Book added');
    }

    public function edit(Book $book): View
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();
        $departments = Department::query()->orderBy('name', 'asc')->get();
        return view('books.edit', compact('book', 'categories', 'departments'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:64', 'unique:books,isbn,' . $book->id],
            'total_copies' => ['required', 'integer', 'min:1'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'has_kindle_version' => ['nullable', 'boolean'],
            'kindle_link' => ['nullable', 'url', 'max:2048'],
        ]);

        // Adjust available copies proportionally if total changes downwards
        $difference = $data['total_copies'] - $book->total_copies;
        $data['has_kindle_version'] = $request->boolean('has_kindle_version');
        $book->fill($data);
        $book->available_copies = max(0, $book->available_copies + $difference);
        $book->save();

        // Log admin activity
        AdminActivityLogger::log(
            'update',
            'Book Updated',
            "Book \"{$book->title}\" by {$book->author} was updated",
            route('books.edit', $book)
        );

        return redirect()->route('books.index')->with('status', 'Book updated');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $title = $book->title;
        $author = $book->author;

        Book::destroy($book->id);

        // Log admin activity
        AdminActivityLogger::log(
            'delete',
            'Book Deleted',
            "Book \"{$title}\" by {$author} was removed from the library",
            route('books.index')
        );

        return redirect()->route('books.index')->with('status', 'Book deleted');
    }
}

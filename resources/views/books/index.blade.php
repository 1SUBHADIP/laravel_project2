@extends('layout')

@section('title', 'Books')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div></div>
  <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Add Book</a>
</div>

<form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
  <input type="text" name="q" class="rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" placeholder="Search title, author, ISBN" value="{{ request('q') }}">
  <select name="category_id" class="rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
    <option value="">All Categories</option>
    @isset($categories)
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
      @endforeach
    @endisset
  </select>
  <div>
    <button class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800">Filter</button>
  </div>
</form>

<div class="overflow-hidden rounded-lg border border-slate-800">
  <table class="min-w-full divide-y divide-slate-800">
    <thead class="bg-slate-900/60">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Title</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Author</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">ISBN</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Category</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Available/Total</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-800 bg-card">
      @foreach($books as $book)
        <tr>
          <td class="px-4 py-3">{{ $book->title }}</td>
          <td class="px-4 py-3">{{ $book->author }}</td>
          <td class="px-4 py-3">{{ $book->isbn }}</td>
          <td class="px-4 py-3">{{ $book->category?->name ?? '-' }}</td>
          <td class="px-4 py-3">{{ $book->available_copies }} / {{ $book->total_copies }}</td>
          <td class="px-4 py-3">
            <div class="flex gap-2">
              <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center rounded-md border border-slate-600 px-2 py-1 text-xs hover:bg-slate-800 text-slate-300">
                <i class="fas fa-edit mr-1"></i>Edit
              </a>
              <!-- Delete button -->
              <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md border border-rose-600 bg-rose-900/20 text-rose-300 px-2 py-1 text-xs hover:bg-rose-900/40 hover:border-rose-500">
                  <i class="fas fa-trash mr-1"></i>Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $books->links() }}</div>
@endsection



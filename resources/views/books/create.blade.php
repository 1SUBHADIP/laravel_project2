@extends('layout')

@section('title', 'Add Book')

@section('content')
<form action="{{ route('books.store') }}" method="POST" class="mt-3 space-y-4">
  @csrf
  <div>
    <label class="block text-sm text-slate-300 mb-1">Title</label>
    <input type="text" name="title" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('title') }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Author</label>
    <input type="text" name="author" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('author') }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Category</label>
    <select name="category_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">None</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-2">ISBN</label>
    @include('components.simple-isbn-scanner')
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Total Copies</label>
    <input type="number" name="total_copies" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('total_copies', 1) }}" min="1" required>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Save</button>
  </div>
</form>
@endsection



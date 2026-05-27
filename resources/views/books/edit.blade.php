@extends('layout')

@section('title', 'Edit Book')
@section('breadcrumb', 'Books / Edit')

@section('content')
<form action="{{ route('books.update', $book) }}" method="POST" class="mt-3 space-y-4 max-w-4xl">
  @csrf
  @method('PUT')
  <div>
    <label class="block text-sm text-slate-300 mb-1">Title</label>
    <input type="text" name="title" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('title', $book->title) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Author</label>
    <input type="text" name="author" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('author', $book->author) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Category</label>
    <select name="category_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">None</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Department</label>
    <select name="department_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">All Departments</option>
      @foreach($departments as $department)
        <option value="{{ $department->id }}" @selected(old('department_id', $book->department_id) == $department->id)>{{ $department->name }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-2">ISBN</label>
    @include('components.simple-isbn-scanner', ['initialIsbn' => old('isbn', $book->isbn)])
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Total Copies</label>
    <input type="number" name="total_copies" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('total_copies', $book->total_copies) }}" min="1" required>
  </div>
  
  <!-- Kindle Version -->
  <div class="md:col-span-2 p-4 bg-slate-900/50 border border-slate-800 rounded-lg mt-2 mb-4">
    <div class="flex items-center mb-4">
      <input type="checkbox" 
             id="has_kindle_version"
             name="has_kindle_version" 
             value="1"
             {{ old('has_kindle_version', $book->has_kindle_version) ? 'checked' : '' }}
             class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-primary focus:ring-primary focus:ring-offset-slate-900 transition-colors">
      <label for="has_kindle_version" class="ml-3 block text-sm font-medium text-slate-300">
        <i class="fas fa-tablet-alt mr-2"></i>Available on Kindle
      </label>
    </div>
    
    <div>
      <label for="kindle_link" class="block text-sm font-medium text-slate-400 mb-2">Kindle Link (Optional)</label>
      <input type="url" 
             id="kindle_link"
             name="kindle_link" 
             value="{{ old('kindle_link', $book->kindle_link) }}"
             class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
             placeholder="https://amazon.com/...">
      @error('kindle_link')
        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
      @enderror
    </div>
  </div>

  <div class="flex gap-2">
    <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Update</button>
  </div>
</form>
@endsection



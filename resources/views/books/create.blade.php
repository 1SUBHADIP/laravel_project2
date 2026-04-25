@extends('layout')

@section('title', 'Add Book')
@section('breadcrumb', 'Books > Add New')

@section('content')
<!-- Page Header -->
<div class="mb-8">
  <div class="flex items-center gap-3 mb-4">
    <a href="{{ route('books.index') }}" 
       class="flex items-center justify-center w-10 h-10 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
      <i class="fas fa-arrow-left text-slate-300"></i>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-white">Add New Book</h1>
      <p class="text-slate-400">Enter the details of the new book to add it to the library.</p>
    </div>
  </div>
</div>

<!-- Form Card -->
<div class="max-w-4xl">
  <div class="bg-card border border-slate-800 rounded-xl p-8">
    <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Title -->
        <div class="md:col-span-2">
          <label for="title" class="block text-sm font-medium text-slate-300 mb-2">
            <i class="fas fa-book mr-2"></i>Title
          </label>
          <input type="text" 
                 id="title"
                 name="title" 
                 value="{{ old('title') }}"
                 class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
                 placeholder="Enter book title"
                 required
                 autofocus>
          @error('title')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <!-- Author -->
        <div>
          <label for="author" class="block text-sm font-medium text-slate-300 mb-2">
            <i class="fas fa-user-edit mr-2"></i>Author
          </label>
          <input type="text" 
                 id="author"
                 name="author" 
                 value="{{ old('author') }}"
                 class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
                 placeholder="Enter author name"
                 required>
          @error('author')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <!-- Category -->
        <div>
          <label for="category_id" class="block text-sm font-medium text-slate-300 mb-2">
            <i class="fas fa-tags mr-2"></i>Category
          </label>
          <select id="category_id" 
                  name="category_id" 
                  class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200">
            <option value="">Select Category (Optional)</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
            @endforeach
          </select>
          @error('category_id')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <!-- ISBN -->
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-slate-300 mb-2">
            <i class="fas fa-barcode mr-2"></i>ISBN
          </label>
          @include('components.simple-isbn-scanner')
          @error('isbn')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <!-- Total Copies -->
        <div>
          <label for="total_copies" class="block text-sm font-medium text-slate-300 mb-2">
            <i class="fas fa-copy mr-2"></i>Total Copies
          </label>
          <input type="number" 
                 id="total_copies"
                 name="total_copies" 
                 value="{{ old('total_copies', 1) }}"
                 min="1"
                 class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
                 required>
          @error('total_copies')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t border-slate-700">
        <button type="submit" 
                class="flex-1 sm:flex-none bg-primary hover:bg-primary-600 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
          <i class="fas fa-save mr-2"></i>
          Save Book
        </button>
        <a href="{{ route('books.index') }}" 
           class="flex-1 sm:flex-none bg-slate-700 hover:bg-slate-600 text-white font-semibold px-8 py-3 rounded-lg transition-colors text-center">
          <i class="fas fa-times mr-2"></i>
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
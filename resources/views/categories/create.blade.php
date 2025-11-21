@extends('layout')

@section('title', 'Add Category')
@section('breadcrumb', 'Categories > Add New')

@section('content')
<!-- Page Header -->
<div class="mb-8">
  <div class="flex items-center gap-3 mb-4">
    <a href="{{ route('categories.index') }}" 
       class="flex items-center justify-center w-10 h-10 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
      <i class="fas fa-arrow-left text-slate-300"></i>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-white">Add New Category</h1>
      <p class="text-slate-400">Create a new book category</p>
    </div>
  </div>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
  <div class="bg-card border border-slate-800 rounded-xl p-8">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
      @csrf
      
      <!-- Category Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
          <i class="fas fa-tag mr-2"></i>Category Name
        </label>
        <input type="text" 
               id="name"
               name="name" 
               value="{{ old('name') }}"
               class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
               placeholder="Enter category name (e.g., Fiction, Science, History)"
               required
               autofocus>
        @error('name')
          <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
      </div>

      <!-- Helper Text -->
      <div class="bg-slate-800/50 border border-slate-700 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
          <div>
            <h4 class="text-sm font-medium text-blue-300 mb-1">Category Guidelines</h4>
            <ul class="text-sm text-slate-400 space-y-1">
              <li>• Use clear, descriptive names for easy identification</li>
              <li>• Category names should be unique</li>
              <li>• Consider using broad categories that can accommodate many books</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button type="submit" 
                class="flex-1 sm:flex-none bg-primary hover:bg-primary-600 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
          <i class="fas fa-save mr-2"></i>
          Save Category
        </button>
        <a href="{{ route('categories.index') }}" 
           class="flex-1 sm:flex-none bg-slate-700 hover:bg-slate-600 text-white font-semibold px-8 py-3 rounded-lg transition-colors text-center">
          <i class="fas fa-times mr-2"></i>
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-card border border-slate-800 rounded-xl p-6">
  <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <a href="{{ route('categories.index') }}" 
       class="flex items-center gap-3 p-4 bg-slate-800/50 hover:bg-slate-700 rounded-lg transition-colors group">
      <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
        <i class="fas fa-list text-blue-400"></i>
      </div>
      <div>
        <p class="text-white font-medium">View All Categories</p>
        <p class="text-slate-400 text-sm">Browse existing categories</p>
      </div>
    </a>
    
    <a href="{{ route('books.create') }}" 
       class="flex items-center gap-3 p-4 bg-slate-800/50 hover:bg-slate-700 rounded-lg transition-colors group">
      <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition-colors">
        <i class="fas fa-book text-green-400"></i>
      </div>
      <div>
        <p class="text-white font-medium">Add New Book</p>
        <p class="text-slate-400 text-sm">Add book to this category</p>
      </div>
    </a>
  </div>
</div>
@endsection



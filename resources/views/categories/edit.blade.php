@extends('layout')

@section('title', 'Edit Category')
@section('breadcrumb', 'Categories > Edit')

@section('content')
<!-- Page Header -->
<div class="mb-8">
  <div class="flex items-center gap-3 mb-4">
    <a href="{{ route('categories.index') }}" 
       class="flex items-center justify-center w-10 h-10 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
      <i class="fas fa-arrow-left text-slate-300"></i>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-white">Edit Category</h1>
      <p class="text-slate-400">Update category information</p>
    </div>
  </div>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
  <div class="bg-card border border-slate-800 rounded-xl p-8">
    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      
      <!-- Category Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
          <i class="fas fa-tag mr-2"></i>Category Name
        </label>
        <input type="text" 
               id="name"
               name="name" 
               value="{{ old('name', $category->name) }}"
               class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
               placeholder="Enter category name"
               required
               autofocus>
        @error('name')
          <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
      </div>

      <!-- Category Info -->
      <div class="bg-slate-800/50 border border-slate-700 rounded-lg p-4">
        <div class="flex items-start gap-3">
          <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
          <div>
            <h4 class="text-sm font-medium text-blue-300 mb-2">Category Information</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-400">
              <div>
                <span class="font-medium">Books in this category:</span>
                <span class="ml-2 text-accent">{{ $category->books_count ?? $category->books()->count() }}</span>
              </div>
              <div>
                <span class="font-medium">Created:</span>
                <span class="ml-2">{{ $category->created_at->format('M d, Y') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button type="submit" 
                class="flex-1 sm:flex-none bg-primary hover:bg-primary-600 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
          <i class="fas fa-save mr-2"></i>
          Update Category
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

<!-- Danger Zone -->
@if($category->books()->count() == 0)
<div class="mt-8 bg-red-900/20 border border-red-700 rounded-xl p-6">
  <h3 class="text-lg font-semibold text-red-300 mb-4">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    Danger Zone
  </h3>
  <p class="text-red-200 mb-4">
    This category has no books associated with it. You can safely delete it if no longer needed.
  </p>
  <form action="{{ route('categories.destroy', $category) }}" 
        method="POST" 
        class="inline"
        onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
    @csrf
    @method('DELETE')
    <button type="submit" 
            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
      <i class="fas fa-trash mr-2"></i>
      Delete Category
    </button>
  </form>
</div>
@else
<div class="mt-8 bg-yellow-900/20 border border-yellow-700 rounded-xl p-6">
  <h3 class="text-lg font-semibold text-yellow-300 mb-2">
    <i class="fas fa-info-circle mr-2"></i>
    Category in Use
  </h3>
  <p class="text-yellow-200">
    This category cannot be deleted because it has {{ $category->books()->count() }} book(s) associated with it. 
    Move or remove the books first before deleting this category.
  </p>
</div>
@endif
@endsection



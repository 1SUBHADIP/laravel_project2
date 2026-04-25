@extends('layout')

@section('title', 'Categories')
@section('breadcrumb', 'Categories Management')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-8">
  <div>
    <h1 class="text-2xl font-bold text-white mb-2">Categories</h1>
    <p class="text-slate-400">Manage book categories and classifications</p>
  </div>
  <a href="{{ route('categories.create') }}" 
     class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
    <i class="fas fa-plus text-sm"></i>
    Add Category
  </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-tags text-blue-400 text-xl"></i>
      </div>
      <div>
        <p class="text-slate-400 text-sm">Total Categories</p>
        <p class="text-2xl font-bold text-white">{{ $categories->total() }}</p>
      </div>
    </div>
  </div>
  
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-book text-green-400 text-xl"></i>
      </div>
      <div>
        <p class="text-slate-400 text-sm">Books Categorized</p>
        <p class="text-2xl font-bold text-white">{{ \App\Models\Book::whereNotNull('category_id')->count() }}</p>
      </div>
    </div>
  </div>
  
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-bookmark text-yellow-400 text-xl"></i>
      </div>
      <div>
        <p class="text-slate-400 text-sm">Uncategorized</p>
        <p class="text-2xl font-bold text-white">{{ \App\Models\Book::whereNull('category_id')->count() }}</p>
      </div>
    </div>
  </div>
</div>

<!-- Categories Table -->
<div class="bg-card border border-slate-800 rounded-xl overflow-hidden">
  <div class="px-6 py-4 border-b border-slate-800">
    <h3 class="text-lg font-semibold text-white">All Categories</h3>
  </div>
  
  @if($categories->count() > 0)
    <div class="overflow-x-auto">
      <table class="min-w-[900px] w-full">
        <thead class="bg-slate-800/50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
              <i class="fas fa-tag mr-2"></i>Category Name
            </th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
              <i class="fas fa-book mr-2"></i>Books Count
            </th>
            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
              <i class="fas fa-calendar mr-2"></i>Created
            </th>
            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
              <i class="fas fa-cog mr-2"></i>Actions
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
          @foreach($categories as $category)
            <tr class="hover:bg-slate-800/30 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tag text-primary text-sm"></i>
                  </div>
                  <span class="text-white font-medium">{{ $category->name }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                  <i class="fas fa-book"></i>
                  {{ $category->books_count ?? 0 }} books
                </span>
              </td>
              <td class="px-6 py-4 text-slate-400">
                {{ $category->created_at->format('M d, Y') }}
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('categories.edit', $category) }}" 
                     class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-lg">
                    <i class="fas fa-edit text-xs"></i>
                    Edit
                  </a>
                  <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-700 hover:bg-rose-600 text-white text-sm rounded-lg">
                      <i class="fas fa-trash text-xs"></i>
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
      <div class="px-6 py-4 border-t border-slate-800">
        {{ $categories->links() }}
      </div>
    @endif
  @else
    <!-- Empty State -->
    <div class="text-center py-12">
      <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-tags text-slate-400 text-2xl"></i>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">No Categories Found</h3>
      <p class="text-slate-400 mb-6">Get started by creating your first book category.</p>
      <a href="{{ route('categories.create') }}" 
         class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
        <i class="fas fa-plus text-sm"></i>
        Add First Category
      </a>
    </div>
  @endif
</div>
@endsection



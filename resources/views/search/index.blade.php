@extends('layout')

@section('title', 'Search Results')
@section('breadcrumb', 'Search Results')

@section('content')
<!-- Search Header -->
<div class="mb-8">
  <div class="flex items-center gap-3 mb-4">
    <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
      <i class="fas fa-search text-primary text-xl"></i>
    </div>
    <div>
      <h1 class="text-2xl font-bold text-white">Search Results</h1>
      @if($query)
        <p class="text-slate-400">
          @if($results['total'] > 0)
            Found {{ $results['total'] }} result(s) for "<span class="text-accent">{{ $query }}</span>"
          @else
            No results found for "<span class="text-red-400">{{ $query }}</span>"
          @endif
        </p>
      @else
        <p class="text-slate-400">Enter a search term to find books, members, loans, and categories</p>
      @endif
    </div>
  </div>
</div>

<!-- Search Form -->
<div class="bg-card border border-slate-800 rounded-xl p-6 mb-8">
  <form action="{{ route('search') }}" method="GET" class="flex gap-4">
    <div class="flex-1">
      <input type="text" 
             name="q" 
             value="{{ $query }}"
             placeholder="Search books, members, loans, categories..."
             class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
             autofocus>
    </div>
    <button type="submit" 
            class="bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-lg transition-colors font-semibold">
      <i class="fas fa-search mr-2"></i>
      Search
    </button>
  </form>
</div>

@if($query && $results['total'] > 0)
  <!-- Results Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Books Results -->
    @if($results['books']->count() > 0)
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-book text-blue-400"></i>
          Books ({{ $results['books']->count() }})
        </h3>
        
        <div class="space-y-3">
          @foreach($results['books'] as $book)
            <div class="p-4 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition-colors">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h4 class="text-white font-medium mb-1">{{ $book->title }}</h4>
                  <p class="text-slate-400 text-sm mb-2">by {{ $book->author }}</p>
                  <div class="flex items-center gap-4 text-xs">
                    <span class="text-slate-500">ISBN: {{ $book->isbn }}</span>
                    @if($book->category)
                      <span class="px-2 py-1 bg-primary/20 text-primary rounded-full">{{ $book->category->name }}</span>
                    @endif
                  </div>
                </div>
                <div class="text-right ml-4">
                  <div class="text-sm">
                    <span class="text-accent font-bold">{{ $book->available_copies }}</span>
                    <span class="text-slate-400">/ {{ $book->total_copies }}</span>
                  </div>
                  <p class="text-xs text-slate-500">Available</p>
                </div>
              </div>
              
              <div class="mt-3 flex gap-2">
                <a href="{{ route('books.edit', $book) }}" 
                   class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-1 rounded transition-colors">
                  Edit
                </a>
                @if($book->available_copies > 0)
                  <a href="{{ route('loans.create', ['book_id' => $book->id]) }}" 
                     class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors">
                    Loan
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
        
        @if($results['books']->count() >= 10)
          <div class="mt-4 text-center">
            <a href="{{ route('books.index', ['q' => $query]) }}" 
               class="text-accent hover:text-accent-light text-sm">
              View all book results →
            </a>
          </div>
        @endif
      </div>
    @endif

    <!-- Members Results -->
    @if($results['members']->count() > 0)
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-users text-green-400"></i>
          Members ({{ $results['members']->count() }})
        </h3>
        
        <div class="space-y-3">
          @foreach($results['members'] as $member)
            <div class="p-4 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-green-400"></i>
                  </div>
                  <div>
                    <h4 class="text-white font-medium">{{ $member->name }}</h4>
                    <p class="text-slate-400 text-sm">{{ $member->email }}</p>
                    @if($member->phone)
                      <p class="text-slate-500 text-xs">{{ $member->phone }}</p>
                    @endif
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm text-slate-400">
                    Member since {{ $member->created_at->format('M Y') }}
                  </div>
                </div>
              </div>
              
              <div class="mt-3 flex gap-2">
                <a href="{{ route('members.edit', $member) }}" 
                   class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-1 rounded transition-colors">
                  Edit
                </a>
                <a href="{{ route('loans.create', ['member_id' => $member->id]) }}" 
                   class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                  New Loan
                </a>
              </div>
            </div>
          @endforeach
        </div>
        
        @if($results['members']->count() >= 10)
          <div class="mt-4 text-center">
            <a href="{{ route('members.index', ['q' => $query]) }}" 
               class="text-accent hover:text-accent-light text-sm">
              View all member results →
            </a>
          </div>
        @endif
      </div>
    @endif

    <!-- Categories Results -->
    @if($results['categories']->count() > 0)
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-tags text-yellow-400"></i>
          Categories ({{ $results['categories']->count() }})
        </h3>
        
        <div class="space-y-3">
          @foreach($results['categories'] as $category)
            <div class="p-4 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tag text-yellow-400"></i>
                  </div>
                  <h4 class="text-white font-medium">{{ $category->name }}</h4>
                </div>
                <div class="text-right">
                  <span class="text-sm text-slate-400">{{ $category->books_count ?? $category->books()->count() }} books</span>
                </div>
              </div>
              
              <div class="mt-3 flex gap-2">
                <a href="{{ route('categories.edit', $category) }}" 
                   class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-1 rounded transition-colors">
                  Edit
                </a>
                <a href="{{ route('books.index', ['category_id' => $category->id]) }}" 
                   class="text-xs bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded transition-colors">
                  View Books
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Loans Results -->
    @if($results['loans']->count() > 0)
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-exchange-alt text-purple-400"></i>
          Loans ({{ $results['loans']->count() }})
        </h3>
        
        <div class="space-y-3">
          @foreach($results['loans'] as $loan)
            <div class="p-4 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition-colors">
              <div class="flex items-start justify-between">
                <div>
                  <h4 class="text-white font-medium mb-1">{{ $loan->book->title }}</h4>
                  <p class="text-slate-400 text-sm mb-2">Borrowed by {{ $loan->member->name }}</p>
                  <div class="flex items-center gap-4 text-xs text-slate-500">
                    <span>Loan: {{ $loan->loan_date->format('M d, Y') }}</span>
                    <span>Due: {{ $loan->due_date->format('M d, Y') }}</span>
                  </div>
                </div>
                <div class="text-right">
                  @if($loan->returned_date)
                    <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Returned</span>
                  @elseif($loan->due_date < now())
                    <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">Overdue</span>
                  @else
                    <span class="px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full">Active</span>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        
        @if($results['loans']->count() >= 10)
          <div class="mt-4 text-center">
            <a href="{{ route('loans.index', ['q' => $query]) }}" 
               class="text-accent hover:text-accent-light text-sm">
              View all loan results →
            </a>
          </div>
        @endif
      </div>
    @endif
  </div>

@elseif($query && $results['total'] == 0)
  <!-- No Results -->
  <div class="bg-card border border-slate-800 rounded-xl p-12 text-center">
    <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
      <i class="fas fa-search text-slate-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">No Results Found</h3>
    <p class="text-slate-400 mb-6">
      We couldn't find anything matching "<span class="text-red-400">{{ $query }}</span>". 
      Try adjusting your search terms.
    </p>
    
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="{{ route('books.create') }}" 
         class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
        <i class="fas fa-plus mr-2"></i>Add New Book
      </a>
      <a href="{{ route('members.create') }}" 
         class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
        <i class="fas fa-user-plus mr-2"></i>Add New Member
      </a>
    </div>
  </div>

@else
  <!-- Search Suggestions -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-card border border-slate-800 rounded-xl p-6 text-center">
      <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-book text-blue-400 text-xl"></i>
      </div>
      <h3 class="text-white font-semibold mb-2">Browse Books</h3>
      <p class="text-slate-400 text-sm mb-4">Explore our book collection</p>
      <a href="{{ route('books.index') }}" class="text-blue-400 hover:text-blue-300 text-sm">View All Books →</a>
    </div>
    
    <div class="bg-card border border-slate-800 rounded-xl p-6 text-center">
      <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-users text-green-400 text-xl"></i>
      </div>
      <h3 class="text-white font-semibold mb-2">Browse Members</h3>
      <p class="text-slate-400 text-sm mb-4">View library members</p>
      <a href="{{ route('members.index') }}" class="text-green-400 hover:text-green-300 text-sm">View All Members →</a>
    </div>
    
    <div class="bg-card border border-slate-800 rounded-xl p-6 text-center">
      <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-exchange-alt text-yellow-400 text-xl"></i>
      </div>
      <h3 class="text-white font-semibold mb-2">Browse Loans</h3>
      <p class="text-slate-400 text-sm mb-4">Check loan activities</p>
      <a href="{{ route('loans.index') }}" class="text-yellow-400 hover:text-yellow-300 text-sm">View All Loans →</a>
    </div>
    
    <div class="bg-card border border-slate-800 rounded-xl p-6 text-center">
      <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-tags text-purple-400 text-xl"></i>
      </div>
      <h3 class="text-white font-semibold mb-2">Browse Categories</h3>
      <p class="text-slate-400 text-sm mb-4">Explore book categories</p>
      <a href="{{ route('categories.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">View All Categories →</a>
    </div>
  </div>
@endif
@endsection

@extends('layout')

@section('title', 'Reports')
@section('breadcrumb', 'Reports & Analytics')

@section('content')
<!-- Reports Header -->
<div class="bg-linear-to-r from-primary/10 to-accent/10 border border-primary/20 rounded-xl p-6 mb-8">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 bg-linear-to-br from-primary to-accent rounded-xl flex items-center justify-center">
      <i class="fas fa-chart-bar text-white text-xl"></i>
    </div>
    <div>
      <h2 class="text-xl font-bold text-white">Reports & Analytics</h2>
      <p class="text-slate-300">Comprehensive insights into your library's performance</p>
    </div>
  </div>
</div>

<!-- Filter Options -->
<div class="bg-card border border-slate-800 rounded-xl p-6 mb-8">
  <h3 class="text-lg font-semibold text-white mb-4">
    <i class="fas fa-filter text-accent mr-2"></i>
    Filter Reports
  </h3>
  
  <form id="reportFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Date Range</label>
      <select class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="today">Today</option>
        <option value="week">This Week</option>
        <option value="month" selected>This Month</option>
        <option value="quarter">This Quarter</option>
        <option value="year">This Year</option>
        <option value="custom">Custom Range</option>
      </select>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Report Type</label>
      <select class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="all">All Reports</option>
        <option value="loans">Loan Reports</option>
        <option value="books">Book Reports</option>
        <option value="members">Member Reports</option>
        <option value="overdue">Overdue Reports</option>
      </select>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Category</label>
      <select class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="">All Categories</option>
        @foreach(\App\Models\Category::orderBy('name')->get() as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
    
    <div class="flex items-end">
      <button type="button" onclick="generateReport()" 
              class="w-full bg-primary hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition-colors">
        <i class="fas fa-sync-alt mr-2"></i>Generate Report
      </button>
    </div>
  </form>
</div>

<!-- Real-time Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <!-- Books Statistics -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-book text-blue-400 text-xl"></i>
      </div>
      <span class="text-2xl font-bold text-white">{{ $totalBooks }}</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Total Books</h3>
    <div class="flex items-center text-sm">
      <span class="text-green-400">
        <i class="fas fa-arrow-up mr-1"></i>
        +{{ $newBooksThisMonth }}
      </span>
      <span class="text-slate-400 ml-1">this month</span>
    </div>
  </div>

  <!-- Members Statistics -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-users text-green-400 text-xl"></i>
      </div>
      <span class="text-2xl font-bold text-white">{{ $totalMembers }}</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Active Members</h3>
    <div class="flex items-center text-sm">
      <span class="text-green-400">
        <i class="fas fa-arrow-up mr-1"></i>
        +{{ $newMembersThisMonth }}
      </span>
      <span class="text-slate-400 ml-1">this month</span>
    </div>
  </div>

  <!-- Active Loans -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exchange-alt text-yellow-400 text-xl"></i>
      </div>
      <span class="text-2xl font-bold text-white">{{ $activeLoans }}</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Active Loans</h3>
    <div class="flex items-center text-sm">
      <span class="text-blue-400">
        <i class="fas fa-clock mr-1"></i>
        {{ $loansToday }} today
      </span>
    </div>
  </div>

  <!-- Overdue Books -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
      </div>
      <span class="text-2xl font-bold text-white">{{ $overdueItems }}</span>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Overdue Items</h3>
    <div class="flex items-center text-sm">
      @if($overdueItems > 0)
        <span class="text-red-400">
          <i class="fas fa-exclamation-circle mr-1"></i>
          <a href="{{ route('reports.overdue') }}" class="hover:underline">View details</a>
        </span>
      @else
        <span class="text-green-400">
          <i class="fas fa-check-circle mr-1"></i>
          All up to date
        </span>
      @endif
    </div>
  </div>
</div>

<!-- Detailed Reports Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
  <!-- Loan Trends Chart -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
      <i class="fas fa-chart-line text-accent"></i>
      Loan Trends (Last 7 Days)
    </h3>
    
    <div class="space-y-4">
      @php
        $loanTrends = [];
        for($i = 6; $i >= 0; $i--) {
          $date = now()->subDays($i);
          $loanTrends[] = [
            'date' => $date->format('M d'),
            'loans' => \App\Models\Loan::whereDate('loan_date', $date)->count(),
            'returns' => \App\Models\Loan::whereDate('returned_date', $date)->count()
          ];
        }
      @endphp
      
      @foreach($loanTrends as $trend)
        <div class="flex items-center justify-between">
          <span class="text-slate-400 text-sm">{{ $trend['date'] }}</span>
          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
              <span class="text-sm text-blue-400">{{ $trend['loans'] }} loans</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-green-400 rounded-full"></div>
              <span class="text-sm text-green-400">{{ $trend['returns'] }} returns</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Popular Books -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
      <i class="fas fa-star text-accent"></i>
      Most Popular Books
    </h3>
    
    <div class="space-y-4">
      @php
        $popularBooks = \App\Models\Book::withCount('loans')
          ->orderBy('loans_count', 'desc')
          ->limit(5)
          ->get();
      @endphp
      
      @foreach($popularBooks as $book)
        <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg">
          <div>
            <p class="text-white font-medium">{{ $book->title }}</p>
            <p class="text-slate-400 text-sm">by {{ $book->author }}</p>
          </div>
          <div class="text-right">
            <p class="text-accent font-bold">{{ $book->loans_count }}</p>
            <p class="text-slate-400 text-xs">loans</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="bg-card border border-slate-800 rounded-xl p-6">
  <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
    <i class="fas fa-clock text-accent"></i>
    Recent Activity
  </h3>
  
  <div class="space-y-4">
    @php
      $recentLoans = \App\Models\Loan::with(['book', 'member'])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    @endphp
    
    @foreach($recentLoans as $loan)
      <div class="flex items-center gap-4 p-3 bg-slate-800/30 rounded-lg">
        <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
          @if($loan->returned_date)
            <i class="fas fa-check text-green-400 text-sm"></i>
          @else
            <i class="fas fa-arrow-right text-blue-400 text-sm"></i>
          @endif
        </div>
        <div class="flex-1">
          <p class="text-white text-sm">
            <span class="font-medium">{{ $loan->member->name }}</span>
            @if($loan->returned_date)
              returned
            @else
              borrowed
            @endif
            <span class="font-medium">{{ $loan->book->title }}</span>
          </p>
          <p class="text-slate-400 text-xs">
            {{ $loan->returned_date ? $loan->returned_date->diffForHumans() : $loan->loan_date->diffForHumans() }}
          </p>
        </div>
        @if(!$loan->returned_date && $loan->due_date < now())
          <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">
            Overdue
          </span>
        @endif
      </div>
    @endforeach
  </div>
</div>

<!-- Export Options -->
<div class="mt-8 bg-card border border-slate-800 rounded-xl p-6">
  <h3 class="text-lg font-semibold text-white mb-4">
    <i class="fas fa-download text-accent mr-2"></i>
    Export Reports
  </h3>
  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <button onclick="exportReport('pdf')" 
            class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg transition-colors">
      <i class="fas fa-file-pdf"></i>
      Export as PDF
    </button>
    
    <button onclick="exportReport('excel')" 
            class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors">
      <i class="fas fa-file-excel"></i>
      Export as Excel
    </button>
    
    <button onclick="exportReport('csv')" 
            class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors">
      <i class="fas fa-file-csv"></i>
      Export as CSV
    </button>
  </div>
</div>

<script>
function generateReport() {
  const button = event.target;
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';

  const periodSelect = document.querySelector('#reportFilter select');
  const period = periodSelect ? periodSelect.value : 'month';

  fetch(`{{ route('reports.analytics') }}?period=${encodeURIComponent(period)}`, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
    .then(response => response.json())
    .then(() => {
      showNotification('Report generated successfully!', 'success');
    })
    .catch(() => {
      showNotification('Failed to generate report.', 'error');
    })
    .finally(() => {
      button.innerHTML = originalText;
    });
}

function exportReport(format) {
  const url = new URL('{{ route("reports.export") }}', window.location.origin);
  url.searchParams.set('type', 'loans');
  url.searchParams.set('format', format);

  window.location.href = url.toString();
}

function showNotification(message, type = 'info') {
  // This will be implemented in the layout for global notifications
  console.log(`${type}: ${message}`);
  
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
  
  switch(type) {
    case 'success':
      notification.className += ' bg-green-600 text-white';
      break;
    case 'error':
      notification.className += ' bg-red-600 text-white';
      break;
    default:
      notification.className += ' bg-blue-600 text-white';
  }
  
  notification.innerHTML = `
    <div class="flex items-center gap-3">
      <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
      <span>${message}</span>
    </div>
  `;
  
  document.body.appendChild(notification);
  
  // Animate in
  setTimeout(() => {
    notification.classList.remove('translate-x-full');
  }, 100);
  
  // Remove after 3 seconds
  setTimeout(() => {
    notification.classList.add('translate-x-full');
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}
</script>
@endsection

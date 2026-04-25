@extends('layout')

@section('title', 'Analytics')
@section('breadcrumb', 'Reports › Analytics')

@section('content')
<div class="space-y-6 sm:space-y-8 overflow-x-hidden" x-data="analytics">
  <!-- Page Header -->
  <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex items-start sm:items-center gap-3 min-w-0">
      <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-chart-line text-primary text-xl"></i>
      </div>
      <div class="min-w-0">
        <h1 class="text-xl sm:text-2xl font-bold text-white">Analytics Dashboard</h1>
        <p class="text-slate-400">Comprehensive library performance metrics</p>
      </div>
    </div>
    
    <!-- Time Period Selector -->
    <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-3 w-full lg:w-auto">
      <select x-model="selectedPeriod" @change="loadAnalytics()" 
              class="w-full px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary focus:outline-none">
        <option value="week">Last 7 Days</option>
        <option value="month" selected>Last Month</option>
        <option value="quarter">Last Quarter</option>
        <option value="year">Last Year</option>
      </select>
      
      <button @click="loadAnalytics()" 
              class="w-full sm:w-auto bg-primary hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-colors">
        <i class="fas fa-sync-alt mr-2"></i>Refresh
      </button>
    </div>
  </div>

  <!-- Loading State -->
  <div x-show="loading" class="text-center py-12">
    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
    <p class="text-slate-400 mt-4">Loading analytics...</p>
  </div>

  <!-- Analytics Content -->
  <div x-show="!loading" x-cloak>
    
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm">Total Loans</p>
            <p class="text-2xl font-bold text-white" x-text="monthlyStats.total_loans"></p>
          </div>
          <i class="fas fa-book-reader text-blue-400 text-xl"></i>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm">Total Returns</p>
            <p class="text-2xl font-bold text-white" x-text="monthlyStats.total_returns"></p>
          </div>
          <i class="fas fa-undo text-green-400 text-xl"></i>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm">Avg Loan Duration</p>
            <p class="text-2xl font-bold text-white">
              <span x-text="monthlyStats.average_loan_duration"></span> days
            </p>
          </div>
          <i class="fas fa-clock text-yellow-400 text-xl"></i>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm">Most Active Day</p>
            <p class="text-2xl font-bold text-white" x-text="monthlyStats.most_active_day"></p>
          </div>
          <i class="fas fa-calendar-day text-purple-400 text-xl"></i>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8">
      
      <!-- Loan Trends Chart -->
      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <h3 class="text-lg font-semibold text-white mb-4">
          <i class="fas fa-trending-up text-accent mr-2"></i>
          Loan Trends
        </h3>
        
        <div class="h-64 flex items-end justify-between gap-2">
          <template x-for="trend in loanTrends" :key="trend.date">
            <div class="flex flex-col items-center flex-1">
              <div class="w-full flex flex-col items-end gap-1 mb-2">
                <!-- Loans Bar -->
                <div class="w-full bg-blue-500 rounded-t" 
                     :style="{ height: Math.max(4, (trend.loans / maxLoans * 200)) + 'px' }"
                     :title="`${trend.loans} loans`"></div>
                <!-- Returns Bar -->
                <div class="w-full bg-green-500 rounded-b" 
                     :style="{ height: Math.max(4, (trend.returns / maxLoans * 200)) + 'px' }"
                     :title="`${trend.returns} returns`"></div>
              </div>
              <span class="text-xs text-slate-400 transform -rotate-45" x-text="trend.date"></span>
            </div>
          </template>
        </div>
        
        <div class="flex justify-center gap-6 mt-4 text-sm">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-blue-500 rounded"></div>
            <span class="text-slate-400">Loans</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-green-500 rounded"></div>
            <span class="text-slate-400">Returns</span>
          </div>
        </div>
      </div>

      <!-- Category Distribution -->
      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <h3 class="text-lg font-semibold text-white mb-4">
          <i class="fas fa-chart-pie text-accent mr-2"></i>
          Category Distribution
        </h3>
        
        <div class="space-y-3">
          <template x-for="category in categoryStats.slice(0, 6)" :key="category.id">
            <div class="flex items-center justify-between gap-3">
              <span class="text-slate-300 min-w-0 truncate" x-text="category.name"></span>
              <div class="flex items-center gap-3">
                <div class="w-20 bg-slate-700 rounded-full h-2">
                  <div class="bg-accent h-2 rounded-full" 
                       :style="{ width: (category.loans_count / maxCategoryLoans * 100) + '%' }"></div>
                </div>
                <span class="text-slate-400 text-sm w-8" x-text="category.loans_count"></span>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
      
      <!-- Popular Books -->
      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <h3 class="text-lg font-semibold text-white mb-4">
          <i class="fas fa-star text-accent mr-2"></i>
          Most Popular Books
        </h3>
        
        <div class="space-y-3">
          <template x-for="(book, index) in popularBooks.slice(0, 5)" :key="book.id">
            <div class="flex items-center justify-between gap-3 p-3 bg-slate-800/50 rounded-lg">
              <div class="flex items-center gap-3 min-w-0">
                <span class="w-6 h-6 bg-primary/20 rounded-full flex items-center justify-center text-primary text-sm font-bold" 
                      x-text="index + 1"></span>
                <div class="min-w-0">
                  <p class="text-white font-medium truncate" x-text="book.title"></p>
                  <p class="text-slate-400 text-sm truncate" x-text="book.author"></p>
                </div>
              </div>
              <span class="text-accent font-bold" x-text="book.loans_count"></span>
            </div>
          </template>
        </div>
      </div>

      <!-- Active Members -->
      <div class="bg-card border border-slate-800 rounded-xl p-5 sm:p-6">
        <h3 class="text-lg font-semibold text-white mb-4">
          <i class="fas fa-users text-accent mr-2"></i>
          Most Active Members
        </h3>
        
        <div class="space-y-3">
          <template x-for="(member, index) in activeMembers.slice(0, 5)" :key="member.id">
            <div class="flex items-center justify-between gap-3 p-3 bg-slate-800/50 rounded-lg">
              <div class="flex items-center gap-3 min-w-0">
                <span class="w-6 h-6 bg-green-500/20 rounded-full flex items-center justify-center text-green-400 text-sm font-bold" 
                      x-text="index + 1"></span>
                <div class="min-w-0">
                  <p class="text-white font-medium truncate" x-text="member.name"></p>
                  <p class="text-slate-400 text-sm truncate" x-text="member.email"></p>
                </div>
              </div>
              <span class="text-accent font-bold" x-text="member.loans_count"></span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('analytics', () => ({
    loading: true,
    selectedPeriod: 'month',
    loanTrends: [],
    popularBooks: [],
    categoryStats: [],
    activeMembers: [],
    monthlyStats: {},
    maxLoans: 0,
    maxCategoryLoans: 0,
    
    init() {
      this.loadAnalytics();
    },
    
    async loadAnalytics() {
      this.loading = true;
      
      try {
        const response = await fetch(`{{ route('reports.analytics') }}?period=${this.selectedPeriod}`);
        const result = await response.json();
        
        if (result.success) {
          const data = result.data;
          this.loanTrends = data.loan_trends;
          this.popularBooks = data.popular_books;
          this.categoryStats = data.category_stats;
          this.activeMembers = data.active_members;
          this.monthlyStats = data.monthly_stats;
          
          // Calculate max values for chart scaling
          this.maxLoans = Math.max(...this.loanTrends.map(t => Math.max(t.loans, t.returns)), 1);
          this.maxCategoryLoans = Math.max(...this.categoryStats.map(c => c.loans_count), 1);
        }
      } catch (error) {
        console.error('Failed to load analytics:', error);
      } finally {
        this.loading = false;
      }
    }
  }))
});
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection

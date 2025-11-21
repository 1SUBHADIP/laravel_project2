@extends('layout')

@section('title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
<!-- Welcome Message -->
<div class="bg-gradient-to-r from-primary/10 to-accent/10 border border-primary/20 rounded-xl p-6 mb-8">
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center">
      <i class="fas fa-chart-line text-white text-xl"></i>
    </div>
    <div>
      <h2 class="text-xl font-bold text-white">Welcome to CCLMS Dashboard</h2>
      <p class="text-slate-300">Here's an overview of your library management system</p>
    </div>
  </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
  <!-- Total Books -->
  <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-slate-400 text-sm font-medium">Total Books</p>
        <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['books']) }}</p>
      </div>
      <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-book text-blue-400 text-xl"></i>
      </div>
    </div>
    <div class="mt-4 flex items-center text-sm">
      <span class="text-green-400">
        <i class="fas fa-arrow-up mr-1"></i>
        +2.5%
      </span>
      <span class="text-slate-400 ml-1">from last month</span>
    </div>
  </div>

  <!-- Total Members -->
  <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-slate-400 text-sm font-medium">Total Members</p>
        <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['members']) }}</p>
      </div>
      <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-users text-green-400 text-xl"></i>
      </div>
    </div>
    <div class="mt-4 flex items-center text-sm">
      <span class="text-green-400">
        <i class="fas fa-arrow-up mr-1"></i>
        +5.1%
      </span>
      <span class="text-slate-400 ml-1">from last month</span>
    </div>
  </div>

  <!-- Active Loans -->
  <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-slate-400 text-sm font-medium">Active Loans</p>
        <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['active_loans']) }}</p>
      </div>
      <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exchange-alt text-yellow-400 text-xl"></i>
      </div>
    </div>
    <div class="mt-4 flex items-center text-sm">
      <span class="text-yellow-400">
        <i class="fas fa-arrow-right mr-1"></i>
        Stable
      </span>
      <span class="text-slate-400 ml-1">this week</span>
    </div>
  </div>

  <!-- Overdue Loans -->
  <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-slate-400 text-sm font-medium">Overdue Loans</p>
        <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['overdue_loans']) }}</p>
      </div>
      <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
      </div>
    </div>
    <div class="mt-4 flex items-center text-sm">
      @if($counts['overdue_loans'] > 0)
        <span class="text-red-400">
          <i class="fas fa-exclamation-circle mr-1"></i>
          Needs attention
        </span>
      @else
        <span class="text-green-400">
          <i class="fas fa-check-circle mr-1"></i>
          All good
        </span>
      @endif
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
  <!-- Quick Actions Card -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
      <i class="fas fa-bolt text-accent"></i>
      Quick Actions
    </h3>
    <div class="space-y-3">
      <a href="{{ route('books.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
          <i class="fas fa-plus text-blue-400"></i>
        </div>
        <div>
          <p class="text-white font-medium">Add New Book</p>
          <p class="text-slate-400 text-sm">Add a book to the library</p>
        </div>
      </a>
      
      <a href="{{ route('members.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
        <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition-colors">
          <i class="fas fa-user-plus text-green-400"></i>
        </div>
        <div>
          <p class="text-white font-medium">Register Member</p>
          <p class="text-slate-400 text-sm">Add a new library member</p>
        </div>
      </a>
      
      <a href="{{ route('loans.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
        <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center group-hover:bg-yellow-500/30 transition-colors">
          <i class="fas fa-hand-holding text-yellow-400"></i>
        </div>
        <div>
          <p class="text-white font-medium">Issue Book</p>
          <p class="text-slate-400 text-sm">Create a new book loan</p>
        </div>
      </a>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
      <i class="fas fa-clock text-accent"></i>
      System Status
    </h3>
    <div class="space-y-4">
      <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 bg-green-400 rounded-full"></div>
          <span class="text-slate-300">Database Connection</span>
        </div>
        <span class="text-green-400 text-sm font-medium">Active</span>
      </div>
      
      <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 bg-green-400 rounded-full"></div>
          <span class="text-slate-300">System Performance</span>
        </div>
        <span class="text-green-400 text-sm font-medium">Optimal</span>
      </div>
      
      <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
          <span class="text-slate-300">Backup Status</span>
        </div>
        <span class="text-yellow-400 text-sm font-medium">Scheduled</span>
      </div>
      
      <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
        <div class="flex items-center gap-3">
          <div class="w-2 h-2 bg-green-400 rounded-full"></div>
          <span class="text-slate-300">Security</span>
        </div>
        <span class="text-green-400 text-sm font-medium">Secure</span>
      </div>
    </div>
  </div>
</div>

<!-- Additional Info -->
@if($counts['overdue_loans'] > 0)
<div class="bg-red-900/20 border border-red-700 rounded-xl p-6">
  <div class="flex items-center gap-3 mb-3">
    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
    <h3 class="text-lg font-semibold text-red-300">Attention Required</h3>
  </div>
  <p class="text-red-200 mb-4">
    You have {{ $counts['overdue_loans'] }} overdue loan(s) that need immediate attention.
  </p>
  <a href="{{ route('loans.index') }}?filter=overdue" 
     class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
    <i class="fas fa-eye"></i>
    View Overdue Loans
  </a>
</div>
@endif
@endsection



@extends('layout')

@section('title', 'Overdue Items')
@section('breadcrumb', 'Reports › Overdue Items')

@section('content')
<!-- Header -->
<div class="bg-linear-to-r from-red-500/10 to-orange-500/10 border border-red-500/20 rounded-xl p-6 mb-8">
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-linear-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center">
        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
      </div>
      <div>
        <h2 class="text-xl font-bold text-white">Overdue Items</h2>
        <p class="text-slate-300">Items that have passed their due date</p>
      </div>
    </div>
    <div class="text-right">
      <div class="text-3xl font-bold text-red-400">{{ $overdueLoans->total() }}</div>
      <div class="text-slate-400 text-sm">overdue items</div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="bg-card border border-slate-800 rounded-xl p-6 mb-8">
  <h3 class="text-lg font-semibold text-white mb-4">
    <i class="fas fa-filter text-accent mr-2"></i>
    Filter Overdue Items
  </h3>
  
  <form id="overdueFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Days Overdue</label>
      <select name="days_overdue" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="">All Overdue</option>
        <option value="1">1+ days</option>
        <option value="3">3+ days</option>
        <option value="7">1+ week</option>
        <option value="14">2+ weeks</option>
        <option value="30">1+ month</option>
      </select>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Category</label>
      <select name="category_id" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="">All Categories</option>
        @foreach(\App\Models\Category::orderBy('name')->get() as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-slate-300 mb-2">Sort By</label>
      <select name="sort" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white focus:border-primary">
        <option value="due_date">Due Date</option>
        <option value="member_name">Member Name</option>
        <option value="book_title">Book Title</option>
        <option value="days_overdue">Days Overdue</option>
      </select>
    </div>
    
    <div class="flex items-end">
      <button type="button" onclick="applyFilters()" 
              class="w-full bg-primary hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition-colors">
        <i class="fas fa-search mr-2"></i>Apply Filters
      </button>
    </div>
  </form>
</div>

<!-- Overdue Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-clock text-yellow-400"></i>
      </div>
      <div>
        <div class="text-xl font-bold text-white">{{ $overdueLoans->where('days_overdue', '<=', 7)->count() }}</div>
        <div class="text-slate-400 text-xs">1-7 days</div>
      </div>
    </div>
  </div>
  
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exclamation text-orange-400"></i>
      </div>
      <div>
        <div class="text-xl font-bold text-white">{{ $overdueLoans->where('days_overdue', '>', 7)->where('days_overdue', '<=', 14)->count() }}</div>
        <div class="text-slate-400 text-xs">1-2 weeks</div>
      </div>
    </div>
  </div>
  
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-exclamation-triangle text-red-400"></i>
      </div>
      <div>
        <div class="text-xl font-bold text-white">{{ $overdueLoans->where('days_overdue', '>', 14)->where('days_overdue', '<=', 30)->count() }}</div>
        <div class="text-slate-400 text-xs">2-4 weeks</div>
      </div>
    </div>
  </div>
  
  <div class="bg-card border border-slate-800 rounded-xl p-6">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
        <i class="fas fa-ban text-purple-400"></i>
      </div>
      <div>
        <div class="text-xl font-bold text-white">{{ $overdueLoans->where('days_overdue', '>', 30)->count() }}</div>
        <div class="text-slate-400 text-xs">1+ month</div>
      </div>
    </div>
  </div>
</div>

<!-- Overdue Items Table -->
<div class="bg-card border border-slate-800 rounded-xl p-6">
  <div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-white">
      <i class="fas fa-list text-accent mr-2"></i>
      Overdue Items List
    </h3>
    <div class="flex items-center gap-3">
      <button type="button" onclick="sendReminders(this)" 
              class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
        <i class="fas fa-envelope mr-2"></i>Send Reminders
      </button>
      <button type="button" onclick="exportOverdue(this)" 
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
        <i class="fas fa-download mr-2"></i>Export
      </button>
    </div>
  </div>
  
  @if($overdueLoans->count() > 0)
    <div class="overflow-x-auto">
      <table class="min-w-[1200px] w-full">
        <thead>
          <tr class="border-b border-slate-700">
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Member</th>
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Book</th>
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Due Date</th>
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Days Overdue</th>
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Contact</th>
            <th class="text-left py-3 px-4 text-slate-300 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($overdueLoans as $loan)
            <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-primary text-sm"></i>
                  </div>
                  <div>
                    <div class="text-white font-medium">{{ $loan->member->name }}</div>
                    <div class="text-slate-400 text-sm">ID: {{ $loan->member->id }}</div>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4">
                <div>
                  <div class="text-white font-medium">{{ $loan->book->title }}</div>
                  <div class="text-slate-400 text-sm">by {{ $loan->book->author }}</div>
                  @if($loan->book->category)
                    <span class="inline-block px-2 py-1 bg-accent/20 text-accent text-xs rounded-full mt-1">
                      {{ $loan->book->category->name }}
                    </span>
                  @endif
                </div>
              </td>
              <td class="py-4 px-4">
                <div class="text-white">{{ $loan->due_date->format('M d, Y') }}</div>
                <div class="text-slate-400 text-sm">{{ $loan->due_date->diffForHumans() }}</div>
              </td>
              <td class="py-4 px-4">
                @php
                  $daysOverdue = $loan->days_overdue;
                  $severity = $daysOverdue <= 7 ? 'yellow' : ($daysOverdue <= 14 ? 'orange' : 'red');
                @endphp
                <span class="px-3 py-1 bg-{{ $severity }}-500/20 text-{{ $severity }}-300 text-sm rounded-full font-medium">
                  {{ $daysOverdue }} day{{ $daysOverdue > 1 ? 's' : '' }}
                </span>
              </td>
              <td class="py-4 px-4">
                <div class="text-slate-300 text-sm">{{ $loan->member->email }}</div>
                @if($loan->member->phone)
                  <div class="text-slate-400 text-sm">{{ $loan->member->phone }}</div>
                @endif
                @if($loan->last_reminder_sent)
                  <div class="text-xs text-blue-400 mt-1">
                    <i class="fas fa-bell mr-1"></i>
                    Last reminder: {{ \Carbon\Carbon::parse($loan->last_reminder_sent)->diffForHumans() }}
                    @if($loan->reminder_count > 1)
                      ({{ $loan->reminder_count }} sent)
                    @endif
                  </div>
                @endif
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center gap-2">
                  <button type="button" onclick="sendReminder({{ $loan->id }}, this)" 
                          class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                    <i class="fas fa-envelope mr-1"></i>Remind
                  </button>
                  <button type="button" onclick="markReturned({{ $loan->id }}, this)" 
                          class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                    <i class="fas fa-check mr-1"></i>Return
                  </button>
                  <a href="{{ route('members.edit', $loan->member) }}" 
                     class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1 rounded text-xs transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
      {{ $overdueLoans->links() }}
    </div>
  @else
    <div class="text-center py-12">
      <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">No Overdue Items!</h3>
      <p class="text-slate-400">All loans are returned on time or still within their due date.</p>
    </div>
  @endif
</div>

<script>
function applyFilters() {
  const form = document.getElementById('overdueFilters');
  const formData = new FormData(form);
  const params = new URLSearchParams(formData);
  
  // Reload page with filters
  window.location.href = '{{ route("reports.overdue") }}?' + params.toString();
}

function sendReminder(loanId, button) {
  if (confirm('Send reminder email and SMS to member?')) {
    button = button || null;
    if (!button) {
      return;
    }

    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Sending...';
    button.disabled = true;
    
    // Send reminder via AJAX
    fetch(`/reminders/send/${loanId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(async response => {
      const payload = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(payload.message || 'Failed to send reminder');
      }

      return payload;
    })
    .then(data => {
      button.innerHTML = originalText;
      button.disabled = false;
      
      if (data.success) {
        showNotification(data.message, 'success');
        setTimeout(() => window.location.reload(), 800);
      } else {
        showNotification(data.message || 'Failed to send reminder', 'error');
      }
    })
    .catch(error => {
      button.innerHTML = originalText;
      button.disabled = false;
      showNotification('Error sending reminder: ' + error.message, 'error');
    });
  }
}

function sendReminders(button) {
  if (confirm('Send reminder emails and SMS to all members with overdue items?')) {
    button = button || null;
    if (!button) {
      return;
    }

    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
    button.disabled = true;
    
    // Send bulk reminders via AJAX
    fetch('/reminders/send-all', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(async response => {
      const payload = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(payload.message || 'Failed to send reminders');
      }

      return payload;
    })
    .then(data => {
      button.innerHTML = originalText;
      button.disabled = false;
      
      if (data.success) {
        showNotification(data.message, 'success');
        // Optionally reload the page to show updated reminder counts
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      } else {
        showNotification(data.message || 'Failed to send reminders', 'error');
      }
    })
    .catch(error => {
      button.innerHTML = originalText;
      button.disabled = false;
      showNotification('Error sending reminders: ' + error.message, 'error');
    });
  }
}

function markReturned(loanId, button) {
  if (confirm('Mark this item as returned?')) {
    // This would typically make an AJAX call to update the loan
    fetch(`/loans/${loanId}/return`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(async response => {
      const payload = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(payload.message || 'Error processing request.');
      }

      return payload;
    })
    .then(data => {
      if (data.success) {
        showNotification('Item marked as returned!', 'success');
        // Reload page to update the list
        setTimeout(() => window.location.reload(), 1500);
      } else {
        showNotification('Error marking item as returned.', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Error processing request.', 'error');
    });
  }
}

function exportOverdue(button) {
  button = button || null;
  if (!button) {
    return;
  }

  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
  button.disabled = true;
  
  // Create download link
  const link = document.createElement('a');
  link.href = '{{ route("reports.export") }}?type=overdue&format=csv';
  link.download = 'overdue_items_' + new Date().toISOString().split('T')[0] + '.csv';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  
  setTimeout(() => {
    button.innerHTML = originalText;
    button.disabled = false;
    showNotification('Overdue items exported successfully!', 'success');
  }, 1000);
}

function showNotification(message, type = 'info') {
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

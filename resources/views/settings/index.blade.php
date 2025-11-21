@extends('layout')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex items-center gap-3 mb-8">
    <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
      <i class="fas fa-cogs text-primary text-xl"></i>
    </div>
    <div>
      <h1 class="text-2xl font-bold text-white">System Settings</h1>
      <p class="text-slate-400">Manage library system configuration and preferences</p>
    </div>
  </div>

  <!-- Settings Tabs -->
  <div class="bg-card border border-slate-800 rounded-xl overflow-hidden" x-data="{ activeTab: 'general' }">
    <!-- Tab Navigation -->
    <div class="border-b border-slate-800">
      <nav class="flex space-x-8 px-6">
        <button @click="activeTab = 'general'" 
                :class="{ 'border-primary text-primary': activeTab === 'general', 'border-transparent text-slate-400 hover:text-slate-300': activeTab !== 'general' }"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          <i class="fas fa-sliders-h mr-2"></i>General
        </button>
        <button @click="activeTab = 'profile'" 
                :class="{ 'border-primary text-primary': activeTab === 'profile', 'border-transparent text-slate-400 hover:text-slate-300': activeTab !== 'profile' }"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          <i class="fas fa-user-cog mr-2"></i>Profile
        </button>
        <button @click="activeTab = 'system'" 
                :class="{ 'border-primary text-primary': activeTab === 'system', 'border-transparent text-slate-400 hover:text-slate-300': activeTab !== 'system' }"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          <i class="fas fa-server mr-2"></i>System
        </button>
        <button @click="activeTab = 'maintenance'" 
                :class="{ 'border-primary text-primary': activeTab === 'maintenance', 'border-transparent text-slate-400 hover:text-slate-300': activeTab !== 'maintenance' }"
                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          <i class="fas fa-tools mr-2"></i>Maintenance
        </button>
      </nav>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
      
      <!-- General Settings Tab -->
      <div x-show="activeTab === 'general'" x-transition>
        <form action="{{ route('settings.system') }}" method="POST" class="space-y-6">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Application Settings -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-white mb-4">Application Settings</h3>
              
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Application Name</label>
                <input type="text" name="app_name" 
                       value="{{ session('library_settings.app_name', 'CCLMS Library Management System') }}"
                       class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Default Loan Duration (days)</label>
                <input type="number" name="loan_duration" min="1" max="365"
                       value="{{ session('library_settings.loan_duration', 14) }}"
                       class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Max Books per Member</label>
                <input type="number" name="max_books_per_member" min="1" max="50"
                       value="{{ session('library_settings.max_books_per_member', 5) }}"
                       class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Late Fee per Day ($)</label>
                <input type="number" name="late_fee_per_day" min="0" step="0.01"
                       value="{{ session('library_settings.late_fee_per_day', 0.50) }}"
                       class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
              </div>
            </div>

            <!-- Notification Settings -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-white mb-4">Notification Settings</h3>
              
              <div class="space-y-3">
                <label class="flex items-center">
                  <input type="checkbox" name="email_notifications" value="1"
                         {{ session('library_settings.email_notifications', true) ? 'checked' : '' }}
                         class="w-4 h-4 text-primary bg-slate-800 border-slate-600 rounded focus:ring-primary focus:ring-2">
                  <span class="ml-3 text-sm text-slate-300">Enable Email Notifications</span>
                </label>

                <label class="flex items-center">
                  <input type="checkbox" name="sms_notifications" value="1"
                         {{ session('library_settings.sms_notifications', false) ? 'checked' : '' }}
                         class="w-4 h-4 text-primary bg-slate-800 border-slate-600 rounded focus:ring-primary focus:ring-2">
                  <span class="ml-3 text-sm text-slate-300">Enable SMS Notifications</span>
                </label>
              </div>

              <div class="mt-6 p-4 bg-slate-800/50 rounded-lg">
                <h4 class="font-medium text-white mb-2">System Information</h4>
                <div class="space-y-1 text-sm text-slate-400">
                  <p>App Version: {{ $systemInfo['app_version'] ?? '1.0.0' }}</p>
                  <p>Laravel Version: {{ $systemInfo['laravel_version'] ?? 'Laravel 12' }}</p>
                  <p>PHP Version: {{ $systemInfo['php_version'] ?? phpversion() }}</p>
                  <p>Database: {{ $systemInfo['database_name'] ?? 'SQLite' }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-primary hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
              <i class="fas fa-save mr-2"></i>Save Settings
            </button>
          </div>
        </form>
      </div>

      <!-- Profile Settings Tab -->
      <div x-show="activeTab === 'profile'" x-transition>
        <!-- Display Success/Error Messages -->
        @if(session('success'))
          <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-400 mr-3"></i>
              <span class="text-green-100">{{ session('success') }}</span>
            </div>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg">
            <div class="flex items-center">
              <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
              <span class="text-red-100">{{ session('error') }}</span>
            </div>
          </div>
        @endif

        @if($errors->any())
          <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg">
            <div class="flex items-center mb-2">
              <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
              <span class="text-red-100 font-medium">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-red-100 text-sm space-y-1">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('settings.profile') }}" method="POST" class="space-y-6">
          @csrf
          <div class="max-w-md space-y-4">
            <h3 class="text-lg font-semibold text-white mb-4">Administrator Profile</h3>
            
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
              <input type="text" name="name" 
                     value="{{ old('name', $currentUser->name ?? session('admin_name', 'Administrator')) }}"
                     class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('name') border-red-500 @enderror">
              @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
              <input type="email" name="email" 
                     value="{{ old('email', $currentUser->email ?? session('admin_email', 'admin@library.com')) }}"
                     class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('email') border-red-500 @enderror">
              @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="pt-4 border-t border-slate-700">
              <h4 class="text-md font-medium text-white mb-4">Change Password</h4>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Current Password</label>
                  <input type="password" name="current_password"
                         class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('current_password') border-red-500 @enderror">
                  @error('current_password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">New Password</label>
                  <input type="password" name="new_password"
                         class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none @error('new_password') border-red-500 @enderror">
                  @error('new_password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                  @enderror
                  <p class="mt-1 text-xs text-slate-400">Password must be at least 8 characters long</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Confirm New Password</label>
                  <input type="password" name="new_password_confirmation"
                         class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-user-check mr-2"></i>Update Profile
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- System Tab -->
      <div x-show="activeTab === 'system'" x-transition>
        <div class="space-y-6">
          <h3 class="text-lg font-semibold text-white mb-4">System Information</h3>
          
          <!-- System Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-slate-400 text-sm">Total Books</p>
                  <p class="text-2xl font-bold text-white">{{ $systemInfo['total_records']['books'] ?? '0' }}</p>
                </div>
                <i class="fas fa-book text-blue-400 text-xl"></i>
              </div>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-slate-400 text-sm">Total Members</p>
                  <p class="text-2xl font-bold text-white">{{ $systemInfo['total_records']['members'] ?? '0' }}</p>
                </div>
                <i class="fas fa-users text-green-400 text-xl"></i>
              </div>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-slate-400 text-sm">Total Loans</p>
                  <p class="text-2xl font-bold text-white">{{ $systemInfo['total_records']['loans'] ?? '0' }}</p>
                </div>
                <i class="fas fa-exchange-alt text-yellow-400 text-xl"></i>
              </div>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-slate-400 text-sm">Categories</p>
                  <p class="text-2xl font-bold text-white">{{ $systemInfo['total_records']['categories'] ?? '0' }}</p>
                </div>
                <i class="fas fa-tags text-purple-400 text-xl"></i>
              </div>
            </div>
          </div>

          <!-- System Actions -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-slate-800/30 rounded-lg p-6">
              <h4 class="font-semibold text-white mb-4">Database Connection</h4>
              <p class="text-slate-400 text-sm mb-4">Test the database connection and view table information.</p>
              <button onclick="testDatabase()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-database mr-2"></i>Test Connection
              </button>
            </div>

            <div class="bg-slate-800/30 rounded-lg p-6">
              <h4 class="font-semibold text-white mb-4">System Logs</h4>
              <p class="text-slate-400 text-sm mb-4">View recent system logs and error messages.</p>
              <button onclick="viewLogs()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-file-alt mr-2"></i>View Logs
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Maintenance Tab -->
      <div x-show="activeTab === 'maintenance'" x-transition>
        <div class="space-y-6">
          <h3 class="text-lg font-semibold text-white mb-4">System Maintenance</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Cache Management -->
            <div class="bg-slate-800/30 rounded-lg p-6">
              <h4 class="font-semibold text-white mb-4">
                <i class="fas fa-broom mr-2 text-yellow-400"></i>Clear System Cache
              </h4>
              <p class="text-slate-400 text-sm mb-4">Clear application cache, configuration cache, route cache, and view cache.</p>
              <button onclick="clearCache()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-trash mr-2"></i>Clear Cache
              </button>
            </div>

            <!-- Database Backup -->
            <div class="bg-slate-800/30 rounded-lg p-6">
              <h4 class="font-semibold text-white mb-4">
                <i class="fas fa-download mr-2 text-green-400"></i>Database Backup
              </h4>
              <p class="text-slate-400 text-sm mb-4">Generate a backup of your library database.</p>
              <button onclick="generateBackup()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-database mr-2"></i>Create Backup
              </button>
            </div>
          </div>

          <!-- Maintenance Log -->
          <div class="bg-slate-800/30 rounded-lg p-6">
            <h4 class="font-semibold text-white mb-4">Recent Maintenance Activities</h4>
            <div id="maintenanceLog" class="space-y-2 text-sm text-slate-400">
              <p>No recent activities</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Notification Toast -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
  <div class="bg-slate-800 border border-slate-700 rounded-lg p-4 shadow-xl max-w-sm">
    <div class="flex items-center gap-3">
      <div id="toastIcon" class="flex-shrink-0"></div>
      <div>
        <p id="toastTitle" class="font-medium text-white"></p>
        <p id="toastMessage" class="text-sm text-slate-400"></p>
      </div>
    </div>
  </div>
</div>

<script>
// Settings page functionality
function showToast(title, message, type = 'success') {
  const toast = document.getElementById('toast');
  const toastIcon = document.getElementById('toastIcon');
  const toastTitle = document.getElementById('toastTitle');
  const toastMessage = document.getElementById('toastMessage');
  
  const icons = {
    success: '<i class="fas fa-check-circle text-green-400"></i>',
    error: '<i class="fas fa-exclamation-circle text-red-400"></i>',
    info: '<i class="fas fa-info-circle text-blue-400"></i>'
  };
  
  toastIcon.innerHTML = icons[type];
  toastTitle.textContent = title;
  toastMessage.textContent = message;
  
  toast.classList.remove('hidden');
  
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 5000);
}

function addMaintenanceLog(activity) {
  const log = document.getElementById('maintenanceLog');
  const timestamp = new Date().toLocaleString();
  const logEntry = document.createElement('p');
  logEntry.innerHTML = '<span class="text-slate-500">[' + timestamp + ']</span> ' + activity;
  log.prepend(logEntry);
}

async function clearCache() {
  try {
    const response = await fetch('{{ route("settings.cache") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Success', data.message, 'success');
      addMaintenanceLog('System cache cleared');
    } else {
      showToast('Error', data.message, 'error');
    }
  } catch (error) {
    showToast('Error', 'Failed to clear cache', 'error');
  }
}

async function generateBackup() {
  try {
    const response = await fetch('{{ route("settings.backup") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Success', data.message, 'success');
      addMaintenanceLog('Database backup created: ' + data.filename);
    } else {
      showToast('Error', data.message, 'error');
    }
  } catch (error) {
    showToast('Error', 'Failed to generate backup', 'error');
  }
}

async function testDatabase() {
  try {
    const response = await fetch('{{ route("settings.database") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Success', data.message, 'success');
      addMaintenanceLog('Database connection test completed successfully');
    } else {
      showToast('Error', data.message, 'error');
    }
  } catch (error) {
    showToast('Error', 'Failed to test database connection', 'error');
  }
}

async function viewLogs() {
  try {
    const response = await fetch('{{ route("settings.logs") }}', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Info', 'Found ' + data.logs.length + ' recent log entries', 'info');
      addMaintenanceLog('System logs retrieved');
    } else {
      showToast('Error', data.message, 'error');
    }
  } catch (error) {
    showToast('Error', 'Failed to retrieve logs', 'error');
  }
}
</script>
@endsection

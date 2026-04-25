
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - CCLMS Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/cclms.css') }}">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: {
                DEFAULT: '#1f6feb',
                600: '#1a5bcc',
                700: '#144a9e'
              },
              bg: '#0d1117',
              card: '#161b22',
              accent: '#39d353',
              sidebar: '#0a0e16'
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen bg-bg text-slate-200">
  <!-- Top Navigation -->
  <nav class="fixed top-0 left-0 right-0 z-50 border-b border-slate-800 bg-card/95 backdrop-blur">
    <div class="px-4">
      <div class="flex h-16 items-center justify-between">
        <!-- Logo and Brand -->
        <div class="flex items-center gap-4">
          <button id="sidebarToggle" type="button" aria-label="Toggle sidebar" aria-expanded="false" class="p-2 rounded-md hover:bg-slate-700 transition-colors">
            <span class="hamburger-line block w-5 h-0.5 bg-slate-300 rounded transition-all duration-300"></span>
            <span class="hamburger-line block w-5 h-0.5 bg-slate-300 rounded mt-1 transition-all duration-300"></span>
            <span class="hamburger-line block w-5 h-0.5 bg-slate-300 rounded mt-1 transition-all duration-300"></span>
          </button>
          <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-linear-to-br from-primary to-accent rounded-lg flex items-center justify-center">
              <i class="fas fa-book text-white text-sm"></i>
            </div>
            <span class="font-bold text-xl tracking-wide text-white hidden sm:block">CCLMS</span>
            <span class="hidden md:block text-slate-300 text-sm">Library Management System</span>
          </a>
        </div>

        <!-- Search Bar -->
        <div class="hidden md:flex flex-1 max-w-md mx-8">
          <div class="relative w-full" x-data="{ searchOpen: false, searchQuery: '' }">
            <form action="{{ route('search') }}" method="GET" class="relative">
              <input type="text" 
                     name="q"
                     x-model="searchQuery"
                     @focus="searchOpen = true"
                     @keydown.escape="searchOpen = false"
                     placeholder="Search books, members, loans..." 
                     class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 pl-10 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
              <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-white">
                <i class="fas fa-search text-sm"></i>
              </button>
            </form>
            
            <!-- Search Results Dropdown -->
            <div x-show="searchOpen && searchQuery.length > 2" 
                 @click.away="searchOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute top-full mt-1 w-full bg-card border border-slate-700 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
              
              <div class="p-4">
                <h4 class="text-sm font-medium text-slate-300 mb-3">Quick Search Results</h4>
                
                <!-- Books Section -->
                <div class="mb-4">
                  <h5 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Books</h5>
                  <div class="space-y-2">
                    <a href="#" class="block p-2 hover:bg-slate-700 rounded-lg transition-colors">
                      <div class="flex items-center gap-3">
                        <i class="fas fa-book text-blue-400"></i>
                        <div>
                          <p class="text-sm text-white">The Great Gatsby</p>
                          <p class="text-xs text-slate-400">by F. Scott Fitzgerald</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                
                <!-- Members Section -->
                <div class="mb-4">
                  <h5 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Members</h5>
                  <div class="space-y-2">
                    <a href="#" class="block p-2 hover:bg-slate-700 rounded-lg transition-colors">
                      <div class="flex items-center gap-3">
                        <i class="fas fa-user text-green-400"></i>
                        <div>
                          <p class="text-sm text-white">John Smith</p>
                          <p class="text-xs text-slate-400">john@example.com</p>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                
                <div class="border-t border-slate-700 pt-3">
                  <button type="submit" form="searchForm" class="w-full text-left p-2 hover:bg-slate-700 rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                      <i class="fas fa-search text-accent"></i>
                      <span class="text-sm text-accent">View all results for "<span x-text="searchQuery"></span>"</span>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- User Menu -->
        <div class="flex items-center gap-4">
          <!-- Notifications -->
          <div class="relative" x-data="notifications">
            <button @click="toggleDropdown()" class="relative p-2 rounded-md hover:bg-slate-700 transition-colors">
              <i class="fas fa-bell text-slate-300"></i>
              <span x-show="unreadCount > 0" 
                    x-text="unreadCount" 
                    class="absolute -top-1 -right-1 min-w-4 h-4 bg-red-500 rounded-full text-xs flex items-center justify-center text-white px-1"></span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-card border border-slate-700 rounded-lg shadow-xl z-50 max-h-96 overflow-hidden">
              
              <!-- Header -->
              <div class="px-4 py-3 border-b border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Notifications</h3>
                <button @click="markAllAsRead()" 
                        x-show="unreadCount > 0"
                        class="text-xs text-accent hover:text-accent-light">
                  Mark all read
                </button>
              </div>
              
              <!-- Notifications List -->
              <div class="max-h-64 overflow-y-auto">
                <div x-show="notifications.length === 0" class="p-4 text-center text-slate-400">
                  <i class="fas fa-bell-slash text-2xl mb-2"></i>
                  <p class="text-sm">No notifications</p>
                </div>
                
                <template x-for="notification in notifications" :key="notification.id">
                  <div @click="markAsRead(notification.id)" 
                       class="px-4 py-3 border-b border-slate-800 hover:bg-slate-800/50 cursor-pointer transition-colors"
                       :class="{ 'bg-slate-800/30': !notification.read }">
                    <div class="flex items-start gap-3">
                      <div class="shrink-0 mt-1">
                        <i :class="notification.icon + ' ' + notification.color"></i>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white" x-text="notification.title"></p>
                        <p class="text-xs text-slate-400 mt-1" x-text="notification.message"></p>
                        <p class="text-xs text-slate-500 mt-1" x-text="notification.time"></p>
                      </div>
                      <div x-show="!notification.read" class="w-2 h-2 bg-accent rounded-full shrink-0 mt-2"></div>
                    </div>
                  </div>
                </template>
              </div>
              
              <!-- Footer -->
              <div class="px-4 py-3 border-t border-slate-700 bg-slate-800/30 flex items-center justify-between">
                <a href="{{ route('notifications.view-all') }}" class="text-xs text-accent hover:text-accent-light">View all notifications</a>
                <button @click="clearAllNotifications()" 
                        x-show="notifications.length > 0"
                        class="text-xs text-red-400 hover:text-red-300 transition-colors">
                  <i class="fas fa-trash mr-1"></i>Clear All
                </button>
              </div>
            </div>
          </div>
          
          <!-- User Profile -->
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-md hover:bg-slate-700 transition-colors">
              <div class="w-8 h-8 bg-linear-to-br from-primary to-accent rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
              </div>
              <span class="hidden sm:block text-sm text-slate-300">{{ session('admin_name', 'Admin') }}</span>
              <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-card border border-slate-700 rounded-lg shadow-xl z-50">
              
              <!-- User Info -->
              <div class="px-4 py-3 border-b border-slate-700">
                <p class="text-sm font-medium text-white">{{ session('admin_name', 'Admin') }}</p>
                <p class="text-xs text-slate-400">Administrator</p>
              </div>
              
              <!-- Menu Items -->
              <div class="py-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors">
                  <i class="fas fa-home text-slate-400"></i>
                  Dashboard
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors">
                  <i class="fas fa-cog text-slate-400"></i>
                  Settings
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors">
                  <i class="fas fa-chart-bar text-slate-400"></i>
                  Reports
                </a>
                
                <!-- Divider -->
                <div class="border-t border-slate-700 my-2"></div>
                
                <!-- Logout -->
                <form action="{{ route('admin.logout') }}" method="POST" class="block">
                  @csrf
                  <button type="submit" 
                          class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-300 hover:bg-red-900/20 transition-colors text-left"
                          onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt text-red-400"></i>
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] overflow-y-auto bg-sidebar border-r border-slate-800 transform -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="px-4 py-6 pb-10">
      <!-- Quick Stats -->
      <div class="mb-6">
        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Quick Overview</h3>
        <div class="space-y-2">
          <div class="bg-card/50 rounded-lg p-3 border border-slate-800">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-300">Total Books</span>
              <span class="text-lg font-bold text-accent">{{ \App\Models\Book::count() }}</span>
            </div>
          </div>
          <div class="bg-card/50 rounded-lg p-3 border border-slate-800">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-300">Active Loans</span>
              <span class="text-lg font-bold text-yellow-400">{{ \App\Models\Loan::whereNull('returned_date')->count() }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="space-y-2">
        <div>
          <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Main Menu</h3>
          <ul class="space-y-1">
            <li>
              <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('dashboard') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('books.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-book w-5"></i>
                <span>Books</span>
              </a>
            </li>
            <li>
              <a href="{{ route('members.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('members.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-users w-5"></i>
                <span>Members</span>
              </a>
            </li>
            <li>
              <a href="{{ route('loans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('loans.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-exchange-alt w-5"></i>
                <span>Loans</span>
              </a>
            </li>
            <li>
              <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('categories.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-tags w-5"></i>
                <span>Categories</span>
              </a>
            </li>
            <li>
              <a href="{{ route('departments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('departments.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-building w-5"></i>
                <span>Departments</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="pt-4">
          <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Reports & Analytics</h3>
          <ul class="space-y-1">
            <li>
              <a href="{{ route('reports.analytics') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('reports.analytics') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span>Analytics</span>
              </a>
            </li>
            <li>
              <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('reports.index') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span>Reports</span>
              </a>
            </li>
            <li>
              <a href="{{ route('reports.overdue') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('reports.overdue') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-exclamation-triangle w-5"></i>
                <span>Overdue Items</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="pt-4 border-t border-slate-700">
          <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Account</h3>
          <ul class="space-y-1">
            <li>
              <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg border-l-2 transition-colors {{ request()->routeIs('settings.*') ? 'border-primary bg-primary/20 text-primary' : 'border-transparent text-slate-300 hover:bg-slate-700 hover:border-slate-600' }}">
                <i class="fas fa-cog w-5"></i>
                <span>Settings</span>
              </a>
            </li>
            <li>
              <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-800/30 transition-colors text-red-300 w-full text-left"
                        onclick="return confirm('Are you sure you want to logout?')">
                  <i class="fas fa-sign-out-alt w-5"></i>
                  <span>Logout</span>
                </button>
              </form>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="pt-16 min-h-screen">
    <!-- Mobile Search Bar -->
    <div class="md:hidden bg-card border-b border-slate-800 p-4">
      <form action="{{ route('search') }}" method="GET" class="relative">
        <input type="text" 
               name="q"
               placeholder="Search books, members, loans..." 
               class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 pl-10 text-sm focus:border-primary focus:ring-1 focus:ring-primary">
        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-white">
          <i class="fas fa-search text-sm"></i>
        </button>
      </form>
    </div>

    <!-- Page Header -->
    <div class="bg-card/30 border-b border-slate-800 px-4 sm:px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="inline-block w-2.5 h-2.5 rounded-full bg-accent shadow-[0_0_12px_rgba(57,211,83,0.8)]"></span>
          <h1 class="text-xl font-bold text-white">@yield('title', 'Dashboard')</h1>
        </div>
        
        <!-- Breadcrumb -->
        @hasSection('breadcrumb')
          <nav class="hidden sm:flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
              <li>
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white">
                  <i class="fas fa-home"></i>
                </a>
              </li>
              <li class="text-slate-600">/</li>
              <li class="text-slate-300">@yield('breadcrumb')</li>
            </ol>
          </nav>
        @endif
      </div>
    </div>

    <!-- Content Area -->
    <div class="p-4 sm:p-6">
      <!-- Alert Messages -->
      @if(session('status'))
        <div class="mb-6 rounded-lg border border-emerald-700 bg-emerald-900/40 px-4 py-3 text-emerald-200 flex items-center gap-3">
          <i class="fas fa-check-circle text-emerald-400"></i>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      @if(session('success'))
        <div class="mb-6 rounded-lg border border-emerald-700 bg-emerald-900/40 px-4 py-3 text-emerald-200 flex items-center gap-3">
          <i class="fas fa-check-circle text-emerald-400"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-700 bg-red-900/40 px-4 py-3 text-red-200 flex items-center gap-3">
          <i class="fas fa-exclamation-circle text-red-400"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="mb-6 rounded-lg border border-rose-700 bg-rose-900/40 px-4 py-3 text-rose-200">
          <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-exclamation-triangle text-rose-400"></i>
            <span class="font-semibold">Please fix the following errors:</span>
          </div>
          <ul class="list-disc ml-8 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Page Content -->
      <div class="space-y-6">
        {{ $slot ?? '' }}
        @yield('content')
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="border-t border-slate-800 bg-card/30">
    <div class="px-6 py-4">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <span class="text-sm text-slate-400">© {{ date('Y') }} CCLMS Library Management System</span>
          <span class="hidden sm:block text-xs text-slate-500">v1.0.0</span>
        </div>
        <div class="flex items-center gap-4 text-xs text-slate-500">
          <span>Built with Laravel {{ app()->version() }}</span>
          <span>•</span>
          <span>Powered by Tailwind CSS</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Overlay for mobile sidebar -->
  <div id="sidebarOverlay" class="fixed inset-0 z-30 bg-black/50 opacity-0 pointer-events-none lg:hidden transition-opacity duration-300"></div>

  <!-- Mobile Logout Button -->
  <div class="lg:hidden fixed bottom-6 right-6 z-40">
    <div class="relative" x-data="{ open: false }">
      <button @click="open = !open" 
              class="w-14 h-14 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-200 transform hover:scale-110">
        <i class="fas fa-user text-lg" x-show="!open"></i>
        <i class="fas fa-times text-lg" x-show="open"></i>
      </button>
      
      <!-- Mobile User Menu -->
      <div x-show="open" 
           @click.away="open = false"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 scale-95 translate-y-4"
           x-transition:enter-end="opacity-100 scale-100 translate-y-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 scale-100 translate-y-0"
           x-transition:leave-end="opacity-0 scale-95 translate-y-4"
           class="absolute bottom-16 right-0 w-48 bg-card border border-slate-700 rounded-lg shadow-xl">
        
        <div class="p-3 border-b border-slate-700">
          <p class="text-sm font-medium text-white">{{ session('admin_name', 'Admin') }}</p>
          <p class="text-xs text-slate-400">Administrator</p>
        </div>
        
        <div class="p-2">
          <form action="{{ route('admin.logout') }}" method="POST" class="block">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-300 hover:bg-red-900/20 rounded-lg transition-colors text-left"
                    onclick="return confirm('Are you sure you want to logout?')">
              <i class="fas fa-sign-out-alt text-red-400"></i>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburgerLines = sidebarToggle?.querySelectorAll('.hamburger-line') ?? [];

    function setHamburgerState(isOpen) {
      if (!sidebarToggle || hamburgerLines.length < 3) {
        return;
      }

      sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

      if (isOpen) {
        hamburgerLines[0].style.transform = 'translateY(6px) rotate(45deg)';
        hamburgerLines[1].style.opacity = '0';
        hamburgerLines[2].style.transform = 'translateY(-6px) rotate(-45deg)';
      } else {
        hamburgerLines[0].style.transform = 'none';
        hamburgerLines[1].style.opacity = '1';
        hamburgerLines[2].style.transform = 'none';
      }
    }

    function toggleSidebar() {
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('opacity-0');
      overlay.classList.toggle('pointer-events-none');
      document.body.classList.toggle('overflow-hidden');

      setHamburgerState(!sidebar.classList.contains('-translate-x-full'));
    }

    sidebarToggle?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
    setHamburgerState(false);

    // Close sidebar when clicking on a link (mobile only)
    const sidebarLinks = sidebar?.querySelectorAll('a');
    sidebarLinks?.forEach(link => {
      link.addEventListener('click', () => {
        if (!sidebar.classList.contains('-translate-x-full')) {
          toggleSidebar();
        }
      });
    });

    window.addEventListener('resize', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('opacity-0', 'pointer-events-none');
      document.body.classList.remove('overflow-hidden');
      setHamburgerState(false);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('[class*="bg-emerald-900"], [class*="bg-red-900"], [class*="bg-rose-900"]');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
      }, 5000);
    });

    // Notifications Alpine.js component
    document.addEventListener('alpine:init', () => {
      Alpine.data('notifications', () => ({
        open: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
          this.loadNotifications();
          // Refresh notifications every 30 seconds
          setInterval(() => this.loadNotifications(), 30000);
        },
      
        async loadNotifications() {
          try {
            console.log('Loading notifications...');
            const response = await fetch('{{ route("notifications.index") }}');
            const data = await response.json();
            console.log('Notifications data:', data);
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
          } catch (error) {
            console.error('Failed to load notifications:', error);
          }
        },      toggleDropdown() {
        this.open = !this.open;
      },
      
      async markAsRead(notificationId) {
        try {
          await fetch('{{ route("notifications.mark-read") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: notificationId })
          });
          
          // Update local state
          const notification = this.notifications.find(n => n.id === notificationId);
          if (notification && !notification.read) {
            notification.read = true;
            this.unreadCount = Math.max(0, this.unreadCount - 1);
          }
        } catch (error) {
          console.error('Failed to mark notification as read:', error);
        }
      },
      
      async markAllAsRead() {
        try {
          await fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          // Update local state
          this.notifications.forEach(notification => {
            notification.read = true;
          });
          this.unreadCount = 0;
        } catch (error) {
          console.error('Failed to mark all notifications as read:', error);
        }
      },
      
      async clearAllNotifications() {
        // Show confirmation dialog
        if (!confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
          return;
        }
        
        try {
          await fetch('{{ route("notifications.clear-all") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          // Update local state
          this.notifications = [];
          this.unreadCount = 0;
          this.open = false; // Close dropdown
          
          // Show success message
          this.showToast('All notifications cleared successfully', 'success');
        } catch (error) {
          console.error('Failed to clear all notifications:', error);
          this.showToast('Failed to clear notifications', 'error');
        }
      },
      
      showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600';
        toast.className += ` ${bgColor} text-white`;
        
        const icon = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle';
        
        toast.innerHTML = `
          <div class="flex items-center gap-2">
            <i class="${icon}"></i>
            <span>${message}</span>
          </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
          toast.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
          toast.classList.add('translate-x-full');
          setTimeout(() => toast.remove(), 300);
        }, 3000);
      }
    }))
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>

  @stack('scripts')
</body>
</html>



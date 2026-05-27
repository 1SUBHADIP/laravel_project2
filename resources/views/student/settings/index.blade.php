<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Settings - CCLMS</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-bg via-slate-900 to-bg text-slate-100">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

  <header class="relative z-10 border-b border-slate-800 bg-slate-950/90 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-linear-to-br from-primary to-accent">
          <i class="fas fa-book text-sm text-white"></i>
        </div>
        <div>
          <p class="text-xs uppercase tracking-[0.25em] text-slate-400">CCLMS</p>
          <h1 class="text-sm font-semibold text-white sm:text-base">Student Settings</h1>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}" class="hidden rounded-lg border border-slate-800 px-4 py-2 text-sm text-slate-300 transition-colors hover:border-slate-700 hover:bg-slate-800/60 sm:inline-flex">Dashboard</a>
        <form action="{{ route('student.logout') }}" method="POST">
          @csrf
          <button type="submit" class="rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-800">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="bg-linear-to-r from-primary/10 to-accent/10 border border-primary/20 rounded-xl p-6 mb-8">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-linear-to-br from-primary to-accent rounded-xl flex items-center justify-center">
          <i class="fas fa-cogs text-white text-xl"></i>
        </div>
        <div>
          <h2 class="text-xl font-bold text-white">Account Settings</h2>
          <p class="text-slate-300">Manage your student profile and preferences</p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="mb-6 rounded-xl border border-green-700 bg-green-900/20 p-4 text-green-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <div class="bg-card border border-slate-800 rounded-xl p-6 lg:col-span-2">
        <h3 class="mb-4 text-lg font-semibold text-white flex items-center gap-2">
          <i class="fas fa-user-edit text-accent"></i>
          Update Profile
        </h3>
        <form action="{{ route('student.settings.profile') }}" method="POST" class="space-y-6">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
              <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
              <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Phone</label>
              <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">New Password (optional)</label>
              <input type="password" name="password" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
              <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-3 text-white">
            </div>
          </div>

          <div class="flex items-center justify-between">
            <a href="{{ route('student.dashboard') }}" class="text-sm text-slate-400 hover:underline">Back to Dashboard</a>
            <div>
              <button type="button" onclick="goBackToPreviousPage()" class="hidden rounded-md p-2 text-slate-300 transition-colors hover:bg-slate-800 hover:text-white sm:inline-flex" aria-label="Go back">
                <i class="fas fa-arrow-left"></i>
              </button>
              <button type="submit" class="ml-3 rounded-lg bg-primary px-6 py-3 font-semibold text-white hover:bg-primary-600">Save Changes</button>
            </div>
          </div>
        </form>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="mb-4 text-lg font-semibold text-white flex items-center gap-2">
          <i class="fas fa-circle-info text-accent"></i>
          System Information
        </h3>
        <div class="space-y-2 text-sm text-slate-400">
          <p>App Version: {{ $systemInfo['app_version'] ?? '1.0.0' }}</p>
          <p>{{ $systemInfo['laravel_version'] ?? '' }}</p>
          <p>PHP Version: {{ $systemInfo['php_version'] ?? phpversion() }}</p>
          <p>Database: {{ $systemInfo['database_name'] ?? config('database.default') }}</p>
        </div>
      </div>
    </div>
  </main>

  <script>
    function goBackToPreviousPage() {
      if (window.history.length > 1) {
        window.history.back();
        return;
      }

      window.location.href = "{{ route('student.dashboard') }}";
    }
  </script>
</body>
</html>

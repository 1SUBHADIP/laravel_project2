<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CCLMS Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
              accent: '#39d353'
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-bg via-slate-900 to-bg flex items-center justify-center p-4">
  <!-- Background Pattern -->
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
  
  <!-- Login Container -->
  <div class="relative w-full max-w-md">
    <!-- Login Card -->
    <div class="bg-card/80 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-primary to-accent p-6 text-center">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-book text-3xl text-white"></i>
        </div>
        <h1 class="text-2xl font-bold text-white mb-1">CCLMS</h1>
        <p class="text-white/80 text-sm">Library Management System</p>
      </div>

      <!-- Login Form -->
      <div class="p-8">
        <div class="text-center mb-8">
          <h2 class="text-xl font-semibold text-white mb-2">Admin Login</h2>
          <p class="text-slate-400 text-sm">Sign in to your administrator account</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
          <div class="mb-6 p-4 rounded-lg bg-green-900/40 border border-green-700 text-green-200 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="text-sm">{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 p-4 rounded-lg bg-red-900/40 border border-red-700 text-red-200 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="text-sm">{{ session('error') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div class="mb-6 p-4 rounded-lg bg-red-900/40 border border-red-700 text-red-200">
            <div class="flex items-center gap-3 mb-2">
              <i class="fas fa-exclamation-triangle text-red-400"></i>
              <span class="font-semibold text-sm">Please fix the following errors:</span>
            </div>
            <ul class="list-disc ml-6 space-y-1 text-sm">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
          @csrf
          
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-envelope w-4 mr-2"></i>Email Address
            </label>
            <input type="email" 
                   id="email"
                   name="email" 
                   value="{{ old('email') }}" 
                   class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
                   placeholder="admin@cclms.com"
                   required
                   autocomplete="email">
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-lock w-4 mr-2"></i>Password
            </label>
            <div class="relative">
              <input type="password" 
                     id="password"
                     name="password" 
                     class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200"
                     placeholder="Enter your password"
                     required
                     autocomplete="current-password">
              <button type="button" 
                      onclick="togglePassword()"
                      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                <i id="passwordIcon" class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" 
                     name="remember" 
                     class="w-4 h-4 text-primary bg-slate-800 border-slate-700 rounded focus:ring-primary/20 focus:ring-2">
              <span class="ml-2 text-sm text-slate-300">Remember me</span>
            </label>
            <a href="{{ route('admin.password.request') }}" class="text-sm text-primary hover:text-primary-600 transition-colors">
              Forgot password?
            </a>
          </div>

          <!-- Submit Button -->
          <button type="submit" 
                  class="w-full bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-primary/20">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Sign In
          </button>
        </form>

        <!-- Additional Links -->
        <div class="mt-8 pt-6 border-t border-slate-700">
          <div class="text-center">
            <p class="text-slate-400 text-sm mb-4">Need help accessing your account?</p>
            <div class="flex justify-center space-x-4 text-sm">
              <a href="#" class="text-slate-300 hover:text-white transition-colors">
                <i class="fas fa-phone mr-1"></i>Support
              </a>
              <a href="#" class="text-slate-300 hover:text-white transition-colors">
                <i class="fas fa-question-circle mr-1"></i>Help
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-8">
      <p class="text-slate-500 text-sm">
        © {{ date('Y') }} CCLMS Library Management System
      </p>
      <p class="text-slate-600 text-xs mt-1">
        Secure Admin Access Portal
      </p>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="fixed inset-0 bg-black/50 items-center justify-center z-50 hidden">
    <div class="bg-card rounded-lg p-6 flex items-center gap-4">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
      <span class="text-white">Signing you in...</span>
    </div>
  </div>

  <script>
    // Toggle password visibility
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const passwordIcon = document.getElementById('passwordIcon');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
      }
    }

    // Show loading overlay on form submit
    document.querySelector('form').addEventListener('submit', function() {
      const overlay = document.getElementById('loadingOverlay');
      overlay.classList.remove('hidden');
      overlay.classList.add('flex');
    });

    // Auto-hide alert messages after 8 seconds
    const alerts = document.querySelectorAll('[class*="bg-red-900"], [class*="bg-green-900"]');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
      }, 8000);
    });

    // Focus on email input when page loads
    window.addEventListener('load', () => {
      document.getElementById('email').focus();
    });

    // Auto-submit on Enter key in password field
    document.getElementById('password').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.querySelector('form').submit();
      }
    });
  </script>
</body>
</html>




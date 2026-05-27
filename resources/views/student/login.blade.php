<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - CCLMS Library Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-bg via-slate-900 to-bg flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
  
  <button type="button" onclick="goBackToPreviousPage()" class="fixed left-4 top-4 z-40 inline-flex items-center gap-2 rounded-lg border border-slate-700 bg-slate-900/80 px-4 py-2 text-sm text-slate-200 backdrop-blur hover:bg-slate-800">
    <i class="fas fa-arrow-left"></i>
    Back
  </button>

  <div id="studentToastHost" class="fixed top-4 left-1/2 z-50 hidden w-full max-w-md -translate-x-1/2 px-4"></div>

  <div class="relative w-full max-w-md">
    <div class="bg-card/80 backdrop-blur-xl border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
      <div class="bg-linear-to-r from-primary to-accent p-6 text-center">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-book text-3xl text-white"></i>
        </div>
        <h1 class="text-2xl font-bold text-white mb-1">CCLMS</h1>
        <p class="text-white/80 text-sm">Library Management System</p>
      </div>

      <div class="p-8">
        <div class="text-center mb-8">
          <h2 class="text-xl font-semibold text-white mb-2">Student Login</h2>
          <p class="text-slate-400 text-sm">Use your college ID card number and password to sign in.</p>
        </div>

        @if(session('success'))
          <div class="student-toast mb-6 p-4 rounded-lg bg-green-900/40 border border-green-700 text-green-200 items-center gap-3 shadow-2xl shadow-black/30 backdrop-blur hidden">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="text-sm">{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="student-toast mb-6 p-4 rounded-lg bg-red-900/40 border border-red-700 text-red-200 items-center gap-3 shadow-2xl shadow-black/30 backdrop-blur hidden">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="text-sm">{{ session('error') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div class="student-toast mb-6 p-4 rounded-lg bg-red-900/40 border border-red-700 text-red-200 shadow-2xl shadow-black/30 backdrop-blur hidden">
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

        <form action="{{ route('student.login.submit') }}" method="POST" autocomplete="on" class="space-y-6" id="studentLoginForm">
          @csrf

          <div>
            <label for="student_id" class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-id-card w-4 mr-2"></i>Student ID
            </label>
            <input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200" placeholder="Enter your ID card number" required autofocus autocomplete="username">
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
              <i class="fas fa-lock w-4 mr-2"></i>Password
            </label>
            <div class="relative">
              <input id="password" type="password" name="password" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-200 pr-12" placeholder="Enter password" required autocomplete="current-password">
              <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                <i id="passwordIcon" class="fas fa-eye"></i>
              </button>
            </div>
            <p class="mt-2 text-xs text-slate-500">Numbers only; spaces, dashes, and country codes are ignored.</p>
          </div>

          <div class="flex items-center justify-between gap-4">
            <label class="flex items-center">
              <input type="checkbox" id="rememberMeCheckbox" name="remember_me" value="1" class="w-4 h-4 text-primary bg-slate-800 border-slate-700 rounded focus:ring-primary/20 focus:ring-2">
              <span class="ml-2 text-sm text-slate-300">Remember me</span>
            </label>
            <a href="{{ route('student.password.request') }}" class="text-sm text-primary hover:text-primary-600 transition-colors">Forgot password?</a>
          </div>

          <button type="submit" class="w-full bg-linear-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-primary/20">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Sign In
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-700">
          <div class="text-center">
            <p class="text-slate-400 text-sm mb-4">Need help accessing your student account?</p>
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

    <div class="text-center mt-8">
      <p class="text-slate-500 text-sm">© {{ date('Y') }} CCLMS Library Management System</p>
      <p class="text-slate-600 text-xs mt-1">Secure Student Access Portal</p>
    </div>
  </div>

  <div id="loadingOverlay" class="fixed inset-0 bg-black/50 items-center justify-center z-50 hidden">
    <div class="bg-card rounded-lg p-6 flex items-center gap-4">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
      <span class="text-white">Signing you in...</span>
    </div>
  </div>

  <script>
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

    function goBackToPreviousPage() {
      if (window.history.length > 1) {
        window.history.back();
        return;
      }

      window.location.href = "{{ route('public.about') }}";
    }

    document.querySelector('form').addEventListener('submit', function() {
      const overlay = document.getElementById('loadingOverlay');
      overlay.classList.remove('hidden');
      overlay.classList.add('flex');
    });

    const toastHost = document.getElementById('studentToastHost');
    document.querySelectorAll('.student-toast').forEach((toast) => {
      if (!toastHost) {
        return;
      }

      toast.classList.remove('hidden');
      toastHost.classList.remove('hidden');
      toastHost.appendChild(toast);

      window.setTimeout(() => {
        toast.style.transition = 'opacity 250ms ease, transform 250ms ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';

        window.setTimeout(() => {
          toast.remove();

          if (!toastHost.children.length) {
            toastHost.classList.add('hidden');
          }
        }, 250);
      }, 5000);
    });

    window.addEventListener('load', () => {
      document.getElementById('student_id').focus();
    });

    document.getElementById('password').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.querySelector('form').submit();
      }
    });
  </script>

  <script>
    // Auto-submit when browser autofills and "Remember me" is checked
    (function() {
      const form = document.getElementById('studentLoginForm');
      const studentIdInput = document.getElementById('student_id');
      const passwordInput = document.getElementById('password');
      const rememberCheckbox = document.getElementById('rememberMeCheckbox');

      function tryAutoSubmit() {
        try {
          const idFilled = studentIdInput && studentIdInput.value.trim() !== '';
          const pwdFilled = passwordInput && passwordInput.value.trim() !== '';
          const rememberChecked = rememberCheckbox && rememberCheckbox.checked;

          if (idFilled && pwdFilled && rememberChecked) {
            try { localStorage.setItem('student_auto_login', '1'); } catch (e) {}
            form.submit();
          }
        } catch (e) {}
      }

      window.setTimeout(tryAutoSubmit, 600);

      form.addEventListener('submit', function() {
        try {
          if (rememberCheckbox && rememberCheckbox.checked) {
            localStorage.setItem('student_auto_login', '1');
          } else {
            localStorage.removeItem('student_auto_login');
          }
        } catch (e) {}
      });
    })();
  </script>

</body>
</html>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reset Password - Student</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-bg via-slate-900 to-bg text-slate-100 flex items-center justify-center p-6">
  <div class="w-full max-w-md bg-card/80 rounded-2xl border border-slate-800 p-6">
    <h2 class="text-xl font-semibold text-white mb-2">Set New Password</h2>
    <p class="text-sm text-slate-400 mb-4">Choose a secure password for your student account.</p>

    @if($errors->any())
      <div class="mb-4 p-3 rounded bg-rose-900/40 text-rose-200">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('student.password.update') }}" method="POST" class="space-y-4">
      @csrf
      <label class="block text-sm text-slate-300">New Password</label>
      <input type="password" name="password" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white" required>
      <label class="block text-sm text-slate-300">Confirm Password</label>
      <input type="password" name="password_confirmation" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white" required>
      <div class="flex gap-2">
        <a href="{{ route('student.login') }}" class="text-sm text-slate-400 hover:underline">Back to Login</a>
        <button class="ml-auto inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white">Set Password</button>
      </div>
    </form>
  </div>

  <script>
    function goBackToPreviousPage() {
      if (window.history.length > 1) {
        window.history.back();
        return;
      }

      window.location.href = "{{ route('student.password.verify') }}";
    }
  </script>
</body>
</html>
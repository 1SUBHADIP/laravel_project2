<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CCLMS Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cclms.css') }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-slate-900/80 border border-slate-800 rounded-2xl shadow-2xl p-8">
    <div class="text-center mb-6">
      <div class="w-14 h-14 bg-blue-600/20 rounded-xl mx-auto flex items-center justify-center mb-3">
        <i class="fas fa-key text-blue-400 text-xl"></i>
      </div>
      <h1 class="text-white text-xl font-semibold">Forgot Admin Password</h1>
      <p class="text-slate-400 text-sm mt-2">Enter your admin email to receive a reset link.</p>
    </div>

    @if (session('status'))
      <div class="mb-4 p-3 rounded-lg bg-green-900/40 border border-green-700 text-green-200 text-sm">
        {{ session('status') }}
      </div>
    @endif

    {{-- @if (session('reset_link'))
      <div class="mb-4 p-3 rounded-lg bg-blue-900/40 border border-blue-700 text-blue-200 text-sm space-y-2">
        <div>Password reset link:</div>
        <a href="{{ session('reset_link') }}" class="break-all underline text-blue-300 hover:text-blue-100">
          {{ session('reset_link') }}
        </a>
      </div>
    @endif --}}
    @if (session('status'))
      <div class="mb-4 p-3 rounded-lg bg-green-900/40 border border-green-700 text-green-200 text-sm">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-3 rounded-lg bg-red-900/40 border border-red-700 text-red-200 text-sm">
        <ul class="list-disc ml-5 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-sm text-slate-300 mb-2">Email Address</label>
        <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          required
          autofocus
          class="w-full px-4 py-3 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="admin@cclms.com"
        >
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors"
      >
        Send Password Reset Link
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="{{ route('admin.login') }}" class="text-sm text-slate-300 hover:text-white transition-colors">
        <i class="fas fa-arrow-left mr-1"></i>
        Back to login
      </a>
    </div>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d1117">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta property="og:title" content="{{ $pageTitle }} | CCLMS Library Management System">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }} | CCLMS Library Management System">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ asset('logo.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>{{ $pageTitle }} | CCLMS Library Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-slate-200">
  <div class="min-h-screen bg-slate-950">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top,rgba(31,111,235,0.12),transparent_35%),radial-gradient(circle_at_right,rgba(57,211,83,0.1),transparent_30%)]"></div>
    <main class="mx-auto flex min-h-screen w-full max-w-5xl items-center px-4 py-10 sm:px-6 lg:px-8">
      <section class="relative w-full overflow-hidden rounded-4xl border border-slate-800 bg-card/90 shadow-2xl backdrop-blur">
        <div class="border-b border-slate-800 px-6 py-5 sm:px-8">
          <div class="flex items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
              <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-linear-to-br from-primary to-accent text-white shadow-lg shadow-primary/20">
                <i class="fas fa-book text-lg"></i>
              </div>
              <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">CCLMS</p>
                <p class="text-sm font-semibold text-white">Library Management System</p>
              </div>
            </a>
            <a href="{{ url('/') }}" class="rounded-full border border-slate-700 px-4 py-2 text-sm text-slate-300 transition-colors hover:border-primary hover:text-white">Home</a>
          </div>
        </div>

        <div class="grid gap-8 px-6 py-8 sm:px-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-start">
          <div>
            <span class="inline-flex rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-primary">{{ $pageTitle }}</span>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $pageHeading }}</h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">{{ $pageDescription }}</p>

            <div class="mt-8 space-y-4">
              @foreach($sections as $section)
                <article class="rounded-2xl border border-slate-800 bg-slate-950/40 p-5">
                  <h2 class="text-lg font-semibold text-white">{{ $section['title'] }}</h2>
                  <p class="mt-2 text-sm leading-7 text-slate-300">{{ $section['text'] }}</p>
                </article>
              @endforeach
            </div>
          </div>

          <aside class="rounded-[1.75rem] border border-slate-800 bg-slate-950/60 p-6">
            <div class="flex items-center gap-3">
              <div class="h-12 w-12 rounded-2xl bg-primary/15 text-primary flex items-center justify-center">
                <i class="fas fa-circle-info text-xl"></i>
              </div>
              <div>
                <p class="text-sm font-semibold text-white">Useful links</p>
                <p class="text-xs text-slate-400">Quick access to the public information pages</p>
              </div>
            </div>

            <div class="mt-6 space-y-3 text-sm">
              <a href="{{ route('public.about') }}" class="flex items-center justify-between rounded-xl border border-slate-800 px-4 py-3 text-slate-300 transition-colors hover:border-primary hover:text-white">
                <span>About CCLMS</span>
                <i class="fas fa-arrow-right text-xs"></i>
              </a>
              <a href="{{ route('public.rules') }}" class="flex items-center justify-between rounded-xl border border-slate-800 px-4 py-3 text-slate-300 transition-colors hover:border-primary hover:text-white">
                <span>Library Rules</span>
                <i class="fas fa-arrow-right text-xs"></i>
              </a>
              <a href="{{ route('public.contact') }}" class="flex items-center justify-between rounded-xl border border-slate-800 px-4 py-3 text-slate-300 transition-colors hover:border-primary hover:text-white">
                <span>Contact Support</span>
                <i class="fas fa-arrow-right text-xs"></i>
              </a>
              <a href="{{ route('public.privacy') }}" class="flex items-center justify-between rounded-xl border border-slate-800 px-4 py-3 text-slate-300 transition-colors hover:border-primary hover:text-white">
                <span>Privacy Policy</span>
                <i class="fas fa-arrow-right text-xs"></i>
              </a>
            </div>
          </aside>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - CCLMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-bg via-slate-900 to-bg text-slate-100">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

  <div id="studentMenuOverlay" class="fixed inset-0 z-40 bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"></div>

  <aside id="studentMenu" class="fixed left-0 top-0 z-50 h-full w-72 -translate-x-full overflow-y-auto border-r border-slate-800 bg-slate-950/95 p-4 pt-20 backdrop-blur-xl transition-transform duration-300 lg:hidden">
    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
      <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Student Menu</p>
      <div class="mt-4 space-y-2 text-sm">
        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-slate-800/60 px-4 py-3 text-white">
          <i class="fas fa-home w-4 text-center text-primary"></i>
          Dashboard
        </a>
        <a href="{{ route('student.settings.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-300 transition-colors hover:bg-slate-800/60 hover:text-white">
          <i class="fas fa-cogs w-4 text-center text-cyan-400"></i>
          Account Settings
        </a>
        <a href="{{ route('student.password.change') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-300 transition-colors hover:bg-slate-800/60 hover:text-white">
          <i class="fas fa-key w-4 text-center text-green-400"></i>
          Change Password
        </a>
        <a href="{{ route('student.password.request') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-300 transition-colors hover:bg-slate-800/60 hover:text-white">
          <i class="fas fa-envelope w-4 text-center text-amber-400"></i>
          Password Reset
        </a>
      </div>
    </div>

    <div class="mt-4 rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
      <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Actions</p>
      <div class="mt-4 grid gap-3">
        <form action="{{ route('student.logout') }}" method="POST">
          @csrf
          <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-sm text-rose-300 transition-colors hover:bg-rose-500/10 hover:text-rose-200">
            <i class="fas fa-sign-out-alt w-4 text-center text-rose-400"></i>
            Logout
          </button>
        </form>
      </div>
    </div>
  </aside>

  <header class="relative z-10 border-b border-slate-800 bg-slate-950/90 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-3">
        <button type="button" onclick="goBackToPreviousPage()" class="hidden rounded-md p-2 text-slate-300 transition-colors hover:bg-slate-800 hover:text-white sm:inline-flex" aria-label="Go back">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button id="studentMenuToggle" type="button" aria-label="Toggle student menu" aria-expanded="false" class="inline-flex items-center justify-center rounded-md p-2 transition-colors hover:bg-slate-800 lg:hidden">
          <span class="student-hamburger-line block h-0.5 w-5 rounded bg-slate-300 transition-all duration-300"></span>
          <span class="student-hamburger-line mt-1 block h-0.5 w-5 rounded bg-slate-300 transition-all duration-300"></span>
          <span class="student-hamburger-line mt-1 block h-0.5 w-5 rounded bg-slate-300 transition-all duration-300"></span>
        </button>
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-linear-to-br from-primary to-accent">
          <i class="fas fa-book text-sm text-white"></i>
        </div>
        <div>
          <p class="text-xs uppercase tracking-[0.25em] text-slate-400">CCLMS</p>
          <h1 class="text-sm font-semibold text-white sm:text-base">Student Dashboard</h1>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('student.settings.index') }}" class="hidden rounded-lg border border-slate-800 px-4 py-2 text-sm text-slate-300 transition-colors hover:border-slate-700 hover:bg-slate-800/60 sm:inline-flex">Settings</a>
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
          <i class="fas fa-user-graduate text-white text-xl"></i>
        </div>
        <div>
          <h2 class="text-xl font-bold text-white">Welcome, {{ $member->name }}</h2>
          <p class="text-slate-300">Department: {{ $member->department?->name ?? 'Not assigned' }} • ID: {{ $member->student_id ?? 'N/A' }}</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm font-medium">Total Books</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['books'] ?? $libraryBooksCount) }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-book text-blue-400 text-xl"></i>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400">
            <i class="fas fa-book-open mr-1"></i>
            Available in the library
          </span>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm font-medium">Department Books</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($departmentBooksCount ?? $departmentBooks->count()) }}</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-layer-group text-green-400 text-xl"></i>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400">
            <i class="fas fa-check-circle mr-1"></i>
            Matched to your department
          </span>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm font-medium">Active Loans</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['active_loans'] ?? 0) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-exchange-alt text-yellow-400 text-xl"></i>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-yellow-400">
            <i class="fas fa-arrow-right mr-1"></i>
            Currently issued
          </span>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6 hover:border-slate-700 transition-all duration-200">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-400 text-sm font-medium">Overdue Loans</p>
            <p class="text-3xl font-bold text-white mt-1">{{ number_format($counts['overdue_loans'] ?? 0) }}</p>
          </div>
          <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          @if(($counts['overdue_loans'] ?? 0) > 0)
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-bolt text-accent"></i>
          Quick Actions
        </h3>
        <div class="space-y-3">
          {{-- <a href="{{ route('search') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
              <i class="fas fa-search text-blue-400"></i>
            </div>
            <div>
              <p class="text-white font-medium">Search Catalog</p>
              <p class="text-slate-400 text-sm">Find books and availability</p>
            </div>
          </a> --}}

          <a href="{{ route('student.settings.index') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center group-hover:bg-cyan-500/30 transition-colors">
              <i class="fas fa-cogs text-cyan-400"></i>
            </div>
            <div>
              <p class="text-white font-medium">Account Settings</p>
              <p class="text-slate-400 text-sm">Manage your profile</p>
            </div>
          </a>

          <a href="{{ route('student.password.change') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700 transition-colors group">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition-colors">
              <i class="fas fa-key text-green-400"></i>
            </div>
            <div>
              <p class="text-white font-medium">Change Password</p>
              <p class="text-slate-400 text-sm">Update your account password</p>
            </div>
          </a>
        </div>
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-clock text-accent"></i>
          Current Loan Status
        </h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
            <span class="text-slate-300">Active loans</span>
            <span class="text-yellow-400 font-medium">{{ number_format($counts['active_loans'] ?? 0) }}</span>
          </div>
          <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
            <span class="text-slate-300">Overdue loans</span>
            <span class="{{ ($counts['overdue_loans'] ?? 0) > 0 ? 'text-red-400' : 'text-green-400' }} font-medium">{{ number_format($counts['overdue_loans'] ?? 0) }}</span>
          </div>
          <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
            <span class="text-slate-300">Department coverage</span>
            <span class="text-cyan-400 font-medium">{{ number_format($departmentBooksCount ?? $departmentBooks->count()) }} books</span>
          </div>
          <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
            <span class="text-slate-300">Account status</span>
            <span class="text-green-400 font-medium">Active</span>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-card border border-slate-800 rounded-xl p-6 mb-8">
      <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
        <i class="fas fa-book text-accent"></i>
        Books available to your department
      </h3>
      <p class="text-slate-400 mb-5">These are the books mapped to {{ $member->department?->name ?? 'your department' }}.</p>

      @if($departmentBooks->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-8 text-center text-slate-400">No books are assigned to your department yet.</div>
      @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          @foreach($departmentBooks as $book)
            <article class="rounded-xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-black/10">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <h4 class="text-lg font-semibold text-white">{{ $book->title }}</h4>

              <script>
                function goBackToPreviousPage() {
                  if (window.history.length > 1) {
                    window.history.back();
                    return;
                  }

                  window.location.href = "{{ route('student.dashboard') }}";
                }

                const studentMenuToggle = document.getElementById('studentMenuToggle');
                const studentMenu = document.getElementById('studentMenu');
                const studentMenuOverlay = document.getElementById('studentMenuOverlay');
                const studentHamburgerLines = studentMenuToggle?.querySelectorAll('.student-hamburger-line') ?? [];

                function setStudentHamburgerState(isOpen) {
                  if (!studentMenuToggle || studentHamburgerLines.length < 3) {
                    return;
                  }

                  studentMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                  if (isOpen) {
                    studentHamburgerLines[0].style.transform = 'translateY(6px) rotate(45deg)';
                    studentHamburgerLines[1].style.opacity = '0';
                    studentHamburgerLines[2].style.transform = 'translateY(-6px) rotate(-45deg)';
                  } else {
                    studentHamburgerLines[0].style.transform = 'none';
                    studentHamburgerLines[1].style.opacity = '1';
                    studentHamburgerLines[2].style.transform = 'none';
                  }
                }

                function toggleStudentMenu() {
                  studentMenu.classList.toggle('-translate-x-full');
                  studentMenuOverlay.classList.toggle('opacity-0');
                  studentMenuOverlay.classList.toggle('pointer-events-none');
                  document.body.classList.toggle('overflow-hidden');

                  setStudentHamburgerState(!studentMenu.classList.contains('-translate-x-full'));
                }

                studentMenuToggle?.addEventListener('click', toggleStudentMenu);
                studentMenuOverlay?.addEventListener('click', toggleStudentMenu);
                setStudentHamburgerState(false);

                studentMenu?.querySelectorAll('a, button').forEach((item) => {
                  item.addEventListener('click', () => {
                    if (!studentMenu.classList.contains('-translate-x-full')) {
                      toggleStudentMenu();
                    }
                  });
                });

                window.addEventListener('resize', () => {
                  studentMenu.classList.add('-translate-x-full');
                  studentMenuOverlay.classList.add('opacity-0', 'pointer-events-none');
                  document.body.classList.remove('overflow-hidden');
                  setStudentHamburgerState(false);
                });
              </script>
                  <p class="mt-1 text-sm text-slate-400">{{ $book->author }}</p>
                </div>
                @if($book->has_kindle_version)
                  <span class="inline-flex items-center rounded-full bg-cyan-500/15 px-2.5 py-1 text-xs font-medium text-cyan-200">Kindle</span>
                @endif
              </div>

              <dl class="mt-4 space-y-2 text-sm text-slate-300">
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">ISBN</dt>
                  <dd class="text-right">{{ $book->isbn }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Category</dt>
                  <dd class="text-right">{{ $book->category?->name ?? 'General' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Copies</dt>
                  <dd class="text-right">{{ $book->available_copies }} / {{ $book->total_copies }}</dd>
                </div>
              </dl>

              @if($book->kindle_link)
                <div class="mt-4">
                  <a href="{{ $book->kindle_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm text-cyan-300 hover:text-cyan-200">
                    <i class="fas fa-external-link-alt"></i>
                    Open Kindle version
                  </a>
                </div>
              @endif
            </article>
          @endforeach
        </div>
      @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-hand-holding text-accent"></i>
          Your Current Loans
        </h3>
        <p class="text-slate-400 mb-4">Active loans and due dates.</p>

        @if($currentLoans->isEmpty())
          <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-6 text-center text-slate-400">You have no active loans.</div>
        @else
          <div class="space-y-4">
            @foreach($currentLoans as $loan)
              <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                <div>
                  <div class="font-semibold text-white">{{ $loan->book->title }}</div>
                  <div class="text-sm text-slate-400">Due: {{ optional($loan->due_date)->format('Y-m-d') }} • Loaned on: {{ optional($loan->loan_date)->format('Y-m-d') }}</div>
                </div>
                <div class="flex items-center gap-2">
                  @if($loan->book->kindle_link)
                    <a href="{{ $loan->book->kindle_link }}" target="_blank" class="text-cyan-300 text-sm hover:text-cyan-200">Open Kindle</a>
                  @endif
                  <form action="{{ route('student.loans.renew', $loan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm rounded-md bg-primary px-3 py-1 text-white hover:opacity-90">Request Renewal</button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <div class="bg-card border border-slate-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
          <i class="fas fa-history text-accent"></i>
          Loan History
        </h3>
        <p class="text-slate-400 mb-4">Recently returned items.</p>

        @if($loanHistory->isEmpty())
          <div class="rounded-xl border border-dashed border-slate-700 bg-slate-900/60 p-6 text-center text-slate-400">No recent returns.</div>
        @else
          <div class="space-y-3">
            @foreach($loanHistory as $loan)
              <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                <div>
                  <div class="font-semibold text-white">{{ $loan->book->title }}</div>
                  <div class="text-sm text-slate-400">Returned: {{ optional($loan->returned_date)->format('Y-m-d') }}</div>
                </div>
                <div class="text-sm text-slate-400">{{ $loan->book->author }}</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    @if(session('success'))
    <div class="bg-green-900/20 border border-green-700 rounded-xl p-6">
      <div class="flex items-center gap-3 mb-2">
        <i class="fas fa-check-circle text-green-400 text-xl"></i>
        <h3 class="text-lg font-semibold text-green-300">Success</h3>
      </div>
      <p class="text-green-200">{{ session('success') }}</p>
    </div>
    @endif
  </main>
</body>
</html>

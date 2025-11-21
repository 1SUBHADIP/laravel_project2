@extends('layout')

@section('title', 'Loans')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div></div>
  <a href="{{ route('loans.create') }}" class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">New Loan</a>
</div>

<div class="overflow-hidden rounded-lg border border-slate-800">
  <table class="min-w-full divide-y divide-slate-800">
    <thead class="bg-slate-900/60">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Book</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Member</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Loan Date</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Due Date</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Returned</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Overdue</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-800 bg-card">
      @foreach($loans as $loan)
        <tr>
          <td class="px-4 py-3">{{ $loan->book->title }}</td>
          <td class="px-4 py-3">{{ $loan->member->name }}</td>
          <td class="px-4 py-3">{{ $loan->loan_date->format('Y-m-d') }}</td>
          <td class="px-4 py-3">{{ $loan->due_date->format('Y-m-d') }}</td>
          <td class="px-4 py-3">{{ $loan->returned_date?->format('Y-m-d') ?? '-' }}</td>
          <td class="px-4 py-3">
          @php
            $overdueDays = 0;
            if (!$loan->returned_date && now()->greaterThan($loan->due_date)) {
                $overdueDays = now()->startOfDay()->diffInDays($loan->due_date->startOfDay());
            }
          @endphp
          @if($overdueDays > 0)
            <span class="inline-flex rounded-md bg-rose-900/40 text-rose-200 px-2 py-0.5 text-xs">{{ $overdueDays }} day(s)</span>
          @else
            -
          @endif
        </td>
        <td class="px-4 py-3">
          @if(!$loan->returned_date)
          <form action="{{ route('loans.return', $loan) }}" method="POST" class="inline" onsubmit="return confirm('Mark as returned?');">
            @csrf
            @method('PATCH')
            <button class="inline-flex items-center rounded-md border border-emerald-700 text-emerald-300 px-2 py-1 text-xs hover:bg-emerald-900/30">Mark Returned</button>
          </form>
          @else
          <span class="text-slate-400 text-xs">Completed</span>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $loans->links() }}</div>
@endsection



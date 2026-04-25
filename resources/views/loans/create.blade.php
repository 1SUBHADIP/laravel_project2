@extends('layout')

@section('title', 'New Loan')
@section('breadcrumb', 'Loans / New')

@section('content')
<form action="{{ route('loans.store') }}" method="POST" class="mt-3 space-y-4 max-w-4xl">
  @csrf
  <div>
    <label class="block text-sm text-slate-300 mb-1">Book</label>
    <select name="book_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" required>
      <option value="">-- Select Book --</option>
      @foreach($books as $book)
        <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
          {{ $book->title }} ({{ $book->available_copies }}/{{ $book->total_copies }})
        </option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Member</label>
    <select name="member_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" required>
      <option value="">-- Select Member --</option>
      @foreach($members as $member)
        <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
          {{ $member->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Loan Days</label>
    <input type="number" name="loan_days" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('loan_days', 14) }}" min="1" max="60">
  </div>
  <div class="flex gap-2">
    <a href="{{ route('loans.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Create</button>
  </div>
</form>
@endsection



@extends('layout')

@section('title', 'Members')

@section('content')
<div class="flex items-center justify-between mb-4">
  <div></div>
  <a href="{{ route('members.create') }}" class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Add Member</a>
</div>

<div class="overflow-hidden rounded-lg border border-slate-800">
  <table class="min-w-full divide-y divide-slate-800">
    <thead class="bg-slate-900/60">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Name</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Student ID</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Department</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Email</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Phone</th>
        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-800 bg-card">
      @foreach($members as $member)
        <tr>
          <td class="px-4 py-3">{{ $member->name }}</td>
          <td class="px-4 py-3">
            <span class="text-sm text-slate-400">{{ $member->student_id ?: 'N/A' }}</span>
          </td>
          <td class="px-4 py-3">
            @if($member->department)
              <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                {{ $member->department }}
              </span>
            @else
              <span class="text-sm text-slate-400">N/A</span>
            @endif
          </td>
          <td class="px-4 py-3">{{ $member->email }}</td>
          <td class="px-4 py-3">{{ $member->phone }}</td>
          <td class="px-4 py-3">
            <div class="flex gap-2">
              <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center rounded-md border border-slate-600 px-2 py-1 text-xs hover:bg-slate-800 text-slate-300">
                <i class="fas fa-edit mr-1"></i>Edit
              </a>
              <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this member?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md border border-rose-600 bg-rose-900/20 text-rose-300 px-2 py-1 text-xs hover:bg-rose-900/40 hover:border-rose-500">
                  <i class="fas fa-user-minus mr-1"></i>Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $members->links() }}</div>
@endsection



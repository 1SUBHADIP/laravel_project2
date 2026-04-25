@extends('layout')

@section('title', 'Departments')
@section('breadcrumb', 'Departments Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1">
    <div class="bg-card border border-slate-800 rounded-xl p-6">
      <h2 class="text-lg font-semibold text-white mb-1">Add Department</h2>
      <p class="text-sm text-slate-400 mb-4">Create a department to use in member records.</p>

      <form action="{{ route('departments.store') }}" method="POST" class="space-y-3">
        @csrf
        <div>
          <label class="block text-sm text-slate-300 mb-1">Department Name</label>
          <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" placeholder="e.g. BCA">
        </div>
        <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">
          <i class="fas fa-plus text-xs"></i>
          Add Department
        </button>
      </form>
    </div>
  </div>

  <div class="lg:col-span-2">
    <div class="bg-card border border-slate-800 rounded-xl overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-white">All Departments</h3>
        <span class="text-sm text-slate-400">{{ $departments->total() }} total</span>
      </div>

      @if($departments->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-[760px] w-full">
            <thead class="bg-slate-800/50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Members</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
              @foreach($departments as $department)
                <tr class="hover:bg-slate-800/30 transition-colors">
                  <td class="px-6 py-4 text-white font-medium">{{ $department->name }}</td>
                  <td class="px-6 py-4 text-slate-300">{{ $department->members_count }}</td>
                  <td class="px-6 py-4 text-right">
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Delete this department? Members in this department will become unassigned.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-rose-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-rose-600">
                        <i class="fas fa-trash text-xs"></i>
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($departments->hasPages())
          <div class="px-6 py-4 border-t border-slate-800">
            {{ $departments->links() }}
          </div>
        @endif
      @else
        <div class="p-8 text-center">
          <i class="fas fa-building text-slate-500 text-2xl mb-3"></i>
          <p class="text-slate-300">No departments yet.</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

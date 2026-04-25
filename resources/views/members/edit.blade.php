@extends('layout')

@section('title', 'Edit Member')
@section('breadcrumb', 'Members / Edit')

@section('content')
<form action="{{ route('members.update', $member) }}" method="POST" class="mt-3 space-y-4 max-w-4xl">
  @csrf
  @method('PUT')
  <div>
    <label class="block text-sm text-slate-300 mb-1">Name</label>
    <input type="text" name="name" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('name', $member->name) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Student ID</label>
    <input type="text" name="student_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('student_id', $member->student_id) }}" placeholder="Enter student ID">
  </div>
  <div>
    <div class="mb-1 flex items-center justify-between">
      <label class="block text-sm text-slate-300">Department</label>
      <a href="{{ route('departments.index') }}" class="text-xs text-primary hover:text-primary-600">Manage Departments</a>
    </div>
    <select id="department_id" name="department_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">Select Department</option>
      @foreach($departments as $dept)
          <option value="{{ $dept->id }}" {{ old('department_id', $member->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
      @endforeach
      <option value="__new__" {{ old('department_id') == '__new__' ? 'selected' : '' }}>+ Add New Department</option>
    </select>
    <div id="new-department-wrapper" class="mt-2 {{ old('department_id') == '__new__' ? '' : 'hidden' }}">
      <input
        type="text"
        name="department_name"
        value="{{ old('department_name') }}"
        placeholder="Enter new department name"
        class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm"
      >
    </div>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Email</label>
    <input type="email" name="email" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('email', $member->email) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Phone</label>
    <input type="text" name="phone" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('phone', $member->phone) }}">
  </div>
  <div class="flex gap-2">
    <a href="{{ route('members.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Update</button>
  </div>
</form>

<script>
  (function () {
    const departmentSelect = document.getElementById('department_id');
    const newDepartmentWrapper = document.getElementById('new-department-wrapper');

    if (!departmentSelect || !newDepartmentWrapper) {
      return;
    }

    const toggleNewDepartmentInput = () => {
      if (departmentSelect.value === '__new__') {
        newDepartmentWrapper.classList.remove('hidden');
      } else {
        newDepartmentWrapper.classList.add('hidden');
      }
    };

    departmentSelect.addEventListener('change', toggleNewDepartmentInput);
    toggleNewDepartmentInput();
  })();
</script>
@endsection



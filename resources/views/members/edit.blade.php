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
    <input type="text" name="student_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('student_id', $member->student_id) }}" placeholder="Enter student ID" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Date of Birth</label>
    <input type="date" name="date_of_birth" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('date_of_birth', optional($member->date_of_birth)->format('Y-m-d')) }}">
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Address</label>
    <textarea name="address" rows="3" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" placeholder="Enter full address">{{ old('address', $member->address) }}</textarea>
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
    <input type="text" name="phone" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('phone', $member->phone) }}" required>
    <p class="mt-1 text-xs text-slate-400">This mobile number is used for OTP password reset.</p>
  </div>
  <div class="grid gap-4 md:grid-cols-3">
    <div>
      <label class="block text-sm text-slate-300 mb-1">Membership Type</label>
      <select name="membership_type" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" required>
        @php($membershipType = old('membership_type', $member->membership_type ?? 'Standard'))
        @foreach(['Standard', 'Premium', 'Student'] as $type)
          <option value="{{ $type }}" {{ $membershipType === $type ? 'selected' : '' }}>{{ $type }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm text-slate-300 mb-1">Membership Date</label>
      <input type="date" name="membership_date" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('membership_date', optional($member->membership_date)->format('Y-m-d') ?: now()->toDateString()) }}" required>
    </div>
    <div>
      <label class="block text-sm text-slate-300 mb-1">Status</label>
      <select name="status" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" required>
        @php($memberStatus = old('status', $member->status ?? 'Active'))
        @foreach(['Active', 'Inactive', 'Suspended'] as $status)
          <option value="{{ $status }}" {{ $memberStatus === $status ? 'selected' : '' }}>{{ $status }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="block text-sm text-slate-300 mb-1">New Password</label>
      <input type="password" name="password" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" placeholder="Leave blank to keep the current password">
    </div>
    <div>
      <label class="block text-sm text-slate-300 mb-1">Confirm New Password</label>
      <input type="password" name="password_confirmation" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" placeholder="Confirm new password">
    </div>
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



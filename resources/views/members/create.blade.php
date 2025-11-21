@extends('layout')

@section('title', 'Add Member')

@section('content')
<form action="{{ route('members.store') }}" method="POST" class="mt-3 space-y-4">
  @csrf
  <div>
    <label class="block text-sm text-slate-300 mb-1">Name</label>
    <input type="text" name="name" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('name') }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Student ID</label>
    <input type="text" name="student_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('student_id') }}" placeholder="Enter student ID">
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Department</label>
    <select name="department" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">Select Department</option>
      <option value="Computer Science" {{ old('department') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
      <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
      <option value="Electronics" {{ old('department') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
      <option value="Mechanical" {{ old('department') == 'Mechanical' ? 'selected' : '' }}>Mechanical</option>
      <option value="Civil" {{ old('department') == 'Civil' ? 'selected' : '' }}>Civil</option>
      <option value="Electrical" {{ old('department') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
      <option value="Mathematics" {{ old('department') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
      <option value="Physics" {{ old('department') == 'Physics' ? 'selected' : '' }}>Physics</option>
      <option value="Chemistry" {{ old('department') == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
      <option value="English" {{ old('department') == 'English' ? 'selected' : '' }}>English</option>
      <option value="Business Administration" {{ old('department') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
      <option value="Other" {{ old('department') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Email</label>
    <input type="email" name="email" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('email') }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Phone</label>
    <input type="text" name="phone" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('phone') }}">
  </div>
  <div class="flex gap-2">
    <a href="{{ route('members.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Save</button>
  </div>
</form>
@endsection



<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Department;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = Member::with('department')->orderBy('name')->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        return view('members.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['nullable', 'unique:members,student_id', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'unique:members,phone', 'max:50'],
            'department_id' => ['nullable', 'string'],
            'department_name' => ['nullable', 'string', 'max:255'],
        ]);

        $data['department_id'] = $this->resolveDepartmentId($request);
        unset($data['department_name']);

        $member = Member::create($data);
        $member->load('department');

        // Log admin activity
        AdminActivityLogger::log(
            'create',
            'Member Added',
            "New member \"{$member->name}\" was added to the library" . ($member->department ? " (Dept: {$member->department->name})" : ""),
            route('members.edit', $member)
        );

        return redirect()->route('members.index')->with('status', 'Member added');
    }

    public function edit(Member $member): View
    {
        $departments = Department::orderBy('name')->get();
        return view('members.edit', compact('member', 'departments'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email,' . $member->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'string'],
            'department_name' => ['nullable', 'string', 'max:255'],
        ]);

        $data['department_id'] = $this->resolveDepartmentId($request);
        unset($data['department_name']);

        $member->update($data);
        $member->load('department');

        // Log admin activity
        AdminActivityLogger::log(
            'update',
            'Member Updated',
            "Member \"{$member->name}\" profile was updated" . ($member->department ? " (Dept: {$member->department->name})" : ""),
            route('members.edit', $member)
        );

        return redirect()->route('members.index')->with('status', 'Member updated');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $name = $member->name;
        $departmentName = $member->department ? $member->department->name : null;

        $member->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'delete',
            'Member Deleted',
            "Member \"{$name}\" was removed from the library" . ($departmentName ? " (Dept: {$departmentName})" : ""),
            route('members.index')
        );

        return redirect()->route('members.index')->with('status', 'Member deleted');
    }

    private function resolveDepartmentId(Request $request): ?int
    {
        $departmentInput = $request->input('department_id');

        if (empty($departmentInput)) {
            return null;
        }

        if ($departmentInput === '__new__') {
            $newDepartmentData = $request->validate([
                'department_name' => ['required', 'string', 'max:255'],
            ]);

            $department = Department::firstOrCreate([
                'name' => trim($newDepartmentData['department_name']),
            ]);

            return $department->id;
        }

        $existingDepartmentData = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        return (int) $existingDepartmentData['department_id'];
    }
}

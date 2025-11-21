<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Helpers\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = Member::orderBy('name')->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $member = Member::create($data);

        // Log admin activity
        AdminActivityLogger::log(
            'create',
            'Member Added',
            "New member \"{$member->name}\" was added to the library" . ($member->department ? " (Dept: {$member->department})" : ""),
            route('members.edit', $member)
        );

        return redirect()->route('members.index')->with('status', 'Member added');
    }

    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email,' . $member->id],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $member->update($data);

        // Log admin activity
        AdminActivityLogger::log(
            'update',
            'Member Updated',
            "Member \"{$member->name}\" profile was updated" . ($member->department ? " (Dept: {$member->department})" : ""),
            route('members.edit', $member)
        );

        return redirect()->route('members.index')->with('status', 'Member updated');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $name = $member->name;
        $department = $member->department;

        $member->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'delete',
            'Member Deleted',
            "Member \"{$name}\" was removed from the library" . ($department ? " (Dept: {$department})" : ""),
            route('members.index')
        );

        return redirect()->route('members.index')->with('status', 'Member deleted');
    }
}

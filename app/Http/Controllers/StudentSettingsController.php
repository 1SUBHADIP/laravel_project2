<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class StudentSettingsController extends Controller
{
    public function show(): View
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $member = Member::findOrFail($studentId);

        $systemInfo = [
            'app_version' => config('app.version', '1.0.0'),
            'laravel_version' => 'Laravel ' . app()->version(),
            'php_version' => phpversion(),
            'database_name' => config('database.default'),
        ];

        return view('student.settings.index', compact('member', 'systemInfo'));
    }
    public function updateProfile(Request $request): RedirectResponse
    {
        $studentId = $request->session()->get('student_id');
        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $member = Member::findOrFail($studentId);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email,' . $member->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $member->update($data);

        // Update session name
        $request->session()->put('student_name', $member->name);

        return back()->with('success', 'Profile updated successfully.');
    }
}

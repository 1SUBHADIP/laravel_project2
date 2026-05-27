<?php

namespace App\Helpers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class StudentSessionManager
{
    public static function restoreRememberedStudent(Request $request): ?Member
    {
        if ($request->session()->has('student_id')) {
            return Member::with('department')->find($request->session()->get('student_id'));
        }

        $rememberedStudentId = $request->cookie('student_remember');

        if (!$rememberedStudentId) {
            return null;
        }

        $member = Member::with('department')->find((int) $rememberedStudentId);

        if (!$member) {
            static::forgetRememberedStudent();

            return null;
        }

        $request->session()->regenerate();
        $request->session()->put([
            'student_id' => $member->id,
            'student_member_no' => $member->student_id,
            'student_name' => $member->name,
            'student_department_id' => $member->department_id,
            'student_department_name' => $member->department?->name,
            'student_login_time' => now(),
        ]);

        return $member;
    }

    public static function rememberStudent(Member $member): void
    {
        Cookie::queue(cookie(
            'student_remember',
            (string) $member->id,
            60 * 24 * 30,
            '/',
            null,
            config('session.secure'),
            true,
            false,
            config('session.same_site')
        ));
    }

    public static function forgetRememberedStudent(): void
    {
        Cookie::queue(Cookie::forget('student_remember'));
    }
}

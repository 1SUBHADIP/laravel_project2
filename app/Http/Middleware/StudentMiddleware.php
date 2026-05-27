<?php

namespace App\Http\Middleware;

use App\Helpers\StudentSessionManager;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $rememberedMember = StudentSessionManager::restoreRememberedStudent($request);
        $studentId = $request->session()->get('student_id');

        if (!$studentId) {
            return redirect()->route('student.login')
                ->with('error', 'Please login to access your student dashboard.');
        }

        $member = Member::with('department')->find($studentId);

        if (!$member) {
            $request->session()->forget([
                'student_id',
                'student_member_no',
                'student_name',
                'student_department_id',
                'student_department_name',
                'student_login_time',
            ]);

            return redirect()->route('student.login')
                ->with('error', 'Your student session expired. Please sign in again.');
        }

        $request->merge(['student_member' => $member]);

        return $next($request);
    }
}

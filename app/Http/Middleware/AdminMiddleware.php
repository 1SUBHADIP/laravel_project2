<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminId = $request->session()->get('admin_id');

        if (!$adminId && Auth::check()) {
            $user = Auth::user();

            if ($user && $user->is_admin) {
                $request->session()->put([
                    'admin_id' => $user->id,
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                ]);

                $adminId = $user->id;
            }
        }

        if (!$adminId) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $user = User::find($adminId);
        if (!$user || !$user->is_admin) {
            Auth::logout();
            $request->session()->forget(['admin_id', 'admin_name', 'admin_remember']);
            return redirect()->route('admin.login')
                ->with('error', 'Your session has expired or you do not have admin privileges.');
        }

        // Add user info to the request for easy access in views
        $request->merge(['admin_user' => $user]);

        return $next($request);
    }
}

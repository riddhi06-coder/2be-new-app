<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the employee portal:
 *  - only signed-in employees may pass;
 *  - if the account's password has changed since this session started
 *    (e.g. reset from another device or by an admin), the session is
 *    logged out and the user must sign in again.
 */
class EmployeeAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->hasRole('employee')) {
            return redirect()->route('frontend.employee_login');
        }

        // Password changed elsewhere -> invalidate this session.
        $sessionHash = $request->session()->get('employee_password_hash');
        if ($sessionHash !== null && ! hash_equals($sessionHash, Auth::user()->password)) {
            Auth::logout();
            return redirect()->route('frontend.employee_login')
                ->with('error', 'Your password was changed. Please sign in again.');
        }

        return $next($request);
    }
}

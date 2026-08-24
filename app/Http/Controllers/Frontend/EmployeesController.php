<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    /** Show the employee login page (redirect straight to dashboard if already signed in). */
    public function employee_login()
    {
        if (Auth::check() && Auth::user()->hasRole('employee')) {
            return redirect()->route('frontend.employee_dashboard');
        }

        return view('frontend.employee.login');
    }

    /** Handle the employee login form submission. */
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            $user = Auth::user();

            // Only employee-role accounts may use this portal.
            if (! $user->hasRole('employee')) {
                Auth::logout();
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'This login is for employees only. Please use the admin login.']);
            }

            if (! $user->is_active) {
                Auth::logout();
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your account is inactive. Please contact your administrator.']);
            }

            $request->session()->regenerate();

            return redirect()->route('frontend.employee_dashboard')->with('message', 'Welcome back, '.$user->name.'!');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'The email or password you entered is incorrect.']);
    }

    /** Show the employee forgot-password page. */
    public function employee_forgot_password()
    {
        return view('frontend.employee.employee_forgot_password');
    }

    /** Employee dashboard (only reachable by a signed-in employee). */
    public function employee_dashboard()
    {
        if (! Auth::check() || ! Auth::user()->hasRole('employee')) {
            return redirect()->route('frontend.employee_login');
        }

        return view('frontend.employee.dashboard', ['employee' => Auth::user()]);
    }

    /** Log the employee out. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.employee_login')->with('message', 'You have been logged out.');
    }
}

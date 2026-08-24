<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

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
            // Remember the password hash so we can detect a later password change.
            $request->session()->put('employee_password_hash', $user->password);

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

    /** Email a password-reset link to the employee. */
    public function send_reset_link(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Please enter your email address.',
        ]);

        // Only employee accounts can reset through this portal.
        $user = User::where('email', $request->email)->first();
        if (! $user || ! $user->hasRole('employee')) {
            return back()->withInput()->with('error', 'No employee account was found with that email address.');
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('message', 'We have emailed you a password reset link. Please check your inbox.')
            : back()->withInput()->with('error', 'Sorry, we could not send a reset link right now. Please try again in a moment.');
    }

    /** Show the reset-password form (opened from the emailed link). */
    public function employee_reset_password(Request $request, string $token)
    {
        return view('frontend.employee.employee_reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Set the new password using the reset token. */
    public function update_password(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('frontend.employee_login')
                ->with('message', 'Your password has been reset successfully. Please sign in with your new password.');
        }

        // Friendly, non-technical messages a layman user can understand.
        $friendly = match ($status) {
            Password::INVALID_TOKEN   => 'This password reset link has already been used or has expired. Please request a new one.',
            Password::INVALID_USER    => 'We could not find an account with that email address.',
            Password::RESET_THROTTLED => 'You have tried too many times. Please wait a moment and try again.',
            default                   => 'Sorry, we could not reset your password. Please request a new reset link.',
        };

        // If the link is invalid/used/expired, send them to the forgot page to request a fresh one.
        if ($status === Password::INVALID_TOKEN) {
            return redirect()->route('frontend.employee_forgot_password')->with('error', $friendly);
        }

        return back()->withInput($request->only('email'))->with('error', $friendly);
    }

    /** Employee dashboard (guarded by the employee.auth middleware). */
    public function employee_dashboard()
    {
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

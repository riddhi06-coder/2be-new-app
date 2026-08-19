<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * Manages career-portal employees. An "employee" is simply a User with the
 * `employee` role — this controller scopes every query to that role so admins
 * never appear here and can't be created/edited from the HR screen.
 */
class EmployeeController extends Controller
{
    /** Resolve the employee role (used to scope + assign). */
    private function employeeRole(): Role
    {
        return Role::where('slug', 'employee')->firstOrFail();
    }

    /** Base query: only users that hold the employee role. */
    private function employeesQuery()
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', 'employee'));
    }

    public function index()
    {
        $employees = $this->employeesQuery()->orderByDesc('id')->get();
        return view('backend.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('backend.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)],
        ]);

        $employee = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role_id'   => $this->employeeRole()->id,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Send the introductory email with the credentials the admin set.
        // A mail failure must not roll back the created account — instead we
        // leave welcome_email_sent_at null so it can be retried on first update.
        $emailSent = $this->sendWelcomeEmail($employee, $validated['password']);

        if ($emailSent) {
            $employee->forceFill(['welcome_email_sent_at' => now()])->save();
        }

        $message = $emailSent
            ? 'Employee created and welcome email sent successfully.'
            : 'Employee created, but the welcome email could not be sent. It will be retried the next time you update this employee with a password.';

        return redirect()->route('admin.employees.index')->with('message', $message);
    }

    /**
     * Email the new employee their login credentials (the plain password the
     * admin just set). Returns whether the mail was dispatched without error.
     */
    private function sendWelcomeEmail(User $employee, string $plainPassword): bool
    {
        try {
            Mail::send('emails.employee_welcome', [
                'name'     => $employee->name,
                'email'    => $employee->email,
                'password' => $plainPassword,
                'loginUrl' => route('admin.login'),
            ], function ($msg) use ($employee) {
                $msg->to($employee->email, $employee->name)
                    ->subject('Welcome to the '.config('app.name').' Employee Portal');
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Employee welcome email failed for '.$employee->email.': '.$e->getMessage());
            return false;
        }
    }

    public function edit(User $employee)
    {
        $this->ensureIsEmployee($employee);
        return view('backend.employees.edit', ['employee' => $employee]);
    }

    public function update(Request $request, User $employee)
    {
        $this->ensureIsEmployee($employee);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($employee->id)->whereNull('deleted_at')],
            'password' => ['nullable', 'string', 'confirmed', PasswordRule::min(8)],
        ]);

        $employee->name  = $validated['name'];
        $employee->email = $validated['email'];

        if (! empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->is_active = $request->boolean('is_active');
        $employee->save();

        // Retry the welcome email if it was never sent at creation. We can only
        // include credentials when a password is available on this update
        // (stored passwords are hashed and cannot be read back).
        if (is_null($employee->welcome_email_sent_at)) {
            if (! empty($validated['password'])) {
                if ($this->sendWelcomeEmail($employee, $validated['password'])) {
                    $employee->forceFill(['welcome_email_sent_at' => now()])->save();
                    return redirect()->route('admin.employees.index')
                        ->with('message', 'Employee updated and welcome email sent successfully.');
                }

                return redirect()->route('admin.employees.index')
                    ->with('message', 'Employee updated, but the welcome email still could not be sent.');
            }

            // Never sent + no password provided this time → can't send credentials yet.
            return redirect()->route('admin.employees.index')
                ->with('message', 'Employee updated. The welcome email is still pending — set a password to send their credentials.');
        }

        return redirect()->route('admin.employees.index')->with('message', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $this->ensureIsEmployee($employee);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('message', 'Employee deleted successfully.');
    }

    /** Guard so this controller can never touch a non-employee user. */
    private function ensureIsEmployee(User $user): void
    {
        abort_unless($user->hasRole('employee'), 404);
    }
}

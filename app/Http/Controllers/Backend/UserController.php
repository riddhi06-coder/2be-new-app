<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderByDesc('id')->get();
        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        // Employees are managed from the HR "Employees" section (which sends the
        // welcome email) — so exclude the employee role from the Users form.
        $roles = Role::where('is_active', true)
            ->where('slug', '!=', 'employee')
            ->orderBy('name')->get();

        return view('backend.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)],
            'role_id'  => 'required|exists:roles,id',
        ]);

        $role = Role::find($validated['role_id']);
        if (! $role || ! $role->is_active) {
            return back()->withInput()->withErrors(['role_id' => 'Selected role is not active.']);
        }
        if ($role->slug === 'employee') {
            return back()->withInput()->withErrors(['role_id' => 'Please create employees from the Employees section so they receive their welcome email.']);
        }

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role_id'   => $validated['role_id'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.users.index')->with('message', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Active non-employee roles, plus the user's current role (so the form
        // doesn't break even if their role is inactive or the employee role).
        $roles = Role::where(function ($q) {
                $q->where('is_active', true)->where('slug', '!=', 'employee');
            })
            ->orWhere('id', $user->role_id)
            ->orderBy('name')->get();

        return view('backend.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'password' => ['nullable', 'string', 'confirmed', PasswordRule::min(8)],
            'role_id'  => 'required|exists:roles,id',
        ]);

        // Block switching a user into the employee role from the Users form,
        // unless they already are one (so existing rows still save).
        $newRole = Role::find($validated['role_id']);
        if ($newRole && $newRole->slug === 'employee' && $user->role_id !== $newRole->id) {
            return back()->withInput()->withErrors(['role_id' => 'Please manage employees from the Employees section.']);
        }

        $user->name    = $validated['name'];
        $user->email   = $validated['email'];
        $user->role_id = $validated['role_id'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Don't let an admin lock themselves out by deactivating their own account
        if ($user->id !== $request->user()->id) {
            $user->is_active = $request->boolean('is_active');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('message', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('message', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin() && User::whereHas('role', fn ($q) => $q->where('slug', Role::SUPERADMIN_SLUG))->count() <= 1) {
            return back()->with('message', 'Cannot delete the only Super Admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('message', 'User deleted successfully.');
    }
}

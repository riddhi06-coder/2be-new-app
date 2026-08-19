<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // ---------- System permissions (User Management) ----------
            $permissionData = [
                // Dashboard
                ['name' => 'View Dashboard',      'slug' => 'dashboard.view',     'module' => 'Dashboard'],
                // Roles
                ['name' => 'View Roles',          'slug' => 'roles.view',         'module' => 'Roles'],
                ['name' => 'Create Roles',        'slug' => 'roles.create',       'module' => 'Roles'],
                ['name' => 'Edit Roles',          'slug' => 'roles.edit',         'module' => 'Roles'],
                ['name' => 'Delete Roles',        'slug' => 'roles.delete',       'module' => 'Roles'],
                // Users
                ['name' => 'View Users',          'slug' => 'users.view',         'module' => 'Users'],
                ['name' => 'Create Users',        'slug' => 'users.create',       'module' => 'Users'],
                ['name' => 'Edit Users',          'slug' => 'users.edit',         'module' => 'Users'],
                ['name' => 'Delete Users',        'slug' => 'users.delete',       'module' => 'Users'],
                // Permissions
                ['name' => 'View Permissions',    'slug' => 'permissions.view',   'module' => 'Permissions'],
                ['name' => 'Assign Permissions',  'slug' => 'permissions.assign', 'module' => 'Permissions'],
            ];

            foreach ($permissionData as $perm) {
                Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
            }

            // ---------- Roles ----------
            $superadmin = Role::updateOrCreate(
                ['slug' => Role::SUPERADMIN_SLUG],
                ['name' => 'Super Admin', 'description' => 'Full access to everything', 'is_protected' => true]
            );

            $admin = Role::updateOrCreate(
                ['slug' => 'admin'],
                ['name' => 'Admin', 'description' => 'Owners / HR admin access', 'is_protected' => false]
            );

            // Career-portal end user (employees who have personal documents)
            $employee = Role::updateOrCreate(
                ['slug' => 'employee'],
                ['name' => 'Employee', 'description' => 'Career portal user (own documents only)', 'is_protected' => false]
            );

            // Super Admin gets all permissions (also implicit via hasPermission(), stored for transparency)
            $superadmin->permissions()->sync(Permission::pluck('id'));

            // Admin gets read access by default; tweakable in the Permissions UI
            $admin->permissions()->sync(Permission::whereIn('slug', [
                'dashboard.view',
                'roles.view',
                'users.view',
                'users.create', 'users.edit',
                'permissions.view',
            ])->pluck('id'));

            // Employee: dashboard only by default (career-portal permissions added later)
            $employee->permissions()->sync(Permission::whereIn('slug', ['dashboard.view'])->pluck('id'));

            // ---------- Elevate the very first existing user to superadmin ----------
            $firstUser = User::orderBy('id')->first();
            if ($firstUser && ! $firstUser->role_id) {
                $firstUser->role_id = $superadmin->id;
                $firstUser->save();
            }
        });
    }
}

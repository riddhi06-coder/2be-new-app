<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Module catalog
            |--------------------------------------------------------------------------
            | Each entry => [ 'Sub-item label', 'slug-prefix', [actions], 'Group heading'? ]
            |   - 'Sub-item label' builds the permission name ("View {label}", ...).
            |   - 'slug-prefix' builds the slug ("{prefix}.{action}") — NEVER rename,
            |     route middleware + sidebar reference these.
            |   - optional 4th element is the matrix card heading (the "module"). When
            |     omitted it defaults to the label (a standalone card).
            |
            | Add new career-portal modules here as they are built (e.g. documents,
            | announcements, incident-reports, calendar, performance-reviews).
            */
            $modules = [
                // Existing 2B app modules (sidebar tabs)
                ['Disposal Details',  'manage-disposal-details', ['view', 'create', 'edit', 'delete']],
                ['Email Settings',    'manage-email-settings',   ['view', 'create', 'edit', 'delete']],
                ['Cesspool Records',  'cesspool-records',        ['view', 'edit', 'delete']],
                ['Septic Records',    'septic-records',          ['view', 'edit', 'delete']],

                // Career portal modules
                ['Employees',         'employees',               ['view', 'create', 'edit', 'delete']],

                // Documents (grouped under one "Documents" card: Folders + Files)
                ['Folders',           'document-categories',     ['view', 'create', 'edit', 'delete'], 'Documents'],
                ['Files',             'documents',               ['view', 'create', 'edit', 'delete'], 'Documents'],
            ];

            $actionLabels = [
                'view'   => 'View',
                'create' => 'Create',
                'edit'   => 'Edit',
                'delete' => 'Delete',
            ];

            foreach ($modules as $module) {
                [$moduleLabel, $prefix, $actions] = $module;
                // Optional 4th element = matrix group heading (defaults to the label).
                $group = $module[3] ?? $moduleLabel;

                foreach ($actions as $action) {
                    Permission::updateOrCreate(
                        ['slug' => $prefix.'.'.$action],
                        [
                            'name'   => $actionLabels[$action].' '.$moduleLabel,
                            'module' => $group,
                        ]
                    );
                }
            }

            // Group the system permissions (Roles/Users/Permissions) under one
            // "User Management" card, matching the sidebar. Only relabels the heading —
            // slugs and role assignments are untouched.
            Permission::whereIn('module', ['Roles', 'Users', 'Permissions'])
                ->update(['module' => 'User Management']);

            /*
            |--------------------------------------------------------------------------
            | Role assignments
            |--------------------------------------------------------------------------
            | superadmin: all permissions (hasPermission() also short-circuits to true).
            | admin     : keep existing + every non-delete action on every module.
            */

            // Super Admin -> everything
            $superadmin = Role::where('slug', Role::SUPERADMIN_SLUG)->first();
            if ($superadmin) {
                $superadmin->permissions()->sync(Permission::pluck('id'));
            }

            // Admin -> existing + view/create/edit on every module (no delete)
            $admin = Role::where('slug', 'admin')->first();
            if ($admin) {
                $adminSlugs = [];
                foreach ($modules as [, $prefix, $actions]) {
                    foreach ($actions as $action) {
                        if ($action !== 'delete') {
                            $adminSlugs[] = $prefix.'.'.$action;
                        }
                    }
                }

                $existingAdminIds = $admin->permissions()->pluck('permissions.id')->all();
                $newAdminIds      = Permission::whereIn('slug', $adminSlugs)->pluck('id')->all();
                $mergedAdminIds   = array_unique(array_merge($existingAdminIds, $newAdminIds));
                $admin->permissions()->sync($mergedAdminIds);
            }
        });
    }
}

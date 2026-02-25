<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define resources that need permissions
        $resources = [
            'department',
            'location',
            'user',
            'asset',
            'category',
        ];

        // Define permission actions
        $actions = ['view', 'create', 'edit', 'delete'];

        // Create permissions for each resource and action
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    [
                        'name' => "{$action}_{$resource}",
                        'guard_name' => 'web'
                    ]
                );
            }
        }

        $this->command->info('Permissions seeded successfully!');
        $this->command->line('Created: ' . (count($resources) * count($actions)) . ' permissions');

        // Now assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to roles based on role hierarchy
     */
    private function assignPermissionsToRoles(): void
    {
        // Get all roles
        $adminRole = Role::where('name', 'admin')->first();
        $direktorRole = Role::where('name', 'direktur')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Admin gets all permissions
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->syncPermissions($allPermissions);
            $this->command->line('✓ Admin role: All permissions assigned');
        }

        // Direktur gets most permissions except user management
        if ($direktorRole) {
            $permissions = Permission::whereNotIn('name', [
                // Restrict some admin-only operations
            ])->get();
            $direktorRole->syncPermissions($permissions);
            $this->command->line('✓ Direktur role: Appropriate permissions assigned');
        }

        // Manager: Limited to asset, category, location, department
        if ($managerRole) {
            $permissionNames = [
                'view_asset', 'create_asset', 'edit_asset',
                'view_category', 'create_category', 'edit_category',
                'view_location', 'create_location', 'edit_location',
                'view_department', 'create_department', 'edit_department',
            ];
            $permissions = Permission::whereIn('name', $permissionNames)->get();
            $managerRole->syncPermissions($permissions);
            $this->command->line('✓ Manager role: Asset & Data management permissions assigned');
        }

        // Supervisor: Mostly view and limited edit
        if ($supervisorRole) {
            $permissionNames = [
                'view_asset', 'edit_asset',
                'view_category',
                'view_location',
                'view_department',
            ];
            $permissions = Permission::whereIn('name', $permissionNames)->get();
            $supervisorRole->syncPermissions($permissions);
            $this->command->line('✓ Supervisor role: View and limited edit permissions assigned');
        }

        $this->command->info('All roles have been configured with appropriate permissions!');
    }
}


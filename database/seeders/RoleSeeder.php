<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles to create
        $roles = [
            'admin',           // System Administrator - Full access
            'direktur',        // Director - High-level management
            'manager',         // Manager - Department management
            'supervisor',      // Supervisor - Limited management
            'staff',           // Regular Staff - Basic access
            'technician',      // Technician - Maintenance operations
        ];

        // Create each role
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => 'web'
                ]
            );
        }

        $this->command->info('Roles seeded successfully!');
        $this->command->line('Created ' . count($roles) . ' roles:');
        foreach ($roles as $role) {
            $this->command->line('  ✓ ' . ucfirst($role));
        }
    }
}


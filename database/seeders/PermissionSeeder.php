<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeederCustom extends Seeder
{
    public function run()
    {
        // pastikan menyertakan guard_name jika tabel memiliki kolom ini
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123')
            ]
        );

        if (! $user->roles()->where('id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id);
        }
    }
}
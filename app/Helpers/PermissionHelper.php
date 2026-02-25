<?php

namespace App\Helpers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Permission Helper Class
 * Provides convenient methods for permission management
 */
class PermissionHelper
{
    /**
     * Get all available roles
     */
    public static function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Get all available permissions
     */
    public static function getAllPermissions()
    {
        return Permission::all();
    }

    /**
     * Get roles for a user
     */
    public static function getUserRoles(User $user)
    {
        return $user->roles;
    }

    /**
     * Get permissions for a user (including through roles)
     */
    public static function getUserPermissions(User $user)
    {
        return $user->getAllPermissions();
    }

    /**
     * Check if user has any role from list
     */
    public static function hasAnyRole(User $user, $roles)
    {
        return $user->hasAnyRole($roles);
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Check if user is director
     */
    public static function isDirector(User $user)
    {
        return $user->hasRole('direktur');
    }

    /**
     * Check if user is manager
     */
    public static function isManager(User $user)
    {
        return $user->hasRole('manager');
    }

    /**
     * Check if user is technician
     */
    public static function isTechnician(User $user)
    {
        return $user->hasRole('technician');
    }

    /**
     * Get permission description
     */
    public static function getPermissionDescription($permissionName)
    {
        $descriptions = [
            'view_asset' => 'Melihat daftar aset',
            'create_asset' => 'Membuat aset baru',
            'edit_asset' => 'Mengedit aset',
            'delete_asset' => 'Menghapus aset',
            
            'view_user' => 'Melihat daftar pengguna',
            'create_user' => 'Membuat pengguna baru',
            'edit_user' => 'Mengedit pengguna',
            'delete_user' => 'Menghapus pengguna',
            
            'view_department' => 'Melihat departemen',
            'create_department' => 'Membuat departemen baru',
            'edit_department' => 'Mengedit departemen',
            'delete_department' => 'Menghapus departemen',
            
            'view_location' => 'Melihat lokasi',
            'create_location' => 'Membuat lokasi baru',
            'edit_location' => 'Mengedit lokasi',
            'delete_location' => 'Menghapus lokasi',
            
            'view_category' => 'Melihat kategori',
            'create_category' => 'Membuat kategori baru',
            'edit_category' => 'Mengedit kategori',
            'delete_category' => 'Menghapus kategori',
        ];

        return $descriptions[$permissionName] ?? $permissionName;
    }

    /**
     * Get role description
     */
    public static function getRoleDescription($roleName)
    {
        $descriptions = [
            'admin' => 'Administrator Sistem - Akses penuh ke semua fitur',
            'direktur' => 'Direktur - Manajemen tingkat lanjut',
            'manager' => 'Manajer - Manajemen aset dan departemen',
            'supervisor' => 'Supervisor - Akses terbatas untuk pengawasan',
            'staff' => 'Staf Reguler - Akses dasar dan aset pribadi',
            'technician' => 'Teknisi - Operasi pemeliharaan dan tugas',
        ];

        return $descriptions[$roleName] ?? $roleName;
    }

    /**
     * Assign role to user (with option to clear existing)
     */
    public static function assignRole(User $user, $role, $clearExisting = false)
    {
        if ($clearExisting) {
            $user->syncRoles([$role]);
        } else {
            $user->assignRole($role);
        }
    }

    /**
     * Revoke role from user
     */
    public static function revokeRole(User $user, $role)
    {
        $user->removeRole($role);
    }

    /**
     * Give permission to user
     */
    public static function givePermission(User $user, $permission)
    {
        $user->givePermissionTo($permission);
    }

    /**
     * Revoke permission from user
     */
    public static function revokePermission(User $user, $permission)
    {
        $user->revokePermissionTo($permission);
    }

    /**
     * Sync permissions for user
     */
    public static function syncPermissions(User $user, $permissions)
    {
        $user->syncPermissions($permissions);
    }

    /**
     * Get users with specific role
     */
    public static function getUsersWithRole($role)
    {
        return Role::where('name', $role)->first()?->users ?? collect();
    }

    /**
     * Get users with specific permission
     */
    public static function getUsersWithPermission($permission)
    {
        return Permission::where('name', $permission)->first()?->users ?? collect();
    }

    /**
     * Create role with permissions
     */
    public static function createRoleWithPermissions($roleName, $permissions = [])
    {
        $role = Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'web']
        );

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    /**
     * Check if permission exists
     */
    public static function permissionExists($permissionName)
    {
        return Permission::where('name', $permissionName)->exists();
    }

    /**
     * Check if role exists
     */
    public static function roleExists($roleName)
    {
        return Role::where('name', $roleName)->exists();
    }

    /**
     * Get all permissions grouped by resource
     */
    public static function getPermissionsGrouped()
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach ($permissions as $permission) {
            // Extract resource from permission name (e.g., "view_asset" => "asset")
            preg_match('/_(.*?)$/', $permission->name, $matches);
            $resource = $matches[1] ?? 'other';

            if (!isset($grouped[$resource])) {
                $grouped[$resource] = [];
            }

            $grouped[$resource][] = $permission;
        }

        return $grouped;
    }

    /**
     * Get permissions for a role
     */
    public static function getRolePermissions($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        return $role ? $role->permissions : collect();
    }

    /**
     * Assign menu access by role
     * (Useful for quick setup)
     */
    public static function setupDefaultRolePermissions()
    {
        // Admin: All permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        // Direktur: All except user deletion
        $directorRole = Role::where('name', 'direktur')->first();
        if ($directorRole) {
            $permissions = Permission::whereNotIn('name', ['delete_user'])->get();
            $directorRole->syncPermissions($permissions);
        }

        // Manager: Asset and data management
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $permissionNames = [
                'view_asset', 'create_asset', 'edit_asset',
                'view_category', 'create_category', 'edit_category',
                'view_location', 'create_location', 'edit_location',
                'view_department', 'create_department', 'edit_department',
            ];
            $permissions = Permission::whereIn('name', $permissionNames)->get();
            $managerRole->syncPermissions($permissions);
        }

        // Supervisor: View and limited edit
        $supervisorRole = Role::where('name', 'supervisor')->first();
        if ($supervisorRole) {
            $permissionNames = [
                'view_asset', 'edit_asset',
                'view_category',
                'view_location',
                'view_department',
            ];
            $permissions = Permission::whereIn('name', $permissionNames)->get();
            $supervisorRole->syncPermissions($permissions);
        }

        return true;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\User;

class MenuService
{
    /**
     * Get all menu items the user can access
     */
    public function getAccessibleMenus(?User $user = null): Collection
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return collect([]);
        }

        $menus = config('menus.menu_items', []);
        
        return collect($menus)
            ->filter(fn($menu) => $this->userCanAccessMenu($menu, $user))
            ->map(fn($menu) => $this->processMenu($menu, $user));
    }

    /**
     * Check if a user can access a specific menu
     */
    public function userCanAccessMenu(array $menu, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        // Check roles
        $roleAccess = $this->checkRoleAccess($menu['roles'] ?? [], $user);
        
        if (!$roleAccess) {
            return false;
        }

        // Check permissions
        $permissions = $menu['permissions'] ?? [];
        if (!empty($permissions)) {
            return $user->hasAnyPermission($permissions);
        }

        return true;
    }

    /**
     * Check if user has required roles for menu
     */
    private function checkRoleAccess(array $roles, User $user): bool
    {
        if (empty($roles)) {
            return true;
        }

        return $user->hasAnyRole($roles);
    }

    /**
     * Process menu - filter submenu items based on permissions
     */
    private function processMenu(array $menu, User $user): array
    {
        // Process submenu if exists
        if (isset($menu['submenu']) && is_array($menu['submenu'])) {
            $menu['submenu'] = collect($menu['submenu'])
                ->filter(fn($submenu) => $this->userCanAccessMenu($submenu, $user))
                ->values()
                ->toArray();
        }

        return $menu;
    }

    /**
     * Check if user can access a specific feature
     */
    public function canAccessFeature(string $featureName, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        $features = config('menus.features', []);
        $requirements = $features[$featureName] ?? null;

        if ($requirements === null) {
            return false;
        }

        // If empty array, no specific requirement (just needs to be logged in)
        if (empty($requirements)) {
            return true;
        }

        // Check if it's a permission list
        if (is_array($requirements) && !empty($requirements)) {
            $isRole = in_array($requirements[0] ?? '', ['admin', 'direktur', 'manager', 'supervisor', 'staff', 'technician']);
            
            if ($isRole) {
                return $user->hasAnyRole($requirements);
            } else {
                return $user->hasAnyPermission($requirements);
            }
        }

        return true;
    }

    /**
     * Get a specific menu by ID
     */
    public function getMenuById(string $menuId, ?User $user = null): ?array
    {
        $menus = $this->getAccessibleMenus($user);
        
        return $menus->firstWhere('id', $menuId);
    }

    /**
     * Check if user can view a specific menu ID
     */
    public function canViewMenu(string $menuId, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        $menus = config('menus.menu_items', []);
        $menu = collect($menus)->firstWhere('id', $menuId);

        if (!$menu) {
            return false;
        }

        return $this->userCanAccessMenu($menu, $user);
    }

    /**
     * Get all menu items for current user (including submenus with proper nesting)
     */
    public function getMenuStructure(?User $user = null)
    {
        return $this->getAccessibleMenus($user);
    }

    /**
     * Get required permissions for a menu
     */
    public function getMenuPermissions(string $menuId): array
    {
        $menus = config('menus.menu_items', []);
        $menu = collect($menus)->firstWhere('id', $menuId);

        return $menu['permissions'] ?? [];
    }

    /**
     * Get required roles for a menu
     */
    public function getMenuRoles(string $menuId): array
    {
        $menus = config('menus.menu_items', []);
        $menu = collect($menus)->firstWhere('id', $menuId);

        return $menu['roles'] ?? [];
    }
}

# Customizing Menu Configuration - Advanced Examples

This document provides examples and patterns for customizing `config/menus.php`.

## File Location
```
config/menus.php
```

## Basic Menu Item Example

```php
[
    'id' => 'unique_id',           // Unique identifier for this menu
    'label' => 'Display Name',     // What appears in sidebar
    'route' => 'route.name',       // Laravel named route
    'icon' => 'icon_name',         // Material Design icon
    'roles' => ['admin'],          // Array of roles that can see this
    'permissions' => [],           // Optional: Additional permission check
]
```

## Real-World Examples

### 1. Simple Menu Item
```php
[
    'id' => 'dashboard',
    'label' => 'Dashboard',
    'route' => 'dashboard',
    'icon' => 'dashboard',
    'roles' => ['admin', 'direktur', 'manager', 'supervisor', 'staff'],
    'permissions' => [],  // Everyone who's logged in can see it
]
```

### 2. Multi-Role Menu
```php
[
    'id' => 'reports',
    'label' => 'Laporan',
    'route' => 'reports.index',
    'icon' => 'assessment',
    'roles' => ['admin', 'direktur', 'manager'],
    'permissions' => [],
]
```

### 3. Permission + Role Check
```php
[
    'id' => 'user_management',
    'label' => 'Manajemen User',
    'route' => 'users.index',
    'icon' => 'people',
    'roles' => ['admin', 'direktur'],
    'permissions' => ['view_user'],  // Must have BOTH role AND permission
]
```

### 4. Submenu Group
```php
[
    'id' => 'master_data',
    'label' => 'Master Data',
    'icon' => 'database',
    'roles' => ['admin', 'direktur', 'manager'],
    'permissions' => [],
    'submenu' => [
        [
            'id' => 'users',
            'label' => 'Manajemen User',
            'route' => 'users.index',
            'icon' => 'person',
            'roles' => ['admin', 'direktur'],
        ],
        [
            'id' => 'roles',
            'label' => 'Manajemen Role',
            'route' => 'roles.index',
            'icon' => 'security',
            'roles' => ['admin'],
        ],
        [
            'id' => 'departments',
            'label' => 'Departemen',
            'route' => 'departments.index',
            'icon' => 'apartment',
            'roles' => ['admin', 'direktur', 'manager'],
        ],
    ]
]
```

### 5. Nested Submenus (3 levels)
```php
[
    'id' => 'operations',
    'label' => 'Operasi',
    'icon' => 'settings',
    'roles' => ['admin', 'direktur', 'manager'],
    'submenu' => [
        [
            'id' => 'asset_ops',
            'label' => 'Operasi Aset',
            'icon' => 'inventory',
            'roles' => ['admin', 'direktur', 'manager'],
            // Note: Nested submenus not yet supported; use flat structure
        ],
    ]
]
```

## Feature Definition Examples

### 1. Permission-Based Feature
```php
'features' => [
    'asset.create' => ['create_asset'],      // Requires create_asset permission
    'asset.edit' => ['edit_asset'],          // Requires edit_asset permission
    'asset.delete' => ['delete_asset'],      // Requires delete_asset permission
]
```

### 2. Role-Based Feature
```php
'features' => [
    'maintenance.assign' => ['admin', 'direktur'],  // Only admin or direktur
    'asset.import' => ['admin'],                     // Admin only
    'report.export' => ['admin', 'direktur'],       // Multiple roles
]
```

### 3. No Restriction (Logged-in users only)
```php
'features' => [
    'dashboard.view' => [],                  // Empty = all authenticated users
    'asset.view' => [],                      // Open to all authenticated users
]
```

## Real-World Scenario: Manufacturing Plant

```php
return [
    'menu_items' => [
        // Main dashboard - everyone
        [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
            'roles' => ['admin', 'plant_manager', 'supervisor', 'operator', 'technician'],
            'permissions' => [],
        ],

        // Production floor - operators and managers
        [
            'id' => 'production',
            'label' => 'Produksi',
            'route' => 'production.index',
            'icon' => 'factory',
            'roles' => ['admin', 'plant_manager', 'supervisor', 'operator'],
            'permissions' => [],
        ],

        // Maintenance - technicians and managers
        [
            'id' => 'maintenance',
            'label' => 'Pemeliharaan',
            'route' => 'maintenance.index',
            'icon' => 'build',
            'roles' => ['admin', 'plant_manager', 'supervisor', 'technician'],
            'permissions' => [],
        ],

        // Inventory - managers only
        [
            'id' => 'inventory',
            'label' => 'Inventaris',
            'route' => 'inventory.index',
            'icon' => 'storage',
            'roles' => ['admin', 'plant_manager'],
            'permissions' => ['view_inventory'],
        ],

        // Administration
        [
            'id' => 'admin',
            'label' => 'Administrasi',
            'icon' => 'admin_panel_settings',
            'roles' => ['admin'],
            'permissions' => [],
            'submenu' => [
                [
                    'id' => 'users',
                    'label' => 'Manajemen User',
                    'route' => 'users.index',
                    'icon' => 'people',
                    'roles' => ['admin'],
                ],
                [
                    'id' => 'roles',
                    'label' => 'Manajemen Role',
                    'route' => 'roles.index',
                    'icon' => 'security',
                    'roles' => ['admin'],
                ],
                [
                    'id' => 'permissions',
                    'label' => 'Manajemen Permission',
                    'route' => 'permissions.index',
                    'icon' => 'lock',
                    'roles' => ['admin'],
                ],
                [
                    'id' => 'reports',
                    'label' => 'Laporan Sistem',
                    'route' => 'admin.reports',
                    'icon' => 'assessment',
                    'roles' => ['admin'],
                ],
            ],
        ],
    ],

    'features' => [
        // Production features
        'production.start' => ['operator'],
        'production.pause' => ['supervisor', 'operator'],
        'production.stop' => ['supervisor'],
        'production.report' => ['plant_manager'],

        // Maintenance features
        'maintenance.create' => ['technician', 'supervisor'],
        'maintenance.complete' => ['technician'],
        'maintenance.schedule' => ['plant_manager'],

        // Inventory
        'inventory.create' => ['plant_manager'],
        'inventory.edit' => ['plant_manager'],
        'inventory.delete' => ['admin'],
        'inventory.export' => ['plant_manager'],
    ],
];
```

## Custom Permission Names

Instead of generated permissions, you might use custom names:

```php
return [
    'features' => [
        // Custom permission names
        'asset.can_transfer' => ['can_transfer_assets'],
        'asset.can_write_off' => ['can_write_off_assets'],
        'maintenance.can_schedule' => ['can_schedule_maintenance'],
        'maintenance.can_approve' => ['can_approve_maintenance'],
        'report.can_view_sensitive' => ['can_view_sensitive_reports'],
    ],
];
```

Then create these permissions:
```php
// In seeder
foreach ([
    'can_transfer_assets',
    'can_write_off_assets',
    'can_schedule_maintenance',
    'can_approve_maintenance',
    'can_view_sensitive_reports',
] as $permission) {
    Permission::firstOrCreate([
        'name' => $permission,
        'guard_name' => 'web'
    ]);
}
```

## Conditional Menu Based on Tenant/Branch

```php
// For multi-tenant systems
$tenantMenus = [];

if (auth()->user()->isHeadOffice()) {
    $tenantMenus[] = [
        'id' => 'all_branches',
        'label' => 'Semua Cabang',
        'route' => 'branches.index',
        'icon' => 'location_city',
        'roles' => ['admin', 'direktur'],
        'permissions' => [],
    ];
}

if (auth()->user()->isBranchManager()) {
    $tenantMenus[] = [
        'id' => 'my_branch',
        'label' => 'Cabang Saya',
        'route' => 'branch.dashboard',
        'icon' => 'store',
        'roles' => ['admin', 'branch_manager'],
        'permissions' => [],
    ];
}

// Merge with other menus
$menus = array_merge($defaultMenus, $tenantMenus);
```

## Dynamic Menu Building in Controller

Instead of config, you could build in a service:

```php
// app/Services/DynamicMenuService.php
class DynamicMenuService
{
    public function buildMenus(User $user)
    {
        $menus = [];
        
        // All users get dashboard
        $menus[] = [
            'id' => 'dashboard',
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'dashboard',
        ];

        // Add role-specific menus
        if ($user->hasRole('admin')) {
            $menus = array_merge($menus, $this->getAdminMenus());
        }
        
        if ($user->hasRole('manager')) {
            $menus = array_merge($menus, $this->getManagerMenus());
        }

        // Add feature-specific menus
        if ($user->can('report_access')) {
            $menus[] = [
                'id' => 'reports',
                'label' => 'Reports',
                'route' => 'reports.index',
                'icon' => 'assessment',
            ];
        }

        return $menus;
    }

    private function getAdminMenus()
    {
        return [
            [
                'id' => 'user_management',
                'label' => 'User Management',
                'route' => 'users.index',
                'icon' => 'people',
            ],
            // ... more admin menus
        ];
    }

    private function getManagerMenus()
    {
        return [
            [
                'id' => 'department_dashboard',
                'label' => 'Department',
                'route' => 'department.dashboard',
                'icon' => 'apartment',
            ],
            // ... more manager menus
        ];
    }
}
```

## Performance Optimization

For large menu structures, cache them:

```php
// In MenuService or a service provider
public function getAccessibleMenus(?User $user = null): Collection
{
    $user = $user ?? auth()->user();
    
    // Cache key
    $cacheKey = 'menus_' . $user->id;
    
    // Return cached if exists
    return Cache::remember($cacheKey, 3600, function () use ($user) {
        $menus = config('menus.menu_items', []);
        
        return collect($menus)
            ->filter(fn($menu) => $this->userCanAccessMenu($menu, $user))
            ->map(fn($menu) => $this->processMenu($menu, $user));
    });
}
```

Clear cache when permissions change:
```php
// In a seeder or command
Cache::tags(['menus'])->flush();

// Or specifically
Cache::forget('menus_' . auth()->id());
```

## Icon Reference

Material Design icons available:
- admin_panel_settings
- apartment
- assess ment
- backpack
- build
- category
- compare_arrows
- dashboard
- database
- event_repeat
- factory
- handyman
- inventory_2
- lock
- location_on
- people
- person
- security
- settings
- storage
- task_alt
- trending_up

See: https://fonts.google.com/icons

## Testing Menu Configuration

```php
// In tests
use App\Services\MenuService;

it('user can access correct menus', function () {
    $user = User::factory()->create();
    $user->assignRole('manager');
    
    $menuService = app(MenuService::class);
    $menus = $menuService->getAccessibleMenus($user);
    
    expect($menus->pluck('id')->toArray())
        ->toContain('dashboard', 'asset_management')
        ->not()->toContain('user_management'); // Manager shouldn't see this
});
```

---

## Summary

The menu configuration system is flexible and supports:
- ✅ Simple menu items
- ✅ Multi-level submenus
- ✅ Role-based access
- ✅ Permission-based access
- ✅ Combined role + permission checks
- ✅ Feature-level permissions
- ✅ Dynamic menu building
- ✅ Caching for performance
- ✅ Easy customization

For your specific needs, choose the pattern that fits best!

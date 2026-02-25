# Permission & Menu Management System

## Overview
This document explains the new permission-based menu system implemented for the RSMM Asset Management application. The system uses Spatie Laravel Permission for role-based access control and provides a flexible configuration-driven approach to manage menus and features.

## Components

### 1. **Menu Configuration** (`config/menus.php`)
Centralized configuration file that defines:
- **Menu Items**: All available menus with their properties
- **Features**: Specific features and their permission requirements

```php
// Example menu configuration
[
    'id' => 'asset_management',
    'label' => 'Manajemen Asset',
    'route' => 'assets.index',
    'icon' => 'inventory_2',
    'roles' => ['admin', 'direktur', 'manager'],
    'permissions' => ['view_asset'],
]
```

### 2. **MenuService** (`app/Services/MenuService.php`)
Core service that handles:
- Getting accessible menus for current user
- Checking menu/feature access
- Filtering submenus based on permissions
- Managing role and permission validation

**Key Methods:**
```php
// Get all accessible menus
$menus = app(MenuService::class)->getAccessibleMenus();

// Check if user can view a menu
$canView = app(MenuService::class)->canViewMenu('asset_management');

// Check if user can access a feature
$canCreate = app(MenuService::class)->canAccessFeature('asset.create');
```

### 3. **Blade Directives** (Registered in `AppServiceProvider`)
Four custom directives for templates:

```blade
<!-- Check menu access -->
@canViewMenu('asset_management')
    <!-- Content shown only if user can view this menu -->
@endcanViewMenu

<!-- Check feature access -->
@canAccessFeature('asset.create')
    <!-- Show create button only if permitted -->
@endcanAccessFeature

<!-- Check specific role -->
@hasRole('admin')
    <!-- Admin-only content -->
@endhasRole

<!-- Check specific permission -->
@hasPermission('edit_asset')
    <!-- Content for users with edit_asset permission -->
@endhasPermission
```

### 4. **Middleware** 
Two middleware classes for route protection:

**CheckMenuAccess** - Protect routes by menu ID:
```php
Route::get('/assets', [AssetController::class, 'index'])
    ->middleware('check.menu:asset_management');
```

**CheckFeatureAccess** - Protect routes by feature:
```php
Route::post('/assets', [AssetController::class, 'store'])
    ->middleware('check.feature:asset.create');
```

## Role Hierarchy

### Role Definitions
- **Admin**: Full system access, all menus and features
- **Direktur (Director)**: High-level management, most features except user administration
- **Manager**: Asset and data management
- **Supervisor**: Limited editing, mostly view-only
- **Staff**: Basic access, view own assets
- **Technician**: Maintenance operations and task management

### Default Permissions by Role

| Permission | Admin | Direktur | Manager | Supervisor | Staff | Technician |
|-----------|-------|----------|---------|-----------|-------|-----------|
| view_asset | ✓ | ✓ | ✓ | ✓ | ✓ | |
| create_asset | ✓ | ✓ | ✓ | | | |
| edit_asset | ✓ | ✓ | ✓ | ✓ | | |
| delete_asset | ✓ | ✓ | | | | |
| view_user | ✓ | ✓ | | | | |
| create_user | ✓ | ✓ | | | | |
| edit_user | ✓ | ✓ | | | | |
| delete_user | ✓ | | | | | |
| view_department | ✓ | ✓ | ✓ | ✓ | | |
| create_department | ✓ | ✓ | ✓ | | | |
| edit_department | ✓ | ✓ | ✓ | | | |
| delete_department | ✓ | | | | | |

## Implementation Guide

### 1. Setup (One-time)
```bash
# Ensure migrations are run
php artisan migrate

# Seed roles and permissions
php artisan db:seed RoleSeeder
php artisan db:seed PermissionSeeder

# Create admin user
php artisan db:seed AdminUserSeederCustom
```

### 2. Adding New Menu Items
Edit `config/menus.php`:

```php
'menu_items' => [
    // ... existing items ...
    [
        'id' => 'reports',
        'label' => 'Laporan',
        'route' => 'reports.index',
        'icon' => 'assessment',
        'roles' => ['admin', 'direktur', 'manager'],
        'permissions' => [], // Optional: empty or specific permission
    ],
]
```

### 3. Adding New Features
Edit `config/menus.php` in the `features` section:

```php
'features' => [
    // ... existing features ...
    'report.export' => ['admin', 'direktur', 'manager'],
    'report.schedule' => [],  // No specific requirement
]
```

### 4. Protecting Routes
In `routes/web.php`:

```php
// Protect by menu
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('check.menu:reports');

// Protect by feature
Route::post('/reports/export', [ReportController::class, 'export'])
    ->middleware('check.feature:report.export');

// Using action-based permissions (Spatie built-in)
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('permission:view_report');
```

### 5. Using in Views/Blade
```blade
<!-- Sidebar example (auto-generated from MenuService) -->
<nav>
    @php
        $menus = app(\App\Services\MenuService::class)->getAccessibleMenus();
    @endphp
    
    @foreach($menus as $menu)
        @if(isset($menu['route']))
            <a href="{{ route($menu['route']) }}">
                {{ $menu['label'] }}
            </a>
        @endif
    @endforeach
</nav>

<!-- Feature-based UI elements -->
<div class="actions">
    @canAccessFeature('asset.create')
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            Tambah Asset
        </a>
    @endcanAccessFeature
    
    @canAccessFeature('asset.export')
        <button onclick="exportAssets()" class="btn btn-secondary">
            Ekspor
        </button>
    @endcanAccessFeature
</div>

<!-- Role-based content -->
@hasRole(['admin', 'direktur'])
    <div class="admin-panel">
        <!-- Admin-only content -->
    </div>
@endhasRole
```

### 6. Creating Custom Roles
Via database seeder (`database/seeders`):
```php
public function run(): void
{
    $role = Role::create(['name' => 'custom_role', 'guard_name' => 'web']);
    
    $permissions = Permission::whereIn('name', [
        'view_asset',
        'create_asset',
        'edit_asset',
    ])->get();
    
    $role->syncPermissions($permissions);
}
```

### 7. Assigning Roles/Permissions to Users
```php
// In Controller or Model
$user = User::find($id);

// Assign role
$user->assignRole('manager');

// Assign multiple roles
$user->syncRoles(['manager', 'supervisor']);

// Give direct permission
$user->givePermissionTo('edit_asset');

// Check permissions
if ($user->hasPermissionTo('delete_asset')) {
    // Allow deletion
}
```

## Advanced Usage

### Custom Permission Checks in Controllers
```php
public function destroy(Asset $asset)
{
    if (!auth()->user()->hasPermissionTo('delete_asset')) {
        abort(403, 'Anda tidak memiliki izin menghapus aset.');
    }
    
    $asset->delete();
}
```

### Using MenuService in Controllers
```php
use App\Services\MenuService;

class AssetController extends Controller
{
    public function __construct(private MenuService $menuService)
    {
    }
    
    public function create()
    {
        if (!$this->menuService->canAccessFeature('asset.create')) {
            abort(403);
        }
        
        return view('assets.create');
    }
}
```

### Querying Available Menus
```php
// Get menus for current user
$menus = app(MenuService::class)->getMenuStructure();

// Check specific menu
$menu = app(MenuService::class)->getMenuById('asset_management');

// Get menu requirements
$roles = app(MenuService::class)->getMenuRoles('asset_management');
$permissions = app(MenuService::class)->getMenuPermissions('asset_management');
```

## Updating Existing Routes

To add permission checks to existing routes:

```php
// routes/web.php
Route::middleware(['auth'])->prefix('master-data')->group(function () {
    // Add middleware to protect sensitive operations
    Route::resource('assets', AssetController::class)
        ->middleware('check.menu:asset_management');
    
    Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
        ->middleware('check.feature:asset.delete');
});
```

## Commands to Remember

```bash
# Reseed permissions (careful - clears existing role permissions)
php artisan db:seed PermissionSeeder

# Clear all roles and permissions (destructive!)
php artisan cache:forget spatie.permission.cache

# Cache permissions for better performance
php artisan optimize
```

## Troubleshooting

### Issue: Menu not showing up
1. Check if user has required role in `config/menus.php`
2. Verify user has assigned role: `$user->roles`
3. Check permissions: `$user->permissions`
4. Clear cache: `php artisan cache:clear`

### Issue: Permission check always false
1. Verify permission exists in database
2. Check if role-permission mapping exists
3. Verify `guard_name` is 'web' in database

### Issue: Middleware throwing 403
1. Check user has required role/permission
2. Verify middleware alias is registered in `bootstrap/app.php`
3. Test with: `php artisan tinker` → `auth()->user()->hasRole('admin')`

## Best Practices

1. **Use menu configuration** for navigation items rather than hardcoding
2. **Check permissions in views** to hide UI elements (UX), not for security
3. **Always protect routes** with appropriate middleware
4. **Use feature checks** for complex business logic permissions
5. **Cache permissions** in production for better performance
6. **Document custom roles** and their intended use
7. **Regularly audit** role-permission assignments
8. **Test permission scenarios** as part of your QA process

## Performance Considerations

- MenuService caches role/permission lookups
- Spatie Permission includes caching support
- In production, use: `php artisan cache:clear` after permission changes
- Large role/permission sets: Consider pagination in admin UI

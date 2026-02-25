# Permission System - Quick Reference

## Installation & Setup

```bash
# 1. Ensure database migrations are done
php artisan migrate

# 2. Seed the initial data
php artisan db:seed RoleSeeder
php artisan db:seed PermissionSeeder

# 3. Create default admin user
php artisan db:seed AdminUserSeederCustom
```

## Blade Directives - Quick Examples

```blade
{{-- Show menu only if user can access --}}
@canViewMenu('asset_management')
    <a href="{{ route('assets.index') }}">Manajemen Asset</a>
@endcanViewMenu

{{-- Show button only if feature is allowed --}}
@canAccessFeature('asset.create')
    <button class="btn-primary">Tambah Asset</button>
@endcanAccessFeature

{{-- Content for specific roles --}}
@hasRole('admin')
    <div class="admin-only">Admin Panel</div>
@endhasRole

{{-- Content for specific permissions --}}
@hasPermission('delete_asset')
    <button onclick="deleteAsset()">Delete</button>
@endhasPermission
```

## Route Protection Examples

```php
// Protect entire resource with menu access
Route::resource('assets', AssetController::class)
    ->middleware(['auth', 'check.menu:asset_management']);

// Protect specific action with feature check
Route::post('assets/import', [AssetController::class, 'processImport'])
    ->middleware('check.feature:asset.import');

// Using Spatie's built-in permission middleware
Route::delete('assets/{asset}', [AssetController::class, 'destroy'])
    ->middleware('permission:delete_asset');
```

## MenuService Methods

```php
use App\Services\MenuService;

$service = app(MenuService::class);

// Get all accessible menus
$menus = $service->getAccessibleMenus();
$menus = $service->getAccessibleMenus($user);

// Check menu access
$canView = $service->canViewMenu('asset_management');
$canView = $service->canViewMenu('asset_management', $user);

// Check feature access
$canCreate = $service->canAccessFeature('asset.create');
$canCreate = $service->canAccessFeature('asset.create', $user);

// Get menu info
$menu = $service->getMenuById('asset_management');
$roles = $service->getMenuRoles('asset_management');
$permissions = $service->getMenuPermissions('asset_management');

// Get complete menu structure
$structure = $service->getMenuStructure();
```

## Managing Roles & Permissions

```php
// Assign role to user
$user->assignRole('manager');
$user->assignRole(['manager', 'supervisor']);
$user->syncRoles(['manager']);  // Removes other roles

// Check roles
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'direktur']);
$user->hasAllRoles(['admin', 'direktur']);

// Give direct permission (bypasses roles)
$user->givePermissionTo('edit_asset');
$user->givePermissionTo(['edit_asset', 'delete_asset']);

// Remove permission
$user->revokePermissionTo('delete_asset');

// Check permissions
$user->hasPermissionTo('edit_asset');
$user->hasAnyPermission(['edit_asset', 'delete_asset']);
$user->hasAllPermissions(['edit_asset', 'delete_asset']);
```

## Configuration File Structure

### Menu Item Structure
```php
[
    'id' => 'unique_menu_id',           // Required: Unique identifier
    'label' => 'Menu Label',            // Required: Display text
    'route' => 'route.name',            // Required: Named route
    'icon' => 'material_icon',          // Required: Material icon name
    'roles' => ['admin', 'manager'],    // Required: Allowed roles
    'permissions' => ['view_asset'],    // Optional: Additional permission check
    'submenu' => [                      // Optional: Submenu items
        [
            'id' => 'submenu_id',
            'label' => 'Submenu Label',
            'route' => 'submenu.route',
            'icon' => 'icon_name',
            'roles' => ['admin', 'manager'],
        ]
    ]
]
```

### Feature Structure
```php
'features' => [
    'asset.create' => ['create_asset'],          // Requires permission
    'asset.delete' => ['admin'],                 // Requires role
    'maintenance.assign' => [],                  // All authenticated users
    'report.export' => ['admin', 'direktur'],   // Multiple roles
]
```

## Default Roles

- **admin**: Full system access
- **direktur**: Director level access
- **manager**: Department/asset management
- **supervisor**: Limited management
- **staff**: Basic employee access
- **technician**: Maintenance operations

## Common Permissions

```
view_asset      - View asset list and details
create_asset    - Create new assets
edit_asset      - Edit existing assets
delete_asset    - Delete assets

view_user       - View users
create_user     - Create users
edit_user       - Edit users
delete_user     - Delete users

view_department - View departments
create_department - Create departments
edit_department - Edit departments
delete_department - Delete departments

view_location   - View locations
create_location - Create locations
edit_location   - Edit locations
delete_location - Delete locations

view_category   - View categories
create_category - Create categories
edit_category   - Edit categories
delete_category - Delete categories
```

## Testing Permissions

```php
// In Tinker or tests
php artisan tinker

// Get user
$user = User::find(1);

// Check what roles they have
$user->getRoleNames();

// Check what permissions they have
$user->getAllPermissions();
$user->getDirectPermissions();

// Check specific role
$user->hasRole('admin');

// Check specific permission
$user->hasPermissionTo('edit_asset');

// Get accessible menus
app(\App\Services\MenuService::class)->getAccessibleMenus($user);

// Check feature access
app(\App\Services\MenuService::class)->canAccessFeature('asset.create', $user);
```

## Customizing Permissions

### Adding New Permission
```php
// In seeder or migrations
use Spatie\Permission\Models\Permission;

Permission::create([
    'name' => 'export_report',
    'guard_name' => 'web'
]);
```

### Creating Custom Role
```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::create([
    'name' => 'custom_role',
    'guard_name' => 'web'
]);

// Assign permissions
$permissions = Permission::whereIn('name', [
    'view_asset',
    'create_asset',
])->get();

$role->syncPermissions($permissions);
```

### Assigning Permissions to Role
```php
$role = Role::where('name', 'manager')->first();

// Add permission
$role->givePermissionTo('edit_asset');

// Add multiple
$role->syncPermissions(['view_asset', 'create_asset', 'edit_asset']);

// Remove permission
$role->revokePermissionTo('delete_asset');
```

## Performance Tips

1. **Cache permissions** after changes: `php artisan cache:clear`
2. **Use lazy loading** for large datasets in admin panels
3. **Batch assign** permissions when possible
4. **Check in view** for UX (hide buttons), protect routes for security
5. **Profile** slow permission checks in production

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Menu not showing | Check role assignment and config |
| Permission denied | Verify role-permission mapping |
| Cached old permissions | Run `php artisan cache:clear` |
| 403 on route | Check middleware and role/permission |
| User can't see menu | Verify `config/menus.php` roles |

## Next Steps

1. Review `config/menus.php` and customize for your needs
2. Assign roles to existing users
3. Update routes that need protection
4. Update views to conditionally show/hide elements
5. Test permission scenarios thoroughly
